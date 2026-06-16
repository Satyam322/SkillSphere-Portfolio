<?php
session_start();
include('../config/db.php');

// ✅ Protect user
if(!isset($_SESSION['user_id'])){
  header("Location: ../login.php");
  exit();
}

$uid = $_SESSION['user_id'];

// ✅ Add Skill
if(isset($_POST['add'])){
  $skill = trim($_POST['skill']);
  $percent = intval($_POST['percent']);
  if(!empty($skill) && $percent >= 0 && $percent <= 100){
    mysqli_query($conn, "INSERT INTO skills (user_id, skill_name, percentage, created_at)
                         VALUES ($uid, '$skill', '$percent', NOW())");
    echo "<script>alert('✅ Skill added successfully!'); window.location='skills.php';</script>";
  } else {
    echo "<script>alert('⚠️ Please enter valid skill and percentage.');</script>";
  }
}

// ✅ Update Skill
if(isset($_POST['update'])){
  $sid = intval($_POST['skill_id']);
  $skill = trim($_POST['skill']);
  $percent = intval($_POST['percent']);
  if(!empty($skill) && $percent >= 0 && $percent <= 100){
    mysqli_query($conn, "UPDATE skills SET skill_name='$skill', percentage='$percent' WHERE id=$sid AND user_id=$uid");
    echo "<script>alert('✅ Skill updated successfully!'); window.location='skills.php';</script>";
  } else {
    echo "<script>alert('⚠️ Please enter valid skill and percentage.');</script>";
  }
}

// ✅ Delete Skill
if(isset($_GET['del'])){
  $id = intval($_GET['del']);
  mysqli_query($conn, "DELETE FROM skills WHERE id=$id AND user_id=$uid");
  echo "<script>alert('🗑️ Skill deleted!'); window.location='skills.php';</script>";
}

// ✅ Fetch Skills
$data = mysqli_query($conn, "SELECT * FROM skills WHERE user_id=$uid ORDER BY id DESC");

// ✅ Edit Skill
$edit_skill = null;
if(isset($_GET['edit'])){
  $id = intval($_GET['edit']);
  $res = mysqli_query($conn, "SELECT * FROM skills WHERE id=$id AND user_id=$uid");
  if(mysqli_num_rows($res) > 0){
    $edit_skill = mysqli_fetch_assoc($res);
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>💡 My Skills | Smart Portfolio</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- ✅ Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<style>
body{
  font-family:'Poppins',sans-serif;
  background:#f1f5f9;
  transition:background 0.4s,color 0.4s;
}

/* ✅ Header */
.navbar{
  background:linear-gradient(90deg,#2563eb,#9333ea);
  color:#fff;
  padding:12px 20px;
  display:flex;
  justify-content:space-between;
  align-items:center;
  box-shadow:0 4px 10px rgba(0,0,0,0.2);
}
.navbar h4{margin:0;font-weight:600;}
.navbar .back-btn{
  background:#ffffff33;
  color:#fff;
  border:none;
  border-radius:6px;
  padding:8px 14px;
  text-decoration:none;
  font-weight:500;
  transition:0.3s;
}
.navbar .back-btn:hover{background:#ffffff55;}
.theme-toggle{
  background:#fff;
  border:none;
  color:#9333ea;
  width:40px;height:40px;
  border-radius:50%;
  font-size:20px;
  cursor:pointer;
  transition:0.3s;
}
.theme-toggle:hover{transform:scale(1.1);}

/* ✅ Main Container */
.container{max-width:1000px;margin-top:60px;}
.card{
  border:none;
  border-radius:15px;
  box-shadow:0 6px 20px rgba(0,0,0,0.08);
  transition:background 0.4s,box-shadow 0.4s;
}
.btn-primary{
  background:linear-gradient(90deg,#2563eb,#9333ea);
  border:none;
}
.btn-primary:hover{
  background:linear-gradient(90deg,#9333ea,#06b6d4);
}
.progress{
  height:12px;
  border-radius:8px;
  background:#e2e8f0;
  overflow:hidden;
}
.progress-bar{
  background:linear-gradient(90deg,#2563eb,#9333ea);
  transition:width 0.4s;
}

/* 🌙 Dark Mode */
body.dark{
  background:linear-gradient(135deg,#0f172a,#1e293b);
  color:#f8fafc;
}
body.dark .navbar{
  background:linear-gradient(90deg,#0f172a,#1e293b);
}
body.dark .card{background:#1e293b;box-shadow:0 4px 12px rgba(0,0,0,0.5);}
body.dark .btn-primary{background:linear-gradient(90deg,#9333ea,#06b6d4);}
body.dark .table{color:#f1f5f9;}
body.dark th{background:#334155 !important;}
body.dark tr:nth-child(even){background:#1e293b !important;}
body.dark input,body.dark textarea,body.dark input[type=number]{
  background:#334155;
  color:#fff;
  border:1px solid #475569;
}
body.dark input:focus,body.dark textarea:focus{
  background:#1e293b;
  border-color:#9333ea;
  color:#fff;
}
</style>
</head>

<body>

<!-- ✅ Header -->
<div class="navbar">
  <a href="dashboard.php" class="back-btn">⬅ Back Dashboard</a>
  <h4> Manage Skills</h4>
  <button id="themeToggle" class="theme-toggle">🌙</button>
</div>

<div class="container mt-4">

  <!-- ✅ Add/Edit Skill -->
  <div class="card p-4 mb-4">
    <h4 class="fw-semibold text-center mb-3"><?= $edit_skill ? "✏️ Edit Skill" : "➕ Add New Skill" ?></h4>
    <form method="POST" class="row g-3">
      <?php if($edit_skill): ?>
        <input type="hidden" name="skill_id" value="<?= $edit_skill['id'] ?>">
      <?php endif; ?>
      <div class="col-md-6">
        <input type="text" name="skill" class="form-control" placeholder="Skill Name (e.g., JavaScript)" 
               value="<?= htmlspecialchars($edit_skill['skill_name'] ?? '') ?>" required>
      </div>
      <div class="col-md-6">
        <input type="number" name="percent" class="form-control" placeholder="Skill Level (0–100)" 
               value="<?= htmlspecialchars($edit_skill['percentage'] ?? '') ?>" required>
      </div>
      <div class="col-12 text-center">
        <button type="submit" name="<?= $edit_skill ? 'update' : 'add' ?>" class="btn btn-primary px-4 py-2">
          <?= $edit_skill ? '💾 Update Skill' : '➕ Add Skill' ?>
        </button>
        <?php if($edit_skill): ?>
          <a href="skills.php" class="btn btn-danger px-4 py-2 ms-2">Cancel</a>
        <?php endif; ?>
      </div>
    </form>
  </div>

  <!-- ✅ Skills Table -->
  <div class="card p-3">
    <h5 class="fw-semibold mb-3">💡 All Skills</h5>
    <div class="table-responsive">
      <table class="table align-middle table-bordered">
        <thead class="text-white" style="background:#2563eb;">
          <tr>
            <th>ID</th>
            <th>Skill</th>
            <th>Proficiency</th>
            <th>Progress</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if(mysqli_num_rows($data) > 0): ?>
            <?php while($row=mysqli_fetch_assoc($data)): ?>
              <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['skill_name']) ?></td>
                <td><?= $row['percentage'] ?>%</td>
                <td>
                  <div class="progress">
                    <div class="progress-bar" role="progressbar" style="width:<?= $row['percentage'] ?>%;"></div>
                  </div>
                </td>
                <td>
                  <a href="?edit=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                  <a href="?del=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this skill?')">Delete</a>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="5" class="text-center text-muted">No skills added yet 😔</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

</div>

<!-- ✅ Dark Mode Script -->
<script>
const themeBtn=document.getElementById('themeToggle');
const body=document.body;
if(localStorage.getItem('theme')==='dark'){body.classList.add('dark');themeBtn.textContent='☀️';}
themeBtn.addEventListener('click',()=>{
  body.classList.toggle('dark');
  if(body.classList.contains('dark')){themeBtn.textContent='☀️';localStorage.setItem('theme','dark');}
  else{themeBtn.textContent='🌙';localStorage.setItem('theme','light');}
});
</script>

</body>
</html>
