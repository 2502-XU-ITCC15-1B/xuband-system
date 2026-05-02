<?php
include("../../config/database.php");

$id = $_GET['id'];

mysqli_query($conn, "DELETE FROM announcements WHERE id=$id");

header("Location: list.php");
?>