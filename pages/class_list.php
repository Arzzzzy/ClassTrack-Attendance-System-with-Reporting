<div class="container d-flex justify-content-center">
    <!-- Page title for Classes List -->
    <div class="page-title mb-3">Classes List</div>
</div>

<?php 
// Retrieve the list of classes using the list_class method from Actions class
$classList = $actionClass->list_class();
?>

<div class="row justify-content-center">
    <div class="col-lg-10 col-md-12 col-sm-12 col-12">
        <div class="card shadow">
            <div class="card-header rounded-0">
                <div class="d-flex w-100 justify-content-end align-items-center">
                    <!-- Button to add a new class -->
                    <button class="btn btn-sm rounded-2 btn-outline-primary" type="button" id="add_class"><i class="fa-solid fa-user-plus"></i> Add Class</button>
                </div>
            </div>
            <div class="card-body rounded-0">
                <div class="container-fluid">
                    <div class="table-responsive">
                        <!-- Table to display the list of classes -->
                        <table class="table table-bordered table-hovered table-stripped">
                            <colgroup>
                                <col width="8%">
                                <col width="70%">
                                <col width="22%">
                            </colgroup>
                            <thead class="bg-dark-subtle">
                                <tr class="bg-transparent">
                                    <th class="bg-transparent text-center">ID</th>
                                    <th class="bg-transparent text-center">Section Name - Subject</th>
                                    <th class="bg-transparent text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody><!-- Display ClassList with action buttons to edit and delete -->
                                <?php if(!empty($classList) && is_array($classList)): ?>
                                    <?php foreach($classList as $row): ?>
                                        <tr>
                                            <td class="text-center px-2 py-1"><?= $row['id'] ?></td>
                                            <td class="px-2 py-1"><?= $row['name'] ?></td>
                                            <td class="text-center px-2 py-1">
                                                <div class="input-group input-group-sm justify-content-center">
                                                    <!-- Button to edit a class -->
                                                    <button class="btn btn-sm btn-outline-warning rounded-1 edit_class" type="button" data-id="<?= $row['id'] ?>" title="Edit"><i class="fas fa-edit"></i>Edit</button>
                                                    <!-- Button to delete a class -->
                                                    <button class="btn btn-sm btn-outline-danger rounded-1 delete_class ms-3" type="button" data-id="<?= $row['id'] ?>" title="Delete"><i class="fas fa-trash"></i>Delete</button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <!-- Display this row if the class list is empty -->
                                    <tr>
                                        <th class="text-center px-2 py-1" colspan="3">No data found.</th>
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.20/dist/sweetalert2.min.js"></script>
<!-- CRUD Operations using jQuery and SweetAlert for delete confirmation -->
<script>
    $(document).ready(function(){
        // Open modal to add a new class
        $('#add_class').click(function(e){
            e.preventDefault()
            open_modal('class_form.php', "Add Class")
        })

        // Open modal to edit a class
        $('.edit_class').click(function(e){
            e.preventDefault()
            var id = $(this)[0].dataset?.id || ''
            open_modal('class_form.php', "Update Class", {id: id})
        })

        // Confirm and delete a class
        $('.delete_class').click(function(e){
            e.preventDefault()
            var id = $(this)[0].dataset?.id || ''
            Swal.fire({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this class!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "./ajax-api.php?action=delete_class",
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
