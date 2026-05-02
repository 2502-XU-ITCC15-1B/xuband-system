<?php
include("../includes/auth_check.php");
include("../config/database.php");
include("../includes/header.php");
include("../includes/navbar.php");
?>

<div class="d-flex">
    
    <?php include("../includes/sidebar.php"); ?>

    <div class="p-4 w-100">
        <h2>Dashboard</h2>

        <div class="row mt-4">

            <?php
            $users = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users"));
            $files = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM files"));
            $announcements = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM announcements"));
            $attendance = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM attendance"));
            ?>

            <div class="col-md-3">
                <div class="card text-white bg-primary p-3">
                    <h5>Total Users</h5>
                    <h3><?php echo $users; ?></h3>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-white bg-success p-3">
                    <h5>Music Sheets</h5>
                    <h3><?php echo $files; ?></h3>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-white bg-warning p-3">
                    <h5>Announcements</h5>
                    <h3><?php echo $announcements; ?></h3>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-white bg-danger p-3">
                    <h5>Attendance Records</h5>
                    <h3><?php echo $attendance; ?></h3>
                </div>
            </div>

        </div>
    </div>

</div>

<?php include("../includes/footer.php"); ?>