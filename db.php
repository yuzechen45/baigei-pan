<?php
$servername = "Your database address";
$username = "Your database username";
$password = "Your database password";
$dbname = "Your database name";

// 创建连接
$conn = new mysqli($servername, $username, $password, $dbname);

// 检查连接
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}
?>