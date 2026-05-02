<nav class="navbar navbar-dark bg-dark px-3">
    <span class="navbar-brand">XUBAND System</span>

    <div class="text-white">
        Welcome, <?php echo $_SESSION['name']; ?> |
        <a href="<?php echo $base_url; ?>logout.php" class="text-warning text-decoration-none">Logout</a>
    </div>
</nav>