<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.staticfile.net/twitter-bootstrap/4.1.0/css/bootstrap.min.css">
    <script src="https://cdn.staticfile.net/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdn.staticfile.net/popper.js/1.12.5/umd/popper.min.js"></script>
    <script src="https://cdn.staticfile.net/twitter-bootstrap/4.1.0/js/bootstrap.min.js"></script>
    <title>file management system</title>
</head>
<body>
  <div class="alert alert-info alert-dismissible">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>警告!</strong> 数据无价
  </div>
<div class="four">
<?php
include 'db.php';
session_start();

// 检查用户是否登录
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}

// 获取当前目录，如果没有指定，则默认为 ./file
$directory = isset($_GET['dir']) ? realpath($_GET['dir']) : './file';

// 检查目录是否存在
if (!is_dir($directory)) {
    die("目录不存在: " . htmlspecialchars($directory));
}

$files = scandir($directory);

// 创建新文件夹的处理
if (isset($_POST['new_folder_name'])) {
    $new_folder_name = $_POST['new_folder_name'];
    $new_folder_path = $directory . '/' . $new_folder_name;
    if (!file_exists($new_folder_path)) {
        if (mkdir($new_folder_path, 0777, true)) {
            // 页面将在3秒后刷新
            echo "<meta http-equiv='refresh' content='3;url=admin.php?folder_created=true'>";
            echo "<div class='alert alert-success'>文件夹 '$new_folder_name' 创建成功,3s后刷新页面</div>";
        } else {
            echo "<div class='alert alert-danger'>文件夹创建失败。</div>";
        }
    } else {
        echo "<div class='alert alert-warning'>文件夹已存在。</div>";
    }
}

echo "<h2>文件列表 - " . htmlspecialchars(basename($directory)) . "</h2>";
echo "<a href='?dir=" . urlencode(dirname($directory)) . "'>返回上级目录</a><br><br>";

echo "<form method='post' action='upload.php' enctype='multipart/form-data'>";
echo "<input type='hidden' name='current_dir' value='" . htmlspecialchars($directory) . "'>";
echo "选择文件: <input type='file' name='file'><br>";
echo "<input type='submit' value='上传到当前目录'>";
echo "</form>";

// 新建文件夹表单
echo "<form method='post' action=''>";
echo "新建文件夹: <input type='text' name='new_folder_name'> ";
echo "<input type='submit' value='创建'>";
echo "</form>";

echo "<table class='table'>";
echo "<tr><th>文件名</th><th>大小</th><th>类型</th><th>操作</th></tr>";
foreach ($files as $file) {
    if ($file != "." && $file != "..") {
        $filepath = $directory . "/" . $file;
        $fileinfo = pathinfo($filepath);
        echo "<tr>";
        echo "<td>";
        if (is_dir($filepath)) {
            // 如果是目录，则创建一个链接，允许用户进入
            echo "<a href='?dir=" . urlencode($filepath) . "'>" . htmlspecialchars($file) . "</a>";
        } else {
            // 如果是文件，则显示文件名
            echo htmlspecialchars($file);
        }
        echo "</td>";
        echo "<td>" . (is_file($filepath) ? filesize($filepath) : '-') . "</td>";
        echo "<td>" . (isset($fileinfo['extension']) ? $fileinfo['extension'] : '文件夹') . "</td>";
        echo "<td>";
        // 传递文件的完整路径
        $escaped_path = urlencode($filepath);
        echo "<a href='delete.php?path=" . $escaped_path . "' onclick='return confirm(\"确定删除吗？\")'>删除</a> | ";
        echo "<button onclick='showRenameModal(\"" . urlencode($file) . "\",\"" . urlencode($directory) . "\")'>重命名</button>";
        if (is_file($filepath)) {
            echo " | <a href='download.php?file=" . urlencode($filepath) . "'>下载</a>";
        }
        echo "</td>";
        echo "</tr>";
    }
}
echo "</table>";

$conn->close();
?>

<!-- 模态框（Modal）-->
<div id="renameModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <p>请输入新的文件名：</p>
        <form action="rename.php" method="get">
            <input type="text" name="new_name" required>
            <input type="hidden" id="rename_name" name="name">
            <input type="hidden" id="rename_dir" name="dir">
            <input type="submit" value="确定">
            <button type="button" class="cancel">取消</button>
        </form>
    </div>
</div>

<script>
function showRenameModal(name, directory) {
    document.getElementById('rename_name').value = name;
    document.getElementById('rename_dir').value = directory;
    document.getElementById('renameModal').style.display = 'block';
}

// 获取模态框元素和关闭按钮元素
var modal = document.getElementById('renameModal');
var span = document.getElementsByClassName('close')[0];

// 点击×关闭模态框
span.onclick = function() {
    modal.style.display = 'none';
}

// 点击取消按钮关闭模态框
document.getElementsByClassName('cancel')[0].onclick = function() {
    modal.style.display = 'none';
}

// 点击窗口以外的地方关闭模态框
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}
</script>

<style>
        body {
  background-image: url('https://lovefurina.us.kg/'); /*pc background ;*/
  background-size: cover;
  background-attachment: fixed;
}
.modal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.25);
    box-shadow: 0 8px 32px 0 rgba( 31, 38, 135, 0.37 );
backdrop-filter: blur( 3px );
-webkit-backdrop-filter: blur( 3px );
border-radius: 10px;
border: 1px solid rgba( 255, 255, 255, 0.18 );}
}

.modal-content {
    background-color: rgba(0,0,0,0.25);
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 30%;
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}
.four {background: rgba( 0, 0, 0, 0.25 );
box-shadow: 0 8px 32px 0 rgba( 31, 38, 135, 0.37 );
backdrop-filter: blur( 3px );
-webkit-backdrop-filter: blur( 3px );
border-radius: 10px;
border: 1px solid rgba( 255, 255, 255, 0.18 );}
</style>
</body>
</html>