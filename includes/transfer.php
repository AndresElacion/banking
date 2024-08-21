<?php
    require_once '../includes/auth.php';
    require_once '../includes/db.php'; // Assuming you have a DB class

    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $sender_id = $_SESSION['user_id'];
        $receiver_account_number = $_POST['receiver_account_number'];
        $amount = $_POST['amount'];

        $db = new DB();
        $conn = $db->connect();

        // Get sender's account
        $sender_account_query = "SELECT account_number, balance FROM accounts WHERE user_id = :user_id";
        $sender_stmt = $conn->prepare($sender_account_query);
        $sender_stmt->bindParam(':user_id', $sender_id);
        $sender_stmt->execute();
        $sender_account = $sender_stmt->fetch(PDO::FETCH_ASSOC);

        if ($sender_account['balance'] >= $amount) {
            // Get receiver's account
            $receiver_account_query = "SELECT user_id, balance FROM accounts WHERE account_number = :account_number";
            $receiver_stmt = $conn->prepare($receiver_account_query);
            $receiver_stmt->bindParam(':account_number', $receiver_account_number);
            $receiver_stmt->execute();
            $receiver_account = $receiver_stmt->fetch(PDO::FETCH_ASSOC);

            if ($receiver_account) {
                $conn->beginTransaction();
                try {
                    // Deduct from sender
                    $new_sender_balance = $sender_account['balance'] - $amount;
                    $update_sender_query = "UPDATE accounts SET balance = :balance WHERE user_id = :user_id";
                    $update_sender_stmt = $conn->prepare($update_sender_query);
                    $update_sender_stmt->bindParam(':balance', $new_sender_balance);
                    $update_sender_stmt->bindParam(':user_id', $sender_id);
                    $update_sender_stmt->execute();

                    // Add to receiver
                    $new_receiver_balance = $receiver_account['balance'] + $amount;
                    $update_receiver_query = "UPDATE accounts SET balance = :balance WHERE user_id = :user_id";
                    $update_receiver_stmt = $conn->prepare($update_receiver_query);
                    $update_receiver_stmt->bindParam(':balance', $new_receiver_balance);
                    $update_receiver_stmt->bindParam(':user_id', $receiver_account['user_id']);
                    $update_receiver_stmt->execute();

                    // Commit the transaction
                    $conn->commit();
                    echo "Transfer successful!";
                } catch (Exception $e) {
                    $conn->rollback();
                    echo "Transfer failed!";
                }
            } else {
                echo "Receiver's account not found.";
            }
        } else {
            echo "Insufficient balance.";
        }
    }
?>
