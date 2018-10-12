<nav class="navbar navbar-expand-md navbar-dark bg-info shadow fixed-top">
    <div id="sidebar-toggle" class="btn"><span class="navbar-toggler-icon"></span></div>
    <div class="nav-brand-holder">
        <a class="navbar-brand" href="#">Application Name</a>
    </div>
    <ul class="nav navbar nav-right-items">
        <span class="text-white"> <?php global $USER; echo $USER->firstname; ?></span>
        <li class="dropdown">
            <a data-toggle="dropdown" href="#">
                <img class="user-picture img-rounded" src="<? echo "$C->wwwroot/theme/resources/image/user-default.png";?>" width="40px" height="40px"/>
            </a>
            <ul class="dropdown-menu user-options-dropdown">
                <li class="dropdown-item"><a class="" href="<? echo "$C->wwwroot/system/manage_user.php?userid=$USER->id" ?>">My Profile</a> </li>
                <li class="dropdown-item list-unstyled"><a href="#">Account Settings</a></li>
                <li class="dropdown-item list-unstyled"><a href="<? echo "$C->wwwroot/account/signout.php"; ?>">Log Out</a></li>
            </ul>
        </li>
    </ul>
</nav>