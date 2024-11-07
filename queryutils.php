<?php


/*
@param $dbc                 //database connection
@param $sql_query           //sql statement
@param $data_types          //string containing characters for each parameter type
@param $query_parameters    //variable list of parameters representing each query parameter
*/

function parameterizedQuery($dbc, $sql_query, $data_types, ...$query_parameters) {

    $ret_val = false; //failure

    if ($stmt = mysqli_prepare($dbc, $sql_query)) {

        if (mysqli_stmt_bind_param($stmt, $data_types, ...$query_parameters) && mysqli_stmt_execute($stmt)) {
            $ret_val = mysqli_stmt_get_result($stmt);

            if (!mysqli_errno($dbc) && !$ret_val) {
                $ret_val = true;
            }
        }
    }
    return $ret_val;
}
?>