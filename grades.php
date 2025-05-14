<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

// Get user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

// Set page title
$pageTitle = 'My Grades';

// Sample grade data (replace with actual database query)
$courses = [
    [
        'course_code' => 'CS101',
        'course_title' => 'Introduction to Computer Science',
        'instructor' => 'Dr. Smith',
        'term' => '1st Semester 2023',
        'units' => 3,
        'theory_components' => [
            ['name' => 'Examination', 'percentage' => 30, 'score' => 85, 'total' => 100],
            ['name' => 'Recitation', 'percentage' => 20, 'score' => 92, 'total' => 100],
            ['name' => 'Term Paper', 'percentage' => 15, 'score' => 88, 'total' => 100],
            ['name' => 'Short Quizzes', 'percentage' => 25, 'score' => 90, 'total' => 100],
            ['name' => 'Attendance', 'percentage' => 10, 'score' => 95, 'total' => 100]
        ],
        'lab_components' => [
            ['name' => 'Project', 'percentage' => 30, 'score' => 92, 'total' => 100],
            ['name' => 'Performance', 'percentage' => 40, 'score' => 85, 'total' => 100],
            ['name' => 'Attitude Towards Work', 'percentage' => 15, 'score' => 90, 'total' => 100],
            ['name' => 'Attendance', 'percentage' => 15, 'score' => 95, 'total' => 100]
        ],
        'theory_average' => 88.5,
        'lab_average' => 89.3,
        'general_average' => 89.0,
        'final_grade' => '1.25',
        'equivalent' => 'A',
        'remarks' => 'Passed'
    ],
    [
        'course_code' => 'MATH202',
        'course_title' => 'Calculus II',
        'instructor' => 'Prof. Johnson',
        'term' => '1st Semester 2023',
        'units' => 4,
        'theory_components' => [
            ['name' => 'Examination', 'percentage' => 35, 'score' => 78, 'total' => 100],
            ['name' => 'Recitation', 'percentage' => 20, 'score' => 85, 'total' => 100],
            ['name' => 'Term Paper', 'percentage' => 15, 'score' => 82, 'total' => 100],
            ['name' => 'Short Quizzes', 'percentage' => 20, 'score' => 80, 'total' => 100],
            ['name' => 'Attendance', 'percentage' => 10, 'score' => 90, 'total' => 100]
        ],
        'lab_components' => [
            ['name' => 'Project', 'percentage' => 30, 'score' => 85, 'total' => 100],
            ['name' => 'Performance', 'percentage' => 40, 'score' => 80, 'total' => 100],
            ['name' => 'Attitude Towards Work', 'percentage' => 15, 'score' => 85, 'total' => 100],
            ['name' => 'Attendance', 'percentage' => 15, 'score' => 90, 'total' => 100]
        ],
        'theory_average' => 81.3,
        'lab_average' => 82.8,
        'general_average' => 82.3,
        'final_grade' => '1.75',
        'equivalent' => 'B',
        'remarks' => 'Passed'
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <?php include 'components/styles.php'; ?>
    <style>
        .course-card {
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .course-header {
            background-color: #f8f9fa;
            padding: 15px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #eee;
        }
        .course-header h3 {
            margin: 0;
            font-size: 18px;
        }
        .course-content {
            padding: 15px;
            background-color: white;
        }
        .grade-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        .grade-summary-item {
            padding: 15px;
            border-radius: 6px;
            background-color: #f8f9fa;
            text-align: center;
        }
        .grade-summary-item h4 {
            margin-top: 0;
            margin-bottom: 10px;
            font-size: 16px;
            color: #555;
        }
        .grade-summary-value {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .grade-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 14px;
        }
        .grade-A { background-color: #d4edda; color: #155724; }
        .grade-B { background-color: #cce5ff; color: #004085; }
        .grade-C { background-color: #fff3cd; color: #856404; }
        .grade-D { background-color: #f8d7da; color: #721c24; }
        .grade-F { background-color: #f8d7da; color: #721c24; }
        
        .component-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .component-table th, .component-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        .component-table th {
            background-color: #f1f1f1;
            font-weight: 600;
        }
        .component-table tr:hover {
            background-color: #f9f9f9;
        }
        .progress-container {
            width: 100%;
            background-color: #e9ecef;
            border-radius: 5px;
            height: 10px;
            margin-top: 5px;
        }
        .progress-bar {
            height: 100%;
            border-radius: 5px;
            background-color: var(--primary-color);
        }
        .toggle-icon {
            transition: transform 0.3s ease;
        }
        .collapsed .toggle-icon {
            transform: rotate(-90deg);
        }
        
        @media (max-width: 768px) {
            .grade-summary {
                grid-template-columns: 1fr;
            }
            .component-table {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <?php include 'components/sidebar.php'; ?>
        
        <!-- Main Content -->
        <div class="main-content" id="mainContent">
            <?php include 'components/header.php'; ?>

            <!-- Content Wrapper -->
            <div class="content-wrapper">
                <div class="page-header">
                    <div class="page-title">
                        <h1>My Grades</h1>
                        <p>View your academic performance</p>
                    </div>
                    <div class="page-actions">
                        <button class="btn btn-primary" onclick="window.print()">
                            <i class="fas fa-print"></i> Print Grades
                        </button>
                    </div>
                </div>

                <?php foreach ($courses as $course): ?>
                <div class="course-card">
                    <div class="course-header" onclick="toggleCourse(this)">
                        <h3>
                            <?php echo htmlspecialchars($course['course_code']); ?> - 
                            <?php echo htmlspecialchars($course['course_title']); ?>
                            <small style="font-size: 14px; color: #666;">
                                (<?php echo htmlspecialchars($course['term']); ?>)
                            </small>
                        </h3>
                        <span class="toggle-icon"><i class="fas fa-chevron-down"></i></span>
                    </div>
                    
                    <div class="course-content">
                        <!-- Basic Info -->
                        <div style="margin-bottom: 15px;">
                            <p style="margin: 5px 0;">
                                <strong>Instructor:</strong> <?php echo htmlspecialchars($course['instructor']); ?>
                            </p>
                            <p style="margin: 5px 0;">
                                <strong>Units:</strong> <?php echo htmlspecialchars($course['units']); ?>
                            </p>
                        </div>
                        
                        <!-- Grade Summary -->
                        <div class="grade-summary">
                            <div class="grade-summary-item">
                                <h4>Theory Average (35%)</h4>
                                <div class="grade-summary-value"><?php echo number_format($course['theory_average'], 1); ?>%</div>
                                <div class="progress-container">
                                    <div class="progress-bar" style="width: <?php echo $course['theory_average']; ?>%"></div>
                                </div>
                            </div>
                            
                            <div class="grade-summary-item">
                                <h4>Lab Average (65%)</h4>
                                <div class="grade-summary-value"><?php echo number_format($course['lab_average'], 1); ?>%</div>
                                <div class="progress-container">
                                    <div class="progress-bar" style="width: <?php echo $course['lab_average']; ?>%"></div>
                                </div>
                            </div>
                            
                            <div class="grade-summary-item">
                                <h4>General Average</h4>
                                <div class="grade-summary-value"><?php echo number_format($course['general_average'], 1); ?>%</div>
                                <div class="progress-container">
                                    <div class="progress-bar" style="width: <?php echo $course['general_average']; ?>%"></div>
                                </div>
                            </div>
                            
                            <div class="grade-summary-item">
                                <h4>Final Grade</h4>
                                <div class="grade-summary-value">
                                    <span class="grade-badge grade-<?php echo substr($course['equivalent'], 0, 1); ?>">
                                        <?php echo htmlspecialchars($course['final_grade']); ?> (<?php echo htmlspecialchars($course['equivalent']); ?>)
                                    </span>
                                </div>
                                <div><?php echo htmlspecialchars($course['remarks']); ?></div>
                            </div>
                        </div>
                        
                        <!-- Theory Components -->
                        <div style="margin-top: 20px;">
                            <h4 style="margin-bottom: 10px; color: #2c3e50;">Theory Components (35%)</h4>
                            <table class="component-table">
                                <thead>
                                    <tr>
                                        <th>Component</th>
                                        <th>Score</th>
                                        <th>Percentage</th>
                                        <th>Weighted</th>
                                        <th>Progress</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($course['theory_components'] as $component): 
                                        $weighted = ($component['score']/$component['total']) * $component['percentage'];
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($component['name']); ?></td>
                                        <td><?php echo htmlspecialchars($component['score']); ?>/<?php echo htmlspecialchars($component['total']); ?></td>
                                        <td><?php echo htmlspecialchars($component['percentage']); ?>%</td>
                                        <td><?php echo number_format($weighted, 1); ?>%</td>
                                        <td>
                                            <div class="progress-container">
                                                <div class="progress-bar" style="width: <?php echo ($component['score']/$component['total'])*100; ?>%"></div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Lab Components -->
                        <div style="margin-top: 30px;">
                            <h4 style="margin-bottom: 10px; color: #2c3e50;">Laboratory Components (65%)</h4>
                            <table class="component-table">
                                <thead>
                                    <tr>
                                        <th>Component</th>
                                        <th>Score</th>
                                        <th>Percentage</th>
                                        <th>Weighted</th>
                                        <th>Progress</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($course['lab_components'] as $component): 
                                        $weighted = ($component['score']/$component['total']) * $component['percentage'];
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($component['name']); ?></td>
                                        <td><?php echo htmlspecialchars($component['score']); ?>/<?php echo htmlspecialchars($component['total']); ?></td>
                                        <td><?php echo htmlspecialchars($component['percentage']); ?>%</td>
                                        <td><?php echo number_format($weighted, 1); ?>%</td>
                                        <td>
                                            <div class="progress-container">
                                                <div class="progress-bar" style="width: <?php echo ($component['score']/$component['total'])*100; ?>%"></div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <?php include 'components/script.php'; ?>
    <script>
        function toggleCourse(header) {
            const card = header.parentElement;
            const content = header.nextElementSibling;
            
            if (content.style.display === 'none') {
                content.style.display = 'block';
                card.classList.remove('collapsed');
            } else {
                content.style.display = 'none';
                card.classList.add('collapsed');
            }
        }
        
        // Initialize all courses as expanded
        document.addEventListener('DOMContentLoaded', function() {
            const courseContents = document.querySelectorAll('.course-content');
            courseContents.forEach(content => {
                content.style.display = 'block';
            });
        });
    </script>
</body>
</html>