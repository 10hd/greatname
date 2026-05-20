<?php
session_start();
include 'db.php';

$target_id = $_GET['id'] ?? null;

if (!$target_id) {
    die("No username specified.");
}

$query = "SELECT user_id, name, created_at, deactivated, is_admin, has_title, description, emoji, reason FROM accounts WHERE name = $1";
$result = pg_query_params($dbconn, $query, [$target_id]);

if ($result && pg_num_rows($result) > 0) {
    $user = pg_fetch_assoc($result);
} else {
    die("User not found.");
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
    <title>greatname.net | <?php echo htmlspecialchars($user['name']); ?>'s Profile</title>
</head>
<body class="bg-black text-white flex flex-col min-h-screen items-center m-0 p-0 h-full">
    <header class="mt-5">
        <h1 class="text-4xl font-bold">great<span class="text-[#6674b2]">name</span>.net</h1>
    </header>
    <main class="flex flex-col gap-4 justify-center flex-1 items-center w-full max-w-lg">
        <?php if ($user['deactivated'] == 'f'): ?>
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

                    ?>;"><?php echo htmlspecialchars($user['name']); ?></span>
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
            <h4 class="text-lg font-bold mb-1">Description:</h4>
            <p class="text-mist-300 text-lg mb-4">
                <?php echo htmlspecialchars($user['description'] ?? 'No description set.'); ?>
            </p>
            <h4 class="text-lg font-bold mb-1">Emoji:</h4>
            <p class="text-mist-300 text-lg mb-4">
                <?php echo htmlspecialchars($user['emoji'] ?? 'No emoji set.'); ?>
            </p>
        </div>
        <div class="flex gap-4 mt-2">
            <a href="/dashboard" class="text-blue-500 text-lg hover:underline">Dashboard</a>
            <span class="text-mist-500 cursor-default">|</span>
            <a href="logout.php" class="text-blue-500 text-lg hover:underline">Log out</a>
        </div>
        <?php else: ?>
            <div class="text-center p-8">
                <h2 class="text-2xl font-bold mb-2 text-red-500">This user has been deactivated.</h2>
                <?php if (!empty($user['reason'])): ?>
                    <p class="text-lg text-gray-400 mt-2 font-bold">
                        Reason: <span class="text-white font-normal"><?php echo htmlspecialchars($user['reason']); ?></span>
                    </p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <div class="flex gap-4 mt-2">
            <a href="/dashboard" class="text-blue-500 text-lg hover:underline">Back</a>
            <span class="text-mist-500 cursor-default">|</span>
            <a href="logout.php" class="text-blue-500 text-lg hover:underline">Log out</a>
        </div>
    </main>
    <footer class="mb-5 w-full text-center">
        <p>Copyright &copy; <span id="year"></span> greatname. All rights reserved.</p>
    </footer>
    <script src="year.js"></script>
</body>
</html>