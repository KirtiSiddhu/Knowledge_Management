<?php

$trainings = [
    [
        'title' => 'Introduction to Python Programming',
        'video' => 'https://www.youtube.com/embed/rfscVS0vtbw',
        'category' => 'technology',
        'description' => 'Learn the basics of Python programming language in this comprehensive tutorial.',
        'duration' => '4:26:52',
        'level' => 'beginner'
    ],
    [
        'title' => 'Effective Research Strategies',
        'video' => 'https://www.youtube.com/embed/x0NO4f5UCUY',
        'category' => 'research',
        'description' => 'Discover proven techniques for conducting efficient academic research.',
        'duration' => '12:34',
        'level' => 'intermediate'
    ],
    [
        'title' => 'Microsoft Excel Advanced Functions',
        'video' => 'https://www.youtube.com/embed/Mkkb5Bk6Z-Y',
        'category' => 'software',
        'description' => 'Master advanced Excel functions to boost your productivity.',
        'duration' => '18:45',
        'level' => 'advanced'
    ],
    [
        'title' => 'Academic Writing Essentials',
        'video' => 'https://www.youtube.com/embed/gFXE9n7hrOI',
        'category' => 'academic',
        'description' => 'Learn the key principles of effective academic writing.',
        'duration' => '15:20',
        'level' => 'beginner'
    ],
    [
        'title' => 'Database Design Fundamentals',
        'video' => 'https://www.youtube.com/embed/ztHopE5Wnpc',
        'category' => 'technology',
        'description' => 'Understand the core concepts of relational database design.',
        'duration' => '1:02:15',
        'level' => 'intermediate'
    ],
    [
        'title' => 'Literature Review Techniques',
        'video' => 'https://www.youtube.com/embed/zIYC6zG265E',
        'category' => 'research',
        'description' => 'Learn how to conduct and write a comprehensive literature review.',
        'duration' => '22:10',
        'level' => 'intermediate'
    ],
    [
        'title' => 'Photoshop for Beginners',
        'video' => 'https://www.youtube.com/embed/IyR_uYsRdPs',
        'category' => 'software',
        'description' => 'Get started with Adobe Photoshop with this beginner-friendly tutorial.',
        'duration' => '2:08:15',
        'level' => 'beginner'
    ],
    [
        'title' => 'Citing Sources in APA Format',
        'video' => 'https://www.youtube.com/embed/GST_eswqzaQ',
        'category' => 'academic',
        'description' => 'Learn how to properly cite sources using APA citation style.',
        'duration' => '9:45',
        'level' => 'beginner'
    ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Knowledge Portal - Training & Tutorials</title>
    <script src="https://cdn.tailwindcss.com"></script>
   
</head>
<body class="bg-gray-100 min-h-screen">
    <header class="bg-blue-700 text-white p-4 shadow-md sticky top-0 z-10">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold">Training & Tutorials</h1>
            <a href="project.html" class="hover:underline flex items-center">
                <i class="fas fa-home mr-2"></i> Back to Home
            </a>
        </div>
    </header>

    <main class="container mx-auto p-6">
        <div class="mb-8 bg-white rounded-lg shadow p-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-1">Educational Resources</h2>
                    <p class="text-gray-600">Browse our collection of training materials and tutorials</p>
                </div>
                <div class="flex flex-wrap gap-4">
                    <div>
                        <label for="categoryFilter" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select id="categoryFilter" class="p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                            <option value="all">All Categories</option>
                            <option value="technology">Technology</option>
                            <option value="research">Research Skills</option>
                            <option value="software">Software Tutorials</option>
                            <option value="academic">Academic Writing</option>
                        </select>
                    </div>
                    <div>
                        <label for="levelFilter" class="block text-sm font-medium text-gray-700 mb-1">Level</label>
                        <select id="levelFilter" class="p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                            <option value="all">All Levels</option>
                            <option value="beginner">Beginner</option>
                            <option value="intermediate">Intermediate</option>
                            <option value="advanced">Advanced</option>
                        </select>
                    </div> 
                </div>
            </div>
        </div>

        <div id="trainingContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach ($trainings as $training): ?>
                <div class="training-item bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300" 
                     data-category="<?= $training['category'] ?>" 
                     data-level="<?= $training['level'] ?>">
                    <div class="video-container">
                        <iframe src="<?= $training['video'] ?>?rel=0" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                    <div class="p-4">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-bold text-lg text-gray-800"><?= htmlspecialchars($training['title']) ?></h3>
                            <span class="px-2 py-1 text-xs rounded-full <?= 
                                $training['category'] === 'technology' ? 'bg-blue-100 text-blue-800' : 
                                ($training['category'] === 'research' ? 'bg-green-100 text-green-800' : 
                                ($training['category'] === 'software' ? 'bg-purple-100 text-purple-800' : 
                                'bg-yellow-100 text-yellow-800')) ?>">
                                <?= ucfirst($training['category']) ?>
                            </span>
                        </div>
                        <p class="text-gray-600 mb-4 text-sm"><?= htmlspecialchars($training['description']) ?></p>
                        <div class="flex justify-between items-center">
                            <span class="text-xs px-2 py-1 rounded-full <?= 
                                $training['level'] === 'beginner' ? 'bg-blue-100 text-blue-800' : 
                                ($training['level'] === 'intermediate' ? 'bg-yellow-100 text-yellow-800' : 
                                'bg-red-100 text-red-800') ?>">
                                <?= ucfirst($training['level']) ?>
                            </span>
                            <span class="text-xs text-gray-500">
                                <i class="far fa-clock mr-1"></i><?= $training['duration'] ?>
                            </span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Video Modal -->
        <div id="videoModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 hidden">
            <div class="bg-white rounded-lg overflow-hidden w-full max-w-4xl mx-4">
                <div class="flex justify-between items-center bg-gray-100 px-4 py-2">
                    <h3 id="modalTitle" class="font-bold text-lg"></h3>
                    <button onclick="closeVideoModal()" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="video-container">
                    <iframe id="videoFrame" class="w-full" frameborder="0" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-gray-800 text-white p-6 mt-12">
        <div class="container mx-auto text-center">
            <p>&copy; 2023 Knowledge Portal. All rights reserved.</p>
        </div>
    </footer>

    <script>
        // Filter Functionality
        function applyFilters() {
            const category = document.getElementById('categoryFilter').value;
            const level = document.getElementById('levelFilter').value;
            
            document.querySelectorAll('.training-item').forEach(item => {
                const itemCategory = item.dataset.category;
                const itemLevel = item.dataset.level;
                
                const categoryMatch = category === 'all' || itemCategory === category;
                const levelMatch = level === 'all' || itemLevel === level;
                
                if (categoryMatch && levelMatch) {
                    item.classList.remove('hidden');
                } else {
                    item.classList.add('hidden');
                }
            });
        }

        document.getElementById('categoryFilter').addEventListener('change', applyFilters);
        document.getElementById('levelFilter').addEventListener('change', applyFilters);

        // Animation for cards when they come into view
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = 1;
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, { threshold: 0.1 });
    </script>
</body>
</html>