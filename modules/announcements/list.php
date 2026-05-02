<?php
include("../../includes/auth_check.php");
include("../../config/database.php");

include("../../includes/header.php");
include("../../includes/navbar.php");
?>

<div class="d-flex">

<?php include("../../includes/sidebar.php"); ?>

<div class="p-4 w-100">
    <h2>Announcements</h2>

    <?php if($_SESSION['role'] != 'member'): ?>
        <a href="add.php" class="btn btn-primary mb-3">Add Announcement</a>
    <?php endif; ?>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Title</th>
                <th>Content</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
        <?php
        $result = mysqli_query($conn, "SELECT * FROM announcements ORDER BY created_at DESC");

        while($row = mysqli_fetch_assoc($result)){
        ?>
            <tr>
                <td><?= $row['title']; ?></td>
                <td><?= $row['content']; ?></td>
                <td><?= $row['created_at']; ?></td>
                <td>
                    <?php if($_SESSION['role'] == 'admin'): ?>
                        <a href="edit.php?id=<?= $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="delete.php?id=<?= $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this announcement?')">Delete</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

</div>
</div>

<?php include("../../includes/footer.php"); ?>