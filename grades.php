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
        'units' => 3,
        'components' => [
            ['name' => 'Quizzes', 'percentage' => 20, 'score' => 85, 'total' => 100],
            ['name' => 'Projects', 'percentage' => 30, 'score' => 92, 'total' => 100],
            ['name' => 'Midterm Exam', 'percentage' => 20, 'score' => 88, 'total' => 100],
            ['name' => 'Final Exam', 'percentage' => 30, 'score' => 90, 'total' => 100]
        ],
        'average' => 89.1,
        'equivalent' => 'A-',
        'remarks' => 'Passed'
    ],
    [
        'course_code' => 'MATH202',
        'course_title' => 'Calculus II',
        'instructor' => 'Prof. Johnson',
        'units' => 4,
        'components' => [
            ['name' => 'Quizzes', 'percentage' => 15, 'score' => 78, 'total' => 100],
            ['name' => 'Homework', 'percentage' => 25, 'score' => 85, 'total' => 100],
            ['name' => 'Midterm Exam', 'percentage' => 30, 'score' => 82, 'total' => 100],
            ['name' => 'Final Exam', 'percentage' => 30, 'score' => 80, 'total' => 100]
        ],
        'average' => 81.7,
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
        .grade-progress {
            height: 10px;
            background-color: #e9ecef;
            border-radius: 5px;
            margin-top: 5px;
        }
        .grade-progress-bar {
            height: 100%;
            border-radius: 5px;
            background-color: var(--primary-color);
        }
        .grade-table {
            width: 100%;
            border-collapse: collapse;
        }
        .grade-table th, .grade-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }
        .grade-table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }
        .component-row td {
            padding-left: 30px !important;
        }
        .course-header {
            background-color: #f1f1f1 !important;
            cursor: pointer;
        }
        .grade-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-weight: 500;
            font-size: 12px;
        }
        .grade-A { background-color: #d4edda; color: #155724; }
        .grade-B { background-color: #cce5ff; color: #004085; }
        .grade-C { background-color: #fff3cd; color: #856404; }
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
                        <p>Detailed view of your academic performance</p>
                    </div>
                    <div class="page-actions">
                        <button class="btn btn-primary" onclick="window.print()">
                            <i class="fas fa-print"></i> Print Grades
                        </button>
                    </div>
                </div>

                <!-- Grades Summary Card -->
                <div class="card">
                    <div class="card-header">
                        <h3><i class="fas fa-chart-pie"></i> Overall Performance</h3>
                    </div>
                    <div class="card-content" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                        <div>
                            <h4>Current GPA</h4>
                            <div style="font-size: 32px; font-weight: bold; color: var(--primary-color);">3.42</div>
                            <p style="color: #6c757d; font-size: 14px;">Based on 12 completed units</p>
                        </div>
                        <div>
                            <h4>Highest Grade</h4>
                            <div style="font-size: 32px; font-weight: bold; color: #28a745;">A</div>
                            <p style="color: #6c757d; font-size: 14px;">In Introduction to CS</p>
                        </div>
                        <div>
                            <h4>Completed Units</h4>
                            <div style="font-size: 32px; font-weight: bold; color: var(--dark-color);">36</div>
                            <p style="color: #6c757d; font-size: 14px;">Out of 120 required</p>
                        </div>
                    </div>
                </div>

                <!-- Course Grades Table -->
                <div class="card">
                    <div class="card-header">
                        <h3><i class="fas fa-book-open"></i> Course Grades</h3>
                    </div>
                    <div class="card-content" style="overflow-x: auto;">
                        <table class="grade-table">
                            <thead>
                                <tr>
                                    <th>Course</th>
                                    <th>Instructor</th>
                                    <th>Units</th>
                                    <th>Grade Components</th>
                                    <th>Average</th>
                                    <th>Equivalent</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($courses as $course): ?>
                                <tr class="course-header">
                                    <td>
                                        <strong><?php echo htmlspecialchars($course['course_code']); ?></strong><br>
                                        <small><?php echo htmlspecialchars($course['course_title']); ?></small>
                                    </td>
                                    <td><?php echo htmlspecialchars($course['instructor']); ?></td>
                                    <td><?php echo htmlspecialchars($course['units']); ?></td>
                                    <td>
                                        <div class="grade-progress">
                                            <div class="grade-progress-bar" style="width: <?php echo $course['average']; ?>%"></div>
                                        </div>
                                        <small><?php echo count($course['components']); ?> components</small>
                                    </td>
                                    <td><strong><?php echo number_format($course['average'], 1); ?>%</strong></td>
                                    <td>
                                        <span class="grade-badge grade-<?php echo substr($course['equivalent'], 0, 1); ?>">
                                            <?php echo htmlspecialchars($course['equivalent']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($course['remarks']); ?></td>
                                </tr>
                                <?php foreach ($course['components'] as $component): ?>
                                <tr class="component-row">
                                    <td><?php echo htmlspecialchars($component['name']); ?></td>
                                    <td><?php echo htmlspecialchars($component['percentage']); ?>%</td>
                                    <td colspan="2">
                                        <?php echo htmlspecialchars($component['score']); ?> / <?php echo htmlspecialchars($component['total']); ?>
                                        <div class="grade-progress">
                                            <div class="grade-progress-bar" style="width: <?php echo ($component['score']/$component['total'])*100; ?>%"></div>
                                        </div>
                                    </td>
                                    <td><?php echo number_format(($component['score']/$component['total'])*100, 1); ?>%</td>
                                    <td colspan="2"></td>
                                </tr>
                                <?php endforeach; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Grade Legend -->
                <div class="card">
                    <div class="card-header">
                        <h3><i class="fas fa-info-circle"></i> Grading System</h3>
                    </div>
                    <div class="card-content">
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px;">
                            <div>
                                <span class="grade-badge grade-A">A (94-100%)</span>
                                <p style="margin-top: 5px; font-size: 13px;">Excellent</p>
                            </div>
                            <div>
                                <span class="grade-badge grade-A">A- (90-93%)</span>
                                <p style="margin-top: 5px; font-size: 13px;">Very Good</p>
                            </div>
                            <div>
                                <span class="grade-badge grade-B">B+ (87-89%)</span>
                                <p style="margin-top: 5px; font-size: 13px;">Good</p>
                            </div>
                            <div>
                                <span class="grade-badge grade-B">B (83-86%)</span>
                                <p style="margin-top: 5px; font-size: 13px;">Above Average</p>
                            </div>
                            <div>
                                <span class="grade-badge grade-B">B- (80-82%)</span>
                                <p style="margin-top: 5px; font-size: 13px;">Average</p>
                            </div>
                            <div>
                                <span class="grade-badge grade-C">C+ (77-79%)</span>
                                <p style="margin-top: 5px; font-size: 13px;">Below Average</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'components/script.php'; ?>
    <script>
        // Add click functionality to expand/collapse course details
        document.querySelectorAll('.course-header').forEach(header => {
            header.addEventListener('click', () => {
                let nextRow = header.nextElementSibling;
                while (nextRow && nextRow.classList.contains('component-row')) {
                    nextRow.style.display = nextRow.style.display === 'none' ? 'table-row' : 'none';
                    nextRow = nextRow.nextElementSibling;
                }
            });
        });

        // Initially hide all component rows
        document.querySelectorAll('.component-row').forEach(row => {
            row.style.display = 'none';
        });
    </script>
</body>
</html>