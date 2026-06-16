<?php
session_start();
include('../config/db.php');

// ✅ Protect user
if(!isset($_SESSION['user_id'])){
  header("Location: ../login.php");
  exit();
}

$uid = $_SESSION['user_id'];

// ✅ Fetch existing homepage data
$home = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM homepage WHERE user_id='$uid' LIMIT 1"));

// ✅ Add / Update homepage data
if(isset($_POST['save_home'])){
  $name = mysqli_real_escape_string($conn, $_POST['name']);
  $tagline = mysqli_real_escape_string($conn, $_POST['tagline']);
  $about = mysqli_real_escape_string($conn, $_POST['about']);

  $img = $home['profile_image'] ?? '';

  // ✅ Handle new image upload
  if(isset($_FILES['profile_image']['name']) && $_FILES['profile_image']['name'] != ''){
    $img = time() . "_" . basename($_FILES['profile_image']['name']);
    $path = "../assets/uploads/profile/";
    if(!is_dir($path)) mkdir($path, 0777, true);
    move_uploaded_file($_FILES['profile_image']['tmp_name'], $path . $img);
  }

  // ✅ If user record exists → update, else insert new
  if($home){
    $query = "UPDATE homepage SET name='$name', tagline='$tagline', about='$about', profile_image='$img' WHERE user_id='$uid'";
  } else {
    $query = "INSERT INTO homepage (user_id, name, tagline, about, profile_image) VALUES ('$uid', '$name', '$tagline', '$about', '$img')";
  }

  if(mysqli_query($conn, $query)){
    echo "<script>alert('✅ Home details updated successfully!'); window.location='home.php';</script>";
  } else {
    echo "<script>alert('❌ Database error while saving!');</script>";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>🏠 Home Page Manager | Smart Portfolio</title>
<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Poppins',sans-serif;}
body{background:#f4f7fa;min-height:100vh;display:flex;flex-direction:column;align-items:center;padding:80px 20px;}
.container{
  background:white;
  border-radius:16px;
  box-shadow:0 4px 20px rgba(0,0,0,0.1);
  width:95%;
  max-width:950px;
  padding:30px 40px;
}
.header{
  display:flex;
  justify-content:space-between;
  align-items:center;
  margin-bottom:25px;
}
.header h2{font-size:24px;color:#1e3a8a;}
.back-btn{
  background:#2563eb;
  color:white;
  padding:8px 16px;
  border:none;
  border-radius:6px;
  text-decoration:none;
  font-size:14px;
}
.back-btn:hover{background:#1d4ed8;}
form{display:flex;flex-direction:column;gap:15px;}
input,textarea{
  width:100%;
  padding:12px;
  border:1px solid #ccc;
  border-radius:8px;
  font-size:14px;
}
textarea{resize:none;height:100px;}
.profile-preview{
  width:120px;
  height:120px;
  border-radius:50%;
  border:3px solid #2563eb;
  object-fit:cover;
  margin-top:10px;
}
button{
  background:#2563eb;
  color:white;
  padding:12px;
  border:none;
  border-radius:8px;
  cursor:pointer;
  font-size:15px;
  font-weight:500;
}
button:hover{background:#1d4ed8;}
.info-box{
  background:#f1f5ff;
  border-radius:10px;
  padding:15px;
  margin-top:20px;
  border-left:4px solid #2563eb;
}
</style>
</head>
<body>

<div class="container">
  <div class="header">
    <h2>🏠 Manage Home Page</h2>
    <a href="dashboard.php" class="back-btn">⬅ Back to Dashboard</a>
  </div>

  <form method="POST" enctype="multipart/form-data">
    <label>Full Name:</label>
    <input type="text" name="name" placeholder="Your Name" value="<?= htmlspecialchars($home['name'] ?? '') ?>" required>

    <label>Tagline:</label>
    <input type="text" name="tagline" placeholder="e.g. Full Stack Developer | Designer" value="<?= htmlspecialchars($home['tagline'] ?? '') ?>">

    <label>Short About:</label>
    <textarea name="about" placeholder="Write a short intro about yourself..."><?= htmlspecialchars($home['about'] ?? '') ?></textarea>

    <label>Profile Image:</label>
    <?php if(!empty($home['profile_image'])): ?>
      <img src="../assets/uploads/profile/<?= $home['profile_image'] ?>" class="profile-preview" alt="Profile Image">
    <?php else: ?>
      <p style="color:#64748b;">No image uploaded yet.</p>
    <?php endif; ?>
    <input type="file" name="profile_image" accept="image/*">

    <button type="submit" name="save_home">💾 Save Changes</button>
  </form>

  <?php if($home): ?>
  <div class="info-box">
    <h3>✅ Current Home Preview</h3>
    <p><b>Name:</b> <?= htmlspecialchars($home['name']) ?></p>
    <p><b>Tagline:</b> <?= htmlspecialchars($home['tagline']) ?></p>
    <p><b>About:</b> <?= nl2br(htmlspecialchars($home['about'])) ?></p>
  </div>
  <?php endif; ?>
</div>

</body>
</html>
