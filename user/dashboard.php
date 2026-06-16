<?php
session_start();

if(!isset($_SESSION['user_id'])){
  header("Location: ../login.php");
  exit();
}

include('../config/db.php');
$uid = $_SESSION['user_id'];
$username = $_SESSION['user_name'] ?? 'User';

function getCount($conn, $query){
  $res = mysqli_query($conn, $query);
  if($res){
    $data = mysqli_fetch_assoc($res);
    return $data['total'] ?? 0;
  }
  return 0;
}

$projects = getCount($conn, "SELECT COUNT(*) AS total FROM projects WHERE user_id=$uid");
$skills = getCount($conn, "SELECT COUNT(*) AS total FROM skills WHERE user_id=$uid");
$blogs = getCount($conn, "SELECT COUNT(*) AS total FROM blogs WHERE user_id=$uid");
$about_q = mysqli_query($conn, "SELECT about_me FROM users WHERE id=$uid AND about_me <> ''");
$about = ($about_q && mysqli_num_rows($about_q)>0) ? 1 : 0;
$messages = getCount($conn, "SELECT COUNT(*) AS total FROM contact_messages WHERE user_id=$uid");
$latest_msg = mysqli_fetch_assoc(mysqli_query($conn, "SELECT created_at FROM contact_messages WHERE user_id=$uid ORDER BY created_at DESC LIMIT 1"));
$last_message = $latest_msg ? date('d M Y, h:i A', strtotime($latest_msg['created_at'])) : 'No messages yet';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>User Dashboard | Smart Portfolio</title>
<script src="https://kit.fontawesome.com/6f64070c10.js" crossorigin="anonymous"></script>

<style>
/* ========================= GLOBAL ========================= */
*{margin:0;padding:0;box-sizing:border-box;font-family:'Poppins',sans-serif;}
body{
  background:linear-gradient(120deg,#0f172a,#1e3a8a,#2563eb);
  background-size:200% 200%;
  animation:gradientMove 8s ease infinite;
  min-height:100vh;
  overflow-x:hidden;
  display:flex;
  transition:0.4s;
}

@keyframes gradientMove{
  0%{background-position:0% 50%;}
  50%{background-position:100% 50%;}
  100%{background-position:0% 50%;}
}

/* ========================= SIDEBAR ========================= */
.sidebar{
  width:260px;
  background:rgba(17,24,39,0.55);
  backdrop-filter:blur(18px);
  border-right:1px solid rgba(255,255,255,0.1);
  position:fixed;
  left:-260px;
  height:100%;
  transition:0.4s ease;
  color:white;
  z-index:12;
}
.sidebar.show{left:0;}

.sidebar-header{
  padding:22px;
  text-align:center;
  font-size:22px;
  font-weight:700;
  background:linear-gradient(90deg,#2563eb,#9333ea);
  border-bottom:1px solid rgba(255,255,255,0.15);
}

.sidebar ul{list-style:none;padding:20px;}
.sidebar ul li{margin-bottom:16px;}
.sidebar ul li a{
  text-decoration:none;
  padding:13px 20px;
  border-radius:12px;
  display:flex;align-items:center;gap:15px;
  background:rgba(255,255,255,0.08);
  color:white;
  transition:0.3s;
}
.sidebar ul li a:hover{
  background:linear-gradient(90deg,#2563eb,#9333ea);
  transform:translateX(5px);
  box-shadow:0 5px 18px rgba(0,0,0,0.3);
}
.sidebar ul li.active a{
  background:linear-gradient(90deg,#2563eb,#9333ea);
  font-weight:600;
}

.logout{text-align:center;margin-top:30px;}
.logout a{
  padding:12px 30px;
  background:#ef4444;
  border-radius:30px;
  color:white;
  text-decoration:none;
  font-weight:600;
  transition:0.3s;
}
.logout a:hover{background:#dc2626;transform:scale(1.06);}

/* ========================= NAVBAR ========================= */
.navbar{
  position:fixed;
  top:0;
  left:0;
  width:100%;
  background:rgba(255,255,255,0.15);
  backdrop-filter:blur(18px);
  border-bottom:1px solid rgba(255,255,255,0.15);
  padding:12px 25px;
  display:flex;
  align-items:center;
  justify-content:space-between;
  z-index:20;
  color:white;
}

/* username next to icon */
.nav-left{
  display:flex;
  align-items:center;
  gap:15px;
}

.username-text{
  font-size:18px;
  font-weight:600;
  color:white;
}

.toggle-btn{
  font-size:26px;
  cursor:pointer;
  color:white;
}

.theme-toggle{
  font-size:22px;
  cursor:pointer;
  border:none;
  background:linear-gradient(90deg,#2563eb,#9333ea);
  color:white;
  width:45px;
  height:45px;
  border-radius:50%;
  display:flex;align-items:center;justify-content:center;
  transition:0.3s;
  box-shadow:0 3px 12px rgba(0,0,0,0.3);
}
.theme-toggle:hover{
  transform:scale(1.12);
  background:linear-gradient(90deg,#9333ea,#06b6d4);
}

/* ========================= MAIN ========================= */
.main{
  flex:1;
  padding:110px 40px 40px;
  color:white;
  width:100%;
  transition:0.4s ease;
}
.main.shifted{margin-left:260px;}

/* ========================= DASHBOARD CARDS ========================= */
.dashboard-cards{
  display:grid;
  grid-template-columns:repeat(3,1fr);
  gap:35px;
  margin-top:30px;
}

.card{
  background:rgba(255,255,255,0.18);
  backdrop-filter:blur(12px);
  padding:35px 20px;
  border-radius:20px;
  height:240px;
  text-align:center;
  box-shadow:0 10px 25px rgba(0,0,0,0.18);
  transition:0.35s;
  border:1px solid rgba(255,255,255,0.2);
}

.card:hover{
  transform:translateY(-10px);
  box-shadow:0 15px 35px rgba(0,0,0,0.35);
}

.card h3{
  font-size:22px;
  margin-bottom:10px;
  color:#e0e7ff;
}

.card p{
  font-size:42px;
  font-weight:700;
  margin-bottom:15px;
  color:white;
}

.card a{
  text-decoration:none;
  padding:12px 25px;
  border-radius:10px;
  font-size:15px;
  font-weight:600;
  color:white;
  display:inline-block;
  background:linear-gradient(90deg,#2563eb,#9333ea);
  transition:0.3s;
}
.card a:hover{
  transform:scale(1.1);
  background:linear-gradient(90deg,#9333ea,#06b6d4);
}

.card small{
  color:#e2e8f0;
  margin-top:12px;
  display:block;
}

/* ========================= DARK MODE ========================= */
body.dark{
  background:linear-gradient(120deg,#0f172a,#1e293b);
}

body.dark .navbar{
  background:rgba(30,41,59,0.7);
}

body.dark .sidebar{
  background:rgba(17,24,39,0.65);
}

body.dark .card{
  background:rgba(30,41,59,0.65);
  border-color:rgba(255,255,255,0.12);
}

body.dark .card h3{color:#c084fc;}
body.dark .card p{color:white;}
body.dark .card small{color:#94a3b8;}

/* ========================= RESPONSIVE ========================= */
@media(max-width:992px){.dashboard-cards{grid-template-columns:repeat(2,1fr);} }
@media(max-width:700px){
  .dashboard-cards{grid-template-columns:1fr;}
  .main.shifted{margin-left:0;}
}
</style>
</head>

<body>

<!-- ========================= SIDEBAR ========================= -->
<div class="sidebar" id="sidebar">
  <div class="sidebar-header">SkillSphere</div>
  <ul>
    <li class="active"><a href="dashboard.php"><i class="fa-solid fa-house"></i> Dashboard</a></li>
    <li><a href="about.php"><i class="fa-solid fa-user-pen"></i> About Me</a></li>
    <li><a href="projects.php"><i class="fa-solid fa-briefcase"></i> My Projects</a></li>
    <li><a href="skills.php"><i class="fa-solid fa-brain"></i> My Skills</a></li>
    <li><a href="blogs.php"><i class="fa-solid fa-pen-nib"></i> My Blogs</a></li>
    <li><a href="messages.php"><i class="fa-solid fa-envelope"></i> Visitor Messages</a></li>
    <li><a href="change_password.php"><i class="fa-solid fa-key"></i> Change Password</a></li>
  </ul>

  <div class="logout">
    <a href="../logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
  </div>
</div>

<!-- ========================= NAVBAR ========================= -->
<div class="navbar">

  <!-- LEFT (ICON + USER NAME) -->
  <div class="nav-left">
    <span class="toggle-btn" id="toggle-btn">&#9776;</span>

    <span class="username-text"><?= htmlspecialchars($username) ?> 👋</span>
  </div>

  <!-- CENTER TITLE -->
  <h2 style="
      position:absolute;
      left:50%;
      transform:translateX(-50%);
      font-weight:700;
      font-size:20px;">
      User – SkillSphere
  </h2>

  <!-- RIGHT -->
  <button class="theme-toggle" id="themeToggle">🌙</button>
</div>

<!-- ========================= MAIN CONTENT ========================= -->
<div class="main" id="main">

  <div class="dashboard-cards">

    <div class="card">
      <h3>👤 About Me</h3>
      <p><?= $about ?></p>
      <a href="about.php">Manage</a>
    </div>

    <div class="card">
      <h3>💼 Projects</h3>
      <p><?= $projects ?></p>
      <a href="projects.php">Manage</a>
    </div>

    <div class="card">
      <h3>🧠 Skills</h3>
      <p><?= $skills ?></p>
      <a href="skills.php">Manage</a>
    </div>

    <div class="card">
      <h3>📝 Blogs</h3>
      <p><?= $blogs ?></p>
      <a href="blogs.php">Manage</a>
    </div>

    <div class="card">
      <h3>📩 Visitor Messages</h3>
      <p><?= $messages ?></p>
      <a href="messages.php">View</a>
      <small>Last: <?= $last_message ?></small>
    </div>

    <div class="card">
      <h3>🔑 Change Password</h3>
      <p>1</p>
      <a href="change_password.php">Update</a>
    </div>

  </div>

</div>

<script>
/* Sidebar Toggle */
const sidebar=document.getElementById('sidebar');
const main=document.getElementById('main');
document.getElementById('toggle-btn').onclick=()=>{sidebar.classList.toggle('show');main.classList.toggle('shifted');};

/* Dark Mode Toggle */
const themeBtn=document.getElementById('themeToggle');
const body=document.body;

if(localStorage.getItem('theme')==='dark'){
  body.classList.add('dark');
  themeBtn.textContent='☀️';
}

themeBtn.onclick=()=>{
  body.classList.toggle('dark');
  if(body.classList.contains('dark')){
    themeBtn.textContent='☀️';
    localStorage.setItem('theme','dark');
  }else{
    themeBtn.textContent='🌙';
    localStorage.setItem('theme','light');
  }
};
</script>

</body>
</html>
