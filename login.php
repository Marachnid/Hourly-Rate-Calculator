<?php
    require_once('pagetitles.php');
    $page_title = CALC_LOGIN_PAGE;
?>

<!DOCTYPE html>
<html>
    <head>
        <title><?= $page_title ?></title>
        <link rel="stylesheet"
                href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css"
                integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS"
                crossorigin="anonymous">
    </head>

    <body>
        <div class="card">
            <div class="card-body">

            <?php
                //call nav
                require_once('navmenu.php');

                //process login if no user is currently logged in
                if (empty($_SESSION['user_id']) && isset($_POST['login_submission'])) {
                    
                    //assign username and password
                    $username = $_POST['username'];
                    $password = $_POST['password'];


                    //validate if fields are populated
                    if (!empty($username) && !empty($password)) {

                        //call supporting scripts
                        require_once('dbconnection.php');
                        require_once('queryutils.php');

                        //db connection
                        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
                                or trigger_error('Error connecting to MySQL server for ' . DB_NAME, E_USER_ERROR );

                        //search for user
                        $query = "SELECT user_id, username, password_hash FROM user WHERE username = ?";

                        $result = parameterizedQuery($dbc, $query, 's', $username)
                                or trigger_error(mysqli_error($dbc), E_USER_ERROR);


                        //validate password if only one user was returned
                        if (mysqli_num_rows($result) == 1) {
                        
                            $row = mysqli_fetch_array($result);

                            //password hash validation
                            if (password_verify($password, $row['password_hash'])) {
                            
                                //set session details
                                $_SESSION['user_id'] = $row['user_id'];
                                $_SESSION['username'] = $row['username'];

                                //send to profile after logging in
                                header('Location: profile.php');
                                exit;

                            } else {
                            
                                //error message if details were incorrect
                                echo "<h4><p class='text-danger'>Password and/or username were incorrect."
                                        . "</p></h4><hr/>";
                            }

                        //
                        } else {

                            //error message for no existing user or more than one user - purposely ambiguous to not leak usernames
                            echo "<h4><p class='text-danger'>Password and/or username were incorrect."
                                    . "</p></h4><hr/>";
                        }
                    }
                }

                //if the session is empty, show sign-in form
                if (empty($_SESSION['user_id'])) {
            ?>

                    <form class="needs-validation" novalidate method="POST"
                            action="<?= $_SERVER['PHP_SELF'] ?>">

                    <div class="form-group row">
                        <label for="username" class="col-sm-2 col-form-label-lg">Username</label>
                        
                        <div class="col-sm-4">
                        <input type="text" class="form-control" id="username"
                            name="username" placeholder="Enter a username"
                            required>
                        <div class="invalid-feedback">Please provide a valid username</div>

                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password" class="col-sm-2 col-form-label-lg">Password</label>

                        <div class="col-sm-4">
                            <input type="password" class="form-control"
                                id="password" name="password"
                                placeholder="Enter a password" required>
                            <div class="invalid-feedback">
                                Please provide a valid password
                            </div>
                        </div>
                    </div>

                        <button class="btn btn-primary" type="submit"
                            name="login_submission">Log In
                        </button>
                        <a href="index.php">
                            <button class="btn btn-primary">Cancel</button>
                        </a>  
                    </form>

            <?php
                } 
            ?>

            </div>
        </div>
        
        <script>
            //js validations
            (function () {
                'use strict';
                window.addEventListener('load', function () {
                    var forms = document.getElementsByClassName('needs-validation');
                    var validation = Array.prototype.filter.call(forms, function (form) {
                        form.addEventListener('submit', function (event) {
                            if (form.checkValidity() === false) {
                                event.preventDefault();
                                event.stopPropagation();
                            }
                            form.classList.add('was-validated');
                        }, false);
                    });
                }, false);
            })();
        </script>

        <!-- bootstrap -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
                integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
                crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"
                integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut"
                crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"
                integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k"
                crossorigin="anonymous"></script>
    </body>
</html>