<?php
    require_once('pagetitles.php');
    $page_title = CALC_EDIT_PROFILE_PAGE;
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

    <body>
        <div class="card">
            <div class="card-body">

            <?php
                //call supporting scripts
                require_once('navmenu.php');
                require_once('dbconnection.php');
                require_once('queryutils.php');

                //connect to db
                $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
                        or trigger_error('Error connecting to MySQL Server for' . DB_NAME, E_USER_ERROR);
                

                //if edit profile was clicked from save
                if (isset($_GET['id_to_edit'])) {

                    //assign and query id to update
                    $id_to_edit = $_GET['id_to_edit'];
                    $user_id = $_SESSION['user_id'];

                    $query = "SELECT * FROM user WHERE user_id = ?";

                    $result = parameterizedQuery($dbc, $query, 'i', $id_to_edit)
                            or trigger_error(mysqli_error($dbc), E_USER_ERROR);

                    //if only one user was returned
                    if (mysqli_num_rows($result) == 1) {

                        //condition to prevent hotlinking by anyone not signed in 
                        //or signed into a different profile and trying to access someone else
                        //separate if to send back to index in else
                        if ($id_to_edit == $user_id) {

                            $row = mysqli_fetch_array($result);

                            //assign output variables to rows - must be divided by 100 - PHP/MySQL refused to save decimal POST values
                            $username = $row['username'];
                            $job_title = $row['job_title'];
                            $weekly_hours = $row['weekly_hours'] / 100;
                            $salary = $row['salary'] / 100;
                            $vacation_days = $row['vacation_day'] / 100;
                            $sick_days = $row['sick_day'] / 100;
                            $holidays = $row['holiday'] / 100;
                            $other = $row['other'] / 100;
                            $hourly_rate_total = $row['hourly_total'];

                        } else {

                            //redirects user to index upon unexpected navigation
                            $home_url = dirname($_SERVER['PHP_SELF']);
                            header('Location: ' . $home_url);
                            exit;
                        }

                    } else {

                        //if more or less than one profile are returned
                        echo '<h2>Error retrieving profile.</h2>';
                    }
                    
                //validate required fields are entered and form submitted
                } else if (isset(
                        $_POST['edit_user'],
                        $_POST['weekly_hours'],
                        $_POST['salary'])) {


                    //ratecalculation.php already contains form number values used in calculations
                    require_once('ratecalculation.php');

                    //grab unassigned form data
                    $job_title = $_POST['job_title'];
                    $id_to_update = $_POST['id_to_update'];

                    //update user
                    $query = "UPDATE user SET 
                            job_title = ?,
                            weekly_hours = ?, 
                            salary = ?,
                            vacation_day = ?,
                            sick_day = ?,
                            holiday = ?,
                            other = ?,
                            hourly_total = ?
                            WHERE user_id = ?";

                    parameterizedQuery($dbc, $query, 'siiiiiiii', $job_title, $weekly_hours, 
                            $salary, $vacation_days, $sick_days, $holidays, $other, $hourly_rate_total, $id_to_update)
                            or trigger_error(mysqli_error($dbc), E_USER_ERROR);

                    //redirect back to profile
                    header('Location: profile.php');

                } else {

                    //redirects user to index upon unexpected navigation
                    $home_url = dirname($_SERVER['PHP_SELF']);
                    header('Location: ' . $home_url);
                    exit;
                }

            ?>

                    <div class="float-left container-fluid w-50">
                        <form class="needs-validation" id="calculator_form" novalidate method="POST"
                                action="<?= $_SERVER['PHP_SELF'] ?>">

                            <div class="form-group row">
                                <label for="username" class="col-sm-3 col-form-label-lg">Username</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control"
                                            id="username" name="username"
                                            value="<?= $username ?>"
                                            placeholder="Required"
                                            disabled>
                                    <div class="invalid-feedback">Please provide a username</div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="job_title" class="col-sm-3 col-form-label-lg">Job Title</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control"
                                            id="job_title" name="job_title"
                                            value="<?= $job_title ?>"
                                            placeholder="Required"
                                            required>
                                    <div class="invalid-feedback">Please provide a title</div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="weekly_hours" class="col-sm-3 col-form-label-lg">Weekly Hours</label>
                                <div class="col-sm-5">
                                    <input type="number" step="0.01" class="form-control"
                                            id="decimalInput" name="weekly_hours"
                                            value="<?= $weekly_hours ?>"
                                            placeholder="Required"
                                            required>
                                    <div class="invalid-feedback">Please provide expected weekly work hours</div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="salary" class="col-sm-3 col-form-label-lg">Salary</label>
                                <div class="col-sm-5">
                                    <input type="number" step="0.01" class="form-control"
                                            id="decimalInput" name="salary"
                                            value="<?= $salary ?>" 
                                            placeholder="Required"
                                            required>
                                    <div class="invalid-feedback">Please provide a salary</div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="vacation_days" class="col-sm-3 col-form-label-lg">Vacation Days</label>
                                <div class="col-sm-5">
                                    <input type="number" step="0.01" class="form-control"
                                            id="decimalInput" name="vacation_days"
                                            value="<?= $vacation_days ?>"
                                            placeholder="Optional">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="sick_days" class="col-sm-3 col-form-label-lg">Sick Days</label>
                                <div class="col-sm-5">
                                    <input type="number" step="0.01" class="form-control"
                                            id="decimalInput" name="sick_days"
                                            value="<?= $sick_days ?>"
                                            placeholder="Optional">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="holidays" class="col-sm-3 col-form-label-lg">Holidays</label>
                                <div class="col-sm-5">
                                    <input type="number" step="0.01" class="form-control"
                                            id="decimalInput" name="holidays"
                                            value="<?= $holidays ?>"
                                            placeholder="Optional">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="other" class="col-sm-3 col-form-label-lg">Other</label>
                                <div class="col-sm-5">
                                    <input type="number" step="0.01" class="form-control"
                                            id="decimalInput" name="other"
                                            value="<?= $other ?>"
                                            placeholder="Optional">
                                    <p><i>*This could be any lump sum that you recieve in addition to your salary and PTO, such as a bonus, 401k match, and more.</i></p>
                                </div>
                            </div>

                            <button class="btn btn-primary" type="submit" name="edit_user">Save</button>
                            <button class="btn btn-primary" type="reset" name="reset_submission">Reset</button>
                            <input type="hidden" name="id_to_update" value="<?= $id_to_edit ?>">

                        </form>
                    </div>
            </div>
        </div>

        <script>
            //js for form validation - allows decimal numbers
            (function () {
                'use strict';
                window.addEventListener('load', function () {
                    var forms = document.getElementsByClassName('needs-validation');
                    var Validation = Array.prototype.filter.call(forms, function (form) {
                        form.addEventListener('submit', function (event) {
                            if (form.checkValidity() == false) {
                                event.preventDefault();
                                event.stopPropagation();
                            }
                        }, false);
                    })
                }, false);
            })();
        </script>

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