<?php
    $page_title = isset($page_title) ? $page_title : "";

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
?>

    <header>
        <h1><?= $page_title ?></h1>
        <nav>
            <a <?= $page_title == CALC_HOME_PAGE ? ' active' : '' ?> href="<?= dirname($_SERVER['PHP_SELF']) ?>">Home</a> &bull;

        <?php
            //alters navbar if a user is logged in
            if (isset($_SESSION['user_id'])) {
        ?>

            <a <?= $page_title == CALC_PROFILE_PAGE ? ' active' : '' ?> href="profile.php">Profile</a> &bull;
            <a href="logout.php">Logout (<?= $_SESSION['username'] ?>)</a>

        <?php
            //no user nav options
            } else {
        ?>

            <a <?= $page_title == CALC_CREATE_PROFILE_PAGE ? ' active' : '' ?> href="createprofile.php">Create Profile</a> &bull;
            <a <?= $page_title == CALC_LOGIN_PAGE ? ' active' : '' ?> href="login.php">Login</a>

        <?php
            }
        ?>
        </nav>
    </header>
    <hr>