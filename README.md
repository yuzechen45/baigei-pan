# baigei-pan
一个简单的php网盘，用来挂载和管理本地文件
使用mysql来储存用户名与密码
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
CREATE TABLE users (
  id INT(11) NOT NULL AUTO_INCREMENT,
  username VARCHAR(50) NOT NULL,
  password VARCHAR(255) NOT NULL,
  PRIMARY KEY (id)
);
```
 
