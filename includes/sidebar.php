<div class="bg-light border sidebar" style="width: 200px;">
    <ul class="nav flex-column p-3">

        <li class="nav-item">
            <a href="<?php echo $base_url; ?>dashboard/dashboard.php" class="nav-link">Dashboard</a>
        </li>

        <li class="nav-item">
            <a href="<?php echo $base_url; ?>modules/profile/profile.php" class="nav-link">My Profile</a>
        </li>
        <?php if($_SESSION['role'] == 'admin'): ?>
        <li class="nav-item">
            <a href="<?php echo $base_url; ?>modules/users/list_user.php" class="nav-link">User Management</a>
        </li>
        <?php endif; ?>

        <li class="nav-item">
            <a href="<?php echo $base_url; ?>modules/files/list_files.php" class="nav-link">Music Sheets</a>
        </li>

        <li class="nav-item">
            <a href="<?php echo $base_url; ?>modules/announcements/list.php" class="nav-link">Announcements</a>
        </li>

        <li class="nav-item">
            <a href="<?php echo $base_url; ?>modules/attendance/list.php" class="nav-link">Attendance</a>
        </li>

        <li class="nav-item">
            <a href="<?php echo $base_url; ?>modules/scholarship/status.php" class="nav-link">Scholarship</a>
        </li>

        <li class="nav-item">
            <a href="<?php echo $base_url; ?>modules/reports/logs.php" class="nav-link">Reports</a>
        </li>

    </ul>
</div>