<?php
include("../../includes/auth_check.php");
include("../../config/database.php");

include("../../includes/header.php");
include("../../includes/navbar.php");
?>

<div class="d-flex">

<?php include("../../includes/sidebar.php"); ?>

<div class="p-4 w-100">
    <h2>Music Sheets</h2>

    <a href="upload.php" class="btn btn-primary mb-3">Upload File</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Title</th>
                <th>Category</th>
                <th>File</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
        <?php
        $result = mysqli_query($conn, "SELECT * FROM files");

        while($row = mysqli_fetch_assoc($result)){
        ?>
            <tr>
                <td><?= $row['title']; ?></td>
                <td><?= $row['category']; ?></td>
                <td><?= $row['file_name']; ?></td>
                <td>
                    <a href="download.php?id=<?= $row['id']; ?>" class="btn btn-success btn-sm">Download</a>

                    <?php if($_SESSION['role'] == 'admin'): ?>
                        <a href="delete.php?id=<?= $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this file?')">Delete</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

</div>
</div>

<?php include("../../includes/footer.php"); ?>