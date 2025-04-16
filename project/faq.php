<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Initialize variables
$formSubmitted = false;
$formError = '';
$formSuccess = '';
$newQuestion = '';
$newEmail = '';
$selectedCategory = '';

$userQuestionsFile = 'user_questions.json';
$adminAnswersFile = 'admin_answers.json';

// Load user questions
$userQuestions = [];
if (file_exists($userQuestionsFile)) {
    $userQuestions = json_decode(file_get_contents($userQuestionsFile), true) ?: [];
}

// Load admin answers (which will be added to FAQs)
$adminAnswers = [];
if (file_exists($adminAnswersFile)) {
    $adminAnswers = json_decode(file_get_contents($adminAnswersFile), true) ?: [];
}

$faqCategories = [
    'JavaScript' => [
        [
            'question' => 'What is JavaScript?',
            'answer' => 'JavaScript is a scripting language used to create and control dynamic website content.'
        ],
        [
            'question' => 'How do I declare variables in JavaScript?',
            'answer' => 'You can declare variables using var, let, or const keywords.'
        ],
        [
            'question' => 'What is the difference between == and === in JavaScript?',
            'answer' => '== compares values with type coercion, while === compares both value and type without coercion.'
        ],
        [
            'question' => 'What are JavaScript promises?',
            'answer' => 'Promises are objects representing the eventual completion or failure of an asynchronous operation.'
        ],
        [
            'question' => 'How does JavaScript handle asynchronous code?',
            'answer' => 'JavaScript handles async code using callbacks, promises, and async/await syntax.'
        ]
    ],
    'PHP' => [
        [
            'question' => 'What is PHP used for?',
            'answer' => 'PHP is a server-side scripting language designed for web development.'
        ],
        [
            'question' => 'How do you connect to a MySQL database in PHP?',
            'answer' => 'You can use mysqli or PDO extensions to connect to MySQL in PHP.'
        ],
        [
            'question' => 'What are PHP sessions?',
            'answer' => 'Sessions are a way to preserve data across subsequent HTTP requests.'
        ],
        [
            'question' => 'How do you prevent SQL injection in PHP?',
            'answer' => 'Use prepared statements with parameterized queries via PDO or mysqli.'
        ],
        [
            'question' => 'What is Composer in PHP?',
            'answer' => 'Composer is a dependency management tool for PHP.'
        ]
    ],
    'Python' => [
        [
            'question' => 'What is Python good for?',
            'answer' => 'Python is versatile and used for web development, data analysis, AI, and more.'
        ],
        [
            'question' => 'How do you create a virtual environment in Python?',
            'answer' => 'Use "python -m venv envname" to create a virtual environment.'
        ],
        [
            'question' => 'What are Python decorators?',
            'answer' => 'Decorators are functions that modify the behavior of other functions.'
        ],
        [
            'question' => 'How do you handle exceptions in Python?',
            'answer' => 'Use try-except blocks to handle exceptions in Python.'
        ],
        [
            'question' => 'What is the difference between lists and tuples?',
            'answer' => 'Lists are mutable while tuples are immutable.'
        ]
    ],
    'HTML/CSS' => [
        [
            'question' => 'What is the difference between HTML and CSS?',
            'answer' => 'HTML structures content while CSS styles it.'
        ],
        [
            'question' => 'What are semantic HTML elements?',
            'answer' => 'Elements like <header>, <footer>, <article> that clearly describe their meaning.'
        ],
        [
            'question' => 'How does CSS Flexbox work?',
            'answer' => 'Flexbox is a layout model that allows responsive elements within a container.'
        ],
        [
            'question' => 'What is CSS Grid?',
            'answer' => 'A 2D layout system for the web that lets you create complex responsive designs.'
        ],
        [
            'question' => 'How do you center a div in CSS?',
            'answer' => 'Use margin: auto with fixed width, or flexbox/grid centering techniques.'
        ]
    ],
    'SQL' => [
        [
            'question' => 'What is SQL?',
            'answer' => 'Structured Query Language used to manage relational databases.'
        ],
        [
            'question' => 'What is the difference between WHERE and HAVING?',
            'answer' => 'WHERE filters rows before grouping, HAVING filters after grouping.'
        ],
        [
            'question' => 'What are SQL joins?',
            'answer' => 'Joins combine rows from two or more tables based on related columns.'
        ],
        [
            'question' => 'What is normalization in databases?',
            'answer' => 'The process of organizing data to minimize redundancy.'
        ],
        [
            'question' => 'What is an SQL index?',
            'answer' => 'A database structure that improves the speed of data retrieval.'
        ]
    ]
];

// Add admin answers to FAQ categories
foreach ($adminAnswers as $answer) {
    if (isset($faqCategories[$answer['category']])) {
        $faqCategories[$answer['category']][] = [
            'question' => $answer['question'],
            'answer' => $answer['answer']
        ];
    }
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (empty($_POST['question'])) {
            throw new Exception('Question is required');
        }
        
        if (empty($_POST['email'])) {
            throw new Exception('Email is required');
        }

        $newQuestion = htmlspecialchars(trim($_POST['question']));
        $newEmail = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $selectedCategory = htmlspecialchars($_POST['category'] ?? '');

        if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Please enter a valid email address');
        }

        if (!isset($faqCategories[$selectedCategory])) {
            $selectedCategory = 'General';
        }
        
        $userQuestions[] = [
            'question' => $newQuestion,
            'category' => $selectedCategory,
            'email' => $newEmail,
            'date' => date('Y-m-d H:i:s'),
            'status' => 'pending',
            'answer' => ''
        ];
        
        // Save to JSON file
        file_put_contents($userQuestionsFile, json_encode($userQuestions, JSON_PRETTY_PRINT));
        
        $formSubmitted = true;
        $formSuccess = 'Thank you for your question! Our team will review it and get back to you.';
        
        // Reset form fields
        $newQuestion = '';
        $newEmail = '';
    } catch (Exception $e) {
        $formError = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQs</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">
   
    <nav class="bg-blue-600 text-white p-4 shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold">Knowledge Base</h1>
            <div class="space-x-4">
<a href="admin.php" class="admin-btn relative inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium rounded-lg group bg-gradient-to-br from-yellow-400 to-yellow-600 hover:from-yellow-500 hover:to-yellow-700 text-black shadow-lg transform transition-all duration-300 hover:scale-105">
    <span class="relative flex items-center">
        <!-- <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd" /> -->
        <!-- </svg> -->
        Admin Panel
    </span>
    <!-- <span class="absolute -bottom-1 -right-1 w-3 h-3 bg-green-500 rounded-full border-2 border-white animate-pulse"></span> -->
</a>


<a href="project.html" class="home-btn relative inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium rounded-lg group bg-gradient-to-br from-white to-gray-100 hover:from-gray-100 hover:to-gray-200 text-blue-600 shadow-lg transform transition-all duration-300 hover:scale-105 border border-blue-200">
    <span class="relative flex items-center">
        <!-- <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" /> -->
        <!-- </svg> -->
        Home
    </span>
</a>
                </a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto py-8 px-4 max-w-6xl">
        <h1 class="text-3xl font-bold text-center mb-8 text-blue-600">FAQs</h1>
        
        <?php if ($formError): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php echo htmlspecialchars($formError); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($formSuccess): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?php echo htmlspecialchars($formSuccess); ?>
            </div>
        <?php endif; ?>

        <!-- Category Tabs -->
        <div class="flex flex-wrap gap-2 mb-6">
            <?php foreach (array_keys($faqCategories) as $index => $category): ?>
                <button onclick="showCategory('<?php echo htmlspecialchars($category); ?>')" 
                        class="category-tab px-4 py-2 rounded-lg border border-blue-500 text-blue-600 hover:bg-blue-50 <?php echo $index === 0 ? 'bg-blue-100' : ''; ?>">
                    <?php echo htmlspecialchars($category); ?>
                </button>
            <?php endforeach; ?>
        </div>

        <!-- FAQ Categories -->
        <?php foreach ($faqCategories as $category => $faqs): ?>
            <div id="category-<?php echo htmlspecialchars($category); ?>" class="faq-category mb-8 <?php echo $category !== array_key_first($faqCategories) ? 'hidden' : ''; ?>">
                <h2 class="text-2xl font-bold mb-4 text-gray-800"><?php echo htmlspecialchars($category); ?> Questions</h2>
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <?php foreach ($faqs as $index => $faq): ?>
                        <div class="faq-item border-b border-gray-200 last:border-b-0">
                            <div class="faq-question flex justify-between items-center p-4 cursor-pointer hover:bg-blue-50 transition-colors duration-200" 
                                 onclick="toggleAnswer('<?php echo htmlspecialchars($category); ?>', <?php echo $index; ?>)">
                                <h3 class="font-bold text-gray-800"><?php echo htmlspecialchars($faq['question']); ?></h3>
                                <span id="icon-<?php echo htmlspecialchars($category); ?>-<?php echo $index; ?>" class="text-xl font-light transition-all">+</span>
                            </div>
                            <div id="answer-<?php echo htmlspecialchars($category); ?>-<?php echo $index; ?>" class="faq-answer hidden px-4 pb-4 pt-2 bg-blue-50">
                                <p class="text-gray-700"><?php echo htmlspecialchars($faq['answer']); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <!-- Ask a Question Section -->
        <div class="bg-white rounded-lg shadow-md p-6 mt-8">
            <h2 class="text-2xl font-bold mb-4 text-blue-600">Ask a Question</h2>
            <form method="POST" class="space-y-4">
                <div>
                    <label for="category" class="block text-gray-700 font-medium mb-2">Language/Category*</label>
                    <select id="category" name="category" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <?php foreach (array_keys($faqCategories) as $category): ?>
                            <option value="<?php echo htmlspecialchars($category); ?>"><?php echo htmlspecialchars($category); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="question" class="block text-gray-700 font-medium mb-2">Your Question*</label>
                    <textarea id="question" name="question" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                              required><?php echo htmlspecialchars($newQuestion); ?></textarea>
                </div>
                <div>
                    <label for="email" class="block text-gray-700 font-medium mb-2">Your Email*</label>
                    <input type="email" id="email" name="email" 
                           value="<?php echo htmlspecialchars($newEmail); ?>"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                           required>
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors">
                    Submit Question
                </button>
            </form>
        </div>
    </div>

    <script>
        // Show selected category
        function showCategory(category) {
            document.querySelectorAll('.faq-category').forEach(el => {
                el.classList.add('hidden');
            });
            document.getElementById('category-' + category).classList.remove('hidden');
            
            // Update active tab styling
            document.querySelectorAll('.category-tab').forEach(tab => {
                tab.classList.remove('bg-blue-100');
            });
            event.target.classList.add('bg-blue-100');
        }

     
        function toggleAnswer(category, index) {
            const answer = document.getElementById('answer-' + category + '-' + index);
            const icon = document.getElementById('icon-' + category + '-' + index);
            
            answer.classList.toggle('hidden');
            
            if (answer.classList.contains('hidden')) {
                icon.textContent = '+';
            } else {
                icon.textContent = '−';
            }
        }
    </script>
</body>
</html>