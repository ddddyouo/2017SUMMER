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

    $link->query('SET NAMES UTF8');

    if($name==""||$password1==""||$password2=="")//判断是否填写完整
    {
      echo "请填写完整！";
      header("refresh:2,$regurl");
      $link=null;//断开数据库连接
      exit;
    }

    if(strlen($password1)>36||strlen($password1)<6){
      echo "密码长度应小于36";
      header("refresh:2,$regurl");
      $link=null;//断开数据库连接
      exit;
    }

    if($password1==$password2)//判断两次输入的密码是否一样
    {
      $str="select count(*) from users where username='$name'";
      $result=$link->query($str);
      if($result)
      {
        $row=$result->fetchColumn();
        $count=(int)$row;
        if($count!=0)//判断数据库表中是否已存在该用户名
        {
          echo "该用户名已被注册,请重新输入";
          header("refresh:2,$regurl");
          $link=null;//断开数据库连接
          exit;
        }
      }

      if(!preg_match("/^[\x{4e00}-\x{9fa5}a-zA-Z0-9]+$/u",$name))
      {
        echo "用户名仅能由中文、英文字母、数字组成";
        header("refresh:2,$regurl");
        $link=null;//断开数据库连接
        exit;
      }


      if(preg_match("/^[a-zA-Z]+$/i", $password1)||preg_match("/^[0-9]+$/i", $password1))
      {
        echo "请勿使用全数字或全字母的弱密码！";
        header("refresh:2,$regurl");
        $link=null;//断开数据库连接
        exit;
      }


      define("PBKDF2_SALT_BYTE_SIZE", 24);
      $iterations = 1000;
      $user_salt = base64_encode(mcrypt_create_iv(PBKDF2_SALT_BYTE_SIZE, MCRYPT_DEV_URANDOM));
      $user_hash = hash_pbkdf2("sha256", $password1, $user_salt, $iterations, 20);


      $sql = " INSERT INTO users(user_name,user_pswd_hash,user_salt) values('$name','$user_hash','$user_salt')";//将注册信息插入数据库表中
      $link->query($sql);

      echo "注册成功！请登录！";
      header("refresh:2,$logurl");
      $link=null;//断开数据库连接
      exit;
    }
    else
    {
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
