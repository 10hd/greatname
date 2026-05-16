<?php include 'auth.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="shortcut icon" href="coliseum.svg" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
    <title>greatname.net | Registration</title>
</head>
<body class="bg-black text-white flex flex-col min-h-screen items-center m-0 p-0 h-full">
    <header class="mt-5">
        <h1 class="text-4xl font-bold">great<span class="text-[#6674b2]">name</span>.net</h1>
    </header>
    <main class="flex flex-col gap-4 justify-center flex-1 items-center w-full max-w-sm px-4">
        <form action="auth.php" method="POST" class="w-full">
            <h2 class="text-2xl font-bold mb-4 text-center">Registration & Login</h2>
            <div>
                <p class="text-lg text-mist-400 pl-4">Username</p>
                <input class="bg-mist-200 text-black rounded-full mb-7 mt-1 w-full p-2 focus:outline-none focus:ring-0" type="text" name="usernameField" id="usernameInput">
            </div>
            <div>
                <p class="text-lg text-mist-400 pl-4">Email <span class="text-sm float-right bg-gray-500 opacity-50 text-white p-1 rounded-full mr-2">optional</span></p>
                <input class="peer bg-mist-200 text-black rounded-full mb-1 mt-1 w-full p-2 focus:outline-none focus:ring-0" type="text" name="emailField" id="emailInput">
                <p class="invisible peer-focus:visible text-red-400 text-sm ml-4 mb-1">Only required to reset password later.</p>
            </div>
            <div>
                <p class="text-lg text-mist-400 pl-4">Password <a href="resetPassword.php" class="text-sm text-blue-500 float-right opacity-80 p-1 mr-2 hover:underline">forgot?</a></p>
                <input class="bg-mist-200 text-black rounded-full mb-4 mt-1 w-full p-2 focus:outline-none focus:ring-0" type="password" name="passwordField" id="passwordInput">
            </div>
            <div class="hidden">
                <label for="phone_number">Phone Number</label>
                <input type="text" name="phone_number" id="phone_number" autocomplete="off" tabindex="-1">
            </div>
            <div class="flex flex-col items-center justify-center gap-2">
                <button name="action" value="login" type="submit" id="loginButton" class="mt-6 bg-[#6674b2] opacity-80 border-2 border-transparent hover:bg-slate-800 hover:border-white text-white font-bold w-80 py-2 px-4 rounded-full hover:cursor-pointer transition-colors">Sign In</button>
                <button name="action" value="register" type="submit" id="registerButton" class="mt-2 bg-gray-500 opacity-80 border-2 border-transparent hover:bg-slate-900 hover:border-white text-white font-bold w-80 py-2 px-4 rounded-full hover:cursor-pointer transition-colors">Register</button>
            </div>
            <?php
            $msg = "";
            if (isset($_GET["error"]) && $_GET["error"] === "invalid") {
                $msg = "Invalid username or password.";
            } elseif (isset($_GET["error"])) {
                $msg = "An error occurred: " . htmlspecialchars($_GET["error"]);
            }
            ?>
            <p class="text-md font-bold mt-2 text-center text-red-400" id="errorText"><?php echo $msg; ?></p>
        </form>
    </main>
    <footer class="mb-5 w-full text-center">
        <p>Copyright &copy; <span id="year"></span> greatname. All rights reserved.</p>
    </footer>
    <script src="script.js"></script>
    <script src="year.js"></script>
</body>
</html>