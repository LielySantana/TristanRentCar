<?php 
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();  
}

if ($_SESSION['role'] == 'admin') {
    include 'templates/header-admin.php';
} else if ($_SESSION['role'] == 'user') {
    include 'templates/header-user.php';
} else {
    // Handle unexpected roles
    header("Location: index.php");
    exit(); 
}
echo "Hola, " . $_SESSION['username'];  
?>