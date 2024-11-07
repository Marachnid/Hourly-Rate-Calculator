<?php
    session_start();

    if (isset($_SESSION['user_id'])) {

        $_SESSION = array();
        session_destroy();

        //set session data to null
        $_SESSION = [];
    }

    //redirect to index
    $home_url = dirname($_SERVER['PHP_SELF']);
    header('Location: ' . $home_url);
    exit;
