<?php
include("../../includes/auth_check.php");
include("../../config/database.php");

if($_SESSION['role'] != 'admin'){
    header("Location: ../../dashboard/dashboard.php");
    exit();
}

include("../../includes/header.php");
include("../../includes/navbar.php");
?>

<div class="d-flex">

<?php include("../../includes/sidebar.php"); ?>

<div class="p-4 w-100">
    <h2>User Management</h2>

    <a href="add_user.php" class="btn btn-primary mb-3">Add User</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Instrument</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
        <?php
        $result = mysqli_query($conn, "SELECT * FROM users");

        while($row = mysqli_fetch_assoc($result)){
        ?>
            <tr>
                <td><?= $row['id']; ?></td>
                <td><?= $row['full_name']; ?></td>
                <td><?= $row['email']; ?></td>
                <td><?= $row['role']; ?></td>
                <td><?= $row['instrument']; ?></td>
                <td>
                    <a href="edit_user.php?id=<?= $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="delete_user.php?id=<?= $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this user?')">Delete</a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

</div>
</div>

<?php include("../../includes/footer.php"); ?>