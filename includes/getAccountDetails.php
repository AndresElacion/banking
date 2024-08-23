<?php
    // Fetch account details for the logged-in user
    function getAccountDetails($user_id) {
        $db = new DB();
        $conn = $db->connect();

        $query = "SELECT account_number, expiration_date, cvv, balance FROM accounts WHERE user_id = :user_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
?>