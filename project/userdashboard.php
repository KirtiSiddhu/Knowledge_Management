<?php
session_start();

// ✅ Database connection
$host = 'localhost';
$dbname = 'docrepo';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Fallback to session-only if DB connection fails
    $pdo = null;
}

// ✅ User ID (default 0 for guest)
$user_id = $_SESSION['user_id'] ?? 0;

// ✅ Initialize bookmarks if not already in session
if (!isset($_SESSION['bookmarks'])) {
    $_SESSION['bookmarks'] = [
        ["title" => "PHP Basics", "link" => "https://www.w3schools.com/php/"],
        ["title" => "JavaScript Guide", "link" => "https://www.w3schools.com/js/"],
        ["title" => "AI Overview", "link" => "https://www.w3schools.com/ai/default.asp"]
    ];
}

// ✅ Initialize recent activity in session (optional)
if (!isset($_SESSION['recent_activity'])) {
    $_SESSION['recent_activity'] = [
        "Viewed Python Tutorial",
        "Searched HTML Basics",
        "Visited CSS Section"
    ];
}

// ✅ Handle bookmark submission
if (isset($_POST['add_bookmark'])) {
    $newTitle = htmlspecialchars($_POST['bookmark_title']);
    $keyword = strtolower(trim($newTitle));
    $baseUrl = "https://www.w3schools.com/";
    $topicsMap = [
        "php" => "php/",
        "javascript" => "js/",
        "python" => "python/",
        "html" => "html/",
        "css" => "css/",
        "sql" => "sql/",
        "ai" => "ai/default.asp",
        "machine learning" => "ai/ai_machine_learning.asp",
        "c" => "c/index.php",
        "c++" => "cpp/default.asp",
        "c#" => "cs/index.php",
        "java" => "java/default.asp",
        "kotlin" => "kotlin/index.php",
        "r" => "r/index.php",
        "go" => "go/index.php",
        "django" => "django/index.php",
        "typescript" => "typescript/index.php",
        "react" => "react/default.asp",
        "angular" => "angular/default.asp",
        "node.js" => "nodejs/default.asp",
        "json" => "js/js_json.asp",
        "xml" => "xml/default.asp",
        "bootstrap" => "bootstrap/bootstrap_ver.asp",
        "jquery" => "jquery/default.asp",
        "numpy" => "python/numpy/default.asp",
        "pandas" => "python/pandas/default.asp",
        "matplotlib" => "python/matplotlib_intro.asp",
        "mysql" => "mysql/index.php",
        "mongodb" => "mongodb/index.php",
        "asp.net" => "asp/index.php",
        "programming" => "whatis/",
        "web development" => "whatis/"
    ];

    $matched = false;
    foreach ($topicsMap as $key => $path) {
        if (stripos($keyword, $key) !== false) {
            $newLink = $baseUrl . $path;
            $matched = true;
            break;
        }
    }
    if (!$matched) {
        $newLink = $baseUrl;
    }

    if ($newTitle && $newLink) {
        $_SESSION['bookmarks'][] = ["title" => $newTitle, "link" => $newLink];
        $activity = "Bookmarked: $newTitle";
        array_unshift($_SESSION['recent_activity'], $activity);

        if ($pdo) {
            $stmt = $pdo->prepare("INSERT INTO recent_activity (user_id, activity) VALUES (?, ?)");
            $stmt->execute([$user_id, $activity]);
        }
    }
}

// ✅ Handle bookmark removal
if (isset($_POST['remove_bookmark'])) {
    $removeIndex = intval($_POST['bookmark_index']);
    if (isset($_SESSION['bookmarks'][$removeIndex])) {
        $removed = $_SESSION['bookmarks'][$removeIndex]['title'];
        array_splice($_SESSION['bookmarks'], $removeIndex, 1);
        $activity = "Removed bookmark: $removed";
        array_unshift($_SESSION['recent_activity'], $activity);

        if ($pdo) {
            $stmt = $pdo->prepare("INSERT INTO recent_activity (user_id, activity) VALUES (?, ?)");
            $stmt->execute([$user_id, $activity]);
        }
    }
}

// ✅ Limit session activity to 10
$_SESSION['recent_activity'] = array_slice($_SESSION['recent_activity'], 0, 10);

// ✅ Get activity from database if available
if ($pdo) {
    $stmt = $pdo->prepare("SELECT activity FROM recent_activity WHERE user_id = ? ORDER BY timestamp DESC LIMIT 10");
    $stmt->execute([$user_id]);
    $recentActivity = array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'activity');
} else {
    $recentActivity = $_SESSION['recent_activity'];
}

$user = $_SESSION['username'] ?? 'Guest';
$bookmarks = $_SESSION['bookmarks'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard - DocRepo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            margin: 0;
            background-color: #2f2f2f;
            font-family: 'Segoe UI', Tahoma, sans-serif;
            color: white;
        }
        header {
            background-color: white;
            color: #2f2f2f;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        header h1 {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        }
        header a {
            position: absolute;
            right: 20px;
            font-size: 16px;
            color: #2f2f2f;
            text-decoration: none;
        }
        .dashboard {
            padding: 30px;
        }
        .section {
            background-color: #1e1e1e;
            margin-bottom: 30px;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.3);
        }
        .section h2 {
            color: #4CAF50;
            margin-bottom: 15px;
        }
        .section ul {
            list-style: none;
            padding-left: 0;
        }
        .section li {
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .section a {
            color: #4CAF50;
            text-decoration: none;
        }
        .section a:hover {
            text-decoration: underline;
        }
        .section form {
            margin-top: 20px;
        }
        .section input[type="text"] {
            padding: 8px;
            margin-right: 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
            font-size: 14px;
        }
        .section button {
            padding: 8px 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .section button:hover {
            background-color: #45a049;
        }
        .remove-button {
            background-color: #e74c3c;
            margin-left: 15px;
        }
        .remove-button:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>

<header>
    <h1>Your Coding Dashboard</h1>
    <a href="project.html">⬅ Back</a>
</header>

<div class="dashboard">
    <h2 class="text-2xl font-bold">Welcome, <?php echo htmlspecialchars($user); ?>!</h2>

    <div class="section">
        <h2>🔖 Bookmarked Topics</h2>
        <ul>
            <?php foreach ($bookmarks as $index => $bm): ?>
                <li>
                    <a href="<?= $bm['link'] ?>" target="_blank"><?= htmlspecialchars($bm['title']) ?></a>
                    <form method="post" style="display:inline">
                        <input type="hidden" name="bookmark_index" value="<?= $index ?>">
                        <button type="submit" name="remove_bookmark" class="remove-button">Remove</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
        <form method="post">
            <input type="text" name="bookmark_title" placeholder="Topic Title" required>
            <button type="submit" name="add_bookmark">Add Bookmark</button>
        </form>
    </div>

    <div class="section">
        <h2>🕘 Recent Activity</h2>
        <ul>
            <?php foreach ($recentActivity as $activity): ?>
                <li><?= htmlspecialchars($activity) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="section">
        <h2>📚 Personalized Suggestions</h2>
        <p>Keep exploring new topics and grow your coding skills!</p>
        <a href="https://www.w3schools.com/" target="_blank">Go to W3Schools Homepage</a>
    </div>
</div>

</body>
</html>
