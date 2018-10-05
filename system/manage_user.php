<?php
require_once('../config.php');
$PAGE->title("Users");
\layout\system::start();

$userid = \manager\page::optional_param('userid');
$user = \manager\user::get_user_by_id($userid);

?>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">User</a></li>
            <li class="breadcrumb-item active" aria-current="page">Profile</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col col-md-4 ">
            <div class="text-center">
                <img class="img-rounded" src="../theme/resources/image/user-default.png" width="200px" height="200px"/>
            </div>
        </div>
        <div class="col col-md-6">
            <table class="table table-responsive-md">
                <tr>
                    <th>Username</th>
                    <td><? echo $user->username ?> </td>
                </tr>
                <tr>
                    <th>Full Name</th>
                    <td><? echo $user->firstname ?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><? echo $user->email ?></td>
                </tr>
                <tr>
                    <th>Mobile</th>
                    <td><? echo $user->mobile ?></td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td><? echo user_renderer::render_status($user->active) ?></td>
                </tr>
                <tr>
                    <th>Last Signin</th>
                    <td>Jan 11 2018 9:00 AM</td>
                </tr>
            </table>
        </div>
    </div>

    <ul class="nav nav-tabs mb-3" id="pills-tab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab"
               aria-controls="pills-home" aria-selected="true">Activities</a>
        </li>
        <?
        if(\manager\permisssion::user_has_permission('role_view', $USER->id)){
            echo <<<HTML
        <li class="nav-item">
            <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab"
               aria-controls="pills-profile" aria-selected="false">Roles</a>
        </li>
HTML;
        }
        ?>

        <li class="nav-item">
            <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab"
               aria-controls="pills-contact" aria-selected="false">Permissions</a>
        </li>
    </ul>
    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
            <div class="card mb-2">
                <div class="card-body">
                    <div class="m-2">No user activities found</div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
            <div class="card mb-2">
                <div class="card-body">
                    <div class="card-title">
                        <div class="row">
                            <div class="m-2">
                                <h4>Manage Roles</h4>
                                <small>Here are the list of roles assigned to the user. Permissions associated with the roles will be allowed to this user.</small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col col-md-5">
                            <h6>Assigned Roles</h6>
                            <hr>
                            <ul class="list-group list-unstyled" id="assigned_roles_list">
                                <li class="list-group-item">
                                    Loading...
                                </li>

                            </ul>
                        </div>
                        <div class="col col-md-5">
                            <h6>Assign New Role</h6>
                            <hr>
                            <form id="role_assign_form">
                                <div class="form-group">
                                    <div class="">
                                        <select name="role_id" class="form-control" id="role_assign_select">
                                            <option value="0">Select Roles</option>
                                        </select>
                                    </div>
                                </div>
                                <input type="hidden" name="user_id" value="<? echo $userid ?>"/>
                                <button type="submit" class="btn btn-primary btn-sm">Assign Role</button>
                            </form>

                        </div>

                    </div>

                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
            <div class="card mb-2">
                <div class="card-body">
                    <div class="card-title">
                        <div class="row">
                            <div class="col-12">
                                <h3>Permissions</h3>
                                <small>List of all the permissions allowed to the user.<br>
                                    Note: Permissions can only be assigned to the role. It cannot be assigned straight to the user</small>
                            </div>
                        </div>
                    </div>
                    <table id="permissions_list" class="table border-0 w-100">
                        <thead>
                        <tr>
                            <th>Permission Name</th>
                            <th>Description</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>

            <script type="text/javascript">
                $(document).ready(function () {
                    var permissions_list_table = $("#permissions_list").DataTable({
                        "serverside": true,
                        ajax: {
                            url: '<?php echo $C->wwwroot . "/system/api.php?action=get_user_permissions&userid=" . $userid; ?>'
                        },

                        columns: [
                            {"data": "name"},
                            {"data": "description"},
                            {
                                "data": "",
                                "render": function (data, type, row, meta) {
                                    return "<span class=\"badge badge-pill badge-success\">Allowed</span>";
                                }
                            },
                        ]
                    });
                    render_role_assign_select_options();
                    render_assigned_roles();
                    $("#role_assign_form").on('submit', function (e) {
                        var post_url = '<?php echo $C->wwwroot . "/system/api.php?action=assign_role"; ?>';
                        e.preventDefault();
                        if (get_selected_role_id() < 1) {
                            $.notify({
                                // options
                                message: 'No role selected to assign.'
                            }, {
                                // settings
                                type: 'danger'
                            });
                        } else {
                            $.ajax({
                                type: 'post',
                                data: $("#role_assign_form").serialize(),
                                url: post_url,
                                success: function (data) {
                                    $.notify({
                                        // options
                                        message: 'Role assigned successfully'
                                    }, {
                                        // settings
                                        type: 'success'
                                    });
                                    render_assigned_roles();
                                    render_role_assign_select_options();
                                    refresh_permissions_list();
                                }
                            });
                        }
                    });

                    $("#assigned_roles_list").on('click','.remove-role', function (e){
                       var role_id = $(this).attr('id');
                       var post_url = '<?php echo $C->wwwroot . "/system/api.php?action=remove_user_role"; ?>';
                       if(role_id > 0){
                           $.ajax({
                               type: 'post',
                               data: {'role_id': role_id, 'user_id': <? echo $userid; ?>},
                               url: post_url,
                               success: function (data) {
                                   var result = JSON.parse(data);
                                   console.dir(result);
                                   $.notify({
                                       // options
                                       message: result.data
                                   });
                               }
                           });
                           render_assigned_roles();
                           render_role_assign_select_options();
                           refresh_permissions_list();
                       }
                    });
                });

                function render_role_assign_select_options() {
                    $.ajax({
                        url: '<? echo "$C->wwwroot/system/api.php"; ?>',
                        type: 'post',
                        data: {action: 'get_user_roles', userid: '<? echo $userid ?>', type: 'unassigned'},
                        success: function (data) {
                            var roles = JSON.parse(data).data;
                            console.dir(roles);
                            var options = '<option value="0">Select Role</option>';
                            roles.forEach(function (role) {
                                options += '<option value="' + role.id + '">' + role.name + '</option>';
                            });
                            console.dir(options);
                            $("#role_assign_select").html(options);
                        }
                    });
                }

                function render_assigned_roles() {
                    $.ajax({
                        url: '<? echo "$C->wwwroot/system/api.php"; ?>',
                        type: 'post',
                        data: {action: 'get_user_roles', userid: '<? echo $userid ?>', type: 'assigned'},
                        success: function (data) {
                            var roles = JSON.parse(data).data;
                            var assigned_roles_html = '';
                            roles.forEach(function (role) {
                                assigned_roles_html += '<li class="list-group-item">\n' +
                                    '                                    <span class="mr-2"><i class="ion ion-checkmark-circled text-success"></i></span>\n' +
                                    '                                    <span class="float-right position-relative"><a class="remove-role" id="'+ role.id +'" href="#">Remove</a></span>\n' +
                                    '                                    <span><bold>' + role.name + ' </bold></span>\n' +
                                    '                                    <br>\n' +
                                    '                                    <span><small>' + role.description + '</small></span>\n' +
                                    '                      </li>';
                            });
                            if (assigned_roles_html.length < 1) {
                                assigned_roles_html = '<li class="list-group-item">\n' +
                                    '                  No roles assigned' +
                                    '                  </li>';
                            }
                            $("#assigned_roles_list").html(assigned_roles_html);
                        }
                    });
                }
                function refresh_permissions_list(){
                    $("#permissions_list").DataTable().ajax.reload();
                }

                function get_selected_role_id() {
                    return $("#role_assign_select").val();
                }
            </script>
        </div>
    </div>


<?
\layout\system::end();
?>