<?php
    require_once '../../includes/auth.php';

    if (!isAdmin()) {
        header("Location: ../login.php");
    }

    $db = new DB();
    $conn = $db->connect();

    $filter = isset($_GET['filter']) ? $_GET['filter'] : 'daily';

    // default by daily
    if ($filter === 'monthly') {
        $query = "SELECT t.*, a.user_id FROM transactions t JOIN accounts a ON t.account_id = a.account_id WHERE DATE(transaction_date) = MONTH(CURDATE()) AND YEAR(transaction_date) = YEAR(CURDATE())";
    } elseif ($filter === 'yearly') {
        $query = "SELECT t.*, a.user_id FROM transactions t JOIN accounts a ON t.account_id = a.account_id WHERE DATE(transaction_date) = YEAR(CURDATE())";
    } else { 
        // daily transaction
        $query = "SELECT t.*, a.user_id FROM transactions t JOIN accounts a ON t.account_id = a.account_id WHERE DATE(t.transaction_date) = CURDATE()";
    }

    $transactions = $conn->query($query)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.css"  rel="stylesheet" />
    <title>BANKING | DASHBOARD</title>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto my-10 p-5">
        <h1 class="text-2xl font-bold mb-5">Admin Dashboard</h1>
        <div class="flex justify-between mb-4">
            <a href="?filter=daily" class="bg-blue-500 text-white py-2 px-4 rounded">Daily</a>
            <a href="?filter=monthly" class="bg-green-500 text-white py-2 px-4 rounded">Monthly</a>
            <a href="?filter=yearly" class="bg-red-500 text-white py-2 px-4 rounded">Yearly</a>
        </div>
        <div class="bg-white p-5 rounded shadow">
            <h2 class="text-xl font-bold mb-5">
                <?php
                    if ($filter === 'monthly') {
                        echo "Monthly Transactions";
                    } elseif ($filter === 'yearly') {
                        echo "Yearly Transactions";
                    } else {
                        echo "Daily Transactions";
                    }
                ?>
            </h2>
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="py-2">Transaction ID</th>
                        <th class="py-2">User ID</th>
                        <th class="py-2">Amount</th>
                        <th class="py-2">Type</th>
                        <th class="py-2">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transactions as $transaction): ?>
                    <tr class="text-center">
                        <td class="py-2"><?php echo htmlspecialchars($transaction['transaction_id']); ?></td>
                        <td class="py-2"><?php echo htmlspecialchars($transaction['user_id']); ?></td>
                        <td class="py-2"><?php echo htmlspecialchars($transaction['amount']); ?></td>
                        <td class="py-2"><?php echo htmlspecialchars($transaction['type']); ?></td>
                        <td class="py-2"><?php echo htmlspecialchars($transaction['transaction_date']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>
</body>
</html>