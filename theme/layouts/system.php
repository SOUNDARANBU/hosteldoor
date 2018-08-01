<?php
namespace layout;
class system{
    public static function start(){
        global $C, $PAGE, $USER;
        $USER->require_signin();
        $PAGE->header();
        $PAGE->topnav();
        echo <<<HTML
                <div class="body-content">
                    <div id="sidebar" class="bg-white shadow">
                        <ul class="nav nav-pills flex-column">
                            <li class="nav-item">
                                <a class="nav-link bg-info active" href="dashboard.php"><i class="ion-home h5"></i> &nbsp; Dashboard</a>
                            </li>
            
                            <li class="nav-item">
                                <a class="nav-link" href="users.php"><i class="ion-person h5"></i> &nbsp; Users</a>
                            </li>
            
                            <li class="nav-item">
                                <a class="nav-link" href="roles.php"><i class="ion-person-stalker h5"></i> &nbsp; Roles</a>
                            </li>
            
                            <li class="nav-item">
                                <a class="nav-link" href="permissions.php"><i class="ion-android-checkbox h5"></i> &nbsp; Permissions</a>
                            </li>
            
                            <li class="nav-item">
                                <a class="nav-link" href="settings.php"><i class="ion-settings h5"></i> &nbsp; Settings</a>
                            </li>
                            <!---->
                            <!--                <li class="nav-item dropdown">-->
                            <!--                    <a class="nav-link dropdown-toggle" data-toggle="collapse" href="#submenu" role="button"-->
                            <!--                       aria-expanded="false"><i class="ion-home h5"></i> &nbsp;Administration</a>-->
                            <!--                    <ul class="collapse list-unstyled" id="submenu">-->
                            <!--                        <li class=""><a class="dropdown-item" href="#">Action</a></li>-->
                            <!--                        <li class=""><a class="dropdown-item" href="#">Action</a></li>-->
                            <!--                        <li class=""><a class="dropdown-item" href="#">Action</a></li>-->
                            <!--                    </ul>-->
                            <!--                </li>-->
                            <!--                <li class="nav-item">-->
                            <!--                    <a class="nav-link" href="#"><i class="ion-home h5"></i> &nbsp;Link</a>-->
                            <!--                </li>-->
                            <!--                <li class="nav-item">-->
                            <!--                    <a class="nav-link disabled" href="#"><i class="ion-home h5"></i> &nbsp; Disabled</a>-->
                            <!--                </li>-->
                        </ul>
                    </div>
                    <div class="body-main">
HTML;
    }

    public static function end(){
        global $PAGE;
        echo "</div>";
        $PAGE->footer();
    }
}
?>
