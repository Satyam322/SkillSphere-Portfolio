<?php
session_start();
include('../config/db.php');

// ✅ Only logged-in user allowed
if(!isset($_SESSION['user_id'])){
  header("Location: ../login.php");
  exit();
}

$user_id = $_SESSION['user_id'];

// Handle password update
if(isset($_POST['update_password'])){
  $current = trim($_POST['current_password']);
  $new = trim($_POST['new_password']);
  $confirm = trim($_POST['confirm_password']);

  $res = mysqli_query($conn, "SELECT password FROM users WHERE id=$user_id");
  $user = mysqli_fetch_assoc($res);

  if(empty($current) || empty($new) || empty($confirm)){
    $error = "⚠️ Please fill all fields.";
  } elseif(!password_verify($current, $user['password']) && $current !== $user['password']){
    $error = "❌ Current password is incorrect.";
  } elseif($new !== $confirm){
    $error = "❌ New passwords do not match.";
  } elseif(strlen($new) < 6){
    $error = "⚠️ Password should be at least 6 characters.";
  } else {
    $hashed = password_hash($new, PASSWORD_DEFAULT);
    if(mysqli_query($conn, "UPDATE users SET password='$hashed' WHERE id=$user_id")){
      $success = "✅ Password updated successfully!";
    } else {
      $error = "❌ Something went wrong.";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>🔒 Change Password | Smart Portfolio</title>
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
.navbar h4{
  margin:0;
  font-weight:600;
}
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
.navbar .back-btn:hover{
  background:#ffffff55;
}
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
.theme-toggle:hover{
  transform:scale(1.1);
}
.card{
  width:420px;
  border:none;
  border-radius:15px;
  box-shadow:0 6px 20px rgba(0,0,0,0.1);
  margin:80px auto;
  padding:25px 30px;
  transition:background 0.4s,box-shadow 0.4s;
}
.btn-primary{
  background:linear-gradient(90deg,#2563eb,#9333ea);
  border:none;
}
.btn-primary:hover{
  background:linear-gradient(90deg,#9333ea,#06b6d4);
  transform:scale(1.02);
}
.form-control:focus{
  border-color:#9333ea;
  box-shadow:0 0 0 3px rgba(147,51,234,0.2);
}
.meter{
  height:10px;
  border-radius:5px;
  background:#e5e7eb;
  margin-top:-5px;
  margin-bottom:10px;
  overflow:hidden;
}
.meter div{
  height:100%;
  border-radius:5px;
  transition:width 0.3s ease;
}
.meter.weak div{width:33%;background:#dc2626;}
.meter.medium div{width:66%;background:#f59e0b;}
.meter.strong div{width:100%;background:#16a34a;}

/* 🌙 Dark Mode */
body.dark{
  background:linear-gradient(135deg,#0f172a,#1e293b);
  color:#f8fafc;
}
body.dark .navbar{
  background:linear-gradient(90deg,#0f172a,#1e293b);
  color:#f8fafc;
}
body.dark .card{
  background:#1e293b;
  box-shadow:0 6px 15px rgba(0,0,0,0.5);
}
body.dark .form-control{
  background:#334155;
  color:#fff;
  border:1px solid #475569;
}
body.dark .form-control:focus{
  background:#1e293b;
  border-color:#9333ea;
  color:#fff;
}
body.dark .btn-primary{
  background:linear-gradient(90deg,#9333ea,#06b6d4);
}
body.dark .navbar .back-btn{
  background:#9333ea;
  color:#fff;
}
</style>
</head>

<body>

<!-- ✅ Header -->
<div class="navbar">
  <a href="dashboard.php" class="back-btn">⬅ Back Dashboard</a>
  <h4>Change Password</h4>
  <button id="themeToggle" class="theme-toggle">🌙</button>
</div>

<!-- ✅ Card Section -->
<div class="card text-center">
  <h2 class="fw-bold text-primary mb-3">🔒 Change Password</h2>

  <?php if(isset($error)): ?>
    <div class="alert alert-danger py-2"><?= $error ?></div>
  <?php endif; ?>
  <?php if(isset($success)): ?>
    <div class="alert alert-success py-2"><?= $success ?></div>
  <?php endif; ?>

  <form method="POST" class="text-start">
    <label class="fw-semibold mt-2">Current Password</label>
    <input type="password" name="current_password" class="form-control" placeholder="Enter current password" required>

    <label class="fw-semibold mt-3">New Password</label>
    <input type="password" id="new_password" name="new_password" class="form-control" placeholder="Enter new password" required>
    <div class="meter mt-1" id="meter"><div></div></div>

    <label class="fw-semibold mt-3">Confirm New Password</label>
    <input type="password" name="confirm_password" class="form-control" placeholder="Confirm new password" required>

    <button type="submit" name="update_password" class="btn btn-primary w-100 mt-4">💾 Update Password</button>
  </form>
</div>

<script>
const passwordInput=document.getElementById('new_password');
const meter=document.getElementById('meter');
passwordInput.addEventListener('input',()=>{
  const val=passwordInput.value;
  meter.className='meter';
  let strength=0;
  if(val.length>=6) strength++;
  if(/[A-Z]/.test(val)) strength++;
  if(/[0-9]/.test(val)) strength++;
  if(/[@$!%*?&]/.test(val)) strength++;
  if(strength<=1) meter.classList.add('weak');
  else if(strength===2||strength===3) meter.classList.add('medium');
  else meter.classList.add('strong');
});

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
  }else{
    themeBtn.textContent='🌙';
    localStorage.setItem('theme','light');
  }
});
</script>
</body>
</html>
