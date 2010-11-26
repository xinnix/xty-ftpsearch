<?php
$dbhost='localhost';//服务器地址
$usrname='root';//数据库用户名
$password='xinxin';//密码
$dbname='ftpsearch';//数据库名称
$conn=mysql_connect($dbhost,$usrname,$password,$dbname);
if (!$conn){
	die('Could not connect: ' . mysql_error());
}
echo "success";
?>

