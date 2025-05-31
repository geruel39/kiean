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
            <p class="text-lg cursor-pointer hover:text-blue-700 p-3 rounded bg-blue-500 tab">Payment Records</p>
            <p class="text-lg cursor-pointer hover:text-blue-700 p-3 rounded tab">Payment History</p>
        </div>

        <div class="page">
            <div class="flex items-center space-x-2">
                <h1 class="text-2xl mr-auto">Payment Records</h1>
                <input id="payment_search" type="search" placeholder="Search Tutee Name" class="focus:outline-none text-xl p-2 cursor-pointer border border-blue-500 rounded">
                <select id="payment_month" class="text-xl p-2 cursor-pointer border border-blue-500 rounded months">
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
                <select id="payment_year" class="focus:outline-none text-xl p-2 cursor-pointer border border-blue-500 rounded years"></select>
            </div>

            <div class="flex p-2 my-1 rounded bg-blue-500">
                <p class="w-full font-bold">Fullname</p>
                <p class="w-full font-bold">Rates</p>
                <p class="w-full font-bold">Paid Amount</p>
                <p class="w-full font-bold">Status</p>
                <p class="w-full font-bold"></p>
            </div>

            <div id="payment_record_table">
                <div>
                    <div class="flex p-2 my-1 rounded border border-blue-500">
                        <p class="w-full text-sm font-bold text-center">Loading...</p>
                    </div>
                </div>
            </div>

        </div>

        <div class="page hidden">
            <h1 class="text-2xl mr-auto">Payment History</h1>

            <div class="flex p-2 my-1 rounded bg-blue-500">
                <p class="w-full font-bold">Tutee Name</p>
                <p class="w-full font-bold">Amount</p>
                <p class="w-full font-bold">Tutoring Period</p>
                <p class="w-full font-bold">Payment Date</p>
                <p class="w-full font-bold"></p>
            </div>

            <div id="payment_history_table">
                <div>
                    <div class="flex p-2 my-1 rounded border border-blue-500">
                        <p class="w-full text-sm font-bold text-center">Loading...</p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Add Payment Modal -->
    <div id="add_payment_modal" class="w-2/5 h-60 border border-black absolute inset-2/4 transform -translate-x-2/4 -translate-y-2/4 rounded bg-gray-100 p-3 flex flex-col space-y-3 hidden">
        <h1 class="text-2xl uppercase">Add Payment</h1>
        <p class="text-gray-700 text-xl" id="date_period">Loading...</p>
        <input id="payment_amount" type="number" placeholder="Amount" class="text-xl p-2 cursor-pointer border border-blue-500 rounded">
        <div class="flex space-x-3">
            <button class="w-full p-2 bg-red-500 rounded opacity-80 hover:opacity-100 close-popup">Cancel</button>
            <button id="confirm_payment" class="w-full p-2 bg-blue-500 rounded opacity-80 hover:opacity-100">Confirm</button>
        </div>
    </div>
    
    <script src="js/general.js"></script>
    <script src="js/b_payment.js"></script>
</body>
</html>