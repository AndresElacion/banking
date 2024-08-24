<div class="bg-white rounded-l-3xl border-gray-200 fixed top-0 right-0 h-full w-64 z-50 shadow-lg p-5 lg:order-3">
    <h2 class="text-xl font-bold mb-5">Latest Transactions</h2>
    <div class="flex flex-col space-y-4 overflow-y-auto h-full">
        <?php if (!empty($transactionDetails)) : ?>
            <?php foreach ($transactionDetails as $transaction) : ?>
                <div class="p-4 bg-gray-100 rounded-xl shadow">
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