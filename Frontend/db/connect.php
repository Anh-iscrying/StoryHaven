<?php
$mysqli = new mysqli("localhost", "root", "", "webbansach");

// Check connection
if ($mysqli->connect_errno) {
    header('Content-Type: application/json'); // Quan trọng: Đặt header
    echo json_encode(['success' => false, 'message' => "Failed to connect to MySQL: " . $mysqli->connect_error]);
    exit(); // Kết thúc script
}
?>