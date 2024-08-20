<?php
    session_start();
    require_once 'db.php';

    function registerUser($name, $email, $password) {
        $db = new DB();
        $conn = $db->connect();

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    function loginUser($email, $password) {
        $db = new DB();
        $conn = $db->connect();

        $query = "SELECT * FROM users WHERE email = :email";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            return true;
        } else {
            return false;
        }
    }

    function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    function logoutUser() {
        session_unset();
        session_destroy();
        header("Location: login.php");
    }

    function getAccountBalance($user_id) {
        $db = new DB();
        $conn = $db->connect();
    
        $query = "SELECT balance FROM accounts WHERE user_id = :user_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
    
        $account = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($account) {
            return $account['balance'];
        } else {
            // Return a default value if no account is found
            return 0.00;
        }
    }

    function deposit($user_id, $amount) {
        if ($amount <= 0) {
            return false;
        }

        $db = new DB();
        $conn = $db->connect();

        $balance = getAccountBalance($user_id);
        $new_balance = $balance + $amount;

        $conn->beginTransaction();

        try {
            $update_query = "UPDATE accounts SET balance = :balance WHERE user_id = :user_id";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bindParam(':balance', $new_balance);
            $update_stmt->bindParam(':user_id', $user_id);
            $update_stmt->execute();

            $transaction_query = "INSERT INTO transactions (account_id, type, amount)
                                    VALUES ((SELECT account_id FROM accounts WHERE user_id = :user_id), 'deposit', :amount)";
            $transaction_stmt = $conn->prepare($transaction_query);
            $transaction_stmt->bindParam(':user_id', $user_id);
            $transaction_stmt->bindParam(':amount', $amount);
            $transaction_stmt->execute();

            $conn->commit();
            return true;
        } catch (Exception $e) {
            $conn->rollback();
            return false;
        }
    }

    function withdraw($user_id, $amount) {
        if ($amount <= 0) {
            return false;
        }

        $db = new DB();
        $conn = $db->connect();

        $balance = getAccountBalance($user_id);

        if ($balance < $amount) {
            return false;
        }

        $new_balance = $balance - $amount;

        $conn->beginTransaction();

        try {
            $update_query = "UPDATE accounts SET balance = :balance WHERE user_id = :user_id";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bindParam(':balance', $new_balance);
            $update_stmt->bindParam(':user_id', $user_id);
            $update_stmt->execute();

            $transaction_query = "INSERT INTO transactions (account_id, type, amount) 
                                    VALUES ((SELECT account_id FROM accounts WHERE user_id = :user_id), 'withdrawal', :amount)";
            $transaction_stmt = $conn->prepare($transaction_query);
            $transaction_stmt->bindParam(':user_id', $user_id);
            $transaction_stmt->bindParam(':amount', $amount);
            $transaction_stmt->execute();

            $conn->commit();
            return true;
        } catch (Exception $e) {
            $conn->rollback();
            return false;
        }
    }
?>