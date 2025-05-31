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

    <div class="w-1/4 bg-gray-200 fixed top-16 right-1 shadow rounded flex flex-col items-center p-2 z-100 hidden" id="change_branch_modal">
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
            <p class="text-lg cursor-pointer hover:text-blue-700 p-3 rounded bg-blue-500 tab">Add Tutees</p>
            <p class="text-lg cursor-pointer hover:text-blue-700 p-3 rounded tab">Tutee List</p>
            <p class="text-lg cursor-pointer hover:text-blue-700 p-3 rounded tab">Tutees Information</p>
            <p class="text-lg cursor-pointer hover:text-blue-700 p-3 rounded tab">Archives</p>
        </div>

        <!-- Add New Tutees -->
        <div class="page">

            <h1 class="text-2xl">Add New Tutee</h1>
            <p class="text-gray-500">Add Tutee information so you can enroll them everytime</p>
            <hr class="border border-black my-1">
            <div class="flex justify-between">

                <div class="flex flex-col w-full space-y-3">

                    <h3 class="text-xl font-bold">Tutee Information</h3>
                    <input type="text" placeholder="Firstname" class="p-2 w-3/4" id="fname">
                    <input type="text" placeholder="Middlename" class="p-2 w-3/4" id="mname">
                    <input type="text" placeholder="Lastname" class="p-2 w-3/4" id="lname">
                    <select id="gender" class="p-2 w-3/4">
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                    <p>Birthday:</p>
                    <input type="date" class="p-2 w-3/4" id="bday">

                </div>
                
                <div class="flex flex-col w-full space-y-3">

                    <h3 class="text-xl font-bold">Guardian/Parent Information</h3>
                    <input type="text" placeholder="Firstname" class="p-2 w-3/4" id="g_fname">
                    <input type="text" placeholder="Middlename" class="p-2 w-3/4" id="g_mname">
                    <input type="text" placeholder="Lastname" class="p-2 w-3/4" id="g_lname">
                    <input type="number" placeholder="Phone Number" class="p-2 w-3/4" id="phone">
                    <input type="email" placeholder="Email Address" class="p-2 w-3/4" id="email">
                    <input type="address" placeholder="Home Address" class="p-2 w-3/4" id="address">

                </div>

            </div>

            <div class="flex mt-2 py-5 border-t-2 border-gray-500">
                <button class="w-full p-2 m-1 rounded bg-blue-500 text-white opacity-80 hover:opacity-100" id="add_info">Add Tutee Information</button>
            </div>

        </div>

        <!-- Tutees List -->
        <div class="page hidden">

            <div class="flex items-center justify-end">
                <h1 class="text-2xl mr-auto">Tutees List - Monthly</h1>
                <div class="flex space-x-1">
                    <input id="tutee_search" type="search" placeholder="Search Name" class="text-xl p-2 cursor-pointer border border-blue-500 rounded">
                    <select id="tutee_program" class="text-xl p-2 cursor-pointer border border-blue-500 rounded programs"></select>
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
                    <select id="tutee_year" class="text-xl p-2 cursor-pointer border border-blue-500 rounded years"></select>
                </div>
            </div>

            <div class="flex p-2 my-1 rounded bg-blue-500">
                <p class="w-full font-bold">Full name</p>
                <p class="w-full font-bold">Program</p>
                <p class="w-full font-bold"></p>
            </div>

            <div id="tutee-table">
                <div class="flex p-2 my-1 rounded border border-blue-500">
                    <p class="w-full font-bold text-center">Loading...</p>
                </div>
            </div>
              

        </div>

        <!-- Tutees Information -->
        <div class="page hidden">

            <div class="flex items-center">
                <h1 class="text-2xl mr-auto">Tutees Information</h1>
                <input type="search" id="search_std_info" placeholder="Search Tutee Name" class="focus:outline-none text-xl p-2 cursor-pointer border border-blue-500 rounded">
            </div>

            <div class="flex p-2 my-1 rounded bg-blue-500">
                <p class="w-full font-bold">First name</p>
                <p class="w-full font-bold">Middle name</p>
                <p class="w-full font-bold">Last name</p>
                <p class="w-full font-bold">Gender</p>
                <p class="w-full font-bold">Age</p>
                <p class="w-full font-bold"></p>
            </div>

            <div id="tutees-info-table">
                <div>
                    <div class="flex p-2 my-1 rounded border border-blue-500">
                        <p class="w-full text-sm font-bold text-center">Loading...</p>
                    </div>
                </div>
            </div>

        </div>

        <!-- Archives -->
        <div class="page hidden">
            Not Available Yet
        </div>

    </div>

    <!-- Enroll Modal -->
    <div id="enroll_student_modal" class="w-2/5 h-3/4 border border-black absolute inset-2/4 transform -translate-x-2/4 -translate-y-2/4 rounded bg-gray-100 p-3 flex flex-col space-y-3 hidden">
        <h1 class="text-2xl uppercase">Enroll Tutee</h1>
        <p class="text-gray-500">Enroll Tutee to the specific Month-Year</p>
        <select id="enroll_year" class="text-xl p-2 cursor-pointer border border-blue-500 rounded years"></select>
        <select id="enroll_month" class="text-xl p-2 cursor-pointer border border-blue-500 rounded months">
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
        <select id="program_select" class="text-xl p-2 cursor-pointer border border-blue-500 rounded programs" ></select>
        <div id="program_list_table">

        </div>
        <input type="number" id="enroll_rates" placeholder="Rates" class="text-xl p-2 cursor-pointer border border-blue-500 rounded">
        <div class="flex items-center space-x-3">
            <button class="w-full p-3 bg-red-500 rounded opacity-80 hover:opacity-100 close-popup">Cancel</button>
            <button id="confirm_enroll" class="w-full p-3 bg-blue-500 rounded opacity-80 hover:opacity-100">Confirm</button>
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

    <!-- Enroll Modal -->
    <div id="edit_details_modal" class="w-2/5 h-full border border-black absolute inset-2/4 transform -translate-x-2/4 -translate-y-2/4 rounded bg-gray-100 p-3 flex flex-col space-y-3 hidden">
        <h1 class="text-2xl uppercase">Edit Tutee Details</h1>

        <div class="flex flex-col space-y-1">
            <h3 class="font-bold">TUTEE INFORMATION</h3>
            <input type="text" placeholder="First name" class="p-1" id="e-fname">
            <input type="text" placeholder="Middle name" class="p-1" id="e-mname">
            <input type="text" placeholder="Last name" class="p-1" id="e-lname">
            <select class="p-1" id="e-gender">
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
            <input type="date" class="p-1" id="e-bday">
        </div>

        <div class="flex flex-col space-y-1">
            <h3 class="font-bold">GUARDIAN/PARENT INFORMATION</h3>
            <input type="text" placeholder="First name" class="p-1" id="e-gfname">
            <input type="text" placeholder="Middle name" class="p-1" id="e-gmname">
            <input type="text" placeholder="Last name" class="p-1" id="e-glname">
            <input type="text" placeholder="Email Address" class="p-1" id="e-email">
            <input type="number" placeholder="Phone Number" class="p-1" id="e-phone">
            <input type="text" placeholder="Home Addess" class="p-1" id="e-address">
        </div>


        <div class="flex items-center space-x-3">
            <button class="w-full p-3 bg-red-500 rounded opacity-80 hover:opacity-100 close-popup">Cancel</button>
            <button id="save_edit_details" class="w-full p-3 bg-blue-500 rounded opacity-80 hover:opacity-100">Save Changes</button>
        </div>
    </div>

    
    <script src="js/general.js"></script>
    <script src="js/b_student.js"></script>
</body>
</html>