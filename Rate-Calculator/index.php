<?php
    require_once('pagetitles.php');
    $page_title = CALC_HOME_PAGE;
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
            <div class="card-body" style="width: 95%; margin: 0 auto">

            <?php
                //modifier adjusts calculator and output table based on profile page or index
                $profile_page = false;

                //call nav
                require_once('navmenu.php');
            ?>

                <p>This calculator is designed to calculate an hourly wage based on your salary and benefits (Vacation, Sick, Holiday, other compensation additives),
                        taking annual values, determining their respective hourly values, and provide a cumulative total.</p>
                <p>Consider creating a profile to compare different job compensation packages against each other!<p>
                <hr>

            <?php
            //allows un-signed-in access to calculator
            require_once('hourlyform.php');
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