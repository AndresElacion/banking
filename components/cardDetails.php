<div class="relative bg-gradient-to-r from-red-800 via-red-600 to-red-500 rounded-xl shadow-2xl p-6 w-96 h-56">
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