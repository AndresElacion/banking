<?php
require_once '../../includes/auth.php';

if (!isAdmin()) {
    header("Location: ../login.php");
    exit();
}

$db = new DB();
$conn = $db->connect();

$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$records_per_page = 10;
$offset = ($page - 1) * $records_per_page;

// Base query for counting total records
$count_query_base = "SELECT COUNT(*) FROM transactions t 
                     JOIN accounts a ON t.account_id = a.account_id";

// Modify the query based on the filter
if ($filter === 'monthly') {
    $count_query = $count_query_base . " WHERE MONTH(t.transaction_date) = MONTH(CURDATE()) 
                                          AND YEAR(t.transaction_date) = YEAR(CURDATE())";
    $query = "SELECT t.*, a.user_id FROM transactions t 
              JOIN accounts a ON t.account_id = a.account_id 
              WHERE MONTH(t.transaction_date) = MONTH(CURDATE()) 
              AND YEAR(t.transaction_date) = YEAR(CURDATE())
              ORDER BY t.transaction_date DESC
              LIMIT :limit OFFSET :offset";
} elseif ($filter === 'yearly') {
    $count_query = $count_query_base . " WHERE YEAR(t.transaction_date) = YEAR(CURDATE())";
    $query = "SELECT t.*, a.user_id FROM transactions t 
              JOIN accounts a ON t.account_id = a.account_id 
              WHERE YEAR(t.transaction_date) = YEAR(CURDATE())
              ORDER BY t.transaction_date DESC
              LIMIT :limit OFFSET :offset";
} else {
    $count_query = $count_query_base;
    $query = "SELECT t.*, a.user_id 
              FROM transactions t 
              JOIN accounts a ON t.account_id = a.account_id
              ORDER BY t.transaction_date DESC
              LIMIT :limit OFFSET :offset";
}

// Get total records for pagination
$stmt = $conn->prepare($count_query);
$stmt->execute();
$total_records = $stmt->fetchColumn();
$total_pages = ceil($total_records / $records_per_page);

// Fetch paginated transactions
$stmt = $conn->prepare($query);
$stmt->bindParam(':limit', $records_per_page, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.css" rel="stylesheet" />
    <title>BANKING | DASHBOARD</title>
</head>
<body class="bg-gray-100 flex h-screen overflow-hidden">
    <?php 
        include('../../components/admin/nav.php');
    ?>
    <div class="container mx-auto my-10 p-5">
        <h1 class="text-2xl font-bold mb-5">Admin Dashboard</h1>
        <div class="flex justify-between mb-4">
            <a href="?filter=all" class="bg-blue-500 text-white py-2 px-4 rounded">All</a>
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
                        echo "All Transactions";
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
                    <?php if (!empty($transactions)): ?>
                        <?php foreach ($transactions as $transaction): ?>
                        <tr class="text-center">
                            <td class="py-2"><?php echo htmlspecialchars($transaction['transaction_id']); ?></td>
                            <td class="py-2"><?php echo htmlspecialchars($transaction['user_id']); ?></td>
                            <td class="py-2"><?php echo htmlspecialchars($transaction['amount']); ?></td>
                            <td class="py-2"><?php echo htmlspecialchars($transaction['type']); ?></td>
                            <td class="py-2"><?php echo htmlspecialchars($transaction['transaction_date']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="py-2 text-center text-red-500">No transactions found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            
            <!-- Pagination Links -->
            <div class="mt-4 flex justify-center">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?filter=<?php echo $filter; ?>&page=<?php echo $i; ?>" class="px-3 py-1 <?php echo $i == $page ? 'bg-blue-500 text-white' : 'bg-gray-300 text-gray-800'; ?> rounded mx-1"><?php echo $i; ?></a>
                <?php endfor; ?>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>
</body>
</html>
