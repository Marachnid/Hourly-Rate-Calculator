<?php
    require_once('pagetitles.php');
    $page_title = CALC_PROFILE_PAGE;
?>

<!DOCTYPE html>
<html>
    <head>
        <title><?= $page_title ?></title>
        <link rel="stylesheet" 
            href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
            integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk"
            crossorigin="anonymous">
    </head>

    <body style="width: 95%; margin: 0 auto">
        <div class="card">
            <div class="card-body" style="width: 95%; margin: 0 auto">

            <?php
                //call nav
                require_once('navmenu.php');

                //form/table modifier - adds unique fields only if logged in/at profile page
                $profile_page = true;

                //display if user is logged in - else, redirect
                if (isset($_SESSION['user_id'])) {

                    //call utility scripts
                    require_once('dbconnection.php');
                    require_once('queryutils.php');

                    //set variable for querying
                    $user_id = $_SESSION['user_id'];

                    //db connection
                    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
                            or trigger_error('Error connecting to MySQL server for ' . DB_NAME, E_USER_ERROR );

                    //pulls display info for user
                    $query = "SELECT username, job_title, weekly_hours, salary, vacation_day, sick_day, holiday, 
                            other, hourly_total 
                            FROM user WHERE user_id = ?";

                    $result = parameterizedQuery($dbc, $query, 'i', $user_id)
                            or trigger_error(mysqli_error($dbc), E_USER_ERROR);


                        //validates one user is returned
                        if (mysqli_num_rows($result) == 1) {

                            $row = mysqli_fetch_array($result);
                        }
            ?>

                            <!-- user profile data -->
                            <div class="float-left container-fluid w-auto">
                                <h2>Current Details</h2>
                                <table class="table table-hover w-auto">
                                    <tbody>
                                            <tr class="row-auto">
                                                <th scope="row">Username:</th>
                                                <td><?= $row['username'] ?></td>
                                            </tr>
                                            <tr class="row-auto">
                                                <th scope="row">Job Title:</th>
                                                <td><?= $row['job_title'] ?></td>
                                            </tr>
                                            <tr class="row-auto">
                                                <th scope="row">Expected weekly work hours:</th>
                                                <td><?= $row['weekly_hours'] / 100 ?> hours</td></tr>
                                            <tr class="row-auto">
                                                <th scope="row">Salary:</th>
                                                <td>$<?= number_format(($row['salary'] / 100), 2) ?></td>
                                            </tr>
                                            <tr class="row-auto">
                                                <th scope="row">Vacation:</th>
                                                <td><?= $row['vacation_day'] / 100 ?></td>
                                            </tr>
                                            <tr class="row-auto">
                                                <th scope="row">Sick day:</th>
                                                <td><?= $row['sick_day'] / 100 ?></td>
                                            </tr>
                                            <tr class="row-auto">
                                                <th scope="row">Holiday:</th>
                                                <td><?= $row['holiday'] / 100 ?></td>
                                            </tr>
                                            <tr class="row-auto">
                                                <th scope="row">Other:</th>
                                                <td>$<?= $row['other'] / 100 ?></td>
                                            </tr>
                                            <tr class="row-auto">
                                                <th scope="row"><u>Hourly Rate:</u></th>
                                                <td><strong>$<u><?= number_format(($row['hourly_total'] / 100), 2) ?></u></strong></td>
                                            </tr>
                                    </tbody>
                                </table>

                                <a href="editprofile.php?id_to_edit=<?= $_SESSION['user_id'] ?>">
                                    <button class="btn btn-primary">Edit Profile</button>
                                </a>
                            </div>

                    <div class="container">

                <?php
                    //calls hourly calculator form with results table display
                    require_once('hourlyform.php');
                ?>
                    </div>
                <?php

                    //allows a user to save the results table as an entry
                    if (isset($_POST['save_submission'])) {

                        //POST data absolutely refused to save as decimal values, values are multiplied
                        //by 100 to store, then divided by 100 to display correctly
                        $job_title = $_POST['save_job_title'];
                        $salary = $_POST['save_salary'];
                        $weekly_hours = $_POST['save_weekly_hours'];
                        $salary_hourly = $_POST['save_salary_hourly'] * 100;
                        $vacation_days_hourly = $_POST['save_vacation_days_hourly'] * 100;
                        $sick_days_hourly = $_POST['save_sick_days_hourly'] * 100;
                        $holidays_hourly = $_POST['save_holidays_hourly'] * 100;
                        $other_hourly = $_POST['save_other_hourly'] * 100;
                        $hourly_rate_total = $_POST['save_hourly_rate_total'];
            
                
                        //inserts data for user
                        $query = "INSERT INTO rate_log (job_title, weekly_hours, salary, salary_hourly, vacation_hourly, 
                                sick_hourly, holiday_hourly, other_hourly, hourly_total, user_id)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"; 
                                
                        $result = parameterizedQuery($dbc, $query, 'siiiiiiiii', $job_title, $weekly_hours, $salary,
                                $salary_hourly, $vacation_days_hourly, $sick_days_hourly, $holidays_hourly, $other_hourly, $hourly_rate_total, $user_id)
                                or trigger_error(mysqli_error($dbc), E_USER_ERROR);
                    }


                    //displays recent entries matched to user
                    $query = "SELECT log_id, job_title, weekly_hours, salary, salary_hourly, vacation_hourly, sick_hourly, 
                            holiday_hourly, other_hourly, hourly_total 
                            FROM rate_log WHERE user_id = ?
                            ORDER BY log_id DESC
                            LIMIT 10";

                    $result = parameterizedQuery($dbc, $query, 'i', $user_id)
                            or trigger_error(mysqli_error($dbc), E_USER_ERROR);

                    //if there are entries to display
                    if (mysqli_num_rows($result) > 0) {

                ?>
                        <!-- formatted output table -->
                        <div style="clear: both;">
                            <h3>Recent Entries</h3>
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Job Title</th>
                                        <th>Weekly Work Hours</th>
                                        <th>Salary</th>
                                        <th>Salary Hourly</th>
                                        <th>Vacation Hourly</th>
                                        <th>Sick Hourly</th>
                                        <th>Holiday Hourly</th>
                                        <th>Other Hourly</th>
                                        <th>Total Hourly Rate</th>
                                    </tr>
                                </thead>
                                <tbody>
            <?php
                        //while loop to display each record
                        while ($row = mysqli_fetch_assoc($result)) {
            ?>
                                <tr>
                                    <td><?= $row['job_title'] ?></td>
                                    <td><?= ($row['weekly_hours'] / 100) ?></td>
                                    <td>$<?= number_format(($row['salary'] / 100), 2)?></td>
                                    <td>$<?= $row['salary_hourly'] / 100 ?></td>
                                    <td>$<?= $row['vacation_hourly'] / 100 ?></td>
                                    <td>$<?= $row['sick_hourly'] / 100 ?></td>
                                    <td>$<?= $row['holiday_hourly'] / 100 ?></td>
                                    <td>$<?= $row['other_hourly'] / 100 ?></td>
                                    <td>$<?= number_format(($row['hourly_total'] / 100), 2) ?></td>

                                    <!-- deletes log on click - does not take to a separate page to confirm -->
                                    <td>
                                        <a class="nav-link" href="removelog.php?id_to_delete=<?= $row['log_id'] ?>">Delete</a>
                                    </td>
                                </tr>

                    <?php
                        }
                    ?>

                                </tbody>
                            </table>
                        </div>
            <?php
                    } else {

                        //message for no existing entries
                        echo '<div style="clear: both;"><br><h3>Recent Entries</h3><br><p>No Entries to display</p></div>';
                    }
                } else {

                    //handles unexpected navigation, redirects to index
                    $home_url = dirname($_SERVER['PHP_SELF']);
                    header('Location: ' . $home_url);
                    exit;
                }
            ?>
            </div>
        </div>

    <!-- bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script> 
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
        integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI"
        crossorigin="anonymous"></script>
    </body>
</html>