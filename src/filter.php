<?php
class Filter{
	/* input 字符串
	 * return 布尔值
	 * function 字符串是否无意义，应该被过滤，不用存入数据库
	 */
	function fil($inputname)
	{
		$filword=split("\|",file_get_contents("filter.conf"));
		$res=in_array($inputname,$filword);
		if($res){
		return TRUE;	
		}else{
			return FALSE;
		}
	}
}
$fil=new Filter();
echo $fil->fil("新建文本文档");
?>
