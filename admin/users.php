<?php
session_start();
include('../config/db.php');

// ✅ Allow only admin
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
  header("Location: ../login.php");
  exit();
}

// ✅ Ensure `status` column exists
mysqli_query($conn, "ALTER TABLE users ADD COLUMN IF NOT EXISTS status ENUM('active','blocked') DEFAULT 'active'");

// ✅ Handle block/unblock toggle
if(isset($_GET['toggle'])){
  $id = intval($_GET['toggle']);
  $res = mysqli_query($conn, "SELECT status FROM users WHERE id=$id");
  if($row = mysqli_fetch_assoc($res)){
    $new_status = ($row['status'] == 'active') ? 'blocked' : 'active';
    mysqli_query($conn, "UPDATE users SET status='$new_status' WHERE id=$id");
    echo "<script>alert('User status changed to $new_status'); window.location='users.php';</script>";
    exit();
  }
}

// ✅ Handle user delete (not admin)
if(isset($_GET['delete'])){
  $id = intval($_GET['delete']);
  $check = mysqli_fetch_assoc(mysqli_query($conn, "SELECT role FROM users WHERE id=$id"));
  if($check && $check['role'] != 'admin'){
    mysqli_query($conn, "DELETE FROM users WHERE id=$id");
    echo "<script>alert('🗑️ User deleted successfully!'); window.location='users.php';</script>";
    exit();
  } else {
    echo "<script>alert('❌ You cannot delete an admin account!'); window.location='users.php';</script>";
  }
}

// ✅ Fetch all users
$result = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");

// ✅ Count summary
$total_users = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE role='user'"));
$total_admins = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE role='admin'"));
$total_blocked = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE status='blocked'"));
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>👥 Manage Users | Smart Portfolio Admin</title>

<!-- ✅ Bootstrap 5 CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
body {
  font-family: 'Poppins', sans-serif;
  background: #f1f5f9;
  transition: background 0.4s, color 0.4s;
}

/* 🌙 Dark Mode */
body.dark {
  background: linear-gradient(135deg,#0f172a,#1e293b);
  color: #f8fafc;
}
body.dark .card, body.dark table {
  background: #1e293b !important;
  color: #f8fafc;
  box-shadow: 0 6px 15px rgba(0,0,0,0.5);
}
body.dark th { background: #9333ea !important; }
body.dark td { border-color: #334155; }
body.dark .btn-outline-secondary { color: #f8fafc; border-color: #475569; }
body.dark .btn-outline-secondary:hover { background: #475569; }
body.dark input {
  background: #334155;
  color: #f1f5f9;
  border: 1px solid #475569;
}
body.dark input:focus {
  background: #1e293b;
  border-color: #9333ea;
}

/* 🌙 Theme Toggle */
.theme-toggle {
  background: linear-gradient(90deg,#2563eb,#9333ea);
  color: white;
  border: none;
  width: 45px;
  height: 45px;
  border-radius: 50%;
  font-size: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: 0.3s;
}
.theme-toggle:hover {
  transform: scale(1.1);
  background: linear-gradient(90deg,#9333ea,#06b6d4);
}
.role-admin { color: #22c55e; font-weight: 600; }
.role-user { color: #2563eb; font-weight: 600; }
.status-active { color: #16a34a; font-weight: 600; }
.status-blocked { color: #dc2626; font-weight: 600; }
</style>
</head>

<body>

<!-- ✅ Navbar -->
<nav class="navbar navbar-expand-lg bg-white shadow-sm py-3 px-4 sticky-top">
  <div class="container-fluid d-flex justify-content-between align-items-center">
    <a href="dashboard.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i>Back to Dashboard</a>
    <h4 class="fw-bold text-primary m-0"><i class="bi bi-people-fill"></i> Manage Users</h4>
    <button id="themeToggle" class="theme-toggle">🌙</button>
  </div>
</nav>

<!-- ✅ Main Container -->
<div class="container my-5">
  <div class="card border-0 shadow p-4 rounded-4">

    <!-- Stats -->
    <div class="row text-center mb-4">
      <div class="col-md-4 mb-3">
        <div class="p-3 rounded-3 text-white" style="background:linear-gradient(90deg,#2563eb,#9333ea);">
          👥 Total Users: <b><?= $total_users ?></b>
        </div>
      </div>
      <div class="col-md-4 mb-3">
        <div class="p-3 rounded-3 text-white" style="background:linear-gradient(90deg,#16a34a,#22c55e);">
          🧑‍💼 Admins: <b><?= $total_admins ?></b>
        </div>
      </div>
      <div class="col-md-4 mb-3">
        <div class="p-3 rounded-3 text-white" style="background:linear-gradient(90deg,#dc2626,#ef4444);">
          🚫 Blocked: <b><?= $total_blocked ?></b>
        </div>
      </div>
    </div>

    <!-- Search -->
    <div class="d-flex justify-content-center mb-4">
      <input type="text" id="searchInput" class="form-control w-50 shadow-sm" placeholder="🔍 Search by name or email...">
    </div>

    <!-- Table -->
    <div class="table-responsive">
      <table class="table table-bordered align-middle text-center">
        <thead class="table-primary">
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Status</th>
            <th>Created</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody id="userTable">
          <?php if(mysqli_num_rows($result) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
              <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td class="<?= ($row['role']=='admin') ? 'role-admin':'role-user' ?>"><?= ucfirst($row['role']) ?></td>
                <td class="<?= ($row['status']=='active') ? 'status-active':'status-blocked' ?>">
                  <?= ucfirst($row['status']) ?> <?= ($row['status']=='active') ? '🟢':'🔴' ?>
                </td>
                <td><?= date('d M Y', strtotime($row['created_at'])) ?></td>
                <td>
                  <?php if($row['role'] != 'admin'): ?>
                    <a href="?toggle=<?= $row['id'] ?>" class="text-primary fw-semibold" onclick="return confirm('Change this user’s status?')">
                      <?= ($row['status']=='active') ? 'Block' : 'Unblock' ?>
                    </a> |
                    <a href="?delete=<?= $row['id'] ?>" class="text-danger fw-semibold" onclick="return confirm('Delete this user permanently?')">Delete</a>
                  <?php else: ?>
                    <span class="text-secondary">Admin</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="7" class="text-muted py-4">No users found 😔</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- 🌙 Dark Mode Script -->
<script>
const themeBtn=document.getElementById('themeToggle');
const body=document.body;
if(localStorage.getItem('theme')==='dark'){
  body.classList.add('dark');
  themeBtn.textContent='☀️';
}
themeBtn.addEventListener('click',()=>{
  body.classList.toggle('dark');
  if(body.classList.contains('dark')){
    themeBtn.textContent='☀️';
    localStorage.setItem('theme','dark');
  } else {
    themeBtn.textContent='🌙';
    localStorage.setItem('theme','light');
  }
});

// ✅ Search filter
document.getElementById('searchInput').addEventListener('keyup', function(){
  let filter = this.value.toLowerCase();
  let rows = document.querySelectorAll('#userTable tr');
  rows.forEach(row => {
    let text = row.textContent.toLowerCase();
    row.style.display = text.includes(filter) ? '' : 'none';
  });
});
</script>

</body>
</html>
