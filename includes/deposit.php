<?php
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
?>