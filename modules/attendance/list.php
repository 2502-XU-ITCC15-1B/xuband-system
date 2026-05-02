<?php
include("../../includes/auth_check.php");
include("../../config/database.php");

include("../../includes/header.php");
include("../../includes/navbar.php");
?>

<div class="d-flex">

<?php include("../../includes/sidebar.php"); ?>

<div class="p-4 w-100">
    <h2>Attendance Records</h2>

    <a href="mark.php" class="btn btn-primary mb-3">Mark Attendance</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Date</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>
        <?php
        $query = "
            SELECT attendance.*, users.full_name 
            FROM attendance
            JOIN users ON attendance.user_id = users.id
            ORDER BY date DESC
        ";

        $result = mysqli_query($conn, $query);

        while($row = mysqli_fetch_assoc($result)){
        ?>
            <tr>
                <td><?= $row['full_name']; ?></td>
                <td><?= $row['date']; ?></td>
                <td><?= $row['status']; ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

</div>
</div>

<?php include("../../includes/footer.php"); ?>