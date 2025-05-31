<?php
    session_start();
    if($_SESSION['id']){
        $account_id = $_SESSION['id'];
        echo "<input type='hidden'  style='display:none;' id='session_id' value='$account_id'>";
    }
    else{
        header("Location: default.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/general.css">
</head>
<body>
    
    <div class="w-full fixed top-0 h-16 bg-blue-600 flex items-center justify-between px-5">
        <div class="flex items-center space-x-5">
            <div class="text-2xl px-3 cursor-pointer rounded hover:bg-gray-200" id="burger-icon">&#9776;</div>
            <h3 class="text-2xl">KieAn Tutorial Center</h3>
        </div>
        <div class="flex items-center ">
            <h3 class="text-2xl uppercase" id="header_branch_display">Loading...</h3>
            <div class="text-2xl px-3 m-2 cursor-pointer rounded hover:bg-gray-200" id="my_branches">&#8942;</div>
        </div>
    </div>

    <div class="w-1/4 bg-gray-200 fixed top-16 right-1 shadow rounded flex flex-col items-center p-2 hidden" id="change_branch_modal">
        <h3 class="text-2xl">Change Branch</h3>
        <p class="text-gray-600">Click to select branch</p>
        <hr class="border border-gray-900 w-full my-3 opacity-50">
        <div class="w-full" id="change_branch_list">
            <p class="w-full py-2 cursor-pointer border border-blue-500 text-center rounded hover:bg-gray-300">Loading...</p>
        </div>
    </div>

    <div class="h-screen w-1/4 bg-blue-300 border-r border-blue-500 flex flex-col items-center space-y-3 fixed left-0 hidden" id="sidebar">
        <div class="flex items-center space-x-5 pt-5 mb-10">
            <div class="text-2xl cursor-pointer hover:text-red-500" id="times-icon">&#10006; <span>CLOSE</span></div>
        </div>
        <a href="b_dashboard.php" class="w-3/4 text-center text-lg font-bold hover:bg-blue-700 p-2 rounded">Dashboard</a>
        <a href="b_student.php" class="w-3/4 text-center text-lg font-bold hover:bg-blue-700 p-2 rounded">Student</a>
        <a href="b_payment.php" class="w-3/4 text-center text-lg font-bold hover:bg-blue-700 p-2 rounded">Payment</a>
        <a href="b_financial.php" class="w-3/4 text-center text-lg font-bold hover:bg-blue-700 p-2 rounded">Financials</a>
        <a href="b_inventory.php" class="w-3/4 text-center text-lg font-bold hover:bg-blue-700 p-2 rounded">Invetory</a>
        <a href="b_setting.php" class="w-3/4 text-center text-lg font-bold hover:bg-blue-700 p-2 rounded">Settings</a>
        <button class="w-3/4 text-center text-lg font-bold hover:bg-blue-700 p-2 rounded logout">Logout</button>
    </div>

    <div class="w-full min-h-screen pt-16 p-5">
        
        <div class="flex space-x-5 m-2 border-b-2 border-black py-2">
            <p class="text-lg cursor-pointer hover:text-blue-700 p-3 rounded bg-blue-500 tab">Overview</p>
            <p class="text-lg cursor-pointer hover:text-blue-700 p-3 rounded tab">Expenses & Supply</p>
        </div>

        <!-- Overview Page -->
        <div class="page">

            <div class="flex items-center justify-between">

                <div class="flex items-center space-x-2">
                    <h1 class="text-2xl">Overview <span id="overview_date" class="text-red-500">...</span></h1>
                    <button class="text-sm hover:text-white hover:bg-gray-800 px-2 rounded"> Download </button>             
                </div>
                <div>
                    <select id="overview_month" class="text-xl p-2 cursor-pointer border border-blue-500 rounded months">
                            <option value="01">January</option>
                            <option value="02">February</option>
                            <option value="03">March</option>
                            <option value="04">April</option>
                            <option value="05">May</option>
                            <option value="06">June</option>
                            <option value="07">July</option>
                            <option value="08">August</option>
                            <option value="09">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                        </select>
                    <select id="overview_year" class="text-xl p-2 cursor-pointer border border-blue-500 rounded years"></select>
                </div>

            </div>

            <div class="flex flex-col">
                <div class="flex">
                    <div class="w-full h-60 p-5 m-2 rounded bg-blue-700">
                        <h2 class="text-white text-2xl font-bold mb-4">Accumulated Payment</h2>
                        <p class="text-white">Payments have currently been collected and received for this month.</p>
                        <div class="flex items-center space-x-5 mt-5">
                            <p class="text-8xl text-white">₱</p>
                            <p id="payment" class="text-7xl text-white">0.00</p>
                        </div>
                    </div>
                    <div class="w-full h-60 p-5 m-2 rounded bg-red-700">
                        <h2 class="text-white text-2xl font-bold mb-4">Expenses</h2>
                        <p class="text-white">Total expenses recorded this month.</p>
                        <div class="flex items-center space-x-5 mt-5">
                            <p class="text-8xl text-white">₱</p>
                            <p id="expenses" class="text-7xl text-white">0.00</p>
                        </div>
                    </div>
                </div>
                <div class="flex">
                    <div class="w-full h-60  p-5 m-2 rounded bg-yellow-700">
                        <h2 class="text-white text-2xl font-bold mb-4">Supply Used</h2>
                        <p class="text-white">Total cost of all school supplies used this month.<p>
                        <div class="flex items-center space-x-5 mt-5">
                            <p class="text-8xl text-white">₱</p>
                            <p id="supply" class="text-7xl text-white">0.00</p>
                        </div>
                    </div>
                    <div class="w-full h-60  p-5 m-2 rounded bg-green-700">
                        <h2 class="text-white text-2xl font-bold mb-4">Provisional Income</h2>
                        <p class="text-white">Estimated provisional income based on current calculations.</p>
                        <div class="flex items-center space-x-5 mt-5">
                            <p class="text-8xl text-white">₱</p>
                            <p id="income" class="text-7xl text-white">0.00</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Expenses and Suppy Page -->
        <div class="page hidden">

            <div class="flex items-center justify-between">

                <div class="flex items-center space-x-5">
                    <h1 class="text-2xl">Expenses Record <span id="expenses_date" class="text-red-500">...</span></h1>
                </div>
                <div>
                    <select id="exp_month" class="text-xl p-2 cursor-pointer border border-blue-500 rounded months">
                            <option value="01">January</option>
                            <option value="02">February</option>
                            <option value="03">March</option>
                            <option value="04">April</option>
                            <option value="05">May</option>
                            <option value="06">June</option>
                            <option value="07">July</option>
                            <option value="08">August</option>
                            <option value="09">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                        </select>
                    <select id="exp_year" class="text-xl p-2 cursor-pointer border border-blue-500 rounded years"></select>
                </div>
           
            </div>

            <div class="flex">
                <button id="open-add-expenses" class="uppercase text-2xl p-2 hover:bg-blue-500 rounded m-1">+ Add Expenses</button>
                <button id="open-add-type" class="uppercase text-2xl p-2 hover:bg-blue-500 rounded m-1">+ Add Types</button>
            </div>

            <!-- Expenses Table -->
            <div class="flex flex-col border-t border-red-500">

                <h1 class="text-xl my-3">Expenses List</h1>

                <div class="flex p-2 my-1 rounded bg-red-500">
                    <p class="w-full font-bold tracking-widest">Type</p>
                    <p class="w-full font-bold tracking-widest">Amount</p>
                </div>

                <div id="expenses_table">
                    <div class="flex p-2 my-1 rounded border border-red-500 hover:bg-gray-300">
                        <p class="w-full font-bold text-center tracking-widest">Loading...</p>
                    </div>

                    <div class="flex p-2 my-1 bg-red-500">
                        <p class="w-full font-bold text-xl text-white tracking-widest">TOTAL</p>
                        <p class="w-full font-bold text-xl text-white tracking-widest">0</p>
                    </div>
                </div>

            </div>

            <hr class="my-5 border border-black">

            <!-- Supply Table -->
            <div class="flex flex-col mt-5">

                <h1 class="text-xl my-3">Supply Used</h1>

                <div class="flex p-2 my-1 rounded bg-yellow-300">
                    <p class="w-full font-bold tracking-widest">Item</p>
                    <p class="w-full font-bold tracking-widest">Quantity</p>
                    <p class="w-full font-bold tracking-widest">Cost</p>
                </div>

                <div id="used_table">
                    <div class="flex p-2 my-1 rounded border border-yellow-300 hover:bg-gray-300">
                        <p class="w-full font-bold text-center tracking-widest">Loading...</p>
                    </div>
                </div>

                <div class="flex p-2 my-1 bg-yellow-300">
                    <p class="w-full font-bold text-xl tracking-widest">TOTAL</p>
                    <p id="tq_display" class="w-full font-bold text-xl tracking-widest">0</p>
                    <p id="tc_display" class="w-full font-bold text-xl tracking-widest">0</p>
                </div>

            </div>

        </div>

        <!-- Add Expenses Modal -->
        <div id="expenses_modal" class="w-2/5 h-60 border border-black fixed inset-2/4 transform -translate-x-2/4 -translate-y-2/4 rounded bg-gray-100 p-3 flex flex-col space-y-3 hidden">
            <h1 class="text-2xl uppercase">Add Expenses</h1>
            <select id="exp_type" class="text-xl p-2 cursor-pointer border border-red-500 rounded">

            </select>
            <input id="exp_amount" type="number" placeholder="Amount" class="text-xl p-2 cursor-pointer border border-red-500 rounded">
            <div class="flex space-x-3">
                <button class="w-full p-2 bg-red-500 rounded opacity-80 hover:opacity-100 close-popup">Cancel</button>
                <button id="add_expenses" class="w-full p-2 bg-blue-500 rounded opacity-80 hover:opacity-100">Add Expenses</button>
            </div>
        </div>

        <!-- Add Type Modal -->
        <div id="type_modal" class="w-2/5 h-48 border border-black fixed inset-2/4 transform -translate-x-2/4 -translate-y-2/4 rounded bg-gray-100 p-3 flex flex-col space-y-3 hidden">
            <h1 class="text-2xl uppercase">Add Type</h1>
            <input id="new_xtype" type="text" placeholder="Enter Type" class="text-xl p-2 cursor-pointer border border-red-500 rounded">
            <div class="flex space-x-3">
                <button class="w-full p-2 bg-red-500 rounded opacity-80 hover:opacity-100 close-popup">Cancel</button>
                <button id="add_type" class="w-full p-2 bg-blue-500 rounded opacity-80 hover:opacity-100">Add Type</button>
            </div>
        </div>

    </div>
    
    <script src="js/general.js"></script>
    <script src="js/b_financial.js"></script>
</body>
</html>