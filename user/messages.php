<?php
session_start();
include('../config/db.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

// ✅ Protect user
if(!isset($_SESSION['user_id'])){
  header("Location: ../login.php");
  exit();
}

$uid = $_SESSION['user_id'];
$user_email = ""; // for sending mail

// ✅ Get user email from DB
$res = mysqli_query($conn, "SELECT email, name FROM users WHERE id=$uid");
if($row = mysqli_fetch_assoc($res)){
  $user_email = $row['email'];
  $user_name = $row['name'];
}

// ✅ Delete Message
if(isset($_GET['del'])){
  $id = intval($_GET['del']);
  mysqli_query($conn, "DELETE FROM contact_messages WHERE id=$id AND user_id=$uid");
  echo "<script>alert('🗑️ Message deleted!'); window.location='messages.php';</script>";
  exit;
}

// ✅ Reply to Visitor
if(isset($_POST['reply'])){
  $msg_id = intval($_POST['msg_id']);
  $to = trim($_POST['email']);
  $reply_msg = trim($_POST['reply_msg']);

  $mail = new PHPMailer(true);
  try {
      $mail->isSMTP();
      $mail->Host = 'smtp.gmail.com';
      $mail->SMTPAuth = true;
      $mail->Username = 'satyamv122005@gmail.com'; // ✅ Use single Gmail for all outgoing mails
      $mail->Password = 'yzjh sztq hzel txtp'; // ✅ App Password
      $mail->SMTPSecure = 'tls';
      $mail->Port = 587;

      // ✅ From = user’s identity
      $mail->setFrom('satyamv122005@gmail.com', "Reply from $user_name (Smart Portfolio)");
      $mail->addAddress($to);
      $mail->isHTML(true);
      $mail->Subject = "Reply from $user_name (SkillSphere)";
      $mail->Body = nl2br($reply_msg);

      $mail->send();

      mysqli_query($conn, "UPDATE contact_messages SET replied=1 WHERE id=$msg_id");
      echo "<script>alert('✅ Reply sent successfully!'); window.location='messages.php';</script>";
  } catch (Exception $e) {
      echo "<script>alert('❌ Error sending email: {$mail->ErrorInfo}');</script>";
  }
}

// ✅ Fetch Visitor Messages
$messages = mysqli_query($conn, "SELECT * FROM contact_messages WHERE user_id=$uid ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>📩 Visitor Messages | Smart Portfolio</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
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
  color:#fff;padding:12px 20px;
  display:flex;justify-content:space-between;
  align-items:center;box-shadow:0 4px 10px rgba(0,0,0,0.2);
}
.navbar h4{margin:0;font-weight:600;}
.navbar .back-btn{
  background:#ffffff33;color:#fff;border:none;
  border-radius:6px;padding:8px 14px;text-decoration:none;
  font-weight:500;transition:0.3s;
}
.navbar .back-btn:hover{background:#ffffff55;}
.theme-toggle{
  background:#fff;border:none;color:#9333ea;
  width:40px;height:40px;border-radius:50%;
  font-size:20px;cursor:pointer;transition:0.3s;
}
.theme-toggle:hover{transform:scale(1.1);}
.container{max-width:1100px;margin-top:60px;}
.card{
  border:none;border-radius:15px;
  box-shadow:0 6px 20px rgba(0,0,0,0.08);
  transition:background 0.4s,box-shadow 0.4s;
}
.table th{background:#2563eb;color:#fff;text-align:left;}
.table td{vertical-align:top;}
.table-striped>tbody>tr:nth-of-type(odd)>*{background-color:rgba(37,99,235,0.05);}
textarea{width:100%;border-radius:8px;padding:6px;border:1px solid #cbd5e1;resize:vertical;}
textarea:focus{border-color:#2563eb;outline:none;}
body.dark{background:linear-gradient(135deg,#0f172a,#1e293b);color:#f8fafc;}
body.dark .navbar{background:linear-gradient(90deg,#0f172a,#1e293b);}
body.dark .card{background:#1e293b;box-shadow:0 4px 12px rgba(0,0,0,0.5);}
body.dark .table{color:#f1f5f9;}
body.dark th{background:#334155 !important;}
body.dark tr:nth-child(even){background:#1e293b !important;}
body.dark textarea{background:#334155;color:#fff;border-color:#475569;}
</style>
</head>
<body>

<div class="navbar">
  <a href="dashboard.php" class="back-btn">⬅ Back Dashboard</a>
  <h4> Visitor Messages</h4>
  <button id="themeToggle" class="theme-toggle">🌙</button>
</div>

<div class="container mt-4">
  <div class="card p-4">
    <h4 class="fw-semibold text-center mb-3">📨 Messages Received from Visitors</h4>
    <div class="table-responsive">
      <table class="table table-bordered table-striped align-middle">
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Subject</th>
            <th>Message</th>
            <th>Reply</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if(mysqli_num_rows($messages) > 0): ?>
            <?php while($row=mysqli_fetch_assoc($messages)): ?>
              <tr>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['subject']) ?></td>
                <td><?= nl2br(htmlspecialchars($row['message'])) ?></td>
                <td>
                  <form method="POST">
                    <input type="hidden" name="msg_id" value="<?= $row['id'] ?>">
                    <input type="hidden" name="email" value="<?= htmlspecialchars($row['email']) ?>">
                    <textarea name="reply_msg" placeholder="Write reply..." required></textarea>
                    <button type="submit" name="reply" class="btn btn-success btn-sm mt-1">Send</button>
                  </form>
                </td>
                <td>
                  <a href="?del=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this message?')">Delete</a>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="6" class="text-center text-muted">No visitor messages yet 📭</td></tr>
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
