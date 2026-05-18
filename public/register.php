<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';
startSession();

if (isLoggedIn()) { redirect('/dashboard.php'); }

$error   = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';
    $instr    = trim($_POST['instrument'] ?? '');
    $yr       = trim($_POST['year_level'] ?? '');
    $sid      = trim($_POST['student_id'] ?? '');
    $contact  = preg_replace('/[^0-9+\-\s()]/', '', trim($_POST['contact_number'] ?? ''));

    if (!$name || !$email || !$password) {
        $error = 'Name, email, and password are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email address.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        $exists = dbQueryOne('SELECT id FROM users WHERE email = ?', [$email]);
        if ($exists) {
            $error = 'That email is already registered.';
        } else {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            dbInsert(
                'INSERT INTO users (name,email,password_hash,role,instrument,year_level,student_id,contact_number,status) VALUES (?,?,?,?,?,?,?,?,?)',
                [$name, $email, $hash, 'member', $instr, $yr, $sid, $contact, 'pending']
            );
            $success = true;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sign Up — XUBand</title>
  <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="/assets/css/bootstrap-icons.min.css">
  <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<div class="login-page">
  <div class="login-box" style="max-width:480px">
    <div class="login-logo">
      <img src="/assets/img/xuband-logo.png" alt="XUBand Logo"
           style="max-width:120px;max-height:72px;object-fit:contain;margin-bottom:.75rem">
      <h1 style="font-size:1.4rem;font-weight:900;letter-spacing:.5px">CREATE ACCOUNT</h1>
      <p class="text-muted" style="font-size:.85rem;margin-top:.25rem">Xavier University Band Digital Filing System</p>
    </div>

    <?php if ($success): ?>
    <div class="alert alert-success d-flex align-items-start gap-2">
      <i class="bi bi-check-circle-fill mt-1"></i>
      <div>
        <strong>Registration submitted!</strong><br>
        Your account is pending approval by a moderator. You'll be able to log in once it's approved.
      </div>
    </div>
    <a href="/login.php" class="btn btn-primary w-100">Back to Login</a>

    <?php else: ?>

    <?php if ($error): ?>
    <div class="alert alert-danger d-flex align-items-center gap-2">
      <i class="bi bi-exclamation-triangle-fill"></i> <?= h($error) ?>
    </div>
    <?php endif; ?>

    <form method="POST" autocomplete="off">
      <div class="row g-3 mb-3">
        <div class="col-12">
          <label class="form-label">Full Name *</label>
          <input name="name" class="form-control" value="<?= h($_POST['name'] ?? '') ?>" required autofocus placeholder="e.g. Juan dela Cruz">
        </div>
        <div class="col-12">
          <label class="form-label">Email Address *</label>
          <input name="email" type="email" class="form-control" value="<?= h($_POST['email'] ?? '') ?>" required placeholder="you@example.com">
        </div>
        <div class="col-sm-6">
          <label class="form-label">Password *</label>
          <input name="password" type="password" class="form-control" required placeholder="Min. 6 characters">
        </div>
        <div class="col-sm-6">
          <label class="form-label">Confirm Password *</label>
          <input name="confirm_password" type="password" class="form-control" required placeholder="Repeat password">
        </div>
        <div class="col-sm-6">
          <label class="form-label">Instrument</label>
          <input name="instrument" class="form-control" value="<?= h($_POST['instrument'] ?? '') ?>" placeholder="e.g. Trumpet">
        </div>
        <div class="col-sm-6">
          <label class="form-label">Year Level</label>
          <input name="year_level" class="form-control" value="<?= h($_POST['year_level'] ?? '') ?>" placeholder="e.g. 2nd Year">
        </div>
        <div class="col-sm-6">
          <label class="form-label">Student ID</label>
          <input name="student_id" class="form-control" value="<?= h($_POST['student_id'] ?? '') ?>" placeholder="e.g. XU-2024-001">
        </div>
        <div class="col-sm-6">
          <label class="form-label">Contact Number</label>
          <input name="contact_number" class="form-control contact-number-input" inputmode="tel"
                 value="<?= h($_POST['contact_number'] ?? '') ?>" placeholder="e.g. 09171234567">
        </div>
      </div>

      <button type="submit" class="btn btn-primary w-100 py-2">
        <i class="bi bi-person-check me-1"></i> Submit Registration
      </button>
    </form>

    <div class="text-center mt-3" style="font-size:.85rem">
      Already have an account? <a href="/login.php">Sign in</a>
    </div>

    <?php endif; ?>
  </div>
</div>
<script src="/assets/js/bootstrap.bundle.min.js"></script>
<script src="/assets/js/main.js"></script>
</body>
</html>
