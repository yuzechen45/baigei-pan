<?php
include 'db.php';
session_start();

// 检查是否登录
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}

// 定义删除目录的函数
function deleteDirectory($dirPath) {
    if (!is_dir($dirPath)) {
        throw new InvalidArgumentException("$dirPath must be a directory");
    }
    if (substr($dirPath, -1) != '/') {
        $dirPath .= '/';
    }
    $files = array_diff(scandir($dirPath), array('.', '..'));
    foreach ($files as $file) {
        if (is_dir($dirPath . $file)) {
            deleteDirectory($dirPath . $file);
        } else {
            unlink($dirPath . $file);
        }
    }
    return rmdir($dirPath);
}

// 获取要删除的文件或目录路径
if (isset($_GET['path'])) {
    $path = $_GET['path'];
    
    // 检查文件或目录是否存在
    if (file_exists($path)) {
        // 尝试删除文件或目录
        if (is_file($path)) {
            if (unlink($path)) {
                echo "文件删除成功。";
            } else {
                echo "删除失败：无法删除文件。";
            }
        } elseif (is_dir($path)) {
            // 删除目录及其所有内容
            if (deleteDirectory($path)) {
                echo "目录删除成功。";
            } else {
                echo "删除失败：无法删除目录。";
            }
        } else {
            echo "删除失败：未知错误。";
        }
    } else {
        echo "删除失败：文件或目录不存在。";
    }
} else {
    echo "错误：未提供文件或目录路径。";
}

$conn->close();
?>