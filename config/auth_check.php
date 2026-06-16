<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// ✅ Common login check
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// ✅ Optional: restrict admin-only pages
if (isset($adminOnly) && $adminOnly === true && ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: ../user/dashboard.php");
    exit();
}

// ✅ Optional: restrict user-only pages
if (isset($userOnly) && $userOnly === true && ($_SESSION['role'] ?? '') !== 'user') {
    header("Location: ../admin/dashboard.php");
    exit();
}
?>
