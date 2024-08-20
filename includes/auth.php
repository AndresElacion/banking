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
?>