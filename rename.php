<?php
include 'db.php';
session_start();

// 检查用户是否登录
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}

// 获取参数
$oldName = isset($_GET['name']) ? $_GET['name'] : '';
$directory = isset($_GET['dir']) ? $_GET['dir'] : './file';
$newName = isset($_GET['new_name']) ? $_GET['new_name'] : '';

// 检查参数是否完整
if (empty($oldName) || empty($newName)) {
    echo "参数不完整。";
    exit;
}

// URL解码
$oldName = urldecode($oldName);
$newName = urldecode($newName);
$directory = urldecode($directory);

// 构造完整的旧路径和新路径
$oldPath = rtrim($directory, '/') . '/' . $oldName;
$newPath = rtrim($directory, '/') . '/' . $newName;

// 检查文件是否存在
if (!file_exists($oldPath)) {
    echo "文件不存在，无法重命名。";
    exit;
}

// 执行重命名操作
if (rename($oldPath, $newPath)) {
    echo "文件或目录已成功重命名为：" . htmlspecialchars($newName);
} else {
    echo "重命名失败，请检查权限或路径是否正确。";
}

$conn->close();
?>