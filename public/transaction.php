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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="flex justify-center items-center h-screen">
        <div class="container mx-auto my-10 p-5 w-full max-w-sm bg-white p-8 rounded-md shadow-xl">
            <h1 class="text-2xl font-bold mb-5">Make a Transaction</h1>
            <p><strong>Account ID:</strong> <?php echo htmlspecialchars($_SESSION['user_id']); ?></p>
            <?php if (isset($error_message)): ?>
                <div class="bg-red-100 text-red-700 p-3 rounded-md mb-4">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>
            <div class="mb-5">
                <strong>Current Balance:</strong> $
            </div>
            <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
                <div class="mb-4">
                    <label class="block text-gray-700">Transaction Type</label>
                    <select name="type" class="w-full p-2 border border-gray-300 rounded mt-1">
                        <option value="deposit">Deposit</option>
                        <option value="withdraw">Withdrawal</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Amount</label>
                    <input type="number" name="amount" step="0.01" class="w-full p-2 border border-gray-300 rounded mt-1" required>
                </div>
                <button type="submit" class="bg-blue-500 text-white p-2 rounded">Submit</button>
            </form>
        </div>
    </div>
</body>
</html>