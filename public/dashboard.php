<?php
    require_once '../includes/auth.php';
    require_once '../includes/transferDetails.php';

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
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/62d4d8e42a.js" crossorigin="anonymous"></script>
    <title>BANKING | DASHBOARD</title>
</head>
<body class="bg-gray-100">
    <?php 
        include('../components/nav.php');
    ?>
    <div class="mx-auto my-5 p-5 max-w-7xl">
        <h1 class="text-2xl font-bold mb-5">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></h1>
        
        <div class="flex flex-col-reverse md:flex-row-reverse justify-between">
            <!-- Right Sidebar for Transactions -->
            <div class="bg-white border-r border-gray-200 fixed top-0 right-0 h-full w-64 z-50 shadow-lg p-5">
                <h2 class="text-xl font-bold mb-5">Latest Transactions</h2>
                <div class="flex flex-col space-y-4 overflow-y-auto h-full">
                    <?php if (!empty($transactionDetails)) : ?>
                        <?php foreach ($transactionDetails as $transaction) : ?>
                            <div class="p-4 bg-gray-100 rounded-lg shadow">
                                <p class="text-sm text-gray-500"><?php echo htmlspecialchars($transaction['transaction_date']); ?></p>
                                <p class="text-lg font-bold text-gray-800">$ <?php echo htmlspecialchars(number_format($transaction['amount'], 2)); ?></p>
                                <p class="text-sm text-gray-500"><?php echo htmlspecialchars(ucfirst($transaction['type'])); ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <p class="text-center text-gray-500">No recent transactions.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Main Content -->
            <div class="flex-grow">
                <div class="flex flex-col lg:flex-row justify-between p-4">
                    <div class="rounded-2xl shadow-2xl p-6 mb-5 lg:mb-0 bg-white max-w-lg lg:max-w-md">
                        <p class="text-md font-bold">Balance:</p>
                        <p class="text-xl">$ <?php echo htmlspecialchars(number_format($accountDetails['balance'], 2)); ?></p>
                        <a href="transaction.php" class="rounded-xl bg-blue-500 text-white p-2 mt-5 inline-block hover:bg-blue-700">Make a Transaction</a>
                    </div>

                    <div class="text-white p-4 max-w-lg lg:max-w-md">
                        <div class="relative bg-gradient-to-r from-red-800 via-red-600 to-red-500 rounded-2xl shadow-2xl p-6 w-full h-56">
                            <!-- Card Chip -->
                            <div class="absolute top-4 left-6 w-12 h-8 bg-yellow-400 rounded-sm"></div>
                    
                            <!-- Card Logo -->
                            <div class="absolute top-4 right-6 text-white text-2xl font-bold">
                                VISA
                            </div>
                    
                            <!-- Card Number -->
                            <div class="mt-12 text-white text-xl tracking-widest font-semibold">
                                <?php echo chunk_split(htmlspecialchars($accountDetails['account_number']), 4, ' '); ?>
                            </div>
                    
                            <!-- Expiration Date and CVV -->
                            <div class="flex justify-between mt-4">
                                <div class="text-white">
                                    <div class="text-xs tracking-widest">VALID THRU</div>
                                    <div class="text-lg font-medium"><?php echo htmlspecialchars($accountDetails['expiration_date']); ?></div>
                                </div>
                                <div class="text-white">
                                    <div class="text-xs tracking-widest">CVV</div>
                                    <div class="text-lg font-medium"><?php echo htmlspecialchars($accountDetails['cvv']); ?></div>
                                </div>
                            </div>
                    
                            <!-- Cardholder Name -->
                            <div class="mt-6 text-white text-lg font-bold uppercase">
                                <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Transfer Section -->
                <div class="bg-white p-5 rounded-lg shadow mt-10">
                    <?php
                        include('../components/transfer.php');
                    ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>
</body>
</html>

