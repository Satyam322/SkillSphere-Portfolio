<?php
include('../config/auth_check.php');
include('../config/db.php');

// ✅ Add Skill
if(isset($_POST['add_skill'])){
    $skill = trim($_POST['skill']);
    $percent = intval($_POST['percent']);
    if(!empty($skill) && $percent >= 0 && $percent <= 100){
        mysqli_query($conn, "INSERT INTO skills (user_id, skill_name, percentage) VALUES (1, '$skill', $percent)");
        echo "<script>alert('✅ Skill Added Successfully!'); window.location='skills.php';</script>";
    } else {
        echo "<script>alert('⚠️ Please enter valid skill and percentage!');</script>";
    }
}

// ✅ Delete Skill
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM skills WHERE id=$id");
    echo "<script>alert('🗑️ Skill Deleted Successfully!'); window.location='skills.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>💡 Manage Skills | Smart Portfolio</title>
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
    <h4 class="fw-bold text-primary m-0">💡 Manage Skills</h4>
    <button id="themeToggle" class="theme-toggle">🌙</button>
  </div>
</nav>

<!-- ✅ Main Container -->
<div class="container my-5">
  <div class="card border-0 shadow p-4 rounded-4">

    <!-- Add Skill Form -->
    <form method="POST" class="mb-5 d-flex flex-wrap justify-content-center gap-3">
      <input type="text" name="skill" class="form-control" placeholder="Enter Skill Name (e.g., HTML, Python)" required style="max-width:250px;">
      <input type="number" name="percent" class="form-control" placeholder="Skill Level %" min="0" max="100" required style="max-width:150px;">
      <button type="submit" name="add_skill" class="btn btn-primary">➕ Add Skill</button>
    </form>

    <!-- Skills Table -->
    <div class="table-responsive">
      <table class="table table-bordered align-middle text-center">
        <thead class="table-primary">
          <tr>
            <th>ID</th>
            <th>Skill</th>
            <th>Proficiency</th>
            <th>Level</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $res = mysqli_query($conn, "SELECT * FROM skills ORDER BY id DESC");
          if(mysqli_num_rows($res) > 0){
            while($r = mysqli_fetch_assoc($res)){
              echo "
              <tr>
                <td>{$r['id']}</td>
                <td class='fw-semibold text-primary'>{$r['skill_name']}</td>
                <td>{$r['percentage']}%</td>
                <td>
                  <div class='progress' style='height:10px;'>
                    <div class='progress-bar bg-primary' style='width:{$r['percentage']}%'></div>
                  </div>
                </td>
                <td>
                  <a href='skills.php?delete={$r['id']}' 
                     class='text-danger fw-semibold' 
                     onclick='return confirm(\"Delete this skill?\")'>Delete</a>
                </td>
              </tr>";
            }
          } else {
            echo "<tr><td colspan='5' class='text-muted py-4'>No skills added yet 😔</td></tr>";
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
