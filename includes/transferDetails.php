<?php
    require_once '../includes/auth.php';
    require_once '../includes/getAccountDetails.php';

    $accountDetails = getAccountDetails($_SESSION['user_id']);

    // Fetch daily transaction details for the logged-in user
    function transactionDetails($user_id) {
        $db = new DB();
        $conn = $db->connect();

        // Query for daily transactions
        $query = "SELECT t.*, a.user_id FROM transactions t JOIN accounts a ON t.account_id = a.account_id WHERE DATE(t.transaction_date) = CURDATE() AND a.user_id = :user_id ORDER BY t.transaction_date DESC LIMIT 5";
    
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    $transactionDetails = transactionDetails($_SESSION['user_id']);
?>