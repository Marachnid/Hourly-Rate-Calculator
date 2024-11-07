<?php
    //once form is submitted, process
    if (isset($_POST['calculate_submission'])) {

        //unique profile variable
        if ($profile_page) {
            $job_title = $_POST['job_title'];
        }

        //call calcualtions for hourly values
        require_once('ratecalculation.php');

        //call formatted html table of results
        require_once('hourlyresultstable.php');


    //clears sticky data pre-inserted into form with ternaries
    } else if (isset($_POST['reset_submission'])) {

        unset($_POST);
    }
?>

    <div class="float-left container-fluid w-50">
        <h2>Calculator</h2>
        <form class="needs-validation" id="calculator_form" novalidate method="POST"
                action="<?= $_SERVER['PHP_SELF'] ?>">

<?php
    //condition to show unique title field for profile
    if ($profile_page) {
?>
            <!-- fields contain ternary operations to repopulate form with previously entered data -->
            <div class="form-group row">
                <label for="job_title" class="col-sm-4 col-form-label-lg">Job Title</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control w-auto"
                            id="job_title" name="job_title" 
                            value="<?php echo isset($_POST['job_title']) ? ($_POST['job_title']) : ''; ?>"
                            placeholder="required"
                            required>
                    <div class="invalid-feedback">Please provide a job title</div>
                </div>
            </div>

<?php
    }
?>

            <div class="form-group row">
                <label for="weekly_hours" class="col-sm-4 col-form-label-lg">Weekly Hours</label>
                <div class="col-sm-5">
                    <input type="number" step="0.01" class="form-control w-auto"
                            id="weekly_hours" name="weekly_hours" 
                            value="<?php echo isset($_POST['weekly_hours']) ? ($_POST['weekly_hours']) : ''; ?>"
                            placeholder="required"
                            required>
                    <div class="invalid-feedback">Please provide expected weekly work hours</div>
                </div>
            </div>

            <div class="form-group row">
                <label for="salary" class="col-sm-4 col-form-label-lg">Salary</label>
                <div class="col-sm-5">
                    <input type="number" step="0.01" class="form-control w-auto"
                            id="salary" name="salary" 
                            value="<?php echo isset($_POST['salary']) ? ($_POST['salary']) : ''; ?>"
                            placeholder="required"
                            required>
                    <div class="invalid-feedback">Please provide a salary</div>
                </div>
            </div>

            <div class="form-group row">
                <label for="vacation_days" class="col-sm-4 col-form-label-lg">Vacation Days</label>
                <div class="col-sm-5">
                    <input type="number" step="0.01" class="form-control w-auto"
                            id="vacation_days" name="vacation_days"
                            value="<?php echo isset($_POST['vacation_days']) ? ($_POST['vacation_days']) : ''; ?>">
                </div>
            </div>

            <div class="form-group row">
                <label for="sick_days" class="col-sm-4 col-form-label-lg">Sick Days</label>
                <div class="col-sm-5">
                    <input type="number" step="0.01" class="form-control w-auto"
                            id="sick_days" name="sick_days"
                            value="<?php echo isset($_POST['sick_days']) ? ($_POST['sick_days']) : ''; ?>">
                </div>
            </div>

            <div class="form-group row">
                <label for="holidays" class="col-sm-4 col-form-label-lg">Holidays</label>
                <div class="col-sm-5">
                    <input type="number" step="0.01" class="form-control w-auto"
                            id="holidays" name="holidays"
                            value="<?php echo isset($_POST['holidays']) ? ($_POST['holidays']) : ''; ?>">
                </div>
            </div>

            <div class="form-group row">
                <label for="other" class="col-sm-4 col-form-label-lg">Other</label>
                <div class="col-sm-5">
                    <input type="number" step="0.01" class="form-control w-auto"
                            id="other" name="other"
                            value="<?php echo isset($_POST['other']) ? ($_POST['other']) : ''; ?>"
                            placeholder="lump sum">
                </div>
            </div>

            <button class="btn btn-primary" type="submit" name="calculate_submission">Calculate</button>
            <button class="btn btn-primary" type="submit" name="reset_submission">Clear</button>
        </form>
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
        </script>
