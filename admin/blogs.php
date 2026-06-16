<?php
include('../config/auth_check.php');
include('../config/db.php');

// ✅ Add Blog (Image + Video)
if (isset($_POST['add_blog'])) {
  $title = mysqli_real_escape_string($conn, $_POST['title']);
  $content = mysqli_real_escape_string($conn, $_POST['content']);
  $img = $vid = "";

  if (!empty($_FILES['image']['name'])) {
    $img = time() . "_" . basename($_FILES['image']['name']);
    move_uploaded_file($_FILES['image']['tmp_name'], "../assets/uploads/blogs/" . $img);
  }

  if (!empty($_FILES['video']['name'])) {
    $vid = time() . "_" . basename($_FILES['video']['name']);
    move_uploaded_file($_FILES['video']['tmp_name'], "../assets/uploads/videos/" . $vid);
  }

  mysqli_query($conn, "INSERT INTO blogs (user_id, title, content, image, video, created_at)
                       VALUES (1, '$title', '$content', '$img', '$vid', NOW())");
  echo "<script>alert('✅ Blog Added Successfully!'); window.location='blogs.php';</script>";
}

// ✅ Delete Blog (Image + Video)
if (isset($_GET['delete'])) {
  $id = intval($_GET['delete']);
  $res = mysqli_query($conn, "SELECT image, video FROM blogs WHERE id=$id");
  if ($row = mysqli_fetch_assoc($res)) {
    if ($row['image'] && file_exists("../assets/uploads/blogs/" . $row['image'])) unlink("../assets/uploads/blogs/" . $row['image']);
    if ($row['video'] && file_exists("../assets/uploads/videos/" . $row['video'])) unlink("../assets/uploads/videos/" . $row['video']);
  }
  mysqli_query($conn, "DELETE FROM blogs WHERE id=$id");
  echo "<script>alert('🗑️ Blog Deleted Successfully!'); window.location='blogs.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>📝 Manage Blogs | Smart Portfolio</title>
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
    <h4 class="fw-bold text-primary m-0">📝 Manage Blogs</h4>
    <button id="themeToggle" class="theme-toggle">🌙</button>
  </div>
</nav>

<!-- ✅ Main Container -->
<div class="container my-5">
  <div class="card border-0 shadow p-4 rounded-4">

    <!-- Add Blog Form -->
    <form method="POST" enctype="multipart/form-data" class="mb-5">
      <div class="mb-3">
        <label class="form-label fw-semibold">Blog Title</label>
        <input type="text" name="title" class="form-control" placeholder="Enter Blog Title" required>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Content</label>
        <textarea name="content" class="form-control" rows="5" placeholder="Write Blog Content..." required></textarea>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Upload Image</label>
        <input type="file" name="image" accept="image/*" class="form-control">
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Upload Video</label>
        <input type="file" name="video" accept="video/*" class="form-control">
      </div>

      <button type="submit" name="add_blog" class="btn btn-primary mt-2">➕ Add Blog</button>
    </form>

    <!-- Blog Table -->
    <div class="table-responsive">
      <table class="table table-bordered align-middle text-center">
        <thead class="table-primary">
          <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Image</th>
            <th>Video</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $res = mysqli_query($conn, "SELECT * FROM blogs ORDER BY id DESC");
          if (mysqli_num_rows($res) > 0) {
            while ($row = mysqli_fetch_assoc($res)) {
              echo "
              <tr>
                <td>{$row['id']}</td>
                <td class='fw-semibold text-primary'>{$row['title']}</td>
                <td>";
                if ($row['image']) {
                  echo "<img src='../assets/uploads/blogs/{$row['image']}' width='160' height='120' 
                        style='object-fit:cover;border-radius:8px;border:1px solid #ccc;box-shadow:0 2px 5px rgba(0,0,0,0.1);'>";
                } else { echo "—"; }
              echo "</td>
                <td>";
                if ($row['video']) {
                  echo "<video width='160' height='120' controls
                        style='border-radius:8px;border:1px solid #ccc;box-shadow:0 2px 5px rgba(0,0,0,0.1);object-fit:cover;'>
                        <source src='../assets/uploads/videos/{$row['video']}' type='video/mp4'>
                      </video>";
                } else { echo "—"; }
              echo "</td>
                <td>
                  <a href='blogs.php?delete={$row['id']}' 
                     class='text-danger fw-semibold'
                     onclick='return confirm(\"Delete this blog?\")'>
                     Delete
                  </a>
                </td>
              </tr>";
            }
          } else {
            echo "<tr><td colspan='5' class='text-muted py-4'>No blogs added yet 😔</td></tr>";
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
