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
            <p class="text-lg cursor-pointer hover:text-blue-700 p-3 rounded bg-blue-500 tab">Tutee List</p>
            <p class="text-lg cursor-pointer hover:text-blue-700 p-3 rounded tab">Tutees Information</p>
            <p class="text-lg cursor-pointer hover:text-blue-700 p-3 rounded tab">Program List</p>
        </div>

        <!-- Tutee List -->
        <div class="page">

            <div class="flex items-center justify-end">
                <h1 class="text-2xl mr-auto">Tutees List - Monthly</h1>
                <select id="tutee_month" class="text-xl p-2 cursor-pointer border border-blue-500 rounded months">
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
                <select id="tutee_year" class="text-xl p-2 cursor-pointer border border-blue-500 rounded mx-5 years"></select>
            </div>

            <div class="flex p-2 my-1 rounded bg-blue-500">
                <p class="w-full font-bold">Fullname</p>
                <p class="w-full font-bold">Program</p>
                <p class="w-full font-bold">Rates</p>
                <p class="w-full font-bold">Paid Amount</p>
            </div>

            <div id="tutee-table">
                <div class="flex p-2 my-1 rounded border border-blue-500">
                    <p class="w-full font-bold text-center">Loading...</p>
                </div>
            </div>
              

        </div>

        <!-- Tutee Informations -->
        <div class="page hidden">

            <div class="flex items-center justify-end">
                <h1 class="text-2xl mr-auto">Tutees Information</h1>
                <div>
                    <input id="info_search" type="search" placeholder="Search Tutee Name" class="text-xl p-2 cursor-pointer border border-blue-500 rounded">
                    <select id="info_branch" class="text-xl p-2 cursor-pointer border border-blue-500 rounded">
                        <option value="">All Branch</option>
                    </select>
                </div>
            </div>

            <div class="flex p-2 my-1 rounded bg-blue-500">
                <p class="w-full font-bold">Fullname</p>
                <p class="w-full font-bold">Gender</p>
                <p class="w-full font-bold">Age</p>
                <p class="w-full font-bold">Guardian/Parent</p>
                <p class="w-full font-bold">Email</p>
                <p class="w-full font-bold">Branch</p>
            </div>

            <div id="tutees-info-table">
                <div class="flex p-2 my-1 rounded border border-blue-500">
                    <p class="w-full text-sm font-bold">Loading...</p>
                </div>
            </div>

        </div>

        <!-- Program List -->
        <div class="page hidden">

            <h1 class="text-2xl">Program List</h1>
            <div class="flex">
                <p id="add_program" class="min-w-1/6 p-2 m-3 cursor-pointer hover:bg-blue-500 rounded text-center text-2xl">+ ADD PROGRAM</p>
                <p id="add_under" class="min-w-1/6 p-2 m-3 cursor-pointer hover:bg-blue-500 rounded text-center text-2xl">+ ADD PROGRAM'S LIST</p>
            </div>

            <div class="flex py-3 my-1 rounded bg-blue-500">
                <p class="w-full text-xl font-bold p-1">Program Title</p>
                <p class="w-full text-xl font-bold p-1">Program List</p>
            </div>

            <div id="programs_table">
                <div>
                    <div class="flex py-3 my-1 rounded border border-blue-500">
                        <p class="w-full text-xl font-bold p-1">Non-Schooler</p>
                        <div class="w-full">
                            <p class="w-full text-md font-bold p-1">None</p>
                        </div>

                    </div>
                </div>
            </div>

        </div>

        <!-- Add Program -->
        <div id="program_modal" class="w-2/5 h-40 border border-black absolute inset-2/4 transform -translate-x-2/4 -translate-y-2/4 rounded bg-gray-100 p-3 flex flex-col space-y-3 hidden">
            <h3 class="text-xl uppercase">Add New Program</h3>
            <input type="text" placeholder="Program Title" class="p-2 rounded" id="program">
            <div class="flex justify-end space-x-5">
                <button class="p-2 rounded bg-blue-500" id="add_program_btn">Add Program</button>
                <button class="p-2 rounded bg-red-500 close-popup">Cancel</button>
            </div>
        </div>

        <!-- Add Program List -->
        <div id="under_modal" class="w-2/5 h-60 border border-black absolute inset-2/4 transform -translate-x-2/4 -translate-y-2/4 rounded bg-gray-100 p-3 flex flex-col space-y-3 hidden">
            <h3 class="text-xl uppercase">Add New Program List</h3>
            <select id="under_program" class="p-2 rounded">
                <option value="">Select Program</option>
            </select>
            <input type="text" placeholder="Program List Title" class="p-2 rounded" id="program_list">
            <div class="flex justify-end space-x-5">
                <button class="p-2 rounded bg-blue-500" id="add_proglist">Add Program List</button>
                <button class="p-2 rounded bg-red-500 close-popup">Cancel</button>
            </div>
        </div>

    </div>

    <script src="js/general.js"></script>
    <script src="js/student.js"></script>
</body>
</html>