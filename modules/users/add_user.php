<?php
include("../../includes/auth_check.php");
include("../../config/database.php");

if($_SESSION['role'] != 'admin'){
    header("Location: ../../dashboard/dashboard.php");
    exit();
}

if(isset($_POST['save'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); $password = $_POST['password'];
    $role = $_POST['role'];
    $instrument = $_POST['instrument'];

    mysqli_query($conn, "INSERT INTO users (full_name,email,password,role,instrument) 
    VALUES ('$name','$email','$password','$role','$instrument')");

    include("../../includes/log_function.php");
    logAction($conn, $_SESSION['user_id'], "Added new user");

    header("Location: list_user.php");
}

include("../../includes/header.php");
include("../../includes/navbar.php");
?>

<div class="d-flex">
<?php include("../includes/sidebar.php"); ?>

<div class="p-4 w-100">
    <h2>Add User</h2>

    <form method="POST">
        <input type="text" name="name" class="form-control mb-2" placeholder="Full Name" required>
        <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
        <input type="text" name="password" class="form-control mb-2" placeholder="Password" required>

        <select name="role" class="form-control mb-2">
            <option value="admin">Admin</option>
            <option value="Officer">Staff</option>
            <option value="member">Band Member</option>
        </select>

        <input type="text" name="instrument" class="form-control mb-2" placeholder="Instrument">

        <button type="submit" name="save" class="btn btn-success">Save</button>
    </form>
</div>
</div>

<?php include("../../includes/footer.php"); ?>