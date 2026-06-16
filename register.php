<?php
include('config/db.php');

if (isset($_POST['register'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm = trim($_POST['confirm']);

    if (empty($name) || empty($email) || empty($password) || empty($confirm)) {
        $error = "All fields are required!";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match!";
    } else {
        $check = mysqli_prepare($conn, "SELECT * FROM users WHERE email=?");
        mysqli_stmt_bind_param($check, "s", $email);
        mysqli_stmt_execute($check);
        $result = mysqli_stmt_get_result($check);

        if (mysqli_num_rows($result) > 0) {
            $error = "Email already registered!";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $insert = mysqli_prepare($conn, 
                "INSERT INTO users (name, email, password, role, status, created_at) 
                 VALUES (?, ?, ?, 'user', 'active', NOW())"
            );
            mysqli_stmt_bind_param($insert, "sss", $name, $email, $hashed);

            if (mysqli_stmt_execute($insert)) {

                // SUCCESS + REDIRECT AFTER 2 SECONDS
                $success = "Registration successful! Redirecting to login...";
                echo "<script>
                        setTimeout(function(){
                            window.location.href = 'login.php';
                        }, 2000);
                      </script>";

            } else {
                $error = "Something went wrong. Try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Smart Portfolio - Register</title>
<meta name="autocomplete" content="off">

<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap');

body{
  font-family:'Poppins',sans-serif;
  height:100vh;margin:0;
  display:flex;justify-content:center;align-items:center;
  background:linear-gradient(120deg,#0f172a,#1e3a8a,#2563eb);
  background-size:200% 200%;
  animation:bgFlow 8s ease infinite;
}

@keyframes bgFlow{
  0%{background-position:0% 50%;}
  50%{background-position:100% 50%;}
  100%{background-position:0% 50%;}
}

.container{
  width:380px;
  padding:40px 35px;
  border-radius:18px;
  background:rgba(255,255,255,0.15);
  backdrop-filter:blur(18px);
  -webkit-backdrop-filter:blur(18px);
  box-shadow:0 8px 32px rgba(0,0,0,0.2);
  text-align:center;
  color:white;
}

h2{
  margin-bottom:18px;
  font-weight:600;
}

.error{
  color:#ffb3b3;
  background:rgba(255,0,0,0.15);
  border:1px solid #ff7b7b;
  padding:10px;
  border-radius:6px;
  margin-bottom:10px;
  font-size:14px;
}

.success{
  color:#c7f5c7;
  background:rgba(0,255,0,0.12);
  border:1px solid #84ff94;
  padding:10px;
  border-radius:6px;
  margin-bottom:10px;
  font-size:14px;
}

input, button{
  width:100%;
  box-sizing:border-box;
}

input{
  padding:12px;
  margin:8px 0;
  border:none;
  border-radius:10px;
  background:rgba(255,255,255,0.25);
  color:white;
  font-size:15px;
  outline:none;
}

input::placeholder{
  color:#e2e8f0;
}

button{
  padding:12px;
  margin-top:12px;
  border:none;
  border-radius:10px;
  background:#ffffff;
  color:#1e3a8a;
  font-size:16px;
  cursor:pointer;
  font-weight:600;
  transition:0.3s;
}

button:hover{
  background:#e2e8f0;
}

p{
  margin-top:14px;
  color:#f8fafc;
}

a{
  color:#fff;
  font-weight:500;
}

a:hover{
  opacity:0.75;
}
</style>
</head>

<body>
<div class="container">
  <h2>🧾 Create Account</h2>

  <?php if(isset($error)) echo "<p class='error'>".htmlspecialchars($error)."</p>"; ?>
  <?php if(isset($success)) echo "<p class='success'>".htmlspecialchars($success)."</p>"; ?>

  <form method="POST" autocomplete="off" novalidate>
    <input type="text" name="fakeuser" style="display:none">
    <input type="password" name="fakepass" style="display:none">

    <input type="text" name="name" placeholder="Full Name" required autocomplete="new-name">
    <input type="email" name="email" placeholder="Email" required autocomplete="new-email">
    <input type="password" name="password" placeholder="Password" required autocomplete="new-password">
    <input type="password" name="confirm" placeholder="Confirm Password" required autocomplete="new-password">

    <button type="submit" name="register">Register</button>
  </form>

  <p>Already have an account? <a href="login.php">Login here</a></p>
</div>
</body>
</html>
