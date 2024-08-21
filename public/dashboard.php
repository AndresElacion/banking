<?php
    require_once '../includes/auth.php';

    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }

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

    $accountDetails = getAccountDetails($_SESSION['user_id']);
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
    <?php 
        include('../components/nav.php');
    ?>
    <div class="container mx-auto my-10 p-5">
        <h1 class="text-2xl font-bold mb-5">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></h1>
        <div class="bg-white p-5 rounded-lg shadow">
            <h2 class="text-xl font-bold mb-5">Account Details</h2>
            <p><strong>Account ID:</strong> <?php echo htmlspecialchars($_SESSION['user_id']); ?></p>
            <p><strong>Account Number:</strong> <?php echo htmlspecialchars($accountDetails['account_number']); ?></p>
            <p><strong>Expiration Date:</strong> <?php echo htmlspecialchars($accountDetails['expiration_date']); ?></p>
            <p><strong>CVV:</strong> <?php echo htmlspecialchars($accountDetails['cvv']); ?></p>
            <p><strong>Balance:</strong> $<?php echo htmlspecialchars(number_format($accountDetails['balance'], 2)); ?></p>
            <a href="transaction.php" class="bg-blue-500 text-white p-2 rounded mt-5 inline-block hover:bg-blue-700">Make a Transaction</a>
        </div>

        <!-- Transfer Section -->
        <div class="bg-white p-5 rounded-lg shadow mt-10">
            <h2 class="text-xl font-bold mb-5">Transfer Money</h2>
            <form action="../includes/transfer.php" method="POST">
                <div class="mb-4">
                    <label for="receiver_account_number" class="block text-lg font-medium text-gray-700">Receiver's Account Number</label>
                    <input type="text" id="receiver_account_number" name="receiver_account_number" required class="w-full p-2 border border-gray-300 rounded-lg">
                </div>
                <div class="mb-4">
                    <label for="amount" class="block text-lg font-medium text-gray-700">Amount to Transfer</label>
                    <input type="number" id="amount" name="amount" min="1" required class="w-full p-2 border border-gray-300 rounded-lg">
                </div>
                <button type="submit" class="bg-green-500 text-white p-2 rounded-lg mt-5 hover:bg-green-700">Transfer</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>
</body>
</html>
