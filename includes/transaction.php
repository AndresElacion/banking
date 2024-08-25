<?php
    require_once '../includes/auth.php';

    if (!isLoggedIn()) {
        header("Location: login.php");
        exit;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $type = htmlspecialchars(trim($_POST['type']));
        $amount = (float)$_POST['amount'];
        $user_id = $_SESSION['user_id'];

        if ($type === 'deposit') {
            $success = deposit($user_id, $amount);
        } elseif ($type === 'withdraw') {
            $success = withdraw($user_id, $amount);
        } else {
            $success = false;
        }

        if ($success) {
            header("Location: dashboard.php");
            exit;
        } else {
            $error_message = "Transaction failed. Please try again.";
        }
    }

    $balance = getAccountBalance($_SESSION['user_id']);
?>