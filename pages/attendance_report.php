<!-- Container and Page Title -->
<div class="container d-flex justify-content-center">
    <div class="page-title mb-3">Attendance Report</div>
</div>

<?php 
// Fetch class list
$classList = $actionClass->list_class();
// Get class ID and month from URL parameters, defaulting to empty string
$class_id = $_GET['class_id'] ?? "";
$class_month = $_GET['class_month'] ?? "";
// Get list of students for the selected class and month
$studentList = $actionClass->attendanceStudentsMonthly($class_id, $class_month);
// Calculate the last day of the previous month
$monthLastDay = 0;
if(!empty($class_month)){
    $monthLastDay = date("d", strtotime("{$class_month}-1 -1 day -1 month")) ;
}
?>

<!-- Attendance Report Form -->
<form action="" id="manage-attendance">
    <div class="row justify-content-center">
        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
            <div id="msg"></div>
            <!-- Class and Date Selection -->
            <div class="card shadow mb-3">
                <div class="card-body rounded-0">
                    <div class="container-fluid">
                        <div class="row align-items-end">
                            <!-- Class Selection Dropdown -->
                            <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                <label for="class_id" class="form-label">Class</label>
                                <select name="class_id" id="class_id" class="form-select" required="required">
                                    <option value="" disabled <?= empty($class_id) ? "selected" : "" ?>> -- Select Here -- </option>
                                    <?php if(!empty($classList) && is_array($classList)): ?>
                                    <?php foreach($classList as $row): ?>
                                        <option value="<?= $row['id'] ?>" <?= (isset($class_id) && $class_id == $row['id']) ? "selected" : "" ?>><?= $row['name'] ?></option>
                                    <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <!-- Date Selection Input -->
                            <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                <label for="class_month" class="form-label">Date</label>
                                <input type="month" name="class_month" id="class_month" class="form-control" value="<?= $class_month ?? '' ?>" required="required">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Legend for Attendance Codes -->
            <?php if(!empty($class_id) && !empty($class_month)): ?>
            <div class="card shadow mb-3">
                <div class="card-body">
                    <div class="container-fluid">
                        <fieldset>
                            <div class="ps-4">
                                <div><span class="text-success fw-bold">P</span> <span class="ms-1">= Present</span></div>
                                <div><span class="text-body-emphasis fw-bold">L</span> <span class="ms-1">= Late</span></div>
                                <div><span class="text-danger fw-bold">A</span> <span class="ms-1">= Absent</span></div>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>

            <!-- Attendance Table -->
            <div class="card shadow mb-3">
                <div class="card-body">
                    <div class="container-fluid">
                        <div class="px-2 py-2 text-center bg-success text-light fw-bolder"><?= date("F", strtotime($class_month)) ?></div>
                        <div class="table-responsive position-relative">
                            <table id="attendance-rpt-tbl" class="table table-bordered">
                                <thead>
                                    <tr class="bg-primary bg-opacity-75">
                                        <!-- Table Headers for Each Day of the Month -->
                                        <th class="text-center bg-secondary text-light" style="width:300px !important; min-width: 300px; max-width: 300px;">Students</th>
                                        <?php for($i=1; $i <= $monthLastDay; $i++): ?>
                                            <th class="text-center" style="min-width: 50px; max-width: 50px; width: 50px"><?= $i ?></th>
                                        <?php endfor; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($studentList) && is_array($studentList)): ?>
                                    <?php foreach($studentList as $row): ?>
                                        <tr class="student-row">
                                            <!-- Student Name Column -->
                                            <td class="px-2 py-1 text-dark-emphasis fw-bold" style="width:300px !important; min-width: 300px; max-width: 300px;">
                                                <input type="hidden" name="student_id[]" value="<?= $row['id'] ?>">
                                                <?= $row['name'] ?>
                                            </td>
                                            <?php 
                                            // Initialize variables to count attendance codes
                                            $tp = 0; // Present
                                            $tl = 0; // Late
                                            $ta = 0; // Absent
                                            ?>
                                            <?php for($i=1; $i <= $monthLastDay; $i++): ?>
                                                <!-- Attendance Status for Each Day -->
                                                <td class="text-center px-2 py-1 text-dark-emphasis" style="min-width: 50px; max-width: 50px; width: 50px;">
                                                    <?php 
                                                        $i = str_pad($i, 2, 0, STR_PAD_LEFT); // Pad single-digit day with leading zero
                                                        // Display attendance code based on the student's attendance for the day
                                                        switch(($row['attendance'][$class_month."-".$i] ?? '')){
                                                            case 1:
                                                                echo "<span class='text-success fw-bold'>P</span>"; // Present
                                                                $tp += 1; // Increment present count
                                                                break;
                                                            case 2:
                                                                echo "<span class='text-body-emphasis fw-bold'>L</span>"; // Late
                                                                $tl += 1; // Increment late count
                                                                break;
                                                            case 3:
                                                                echo "<span class='text-danger fw-bold'>A</span>"; // Absent
                                                                $ta += 1; // Increment absent count
                                                                break;
                                                        }
                                                    ?>
                                                </td>
                                            <?php endfor; ?>
                                            
                                        </tr>
                                    <?php endforeach; ?>
                                    <?php else: ?>
                                        <!-- No Students Listed Message -->
                                        <tr>
                                            <td colspan="<?= $monthLastDay + 1 ?>" class="px-2 py-1 text-center">No Student Listed Yet</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <?php endif; ?>
        </div>
    </div>
</form>
<script>
    $(document).ready(function(){
        $('#class_id, #class_month').change(function(e){
            var class_id = $('#class_id').val()
            var class_month = $('#class_month').val()
            location.replace(`./?page=attendance_report&class_id=${class_id}&class_month=${class_month}`)
        })
    })
    
</script>

