<div class="rounded-2xl shadow-2xl p-6 mb-5 lg:mb-0 bg-white max-w-lg lg:max-w-md">
    <p class="text-md font-bold">Balance:</p>
    <p class="text-xl">$ <?php echo htmlspecialchars(number_format($accountDetails['balance'], 2)); ?></p>
    <a href="transaction.php" class="rounded-xl bg-blue-500 text-white p-2 mt-5 inline-block hover:bg-blue-700">Make a Transaction</a>
</div>