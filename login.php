<?php
include 'db.php';
session_start();

// 检查是否有 POST 请求
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // 防止 SQL 注入
    $username = $conn->real_escape_string($username);
    $password = $conn->real_escape_string($password);

    $sql = "SELECT id, username, password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;
            // 确保在重定向之前没有任何输出
            header("location: admin.php");
            exit;
        } else {
            $error = "无效的用户名或密码。";
        }
    } else {
        $error = "无效的用户名或密码。";
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.staticfile.net/twitter-bootstrap/4.1.0/css/bootstrap.min.css">
    <script src="https://cdn.staticfile.net/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdn.staticfile.net/popper.js/1.12.5/umd/popper.min.js"></script>
    <script src="https://cdn.staticfile.net/twitter-bootstrap/4.1.0/js/bootstrap.min.js"></script>
    <title>admin login panel</title>
    <style>
        /* From Uiverse.io by Na3ar-17 */
        .container {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-image: url('https://lovefurina.us.kg/'); /*pc background ;*/
            background-size: cover;
            background-attachment: fixed;
        }

        .label {
            position: relative;
            display: block;
            width: 250px;
            display: flex;
            border-radius: 6px;
            border: 2px solid #373737;
            padding: 15px 8px 15px 10px;
            text-align: left;
        }

        .icon {
            position: absolute;
            top: 53%;
            right: 0;
            transform: translate(-50%, -50%);
            transition: all 0.3s ease;
            color: #c5c5c5;
        }

        .input {
            background-color: transparent;
            outline: none;
            border: none;
            color: #c5c5c5;
            font-size: 16px;
            flex-grow: 1;
        }

        .four {
            background: rgba(0, 0, 0, 0.25);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            backdrop-filter: blur(3px);
            -webkit-backdrop-filter: blur(3px);
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            padding: 20px;
        }
    </style>
</head>
<body>
  <div class="alert alert-info alert-dismissible">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>success!</strong> 成功连接数据库
  </div>
  <div class="container">
    <div class="four">
        <h2>login</h2>
        <?php if (isset($error)) echo "<p>$error</p>"; ?>
        <form action="login.php" method="post">
            <div class="label">
                <span class="icon">&#128100;</span>
                <input class="input" type="text" name="username" placeholder="Username" required>
            </div>
            <div class="label">
                <span class="icon">&#128272;</span>
                <input class="input" type="password" name="password" placeholder="Password" required>
            </div>
            <input type="submit" value="登录">
        </form>
    </div>
  </div>
</body>
</html>
