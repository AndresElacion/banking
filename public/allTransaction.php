<?php
require_once '../includes/auth.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id']; // Assuming 'user_id' is stored in the session during login

$db = new DB();
$conn = $db->connect();

$filter = isset($_GET['filter']) ? $_GET['filter'] : 'daily';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$records_per_page = 10;
$offset = ($page - 1) * $records_per_page;

// Get total records for pagination
$count_query = "SELECT COUNT(*) 
                FROM transactions t 
                JOIN accounts a ON t.account_id = a.account_id 
                WHERE a.user_id = :user_id";
$stmt = $conn->prepare($count_query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$total_records = $stmt->fetchColumn();
$total_pages = ceil($total_records / $records_per_page);

// Modify the main query to include LIMIT and OFFSET
$query = "SELECT t.*, a.user_id 
          FROM transactions t 
          JOIN accounts a ON t.account_id = a.account_id 
          WHERE a.user_id = :user_id 
          ORDER BY t.transaction_date DESC
          LIMIT :limit OFFSET :offset";
          
$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->bindParam(':limit', $records_per_page, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
if (!$stmt->execute()) {
    print_r($stmt->errorInfo()); // Print any SQL errors
}
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
<body class="bg-gray-100 flex h-screen overflow-hidden">
    <?php 
        include('../components/nav.php');
    ?>
    <div class="container mx-auto my-10 p-5">
        <h1 class="text-2xl font-bold mb-5">My Transaction</h1>
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
                        <th class="py-2">Amount</th>
                        <th class="py-2">Type</th>
                        <th class="py-2">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transactions as $transaction): ?>
                    <tr class="text-center">
                        <td class="py-2"><?php echo htmlspecialchars($transaction['transaction_id']); ?></td>
                        <td class="py-2"><?php echo htmlspecialchars($transaction['amount']); ?></td>
                        <td class="py-2"><?php echo htmlspecialchars($transaction['type']); ?></td>
                        <td class="py-2"><?php echo htmlspecialchars($transaction['transaction_date']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <!-- Pagination Links -->
            <div class="mt-4">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?filter=<?php echo $filter; ?>&page=<?php echo $i; ?>" class="px-3 py-1 bg-gray-300 text-gray-800 rounded mx-1"><?php echo $i; ?></a>
                <?php endfor; ?>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>
</body>
</html>
