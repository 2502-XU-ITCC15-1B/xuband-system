<?php
include("../../includes/auth_check.php");
include("../../config/database.php");

if($_SESSION['role'] == 'member'){
    header("Location: list.php");
    exit();
}

if(isset($_POST['save'])){
    $user_id = $_POST['user_id'];
    $date = $_POST['date'];
    $status = $_POST['status'];

    mysqli_query($conn, "INSERT INTO attendance (user_id, date, status)
    VALUES ('$user_id','$date','$status')");

    header("Location: list.php");
}

include("../../includes/header.php");
include("../../includes/navbar.php");
?>

<div class="d-flex">
<?php include("../../includes/sidebar.php"); ?>

<div class="p-4 w-100">
    <h2>Mark Attendance</h2>

    <form method="POST">

        <select name="user_id" class="form-control mb-2">
            <?php
            $users = mysqli_query($conn, "SELECT * FROM users");
            while($u = mysqli_fetch_assoc($users)){
                echo "<option value='{$u['id']}'>{$u['full_name']}</option>";
            }
            ?>
        </select>

        <input type="date" name="date" class="form-control mb-2" required>

        <select name="status" class="form-control mb-2">
            <option value="present">Present</option>
            <option value="absent">Absent</option>
            <option value="late">Late</option>
        </select>

        <button type="submit" name="save" class="btn btn-success">Save</button>
    </form>
</div>
</div>

<?php include("../../includes/footer.php"); ?>