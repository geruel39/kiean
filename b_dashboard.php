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
        
        

    </div>
    
    <script src="js/general.js"></script>
</body>
</html>