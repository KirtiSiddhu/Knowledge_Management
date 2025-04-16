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
    $pdo = null;
}

// Get user_id from session
$user_id = $_SESSION['user_id'] ?? 0;

// ✅ Define w3Links array
$w3Links = [
    "HTML" => "https://www.w3schools.com/html/",
    "CSS" => "https://www.w3schools.com/css/",
    "JavaScript" => "https://www.w3schools.com/js/",
    "Python" => "https://www.w3schools.com/python/",
    "SQL" => "https://www.w3schools.com/sql/",
    "PHP" => "https://www.w3schools.com/php/",
    "Bootstrap" => "https://www.w3schools.com/bootstrap/",
    "Java" => "https://www.w3schools.com/java/",
    "C++" => "https://www.w3schools.com/cpp/",
    "C#" => "https://www.w3schools.com/cs/",
    "jQuery" => "https://www.w3schools.com/jquery/",
    "React" => "https://www.w3schools.com/react/",
    "XML" => "https://www.w3schools.com/xml/",
    "Django" => "https://www.w3schools.com/django/",
    "NumPy" => "https://www.w3schools.com/python/numpy/",
    "Pandas" => "https://www.w3schools.com/python/pandas/",
    "Node.js" => "https://www.w3schools.com/nodejs/",
    "Data Structures" => "https://www.w3schools.com/dsa/",
    "TypeScript" => "https://www.w3schools.com/typescript/",
    "Angular" => "https://www.w3schools.com/angular/",
    "Git" => "https://www.w3schools.com/git/",
    "PostgreSQL" => "https://www.w3schools.com/postgresql/",
    "MongoDB" => "https://www.w3schools.com/mongodb/",
    "ASP.NET" => "https://www.w3schools.com/asp/",
    "AI" => "https://www.w3schools.com/ai/default.asp",
    "R" => "https://www.w3schools.com/r/",
    "Go" => "https://www.w3schools.com/go/",
    "Kotlin" => "https://www.w3schools.com/kotlin/",
    "SASS" => "https://www.w3schools.com/sass/",
    "Vue.js" => "https://www.w3schools.com/vue/",
    "Cyber Security" => "https://www.w3schools.com/cybersecurity/",
    "Data Science" => "https://www.w3schools.com/datascience/",
    "Scipy" => "https://www.w3schools.com/scipy/",
    "Gen AI" => "https://www.w3schools.com/gen_ai/",
    "W3.CSS" => "https://www.w3schools.com/w3css/"
];

$keywords = array_keys($w3Links);

// ✅ Handle search query
$search = $_GET['search'] ?? '';

// Function to handle keyword matching
function getW3Link($keyword) {
    global $w3Links;
    foreach ($w3Links as $key => $url) {
        if (stripos($keyword, $key) !== false) {
            return $url;
        }
    }
    return "https://www.w3schools.com/";
}

// ✅ Insert visit into the database
if ($search) {
    if ($pdo) {
        $stmt = $pdo->prepare("INSERT INTO visited_topics (user_id, topic) VALUES (?, ?)");
        $stmt->execute([$user_id, $search]);
    }
}

// ✅ Get visited topics from the database
$visitedTopics = [];
if ($pdo) {
    $stmt = $pdo->prepare("SELECT topic FROM visited_topics WHERE user_id = ? ORDER BY timestamp DESC LIMIT 5");
    $stmt->execute([$user_id]);
    $visitedTopics = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DocRepo Search</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        /* Include your styles here */
        body {
            margin: 0;
            background-color: #2f2f2f;
            font-family: 'Segoe UI', Tahoma, sans-serif;
            color: white;
        }

        .topnav {
            background-color: #ffffff;
            padding: 12px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        .topnav .logo {
            font-weight: bold;
            font-size: 20px;
            color: #2f2f2f;
            text-decoration: none;
        }

        .keyword-nav {
            display: flex;
            overflow-x: auto;
            white-space: nowrap;
            background-color: #1e1e1e;
            padding: 10px;
            scrollbar-width: none;
        }

        .keyword-nav::-webkit-scrollbar {
            display: none;
        }

        .keyword-nav button {
            margin: 6px;
            padding: 10px 16px;
            background-color: #333;
            border: 1px solid #555;
            border-radius: 20px;
            color: white;
            cursor: pointer;
            font-size: 14px;
            transition: background 0.3s;
        }

        .keyword-nav button:hover {
            background-color: #4CAF50;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .search-form {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 20px;
        }

        .search-input {
            padding: 12px;
            width: 280px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 16px;
            margin-bottom: 15px;
        }

        .search-button {
            padding: 12px 24px;
            background-color: #ffffff;
            border: 2px solid #4CAF50;
            color: #4CAF50;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
        }

        .search-button:hover {
            background-color: #4CAF50;
            color: #ffffff;
        }

        .visited-topics {
            margin-top: 30px;
            background-color: #1e1e1e;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 6px 12px rgba(0,0,0,0.3);
            width: 80%;
        }

        .visited-topics h3 {
            color: #4CAF50;
            font-size: 20px;
            margin-bottom: 15px;
        }

        .visited-topics ul {
            list-style: none;
            padding-left: 0;
        }

        .visited-topics li {
            margin-bottom: 10px;
        }

        .visited-topics a {
            color: #4CAF50;
            text-decoration: none;
        }

        .visited-topics a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<header style="background-color: white; color: #2f2f2f; padding: 20px; display: flex; justify-content: center; align-items: center; position: relative; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
    <h1 style="font-size: 28px; font-weight: bold; margin: 0;">KNOWLEDGE REPOSITORY</h1>
    <a href="project.html" style="position: absolute; right: 20px; font-size: 16px; color: #2f2f2f; text-decoration: none;">
        ⬅ Back
    </a>
</header>

<div class="keyword-nav">
    <?php foreach ($keywords as $kw): ?>
        <button onclick="handleKeywordClick('<?= $kw ?>', '<?= $w3Links[$kw] ?>')"><?= $kw ?></button>
    <?php endforeach; ?>
</div>

<h2 style="text-align:center; color:#4CAF50; font-size: 28px; margin-top: 30px;">
    Learn to code. Explore. Build your future.
</h2>

<div class="container">
    <form method="get" class="search-form">
        <input type="text" name="search" class="search-input" placeholder="Search...">
        <button type="submit" class="search-button">
            <i class="fas fa-search"></i> Search
        </button>
    </form>
</div>

<!-- Visited Topics Section -->
<div class="visited-topics">
    <h3>📚 Recently Visited Topics</h3>
    <ul>
        <?php foreach ($visitedTopics as $topic): ?>
            <li><a href="<?= $w3Links[$topic['topic']] ?>" target="_blank"><?= htmlspecialchars($topic['topic']) ?></a></li>
        <?php endforeach; ?>
    </ul>
</div>

<script>
    function handleKeywordClick(keyword, link) {
        window.open(link, '_blank');
        window.location.href = '?search=' + encodeURIComponent(keyword);
    }
</script>

</body>
</html>
