<?php
include("../../includes/auth_check.php");
include("../../config/database.php");

$user_id = $_SESSION['user_id'];

// GET CURRENT DATA
$query = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'");
$user = mysqli_fetch_assoc($query);

// UPDATE
if(isset($_POST['update'])){
    $name = $_POST['full_name'];
    $email = $_POST['email'];
    $instrument = $_POST['instrument'];
    $contact = $_POST['contact_number'];

    mysqli_query($conn, "UPDATE users SET 
        full_name='$name',
        email='$email',
        instrument='$instrument',
        contact_number='$contact'
        WHERE id='$user_id'
    ");

    header("Location: profile.php");
    exit();
}

include("../../includes/header.php");
include("../../includes/navbar.php");
?>

<div class="d-flex">

<?php include("../../includes/sidebar.php"); ?>

<div class="p-4 w-100">
    <h2>Edit Profile</h2>

    <form method="POST" style="max-width: 500px;">

        <label>Full Name</label>
        <input type="text" name="full_name" class="form-control mb-2"
               value="<?= $user['full_name']; ?>" required>

        <label>Email</label>
        <input type="email" name="email" class="form-control mb-2"
               value="<?= $user['email']; ?>" required>

        <label>Instrument</label>
        <input type="text" name="instrument" class="form-control mb-2"
               value="<?= $user['instrument']; ?>">

        <label>Contact Number</label>
        <input type="text" name="contact_number" class="form-control mb-2"
               value="<?= $user['contact_number']; ?>">

        <button type="submit" name="update" class="btn btn-success">Save Changes</button>
        <a href="profile.php" class="btn btn-secondary">Cancel</a>

    </form>
</div>

</div>

<?php include("../../includes/footer.php"); ?>