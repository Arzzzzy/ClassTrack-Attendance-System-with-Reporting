<div class="container d-flex justify-content-center">
        <div class="page-title mb-3">Student Attendance</div>
    </div>
<?php 
// Fetch list of classes and initialize class ID and date from GET parameters
$classList = $actionClass->list_class();
$class_id = $_GET['class_id'] ?? "";
$class_date = $_GET['class_date'] ?? "";
$studentList = $actionClass->attendanceStudents($class_id, $class_date);
?>
<form action="" id="manage-attendance">
    <div class="row justify-content-center">
        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
            <div id="msg"></div>
            <div class="card shadow mb-3">
                <div class="card-body rounded-0">
                    <div class="container-fluid">
                        <div class="row align-items-end">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                 <!-- Dropdown for selecting class -->
                                <label for="class_id" class="form-label">Section - Class </label>
                                <select name="class_id" id="class_id" class="form-select" required="required">
                                    <option value="" disabled <?= empty($class_id) ? "selected" : "" ?>> -- Select Here -- </option>
                                    <?php if(!empty($classList) && is_array($classList)): ?>
                                    <?php foreach($classList as $row): ?>
                                        <option value="<?= $row['id'] ?>" <?= (isset($class_id) && $class_id == $row['id']) ? "selected" : "" ?>><?= $row['name'] ?></option>
                                    <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                <!-- Date picker for selecting date -->
                                <label for="class_date" class="form-label">Date</label>
                                <input type="date" name="class_date" id="class_date" class="form-control" value="<?= $class_date ?? '' ?>" required="required">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Display attendance sheet if class and date are selected -->
            <?php if(!empty($class_id) && !empty($class_date)): ?>
            <div class="card shadow mb-3">
                <div class="card-header rounded-0">
                    <div class="card-title">Attendance Sheet</div>
                </div>
                <div class="card-body">
                    <div class="container-fluid">
                        <div class="table-responsive">
                            <table id="attendance-tbl" class="table table-bordered">
                                <colgroup>
                                    <col width="40%">
                                    <col width="20%">
                                    <col width="20%">
                                    <col width="20%">
                                </colgroup>
                                <thead class="bg-primary">
                                    <tr>
                                        <th class="text-center bg-secondary text-light">Students</th>
                                        <th class="text-center bg-success text-light">Present</th>
                                        <th class="text-center bg-dark text-light">Late</th>
                                        <th class="text-center bg-danger text-light">Absent</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Row for check/uncheck all -->
                                    <tr>
                                        <th class="text-center px-2 py-1 text-dark-emphasis">Check/Uncheck All</th>
                                        <th class="text-center px-2 py-1 text-dark-emphasis">
                                            <div class="form-check d-flex w-100 justify-content-center">
                                                <input class="form-check-input checkAll" type="checkbox" id="PCheckAll">
                                                <label class="form-check-label" for="PCheckAll">
                                                </label>
                                            </div>
                                        </th>
                                        <th class="text-center px-2 py-1 text-dark-emphasis">
                                            <div class="form-check d-flex w-100 justify-content-center">
                                                <input class="form-check-input checkAll" type="checkbox" id="LCheckAll">
                                                <label class="form-check-label" for="LCheckAll">
                                                </label>
                                            </div>
                                        </th>
                                        <th class="text-center px-2 py-1 text-dark-emphasis">
                                            <div class="form-check d-flex w-100 justify-content-center">
                                                <input class="form-check-input checkAll" type="checkbox" id="ACheckAll">
                                                <label class="form-check-label" for="ACheckAll">
                                                </label>
                                            </div>
                                        </th>
                                    </tr>
                                    <!-- Display student list with attendance options -->
                                    <?php if(!empty($studentList) && is_array($studentList)): ?>
                                    <?php foreach($studentList as $row): ?>
                                        <tr class="student-row">
                                            <td class="px-2 py-1 text-dark-emphasis fw-bold">
                                                <input type="hidden" name="student_id[]" value="<?= $row['id'] ?>">
                                                <?= $row['name'] ?>
                                            </td>
                                            <td class="text-center px-2 py-1 text-dark-emphasis">
                                                <div class="form-check d-flex w-100 justify-content-center">
                                                    <input class="form-check-input status_check" data-id="<?= $row['id'] ?>" type="checkbox" name="status[]" value="1" id="status_p_<?= $row['id'] ?>" <?= (isset($row['status']) && $row['status'] == 1) ? "checked" : "" ?>>
                                                    <label class="form-check-label" for="status_p_<?= $row['id'] ?>">
                                                    </label>
                                                </div>
                                            </td>
                                            <td class="text-center px-2 py-1 text-dark-emphasis">
                                                <div class="form-check d-flex w-100 justify-content-center">
                                                    <input class="form-check-input status_check" data-id="<?= $row['id'] ?>" type="checkbox" name="status[]" value="2" id="status_l_<?= $row['id'] ?>" <?= (isset($row['status']) && $row['status'] == 2) ? "checked" : "" ?>>
                                                    <label class="form-check-label" for="status_l_<?= $row['id'] ?>">
                                                    </label>
                                                </div>
                                            </td>
                                            <td class="text-center px-2 py-1 text-dark-emphasis">
                                                <div class="form-check d-flex w-100 justify-content-center">
                                                    <input class="form-check-input status_check" data-id="<?= $row['id'] ?>" type="checkbox" name="status[]" value="3" id="status_a_<?= $row['id'] ?>" <?= (isset($row['status']) && $row['status'] == 3) ? "checked" : "" ?>>
                                                    <label class="form-check-label" for="status_a_<?= $row['id'] ?>">
                                                    </label>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="px-2 py-1 text-center">No Student Listed Yet</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Save attendance button -->
            <div class="d-flex w-100 justify-content-center align-items-center">
                <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                    <button class="btn btn-warning rounded w-100" type="submit">Save Attendance</button>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</form>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.20/dist/sweetalert2.min.js"></script>

<script>
    $(document).ready(function(){
        // Initial check for "Check All" boxes
        checkAll_count()

        // Reload page with selected class and date
        $('#class_id, #class_date').change(function(e){
            var class_id = $('#class_id').val()
            var class_date = $('#class_date').val()
            location.replace(`./?page=attendance&class_id=${class_id}&class_date=${class_date}`)
        })
        // Ensure only one status is checked per student
        $('.status_check').change(function(){
            var student_id = $(this)[0].dataset?.id
            var isChecked = $(this).is(":checked")
            if(isChecked === true){
                $(`.status_check[data-id='${student_id}']`).prop("checked", false)
                $(this).prop("checked", true)
            }
            checkAll_count()
        })
        // "Check All" functionality for Present, Late, and Absent
        $('.checkAll').change(function(){
            var _this = $(this)
            var isChecked = $(this).is(":checked")
            var id = $(this).attr('id')
            if(isChecked === true){
                $('.checkAll').each(function(){
                    if($(this).attr('id') != id && $(this).is(":checked") == true){
                        $(this).prop("checked", false)
                    }
                })
                $('.status_check').prop('checked', false)
                if(id == 'PCheckAll'){
                    $('.status_check[value="1"]').prop('checked', true) 
                }else if(id == 'LCheckAll'){
                    $('.status_check[value="2"]').prop('checked', true) 
                }else if(id == 'ACheckAll'){
                    $('.status_check[value="3"]').prop('checked', true) 
                }
            }else{
                if(id == 'PCheckAll'){
                    $('.status_check[value="1"]').prop('checked', false) 
                }else if(id == 'LCheckAll'){
                    $('.status_check[value="2"]').prop('checked', false) 
                }else if(id == 'ACheckAll'){
                    $('.status_check[value="3"]').prop('checked', false) 
                }
            }
        })
        // Form submission handling
        $('#manage-attendance').submit(function(e){
            e.preventDefault()
            var isValid = true;
            var unmarkedStudents = [];

            // Check if any student's attendance is not marked
            $('#attendance-tbl .student-row').each(function(){
                var has_checks = $(this).find('.status_check:checked').length
                if(has_checks < 1){
                    var name = $(this).find('td').first().text() || "";
                    name = String(name).trim();
                    unmarkedStudents.push(name);
                    isValid = false;
                }
            })
            // Display error message if attendance is not marked for some students
            if (!isValid) {
                var message = "The following students' attendance is not yet marked:\n" + unmarkedStudents.join('\n');
                Swal.fire({
                    icon: 'error',
                    title: 'Unmarked Attendance',
                    text: message,
                });
                return false;
            }
            // Save attendance data via AJAX
            start_loader()
            $.ajax({
                url:'./ajax-api.php?action=save_attendance',
                method: 'POST',
                data: $(this).serialize(),
                dataType: 'JSON',
                error: (err) => {
                    console.error(err)
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred while saving the data. Please reload the page.'
                    });
                    end_loader();
                },
                success: function(resp){
                    if(resp?.status == "success"){
                        location.reload()
                    }else if(resp?.status == "error" && resp?.msg != ""){
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            html: resp.msg
                        });
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An error occurred while saving the data. Please reload the page.'
                        });
                    }
                    end_loader();
                }
            })
        })
    })
    // Function to check the count of checked items for each status
    function checkAll_count(){
        var statuses = {'PCheckAll': 1, 'LCheckAll': 2, 'ACheckAll': 3, 'HCheckAll': 4}
        $('.checkAll').each(function(){
            var id = $(this).attr('id')
            var checkedCount = $(`.status_check[value="${statuses[id]}"]:checked`).length
            var totalCount = $(`.status_check[value="${statuses[id]}"]`).length
            if(totalCount != checkedCount){
                $(this).prop('checked', false)
            }else{
                $(`#${id}`).prop('checked', true)
            }
        })
    }
</script>
