<?php

    //prep calculation data - ton of issues trying to store decimal values into MySQL even with datatypes set to decimal at max precision
    //storing as ints * 100 instead - output must be divided by 100 to display correctly

    //sets optional values to 0 if nothing is entered
    if (!empty($_POST['vacation_days'])) {

        $vacation_days = $_POST['vacation_days'] * 100;

    } else {

        $vacation_days = 0;
    }
    
    
    if (!empty($_POST['sick_days'])) {

        $sick_days = $_POST['sick_days'] * 100;

    } else {

        $sick_days = 0;
    } 
    
    if (!empty($_POST['holidays'])) {

        $holidays = $_POST['holidays'] * 100;

    } else {

        $holidays = 0;
    }
    
    if (!empty($_POST['other'])) {

        $other = $_POST['other'] * 100;

    } else {

        $other = 0;
    }

    $weekly_hours = $_POST['weekly_hours'] * 100;
    $salary = $_POST['salary'] * 100;

    //calculate hourly values
    //hourly total rate is stored as a decimal value in MySQL while POST values aren't, multiplied for consistency and increased precision on output
    $annual_hourly_modifier = $weekly_hours * 52; //x hours per week * 52 weeks in a year
    $salary_hourly = $salary / $annual_hourly_modifier;
    $vacation_days_hourly = ($vacation_days * 8) * $salary_hourly / $annual_hourly_modifier;
    $sick_days_hourly = ($sick_days * 8) * $salary_hourly / $annual_hourly_modifier;
    $holidays_hourly = ($holidays * 8) * $salary_hourly / $annual_hourly_modifier;
    $other_hourly = $other / $annual_hourly_modifier;
    $hourly_rate_total = ($salary_hourly + $vacation_days_hourly + $sick_days_hourly + $holidays_hourly + $other_hourly) * 100;