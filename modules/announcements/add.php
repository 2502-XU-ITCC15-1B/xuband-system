<?php
include("../../includes/auth_check.php");
include("../../config/database.php");

if($_SESSION['role'] == 'member'){
    header("Location: list.php");
    exit();
}

if(isset($_POST['save'])){
    $title = $_POST['title'];
    $content = $_POST['content'];

    mysqli_query($conn, "INSERT INTO announcements (title, content, posted_by)
    VALUES ('$title','$content','".$_SESSION['user_id']."')");

    include("../../includes/log_function.php");
    logAction($conn, $_SESSION['user_id'], "Posted announcement");

    header("Location: list.php");
}

include("../../includes/header.php");
include("../../includes/navbar.php");
?>

<div class="d-flex">
<?php include("../../includes/sidebar.php"); ?>

<div class="p-4 w-100">
    <h2>Add Announcement</h2>

    <form method="POST">
        <input type="text" name="title" class="form-control mb-2" placeholder="Title" required>

        <textarea name="content" class="form-control mb-2" placeholder="Content" required></textarea>

        <button type="submit" name="save" class="btn btn-success">Post</button>
    </form>
</div>
</div>

<?php include("../../includes/footer.php"); ?>