<?php
include("../../includes/auth_check.php");
include("../../config/database.php");

if($_SESSION['role'] != 'admin'){
    header("Location: list.php");
    exit();
}

$id = $_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM announcements WHERE id=$id");
$data = mysqli_fetch_assoc($result);

if(isset($_POST['update'])){
    $title = $_POST['title'];
    $content = $_POST['content'];

    mysqli_query($conn, "UPDATE announcements SET 
        title='$title',
        content='$content'
        WHERE id=$id
    ");

    header("Location: list.php");
}

include("../../includes/header.php");
include("../../includes/navbar.php");
?>

<div class="d-flex">
<?php include("../../includes/sidebar.php"); ?>

<div class="p-4 w-100">
    <h2>Edit Announcement</h2>

    <form method="POST">
        <input type="text" name="title" value="<?= $data['title']; ?>" class="form-control mb-2">

        <textarea name="content" class="form-control mb-2"><?= $data['content']; ?></textarea>

        <button type="submit" name="update" class="btn btn-warning">Update</button>
    </form>
</div>
</div>

<?php include("../../includes/footer.php"); ?>