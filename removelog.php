<?php
    session_start();

    if (isset($_SESSION['user_id'])) {
        //call supporting scripts
        require_once('dbconnection.php');
        require_once('queryutils.php');

        //assign log id to delete
        $id_to_delete = $_GET['id_to_delete'];

        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
        or trigger_error('Error connecting to MySQL server for ' . DB_NAME, E_USER_ERROR );

        $query = "SELECT * FROM rate_log WHERE log_id = ?";

        $result = parameterizedQuery($dbc, $query, 'i', $id_to_delete);

        $row = mysqli_fetch_array($result);

        //condition to prevent hotlinking
        if (isset($_GET['id_to_delete']) && ($row['user_id'] == $_SESSION['user_id'])) {

            //if only one result is returned
            if (mysqli_num_rows($result) == 1) {

                $row = mysqli_fetch_assoc($result);
            
                $query = "DELETE FROM rate_log WHERE log_id = ?";
            
                $results = parameterizedQuery($dbc, $query, 'i', $id_to_delete)
                        or trigger_error(mysqli_error($dbc), E_USER_ERROR);
        
                //redirect to profile after deletion
                header('Location: profile.php');
                exit;
            } else {

                echo '<h2>Error Retrieving Data.';
            }

        } else {
            
            echo '<h2>No log to delete.</h2>';
        }
    } else {

        //handles unexpected navigation, redirects to index
        $home_url = dirname($_SERVER['PHP_SELF']);
        header('Location: ' . $home_url);
        exit;
    }