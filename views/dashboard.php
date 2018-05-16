<?php
require_once('../config.php');
global $C, $PAGE;
$PAGE->title('Dashboard');
$PAGE->header();
$PAGE->topnav();
?>

    <div class="body-content">
        <div id="sidebar" class="bg-white shadow">
            <ul class="nav nav-pills flex-column">
                <li class="nav-item">
                    <a class="nav-link bg-info active" href="#"><i class="ion-home h5"></i> &nbsp; Active</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-toggle="collapse" href="#submenu" role="button"
                       aria-expanded="false"><i class="ion-home h5"></i> &nbsp;Dropdown</a>
                    <ul class="collapse list-unstyled" id="submenu">
                        <li class=""><a class="dropdown-item" href="#">Action</a></li>
                        <li class=""><a class="dropdown-item" href="#">Action</a></li>
                        <li class=""><a class="dropdown-item" href="#">Action</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="ion-home h5"></i> &nbsp;Link</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" href="#"><i class="ion-home h5"></i> &nbsp; Disabled</a>
                </li>
            </ul>
        </div>

        <div class="body-main">
            <div class="card-deck">
                <div class="card shadow mb-2">
                    <div class="card-body text-center">
                        <span class="bg-info">
                            <h1>1</h1>
                        </span>
                        <p class="card-text">Course</p>

                    </div>
                </div>

                <div class="card shadow mb-2">
                    <div class="card-body text-center">
                        <span class="bg-info">
                            <h1>1</h1>
                        </span>
                        <p class="card-text">User</p>
                    </div>
                </div>

                <div class="card shadow  mb-2">
                    <div class="card-body text-center">
                        <p class="card-text">Some text inside the first card</p>
                    </div>
                </div>

                <div class="card shadow  mb-2">
                    <div class="card-body text-center">
                        <p class="card-text">Some text inside the first card</p>
                    </div>
                </div>

                <div class="card shadow  mb-2">
                    <div class="card-body text-center">
                        <p class="card-text">Some text inside the first card</p>
                    </div>
                </div>
            </div>
            <div class="card mb-2">
                <div class="card-body">
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ab accusantium adipisci aliquam atque,
                        deleniti distinctio dolore dolores eum laboriosam libero nobis odit praesentium quam quia quis
                        rem sit totam? Inventore.</p>
                    <table class="table border-0">
                        <thead>
                        <th>S.No</th>
                        <th>Name</th>
                        <th>Class</th>
                        </thead>
                        <tbody>
                        <tr>
                            <td>1</td>
                            <td>1</td>
                            <td>1</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card mb-2">
                <div class="card-body">
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ab accusantium adipisci aliquam atque,
                        deleniti distinctio dolore dolores eum laboriosam libero nobis odit praesentium quam quia quis
                        rem sit totam? Inventore.</p>
                    <table class="table border-0">
                        <thead>
                        <th>S.No</th>
                        <th>Name</th>
                        <th>Class</th>
                        </thead>
                        <tbody>
                        <tr>
                            <td>1</td>
                            <td>1</td>
                            <td>1</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card mb-2">
                <div class="card-body">
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ab accusantium adipisci aliquam atque,
                        deleniti distinctio dolore dolores eum laboriosam libero nobis odit praesentium quam quia quis
                        rem sit totam? Inventore.</p>
                    <table class="table border-0">
                        <thead>
                        <th>S.No</th>
                        <th>Name</th>
                        <th>Class</th>
                        </thead>
                        <tbody>
                        <tr>
                            <td>1</td>
                            <td>1</td>
                            <td>1</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card mb-2">
                <div class="card-body">
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ab accusantium adipisci aliquam atque,
                        deleniti distinctio dolore dolores eum laboriosam libero nobis odit praesentium quam quia quis
                        rem sit totam? Inventore.</p>
                    <table class="table border-0">
                        <thead>
                        <th>S.No</th>
                        <th>Name</th>
                        <th>Class</th>
                        </thead>
                        <tbody>
                        <tr>
                            <td>1</td>
                            <td>1</td>
                            <td>1</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>


<?php
$PAGE->footer();