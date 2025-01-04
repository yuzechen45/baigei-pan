<?php
$directory = isset($_GET['dir']) ? $_GET['dir'] : './file';

function listFiles($dir)
{
    $files = array_diff(scandir($dir), array('.', '..'));
    $fileList = [];

    foreach ($files as $file) {
        $filePath = "$dir/$file";
        $size = is_dir($filePath) ? '-' : formatSize(filesize($filePath)); // 使用格式化函数
        $fileList[] = [
            'name' => $file,
            'is_dir' => is_dir($filePath),
            'size' => $size,
            'path' => $filePath,
            'icon' => is_dir($filePath) ? '📁' : getFileIcon($filePath) // 修正图标获取逻辑
        ];
    }

    return $fileList;
}

function formatSize($size)
{
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $unit = 0;

    while ($size >= 1024 && $unit < count($units) - 1) {
        $size /= 1024;
        $unit++;
    }

    return round($size, 2) . ' ' . $units[$unit];
}

function getFileIcon($filePath)
{
    $extension = pathinfo($filePath, PATHINFO_EXTENSION);
    $icons = [
        'pdf' => '📄',
        'doc' => '📄',
        'docx' => '📄',
        'xls' => '📊',
        'xlsx' => '📊',
        'ppt' => '📊',
        'pptx' => '📊',
        'jpg' => '🖼️',
        'jpeg' => '🖼️',
        'png' => '🖼️',
        'gif' => '🖼️',
        'txt' => '📄',
        'zip' => '🗂️',
        'rar' => '🗂️',
        'mp3' => '🎵',
        'wav' => '🎵',
        'mp4' => '🎥',
        'avi' => '🎥',
        'mkv' => '🎥',
        'apk' => '📱', // 添加 APK 文件图标
        // 其他文件类型可以继续添加
    ];

    return isset($icons[$extension]) ? $icons[$extension] : '📄';
}

$fileList = listFiles($directory);

function getParentDirectory($dir)
{
    return dirname($dir);
}
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>baigei's pan</title>
    <style>
         body {
  background-image: url('https://lovefurina.us.kg/'); /*pc background ;*/
  background-size: cover;
  background-attachment: fixed;
font-family: 'tiwate', sans-serif;
}
.four {background: rgba( 0, 0, 0, 0.25 );
box-shadow: 0 8px 32px 0 rgba( 31, 38, 135, 0.37 );
backdrop-filter: blur( 3px );
-webkit-backdrop-filter: blur( 3px );
border-radius: 10px;
border: 1px solid rgba( 255, 255, 255, 0.18 );}
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
        }
        h1 {
            text-align: center;
            margin-bottom: 30px;
            backdrop-filter: blur(3px); /* 模糊度为 3px */
            background: rgba(0, 0, 0, 0.5); /* 黑色背景，50%透明度 */
            border-radius: 10px;
            padding: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
            color: #ffffff; /* 标题字体颜色改为白色 */
        }
        .glass-effect {
            backdrop-filter: blur(3px); /* 模糊度为 3px */
            background: rgba(0, 0, 0, 0.4); /* 黑色背景，40%透明度 */
            border-radius: 10px;
            padding: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
        }
        th, td {
            padding: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            text-align: left;
        }
        th {
            background: rgba(255, 255, 255, 0.1);
        }
        tr:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        a { 
            color: #ffffff; /* 文件名颜色改为白色 */
            text-decoration: none; 
        }
        a:hover { text-decoration: underline; }
        .current-path {
            margin-bottom: 20px;
            font-size: 14px;
            color: #ccc;
        }
        .back-button {
            display: inline-block;
            margin-bottom: 20px;
            font-size: 14px;
            color: #4caf50; /* 返回按钮颜色改为绿色 */
            text-decoration: none;
        }
        .back-button:hover {
            text-decoration: underline;
        }
        .txt {
        color: #ffffff; /* 标题字体颜色改为白色 */
        }
       #announcement {
            position: fixed;
            top: 20px;
            right: -300px; /* 初始位置在屏幕外 */
            width: 300px;
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 10px;
            border-radius: 5px;
            transition: right 0.5s;
        }        
    </style>
</head>
<body>
  <div id="announcement">
        <?= $announcement ?>
    </div>

    <script>
        // 显示公告
        document.getElementById('announcement').style.right = '20px';

        // 5秒后隐藏公告
        setTimeout(function() {
            document.getElementById('announcement').style.right = '-300px';
        }, 5000);
    </script>
    <div class="container">
        <h1>baigei's pan</h1>
    <div class="four">        
        <div class="current-path">当前位置: <?= htmlspecialchars($directory) ?></div>
        <?php if ($directory !== './file' && $directory !== '.'): ?>
            <a class="back-button" href="?dir=<?= urlencode(getParentDirectory($directory)) ?>">返回上一级目录</a></div>
            <p></p><p></p>
        <?php endif; ?>
        <div class="glass-effect">
            <table>
                <thead>
                    <tr>
                        <th>名称</th>
                        <th>大小</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($fileList as $file): ?>
                        <tr>
                            <td>
                                <?= $file['icon'] ?> 
                                <?php if ($file['is_dir']): ?>
                                    <a href="?dir=<?= urlencode($file['path']) ?>"><?= htmlspecialchars($file['name']) ?></a>
                                <?php else: ?>
                                    <a href="download.php?file=<?= urlencode($file['path']) ?>"><?= htmlspecialchars($file['name']) ?></a>
                                <?php endif; ?>
                            </td>
                            <td><?= $file['size'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <p></p>    <p></p>
    <div class="four">
    <h2 class="txt">mc server</h2>
    <p class="txt">欢迎访问baigei's mc server,这里储存了一些启动器与mods,本页面网址既为mc服务器地址：sb3z.us.kg<p class="txt">下面是本人的一些项目</p><p class="txt">blog：baigei.us.kg</p><p class="txt">八班网：class8.skyman.cloud</p></p></div>
</body>
</html>
