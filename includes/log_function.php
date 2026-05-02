<?php
function logAction($conn, $user_id, $action){
    mysqli_query($conn, "INSERT INTO logs (user_id, action) 
    VALUES ('$user_id', '$action')");
}
?>