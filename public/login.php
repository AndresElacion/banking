<?php
    require_once '../includes/auth.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = htmlspecialchars(trim($_POST['email']));
        $password = htmlspecialchars(trim($_POST['password']));

        if (loginUser($email, $password)) {
            header("Location: dashboard.php");
        } else {
            echo "Invalid credentials";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>BANKING | LOGIN</title>
</head>
<body class="bg-gray-100">
    <div class="flex justify-center items-center h-screen">
        <div class="container mx-auto my-10 p-5">
            <h1 class="w-full max-w-sm mx-auto text-center text-2xl font-bold p-3 bg-amber-400 rounded-md">Login</h1>
            <hr class="max-w-sm mx-auto" />
            <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST" class="w-full max-w-sm mx-auto bg-white p-8 rounded-md shadow-xl">
                <div class="mb-4">
                    <label class="block text-gray-700">Email</label>
                    <input type="email" name="email" class="w-full p-2 border border-gray-300 rounded mt-1" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Password</label>
                    <input type="password" name="password" class="w-full p-2 border border-gray-300 rounded mt-1" required>
                </div>
                <button type="submit" class="bg-red-500 font-bold w-full text-white p-2 rounded">Login</button>
            </form>
        </div>
    </div>
</body>
</html>