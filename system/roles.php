<?php
require_once('../config.php');
$PAGE->title("Roles");
\layout\system::start();
?>

    <div class="card mb-2">
        <div class="card-body">
            <div class="card-title">
                <div class="row">
                    <div class="col-md-3">
                        <h3>All Roles</h3>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#RoleModal">
                            Create
                            Role
                        </button>
                    </div>
                </div>
            </div>
            <div class="table-responsive-sm">
                <table id="role_list" class="table w-100">
                    <thead class="thead-light">
                    <tr>
                        <th></th>
                        <th>Role Name</th>
                        <th>Description</th>
                        <th>Level</th>
                        <th>Options</th>
                    </tr>
                    </thead>
                </table>
            </div>

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
                                    <input type="text" name="role_description" id="role_description" tabindex="1"
                                           class="form-control"
                                           placeholder="Role Description" value="">
                                </div>
                                <div class="form-group">
                                    <input type="number" name="role_level" id="role_level" tabindex="1"
                                           class="form-control"
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

    <div class="modal fade" id="RolePermissionModal">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Manage Role Permissions: <span id="role-permission-modal-title">Admin</span>
                    </h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#assigned-permissions">
                                Assigned Permissions
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#unassigned-permissions">
                                Unassigned Permissions
                            </a>
                        </li>
                    </ul>
                    <!--                        Tab panes-->
                    <div class="tab-content">
                        <div class="tab-pane container active" id="assigned-permissions">
                            <br>
                            <div class="table-responsive-sm">
                                <table id="assigned-permissions-table" class="table border-0 w-100">
                                    <thead class="thead-light">
                                    <tr>
                                        <th></th>
                                        <th>Permission</th>
                                        <th>Description</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane container fade" id="unassigned-permissions">
                            <br>
                            <div class="table-responsive-sm">
                                <table id="unassigned-permissions-table" class="table border-0 w-100">
                                    <thead class="thead-light">
                                    <tr>
                                        <th></th>
                                        <th>Permission</th>
                                        <th>Description</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            var role_list_table = $("#role_list").DataTable({
                dom: 'Bfrtip',
                "serverside": true,
                ajax: {
                    url: '<?php echo $C->wwwroot . "/system/api.php?action=get_roles"; ?>'
                },

                columns: [
                    {
                        "data": "id",
                        className: 'select-checkbox',
                        "render": function (data, type, row, meta) {
                            return '';
                        }
                    },
                    {"data": "name"},
                    {"data": "description"},
                    {"data": "level"},
                    {
                        "data": "id",
                        "render": function (data, type, row, meta) {
                            return '<span class="m-2"><i class="ion ion-edit edit_btn" title="edit Role"></i></span>' +
                                '<span class="m-2"><i class="ion ion-android-delete delete_btn" title="delete Role"></i>' +
                                '<span class="btn btn-primary btn-sm m-2 text-white role-permissions">Permissions</span>';

                        }
                    }
                ],
                select: {
                    style: 'multi',
                    selector: 'td:first-child'
                },
                buttons: [
                    {
                        className: 'btn btn-primary',
                        text: 'Select all',
                        action: function () {
                            role_list_table.rows().select();
                            console.dir(role_list_table.select.items());
                        }
                    },
                    {
                        className: 'btn btn-primary',
                        text: 'Select none',
                        action: function () {
                            role_list_table.rows().deselect();
                            console.dir(role_list_table.select.items());
                        }
                    }
                ]
            });
            role_list_table
                .on('select', function (e, dt, type, indexes) {
                    var rowData = role_list_table.rows(indexes).data().toArray();
                    console.dir(rowData[0].id);
                })
                .on('deselect', function (e, dt, type, indexes) {
                    var rowData = role_list_table.rows(indexes).data().toArray();
                    console.dir(rowData[0].id);
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

            $('#role_list').on('click', 'tbody .role-permissions', function () {
                $("#RolePermissionModal").modal('toggle');
                var data_row = role_list_table.row($(this).closest('tr')).data();
                $("#role-permission-modal-title").text(data_row.name);
                var role_id = data_row.id;
                $("#assigned-permissions-table").DataTable().destroy();
                var assigned_permissions_table = null;
                assigned_permissions_table = $("#assigned-permissions-table").DataTable({
                    dom: 'Bfrtip',
                    "serverside": true,
                    ajax: {
                        url: '<?php echo $C->wwwroot . "/system/api.php?action=get_permissions&type=assigned&role_id="; ?>' + role_id
                    },
                    columns: [
                        {
                            "data": "id",
                            className: 'select-checkbox',
                            "render": function (data, type, row, meta) {
                                return '';
                            }
                        },
                        {"data": "name"},
                        {"data": "description"},
                        {
                            "data": "id",
                            "render": function (data, type, row, meta) {
                                return '<span class="m-2"><i class="ion ion-close-circled edit_btn" title="edit Role"></i></span>';
                            }
                        }
                    ],
                    select: {
                        style: 'multi',
                        selector: 'td:first-child'
                    },
                    buttons: [
                        {
                            className: 'btn btn-primary',
                            text: 'Select all',
                            action: function () {
                                assigned_permissions_table.rows().select();
                                console.dir(assigned_permissions_table.rows().data().toArray());
                            }
                        },
                        {
                            className: 'btn btn-primary',
                            text: 'Select none',
                            action: function () {
                                assigned_permissions_table.rows().deselect();
                                console.dir(assigned_permissions_table.rows().data().toArray())
                            }
                        },
                        {
                            className: 'btn btn-danger',
                            text: 'Unassign',
                            action: function () {
                                var selected = $("#assigned-permissions-table").DataTable().rows({selected: true}).data().toArray();
                                console.dir(selected);
                                if (selected.length < 1) {
                                    $.notify({
                                        // options
                                        message: "No permission selected to unassign"
                                    }, {
                                        // settings
                                        type: 'danger',
                                        z_index: 1500
                                    });
                                } else {
                                    var selected_ids = '';
                                    selected.forEach(function (selecteditem) {
                                        console.dir(selecteditem.id);
                                        selected_ids += selecteditem.id + ',';
                                    });
                                    console.log(selected_ids);
                                    unassign_permissions(selected_ids, role_id);
                                }
                            }
                        }
                    ]
                });

                assigned_permissions_table
                    .on('select', function (e, dt, type, indexes) {
                        var rowData = role_list_table.rows(indexes).data().toArray();
                        console.dir(rowData[0].id);
                    })
                    .on('deselect', function (e, dt, type, indexes) {
                        var rowData = role_list_table.rows(indexes).data().toArray();
                        console.dir(rowData[0].id);
                    });

                $("#unassigned-permissions-table").DataTable().destroy();
                var unassigned_permissions_table = null;
                var unassigned_permissions_table = $("#unassigned-permissions-table").DataTable({
                    dom: 'Bfrtip',
                    "serverside": true,
                    ajax: {
                        url: '<?php echo $C->wwwroot . "/system/api.php?action=get_permissions&type=unassigned&role_id="; ?>' + role_id
                    },
                    columns: [
                        {
                            "data": "id",
                            className: 'select-checkbox',
                            "render": function (data, type, row, meta) {
                                return '';
                            }
                        },
                        {"data": "name"},
                        {"data": "description"},
                        {
                            "data": "id",
                            "render": function (data, type, row, meta) {
                                return '<span class="m-2"><i class="ion ion-close-circled edit_btn" title="edit Role"></i></span>';
                            }
                        }
                    ],
                    select: {
                        style: 'multi',
                        selector: 'td:first-child'
                    },
                    buttons: [
                        {
                            className: 'btn btn-primary',
                            text: 'Select all',
                            action: function () {
                                unassigned_permissions_table.rows().select();
                                console.dir(assigned_permissions_table.rows().data().toArray());
                            }
                        },
                        {
                            className: 'btn btn-primary',
                            text: 'Select none',
                            action: function () {
                                unassigned_permissions_table.rows().deselect();
                                console.dir(assigned_permissions_table.rows().data().toArray())
                            }
                        },
                        {
                            className: 'btn btn-success',
                            text: 'Assign',
                            action: function () {
                                var selected = $("#unassigned-permissions-table").DataTable().rows({selected: true}).data().toArray();
                                console.dir(selected);
                                if (selected.length < 1) {
                                    $.notify({
                                        // options
                                        message: "No permission selected to assign"
                                    }, {
                                        // settings
                                        type: 'danger',
                                        z_index: 1500
                                    });
                                } else {
                                    var selected_ids = '';
                                    selected.forEach(function (selecteditem) {
                                        console.dir(selecteditem.id);
                                        selected_ids += selecteditem.id + ',';
                                    });
                                    console.log(selected_ids);
                                    assign_permissions(selected_ids, role_id);
                                }
                            }
                        }
                    ]
                });

                unassigned_permissions_table
                    .on('select', function (e, dt, type, indexes) {
                        var rowData = role_list_table.rows(indexes).data().toArray();
                        console.dir(rowData[0].id);
                    })
                    .on('deselect', function (e, dt, type, indexes) {
                        var rowData = role_list_table.rows(indexes).data().toArray();
                        console.dir(rowData[0].id);
                    });
            });


            $("#register-update-form").on('submit', function (e) {
                var post_url = '<?php echo $C->wwwroot . "/system/api.php?action=create_role"; ?>';
                e.preventDefault();
                $.ajax({
                    type: 'post',
                    data: $("#register-update-form").serialize(),
                    url: post_url,
                    success: function (data) {
                        $("#RoleModal").modal('toggle');
                        $.notify({
                            // options
                            message: (JSON.parse(data)).data.toString()
                        }, {
                            // settings
                            type: 'success'
                        });
                        role_list_table.ajax.reload();
                        $("#Roleid").val('');
                    }
                })
                ;
            });

            $('a[href="#unassigned-permissions"]').on('click', function () {
                $("#unassigned-permissions-table").DataTable().ajax.reload();
            });

            $('a[href="#assigned-permissions"]').on('click', function () {
                $("#assigned-permissions-table").DataTable().ajax.reload();
            });

            function unassign_permissions(permission_ids, role_id) {
                var post_url = '<?php echo "$C->wwwroot/system/api.php?action=unassign_permissions"; ?>';
                $.ajax({
                    type: 'post',
                    data: {permission_ids: permission_ids.toString(), role_id: role_id},
                    url: post_url,
                    success: function (data) {
                        $("#assigned-permissions-table").DataTable().ajax.reload();
                        $.notify({
                            // options
                            message: "Permissions unassigned successfully"
                        }, {
                            // settings
                            type: 'success',
                            z_index: 1500
                        });
                        $("#Roleid").val('');
                    }
                });
            }

            function assign_permissions(permission_ids, role_id) {
                var post_url = '<?php echo "$C->wwwroot/system/api.php?action=assign_permissions"; ?>';
                $.ajax({
                    type: 'post',
                    data: {permission_ids: permission_ids.toString(), role_id: role_id},
                    url: post_url,
                    success: function (data) {
                        $("#unassigned-permissions-table").DataTable().ajax.reload();
                        $.notify({
                            // options
                            message: "Permissions assigned successfully"
                        }, {
                            // settings
                            type: 'success',
                            z_index: 1500
                        });
                        $("#Roleid").val('');
                    }
                });
            }

            function manage_permissions(table, action) {
                var selected = $("#unassigned-permissions-table").DataTable().rows({selected: true}).data().toArray();
                console.dir(selected);
                if (selected.length < 1) {
                    $.notify({
                        // options
                        message: "No permission selected to unassign"
                    }, {
                        // settings
                        type: 'danger',
                        z_index: 1500
                    });
                } else {
                    var selected_ids = '';
                    selected.forEach(function (selecteditem) {
                        console.dir(selecteditem.id);
                        selected_ids += selecteditem.id + ',';
                    });
                    console.log(selected_ids);
                    if (action == 'assign') {
                        unassign_permissions(selected_ids, role_id);
                    } else if (action == 'unassign') {
                        assign_permissions(selected_ids, role_id);
                    }
                }
            }
        });
    </script>
<?
\layout\system::end();
?>