<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('../config/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    $pageTitle = "Message Status | Smart Portfolio";
    $alertClass = "";
    $alertMsg = "";
    $redirect = "view.php?user=$user_id";

    // ✅ Validate
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $alertClass = "danger";
        $alertMsg = "⚠️ Please fill in all required fields!";
    } else {
        // ✅ Create Table if Not Exists
        $createTable = "
        CREATE TABLE IF NOT EXISTS contact_messages (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT,
            name VARCHAR(100),
            email VARCHAR(100),
            subject VARCHAR(255),
            message TEXT,
            replied TINYINT(1) DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB;
        ";
        mysqli_query($conn, $createTable);

        // ✅ Insert Message
        $stmt = mysqli_prepare($conn, "INSERT INTO contact_messages (user_id, name, email, subject, message) VALUES (?, ?, ?, ?, ?)");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "issss", $user_id, $name, $email, $subject, $message);
            if (mysqli_stmt_execute($stmt)) {
                $alertClass = "success";
                $alertMsg = "✅ Your message has been sent successfully!";
            } else {
                $alertClass = "danger";
                $alertMsg = "❌ Failed to send message. Please try again.";
            }
            mysqli_stmt_close($stmt);
        } else {
            $alertClass = "danger";
            $alertMsg = "❌ Database error: " . mysqli_error($conn);
        }
    }
} else {
    header("Location: ../index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $pageTitle ?></title>

<!-- ✅ Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
body {
  background: linear-gradient(135deg, #dbeafe, #f0abfc, #93c5fd);
  font-family: 'Poppins', sans-serif;
  height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
}
.card {
  width: 450px;
  background: rgba(255, 255, 255, 0.9);
  backdrop-filter: blur(15px);
  border-radius: 20px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.15);
  padding: 35px;
  text-align: center;
  animation: fadeIn 0.7s ease-in-out;
}
.card h3 {
  font-weight: 700;
  color: #1e3a8a;
}
.card p {
  color: #475569;
  font-size: 1rem;
}
.alert {
  border-radius: 15px;
  padding: 15px;
  font-weight: 500;
  font-size: 1rem;
}
.btn {
  border-radius: 25px;
  padding: 10px 20px;
  font-weight: 600;
  transition: 0.3s;
}
.btn-primary {
  background: linear-gradient(90deg, #2563eb, #9333ea);
  border: none;
}
.btn-primary:hover {
  background: linear-gradient(90deg, #9333ea, #06b6d4);
  transform: scale(1.05);
}
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(-20px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>
</head>
<body>

<div class="card">
  <h3>Smart Portfolio</h3>
  <hr>
  <div class="alert alert-<?= $alertClass ?>">
    <?= $alertMsg ?>
  </div>
  
  <?php if ($alertClass === "success"): ?>
    <p>Thank you for reaching out! We’ll get back to you soon. 💬</p>
    <a href="<?= htmlspecialchars($redirect) ?>" class="btn btn-primary mt-3">Back to Portfolio</a>
  <?php else: ?>
    <p>Please review your input and try again.</p>
    <a href="javascript:history.back()" class="btn btn-primary mt-3">Go Back</a>
  <?php endif; ?>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
