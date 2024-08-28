<?php
    session_start();
    require_once 'db.php';

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
            $_SESSION['user_role'] = $user['role'];

            return true;
        } else {
            return false;
        }
    }

    function isAdmin() {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
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

?>