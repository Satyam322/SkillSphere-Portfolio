<?php
include '../config/db.php';

$uid = intval($_GET['user'] ?? 0);
if(!$uid) { header("Location: index.php"); exit; }

// Fetch user
$stmt = mysqli_prepare($conn, "SELECT id,name,about_me,education,profile_photo,resume_pdf FROM users WHERE id=? AND status='active' LIMIT 1");
mysqli_stmt_bind_param($stmt, "i", $uid);
mysqli_stmt_execute($stmt);
$userRes = mysqli_stmt_get_result($stmt);
if(mysqli_num_rows($userRes) == 0){ echo "User not found"; exit; }
$user = mysqli_fetch_assoc($userRes);

// Fetch projects, skills, blogs
$projects = mysqli_query($conn, "SELECT * FROM projects WHERE user_id=$uid ORDER BY id DESC");
$skills = mysqli_query($conn, "SELECT * FROM skills WHERE user_id=$uid ORDER BY percentage DESC");
$blogs = mysqli_query($conn, "SELECT * FROM blogs WHERE user_id=$uid ORDER BY id DESC");
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title><?= htmlspecialchars($user['name']) ?> - Smart Portfolio</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
body {
  font-family: 'Poppins', sans-serif;
  background: linear-gradient(120deg, #dbeafe, #f3e8ff);
  transition: background 0.4s ease, color 0.4s ease;
}

/* Container */
.container {
  max-width: 900px;
  margin: 50px auto;
  background: rgba(255, 255, 255, 0.9);
  backdrop-filter: blur(10px);
  border-radius: 16px;
  padding: 35px 40px;
  box-shadow: 0 6px 25px rgba(0,0,0,0.12);
  border: 2px solid #e0e7ff;
}

/* Headings */
h1 {
  color: #1e3a8a;
  font-weight: 700;
  font-size: 2rem;
}
h3 {
  color: #2563eb;
  font-weight: 600;
  margin-top: 35px;
  border-left: 4px solid #9333ea;
  padding-left: 10px;
}

/* Sections */
.section {
  border: 2px dashed #c7d2fe;
  border-radius: 12px;
  padding: 20px 25px;
  margin-top: 20px;
  background: #fafafa;
}

/* Profile */
.profile {
  width: 170px;
  height: 170px;
  object-fit: cover;
  border-radius: 50%;
  border: 4px solid #9333ea;
  box-shadow: 0 6px 18px rgba(0,0,0,0.12);
}

/* BLOG UPDATED DESIGN */
.blog-card {
  background: #ffffff;
  border-radius: 14px;
  padding: 15px;
  margin-bottom: 18px;
  border: 1px solid rgba(0,0,0,0.06);
  box-shadow: 0 4px 14px rgba(0,0,0,0.08);
}
.dark-mode .blog-card {
  background: rgba(30,30,40,0.85);
  border: 1px solid rgba(255,255,255,0.06);
}
.blog-title {
  font-size: 1rem;
  font-weight: 600;
  color: #1e293b;
  margin-bottom: 10px;
}
.dark-mode .blog-title {
  color: #f1f5f9;
}

/* Clean small media */
.blog-media {
  width: 100%;
  max-width: 360px;
  display: block;
  border-radius: 12px;
  margin: 10px auto;
  box-shadow: 0 4px 10px rgba(0,0,0,0.15);
}

/* Contact Form */
form {
  margin-top: 15px;
  background: #f8fafc;
  padding: 20px;
  border-radius: 14px;
  box-shadow: 0 3px 8px rgba(0,0,0,0.07);
  border: 2px dashed #c7d2fe;
}
input, textarea, button {
  width:100%;
  padding:10px;
  margin:8px 0;
  border-radius:8px;
  border:1px solid #cbd5e1;
  font-family:'Poppins',sans-serif;
  font-size:15px;
}
button {
  background: linear-gradient(90deg, #2563eb, #9333ea);
  color:white;
  font-weight:600;
  cursor:pointer;
  border:none;
  transition:0.3s;
  border-radius: 25px;
}
button:hover {
  background: linear-gradient(90deg, #9333ea, #06b6d4);
  transform:scale(1.03);
}

/* Dark mode */
.dark-mode {
  background: linear-gradient(120deg, #1e293b, #0f172a);
  color: #e2e8f0;
}
.dark-mode .container {
  background: rgba(30,41,59,0.9);
  color: #f1f5f9;
  border-color: #334155;
}
.dark-mode .section {
  background: #1e293b;
  border-color: #475569;
}

/* Toggle */
.toggle-btn {
  position: fixed;
  top: 20px;
  right: 30px;
  background: linear-gradient(90deg, #2563eb, #9333ea);
  color: white;
  border: none;
  border-radius: 50%;
  width: 50px;
  height: 50px;
  font-size: 22px;
  cursor: pointer;
  box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}
.toggle-btn:hover {
  transform: scale(1.1);
  background: linear-gradient(90deg, #9333ea, #06b6d4);
}

/* Back link */
.back-link {
  display:inline-block;
  margin-bottom:15px;
  font-weight:500;
  color:#2563eb;
}
.back-link:hover { color:#9333ea; text-decoration:none; }
</style>
</head>
<body>

<!-- 🌙 Theme Toggle -->
<button class="toggle-btn" id="themeToggle">🌙</button>

<div class="container">
  <a href="index.php" class="back-link">← Back to All Portfolios</a>
  <h1><?= htmlspecialchars($user['name']) ?>’s Portfolio</h1>

  <!-- About -->
  <div class="section">
    <div class="row align-items-center mt-4">
      <div class="col-md-4 text-center">
        <img src="<?= !empty($user['profile_photo']) ? '../assets/images/'.htmlspecialchars($user['profile_photo']) : '../assets/images/default-profile.png' ?>" class="profile mb-3">
        <?php if(!empty($user['resume_pdf']) && file_exists('../assets/resume/'.$user['resume_pdf'])): ?>
          <a href="../assets/resume/<?= htmlspecialchars($user['resume_pdf']) ?>" target="_blank" class="btn btn-primary">📄 Resume</a>
        <?php endif; ?>
      </div>
      <div class="col-md-8">
        <h3>About Me</h3>
        <p><?= nl2br(htmlspecialchars($user['about_me'] ?: 'No details added yet.')) ?></p>

        <h3>Education</h3>
        <p><?= htmlspecialchars($user['education'] ?: '—') ?></p>
      </div>
    </div>
  </div>

  <!-- Projects -->
  <h3>Projects</h3>
  <div class="section">
    <?php if(mysqli_num_rows($projects) > 0): ?>
      <?php while($p = mysqli_fetch_assoc($projects)): ?>
        <div class="project-card">
          <h5><?= htmlspecialchars($p['title']) ?></h5>
          <p><?= nl2br(htmlspecialchars($p['description'])) ?></p>
          <?php if(!empty($p['link'])): ?>
            <a href="<?= htmlspecialchars($p['link']) ?>" target="_blank">🔗 View Project</a>
          <?php endif; ?>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p>No projects yet.</p>
    <?php endif; ?>
  </div>

  <!-- Skills -->
  <h3>Skills</h3>
  <div class="section">
    <div class="row">
      <?php if(mysqli_num_rows($skills) > 0): ?>
        <?php while($s = mysqli_fetch_assoc($skills)): ?>
          <div class="col-md-4">
            <div class="p-2 bg-light border rounded mb-2">
              <?= htmlspecialchars($s['skill_name']) ?> — <?= intval($s['percentage']) ?>%
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p>No skills added yet.</p>
      <?php endif; ?>
    </div>
  </div>

  <!-- BLOG SECTION UPDATED -->
  <h3>Blogs</h3>
  <div class="section">
    <?php if(mysqli_num_rows($blogs) > 0): ?>
      <?php while($b = mysqli_fetch_assoc($blogs)): ?>
        <div class="blog-card">

          <div class="blog-title"><?= htmlspecialchars($b['title']) ?></div>

          <?php if(!empty($b['video'])): ?>
            <video controls class="blog-media">
              <source src="../assets/uploads/videos/<?= htmlspecialchars($b['video']) ?>" type="video/mp4">
            </video>
          <?php endif; ?>

          <?php if(!empty($b['image'])): ?>
            <img src="../assets/uploads/blogs/<?= htmlspecialchars($b['image']) ?>" class="blog-media">
          <?php endif; ?>

          <p style="color:#6b7280;">
            <?= nl2br(htmlspecialchars(substr($b['content'],0,250))) ?>...
          </p>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p>No blogs yet.</p>
    <?php endif; ?>
  </div>

  <!-- Contact -->
  <h3>Contact <?= htmlspecialchars($user['name']) ?></h3>
  <div class="section">
    <p>Fill out the form below to contact <b><?= htmlspecialchars($user['name']) ?></b> directly 👇</p>
    <form method="POST" action="contact_submit.php">
      <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
      <input name="name" placeholder="Your name" required>
      <input name="email" type="email" placeholder="Your email" required>
      <input name="subject" placeholder="Subject" required>
      <textarea name="message" rows="5" placeholder="Write your message..." required></textarea>
      <button type="submit">Send Message</button>
    </form>
  </div>
</div>

<script>
const toggleBtn = document.getElementById('themeToggle');
const body = document.body;
if(localStorage.getItem('theme') === 'dark') {
  body.classList.add('dark-mode');
  toggleBtn.textContent = '☀️';
}
toggleBtn.onclick = () => {
  body.classList.toggle('dark-mode');
  if(body.classList.contains('dark-mode')) {
    toggleBtn.textContent = '☀️';
    localStorage.setItem('theme', 'dark');
  } else {
    toggleBtn.textContent = '🌙';
    localStorage.setItem('theme', 'light');
  }
};
</script>

</body>
</html>
