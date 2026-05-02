<?php
include("../../includes/auth_check.php");
include("../../config/database.php");

if($_SESSION['role'] != 'admin'){
    header("Location: ../../dashboard/dashboard.php");
    exit();
}

include("../../includes/header.php");
include("../../includes/navbar.php");

// Get selected user ID from query parameter
$selected_user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : null;
?>

<div class="d-flex">

<?php include("../../includes/sidebar.php"); ?>

<div class="p-4 w-100">
    <h2>Activity Logs</h2>

    <div class="row">
        <!-- User List -->
        <div class="col-md-3">
            <h5>Users</h5>
            <div class="list-group">
                <?php
                // Get non-admin users who have logs
                $user_query = "
                    SELECT DISTINCT users.id, users.full_name 
                    FROM logs 
                    JOIN users ON logs.user_id = users.id 
                    WHERE users.role != 'admin'
                    ORDER BY users.full_name ASC
                ";
                
                $user_result = mysqli_query($conn, $user_query);
                
                while($user_row = mysqli_fetch_assoc($user_result)){
                    $active_class = ($selected_user_id == $user_row['id']) ? 'active' : '';
                    $user_url = "?user_id=" . $user_row['id'];
                    ?>
                    <a href="<?= $user_url; ?>" class="list-group-item list-group-item-action <?= $active_class; ?>">
                        <?= htmlspecialchars($user_row['full_name']); ?>
                    </a>
                    <?php
                }
                ?>
            </div>
        </div>

        <!-- Logs Display -->
        <div class="col-md-9">
            <?php if($selected_user_id): ?>
                <?php
                // Get the selected user's name
                $user_name_query = "SELECT full_name FROM users WHERE id = $selected_user_id";
                $user_name_result = mysqli_query($conn, $user_name_query);
                $user_name_row = mysqli_fetch_assoc($user_name_result);
                $user_name = $user_name_row['full_name'];
                ?>
                <h5>Logs for <?= htmlspecialchars($user_name); ?></h5>
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $logs_query = "
                            SELECT logs.action, logs.created_at 
                            FROM logs 
                            WHERE logs.user_id = $selected_user_id
                            ORDER BY logs.created_at DESC
                        ";
                        
                        $logs_result = mysqli_query($conn, $logs_query);
                        
                        if(mysqli_num_rows($logs_result) > 0){
                            while($log_row = mysqli_fetch_assoc($logs_result)){
                                ?>
                                <tr>
                                    <td><?= htmlspecialchars($log_row['action']); ?></td>
                                    <td><?= $log_row['created_at']; ?></td>
                                </tr>
                                <?php
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="2" class="text-center text-muted">No logs found for this user</td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-muted">Select a user from the list to view their logs.</p>
            <?php endif; ?>
        </div>
    </div>

</div>
</div>

<?php include("../../includes/footer.php"); ?>