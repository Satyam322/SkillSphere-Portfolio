<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('../config/db.php');

$query = "SELECT id, name, about_me, profile_photo 
          FROM users 
          WHERE status='active' AND role!='admin' 
          ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Smart Portfolio | Explore</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
:root {
  --bg-color: #f8fafc;
  --text-color: #1e293b;
  --card-bg: #ffffff;
  --card-border: rgba(0,0,0,0.08);
  --primary: #2563eb;
  --secondary: #9333ea;
}
[data-theme="dark"] {
  --bg-color: #0f172a;
  --text-color: #e2e8f0;
  --card-bg: #1e293b;
  --card-border: rgba(255,255,255,0.08);
}

/* Force All Text White in Dark Mode */
[data-theme="dark"] * {
  color: #e2e8f0 !important;
}

/* Buttons white text */
[data-theme="dark"] .btn {
  color: #fff !important;
}

body {
  background: var(--bg-color);
  font-family: 'Poppins', sans-serif;
  color: var(--text-color);
  transition: background-color 0.6s ease, color 0.6s ease;
}

/* Navbar */
.navbar {
  background: var(--card-bg);
  box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}
.navbar-brand {
  font-weight: 700;
  font-size: 1.6rem;
  background: linear-gradient(90deg, var(--primary), var(--secondary));
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}
[data-theme="dark"] .navbar-brand {
  -webkit-text-fill-color: white !important;
}

.btn-login {
  background: linear-gradient(90deg, var(--primary), var(--secondary));
  color: #fff !important;
  border-radius: 25px;
  padding: 8px 18px;
  border: none;
  box-shadow: 0 4px 12px rgba(37,99,235,0.3);
  transition: all 0.3s ease;
}
.btn-login:hover {
  transform: scale(1.05);
  background: linear-gradient(90deg, var(--secondary), #06b6d4);
}

/* Theme toggle */
.theme-toggle {
  background: linear-gradient(135deg, #9333ea, #2563eb);
  color: #fff;
  border: none;
  border-radius: 50%;
  font-size: 1.2rem;
  width: 38px;
  height: 38px;
  margin-left: 10px;
  cursor: pointer;
  transition: all 0.4s ease;
}
.theme-toggle:hover {
  transform: rotate(20deg) scale(1.1);
}

/* Hero Section */
.hero {
  background: linear-gradient(135deg, #dbeafe, #f0abfc, #93c5fd);
  border-radius: 0 0 40px 40px;
  box-shadow: 0 6px 20px rgba(0,0,0,0.1);
  padding: 60px 0 50px;
}

/* For dark mode hero background */
[data-theme="dark"] .hero {
  background: linear-gradient(135deg, #1e1b4b, #312e81);
}

.hero h1 {
  font-weight: 800;
  font-size: 2.4rem;
  background: linear-gradient(90deg, var(--primary), var(--secondary));
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}
[data-theme="dark"] .hero h1 {
  -webkit-text-fill-color: white !important;
}

.hero p {
  font-size: 1.05rem;
  opacity: 0.9;
}

/* Search */
.search-box {
  position: relative;
  width: 75%;
  margin: 0 auto;
}
.search-box i {
  position: absolute;
  top: 50%;
  left: 20px;
  transform: translateY(-50%);
  color: #9333ea;
  font-size: 1.2rem;
}
.search-box input {
  border-radius: 50px;
  padding: 14px 50px;
  width: 100%;
  background: var(--card-bg);
  color: var(--text-color);
}

/* Cards */
.card {
  background: var(--card-bg);
  border: 1px solid var(--card-border);
  border-radius: 18px;
  text-align: center;
  transition: all 0.4s ease;
  box-shadow: 0 8px 20px rgba(0,0,0,0.08);
}
.card:hover {
  transform: translateY(-8px);
}

.card-img-top {
  width: 100px;
  height: 100px;
  object-fit: cover;
  border-radius: 50%;
  margin: 25px auto 15px;
  border: 3px solid var(--secondary);
}

.card a.btn {
  background: linear-gradient(90deg, var(--primary), var(--secondary));
  color: #fff !important;
  border-radius: 25px;
}

/* Footer */
footer {
  text-align: center;
  padding: 40px 0;
  border-top: 1px solid var(--card-border);
}
</style>
</head>

<body data-theme="light">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg sticky-top">
  <div class="container">
    <a class="navbar-brand" href="#">SkillSphere</a>

    <div class="collapse navbar-collapse justify-content-end">
      <ul class="navbar-nav align-items-center gap-3">
        <li class="nav-item"><a class="nav-link active" href="#">Home</a></li>
        <li class="nav-item d-flex align-items-center">
          <a href="../login.php" class="btn btn-login">Sign In</a>
          <button class="theme-toggle" id="themeToggle">🌙</button>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- Hero -->
<section class="hero text-center text-lg-start">
  <div class="container">
    <div class="row align-items-center justify-content-center">

      <div class="col-lg-6 col-md-10">
        <h1>Smart Personal Portfolio with Admin Control</h1>
        <p>Manage your skills, projects, and achievements — all in one place with smart admin control.</p>
        <a href="../login.php" class="btn btn-login px-4 py-2">Get Started 🚀</a>
      </div>

      <div class="col-lg-5 col-md-8 mt-4 mt-lg-0 text-center">
        <img src="assets/images/hero-portfolio.png" class="img-fluid hero-img">
      </div>

    </div>
  </div>
</section>

<!-- Search -->
<div class="container text-center my-5">
  <div class="search-box">
    <i class="fa fa-search"></i>
    <input type="text" id="searchInput" placeholder="Search creators, skills...">
  </div>
</div>

<!-- User Cards -->
<div class="container pb-5">
  <div class="row g-4" id="userList">

    <?php while($row = mysqli_fetch_assoc($result)): ?>
    <div class="col-12 col-sm-6 col-lg-4 user-card">
      <div class="card h-100">
        <img src="<?= $row['profile_photo'] ? '../assets/images/'.$row['profile_photo'] : 'https://via.placeholder.com/150' ?>" class="card-img-top">
        <div class="card-body">
          <h5 class="card-title"><?= htmlspecialchars($row['name']) ?></h5>
          <p class="card-text"><?= htmlspecialchars(substr($row['about_me'] ?? '', 0, 70)) ?>...</p>
          <a href="view.php?user=<?= $row['id'] ?>" class="btn">View Portfolio</a>
        </div>
      </div>
    </div>
    <?php endwhile; ?>

  </div>
</div>

<footer>
  © 2025 <strong style="color:var(--secondary)">SkillSphere</strong> | Designed by Satyam & Sandeep 💜
</footer>

<script>
// search filter
document.getElementById('searchInput').addEventListener('keyup', function(){
  let filter = this.value.toLowerCase();
  document.querySelectorAll('.user-card').forEach(card=>{
    let name = card.querySelector('.card-title').innerText.toLowerCase();
    let about = card.querySelector('.card-text').innerText.toLowerCase();
    card.style.display = (name.includes(filter) || about.includes(filter)) ? '' : 'none';
  });
});

// theme toggle
const toggle=document.getElementById('themeToggle');
const body=document.body;
let saved=localStorage.getItem('theme');
if(saved){ body.setAttribute('data-theme', saved); toggle.textContent = saved==='light'?'🌙':'☀️'; }

toggle.onclick=()=>{
  let theme = body.getAttribute('data-theme')==='light'?'dark':'light';
  body.setAttribute('data-theme', theme);
  toggle.textContent = theme==='light'?'🌙':'☀️';
  localStorage.setItem('theme', theme);
}
</script>

</body>
</html>
