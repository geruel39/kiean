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

        <!-- Tabs Section -->
        <div class="flex space-x-5 m-2 border-b-2 border-black py-2">
            <p class="text-lg cursor-pointer hover:text-blue-700 p-3 rounded bg-blue-500 tab">Accounts</p>
            <p class="text-lg cursor-pointer hover:text-blue-700 p-3 rounded tab">Branches</p>
        </div>

        <!-- Accounts Page -->
        <div class="page">
            <h1 class="text-4xl my-3">Manage Accounts</h1>
            <div class="flex">
                <p id="add_account" class="min-w-1/6 p-2 m-3 cursor-pointer hover:bg-blue-500 rounded text-center text-2xl">+ ADD ACCOUNT</p>
                <p id="add_role" class="min-w-1/6 p-2 m-3 cursor-pointer hover:bg-blue-500 rounded text-center text-2xl">+ ADD ROLE</p>
            </div>
            <div class="flex p-3 rounded bg-blue-500">
                <p class="w-full text-xl font-bold">Username</p>
                <p class="w-full text-xl font-bold">Role</p>
                <p class="w-full text-xl font-bold text-center"></p>
            </div>

            <div id="account-table">
                <div class="flex p-3 rounded border border-blue-500">
                    <p class="w-full text-xl text-center font-bold">Loading...</p>
                </div>
            </div>
        </div>

        <!-- Branches Page -->
        <div class="page hidden">
            <h1 class="text-4xl my-3">Manage Branches</h1>
            <div class="flex">
                <p id="add_branch" class="min-w-1/6 p-2 m-3 cursor-pointer hover:bg-blue-500 rounded text-center text-2xl">+ ADD BRANCH</p>
            </div>
            <div class="flex p-2 rounded bg-blue-500">
                <p class="w-full text-xl font-bold">Branch Name</p>
                <p class="w-full text-xl font-bold">Commission %</p>
                <p class="w-full text-xl font-bold">Address</p>
                <p class="w-full text-xl font-bold text-center">Access</p>
                <p class="w-full text-xl font-bold text-center"></p>
            </div>

            <div id="branch-table">
                <div>
                    <div class="flex items-center p-2 my-1 rounded border border-blue-500">
                        <p class="w-full text-xl font-bold text-center">Loading...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Account Modal -->
        <div id="account_modal" class="w-2/5 h-96 border border-black absolute inset-2/4 transform -translate-x-2/4 -translate-y-2/4 rounded bg-gray-100 p-3 flex flex-col space-y-3 hidden">
            
            <h3 class="text-xl uppercase">Add New Account</h3>
            <input type="text" placeholder="Username" class="p-2 rounded" id="username">
            <input type="password" placeholder="Password" class="p-2 rounded" id="password">
            <input type="password" placeholder="Re-Enter Password" class="p-2 rounded" id="password2">
            <select id="role" class="p-2 rounded"></select>
            <div class="flex justify-end space-x-5">
                <button class="p-2 rounded bg-blue-500" id="add_account_btn">Add Account</button>
                <button class="p-2 rounded bg-red-500 close-popup">Cancel</button>
            </div>

        </div>

        <!-- Role Modal -->
        <div id="role_modal" class="w-2/5 h-40 border border-black absolute inset-2/4 transform -translate-x-2/4 -translate-y-2/4 rounded bg-gray-100 p-3 flex flex-col space-y-3 hidden">
            <h3 class="text-xl uppercase">Add New Role</h3>
            <input type="text" placeholder="Role Title" class="p-2 rounded" id="role_title">
            <div class="flex justify-end space-x-5">
                <button class="p-2 rounded bg-blue-500" id="add_role_btn">Add Role</button>
                <button class="p-2 rounded bg-red-500 close-popup">Cancel</button>
            </div>
        </div>

        <!-- Branch Modal -->
        <div id="branch_modal" class="w-2/5 h-80 border border-black absolute inset-2/4 transform -translate-x-2/4 -translate-y-2/4 rounded bg-gray-100 p-3 flex flex-col space-y-3 hidden">
            <h3 class="text-xl uppercase">Add New Branch</h3>
            <input type="text" placeholder="Branch Name" class="p-2 rounded" id="branch_name">
            <input type="number" placeholder="Commission %" class="p-2 rounded" id="commission">
            <input type="text" placeholder="Location" class="p-2 rounded" id="location">
            <div class="flex justify-end space-x-5">
                <button class="p-2 rounded bg-blue-500" id="add_branch_btn">Add Branch</button>
                <button class="p-2 rounded bg-red-500 close-popup">Cancel</button>
            </div>
        </div>

        <!-- Give Access Modal -->
        <div id="select_account_modal" class="w-2/5 h-80 border border-black absolute inset-2/4 transform -translate-x-2/4 -translate-y-2/4 rounded bg-gray-100 p-3 flex flex-col space-y-3 overflow-y-auto hidden">
            
            <div class="flex justify-between px-2">
                <h3 class="text-xl uppercase">Select Account</h3>
                <button class="bg-red-500 p-1 rounded close-popup">Close</button>
            </div>

            <p class="text-gray-500">Click to select.</p>

            <div class="flex bg-blue-500 p-1 rounded">
                <p class="w-full font-bold text-center">Username</p>
                <p class="w-full font-bold text-center">Role</p>
            </div>
            <div id="select_account">
                <div>
                    <div class="flex border border-blue-500 p-1 my-1 rounded cursor-pointer hover:bg-gray-400">
                        <p class="w-full font-bold text-center">Loading...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Branch Details -->
        <div id="edit_branch_modal" class="w-2/5 h-80 border border-black absolute inset-2/4 transform -translate-x-2/4 -translate-y-2/4 rounded bg-gray-100 p-3 flex flex-col space-y-3 overflow-y-auto hidden">
            <h3 class="text-xl">EDIT BRANCH DETAILS</h3>

            <input type="text" placeholder="Branch Name" id="e-name" class="p-2">
            <input type="number" placeholder="Commission" id="e-commission" class="p-2">
            <input type="text" placeholder="Location" id="e-location" class="p-2">

            <div class="flex items-center space-x-3">
                <button class="w-full p-3 bg-red-500 rounded opacity-80 hover:opacity-100 close-popup">Cancel</button>
                <button id="save_edit_details" class="w-full p-3 bg-blue-500 rounded opacity-80 hover:opacity-100">Save Changes</button>
            </div>
        </div>

        

    </div>
    
    <script src="js/general.js"></script>
    <script src="js/branch.js"></script>
</body>
</html>