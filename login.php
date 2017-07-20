<?php
header("Content-type:text/html;charset=utf-8");

$mysql_server_name="127.0.0.1";
$mysql_username="root";
$mysql_password="7777";
$mysql_database="cloud";

//定义跳转链接
$regurl="http://cloud:8000/register.html";
$logurl="http://cloud:8000/login.html";
$cloudurl="http://cloud:8000/cloud.html";

//连接数据库
$link=new PDO("mysql:host=$mysql_server_name;dbname=$mysql_database", "$mysql_username", "$mysql_password");

if($link)
{
    if(isset($_POST["logsub"]))
    {
      $name=$_POST["username"];
      $password=$_POST["password"];
      if($name==""||$password=="")//判断是否输入完整
      {
        echo "请填写完整！";
        header("refresh:2,$logurl");//两秒后跳转到$logurl
        $link=null;//断开数据库连接
        exit;
      }

      $str="select password from users where username='$name'";//在数据库中查找该用户的密码
      $result=$link->query($str);
      $row=$result->fetchColumn();

      if($row==null)//密码为空，则用户不存在
      {
        echo "此用户不存在，请先注册！";
        header("refresh:2,$logurl");
        $link=null;
        exit;
      }

      if($row==$password)//判断密码与注册时密码是否一致
      {
        echo "登录成功！";
        header("refresh:2,$cloudurl");
        $link=null;//断开数据库连接
        exit;
      }
      else
      {
        echo "密码错误，登录失败！请重新输入！";
        header("refresh:2,$logurl");
        $link=null;//断开数据库连接
        exit;
      }
    }
}
else {
  echo "数据库连接失败";
  exit;
}
