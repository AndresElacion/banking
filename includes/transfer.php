<?php
require_once '../includes/auth.php';
require_once '../includes/db.php'; // Assuming you have a DB class

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sender_id = $_SESSION['user_id'];
    $receiver_account_number = str_replace(' ', '', htmlspecialchars(trim($_POST['receiver_account_number'])));
    $amount = $_POST['amount'];

    $db = new DB();
    $conn = $db->connect();

    // Get sender's account
    $sender_account_query = "SELECT account_number, balance FROM accounts WHERE user_id = :user_id";
    $sender_stmt = $conn->prepare($sender_account_query);
    $sender_stmt->bindParam(':user_id', $sender_id);
    $sender_stmt->execute();
    $sender_account = $sender_stmt->fetch(PDO::FETCH_ASSOC);

    if ($sender_account && $sender_account['balance'] >= $amount) {
        // Get the sender's account number
        $fromAccountNumber = $sender_account['account_number'];

        // Check if receiver's account exists
        $receiver_account_query = "SELECT account_number FROM accounts WHERE account_number = :account_number";
        $receiver_stmt = $conn->prepare($receiver_account_query);
        $receiver_stmt->bindParam(':account_number', $receiver_account_number);
        $receiver_stmt->execute();
        $receiver_account = $receiver_stmt->fetch(PDO::FETCH_ASSOC);

        if ($receiver_account) {
            // Receiver's account exists, proceed with the transfer
            $toAccountNumber = $receiver_account['account_number'];

            // Use the transferAmount function to handle the transfer
            $transfer_success = transferAmount($fromAccountNumber, $toAccountNumber, $amount);

            if ($transfer_success) {
                echo "Transfer successful!";
            } else {
                echo "Transfer failed!";
            }
        } else {
            echo "Receiver's account not found.";
        }
    } else {
        echo "Insufficient balance or sender's account not found.";
    }
}

// Function to handle the transfer and save transactions to the database
function transferAmount($fromAccountNumber, $toAccountNumber, $amount) {
    if ($amount <= 0) {
        return false;
    }

    $db = new DB();
    $conn = $db->connect();

    $conn->beginTransaction();

    try {
        // Deduct from the sender's account
        $deduct_query = "UPDATE accounts SET balance = balance - :amount WHERE account_number = :from_account AND balance >= :amount";
        $deduct_stmt = $conn->prepare($deduct_query);
        $deduct_stmt->bindParam(':amount', $amount);
        $deduct_stmt->bindParam(':from_account', $fromAccountNumber);
        $deduct_stmt->execute();

        if ($deduct_stmt->rowCount() === 0) {
            throw new Exception("Insufficient funds or account not found.");
        }

        // Add to the receiver's account
        $add_query = "UPDATE accounts SET balance = balance + :amount WHERE account_number = :to_account";
        $add_stmt = $conn->prepare($add_query);
        $add_stmt->bindParam(':amount', $amount);
        $add_stmt->bindParam(':to_account', $toAccountNumber);
        $add_stmt->execute();

        // Log the transfer out transaction
        $logOutQuery = "INSERT INTO transactions (account_id, type, amount, from_account_number, to_account_number) 
                        VALUES ((SELECT account_id FROM accounts WHERE account_number = :from_account), 'transfer_out', :amount, :from_account, :to_account)";
        $logOutStmt = $conn->prepare($logOutQuery);
        $logOutStmt->bindParam(':from_account', $fromAccountNumber);
        $logOutStmt->bindParam(':to_account', $toAccountNumber);
        $logOutStmt->bindParam(':amount', $amount);
        $logOutStmt->execute();

        // Log the transfer in transaction
        $logInQuery = "INSERT INTO transactions (account_id, type, amount, from_account_number, to_account_number) 
                       VALUES ((SELECT account_id FROM accounts WHERE account_number = :to_account), 'transfer_in', :amount, :from_account, :to_account)";
        $logInStmt = $conn->prepare($logInQuery);
        $logInStmt->bindParam(':to_account', $toAccountNumber);
        $logInStmt->bindParam(':from_account', $fromAccountNumber);
        $logInStmt->bindParam(':amount', $amount);
        $logInStmt->execute();

        // Commit the transaction
        $conn->commit();
        return true;
    } catch (Exception $e) {
        $conn->rollback();
        return false;
    }
}

?>
