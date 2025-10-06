<?php
    $page = 'act_log';
    include 'database.php';
    include "auth_middleware.php";
        checkAuth();
        checkRole('admin');
    if (!isset($_SESSION['admin_name'])) {
        header('location:index.php');
    }
    $adminID = $_SESSION['adminID'];
    $userResult = mysqli_query($conn, "SELECT * FROM user WHERE user_id = $adminID");
    if (mysqli_num_rows($userResult) > 0) {
        $userData = mysqli_fetch_array($userResult);
    }
    $defaultImage = '../images/default.jpg';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'include/header.php'; ?>
</head>
<body id="page-top">
    <div id="wrapper">
        <?php include('include/sidebar.php'); ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <?php include('include/topbar.php'); ?>
            <div id="content" class="bg-white">
                <div class="container-fluid p-0">
                    <div class="card mb-4 border border-0" style="background-color: transparent;">
                        <div class="card-body">
                            <form id="filterForm" class="mb-3">
                                <div class="d-flex gap-2 justify-content-end">
                                    <input type="date" id="datePicker" name="filter_date" class="form-control" placeholder="Select Date" style="width: 25%;">
                                </div>
                            </form>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="LogTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th class="d-none">#</th>
                                            <th>Name</th>
                                            <th>Date</th>
                                            <th>Login</th>
                                            <th>Logout</th>
                                            <th>Time Spent</th>
                                            <th>Activity</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $resultLog = mysqli_query($conn, "SELECT a.*, u.name, u.user_type FROM log a INNER JOIN user u ON a.user_id = u.user_id");
                                        $counter = 1;
                                        $logs = [];
                                        $active_users = [];
                                        $inactive_users = [];
                                        while ($dataLog = mysqli_fetch_array($resultLog)) {
                                            $user_id = $dataLog['user_id'];
                                            if (!isset($logs[$user_id])) {
                                                $logs[$user_id] = [];
                                            }
                                            $logs[$user_id][] = $dataLog;
                                        }
                                        foreach ($logs as $user_logs) 
                                        {
                                            $login_time = null;
                                            $logout_time = null;
                                            foreach ($user_logs as $log) 
                                            {
                                                if (stripos($log['description'], 'login') !== false) 
                                                {
                                                    $login_time = strtotime($log['date'] . ' ' . $log['time']);
                                                } 
                                                elseif (stripos($log['description'], 'logout') !== false && $login_time) 
                                                {
                                                    $logout_time = strtotime($log['date'] . ' ' . $log['time']);
                                                    $time_spent = $logout_time - $login_time;

                                                    $activities = [];

                                                    foreach ($user_logs as $activity_log) {
                                                        $activity_time = strtotime($activity_log['date'] . ' ' . $activity_log['time']);

                                                        if ($activity_time > $login_time && $activity_time < $logout_time) {
                                                            $activities[] = [
                                                                'time' => date('h:i:s A', $activity_time),
                                                                'description' => $activity_log['description']
                                                            ];
                                                        }
                                                    }
                                                    $inactive_users[] = [
                                                        'counter' => $counter++,
                                                        'name' => $log['name'],
                                                        'user_type' => $log['user_type'],
                                                        'date' => date('Y-m-d', $login_time),
                                                        'login_time' => date('h:i:s A', $login_time),
                                                        'logout_time' => date('h:i:s A', $logout_time),
                                                        'time_spent' => sprintf('%02d:%02d:%02d', floor($time_spent / 3600), floor(($time_spent % 3600) / 60), $time_spent % 60),
                                                        'activities' => $activities
                                                    ];
                                                    $login_time = null;
                                                    $logout_time = null;
                                                }
                                            }
                                            if ($login_time && !$logout_time) 
                                            {
                                                $active_users[] = [
                                                    'counter' => $counter++,
                                                    'name' => $user_logs[0]['name'],
                                                    'user_type' => $user_logs[0]['user_type'],
                                                    'date' => date('Y-m-d', $login_time),
                                                    'login_time' => date('h:i:s A', $login_time),
                                                    'logout_time' => '-- : -- : -- --',
                                                    'time_spent' => '-- : -- : -- --',
                                                    'activities' => []
                                                ];
                                            }
                                        }
                                        usort($inactive_users, function($a, $b) {
                                            return strtotime($b['logout_time']) - strtotime($a['logout_time']);
                                        });
                                        foreach ($active_users as $user) {
                                            ?>
                                            <tr>
                                                <td class="d-none"><?php echo $user['counter'] ?></td>
                                                <td class="d-flex justify-content-between">
                                                    <div><?php echo $user['name'] ?></div>
                                                    <div>(<?php echo $user['user_type'] ?>)</div>
                                                </td>
                                                <td><?php echo $user['date']; ?></td>
                                                <td><?php echo $user['login_time']; ?></td>
                                                <td><?php echo $user['logout_time']; ?></td>
                                                <td><?php echo $user['time_spent']; ?></td>
                                                <td class="text-center">
                                                    <i class="fa-solid fa-stopwatch text-danger" data-bs-toggle="tooltip" data-bs-placement="top" style="cursor: pointer;" title="<?php echo $user['name'] ?> still using the system"></i>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        foreach ($inactive_users as $user) {
                                            ?>
                                            <tr>
                                                <td class="d-none"><?php echo $user['counter'] ?></td>
                                                <td class="d-flex justify-content-between">
                                                    <div><?php echo $user['name'] ?></div>
                                                    <div>(<?php echo $user['user_type'] ?>)</div>
                                                </td>
                                                <td><?php echo $user['date']; ?></td>
                                                <td><?php echo $user['login_time']; ?></td>
                                                <td><?php echo $user['logout_time']; ?></td>
                                                <td><?php echo $user['time_spent']; ?></td>
                                                <td class="text-center">
                                                    <i class="fa-solid fa-book-open-reader text-success" data-bs-toggle="modal" data-bs-target="#activitiesModal<?php echo $user['counter'] ?>" style="cursor: pointer;" data-toggle="tooltip" data-bs-placement="top" title="View Details"></i>
                                                </td>
                                            </tr>
                                            <div class="modal fade animated--grow-in" id="activitiesModal<?php echo $user['counter'] ?>" tabindex="-1" role="dialog" aria-labelledby="activitiesModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header d-flex align-items-center">
                                                            <strong class="text-dark">Activities</strong>
                                                            <button id="closeModal" type="button" class="btn-close rounded-0" data-bs-dismiss="modal" aria-label="Close" style="font-size: 12px;"></button>
                                                        </div>
                                                        <div class="modal-body p-2">
                                                            <?php if (!empty($user['activities'])): ?>
                                                                <?php foreach ($user['activities'] as $activity): ?>
                                                                    <div class="d-flex justify-content-between">
                                                                        <div class="border-0 px-3 py-3" style="width: 50%;"> <?php echo $activity['description'] ?> </div>
                                                                        <div class="border-0 text-center px-3 py-3" style="width: 50%;"> <?php echo $activity['time'] ?> </div>
                                                                    </div>
                                                                <?php endforeach; ?>
                                                            <?php else: ?>
                                                                <p class="text-center">The user has not performed any actions yet.</p>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include('include/footer.php'); ?>
</body>
</html>

<script>
    $(document).ready(function() {
        var table = $('#LogTable').DataTable({
            info: false,
            order: [],  // No initial ordering
            columnDefs: [
                { orderable: true, targets: 2 },  // Make only the Date column orderable (index 2)
                { orderable: false, targets: [0, 1, 3, 4, 5, 6] }  // Make other columns not orderable
            ],
            language: {
                paginate: {
                    previous: '<i class="fas fa-angle-left"></i>',  // Custom previous button icon
                    next: '<i class="fas fa-angle-right"></i>'  // Custom next button icon
                }
            }
        });
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        function setCurrentDate() {
            var today = new Date();
            var month = String(today.getMonth() + 1).padStart(2, '0');
            var day = String(today.getDate()).padStart(2, '0');
            var year = today.getFullYear();
            var currentDate = year + '-' + month + '-' + day;
            $('#datePicker').val(currentDate);
        }
        setCurrentDate();
        $('#datePicker').on('change', function() {
            var selectedDate = $(this).val();
            if (selectedDate) {
                var dateString = new Date(selectedDate).toISOString().split('T')[0];
                table.column(2).search(dateString).draw();
            } else {
                table.column(2).search('').draw();
            }
        });
    });
</script>