<?php
include("../../includes/auth_check.php");
include("../../config/database.php");

$id = $_GET['id'];

$result = mysqli_query($conn, "SELECT * FROM users WHERE id=$id");
$user = mysqli_fetch_assoc($result);

if(isset($_POST['update'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $instrument = $_POST['instrument'];

    mysqli_query($conn, "UPDATE users SET 
        full_name='$name',
        email='$email',
        role='$role',
        instrument='$instrument'
        WHERE id=$id
    ");

    header("Location: list_user.php");
}

include("../../includes/header.php");
include("../../includes/navbar.php");
?>

<div class="d-flex">
<?php include("../../includes/sidebar.php"); ?>

<div class="p-4 w-100">
    <h2>Edit User</h2>

    <form method="POST">
        <input type="text" name="name" value="<?= $user['full_name']; ?>" class="form-control mb-2">
        <input type="email" name="email" value="<?= $user['email']; ?>" class="form-control mb-2">

        <select name="role" class="form-control mb-2">
            <option value="admin" <?= $user['role']=='admin'?'selected':''; ?>>Admin</option>
            <option value="staff" <?= $user['role']=='staff'?'selected':''; ?>>Staff</option>
            <option value="member" <?= $user['role']=='member'?'selected':''; ?>>Member</option>
        </select>

        <input type="text" name="instrument" value="<?= $user['instrument']; ?>" class="form-control mb-2">

        <button type="submit" name="update" class="btn btn-warning">Update</button>
    </form>
</div>
</div>

<?php include("../../includes/footer.php"); ?>