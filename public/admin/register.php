<?php
    require_once '../../includes/auth.php';
    require_once '../../includes/register.php';
  
    if (!isAdmin()) {
        header("Location: ../login.php");
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = htmlspecialchars(trim($_POST['name']));
        $email = htmlspecialchars(trim($_POST['email']));
        $address = htmlspecialchars(trim($_POST['address']));
        $gender = htmlspecialchars(trim($_POST['gender']));
        $contact_number = htmlspecialchars(trim($_POST['contact_number']));
        $dob = htmlspecialchars(trim($_POST['dob']));
        $role = htmlspecialchars(trim($_POST['role']));
        $password = htmlspecialchars(trim($_POST['password']));

        if (registerUser($name, $email, $password, $address, $gender, $contact_number, $dob, $role)) {
            header("Location: dashboard.php");
        } else {
            echo "Error: Could not register user.";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>BANKING | REGISTER</title>
</head>
<body class="bg-gray-100 flex h-screenh overflow-hidden">
    <?php
        include('../../components/admin/nav.php')
    ?>
    <div class="container mx-auto my-10 p-5">
        <div class="bg-white shadow-md rounded px-8 mt-12 pt-6 pb-8 mb-4 flex flex-col my-2">
            <div class="mb-6">
              <h1 class="text-2xl">Fill up the form to register new user.</h1>
            </div>
            <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
                <!-- Existing form fields -->
                <div class="-mx-3 md:flex mb-6">
                    <div class="md:w-1/2 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="name">
                            Name
                        </label>
                        <input class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3" name="name" id="name" type="text" placeholder="Jane Doe" required>
                    </div>
                    <div class="md:w-1/2 px-3">
                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="email">
                            Email Address
                        </label>
                        <input class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded py-3 px-4" name="email" id="email" type="email" placeholder="Jane@doe.com" required>
                    </div>
                </div>
                <!-- Existing address, gender, contact_number, dob fields -->
                <div class="-mx-3 md:flex mb-6">
                    <div class="md:w-full px-3">
                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="address">
                            Complete Address
                        </label>
                        <input class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded py-3 px-4 mb-3" name="address" id="address" type="address" placeholder="Complete address" required>
                    </div>
                </div>
                <div class="-mx-3 md:flex mb-6">
                    <div class="md:w-1/2 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="gender">
                            Gender
                        </label>
                        <input class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded py-3 px-4" name="gender" id="gender" type="text" placeholder="Male or Female" required>
                    </div>
                    <div class="md:w-1/2 px-3">
                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="contact_number">
                            Contact Number
                        </label>
                        <input class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded py-3 px-4" name="contact_number" id="contact_number" type="text" placeholder="+63 123 456 7890" required>
                    </div>
                    <div class="md:w-1/2 px-3">
                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="dob">
                            Date of Birth
                        </label>
                        <input class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded py-3 px-4" name="dob" id="dob" type="date" placeholder="1/10/2024" required>
                    </div>
                </div>
                <!-- Role selection dropdown -->
                <div class="-mx-3 md:flex mb-6">
                    <div class="md:w-full px-3">
                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="role">
                            Role
                        </label>
                        <select name="role" id="role" class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded py-3 px-4 mb-3" required>
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>
                <!-- Hidden password field -->
                <input type="hidden" name="password">
            
                <button class="bg-blue-500 p-2 text-white rounded-sm hover:bg-blue-600" type="submit">Submit</button>
            </form>
        </div>
    </div>
    <!-- <div class="flex justify-center items-center h-screen">
        <div class="container mx-auto my-10 p-5">
            <h1 class="w-full max-w-sm mx-auto text-center text-2xl font-bold p-3 bg-amber-400 rounded-md">Register</h1>
            <hr class="max-w-sm mx-auto" />
            <form action=" --><!-- <?php htmlspecialchars($_SERVER['PHP_SELF']) ?> --><!-- " method="POST" class="w-full max-w-sm mx-auto bg-white p-8 rounded-md shadow-xl">
                <div class="mb-4">
                    <label class="block text-gray-700">Name</label>
                    <input type="text" name="name" class="w-full p-2 border border-gray-300 rounded mt-1" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Email</label>
                    <input type="email" name="email" class="w-full p-2 border border-gray-300 rounded mt-1" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Password</label>
                    <input type="password" name="password" class="w-full p-2 border border-gray-300 rounded mt-1" required>
                </div>
                <button type="submit" class="bg-red-500 font-bold text-white p-2 rounded w-full">Register</button>
            </form>
        </div>
    </div> -->
</body>
</html>