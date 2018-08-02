<?php
require_once('../config.php');
$PAGE->title("Permissions");
layout\system::start();
?>
    <div class="card mb-2">
        <div class="card-body">
            <div class="card-title">
                <div class="row">
                    <div class="col-2">
                        <h3>Permissions</h3>
                    </div>
                </div>
            </div>
            <table id="permissions_list" class="table border-0 w-100">
                <thead>
                <tr>
                    <th>Permission Name</th>
                    <th>Description</th>
                    <th>Actions</th>
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
                    url: '<?php echo $C->wwwroot . "/system/api.php?action=get_permissions"; ?>'
                },

                columns: [
                    {"data": "name"},
                    {"data": "description"},
                    {
                        "data": "id",
                        "render": function (data, type, row, meta) {
                            return '<span class="m-2"><i class="ion ion-edit edit_btn" title="edit user"></i></span>' +
                                '<span class="m-2"><i class="ion ion-android-delete delete_btn" title="delete user"></i>' +
                                '<span class="m-2"><i class="ion ion-eye visible_btn" title="disable/enable user"></i>';

                        }
                    }
                ]
            });
        })
    </script>

<?
\layout\system::end();