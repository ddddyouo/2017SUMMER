<?php
header("Content-type:text/html;charset=utf-8");

$mysql_server_name="127.0.0.1";
$mysql_username="root";
$mysql_password="7777";
$mysql_database="cloud";

$regurl="http://cloud:8000/register.html";
$logurl="http://cloud:8000/login.html";

//连接数据库
$link = new PDO("mysql:host=$mysql_server_name;dbname=$mysql_database", "$mysql_username","$mysql_password");

if($link)//判断是否连接数据库成功
{
  if(isset($_POST["regsub"]))
  {
    $name=$_POST["username"];
    $password1=$_POST["password1"];//获取表单数据
    $password2=$_POST["password2"];

    if($name==""||$password1==""||$password2=="")//判断是否填写完整
    {
      echo "请填写完整！";
      header("refresh:2,$regurl");
      $link=null;//断开数据库连接
      exit;
    }
    if($password1==$password2)//判断两次输入的密码是否一样
    {
      $str="select count(*) from users where username='$name'";
      $result=$link->query($str);
      $row=$result->fetchColumn();
      $count=(int)$row;
      if($count!=0)//判断数据库表中是否已存在该用户名
      {
        echo "该用户名已被注册,请重新输入";
        header("refresh:2,$regurl");
        $link=null;//断开数据库连接
        exit;
      }

      $sql="insert into users(username,password) values('$name','$password1')";//将注册信息插入数据库表中
      $link->query($sql);
      $link->query('SET NAMES UTF8');

      echo "注册成功！请登录！";
      header("refresh:2,$logurl");
      $link=null;//断开数据库连接
      exit;
    }
    else {
      echo "密码不一致，请重新输入";
      header("refresh:2,$regurl");
      $link=null;//断开数据库连接
      exit;
    }
  }
}
else {
  echo "数据库连接失败";
  exit;
}

 ?>
