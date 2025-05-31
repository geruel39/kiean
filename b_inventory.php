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
        <h1 class="text-2xl m-2">Manage Inventory</h1>

        <div class="flex justify-between items-center">

            <input id="search_item" type="search" placeholder="Search Item" class="text-xl p-2 cursor-pointer border border-blue-500 rounded">

            <div class="flex space-x-2">
                <button id="open_inv_actions" class="p-2 bg-gray-600 rounded text-white opacity-80 hover:opacity-100">Actions</button>
            </div>

        </div>

        <div id="inventory_table" class="max-h-96 flex flex-wrap p-5 my-5 overflow-y-auto shadow">

        </div>
    </div>

    <!-- Edit Item Quantity Modal -->
    <div id="edit_q" class="w-1/5 h-72 p-5 border-2 border-black absolute inset-2/4 transform -translate-x-2/4 -translate-y-2/4 rounded bg-gray-100 p-3 flex flex-col items-center space-y-3 hidden">
        <h1 id="item_name" class="text-2xl">Item Name</h1>
        <h3 id="item_cost" class="text-md">Cost: 12</h3>
        <input id="item_q" type="number" placeholder="Quantity" class="text-xl text-center p-2 cursor-pointer border border-blue-500 rounded ">
        <div class="flex w-full space-x-1">
            <button id="add_q" class="w-2/4 p-3 bg-green-500 rounded opacity-80 hover:opacity-100">Add</button>
            <button id="ded_q" class="w-2/4 p-3 bg-red-500 rounded opacity-80 hover:opacity-100">Deduct</button>
        </div>
        <button class="w-2/4 p-3 bg-blue-500 rounded opacity-80 hover:opacity-100 close-popup">Close</button>
    </div>

    <!-- Inventory Actions -->
    <div id="inv_actions" class="w-3/4 h-96 p-5 border-2 border-black absolute inset-2/4 transform -translate-x-2/4 -translate-y-2/4 rounded bg-gray-100 p-3 flex flex-col space-y-3 hidden">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl">Inventory Actions</h1>
            <div class="flex space-x-1">
                <button id="undo_action" class="px-5 py-1 rounded bg-gray-700 text-white opacity-70 hover:opacity-100">Undo</button>
                <button class="px-5 py-1 rounded bg-red-700 text-white opacity-70 hover:opacity-100 close-popup">Close</button>
            </div>
        </div>

        <div class="flex p-2 bg-gray-500 rounded text-white">
            <p class="w-full">Action</p>
            <p class="w-full">Item</p>
            <p class="w-full">Quantity</p>
            <p class="w-full">Cost</p>
            <p class="w-full">Date</p>
            <p class="w-full">Time</p>
        </div>

        <div id="action_table" class="h-60 overflow-y-scroll">
            
        </div>
    </div>

    
    <script src="js/general.js"></script>
    <script src="js/b_inventory.js"></script>
</body>
</html>