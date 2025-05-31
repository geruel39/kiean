<?php
    session_start();
    if($_SESSION['id']){

        if($_SESSION['role'] != "Admin"){
            header("Location: default.php");
            exit;
        }

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
    
    <div class="w-full fixed top-0 h-16 bg-blue-500 flex items-center justify-between px-5">
        <div class="flex items-center space-x-5">
            <div class="text-2xl cursor-pointer hover:text-red-500" id="burger-icon">&#9776;</div>
            <h3 class="text-2xl">KieAn Tutorial Center</h3>
        </div>
        <h3 class="text-2xl uppercase">System Admin</h3>
    </div>

    <div class="h-screen w-1/4 bg-blue-300 border-r border-blue-500 flex flex-col items-center space-y-3 fixed left-0 hidden" id="sidebar">
        <div class="flex items-center space-x-5 pt-5 mb-10">
            <div class="text-2xl cursor-pointer hover:text-red-500" id="times-icon">&#10006; <span>CLOSE</span></div>
        </div>
        <a href="dashboard.php" class="w-3/4 text-center text-lg font-bold hover:bg-blue-700 p-2 rounded">Dashboard</a>
        <a href="branches.php" class="w-3/4 text-center text-lg font-bold hover:bg-blue-700 p-2 rounded">Account & Branch</a>
        <a href="student.php" class="w-3/4 text-center text-lg font-bold hover:bg-blue-700 p-2 rounded">Student & Payment</a>
        <a href="financials.php" class="w-3/4 text-center text-lg font-bold hover:bg-blue-700 p-2 rounded">Financials</a>
        <a href="inventory.php" class="w-3/4 text-center text-lg font-bold hover:bg-blue-700 p-2 rounded">Inventory</a>
        <a href="setting.php" class="w-3/4 text-center text-lg font-bold hover:bg-blue-700 p-2 rounded">Settings</a>
        <button class="w-3/4 text-center text-lg font-bold hover:bg-blue-700 p-2 rounded logout">Logout</button>
    </div>

    <div class="w-full min-h-screen pt-16 p-5">

        <h1 class="text-2xl m-2">Manage Inventory</h1>

        <div class="flex justify-between items-center">

            <input id="search_item" type="search" placeholder="Search Item" class="text-xl p-2 cursor-pointer border border-blue-500 rounded">

            <div class="flex space-x-2">
                <button id="new_item" class="p-2 bg-green-600 rounded text-white opacity-80 hover:opacity-100">+ New Item</button>
            </div>

        </div>

        <div id="inventory_table" class="max-h-96 flex flex-wrap p-5 my-5 overflow-y-auto shadow">

        </div>

    </div>

    <!-- New Item Modal -->
    <div id="new_item_modal" class="w-2/5 h-96 border border-black absolute inset-2/4 transform -translate-x-2/4 -translate-y-2/4 rounded bg-gray-100 p-3 flex flex-col space-y-3 hidden">
        
        <h1 class="text-2xl uppercase">Add New Item</h1>
        <p class="text-gray-500">Add the item to the inventory to make it accessible.</p>

        <input type="text" id="item_name" placeholder="Item Name" class="text-xl p-2 cursor-pointer border border-blue-500 rounded">
        <input type="number" id="item_cost" placeholder="Item Cost" class="text-xl p-2 cursor-pointer border border-blue-500 rounded">
        <label class="m-0">Item Photo:</label>
        <input type="file" id="item_image" class="text-xl p-2 cursor-pointer border border-blue-500 rounded">
        
        <div class="flex items-center space-x-3">
            <button class="w-full p-3 bg-red-500 rounded opacity-80 hover:opacity-100 close-popup">Cancel</button>
            <button id="add_item_btn" class="w-full p-3 bg-blue-500 rounded opacity-80 hover:opacity-100">Add Item</button>
        </div>
    </div>

    <!-- Edit Item Modal -->
    <div id="edit_item_modal" class="w-2/4 h-56 border border-black absolute inset-2/4 transform -translate-x-2/4 -translate-y-2/4 rounded bg-gray-100 p-3 flex items-center space-x-3 hidden">
        <div>
            <h1 id="edit_item_name" class="text-2xl"></h1>
            <img id="edit_item_image" src="" alt="" class="w-56 h-40 object-cover">
        </div>
        <div class="flex flex-col space-y-1">
            <input id="n_item" type="text" placeholder="Item Name" class="text-xl p-2 cursor-pointer border border-blue-500 rounded">
            <input id="n_cost" type="number" placeholder="Item Cost" class="text-xl p-2 cursor-pointer border border-blue-500 rounded">
            <input id="n_image" type="file" class="text-xl p-2 cursor-pointer border border-blue-500 rounded">
            <div class="flex space-x-2">
                <button class="w-full p-2 bg-red-500 rounded opacity-80 hover:opacity-100 close-popup">Cancel</button>
                <button id="save_edit" class="w-full p-2 bg-blue-500 rounded opacity-80 hover:opacity-100">Save Changes</button>
            </div>
        </div>
    </div>
    
    <script src="js/general.js"></script>
    <script src="js/inventory.js"></script>
</body>
</html>