    <!-- formatted results table -->
    <div class="float-right container-fluid w-50">
        <h2>Results</h2>
        <table class="table table-hover">
            <tbody>

<?php 
    //condition to display unique profile field
    if ($profile_page) {
?>
                <tr>
                    <th scope="row">Job Title:</th>
                    <td><?= $job_title ?></td></tr>
                <tr>
<?php
    }
?>
                <tr>
                    <th scope="row">Expected weekly work hours:</th>
                    <td><?= $weekly_hours / 100?> hours</td></tr>
                <tr>
                    <th scope="row">Salary:</th>
                    <td>$<?= number_format(($salary / 100), 2) ?></td>
                </tr>
                <tr>
                    <th scope="row">Salary hourly:</th>
                    <td>$<?= round($salary_hourly, 2) ?></td>
                </tr>
                <tr>
                    <th scope="row">Vacation hourly:</th>
                    <td>$<?= round($vacation_days_hourly, 2) ?></td>
                </tr>
                <tr>
                    <th scope="row">Sick day hourly:</th>
                    <td>$<?= round($sick_days_hourly, 2) ?></td>
                </tr>
                <tr>
                    <th scope="row">Holiday hourly:</th>
                    <td>$<?= round($holidays_hourly, 2) ?></td>
                </tr>
                <tr>
                    <th scope="row">Other hourly:</th>
                    <td>$<?= round($other_hourly, 2) ?></td>
                </tr>
                <tr>
                    <th scope="row"><u>Total Hourly Rate:</u></th>
                    <td><strong>$<u><?= number_format(($hourly_rate_total / 100), 2) ?></u></strong></td>
                </tr>

<?php
    //conditional logic to allow users to save their calculations from profile page
    if ($profile_page) {
?>
            <form class="needs-validation" novalidate method="POST"
                    action="<?= $_SERVER['PHP_SELF'] ?>"> 
                <input type="hidden" name="save_job_title" value="<?= $job_title ?>">
                <input type="hidden" name="save_weekly_hours" value="<?= $weekly_hours ?>">
                <input type="hidden" name="save_salary" value="<?= $salary ?>">        
                <input type="hidden" name="save_salary_hourly" value="<?= $salary_hourly ?>">    
                <input type="hidden" name="save_vacation_days_hourly" value="<?= $vacation_days_hourly ?>">    
                <input type="hidden" name="save_sick_days_hourly" value="<?= $sick_days_hourly ?>">    
                <input type="hidden" name="save_holidays_hourly" value="<?= $holidays_hourly ?>">    
                <input type="hidden" name="save_other_hourly" value="<?= $other_hourly ?>">    
                <input type="hidden" name="save_hourly_rate_total" value="<?= $hourly_rate_total ?>">    
                <input type="hidden" name="save_user_id" value="<?= $user_id ?>">

                <tr>
                    <td><button class="btn btn-primary" type="submit" name="save_submission">Save</button></td>
                </tr>
            </form>  

<?php
    }
?>

            </tbody>
        </table>

        <p><i>*Display numbers are rounded to two decimal points,
            <br>calculations are unrounded.</i></p>

        <p>
            <i>*Calculation format:
            <br>Salary &amp; Other = value / (weekly hours * 52 weeks) 
            <br>PTO = (days * 8) * salary hourly / (weekly hours * 52 weeks)</i>
        </p>
    </div>


