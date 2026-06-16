<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('../config/db.php');
include('../config/auth_check.php');

// ✅ Allow only logged-in users
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch existing data
$result = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'");
$user = mysqli_fetch_assoc($result);

// Handle form submission
if (isset($_POST['update_about'])) {
    $about = mysqli_real_escape_string($conn, $_POST['about_me']);
    $education = mysqli_real_escape_string($conn, $_POST['education']);

    $profile_photo = $user['profile_photo'];
    $resume_pdf = $user['resume_pdf'];

    // Ensure upload folders exist
    if (!is_dir("../assets/images")) mkdir("../assets/images", 0777, true);
    if (!is_dir("../assets/resume")) mkdir("../assets/resume", 0777, true);

    // 📸 Upload profile photo
    if (!empty($_FILES['profile_photo']['name'])) {
        $img_ext = strtolower(pathinfo($_FILES['profile_photo']['name'], PATHINFO_EXTENSION));
        $allowed_img = ['jpg', 'jpeg', 'png'];

        if (in_array($img_ext, $allowed_img)) {
            $img_name = 'profile_' . $user_id . '_' . time() . '.' . $img_ext;
            $target = "../assets/images/" . $img_name;
            if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $target)) {
                $profile_photo = $img_name;
            }
        }
    }

    // 📄 Upload Resume PDF
    if (!empty($_FILES['resume_pdf']['name'])) {
        $pdf_ext = strtolower(pathinfo($_FILES['resume_pdf']['name'], PATHINFO_EXTENSION));
        if ($pdf_ext === 'pdf') {
            $pdf_name = 'resume_' . $user_id . '_' . time() . '.pdf';
            $target_pdf = "../assets/resume/" . $pdf_name;
            if (move_uploaded_file($_FILES['resume_pdf']['tmp_name'], $target_pdf)) {
                $resume_pdf = $pdf_name;
            }
        }
    }

    // Update database
    $query = "UPDATE users SET about_me='$about', education='$education', profile_photo='$profile_photo', resume_pdf='$resume_pdf' WHERE id='$user_id'";
    if (mysqli_query($conn, $query)) {
        $success = "✅ Profile updated successfully!";
        $user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'"));
    } else {
        $error = "❌ Something went wrong.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>👤 About Me</title>
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

/* ✅ Main Layout */
.container{max-width:900px;margin-top:60px;}
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
  transform:scale(1.05);
}
.profile-preview img{
  width:100px;
  height:100px;
  border-radius:50%;
  object-fit:cover;
  border:2px solid #e2e8f0;
}
a.download{
  color:#2563eb;
  text-decoration:none;
  font-weight:500;
}
a.download:hover{text-decoration:underline;}
.alert{border-radius:10px;}

/* 🌙 Dark Mode */
body.dark{
  background:linear-gradient(135deg,#0f172a,#1e293b);
  color:#f8fafc;
}
body.dark .navbar{
  background:linear-gradient(90deg,#0f172a,#1e293b);
}
body.dark .card{
  background:#1e293b;
  box-shadow:0 4px 15px rgba(0,0,0,0.6);
}
body.dark .btn-primary{
  background:linear-gradient(90deg,#9333ea,#06b6d4);
}
body.dark input,body.dark textarea{
  background:#334155;
  color:#fff;
  border:1px solid #475569;
}
body.dark input:focus,body.dark textarea:focus{
  background:#1e293b;
  border-color:#9333ea;
}
body.dark a.download{color:#a78bfa;}
</style>
</head>
<body>

<!-- ✅ Header -->
<div class="navbar">
  <a href="dashboard.php" class="back-btn">⬅ Back Dashboard</a>
  <h4> About Me</h4>
  <button id="themeToggle" class="theme-toggle">🌙</button>
</div>

<div class="container mt-4">
  <?php if(isset($success)): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
  <?php if(isset($error)): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>

  <div class="card p-4">
    <h4 class="fw-semibold text-center mb-3">👤 About Section</h4>
    <form method="POST" enctype="multipart/form-data" class="row g-3">

      <div class="col-12">
        <label class="form-label fw-semibold">About Me</label>
        <textarea name="about_me" rows="5" class="form-control" placeholder="Write about yourself..."><?= htmlspecialchars($user['about_me'] ?? '') ?></textarea>
      </div>

      <div class="col-md-12">
        <label class="form-label fw-semibold">Education</label>
        <input type="text" name="education" class="form-control" value="<?= htmlspecialchars($user['education'] ?? '') ?>" placeholder="e.g., BSc IT, RJ College, Mumbai">
      </div>

      <div class="col-md-12">
        <label class="form-label fw-semibold">Profile Photo</label>
        <div class="d-flex align-items-center gap-4 profile-preview mt-2">
          <?php if(!empty($user['profile_photo']) && file_exists("../assets/images/".$user['profile_photo'])): ?>
            <img src="../assets/images/<?= htmlspecialchars($user['profile_photo']) ?>" alt="Profile">
          <?php else: ?>
            <img src="../assets/images/default-profile.png" alt="Default">
          <?php endif; ?>
          <input type="file" name="profile_photo" class="form-control w-50" accept="image/*">
        </div>
      </div>

      <div class="col-md-12 mt-3">
        <label class="form-label fw-semibold">Resume (PDF)</label>
        <?php if(!empty($user['resume_pdf']) && file_exists("../assets/resume/".$user['resume_pdf'])): ?>
          <p class="mt-2">📄 <a href="../assets/resume/<?= htmlspecialchars($user['resume_pdf']) ?>" target="_blank" class="download">View Current Resume</a></p>
        <?php endif; ?>
        <input type="file" name="resume_pdf" class="form-control w-50" accept="application/pdf">
      </div>

      <div class="col-12 text-center mt-4">
        <button type="submit" name="update_about" class="btn btn-primary px-4 py-2">💾 Save Changes</button>
      </div>
    </form>
  </div>
</div>

<!-- 🌙 Dark Mode Script -->
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
