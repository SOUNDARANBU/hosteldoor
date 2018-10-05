<?php
require_once('../config.php');
$PAGE->title("Users");
\layout\system::start();
$total_users =  sizeof(\manager\user::get_users());
$active_users =  sizeof(\manager\user::get_users(true));
$inactive_users = $total_users - $active_users;
$deleted_users =  sizeof(\manager\user::get_deleted_users());

?>

    <div class="card-deck">
        <div class="card shadow mb-2">
            <div class="card-body text-center">
                        <span class="bg-info">
                            <h1><?php echo $total_users; ?></h1>
                        </span>
                <p class="card-text">Total Users</p>

            </div>
        </div>

        <div class="card shadow mb-2">
            <div class="card-body text-center">
                        <span class="bg-info">
                            <h1><?php echo $active_users; ?></h1>
                        </span>
                <p class="card-text">Active Users</p>
            </div>
        </div>

        <div class="card shadow  mb-2">
            <div class="card-body text-center">
                         <span class="bg-info">
                            <h1><? echo $inactive_users; ?></h1>
                        </span>
                <p class="card-text">Inactive Users</p>
            </div>
        </div>

        <div class="card shadow  mb-2">
            <div class="card-body text-center">
                         <span class="bg-info">
                            <h1><? echo $deleted_users; ?></h1>
                        </span>
                <p class="card-text">Deleted Users</p>
            </div>
        </div>
    </div>

    <div class="card mb-2">
        <div class="card-body">
            <div class="card-title">
                <div class="row">
                    <div class="col-2">
                        <h3>Users List</h3>
                    </div>
                    <div class="col-2">
                        <button type="button" class="btn btn-primary" id="new_user_btn">
                           <span><i class="ion ion-person-add"></i></span> &nbsp; New User
                        </button>
                    </div>
                </div>
            </div>
            <table id="users_list" class="table border-0 w-100">
                <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>Status</th>
                    <th>Options</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

    <div class="modal fade" id="myModal">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title" id="user-modal-title">Create User</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form id="register-update-form" role="form">
                                <div class="form-group">
                                    <input type="text" name="username" id="username" tabindex="1" class="form-control"
                                           placeholder="Username" value="">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="firstname" id="firstname" tabindex="1" class="form-control"
                                           placeholder="Firstname" value="">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="lastname" id="lastname" tabindex="1" class="form-control"
                                           placeholder="Lastname" value="">
                                </div>
                                <div class="form-group">
                                    <input type="email" name="email" id="email" tabindex="1" class="form-control"
                                           placeholder="Email Address" value="">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="mobile" id="mobile" tabindex="1" class="form-control"
                                           placeholder="Mobile Number" value="">
                                </div>
                                <div class="form-group">
                                    <input type="password" name="password" id="password" tabindex="2"
                                           class="form-control" placeholder="Password">
                                </div>
                                <div class="form-group">
                                    <input type="password" name="confirm-password" id="confirm-password" tabindex="2"
                                           class="form-control" placeholder="Confirm Password">
                                </div>
                                <input type="hidden" name="id" id="userid" tabindex="1" value="">
                                <div class="form-group">
                                    <div class="row justify-content-center">
                                        <div class="col-sm-6">
                                            <input type="submit" name="register-submit" id="register-submit"
                                                   tabindex="4" class="form-control btn btn-primary"
                                                   value="Create User">
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
            var users_list_table = $("#users_list").DataTable({
                "serverside" : true,
                ajax: {
                    url: '<?php echo $C->wwwroot . "/system/api.php?action=get_user"; ?>'
                },

                columns: [
                    {"data": "username"},
                    {"data": "email"},
                    {"data": "mobile"},
                    {
                        "data": "active",
                        "render": function (data, type, row, meta) {
                            if (data == 0)
                                return "<span class=\"badge-pill badge-danger\">Inactive</span>"
                            else
                                return "<span class=\"badge-pill badge-success\">Active</span>";
                        }
                    },
                    {
                        "data": "id",
                        "render": function (data, type, row, meta) {
                            return '<li class="dropdown list-unstyled">' +
                                '<a data-toggle="dropdown" href="#">' +
                                '<span class="badge-pill badge-info">Options</span>'+
                                '</a>'+
                                '<ul class="dropdown-menu user-options-dropdown">'+
                                '<li class="dropdown-item" id="edit"><a href="#">Edit</a></li>'+
                                '<li class="dropdown-item list-unstyled" id="manage_user"><a href="#">Manage</a></li>'+
                                '<li class="dropdown-item list-unstyled"><a href="#">Delete</a></li>'+
                            '</ul></li>';

                        }
                    }
                ]
            });

            $('#new_user_btn').on('click', function () {
                $('#register-update-form')[0].reset();
                $("#userid").val('');
                $("#user-modal-title").text("Create User");
                $("#register-submit").val("Create User");
                $("#myModal").modal('toggle');
            });

            $('#users_list').on('click', 'tbody #edit', function () {
                $("#user-modal-title").text("Update User");
                $("#register-submit").val("Update User");
                $("#myModal").modal('toggle');
                var data_row = users_list_table.row($(this).closest('tr')).data();
                $("#username").val(data_row.username);
                $("#firstname").val(data_row.firstname);
                $("#lastname").val(data_row.lastname);
                $("#email").val(data_row.email);
                $("#mobile").val(data_row.mobile);
                $("#userid").val(data_row.id);
            });

            $('#users_list').on('click', 'tbody #manage_user', function () {
                var data_row = users_list_table.row($(this).closest('tr')).data();
                var url = '<? echo "$C->wwwroot/system/manage_user.php?userid=";?>';
                window.location.href = url + data_row.id;
            });

            $("#register-update-form").on('submit', function (e) {
                var post_url = '<?php echo $C->wwwroot . "/system/api.php?action=create_user"; ?>';
                e.preventDefault();
                $.ajax({
                    type: 'post',
                    data: $("#register-update-form").serialize(),
                    url: post_url,
                    success: function(data){
                        $("#myModal").modal('toggle');
                        $('#register-update-form')[0].reset();
                        $.notify({
                            // options
                            message: (JSON.parse(data)).data.toString()
                        },{
                            // settings
                            type: 'success'
                        });
                        users_list_table.ajax.reload();
                        $("#userid").val('');
                    }
                })
                ;
            });
        });
    </script>
<?
\layout\system::end();
?>