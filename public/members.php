<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/layout.php';
requireRole(['moderator']);
$user = currentUser();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'approve') {
        $id = (int)($_POST['id'] ?? 0);
        dbExecute("UPDATE users SET status='active' WHERE id=? AND status='pending'", [$id]);
        flash('success', 'Account approved.');
        redirect('/members.php');
    }

    if ($action === 'reject') {
        $id = (int)($_POST['id'] ?? 0);
        // Hard delete pending — they haven't used the system
        dbExecute("DELETE FROM users WHERE id=? AND status='pending'", [$id]);
        flash('success', 'Registration rejected and removed.');
        redirect('/members.php');
    }

    if ($action === 'update') {
        $id          = (int)($_POST['id'] ?? 0);
        $name        = trim($_POST['name'] ?? '');
        $email       = trim($_POST['email'] ?? '');
        $role        = $_POST['role'] ?? 'member';
        $instr       = trim($_POST['instrument'] ?? '');
        $yr          = trim($_POST['year_level'] ?? '');
        $sid         = trim($_POST['student_id'] ?? '');
        $contact     = preg_replace('/[^0-9+\-\s()]/', '', trim($_POST['contact_number'] ?? ''));
        $status      = $_POST['status'] ?? 'active';
        $scholarship = $_POST['scholarship_status'] ?? 'Not Scholar';

        $target = dbQueryOne('SELECT role FROM users WHERE id=?', [$id]);
        if ($target && $target['role'] === 'moderator') {
            flash('error', 'Cannot edit a Moderator account.');
            redirect('/members.php');
        }
        if ($role === 'moderator') $role = 'member';

        dbExecute('UPDATE users SET name=?,email=?,role=?,instrument=?,year_level=?,student_id=?,contact_number=?,status=?,scholarship_status=? WHERE id=?',
            [$name,$email,$role,$instr,$yr,$sid,$contact,$status,$scholarship,$id]);
        flash('success', 'Member updated.');
        redirect('/members.php');
    }

    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id === $user['id']) { flash('error', 'Cannot delete yourself.'); redirect('/members.php'); }
        $target = dbQueryOne('SELECT role FROM users WHERE id=?', [$id]);
        if ($target && $target['role'] === 'moderator') {
            flash('error', 'Cannot remove a Moderator account.');
            redirect('/members.php');
        }
        dbExecute('DELETE FROM users WHERE id = ?', [$id]);
        flash('success', 'Member removed.');
        redirect('/members.php');
    }

    if ($action === 'reset_password') {
        $id = (int)($_POST['id'] ?? 0);
        $pw = $_POST['new_password'] ?? 'password';
        $hash = password_hash($pw, PASSWORD_BCRYPT);
        dbExecute('UPDATE users SET password_hash = ? WHERE id = ?', [$hash, $id]);
        flash('success', 'Password reset.');
        redirect('/members.php');
    }
}

$pending = dbQuery("SELECT * FROM users WHERE status='pending' ORDER BY created_at ASC");

$members = dbQuery('SELECT u.*, COALESCE(ps.total_points,0) AS penalty_points
    FROM users u
    LEFT JOIN penalty_summary ps ON ps.user_id = u.id
    WHERE u.role != "moderator" AND u.status = "active"
    ORDER BY u.role ASC, u.name ASC');

layout_head('Members', 'members');
?>

<?php if ($e = getFlash('error')): ?>
<div class="alert alert-danger d-flex align-items-center gap-2" data-auto-dismiss>
  <i class="bi bi-exclamation-triangle-fill"></i> <?= h($e) ?>
</div>
<?php endif; ?>
<?php if ($s = getFlash('success')): ?>
<div class="alert alert-success d-flex align-items-center gap-2" data-auto-dismiss>
  <i class="bi bi-check-circle-fill"></i> <?= h($s) ?>
</div>
<?php endif; ?>

<?php if ($pending): ?>
<div class="card mb-4 border-warning">
  <div class="card-header d-flex align-items-center gap-2" style="background:#fffbeb">
    <i class="bi bi-hourglass-split text-warning"></i>
    <span class="card-title mb-0">Pending Approvals</span>
    <span class="badge bg-warning text-dark ms-1"><?= count($pending) ?></span>
  </div>
  <div class="table-wrap table-responsive">
    <table>
      <thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Instrument</th>
          <th>Year</th>
          <th>Student ID</th>
          <th>Contact</th>
          <th>Registered</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($pending as $p): ?>
        <tr>
          <td><strong><?= h($p['name']) ?></strong></td>
          <td class="small text-muted"><?= h($p['email']) ?></td>
          <td><?= h($p['instrument'] ?: '—') ?></td>
          <td><?= h($p['year_level'] ?: '—') ?></td>
          <td class="small"><?= h($p['student_id'] ?: '—') ?></td>
          <td class="small"><?= h($p['contact_number'] ?: '—') ?></td>
          <td class="small text-muted"><?= formatDate($p['created_at']) ?></td>
          <td style="white-space:nowrap">
            <form method="POST" style="display:inline">
              <input type="hidden" name="action" value="approve">
              <input type="hidden" name="id" value="<?= $p['id'] ?>">
              <button class="btn btn-xs btn-success">
                <i class="bi bi-check-lg me-1"></i>Approve
              </button>
            </form>
            <form method="POST" style="display:inline">
              <input type="hidden" name="action" value="reject">
              <input type="hidden" name="id" value="<?= $p['id'] ?>">
              <button class="btn btn-xs btn-outline-danger"
                      data-confirm="Reject and delete <?= h($p['name']) ?>'s registration?">
                <i class="bi bi-x-lg me-1"></i>Reject
              </button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php endif; ?>

<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <span class="card-title"><i class="bi bi-people me-2"></i>Members Management</span>
  </div>
  <div class="card-body py-3 px-3">
    <div class="search-bar-wrap">
      <span class="search-icon"><i class="bi bi-search"></i></span>
      <input id="tableSearch" type="search" class="form-control search-input" placeholder="Search by name, email, instrument…">
    </div>
  </div>
  <div class="table-wrap table-responsive" data-searchable>
    <table>
      <thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Role</th>
          <th>Instrument</th>
          <th>Year</th>
          <th>Student ID</th>
          <th>Contact</th>
          <th>Scholarship</th>
          <th>Penalty</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($members as $m): ?>
        <tr>
          <td><strong><?= h($m['name']) ?></strong></td>
          <td class="small text-muted"><?= h($m['email']) ?></td>
          <td><?= roleBadge($m['role']) ?></td>
          <td><?= h($m['instrument'] ?: '—') ?></td>
          <td><?= h($m['year_level'] ?: '—') ?></td>
          <td class="small"><?= h($m['student_id'] ?: '—') ?></td>
          <td class="small"><?= h($m['contact_number'] ?: '—') ?></td>
          <td><?= scholarshipBadge($m['scholarship_status'] ?? 'Not Scholar') ?></td>
          <td class="<?= penaltyColor((float)$m['penalty_points']) ?> fw-bold"><?= $m['penalty_points'] ?></td>
          <td style="white-space:nowrap">
            <button class="btn btn-xs btn-outline-secondary"
                    onclick="openModal('xumodalEdit'); fillEdit(<?= htmlspecialchars(json_encode($m), ENT_QUOTES) ?>)">
              <i class="bi bi-pencil"></i> Edit
            </button>
            <?php if ($m['id'] !== $user['id']): ?>
            <form method="POST" style="display:inline">
              <input type="hidden" name="action" value="delete">
              <input type="hidden" name="id" value="<?= $m['id'] ?>">
              <button type="submit" class="btn btn-xs btn-outline-danger"
                      data-confirm="Remove <?= h($m['name']) ?>?">
                <i class="bi bi-trash"></i>
              </button>
            </form>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php if (!$members): ?>
        <tr><td colspan="10">
          <div class="empty-state">
            <div class="empty-icon"><i class="bi bi-people"></i></div>
            <p>No members yet.</p>
          </div>
        </td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Edit Modal -->
<div class="xu-modal-overlay" id="xumodalEdit">
  <div class="xu-modal" style="max-width:620px">
    <div class="xu-modal-header">
      <span class="xu-modal-title"><i class="bi bi-pencil me-2"></i>Edit Member</span>
      <button class="xu-modal-close" onclick="closeModal(this)"><i class="bi bi-x-lg"></i></button>
    </div>
    <form method="POST">
      <input type="hidden" name="action" value="update">
      <input type="hidden" name="id" id="edit_id">
      <div class="xu-modal-body">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Full Name *</label>
            <input id="edit_name" name="name" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Email *</label>
            <input id="edit_email" name="email" type="email" class="form-control" required>
          </div>
        </div>
        <div class="row g-3 mt-0">
          <div class="col-md-6">
            <label class="form-label">Role</label>
            <select id="edit_role" name="role" class="form-control">
              <option value="member">Band Member</option>
              <option value="officer">Officer</option>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label">Account Status</label>
            <select id="edit_status" name="status" class="form-control">
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
            </select>
          </div>
        </div>
        <div class="row g-3 mt-0">
          <div class="col-md-6">
            <label class="form-label">Instrument</label>
            <input id="edit_instrument" name="instrument" class="form-control">
          </div>
          <div class="col-md-6">
            <label class="form-label">Year Level</label>
            <input id="edit_year_level" name="year_level" class="form-control">
          </div>
        </div>
        <div class="row g-3 mt-0">
          <div class="col-md-6">
            <label class="form-label">Student ID</label>
            <input id="edit_student_id" name="student_id" class="form-control">
          </div>
          <div class="col-md-6">
            <label class="form-label">Contact Number</label>
            <input id="edit_contact_number" name="contact_number" class="form-control contact-number-input" inputmode="tel" placeholder="e.g. 09171234567">
          </div>
        </div>
        <div class="mt-3">
          <label class="form-label">Scholarship Status</label>
          <select id="edit_scholarship_status" name="scholarship_status" class="form-control">
            <option value="Not Scholar">Not Scholar</option>
            <option value="Half Scholar">Half Scholar</option>
            <option value="Full Scholar">Full Scholar</option>
          </select>
        </div>
      </div>
      <div class="xu-modal-footer">
        <button type="button" class="btn btn-outline-secondary" onclick="closeModal(this)">Cancel</button>
        <button type="submit" class="btn btn-primary">
          <i class="bi bi-floppy me-1"></i>Save Changes
        </button>
      </div>
    </form>
  </div>
</div>

<script>
function fillEdit(m) {
  ['id','name','email','role','status','instrument','year_level','student_id','contact_number','scholarship_status'].forEach(k => {
    const el = document.getElementById('edit_' + k);
    if (el) el.value = m[k] || (k === 'scholarship_status' ? 'Not Scholar' : '');
  });
}
</script>

<?php layout_foot(); ?>
