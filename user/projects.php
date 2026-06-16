<?php
session_start();
include('../config/db.php');

// ✅ Protect user access
if(!isset($_SESSION['user_id'])){
  header("Location: ../login.php");
  exit();
}

$uid = $_SESSION['user_id'];

// ✅ ADD Project
if(isset($_POST['add_project'])){
  $title = mysqli_real_escape_string($conn, $_POST['title']);
  $desc = mysqli_real_escape_string($conn, $_POST['description']);
  $link = mysqli_real_escape_string($conn, $_POST['link']);

  if(!empty($title) && !empty($desc)){
    $query = "INSERT INTO projects (user_id, title, description, link) VALUES ('$uid', '$title', '$desc', '$link')";
    if(mysqli_query($conn, $query)){
      echo "<script>alert('✅ Project added successfully!'); window.location='projects.php';</script>";
    } else {
      echo "<script>alert('❌ Database error while adding.');</script>";
    }
  } else {
    echo "<script>alert('⚠️ Please fill all required fields.');</script>";
  }
}

// ✅ UPDATE Project
if(isset($_POST['update_project'])){
  $pid = intval($_POST['project_id']);
  $title = mysqli_real_escape_string($conn, $_POST['title']);
  $desc = mysqli_real_escape_string($conn, $_POST['description']);
  $link = mysqli_real_escape_string($conn, $_POST['link']);

  $query = "UPDATE projects SET title='$title', description='$desc', link='$link' WHERE id='$pid' AND user_id='$uid'";
  if(mysqli_query($conn, $query)){
    echo "<script>alert('✅ Project updated successfully!'); window.location='projects.php';</script>";
  } else {
    echo "<script>alert('❌ Database error while updating.');</script>";
  }
}

// ✅ DELETE Project
if(isset($_GET['delete'])){
  $pid = intval($_GET['delete']);
  mysqli_query($conn, "DELETE FROM projects WHERE id='$pid' AND user_id='$uid'");
  echo "<script>alert('🗑️ Project deleted!'); window.location='projects.php';</script>";
}

// ✅ FETCH Projects
$projects = mysqli_query($conn, "SELECT * FROM projects WHERE user_id='$uid' ORDER BY id DESC");

// ✅ EDIT Form
$edit_project = null;
if(isset($_GET['edit'])){
  $pid = intval($_GET['edit']);
  $result = mysqli_query($conn, "SELECT * FROM projects WHERE id='$pid' AND user_id='$uid'");
  if(mysqli_num_rows($result) > 0){
    $edit_project = mysqli_fetch_assoc($result);
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>📁 My Projects | Smart Portfolio</title>
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
.container{max-width:1100px;margin-top:60px;}
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
.table img,table video{
  width:140px;
  height:100px;
  object-fit:cover;
  border-radius:8px;
  border:1px solid #ccc;
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
body.dark input,body.dark textarea{
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
  <h4>Manage Projects</h4>
  <button id="themeToggle" class="theme-toggle">🌙</button>
</div>

<div class="container mt-4">

  <!-- ✅ Add/Edit Project Form -->
  <div class="card p-4 mb-4">
    <h4 class="fw-semibold text-center mb-3"><?= $edit_project ? "✏️ Edit Project" : "➕ Add New Project" ?></h4>
    <form method="POST" class="row g-3">
      <?php if($edit_project): ?>
        <input type="hidden" name="project_id" value="<?= $edit_project['id'] ?>">
      <?php endif; ?>

      <div class="col-12">
        <input type="text" name="title" class="form-control" placeholder="Project Title" 
               value="<?= htmlspecialchars($edit_project['title'] ?? '') ?>" required>
      </div>
      <div class="col-12">
        <textarea name="description" class="form-control" rows="3" placeholder="Project Description" required><?= htmlspecialchars($edit_project['description'] ?? '') ?></textarea>
      </div>
      <div class="col-12">
        <input type="url" name="link" class="form-control" placeholder="Project Link (optional)"
               value="<?= htmlspecialchars($edit_project['link'] ?? '') ?>">
      </div>

      <div class="col-12 text-center">
        <button type="submit" name="<?= $edit_project ? 'update_project' : 'add_project' ?>" class="btn btn-primary px-4 py-2">
          <?= $edit_project ? '💾 Update Project' : '➕ Add Project' ?>
        </button>
        <?php if($edit_project): ?>
          <a href="projects.php" class="btn btn-danger px-4 py-2 ms-2">Cancel</a>
        <?php endif; ?>
      </div>
    </form>
  </div>

  <!-- ✅ Project List -->
  <div class="card p-3">
    <h5 class="fw-semibold mb-3">📁 All Projects</h5>
    <div class="table-responsive">
      <table class="table align-middle table-bordered">
        <thead class="text-white" style="background:#2563eb;">
          <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Description</th>
            <th>Link</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if(mysqli_num_rows($projects) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($projects)): ?>
              <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><?= htmlspecialchars($row['description']) ?></td>
                <td>
                  <?php if(!empty($row['link'])): ?>
                    <a href="<?= htmlspecialchars($row['link']) ?>" target="_blank">Visit</a>
                  <?php else: ?>—
                  <?php endif; ?>
                </td>
                <td>
                  <a href="?edit=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                  <a href="?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this project?')">Delete</a>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="5" class="text-center text-muted">No projects added yet 😔</td></tr>
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
