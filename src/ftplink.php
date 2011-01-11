<?php
/*
 * 目前实现了获取目录结构，但是返回的是数组，没有进行字符串分析
 */
class Ftplink{
	var $ftpadd="127.0.0.1";
	var $usrname="anonymous";
	var	$password="";
	function getlist()
	{

		$conn= ftp_connect($this->ftpadd) or die("Could not connect");
	echo	ftp_login($conn,$this->usrname,$this->password);
echo '2';		
		$list=ftp_rawlist($conn,".",true);
		ftp_close($conn);
		print_r($list);

	}
	/*private*/ function connect_ftp($ip)
	{
		$fp=fsockopen($ip,21,$errno,$errstr,30);
		if(!fp){
			echo "$ip:$errstr($errno)\n";
		}else{
			echo "$ip:success...\n.";
		}
	}
	function searchftp($from,$to,$netmask)
	{
		echo "$from\n";
		echo long2ip(ip2long($from));
		$a=ip2long($from);
		$b=ip2long($to);
		echo "$a\n";
		echo "$b\n";
		for($a;$a<$b;$a++)
		{
			$this->connect_ftp(long2ip($a));
		}

	}
	
}
$ll=new Ftplink;
$ll->getlist();
?>
