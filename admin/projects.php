<?php
include('../config/auth_check.php');
include('../config/db.php');

// ✅ Add project
if(isset($_POST['add_project'])){
    $title = trim($_POST['title']);
    $desc  = trim($_POST['description']);
    $link  = trim($_POST['link']);

    if(!empty($title) && !empty($desc)){
        $query = "INSERT INTO projects (user_id, title, description, link) VALUES (1, '$title', '$desc', '$link')";
        if(mysqli_query($conn, $query)){
            echo "<script>alert('✅ Project Added Successfully!'); window.location='projects.php';</script>";
        } else {
            echo "<script>alert('❌ Database Error: " . mysqli_error($conn) . "');</script>";
        }
    } else {
        echo "<script>alert('⚠️ Please fill all required fields!');</script>";
    }
}

// ✅ Delete project
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM projects WHERE id=$id");
    echo "<script>alert('🗑️ Project Deleted Successfully!'); window.location='projects.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>🚀 Manage Projects | Smart Portfolio</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- ✅ Bootstrap 5 -->
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
body.dark input, body.dark textarea {
  background: #334155;
  color: #f1f5f9;
  border: 1px solid #475569;
}
body.dark input:focus, body.dark textarea:focus {
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
</style>
</head>

<body>

<!-- ✅ Navbar -->
<nav class="navbar navbar-expand-lg bg-white shadow-sm py-3 px-4 sticky-top">
  <div class="container-fluid d-flex justify-content-between align-items-center">
    <a href="dashboard.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back to Dashboard</a>
    <h4 class="fw-bold text-primary m-0">🚀 Manage Projects</h4>
    <button id="themeToggle" class="theme-toggle">🌙</button>
  </div>
</nav>

<!-- ✅ Main Container -->
<div class="container my-5">
  <div class="card border-0 shadow p-4 rounded-4">

    <!-- Add Project Form -->
    <form method="POST" class="mb-5">
      <div class="mb-3">
        <label class="form-label fw-semibold">Project Title</label>
        <input type="text" name="title" class="form-control" placeholder="Enter Project Title" required>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Description</label>
        <textarea name="description" rows="4" class="form-control" placeholder="Enter Project Description" required></textarea>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Project Link (optional)</label>
        <input type="text" name="link" class="form-control" placeholder="Enter Project Link">
      </div>

      <button type="submit" name="add_project" class="btn btn-primary">➕ Add Project</button>
    </form>

    <!-- Projects Table -->
    <div class="table-responsive">
      <table class="table table-bordered align-middle text-center">
        <thead class="table-primary">
          <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Description</th>
            <th>Link</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $result = mysqli_query($conn, "SELECT * FROM projects ORDER BY id DESC");
          if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_assoc($result)){
              echo "
              <tr>
                <td>{$row['id']}</td>
                <td class='fw-semibold text-primary'>{$row['title']}</td>
                <td class='text-muted'>{$row['description']}</td>
                <td>";
                if(!empty($row['link'])){
                  echo "<a href='{$row['link']}' target='_blank' class='text-decoration-none fw-semibold text-info'>Visit 🔗</a>";
                } else { echo "—"; }
              echo "</td>
                <td>
                  <a href='projects.php?delete={$row['id']}' 
                     class='text-danger fw-semibold' 
                     onclick='return confirm(\"Delete this project?\")'>Delete</a>
                </td>
              </tr>";
            }
          } else {
            echo "<tr><td colspan='5' class='text-muted py-4'>No projects added yet 😔</td></tr>";
          }
          ?>
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
</script>

<?php include('includes/footer.php'); ?>
</body>
</html>
