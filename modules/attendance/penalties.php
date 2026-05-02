<?php
include("../../includes/auth_check.php");
include("../../config/database.php");

if($_SESSION['role'] != 'admin'){
    header("Location: list.php");
    exit();
}

if(isset($_POST['add'])){
    $user_id = $_POST['user_id'];
    $attendance_id = $_POST['attendance_id'];
    $amount = $_POST['amount'];
    $reason = $_POST['reason'];

    mysqli_query($conn, "INSERT INTO penalties (user_id, attendance_id, amount, reason)
    VALUES ('$user_id','$attendance_id','$amount','$reason')");

    header("Location: penalties.php");
}

include("../../includes/header.php");
include("../../includes/navbar.php");
?>

<div class="d-flex">
<?php include("../../includes/sidebar.php"); ?>

<div class="p-4 w-100">
    <h2>Penalties</h2>

    <form method="POST" class="mb-4">
        <select name="user_id" class="form-control mb-2">
            <?php
            $users = mysqli_query($conn, "SELECT * FROM users");
            while($u = mysqli_fetch_assoc($users)){
                echo "<option value='{$u['id']}'>{$u['full_name']}</option>";
            }
            ?>
        </select>

        <select name="attendance_id" class="form-control mb-2">
            <?php
            $att = mysqli_query($conn, "SELECT * FROM attendance");
            while($a = mysqli_fetch_assoc($att)){
                echo "<option value='{$a['id']}'>ID {$a['id']} - {$a['date']}</option>";
            }
            ?>
        </select>

        <input type="number" name="amount" class="form-control mb-2" placeholder="Amount">

        <input type="text" name="reason" class="form-control mb-2" placeholder="Reason">

        <button type="submit" name="add" class="btn btn-danger">Add Penalty</button>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>User</th>
                <th>Amount</th>
                <th>Reason</th>
                <th>Date</th>
            </tr>
        </thead>

        <tbody>
        <?php
        $query = "
            SELECT penalties.*, users.full_name 
            FROM penalties
            JOIN users ON penalties.user_id = users.id
            ORDER BY penalties.created_at DESC
        ";

        $result = mysqli_query($conn, $query);

        while($row = mysqli_fetch_assoc($result)){
        ?>
            <tr>
                <td><?= $row['full_name']; ?></td>
                <td><?= $row['amount']; ?></td>
                <td><?= $row['reason']; ?></td>
                <td><?= $row['created_at']; ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

</div>
</div>

<?php include("../../includes/footer.php"); ?>