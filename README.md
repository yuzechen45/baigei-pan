# baigei-pan
一个简单的php网盘，用来挂载和管理本地文件
使用mysql来储存用户名与密码

demo：https://baigeipan.us.kg
# 食用方法：
在mysql中添加一个数据库，在sql中输入以下代码：
```sql
CREATE TABLE users (
  id INT(11) NOT NULL AUTO_INCREMENT,
  username VARCHAR(50) NOT NULL,
  password VARCHAR(255) NOT NULL,
  PRIMARY KEY (id)
);
```
 ```sql
CREATE TABLE files (
  id INT(11) NOT NULL AUTO_INCREMENT,
  path VARCHAR(255) NOT NULL,
  name VARCHAR(255) NOT NULL,
  PRIMARY KEY (id)
);
```
 修改db.php，填入正确的数据库信息
 
 修改insert_user.php,填入你需要的管理员用户名与密码
 
 访问域名下的insert_user.php,显示新记录插入成功既为成功创建新用户
 
 访问 
 你的域名/admin.php 进入后台管理页面
# 说明：
1.你的文件应储存在./file 文件夹中

2.这个网盘还十分简陋，很多都是抄代码或问ai,可能存在未知bug
