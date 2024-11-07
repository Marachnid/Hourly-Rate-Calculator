<?php
    require_once('pagetitles.php');
    $page_title = CALC_CREATE_PROFILE_PAGE;
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
                //call nav
                require_once('navmenu.php');
                
                //show create profile form if no details have been submitted
                $show_create_profile_form = true;

                //once form is submitted, process
                if (isset($_POST['signup_submission'])) {

                    //assign variables to form fields
                    $username = $_POST['username'];
                    $password = $_POST['password'];
                    $job_title = $_POST['job_title'];

                    //ratecalculation.php already contains non-user form values
                    //necessary to calculate profile $hourly_rate_total
                    require_once('ratecalculation.php');

                    
                    //validate necessary fields are entered
                    if (!empty($username) 
                            && !empty($weekly_hours) 
                            && !empty($salary) 
                            && !empty($password)) {

                        //call supporting scripts
                        require_once('dbconnection.php');
                        require_once('queryutils.php');

                        //connect to db
                        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
                                or trigger_error('Error connecting to MySQL Server for' . DB_NAME, E_USER_ERROR);

                        //check if username already exists
                        $query = "SELECT * FROM user WHERE username = ?";

                        $results = parameterizedQuery($dbc, $query, 's', $username)
                                or trigger_error(mysqli_error($dbc), E_USER_ERROR);


                        //if no user exists create new user and insert data
                        if (mysqli_num_rows($results) == 0) {

                            //hash password
                            $salted_hashed_password = password_hash($password, PASSWORD_DEFAULT);

                            $query = "INSERT INTO user (username, job_title, weekly_hours, 
                                    salary, vacation_day, sick_day, holiday, other, hourly_total, password_hash)
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, '$hourly_rate_total', '$salted_hashed_password')";


                            $result = parameterizedQuery($dbc, $query, 'ssiiiiii', $username, $job_title, $weekly_hours, 
                                    $salary, $vacation_days, $sick_days, $holidays, $other)
                                    or trigger_error(mysqli_error($dbc), E_USER_ERROR);

                            //success message, direct user to login page
                            echo "<h4><p class='text-success'>Thank you for signing up, <strong>$username</strong>! "
                                    . "Your new account has been successfully created.<br/>"
                                    . "You are now ready to <a href='login.php'>log in</a>.</p></h4>";
                            
                            //remove form from page
                            $show_create_profile_form = false;

                        } else {
                            
                            //error message for duplicate username
                            echo"<h4><p class='text-danger'>This username is already taken:<span class='font-weight-bold'> "
                                    . "($username)</span>. Please choose a different user name></p</h4>";
                        }
                    }
                }

                //if no details have been submitted, show form
                if ($show_create_profile_form) {
            ?>

                    <div class="float-left container-fluid w-50">
                        <form class="needs-validation" id="calculator_form" novalidate method="POST"
                                action="<?= $_SERVER['PHP_SELF'] ?>">

                            <div class="form-group row">
                                <label for="username" class="col-sm-3 col-form-label-lg">Username</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control"
                                            id="username" name="username"
                                            placeholder="Required"
                                            required>
                                    <div class="invalid-feedback">Please provide a username</div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="job_title" class="col-sm-3 col-form-label-lg">Job Title</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control"
                                            id="job_title" name="job_title"
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
                                            placeholder="Optional">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="sick_days" class="col-sm-3 col-form-label-lg">Sick Days</label>
                                <div class="col-sm-5">
                                    <input type="number" step="0.01" class="form-control"
                                            id="decimalInput" name="sick_days"
                                            placeholder="Optional">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="holidays" class="col-sm-3 col-form-label-lg">Holidays</label>
                                <div class="col-sm-5">
                                    <input type="number" step="0.01" class="form-control"
                                            id="decimalInput" name="holidays"
                                            placeholder="Optional">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="other" class="col-sm-3 col-form-label-lg">Other</label>
                                <div class="col-sm-5">
                                    <input type="number" step="0.01" class="form-control"
                                            id="decimalInput" name="other"
                                            placeholder="Optional">
                                    <p><i>*This could be any lump sum that you recieve in addition to your salary and PTO, such as a bonus, 401k match, and more.</i></p>
                                </div>
                            </div>

                            <div class="form-group-row">
                                <label for="password" class="col-sm-3 col-form-label-lg">Password</label>
                                <div class="col-sm-4">
                                    <input type="password" class="form-control"
                                            id="password" name="password"
                                            placeholder="Enter a password" required>
                                    <div class="form-group form-check">
                                        <input type="checkbox" class="form-check-input"
                                                id="show_password_check"
                                                onclick="togglePassword()">
                                        <label class="form-check-label"
                                                for="show_password_check">Show Password</label>
                                    </div>
                                    <div class="invalid-feedback">Please provide a valid password</div>
                                </div>
                            </div>

                            <button class="btn btn-primary" type="submit" name="signup_submission">Create Profile</button>
                            <button class="btn btn-primary" type="reset" name="reset_submission">Reset</button>

                        </form>
                    </div>

            <?php 
                }
            ?>
            </div>
        </div>

        <script>
            //js for form validation
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
                            form.classList.add('was-validated');
                        }, false);
                    })
                }, false);
            })();

            //toggles visible password
            function togglePassword() {
                var password_entry = document.getElementById("password");
                if (password_entry.type === "password") {
                    password_entry.type = "text";
                } else {
                    password_entry.type = "password";
                }
            }
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