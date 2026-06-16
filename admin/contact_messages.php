<?php
session_start();
include('../config/db.php');

// ✅ Only admin access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
  header("Location: ../login.php");
  exit();
}

// ✅ Delete Message
if (isset($_GET['del'])) {
  $id = intval($_GET['del']);
  mysqli_query($conn, "DELETE FROM contact_messages WHERE id=$id");
  echo "<script>alert('🗑️ Message deleted successfully!'); window.location='contact_messages.php';</script>";
  exit;
}

// ✅ Fetch all messages (no filter)
$messages = mysqli_query($conn, "
  SELECT contact_messages.*, users.name AS user_name
  FROM contact_messages
  LEFT JOIN users ON contact_messages.user_id = users.id
  ORDER BY contact_messages.created_at DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>📩 All Visitor Messages | Admin Panel</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<style>
body {
  font-family:'Poppins',sans-serif;
  background:#f1f5f9;
  transition:background 0.4s,color 0.4s;
}

/* Navbar */
.navbar {
  background:linear-gradient(90deg,#2563eb,#9333ea);
  color:#fff;
  padding:12px 20px;
  display:flex;
  justify-content:space-between;
  align-items:center;
  box-shadow:0 4px 10px rgba(0,0,0,0.2);
}
.navbar h4 { margin:0; font-weight:600; }
.navbar .back-btn {
  background:#ffffff33;
  color:#fff;
  border:none;
  border-radius:6px;
  padding:8px 14px;
  text-decoration:none;
  font-weight:500;
  transition:0.3s;
}
.navbar .back-btn:hover { background:#ffffff55; }
.theme-toggle {
  background:#fff;
  border:none;
  color:#9333ea;
  width:40px; height:40px;
  border-radius:50%;
  font-size:20px;
  cursor:pointer;
  transition:0.3s;
}
.theme-toggle:hover { transform:scale(1.1); }

/* Container */
.container {
  max-width:1150px;
  margin-top:60px;
}
.card {
  border:none;
  border-radius:15px;
  box-shadow:0 6px 20px rgba(0,0,0,0.08);
}

/* Table */
.table th {
  background:#2563eb;
  color:#fff;
  text-align:left;
}
.table td { vertical-align:top; }
.table-striped>tbody>tr:nth-of-type(odd)>* {
  background-color:rgba(37,99,235,0.05);
}

/* Dark Mode */
body.dark { background:linear-gradient(135deg,#0f172a,#1e293b); color:#f8fafc; }
body.dark .navbar { background:linear-gradient(90deg,#0f172a,#1e293b); }
body.dark .card { background:#1e293b; box-shadow:0 4px 12px rgba(0,0,0,0.5); }
body.dark .table { color:#f1f5f9; }
body.dark th { background:#334155 !important; }
body.dark tr:nth-child(even) { background:#1e293b !important; }
</style>
</head>

<body>

<div class="navbar">
  <a href="dashboard.php" class="back-btn">⬅ Back to Dashboard</a>
  <h4> Visitor Messages</h4>
  <button id="themeToggle" class="theme-toggle">🌙</button>
</div>

<div class="container mt-4">
  <div class="card p-4">
    <h4 class="fw-semibold text-center mb-3">📨 All Visitor Messages</h4>
    <div class="table-responsive">
      <table class="table table-bordered table-striped align-middle">
        <thead>
          <tr>
            <th>#</th>
            <th>User</th>
            <th>Visitor Name</th>
            <th>Email</th>
            <th>Subject</th>
            <th>Message</th>
            <th>Date</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if (mysqli_num_rows($messages) > 0): $i = 1; ?>
            <?php while ($row = mysqli_fetch_assoc($messages)): ?>
              <tr>
                <td><?= $i++ ?></td>
                <td><?= htmlspecialchars($row['user_name'] ?? 'Unknown User') ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['subject']) ?></td>
                <td><?= nl2br(htmlspecialchars($row['message'])) ?></td>
                <td><?= date('d M Y, h:i A', strtotime($row['created_at'])) ?></td>
                <td>
                  <a href="?del=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this message?')">Delete</a>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="8" class="text-center text-muted">No messages yet 📭</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
const themeBtn = document.getElementById('themeToggle');
const body = document.body;
if(localStorage.getItem('theme')==='dark'){ body.classList.add('dark'); themeBtn.textContent='☀️'; }
themeBtn.addEventListener('click',()=>{
  body.classList.toggle('dark');
  if(body.classList.contains('dark')){ themeBtn.textContent='☀️'; localStorage.setItem('theme','dark'); }
  else{ themeBtn.textContent='🌙'; localStorage.setItem('theme','light'); }
});
</script>

</body>
</html>
