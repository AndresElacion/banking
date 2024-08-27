<?php
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