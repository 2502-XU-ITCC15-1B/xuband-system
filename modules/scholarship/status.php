<?php
include("../../includes/auth_check.php");
include("../../config/database.php");

include("../../includes/header.php");
include("../../includes/navbar.php");
?>

<div class="d-flex">

<?php include("../../includes/sidebar.php"); ?>

<div class="p-4 w-100">
    <h2>Scholarship Status</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Status</th>
                <th>Remarks</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
        <?php
        // MAIN QUERY (EXCLUDE ADMIN)
        $query = "
        SELECT users.id as user_id, users.full_name,
               scholarships.status,
               scholarships.remarks
        FROM users
        LEFT JOIN scholarships ON users.id = scholarships.user_id
        WHERE users.role != 'admin'
        ";

        // If NOT admin → show only own record
        if($_SESSION['role'] != 'admin'){
            $user_id = $_SESSION['user_id'];
            $query .= " AND users.id = '$user_id'";
        }

        $result = mysqli_query($conn, $query);

        while($row = mysqli_fetch_assoc($result)){
        ?>
        <tr>
            <td><?= $row['full_name']; ?></td>

            <td>
                <?= $row['status'] ? ucfirst($row['status']) : 'Not set'; ?>
            </td>

            <td>
                <?= $row['remarks'] ? $row['remarks'] : '-'; ?>
            </td>

            <td>
                <?php if($_SESSION['role'] == 'admin'): ?>
                    <a href="update.php?user_id=<?= $row['user_id']; ?>" class="btn btn-warning btn-sm">Update</a>
                <?php else: ?>
                    -
                <?php endif; ?>
            </td>
        </tr>
        <?php } ?>
        </tbody>
    </table>

</div>
</div>

<?php include("../../includes/footer.php"); ?>