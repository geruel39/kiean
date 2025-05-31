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
    <div class="w-full h-screen flex flex-col justify-center items-center bg-gray-200">
        <h1 class="text-4xl my-5">KieAn Tutorial Center</h1>
        <div class="w-1/5 flex flex-col items-center p-5 bg-blue-500 space-y-5 rounded">
            <h1 class="text-3xl">Login</h1>
            <input type="text" placeholder="Username" class="p-1 rounded w-full" id="username">
            <input type="password" placeholder="Password" class="p-1 rounded w-full" id="password">
            <button class="bg-white hover:bg-gray-400 w-full rounded p-2" id="login">Login</button>
        </div>
    </div>

    <script src="js/login.js"></script>
</body>
</html>