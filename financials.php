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
        
        <div class="flex space-x-5 m-2 border-b-2 border-black py-2">
            <p class="text-lg cursor-pointer hover:text-blue-700 p-3 rounded bg-blue-500 tab">Overview</p>
            <p class="text-lg cursor-pointer hover:text-blue-700 p-3 rounded tab">Branches Records</p>
        </div>

        <div class="page ">

            <div class="flex items-center justify-between">

                <div class="flex items-center space-x-2">
                    <h1 class="text-2xl">Overview <span id="o_date" class="text-red-500">...</span></h1>
                    <button class="text-sm hover:text-white hover:bg-gray-800 px-2 rounded"> Download </button>             
                </div>
                <div>
                    <select id="o_month" class="text-xl p-2 cursor-pointer border border-blue-500 rounded months">
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
                    <select id="o_year" class="text-xl p-2 cursor-pointer border border-blue-500 rounded years"></select>
                </div>

            </div>

            <div class="flex flex-col">
                <div class="flex">
                    <div class="w-full h-60 p-5 m-2 rounded bg-blue-700">
                        <h2 class="text-white text-2xl font-bold mb-4">Accumulated Payment</h2>
                        <p class="text-white text-sm">Payments have currently been collected and received for this month from all branches.</p>
                        <div class="flex items-center space-x-5 mt-5">
                            <p class="text-8xl text-white">₱</p>
                            <p id="payment" class="text-7xl text-white">0.00</p>
                        </div>
                    </div>
                    <div class="w-full h-60 p-5 m-2 rounded bg-red-700">
                        <h2 class="text-white text-2xl font-bold mb-4">Expenses</h2>
                        <p class="text-white text-sm">Total expenses recorded this month from all branches.</p>
                        <div class="flex items-center space-x-5 mt-5">
                            <p class="text-8xl text-white">₱</p>
                            <p id="expenses" class="text-7xl text-white">0.00</p>
                        </div>
                    </div>
                </div>
                <div class="flex">
                    <div class="w-full h-60  p-5 m-2 rounded bg-yellow-700">
                        <h2 class="text-white text-2xl font-bold mb-4">Supply Used</h2>
                        <p class="text-white  text-sm">Total cost of all school supplies used this month from all branches.<p>
                        <div class="flex items-center space-x-5 mt-5">
                            <p class="text-8xl text-white">₱</p>
                            <p id="supply" class="text-7xl text-white">0.00</p>
                        </div>
                    </div>
                    <div class="w-full h-60  p-5 m-2 rounded bg-green-700">
                        <h2 class="text-white text-2xl font-bold mb-4">Provisional Income</h2>
                        <p class="text-white text-sm">Estimated provisional income based on current calculations from all branches.</p>
                        <div class="flex items-center space-x-5 mt-5">
                            <p class="text-8xl text-white">₱</p>
                            <p id="income" class="text-7xl text-white">0.00</p>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>

        <div class="page hidden">

            <div class="flex items-center justify-between my-1">

                <div class="flex items-center space-x-5">
                    <h1 class="text-2xl">Branches Record <span id="r_date" class="text-red-500">...</span> </h1>
                </div>
                <div>
                    <select id="r_month" class="text-xl p-2 cursor-pointer border border-blue-500 rounded months">
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
                    <select id="r_year" class="text-xl p-2 cursor-pointer border border-blue-500 rounded years"></select>
                </div>
           
            </div>

            <div class="flex flex-col">

                <div class="flex p-2 my-1 rounded bg-red-700">
                    <p class="w-full font-bold text-white tracking-widest">Branch</p>
                    <p class="w-full font-bold text-white tracking-widest">Sales</p>
                    <p class="w-full font-bold text-white tracking-widest">Expenses</p>
                    <p class="w-full font-bold text-white tracking-widest">Supply</p>
                    <p class="w-full font-bold text-white tracking-widest">Income</p>
                    <p class="w-full font-bold text-white tracking-widest">Commission</p>
                </div>

                <div id="records_table">
                    <div class="flex p-2 my-1 rounded border border-red-700 hover:bg-gray-300">
                        <p class="w-full font-bold text-center tracking-widest">Loading...</p>
                    </div>
                </div>
                <div class="flex p-2 my-1 bg-red-700">
                        <p class="w-full font-bold text-sm text-white tracking-widest">TOTAL</p>
                        <p id="t_p" class="w-full font-bold text-sm text-white"></p>
                        <p id="t_e" class="w-full font-bold text-sm text-white"></p>
                        <p id="t_s" class="w-full font-bold text-sm text-white"></p>
                        <p id="t_i" class="w-full font-bold text-sm text-white"></p>
                        <p id="t_c" class="w-full font-bold text-sm text-white"></p>
                </div>

            </div>

        </div>

    </div>
    
    <script src="js/general.js"></script>
    <script src="js/financials.js"></script>
</body>
</html>