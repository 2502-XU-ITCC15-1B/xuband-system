<?php
include("../../includes/auth_check.php");
include("../../config/database.php");

// ONLY ADMIN CAN ACCESS
if($_SESSION['role'] != 'admin'){
    header("Location: status.php");
    exit();
}

$user_id = $_GET['user_id'];

// GET USER INFO
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'"));

// CHECK IF SCHOLARSHIP EXISTS
$check = mysqli_query($conn, "SELECT * FROM scholarships WHERE user_id='$user_id'");
$data = mysqli_fetch_assoc($check);

// SAVE (INSERT OR UPDATE)
if(isset($_POST['update'])){
    $status = $_POST['status'];
    $remarks = $_POST['remarks'];

    if($data){
        mysqli_query($conn, "UPDATE scholarships SET 
            status='$status',
            remarks='$remarks'
            WHERE user_id='$user_id'
        ");
    } else {
        mysqli_query($conn, "INSERT INTO scholarships (user_id, status, remarks)
        VALUES ('$user_id','$status','$remarks')");
    }

    header("Location: status.php");
}

include("../../includes/header.php");
include("../../includes/navbar.php");
?>

<div class="d-flex">

<?php include("../../includes/sidebar.php"); ?>

<div class="p-4 w-100">
    <h2>Update Scholarship</h2>

    <p><strong>Member:</strong> <?= $user['full_name']; ?></p>

    <form method="POST">

        <label>Status</label>
        <select name="status" class="form-control mb-2">
            <option value="full" <?= ($data && $data['status']=='full')?'selected':''; ?>>Full</option>
            <option value="half" <?= ($data && $data['status']=='half')?'selected':''; ?>>Half</option>
            <option value="none" <?= ($data && $data['status']=='none')?'selected':''; ?>>Not Scholar</option>
        </select>

        <label>Remarks</label>
        <textarea name="remarks" class="form-control mb-2"><?= $data['remarks'] ?? ''; ?></textarea>

        <button type="submit" name="update" class="btn btn-success">Save</button>
        <a href="status.php" class="btn btn-secondary">Back</a>

    </form>

</div>
</div>

<?php include("../../includes/footer.php"); ?>