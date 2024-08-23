<nav class="bg-white border-r border-gray-200 fixed top-0 left-0 h-full w-64 z-50 shadow-lg">
  <div class="flex flex-col items-start justify-between h-full p-4">
    <!-- Logo and Brand -->
    <a href="https://flowbite.com/" class="flex items-center space-x-3 rtl:space-x-reverse mb-6">
      <img src="https://flowbite.com/docs/images/logo.svg" class="h-8" alt="Flowbite Logo" />
      <span class="self-center text-2xl font-semibold whitespace-nowrap">Flowbite</span>
    </a>
    
    <!-- Navigation Links -->
    <ul class="flex flex-col space-y-4 w-full">
      <li>
        <a href="#" class="block py-2 px-3 bg-blue-700 rounded md:bg-transparent md:text-blue-700 md:p-0" aria-current="page">Home</a>
      </li>
      <li>
        <a href="#" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0">About</a>
      </li>
      <li>
        <a href="#" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0">Services</a>
      </li>
      <li>
        <a href="#" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0">Pricing</a>
      </li>
      <li>
        <a href="#" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0">Contact</a>
      </li>
    </ul>

    <!-- User Profile and Dropdown -->
    <div class="mt-auto flex items-center w-full space-x-3 md:space-x-0 rtl:space-x-reverse">
      <button type="button" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 " id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown" data-dropdown-placement="right">
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
            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Dashboard</a>
          </li>
          <li>
            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Settings</a>
          </li>
          <li>
            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Earnings</a>
          </li>
          <li>
            <a href="../public/logout.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Sign out</a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</nav>
