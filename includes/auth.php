<?php
    session_start();
    require_once 'db.php';

    function registerUser($name, $email, $password) {
        $db = new DB();
        $conn = $db->connect();
    
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
        $conn->beginTransaction();
    
        try {
            // Insert the new user
            $query = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword);
    
            if ($stmt->execute()) {
                // Get the last inserted user_id
                $user_id = $conn->lastInsertId();

                // Generate card details
                $account_number = generateUniqueAccountNumber();
                $expiration_date = generateExpirationDate();
                $cvv = generateCVV();
    
                // Create an account for the new user
                $account_query = "INSERT INTO accounts (user_id, balance, account_number, expiration_date, cvv) VALUES (:user_id, 0, :account_number, :expiration_date, :cvv)";
                $account_stmt = $conn->prepare($account_query);
                $account_stmt->bindParam(':user_id', $user_id);
                $account_stmt->bindParam(':account_number', $account_number);
                $account_stmt->bindParam(':expiration_date', $expiration_date);
                $account_stmt->bindParam(':cvv', $cvv);
                $account_stmt->execute();
    
                $conn->commit();
                return true;
            } else {
                $conn->rollback();
                return false;
            }
        } catch (Exception $e) {
            $conn->rollback();
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
            $_SESSION['user_email'] = $user['email'];
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
            echo "Invalid amount";
            return false;
        }
    
        $db = new DB();
        $conn = $db->connect();
    
        $balance = getAccountBalance($user_id);
        echo "Current Balance: " . $balance;
    
        $new_balance = $balance + $amount;
        echo "New Balance: " . $new_balance;
    
        $conn->beginTransaction();
    
        try {
            $update_query = "UPDATE accounts SET balance = :balance WHERE user_id = :user_id";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bindParam(':balance', $new_balance);
            $update_stmt->bindParam(':user_id', $user_id);
            $update_stmt->execute();
            echo "Balance updated successfully.";
    
            // Fetch the account_id first
            $account_query = "SELECT account_id FROM accounts WHERE user_id = :user_id";
            $account_stmt = $conn->prepare($account_query);
            $account_stmt->bindParam(':user_id', $user_id);
            $account_stmt->execute();
            $account = $account_stmt->fetch(PDO::FETCH_ASSOC);
    
            if (!$account) {
                echo "Account not found for the user.";
                $conn->rollback();
                return false;
            }
    
            $account_id = $account['account_id'];
    
            $transaction_query = "INSERT INTO transactions (account_id, type, amount) 
                                  VALUES (:account_id, 'deposit', :amount)";
            $transaction_stmt = $conn->prepare($transaction_query);
            $transaction_stmt->bindParam(':account_id', $account_id);
            $transaction_stmt->bindParam(':amount', $amount);
            $transaction_stmt->execute();
            echo "Transaction recorded successfully.";
    
            $conn->commit();
            return true;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
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

    function generateUniqueAccountNumber() {
        $db = new DB();
        $conn = $db->connect();

        do {
            $accountNumber = rand(0000000000000000, 9999999999999999);

            // check if the account number already exist
            $query = "SELECT COUNT(*) FROM accounts WHERE account_number = :account_number";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':account_number', $accountNumber);
            $stmt->execute();

            $count = $stmt->fetchColumn();
        } while ($count > 0);
        return $accountNumber;
    }

    function generateExpirationDate() {
        $date = new DateTime();
        $date->add(new DateInterval('P3Y')); // This will set the expiration date 3 years from today

        return $date->format('Y-m-d');
    }

    function generateCVV() {
        return rand(300, 999);
    }

    function transferAmount($fromAccountNumber, $toAccountNumber, $amount) {
        if ($amount <= 0) {
            return false;
        }

        $db = new DB();
        $conn = $db->connect();

        $conn->beginTransaction();

        try {
            // deduct from the sender`s account
            $deduct_query = "UPDATE accounts SET balance = balance - :amount WHERE account_number = :from_account AND balance >= :amount";
            $deduct_stmt = $conn->prepare($deduct_query);
            $deduct_stmt->bindParam(':amount', $amount);
            $deduct_stmt->bindParam(':from_account', $fromAccountNumber);
            $deduct_stmt->execute();

            if ($deduct_stmt->rowCount() === 0) {
                throw new Exception("Insufficient funds or account not found.");
            }

            // add to the receiver`s account
            $add_query = "UPDATE accounts SET balance = balance + :amount WHERE account_number = :to_account";
            $add_stmt = $conn->prepare($add_query);
            $add_stmt->bindParam(':amount', $amount);
            $add_stmt->bindParam(':to_account', $toAccountNumber);
            $add_stmt->execute();

            // log the transaction
            $logTransactionQuery = "INSER INTO transactions (account_id, type, amount) 
                                    VALUES ((SELECT account_id FROM accounts WHERE account_number = :from_account), 'transfer_out', :amount),
                                            ((SELECT account_id FROM accounts WHERE account_number = :to_account), 'transfer_in', :amount)";
            $logTransactionQuery_stmt = $conn->prepare($logTransactionQuery);
            $logTransactionQuery_stmt->bindParam(':from_account', $fromAccountNumber);
            $logTransactionQuery_stmt->bindParam(':to_account', $toAccountNumber);
            $logTransactionQuery_stmt->bindParam(':amount', $amount);
            $logTransactionQuery_stmt->execute();

            $conn->commit();
            return true;
        } catch (Exception $e) {
            $conn->rollback();
            return false;
        }
    }
?>