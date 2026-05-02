<?php
include("../../includes/auth_check.php");
include("../../config/database.php");

$user_id = $_SESSION['user_id'];

$query = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'");
$user = mysqli_fetch_assoc($query);

include("../../includes/header.php");
include("../../includes/navbar.php");
?>

<div class="d-flex">

<?php include("../../includes/sidebar.php"); ?>

<div class="p-4 w-100">
    <h2>My Profile</h2>

    <div class="card p-4" style="max-width: 500px;">
        <p><strong>Name:</strong> <?= $user['full_name']; ?></p>
        <p><strong>Email:</strong> <?= $user['email']; ?></p>
        <p><strong>Role:</strong> <?= ucfirst($user['role']); ?></p>
        <p><strong>Instrument:</strong> <?= $user['instrument']; ?></p>
        <p><strong>Contact:</strong> <?= $user['contact_number']; ?></p>

        <a href="edit.php" class="btn btn-primary">Edit Profile</a>
    </div>
</div>

</div>

<?php include("../../includes/footer.php"); ?>