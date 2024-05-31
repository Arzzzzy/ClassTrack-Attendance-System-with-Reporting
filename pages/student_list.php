<div class="container d-flex justify-content-center">
    <!-- Page title -->
    <div class="page-title mb-3">Students List</div>
</div>

<?php 
// Retrieve the list of students using the list_student method from Actions class
$studentList = $actionClass->list_student();
?>

<div class="row justify-content-center">
    <div class="col-lg-10 col-md-12 col-sm-12 col-12">
        <div class="card shadow">
            <div class="card-header rounded-0">
                <div class="d-flex w-100 justify-content-end align-items-center">
                    <!-- Button to add a new student -->
                    <button class="btn btn-sm rounded-1 btn-outline-primary" type="button" id="add_student"><i class="fa-solid fa-user-plus"></i> Add Student</button>
                </div>
            </div><br>
            <div class="container-fluid mb-3">
                <!-- Search input for filtering students -->
                <input type="text" class="form-control" id="searchInput" placeholder="Search students or section...">
            </div>
            <div class="card-body rounded-0">
                <div class="container-fluid">
                    <div class="table-responsive">
                        <!-- Table to display the list of students -->
                        <table class="table table-bordered table-hovered table-stripped">
                            <colgroup>
                                <col width="10%">
                                <col width="30%">
                                <col width="40%">
                                <col width="20%">
                            </colgroup>
                            <thead class="bg-dark-subtle">
                                <tr class="bg-transparent">
                                    <th class="bg-transparent text-center">ID</th>
                                    <th class="bg-transparent text-center">Section Name - Subject</th>
                                    <th class="bg-transparent text-center">Student Name</th>
                                    <th class="bg-transparent text-center">Action</th>
                                </tr>
                            </thead>
                            <!-- Display Student list with action buttons for each student -->
                            <tbody id="studentTableBody">
                                <?php if(!empty($studentList) && is_array($studentList)): ?>
                                    <?php foreach($studentList as $row): ?>
                                        <tr class="studentRow">
                                            <td class="text-center px-2 py-1"><?= $row['id'] ?></td>
                                            <td class="px-2 py-1"><?= $row['class'] ?></td>
                                            <td class="px-2 py-1"><?= $row['name'] ?></td>
                                            <td class="text-center px-2 py-1">
                                                <div class="input-group input-group-sm justify-content-center">
                                                    <!-- Button to edit a student -->
                                                    <button class="btn btn-sm btn-outline-warning rounded-1 edit_student" type="button" data-id="<?= $row['id'] ?>" title="Edit"><i class="fas fa-edit"></i>Edit</button>
                                                    <!-- Button to delete a student -->
                                                    <button class="btn btn-sm btn-outline-danger rounded-1 delete_student ms-2" type="button" data-id="<?= $row['id'] ?>" title="Delete"><i class="fas fa-trash"></i>Delete</button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <!-- Display this row if the student list is empty -->
                                    <tr>
                                        <th class="text-center px-2 py-1" colspan="4">No data found.</th>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search Input -->
<script>
    $(document).ready(function(){
        // Filter students based on the search input
        $('#searchInput').on('keyup', function(){
            var searchText = $(this).val().toLowerCase();
            $('.studentRow').each(function(){
                var studentName = $(this).find('td:eq(2)').text().toLowerCase();
                var className = $(this).find('td:eq(1)').text().toLowerCase();
                if(studentName.includes(searchText) || className.includes(searchText)){
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.20/dist/sweetalert2.min.js"></script>
<!-- CRUD Operations using jQuery and SweetAlert for delete confirmation -->
<script>
    $(document).ready(function(){
        // Open modal to add a new student
        $('#add_student').click(function(e){
            e.preventDefault()
            open_modal('student_form.php', `Add New Student`)
        })

        // Open modal to edit a student
        $('.edit_student').click(function(e){
            e.preventDefault()
            var id = $(this)[0].dataset?.id || ''
            open_modal('student_form.php', `Update Student`, {id: id})
        })

        // Confirm and delete a student
        $('.delete_student').click(function(e){
            e.preventDefault()
            var id = $(this)[0].dataset?.id || ''
            Swal.fire({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this student!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "./ajax-api.php?action=delete_student",
                        method: "POST",
                        data: { id : id},
                        dataType: 'JSON',
                        error: (error) => {
                            console.error(error)
                            alert('An error occurred.')
                        },
                        success:function(resp){
                            if(resp?.status != '')
                                location.reload();
                        }
                    })
                }
            });
        })
    })
</script>
