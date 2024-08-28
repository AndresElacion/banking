<div class="min-h-screen flex flex-row bg-gray-100">
  <div class="flex flex-col w-56 bg-white rounded-r-3xl overflow-hidden">
    <div class="flex items-center justify-center h-20 shadow-md">
      <h1 class="text-3xl text-center uppercase text-indigo-500">Banking App</h1>
    </div>
    <ul class="flex flex-col py-4">
      <li>
        <a href="../public/dashboard.php" class="flex flex-row items-center h-12 transform hover:translate-x-2 transition-transform ease-in duration-200 text-gray-500 hover:text-gray-800">
          <span class="inline-flex items-center justify-center h-12 w-12 text-lg text-gray-400"><i class='bx bx-chevron-right'></i></span>
          <span class="text-sm font-medium">Dashobard</span>
        </a>
      </li>
      <li>
        <a href="../public/allTransaction.php" class="flex flex-row items-center h-12 transform hover:translate-x-2 transition-transform ease-in duration-200 text-gray-500 hover:text-gray-800">
          <span class="inline-flex items-center justify-center h-12 w-12 text-lg text-gray-400"><i class='bx bx-chevron-right'></i></span>
          <span class="text-sm font-medium">Add User</span>
        </a>
      </li>
      <hr />
      <li>
        <a href="../public/logout.php" class="flex flex-row items-center h-12 transform hover:translate-x-2 transition-transform ease-in duration-200 text-gray-500 hover:text-red-500">
          <span class="inline-flex items-center justify-center h-12 w-12 text-lg text-gray-400"><i class='bx bx-log-out'></i></span>
          <span class="text-sm font-medium">Logout</span>
        </a>
      </li>
    </ul>

    <!-- User Profile and Dropdown -->
    <div class="mt-auto flex items-center w-full space-x-3 md:space-x-0 rtl:space-x-reverse">
      <button type="button" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300" id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown" data-dropdown-placement="right">
        <span class="sr-only">Open user menu</span>
        <img class="w-8 h-8 rounded-full" src="/docs/images/people/profile-picture-3.jpg" alt="user photo">
      </button>
      <!-- Dropdown menu -->
      <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow " id="user-dropdown">
        <div class="px-4 py-3">
          <span class="block text-sm text-gray-900"><?php echo htmlspecialchars($_SESSION["user_name"]) ?></span>
          <span class="block text-sm text-gray-500 truncate"><?php echo htmlspecialchars($_SESSION["user_email"]) ?></span>
        </div>
        <ul class="py-2" aria-labelledby="user-menu-button">
          <li>
            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Settings</a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>


