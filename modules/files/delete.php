<?php
include("../../config/database.php");

$id = $_GET['id'];

$result = mysqli_query($conn, "SELECT * FROM files WHERE id=$id");
$file = mysqli_fetch_assoc($result);

unlink("../../uploads/".$file['file_name']);

mysqli_query($conn, "DELETE FROM files WHERE id=$id");

header("Location: list_files.php");
?>