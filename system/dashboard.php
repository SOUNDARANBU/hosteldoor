<?php
require_once('../config.php');
\layout\system::start();
?>
    <div class="card">
        <div class="card-body">
            <div class="jumbotron bg-light">
                <h1 class="display-4">Hello, Awesome!</h1>
                <p class="lead">That's indeed a pleasure to welcome you to visit my mini framework that is driven by
                    PHP</p>
                <p class="lead">
                <p>Mini PHP framework inspired from Moodle.</p>
                <p>Anyone can make use of this mini framework to quickly build web applications in PHP</p>
                <p><strong>APIs</strong>:
                    All built in API or Manager Classes for</p>
                <ol>
                    <li>Database (currently support MySql/ MariaDB)</li>
                    <li>Email</li>
                    <li>Pages</li>
                    <li>Layout</li>
                    <li>Users</li>
                    <li>Permissions</li>
                    <li>Roles</li>
                </ol>
                <p><strong>Built In Resources</strong>:</p>
                <ol>
                    <li>Bootstrap v4.0</li>
                    <li>Jquery v3.3.1</li>
                    <li>Ionicons Font/Icons</li>
                </ol>
                <p><strong>Built In Features</strong>:</p>
                <ol>
                    <li>Create, Update, Delete Users</li>
                    <li>Create, Update, Delete Roles</li>
                    <li>Assign permissions to roles</li>
                    <li>Assign roles to users</li>
                    <li>Install or upgrdae DB tables</li>
                </ol>
                </p>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function () {
            $('.carousel').carousel();
        })
    </script>
<?
\layout\system::end();
?>