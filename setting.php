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
    
    <script src="js/general.js"></script>
</body>
</html>