<?php
require_once('../config.php');
$PAGE->title("Roles");
\layout\system::start();
?>

    <div class="card mb-2">
        <div class="card-body">
            <div class="card-title">
                <div class="row">
                    <div class="col-2">
                        <h3>All Roles</h3>
                    </div>
                    <div class="col-2">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#RoleModal">Create
                            Role
                        </button>
                    </div>
                </div>
            </div>
            <table id="role_list" class="table border-0 w-100">
                <thead>
                <tr>
                    <th>Role Name</th>
                    <th>Description</th>
                    <th>Level</th>
                    <th>Options</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

    <div class="modal fade" id="RoleModal">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title" id="role-modal-title">Create Role</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form id="register-update-form" role="form">
                                <div class="form-group">
                                    <input type="text" name="role_name" id="role_name" tabindex="1" class="form-control"
                                           placeholder="Role Name" value="">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="role_description" id="role_description" tabindex="1" class="form-control"
                                           placeholder="Role Description" value="">
                                </div>
                                <div class="form-group">
                                    <input type="number" name="role_level" id="role_level" tabindex="1" class="form-control"
                                           placeholder="Role Level" value="">
                                </div>
                                <input type="hidden" name="role_id" id="role_id" tabindex="1" value="">
                                <div class="form-group">
                                    <div class="row justify-content-center">
                                        <div class="col-sm-6">
                                            <input type="submit" name="register-submit" id="register-submit"
                                                   tabindex="4" class="form-control btn btn-primary"
                                                   value="Create Role">
                                        </div>
                                    </div>
                                </div>
                                <hr>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            var role_list_table = $("#role_list").DataTable({
                "serverside" : true,
                ajax: {
                    url: '<?php echo $C->wwwroot . "/system/api.php?action=get_roles"; ?>'
                },

                columns: [
                    {"data": "name"},
                    {"data": "description"},
                    {"data": "level"},
                    {
                        "data": "id",
                        "render": function (data, type, row, meta) {
                            return '<span class="m-2"><i class="ion ion-edit edit_btn" title="edit Role"></i></span>' +
                                '<span class="m-2"><i class="ion ion-android-delete delete_btn" title="delete Role"></i>' +
                                '<span class="m-2"><i class="ion ion-eye visible_btn" title="disable/enable Role"></i>';

                        }
                    }
                ]
            });

            $('#role_list').on('click', 'tbody .edit_btn', function () {
                $("#role-modal-title").text("Update Role");
                $("#register-submit").val("Update Role");
                $("#RoleModal").modal('toggle');
                var data_row = role_list_table.row($(this).closest('tr')).data();
                $("#role_name").val(data_row.name);
                $("#role_description").val(data_row.description);
                $("#role_level").val(data_row.level);
                $("#role_id").val(data_row.id);
            });

            $("#register-update-form").on('submit', function (e) {
                var post_url = '<?php echo $C->wwwroot . "/system/api.php?action=create_role"; ?>';
                e.preventDefault();
                $.ajax({
                    type: 'post',
                    data: $("#register-update-form").serialize(),
                    url: post_url,
                    success: function(data){
                        $("#RoleModal").modal('toggle');
                        $.notify({
                            // options
                            message: (JSON.parse(data)).data.toString()
                        },{
                            // settings
                            type: 'success'
                        });
                        role_list_table.ajax.reload();
                        $("#Roleid").val('');
                    }
                })
                ;
            });
        });
    </script>
<?
\layout\system::end();
?>