<?php
include 'db.php'; // 包含数据库连接信息的文件

$username = "admin username"; // 你想要添加的用户名
$password = "admin password"; // 你想要添加的密码

// 使用 password_hash 函数创建密码哈希
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

// 使用 prepared statement 防止 SQL 注入
$sql = "INSERT INTO users (username, password) VALUES (?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die('Prepare error: ' . htmlspecialchars($conn->error));
}

$stmt->bind_param("ss", $username, $passwordHash);

// 执行语句
if ($stmt->execute()) {
    echo "新记录插入成功";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
