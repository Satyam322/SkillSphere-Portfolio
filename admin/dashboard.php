<?php
session_start();
include('../config/db.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
  header("Location: ../login.php");
  exit();
}

function getCount($conn, $table) {
  $query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM `$table`");
  if ($query && $row = mysqli_fetch_assoc($query)) {
    return $row['total'];
  }
  return 0;
}

$users = getCount($conn, "users");
$projects = getCount($conn, "projects");
$skills = getCount($conn, "skills");
$blogs = getCount($conn, "blogs");
$messages = getCount($conn, "contact_messages");

$latest_msg = mysqli_fetch_assoc(mysqli_query($conn, "SELECT created_at FROM contact_messages ORDER BY created_at DESC LIMIT 1"));
$last_message = $latest_msg ? date('d M Y, h:i A', strtotime($latest_msg['created_at'])) : 'No messages yet';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard | Smart Portfolio</title>
<script src="https://kit.fontawesome.com/6f64070c10.js" crossorigin="anonymous"></script>

<style>
/* ==================== Global ==================== */
*{margin:0;padding:0;box-sizing:border-box;font-family:'Poppins',sans-serif;}
body{
  background:linear-gradient(120deg,#0f172a,#1e3a8a,#2563eb);
  background-size:200% 200%;
  animation:bgAnim 8s ease infinite;
  min-height:100vh;
  overflow-x:hidden;
  display:flex;
  transition:0.4s;
}

@keyframes bgAnim{
  0%{background-position:0% 50%;}
  50%{background-position:100% 50%;}
  100%{background-position:0% 50%;}
}

/* ==================== Sidebar ==================== */
.sidebar{
  width:260px;
  background:rgba(17,24,39,0.6);
  backdrop-filter:blur(18px);
  border-right:1px solid rgba(255,255,255,0.1);
  height:100vh;
  position:fixed;
  left:-260px;
  top:0;
  transition:0.4s;
  z-index:10;
  padding-top:90px;
  color:white;
}
.sidebar.show{left:0;}

.sidebar-header{
  position:absolute;top:0;left:0;width:100%;
  padding:22px;
  text-align:center;
  font-size:22px;font-weight:700;
  background:linear-gradient(90deg,#2563eb,#9333ea);
  border-bottom:1px solid rgba(255,255,255,0.1);
}

/* Sidebar menu */
.sidebar ul{list-style:none;padding:0 20px;}
.sidebar ul li{margin:15px 0;}
.sidebar ul li a{
  display:flex;gap:15px;align-items:center;
  text-decoration:none;color:white;
  padding:13px 20px;
  background:rgba(255,255,255,0.08);
  border-radius:12px;
  transition:0.3s;
  border:1px solid rgba(255,255,255,0.15);
}
.sidebar ul li a:hover{
  background:linear-gradient(90deg,#2563eb,#9333ea);
  transform:translateX(5px);
  box-shadow:0 5px 20px rgba(0,0,0,0.4);
}
.sidebar ul li.active a{
  background:linear-gradient(90deg,#2563eb,#9333ea);
  box-shadow:0 8px 20px rgba(37,99,235,0.4);
}

.logout{margin-top:auto;text-align:center;padding:30px 0;}
.logout a{
  background:#ef4444;
  padding:10px 25px;
  border-radius:25px;
  color:white;
  font-weight:600;
  text-decoration:none;
  transition:0.3s;
}
.logout a:hover{background:#dc2626;transform:scale(1.07);}

/* ==================== Navbar ==================== */
.navbar{
  width:100%;
  background:rgba(255,255,255,0.15);
  backdrop-filter:blur(18px);
  border-bottom:1px solid rgba(255,255,255,0.2);
  color:white;
  padding:15px 25px;
  display:flex;
  justify-content:space-between;
  align-items:center;
  position:fixed;top:0;z-index:20;
}
.navbar h2{font-size:20px;font-weight:600;}

.toggle-btn{
  font-size:28px;
  cursor:pointer;
  color:white;
}

.theme-toggle{
  width:45px;height:45px;
  border-radius:50%;border:none;
  background:linear-gradient(90deg,#2563eb,#9333ea);
  color:white;
  font-size:20px;
  cursor:pointer;
  transition:0.3s;
}
.theme-toggle:hover{transform:scale(1.12);}

/* ==================== Main ==================== */
.main{
  flex:1;
  padding:110px 40px 40px;
  width:100%;
  transition:0.4s;
}
.main.shifted{margin-left:260px;}

/* ==================== Dashboard Cards ==================== */
.dashboard-cards{
  display:grid;
  grid-template-columns:repeat(3,1fr);
  gap:35px;
  margin-top:30px;
}

.card{
  background:rgba(255,255,255,0.18);
  border-radius:20px;
  backdrop-filter:blur(12px);
  padding:40px 20px;
  text-align:center;
  height:230px;
  box-shadow:0 12px 25px rgba(0,0,0,0.2);
  transition:0.3s;
  border:1px solid rgba(255,255,255,0.15);
}
.card:hover{
  transform:translateY(-10px);
  box-shadow:0 15px 40px rgba(0,0,0,0.35);
}

.card h3{color:#e0e7ff;font-size:22px;margin-bottom:12px;}
.card p{font-size:42px;font-weight:700;color:white;margin-bottom:15px;}
.card a{
  text-decoration:none;
  padding:12px 25px;
  display:inline-block;
  border-radius:10px;
  color:white;
  font-weight:600;
  background:linear-gradient(90deg,#2563eb,#9333ea);
  transition:0.3s;
}
.card a:hover{
  transform:scale(1.1);
  background:linear-gradient(90deg,#9333ea,#06b6d4);
}
.card small{color:#d1d5db;margin-top:10px;display:block;}

/* ==================== Dark Mode ==================== */
body.dark{
  background:linear-gradient(120deg,#0f172a,#1e293b);
}
body.dark .navbar{background:rgba(30,41,59,0.6);}
body.dark .sidebar{background:rgba(17,24,39,0.7);}
body.dark .card{
  background:rgba(30,41,59,0.6);
  border-color:rgba(255,255,255,0.1);
}

/* ==================== Responsive ==================== */
@media(max-width:992px){
  .dashboard-cards{grid-template-columns:repeat(2,1fr);}
}
@media(max-width:700px){
  .dashboard-cards{grid-template-columns:1fr;}
  .main.shifted{margin-left:0;}
}
</style>
</head>

<body>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
  <div class="sidebar-header">Admin Panel</div>
  <ul>
    <li class="active"><a href="dashboard.php"><i class="fa-solid fa-house"></i> Dashboard</a></li>
    <li><a href="users.php"><i class="fa-solid fa-users"></i> Users</a></li>
    <li><a href="projects.php"><i class="fa-solid fa-briefcase"></i> Projects</a></li>
    <li><a href="skills.php"><i class="fa-solid fa-brain"></i> Skills</a></li>
    <li><a href="blogs.php"><i class="fa-solid fa-pen-nib"></i> Blogs</a></li>
    <li><a href="contact_messages.php"><i class="fa-solid fa-envelope"></i> Messages</a></li>
  </ul>
  <div class="logout">
    <a href="../logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
  </div>
</div>

<!-- Navbar -->
<div class="navbar">

  <div style="display:flex;align-items:center;gap:15px;">
    <span class="toggle-btn" id="toggle-btn">&#9776;</span>
    <h2>Welcome, Admin 👑</h2>
  </div>

  <!-- Center title -->
  <h2 style="position:absolute;left:50%;transform:translateX(-50%);font-weight:700;">Admin – SkillSphere</h2>

  <button id="themeToggle" class="theme-toggle">🌙</button>
</div>

<!-- Main Content -->
<div class="main" id="main">

  <div class="dashboard-cards">

    <div class="card">
      <h3>👥 Total Users</h3>
      <p><?= $users ?></p>
      <a href="users.php">Manage</a>
      <small>All registered users</small>
    </div>

    <div class="card">
      <h3>💼 Projects</h3>
      <p><?= $projects ?></p>
      <a href="projects.php">Manage</a>
      <small>All portfolio projects</small>
    </div>

    <div class="card">
      <h3>🧠 Skills</h3>
      <p><?= $skills ?></p>
      <a href="skills.php">Manage</a>
      <small>User-added skills</small>
    </div>

    <div class="card">
      <h3>📝 Blogs</h3>
      <p><?= $blogs ?></p>
      <a href="blogs.php">Manage</a>
      <small>Published articles</small>
    </div>

    <div class="card">
      <h3>📩 Messages</h3>
      <p><?= $messages ?></p>
      <a href="contact_messages.php">View</a>
      <small>Last: <?= $last_message ?></small>
    </div>

  </div>

</div>

<script>
// Sidebar Toggle
document.getElementById('toggle-btn').onclick = () => {
  document.getElementById('sidebar').classList.toggle('show');
  document.getElementById('main').classList.toggle('shifted');
};

// Dark Mode
const body=document.body;
const btn=document.getElementById('themeToggle');

if(localStorage.getItem('theme')==='dark'){
  body.classList.add('dark');
  btn.textContent='☀️';
}

btn.onclick=()=>{
  body.classList.toggle('dark');
  if(body.classList.contains('dark')){
    btn.textContent='☀️';
    localStorage.setItem('theme','dark');
  } else {
    btn.textContent='🌙';
    localStorage.setItem('theme','light');
  }
};
</script>

</body>
</html>
