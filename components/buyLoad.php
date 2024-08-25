<div class="flex flex-col-5 pl-4">
    <div class="pr-4">
        <!-- Modal toggle -->
        <button data-modal-target="buy-load" data-modal-toggle="buy-load" class="block text-black bg-slate-50 hover:bg-white focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-2xl shadow-2xl text-md px-5 py-8" type="button">
            <i class="fa-solid fa-mobile fa-xl pb-10 -ml-8 text-start"></i>
            <p>Buy Load</p>
        </button>
        
        <!-- Main modal -->
        <div id="buy-load" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-2xl max-h-full">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                        <h3 class="text-xl font-semibold text-gray-900">
                            Buy Load
                        </h3>
                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-hide="pay-bills">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class="p-4 md:p-5 space-y-4">
                        <!-- Transfer Section -->
                        <div class="bg-white p-5 rounded-lg shadow">
                            
                            <form action="../includes/transfer.php" method="POST">
                                <div class="mb-4">
                                    <label for="receiver_account_number" class="block text-lg font-medium text-gray-700">Mobile Number</label>
                                    <input type="text" id="receiver_account_number" name="receiver_account_number" required class="w-full p-2 border border-gray-300 rounded-lg">
                                </div>
                                <div class="mb-4">
                                    <label for="amount" class="block text-lg font-medium text-gray-700">Amount</label>
                                    <input type="number" id="amount" name="amount" min="1" required class="w-full p-2 border border-gray-300 rounded-lg">
                                </div>
                                <button type="submit" class="bg-green-500 text-white p-2 rounded-lg mt-5 hover:bg-green-700">Buy</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>