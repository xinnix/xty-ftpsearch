<!DOCTYPE HTML>
	<?php
/*作者：xinnix
 *版本：1.0
 *编码方式：utf-8
 *用法：手动添加ftp服务器
 *并测试是否可以login
 *
 */
	header("content-type:text/html; charset=utf-8");
	require("common/db_mysql.class.php");
	class AddFtp{
		public $DB;			

	public function __construct()
	{
		$this->DB=new DB_MySQL;

	}	
	public	function addone($site,$port,$user,$pw,$info)
		{
			$insert="insert into ftpinfo (site,port,user,pw,acc,indb,info) values ('$site','$port','$user','$pw','0','0','$info')";
			echo $insert;
			$this->DB->query($insert);
			echo "complete";

	}
	public function try2conftp()
	{
		$query="select * from ftpinfo where acc=0";
		$this->DB->query($query);
		$res=$this->DB->get_rows_array();
		
		for ($i=0;$i<$this->DB->get_rows();$i++){
			echo $res[$i]['site'];
			$fp=ftp_connect($res[$i]['site'],$res[$i]['port'],30);
			if(!$fp){
				echo "error";
			}else{
				$conn=ftp_login($fp,$res[$i]['user'],$res[$i]['pw']);
				if($conn){
					echo 'sucess!!!';
					$id=$res[$i]['id'];
					$update="update ftpinfo set acc=1 where id=$id";
					$this->DB->query($update);
				}
			}
			$i++;
		}
	}
}
if($_REQUEST['site']!=null)
{
	echo "go in";
	$site=trim($_REQUEST['site']);
	$port=trim($_REQUEST['port']);
	$user=trim($_REQUEST['user']);
	$pw=trim($_REQUEST['pw']);
	$info=trim($_REQUEST['info']);
	$AFTP=new AddFtp;
	$AFTP->addone($site,$port,$user,$pw,$info);
}
if($_GET['actions']=='connect')
{
	echo 'con';
	$AFTP=new AddFtp;
	$AFTP->try2conftp();

}
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html charset=utf-8">
	<title></title>
</head>
<body>
	<div id="top"><a href='addftp.php?actions=connect'>try2connect...</a></div>
	<div id="main">
		<form method="post" action="addftp.php">
		<ul>
		<li>	<span>ftp地址:</span>
			<input type="text" name="site"/></li>
		<li>	<span>端口：</span>
			<input type="text" name="port"/></li>
		<li>	<span>用户名：</span>
			<input type="text" name="user"/></li>
		<li>	<span>密码</span>
			<input type="text" name="pw"/></li>
		<li>    <span>备注</span>
			<textarea type="text" name="info"></textarea></li>
		</ul>
			<input type="submit" name="submit" value="提交"/>

		</form>
		</div>
</body>
</html>
