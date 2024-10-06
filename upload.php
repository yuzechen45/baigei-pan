<?php
include 'db.php';
session_start();

// 检查是否登录
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}

$_SESSION['timeout'] = time(); // 更新会话超时时间

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["file"])) {
    // 获取当前目录，如果表单中没有传递，则默认为 ./file
    $current_dir = isset($_POST['current_dir']) ? $_POST['current_dir'] : './file';
    
    // 对 URL 编码的路径进行解码
    $current_dir = urldecode($current_dir);
    
    // 确保目录路径以斜杠结尾
    $target_dir = rtrim($current_dir, '/') . '/';
    
    // 转换中文路径为内部编码格式
    $target_dir = mb_convert_encoding($target_dir, 'UTF-8', 'UTF-8');

    $target_file = $target_dir . basename($_FILES["file"]["name"]);
    $uploadOk = 1;

    // 检查文件是否已经存在
    if (file_exists($target_file)) {
        echo "文件已存在。";
        $uploadOk = 0;
    }

    // 检查 $uploadOk 是否被设置为 0
    if ($uploadOk == 0) {
        echo "文件未上传。";
    } else {
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            echo "文件 " . htmlspecialchars(basename($_FILES["file"]["name"])) . " 已被上传到 " . htmlspecialchars($target_dir) . "。";
        } else {
            echo "上传文件时出错。";
        }
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>文件上传</title>
    <style>
        #progress-container {
            width: 100%;
            background-color: #eee;
        }
        #progress-bar {
            width: 0%;
            height: 30px;
            background-color: #76b852;
            text-align: center;
            line-height: 30px;
            color: white;
        }
    </style>
</head>
<body>
<form id="uploadForm" method="post" enctype="multipart/form-data">
    <input type="hidden" name="current_dir" value="<?php echo htmlspecialchars($current_dir); ?>">
    选择文件: <input type="file" name="file" id="file"><br>
    <input type="button" value="上传" onclick="uploadFile()">
</form>

<div id="progress-container" style="display:none;">
    <div id="progress-bar"></div>
</div>

<script>
function uploadFile() {
    var formData = new FormData(document.getElementById('uploadForm'));
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'upload.php', true);

    xhr.upload.onprogress = function(e) {
        if (e.lengthComputable) {
            var percentage = Math.round((e.loaded / e.total) * 100);
            var progressBar = document.getElementById('progress-bar');
            progressBar.style.width = percentage + '%';
            progressBar.textContent = percentage + '%';
            document.getElementById('progress-container').style.display = 'block';
        }
    };

    xhr.onload = function() {
        if (xhr.status == 200) {
            alert('上传成功');
        } else {
            alert('上传失败');
        }
    };

    xhr.send(formData);
}
</script>
</body>
</html>