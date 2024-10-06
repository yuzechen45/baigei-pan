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
  <meta charset="utf-8">
<html>
<head>
<style>
         body {
  background-image: url('https://lovefurina.us.kg/'); /*pc background ;*/
  background-size: cover;
  background-attachment: fixed;
}
/* From Uiverse.io by Yaseen549 */ 
.input {
  background-color: #212121;
  max-width: 190px;
  height: 40px;
  padding: 10px;
  /* text-align: center; */
  border: 2px solid white;
  border-radius: 5px;
  /* box-shadow: 3px 3px 2px rgb(249, 255, 85); */
}

.input:focus {
  color: rgb(0, 255, 255);
  background-color: #212121;
  outline-color: rgb(0, 255, 255);
  box-shadow: -3px -3px 15px rgb(0, 255, 255);
  transition: .1s;
  transition-property: box-shadow;
}
.four {background: rgba( 0, 0, 0, 0.25 );
box-shadow: 0 8px 32px 0 rgba( 31, 38, 135, 0.37 );
backdrop-filter: blur( 3px );
-webkit-backdrop-filter: blur( 3px );
border-radius: 10px;
border: 1px solid rgba( 255, 255, 255, 0.18 );}
</style>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.staticfile.net/twitter-bootstrap/4.1.0/css/bootstrap.min.css">
  <script src="https://cdn.staticfile.net/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://cdn.staticfile.net/popper.js/1.12.5/umd/popper.min.js"></script>
  <script src="https://cdn.staticfile.net/twitter-bootstrap/4.1.0/js/bootstrap.min.js"></script>
    <title>admin login panel</title>
</head>
<body>
  <div class="alert alert-info alert-dismissible">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>success!</strong> 成功连接数据库
  </div>
    <h2 class="four">login</h2>
    <?php if (isset($error)) echo "<p>$error</p>"; ?><div class="four">
    <form action="login.php" method="post">
        用户名: <input placeholder="type username here..." class="input" name="username" type="text"><br>
        密码: <input placeholder="type password here" class="input" name="password" type="password"><br>
        <input type="submit" value="登录">
    </form></div>
</body>
</html>
