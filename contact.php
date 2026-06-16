<?php
include('config/db.php');

if(isset($_POST['send'])){
  $name = mysqli_real_escape_string($conn, $_POST['name']);
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $subject = mysqli_real_escape_string($conn, $_POST['subject']);
  $message = mysqli_real_escape_string($conn, $_POST['message']);

  $query = "INSERT INTO contact_messages(name,email,subject,message) VALUES('$name','$email','$subject','$message')";
  if(mysqli_query($conn, $query)){
    $success = "✅ Message sent successfully!";
  } else {
    $error = "❌ Something went wrong!";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Contact | Smart Portfolio</title>
<style>
body{font-family:'Poppins',sans-serif;background:#f4f7fa;padding:40px;}
.container{max-width:600px;margin:auto;background:#fff;padding:30px;border-radius:12px;box-shadow:0 8px 25px rgba(0,0,0,0.1);}
input,textarea{width:100%;padding:10px;margin:8px 0;border:1px solid #ccc;border-radius:8px;}
button{background:#2563eb;color:#fff;border:none;padding:12px;width:100%;border-radius:8px;font-size:16px;cursor:pointer;}
button:hover{background:#1d4ed8;}
.success{color:#166534;background:#dcfce7;padding:10px;border-radius:6px;}
.error{color:#b91c1c;background:#fee2e2;padding:10px;border-radius:6px;}
</style>
</head>
<body>
<div class="container">
  <h2>📩 Contact Us</h2>
  <?php if(isset($success)) echo "<div class='success'>$success</div>"; ?>
  <?php if(isset($error)) echo "<div class='error'>$error</div>"; ?>
  <form method="POST">
    <input type="text" name="name" placeholder="Your Name" required>
    <input type="email" name="email" placeholder="Your Email" required>
    <input type="text" name="subject" placeholder="Subject" required>
    <textarea name="message" placeholder="Write your message..." rows="5" required></textarea>
    <button type="submit" name="send">Send Message</button>
  </form>
</div>
</body>
</html>
