<?php
    function registerUser($name, $email, $password, $address, $gender, $contact_number, $dob, $role) {
        $db = new DB();
        $conn = $db->connect();
    
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
        $conn->beginTransaction();
    
        try {
            // Insert the new user
            $query = "INSERT INTO users (name, email, password, address, gender, contact_number, dob, role) VALUES (:name, :email, :password, :address, :gender, :contact_number, :dob, :role)";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':gender', $gender);
            $stmt->bindParam(':contact_number', $contact_number);
            $stmt->bindParam(':dob', $dob);
            $stmt->bindParam(':role', $role);
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
            header("Location: dashboard.php");
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
?>