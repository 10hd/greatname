<?php
session_start();
include 'db.php';

if (!isset($_SESSION["username"])) {
    header("Location: /");
    exit();
}

$query = "SELECT user_id, created_at, deactivated, is_admin, has_title, description, emoji, email FROM accounts WHERE name = $1";
$result = pg_query_params($dbconn, $query, [$_SESSION["username"]]);

if ($result) {
    $user = pg_fetch_assoc($result);
} else {
    $user = null;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="shortcut icon" href="coliseum.svg" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
    <title>greatname.net | Profile</title>
</head>
<body class="bg-black text-white flex flex-col min-h-screen items-center m-0 p-0 h-full">
    <header class="mt-5">
        <h1 class="text-4xl font-bold">great<span class="text-[#6674b2]">name</span>.net</h1>
    </header>
    <main class="flex flex-col gap-4 justify-center flex-1 items-center w-full max-w-lg">
        <div class="text-center mb-2">
            <h2 class="text-2xl font-bold mt-4"><span id="date"></span>, <?php echo htmlspecialchars($_SESSION["username"]); ?>!</h2>
        </div>

        <div class="p-8 w-full">
            <h2 class="text-2xl font-bold mb-2">Profile info</h2>
            <h3 class="text-xl mb-4 text-mist-300">
                Username: 
                <span
                    class="text-mist-400"
                    style="color: <?php
                    $currentTitles = strtolower($user['has_title'] ?? '');

                    if ($user['is_admin'] === 't') {
                        echo '#FF0000';
                    } elseif (str_contains($currentTitles, 'developer')) {
                        echo '#780a78';
                    } elseif (str_contains($currentTitles, 'alpha')) {
                        echo '#FF9CF4';
                    } elseif (str_contains($currentTitles, 'tester')) {
                        echo '#8CECFF';
                    } elseif (str_contains($currentTitles, 'sponsor')) {
                        echo '#5ED627';
                    } else {
                        echo 'inherit';
                    }

                    ?>;"><?php echo htmlspecialchars($_SESSION["username"]); ?></span>
                <br>
                Account created: <span class="text-mist-400"><?php $date = new DateTime($user['created_at']); echo $date->format('d/m/Y H:i'); ?></span>
                <br>
                User ID: <span class="text-mist-400"><?php echo $user['user_id']; ?></span>
                <br>
                Titles: 
                <span class="text-mist-400">
                    <?php
                        $titles = [];
                        if ($user['is_admin'] === 't') {
                            $titles[] = '<span style="color: #FF0000;">admin</span>';
                        }
                        if (!empty($user['has_title'])) {
                            $raw_titles = explode(',', $user['has_title']);
                            foreach ($raw_titles as $title) {
                                $cleanTitle = strtolower(trim($title));
                                $color = ($cleanTitle === 'developer') ? '#780a78' : (($cleanTitle === 'alpha') ? '#FF9CF4' : (($cleanTitle === 'tester') ? '#8CECFF' : (($cleanTitle === 'sponsor') ? '#5ED627' : 'inherit')));
                                $extra = ($cleanTitle === 'developer') ? '' : (($cleanTitle === 'alpha') ? '' : (($cleanTitle === 'tester') ? '' : (($cleanTitle === 'sponsor') ? 'text-shadow: 0 0 12px rgb(0, 255, 64);' : '')));
                                $displayTitle = htmlspecialchars($cleanTitle);
                                $titles[] = "<span style=\"color: $color; $extra\">$displayTitle</span>";
                            }
                        }

                        if (empty($titles)) {
                            echo 'none';
                        } else {
                            echo implode(', ', $titles);
                        }
                    ?>
                </span>
                <br>
                Account status: <span class="text-mist-400"><?php echo ($user['deactivated'] === 't') ? 'Deactivated' : 'Alive'; ?></span>
            </h3>
            <form action="description.php" method="POST" class="flex flex-col gap-2">
                <h4 class="text-lg font-bold mb-1">Description:</h4>
                <p class="text-mist-300 text-lg mb-4">
                  <input type="text" name="new_description" value="<?php echo htmlspecialchars($user['description'] ?? ''); ?>" class="bg-transparent border-b border-mist-400 focus:outline-none focus:ring-0 flex-1 text-mist-300 text-lg">
                  <button type="submit" class="text-blue-500 text-lg hover:underline cursor-pointer">Apply</button>
                </p>
            </form>
            <form action="emoji.php" method="POST" class="flex flex-col gap-2">
                <h4 class="text-lg font-bold mb-1">Emoji:</h4>
                <p class="text-mist-300 text-lg mb-4">
                  <input type="text" name="personality_emoji" maxlength="20" value="<?php echo htmlspecialchars($user['emoji'] ?? ''); ?>" class="bg-transparent border-b border-mist-400 focus:outline-none focus:ring-0 flex-1 text-mist-300 text-lg">
                  <button type="submit" class="text-blue-500 text-lg hover:underline cursor-pointer">Apply</button>
                </p>
            </form>
            <form action="email.php" method="POST" class="flex flex-col gap-2">
                <h4 class="text-lg font-bold mb-1">Email:</h4>
                <p class="text-mist-300 text-lg mb-4">
                  <input type="text" name="assigned_email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" class="bg-transparent border-b border-mist-400 focus:outline-none focus:ring-0 flex-1 text-mist-300 text-lg">
                  <button type="submit" class="peer text-blue-500 text-lg hover:underline cursor-pointer">Apply</button>
                </p>
            </form>
        </div>
        <div class="flex gap-4 mt-2">
            <a href="/dashboard" class="text-blue-500 text-lg hover:underline">Back</a>
            <span class="text-mist-500 cursor-default">|</span>
            <a href="logout.php" class="text-blue-500 text-lg hover:underline">Log out</a>
            <span class="text-mist-500 cursor-default">|</span>
            <a href="titles.html" class="text-blue-500 text-lg hover:underline">Title Info</a>
        </div>
    </main>
    <footer class="mb-5 w-full text-center">
        <p>Copyright &copy; <span id="year"></span> greatname. All rights reserved.</p>
    </footer>
    <script src="timeofday.js"></script>
    <script src="year.js"></script>
</body>
</html>