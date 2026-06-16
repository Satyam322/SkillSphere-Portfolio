<?php
session_start();
include('config/db.php');

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $error = "⚠️ Please enter both email and password.";
    } else {
        $stmt = mysqli_prepare($conn, "SELECT id, name, password, role, status FROM users WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);

            if (password_verify($password, $user['password']) || $password === $user['password']) {
                if ($user['status'] === 'blocked') {
                    $error = "🚫 Your account is blocked by admin.";
                } else {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['role'] = $user['role'];

                    if ($user['role'] === 'admin') {
                        header("Location: admin/dashboard.php");
                    } else {
                        header("Location: user/dashboard.php");
                    }
                    exit();
                }
            } else {
                $error = "❌ Invalid password.";
            }
        } else {
            $error = "❌ No account found with this email.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Smart Portfolio – Login</title>

<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap');

body{
  margin:0;
  font-family:'Poppins',sans-serif;
  min-height:100vh;
  display:flex;
  align-items:center;
  justify-content:center;
  background:linear-gradient(120deg,#0f172a,#1e3a8a,#2563eb);
  background-size:200% 200%;
  animation:animateBG 8s ease infinite;
  padding:20px;
}

@keyframes animateBG{
  0%{background-position:0% 50%;}
  50%{background-position:100% 50%;}
  100%{background-position:0% 50%;}
}

/* MAIN WRAPPER */
.main-wrapper{
  display:flex;
  align-items:center;
  justify-content:center;
  gap:60px;
  flex-wrap:wrap;
  width:100%;
}

/* IMAGE */
.image-box img{
  width:420px;
  max-width:100%;
  animation:float 4s ease-in-out infinite;
}

@keyframes float{
  0%{ transform:translateY(0px); }
  50%{ transform:translateY(-15px); }
  100%{ transform:translateY(0px); }
}

/* LOGIN BOX */
.container{
  width:380px;
  padding:45px 40px;
  border-radius:18px;
  background:rgba(255,255,255,0.14);
  backdrop-filter:blur(20px);
  text-align:center;
  color:white;
  box-shadow:0 8px 32px rgba(0,0,0,0.25);
  border:1px solid rgba(255,255,255,0.25);
}

h2{
  margin-bottom:22px;
  font-size:26px;
  font-weight:600;
}

.error{
  padding:12px;
  font-size:14px;
  background:rgba(255,0,0,0.18);
  border:1px solid rgba(255,90,90,0.6);
  color:#ffdada;
  border-radius:8px;
  margin-bottom:15px;
}

input, button{
  width:100%;
  box-sizing:border-box;
}

input{
  padding:14px;
  margin:10px 0;
  border:none;
  border-radius:12px;
  background:rgba(255,255,255,0.25);
  color:white;
  font-size:15px;
  outline:none;
}

input::placeholder{
  color:#e5e7eb;
}

button{
  padding:14px;
  background:white;
  color:#1e40af;
  border:none;
  border-radius:12px;
  font-size:17px;
  font-weight:600;
  cursor:pointer;
  margin-top:10px;
  transition:0.3s;
}

button:hover{
  background:#e5e7eb;
}

p{
  margin-top:16px;
  font-size:14px;
  color:#eef2ff;
}

a{
  color:white;
  font-weight:500;
  text-decoration:none;
}

/* MOBILE */
@media(max-width:768px){
  .main-wrapper{
    flex-direction:column;
    gap:30px;
  }
}
</style>
</head>

<body>

<div class="main-wrapper">

  <!-- LEFT IMAGE -->
  <div class="image-box">
    <img src="assets/images/th.jpg" alt="Login Image">
  </div>

  <!-- LOGIN FORM -->
  <div class="container">
    <h2>🔐 User Login</h2>

    <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>

    <form method="POST" autocomplete="off" novalidate>
      <input type="email" name="email" placeholder="Enter Email" required>
      <input type="password" name="password" placeholder="Enter Password" required>
      <button type="submit" name="login">Login</button>
    </form>

    <p>Don't have an account? <a href="register.php">Register here</a></p>
  </div>

</div>

</body>
</html>