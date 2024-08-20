<?php
    require_once '../includes/auth.php';

    if (!isLoggedIn()) {
        header('Location: index.php');
        exit;
    }

    $balance = getAccountBalance($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>BANKING | DASHBOARD</title>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto my-10 p-5">
        <h1 class="text-2xl font-bold mb-5">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></h1>
        <div class="bg-white p-5 rounded shadow">
            <h2 class="text-xl font-bold mb-5">Account Details</h2>
            <p><strong>Account ID:</strong> <?php echo htmlspecialchars($_SESSION['user_id']); ?></p>
            <p><strong>Balance:</strong> $<?php echo htmlspecialchars(number_format($balance, 2)); ?></p>
            <a href="transaction.php" class="bg-blue-500 text-white p-2 rounded mt-5 inline-block">Make a Transaction</a>
        </div>
    </div>
</body>
</html>