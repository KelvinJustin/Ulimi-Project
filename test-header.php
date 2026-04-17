<?php
// Start session
session_start();

// Simulate a logged-in user
$_SESSION['user_id'] = 1;

// Mock user data for testing
$_SESSION['mock_user'] = [
    'id' => 1,
    'email' => 'test@example.com',
    'display_name' => 'John Doe',
    'role_name' => 'buyer'
];

// Include the header to test
$base = 'http://localhost/ulimi3';
$isLoggedIn = true;
$user = $_SESSION['mock_user'];

include 'app/Views/partials/header.php';
?>
