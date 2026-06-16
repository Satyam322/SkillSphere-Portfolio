<?php
session_start();
include('../config/db.php');

// ✅ Protect user
if(!isset($_SESSION['user_id'])){
  header("Location: ../login.php");
  exit();
}

$uid = $_SESSION['user_id'];

// ✅ Add Blog
if(isset($_POST['add'])){
  $title = trim($_POST['title']);
  $content = trim($_POST['content']);
  
  if(!empty($title) && !empty($content)){
      $img = "";
      $video = "";

      // Image Upload
      if(!empty($_FILES['image']['name'])){
          $img = time() . "_" . basename($_FILES['image']['name']);
          $upload_img = "../assets/uploads/blogs/";
          if(!is_dir($upload_img)) mkdir($upload_img, 0777, true);
          move_uploaded_file($_FILES['image']['tmp_name'], $upload_img . $img);
      }

      // Video Upload
      if(!empty($_FILES['video']['name'])){
          $video = time() . "_" . basename($_FILES['video']['name']);
          $upload_vid = "../assets/uploads/videos/";
          if(!is_dir($upload_vid)) mkdir($upload_vid, 0777, true);
          move_uploaded_file($_FILES['video']['tmp_name'], $upload_vid . $video);
      }

      mysqli_query($conn, "INSERT INTO blogs (user_id, title, content, image, video, created_at)
      VALUES ($uid, '$title', '$content', '$img', '$video', NOW())");

      echo "<script>alert('✅ Blog added successfully!'); window.location='blogs.php';</script>";
  } else {
      echo "<script>alert('⚠️ Please fill all required fields.');</script>";
  }
}

// ✅ Update Blog
if(isset($_POST['update'])){
  $id = intval($_POST['blog_id']);
  $title = trim($_POST['title']);
  $content = trim($_POST['content']);
  $set_img = $set_vid = "";

  if(!empty($_FILES['image']['name'])){
      $new_image = time() . "_" . basename($_FILES['image']['name']);
      move_uploaded_file($_FILES['image']['tmp_name'], "../assets/uploads/blogs/" . $new_image);
      $set_img = ", image='$new_image'";
  }

  if(!empty($_FILES['video']['name'])){
      $new_video = time() . "_" . basename($_FILES['video']['name']);
      move_uploaded_file($_FILES['video']['tmp_name'], "../assets/uploads/videos/" . $new_video);
      $set_vid = ", video='$new_video'";
  }

  mysqli_query($conn, "UPDATE blogs SET title='$title', content='$content' $set_img $set_vid WHERE id=$id AND user_id=$uid");
  echo "<script>alert('✅ Blog updated successfully!'); window.location='blogs.php';</script>";
}

// ✅ Delete Blog
if(isset($_GET['del'])){
  $id = intval($_GET['del']);
  $res = mysqli_query($conn, "SELECT image, video FROM blogs WHERE id=$id AND user_id=$uid");
  if($row = mysqli_fetch_assoc($res)){
    if($row['image'] && file_exists("../assets/uploads/blogs/".$row['image'])) unlink("../assets/uploads/blogs/".$row['image']);
    if($row['video'] && file_exists("../assets/uploads/videos/".$row['video'])) unlink("../assets/uploads/videos/".$row['video']);
  }
  mysqli_query($conn, "DELETE FROM blogs WHERE id=$id AND user_id=$uid");
  echo "<script>alert('🗑️ Blog deleted!'); window.location='blogs.php';</script>";
}

// ✅ Fetch Blogs
$data = mysqli_query($conn, "SELECT * FROM blogs WHERE user_id=$uid ORDER BY id DESC");

// ✅ Edit Blog
$edit_blog = null;
if(isset($_GET['edit'])){
  $id = intval($_GET['edit']);
  $res = mysqli_query($conn, "SELECT * FROM blogs WHERE id=$id AND user_id=$uid");
  if(mysqli_num_rows($res) > 0){
    $edit_blog = mysqli_fetch_assoc($res);
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>📝 My Blogs | Smart Portfolio</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- ✅ Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<style>
body{
  background:#f1f5f9;
  font-family:'Poppins',sans-serif;
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
.container{
  max-width:1100px;
  margin-top:60px;
}
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
}
table img,table video{
  width:140px;
  height:100px;
  object-fit:cover;
  border-radius:8px;
  border:1px solid #ccc;
}

/* 🌙 Dark Mode */
body.dark{
  background:linear-gradient(135deg,#0f172a,#1e293b);
  color:#f8fafc;
}
body.dark .navbar{
  background:linear-gradient(90deg,#0f172a,#1e293b);
}
body.dark .card{background:#1e293b;box-shadow:0 4px 12px rgba(0,0,0,0.5);}
body.dark .btn-primary{background:linear-gradient(90deg,#9333ea,#06b6d4);}
body.dark .table{color:#f1f5f9;}
body.dark th{background:#334155 !important;}
body.dark tr:nth-child(even){background:#1e293b !important;}
body.dark input,body.dark textarea{
  background:#334155;
  color:#fff;
  border:1px solid #475569;
}
body.dark input:focus,body.dark textarea:focus{
  background:#1e293b;
  border-color:#9333ea;
  color:#fff;
}
</style>
</head>
<body>

<!-- ✅ Header -->
<div class="navbar">
  <a href="dashboard.php" class="back-btn">⬅ Back Dashboard</a>
  <h4> Manage Blogs</h4>
  <button id="themeToggle" class="theme-toggle">🌙</button>
</div>

<div class="container mt-4">

  <!-- Add/Edit Blog -->
  <div class="card p-4 mb-4">
    <h4 class="fw-semibold text-center mb-3"><?= $edit_blog ? "✏️ Edit Blog" : "➕ Add New Blog" ?></h4>
    <form method="POST" enctype="multipart/form-data" class="row g-3">
      <?php if($edit_blog): ?>
        <input type="hidden" name="blog_id" value="<?= $edit_blog['id'] ?>">
      <?php endif; ?>

      <div class="col-12">
        <input type="text" name="title" class="form-control" placeholder="Blog Title" 
          value="<?= htmlspecialchars($edit_blog['title'] ?? '') ?>" required>
      </div>
      <div class="col-12">
        <textarea name="content" class="form-control" rows="4" placeholder="Write blog content..." required><?= htmlspecialchars($edit_blog['content'] ?? '') ?></textarea>
      </div>
      <div class="col-md-6">
        <label class="form-label fw-semibold">Upload Image</label>
        <input type="file" name="image" class="form-control" accept="image/*">
      </div>
      <div class="col-md-6">
        <label class="form-label fw-semibold">Upload Video (mp4/webm)</label>
        <input type="file" name="video" class="form-control" accept="video/*">
      </div>

      <?php if($edit_blog): ?>
        <div class="col-12">
          <p class="fw-semibold mt-3 mb-1">Current Media:</p>
          <?php if($edit_blog['image']): ?>
            <img src="../assets/uploads/blogs/<?= $edit_blog['image'] ?>" width="120">
          <?php endif; ?>
          <?php if($edit_blog['video']): ?>
            <video width="120" controls><source src="../assets/uploads/videos/<?= $edit_blog['video'] ?>"></video>
          <?php endif; ?>
        </div>
      <?php endif; ?>

      <div class="col-12 text-center mt-2">
        <button type="submit" name="<?= $edit_blog ? 'update' : 'add' ?>" class="btn btn-primary px-4 py-2">
          <?= $edit_blog ? '💾 Update Blog' : '➕ Add Blog' ?>
        </button>
        <?php if($edit_blog): ?>
          <a href="blogs.php" class="btn btn-danger px-4 py-2 ms-2">Cancel</a>
        <?php endif; ?>
      </div>
    </form>
  </div>

  <!-- Blog Table -->
  <div class="card p-3">
    <h5 class="fw-semibold mb-3">📚 Your Blogs</h5>
    <div class="table-responsive">
      <table class="table align-middle table-bordered">
        <thead class="text-white" style="background:#2563eb;">
          <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Image</th>
            <th>Video</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if(mysqli_num_rows($data) > 0): ?>
            <?php while($row=mysqli_fetch_assoc($data)): ?>
              <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><?php if($row['image']): ?><img src="../assets/uploads/blogs/<?= $row['image'] ?>"><?php else: ?>—<?php endif; ?></td>
                <td><?php if($row['video']): ?><video controls><source src="../assets/uploads/videos/<?= $row['video'] ?>"></video><?php else: ?>—<?php endif; ?></td>
                <td>
                  <a href="?edit=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                  <a href="?del=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this blog?')">Delete</a>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="5" class="text-center text-muted">No blogs added yet.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

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
