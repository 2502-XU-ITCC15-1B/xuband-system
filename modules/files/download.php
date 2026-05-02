<?php
include("../../config/database.php");

$id = $_GET['id'];

$result = mysqli_query($conn, "SELECT * FROM files WHERE id=$id");
$file = mysqli_fetch_assoc($result);

$filepath = "../../uploads/".$file['file_name'];

if(file_exists($filepath)){
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.basename($filepath).'"');
    readfile($filepath);
}
?>