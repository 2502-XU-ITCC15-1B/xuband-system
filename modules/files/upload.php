<?php
include("../../includes/auth_check.php");
include("../../config/database.php");

if(isset($_POST['upload'])){

    $title = $_POST['title'];
    $category = $_POST['category'];

    $file = $_FILES['file']['name'];
    $temp = $_FILES['file']['tmp_name'];

    move_uploaded_file($temp, "../../uploads/".$file);

    mysqli_query($conn, "INSERT INTO files (title,file_name,category,uploaded_by)
    VALUES ('$title','$file','$category','".$_SESSION['user_id']."')");

    include("../../includes/log_function.php");
    logAction($conn, $_SESSION['user_id'], "Uploaded a file");

    header("Location: list_files.php");
}

include("../../includes/header.php");
include("../../includes/navbar.php");
?>

<div class="d-flex">
<?php include("../../includes/sidebar.php"); ?>

<div class="p-4 w-100">
    <h2>Upload Music Sheet</h2>

    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="title" class="form-control mb-2" placeholder="Title" required>

        <input type="text" name="category" class="form-control mb-2" placeholder="Category">

        <input type="file" name="file" class="form-control mb-2" required>

        <button type="submit" name="upload" class="btn btn-primary">Upload</button>
    </form>
</div>
</div>

<?php include("../../includes/footer.php"); ?>