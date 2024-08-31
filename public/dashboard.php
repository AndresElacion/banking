<?php
    require_once '../includes/auth.php';
    require_once '../includes/transferDetails.php';
    require_once '../includes/transaction.php';
    require_once '../includes/deposit.php';

    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
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
<body class="bg-gray-100 flex h-screen overflow-hidden">
    <?php 
        include('../components/nav.php');
    ?>
    <div class="mx-auto p-5 relative flex flex-col flex-1">
        <h1 class="text-2xl font-bold mb-5">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></h1>
        
        <div class="flex flex-col-reverse md:flex-row-reverse justify-between">
            <!-- Right Sidebar for Transactions -->
            <?php include('../components/myTransaction.php'); ?>

            <!-- Main Content -->
            <div class="flex-grow pr-72">
                <!-- Total balance -->
                <div class="flex flex-row justify-between lg:order-2">
                    <div>
                        <?php include('../components/totalBalance.php'); ?>
                    </div>

                    <!-- Card Details section -->
                    <div class="text-white pt-5 max-w-lg">
                        <?php
                            if ($_SESSION['user_role'] === 'admin') {
                                include('../components/adminCardDetails.php');
                            } else {
                                include('../components/cardDetails.php');
                            }
                        ?>
                    </div>
                </div>

                <!-- Transfer Section -->
                <div class="flex flex-row bg-white p-5 rounded-lg shadow mt-10 lg:order-1">
                    <?php
                        include('../components/transfer.php');
                        include('../components/payBills.php');
                        include('../components/buyLoad.php');
                        include('../components/deposit.php');
                        include('../components/withdraw.php');
                    ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>
</body>
</html>

