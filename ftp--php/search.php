<!--///////////////////////////////
//该文件通过post方法接受界面传过来的“关键词”，关键词变量是keyword
////////////////////////////////
///////////////////////////////
////该文件需加入html标签的地方文件中有标识
///////////////////////////////-->
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="css/search.css" rel="stylesheet" type="text/css">
</head>

<body>
<?
require("splitword.php");         //调用分词类文件
require("common/function.php");   //调用自定义函数文件
if($_POST){                        //应用GET方法获取搜索框的关键字
   $keyword=$_POST[keyword];
}
$yuan=trim($keyword);         //获取用户输入的关键词，并去除左右两边空格
$tt= $yuan;                   //将去除左右空格的关键词赋给变量$tt

//$str=gl($tt);                 //对关键词过滤标点符号
//$str=gl1($str1);               //过滤标点符号
//echo $str;

$sp = new SplitWord();        //创建分词对象

//显示时间部分
$time_start = getmicrotime(); //开始计时，这个是备选项
//$time_end = getmicrotime();
//$t0 = $time_end - $time_start;
//$sp->SplitRMM($str);          //调用分词方法，对关键词进行分词操作
//$tt=$sp->SplitRMM($str);      //将分词后的结果赋给变量$tt

?>
<!--////////////

///////////-->
<?php
	$str2=array(" ","");				//定义一个数组
	$cc=str_replace($str2,"",$tt);	//去掉字符串中的空格
	if(substr($cc,0,2)=="、"){
		$cc= substr($cc,2);			//去掉前面的“、”符号
	}
	if(substr($cc,-2,2)=="、"){
		$cc= substr($cc,0,-2);		//去掉后面的“、”符号
	}
	
	if(substr($cc,0,2)=="、" && substr($cc,-2,2)=="、"){
		$a= substr($cc,2);			//去掉前面的“、”符号
		$cc= substr($a,0,-2);		//去掉后面的“、”符号
	}
	$newstr = explode("、",$cc);			//应用explode()函数将字符串转换成数组
	
	
  require_once("common/db_mysql.class.php");  //调用数据访问类文件
	$DB = new DB_MySQL;				//创建对象
	if(count($newstr)==1){					//如果数组的元素个数为1个，则按单个条件进行查询
			 $sql1 = "select * from files where file like '%".$newstr[0]."%'";   //order by id desc "
       $sql2 = "select * from cat where cat like '%".$newstr[0]."%'";

      }
		else{
			for($i=0;$i<count($newstr);$i++){      //循环输出目录表中与之匹配的各个关键词
				$subsql1.=" file like '%".trim($newstr[$i])."%'"." or";	
				$subsql1=substr($subsql1,0,-2);
				$sql1="select * from files where".$subsql1;
			}
			for($j=0;$j<count($newstr);$j++){      //循环输出文件表中与之匹配的各个关键词
				$subsql2.=" cat like '%".trim($newstr[$j])."%'"." or";
				$subsql2=substr($sql2,0,-2);
				$sql2="select * from cat where".$subsql2;	
			} 
		//	$sql1=substr($sql1,0,-2);				//去掉最后一个“or”		
		//	$sql="select * from cat,files where".$sql0.$sql1;       //形成数据库查询语言
			}
  //$time_end = getmicrotime();
  //$t0 = $time_end - $time_start;
	
  $DB->query($sql1);      //发送SQL语句到MySQL服务器对files表进行查询
	$res1 = $DB->get_rows_array();  //将结果储存在数组中
	$rows_counts1=count($res1);         //计算files表总数
	//$time_end = getmicrotime();
	//$t0 = $time_end - $time_start;
	$DB->query($sql2);      //发送SQL语句到MySQL服务器对cat表进行查询
	$res2 = $DB->get_rows_array();  //将结果储存在数组中
	$rows_counts2=count($res2);         //计算cat表总数
	
	//$rows_counts=$rows_counts1+$rows_counts2;//计算搜索总条数
	$time_end = getmicrotime();
	$t0 = $time_end - $time_start;
	
	$rows_counts=$rows_counts1+$rows_counts2;//计算搜索总条数
	
	//$time_end = getmicrotime();				//结束计时
	//$t0 = $time_end - $time_start;			//搜索计时

	?>
<!--	///////////在网页上显示您查找的关键词
//////////需加html标签-->
<div id="title">
		<?php
		  echo "关键词：".$keyword."  ";
		  ?>
<!--///////////
		  
 //////////汇总匹配的条数，在网页上显示
 //////////需加html标签-->
		  <?php
		  echo "显示结果".$rows_counts."条    ";
		  ?>
<!--///////////
		  
 //////////在网页上显示计算搜索的时间
 //////////需加html标签-->
		  <?php
		    echo "用时：".$t0."秒"."<br>";
		    ?>
	</div>    
		   <!--///////////////
//////////////以分页的形式输出符合条件的信息给用户-->
		    <?php
	if($_GET){
		//得到要提取的页码
		$page_num = $_GET['page_num']? $_GET['page_num']: 1;
	}
	else{
		//首次进入时,页码为1
		$page_num = 1;
	}
	
	
	//得到总记录数
	//$DB->query($sql);
	//$row_count_sum = $DB->get_rows();
	//$row_count_sum;
	//每页记录数,可以使用默认值或者直接指定值
	$row_per_page = 9;
	//得到第一部分页面数
	$page_count1= ceil($rows_counts1/$row_per_page);
	//总页数
	$page_count= ceil($rows_counts/$row_per_page);
	
	
	//判断是否为第一页或者最后一页
	$is_first = (1 == $page_num) ? 1 : 0;
	$is_last = ($page_num == $page_count) ? 1 : 0;
	$result;
	$sign;
	$rows_count3;
	$rows_count4;
	$rows_count5;
	$rows_count6;
	
	//查询起始行位置 对数据进行分页显示
	if($page_num<$page_count1)
  {
		$start_row = ($page_num-1) * $row_per_page;
		$sql1.= " limit $start_row,$row_per_page";
		$DB->query($sql1);
	  $res= $DB->get_rows_array();
	  $rows_count3=count($res);
	  
	  for($i=0;$i<$rows_count3;$i++){
	     $file=$res[$i]['file'];
			 $postfix=$res[$i]['postfix'];
			 $pid=$res[$i]['pid'];
			 $ipid=$res[$i]['ipid'];
			 $id1='/';
			 while($pid!=0)
			 {
			 	$DB->query("select * from cat where id=$pid");
			 	$result1= $DB->get_rows_array();
			 	$pid=$result1[0]['pid'];
				if($result1[0]['cat']!=null)
				{
					$result1[0]['cat'].=$id1;
			 		$id1='/'.$result1[0]['cat'];

				}
			 			 }
			 $DB->query("select * from ftpinfo where id=$ipid");
			 $result2= $DB->get_rows_array();
			 echo $result2[0]['port'];
			 $result2[0]['port'].=$id1;
			 $id1=":".$result2[0]['port'];
			 $result2[0]['site'].=$id1;
			 $id1=$result2[0]['site'];
			 $id1.=$file;
			 $result[$i]=$id1;
	    }
	  
	 }
	else if($page_num==$page_count1)
	{
		$start_row = ($page_num-1) * $row_per_page;
		$sql1.= " limit $start_row,$row_per_page";
		$DB->query($sql1);
	  $res= $DB->get_rows_array();
	  $rows_count4=count($res);
	  
	  $rows_count5=$row_per_page-$rows_count4;
	  $sql2.= " limit 0,$rows_count5";
	  $DB->query($sql2);
	  $res2= $DB->get_rows_array();
	  $sign=count($res2);
	  $rows_count5=$rows_count4+$sign;
	  
	  for($i=0;$i<$rows_count5;$i++){
	    if($i<$rows_count4)
	    {
	     $file=$res[$i]['file'];
			 $postfix=$res[$i]['postfix'];
			 $pid=$res[$i]['pid'];
			 $ipid=$res[$i]['ipid'];
			 $id1='/';

			 while($pid!=NULL)
			 {
			 	$DB->query("select * from cat where id=$pid");
			 	$result1= $DB->get_rows_array();
				$pid=$result1[0]['pid'];
				if($result1[0]['cat']!=null){
					$result1[0]['cat'].=$id1;
			 		$id1='/'.$result1[0]['cat'];
			
				}
			 }
			 $DB->query("select * from ftpinfo where id=$ipid");
			 $result2= $DB->get_rows_array();
			 $tmp=$id1;
			 //$result2[0]['port'].=$id1;
			// $id1=":".$result2[0]['port'];
			// $result2[0]['site'].=$id1;
			 $id1=$result2[0]['site'].":".$result2[0]['port'].$tmp;
			 $id1.=$file;
			 $result[$i]="ftp://".$id1;
	        } else
			 {
			   $cat=$res2[$i-$rows_count4]['cat'];
			   $pid=$res2[$i-$rows_count4]['pid'];
			   $ipid=$res2[$i-$rows_count4]['ipid'];
			   $id2='/';
			 while($pid!=NULL)
			 {
			 	$DB->query("select * from cat where id=$pid");
			 	$result1= $DB->get_rows_array();
			 	$pid=$result1[0]['pid'];
			 	if($result1[0]['cat']!=null){
					$result1[0]['cat'].=$id22;
			 		$id2='/'.$result1[0]['cat'];
			
				}
			 	
			 }
			 $DB->query("select * from ftpinfo where id=$ipid");
			 $result2= $DB->get_rows_array();
			 $result2[0]['port'].=$id2;
			 $id2=":".$result2[0]['port'];
			 $result2[0]['site'].=$id2;
			 $id2=$result2[0]['site'];
			 $id2.=$cat;
			 $result[$i]="ftp://".$id2;

			 }

	  }
	}
	else if($page_num>$page_count1)
	{
	  $start_row = ($page_num-$page_count1-1) * $row_per_page+$sign;
	  $sql2.= " limit $start_row,$row_per_page";
		$DB->query($sql2);
	  $res= $DB->get_rows_array();
	  $rows_count6=count($res);
	  
	  for($i=0;$i<$rows_count6;$i++){
	    $cat=$res[$i]['cat'];
			 $pid=$res[$i]['pid'];
			 $ipid=$res[$i]['ipid'];
			 $id2='/';
			 while($pid!=0)
			 {
			 	$DB->query("select * from cat where id=$pid");
			 	$result1= $DB->get_rows_array();
			 	$pid=$result1[0]['pid'];
				if($result1[0]['cat']!=null)
				{
					$result1[0]['cat'].=$id2;
			 		$id2='/'.$result1[0]['cat'];


				}
			 			 }
			 $DB->query("select * from ftpinfo where id=$ipid");
			 $result2= $DB->get_rows_array();
			 $result2[0]['port'].=$id2;
			 $id2=":".$result2[0]['port'];
			 $result2[0]['site'].=$id2;
			 $id2=$result2[0]['site'];
			 $id2.=$cat;
			 $result[$i]="ftp://".$id2;
	  }
	}  

	?>
	<!--/////////
	
	/////////对标题中所有符合查询关键词后的词语进行描红和超链接，这里应用循坏语句实现将对搜素搜索结果进行输出
	/////////需加html标签-->
	<div id="content">
<ul>
	<?php
		 	if($page_num<$page_count1){

		 	
		 	 for($i=0;$i<$rows_count3;$i++){
		 	 for($n=0;$n<count($newstr);$n++){   //应用FOR循环语句对分词后的词语进行描红
				 $href=$result[$i];
				 $result[$i]= str_ireplace($newstr[$n],"<font color='#FF0000'>".$newstr[$n]."</font>",$result[$i]);

			 }
		 ?>	
<li>		 <a href="<?php echo $href;?>"><?php echo $result[$i];?></a></li>
			<?php
			  }
			}
			  ?>
			  <?php
	if($page_num==$page_count1)
	{
		 	 for($i=0;$i<$rows_count5;$i++){
				 
				 $href=$result[$i];
		 	 for($n=0;$n<count($newstr);$n++)   //应用FOR循环语句对分词后的词语进行描红
				 $result[$i]= str_ireplace($newstr[$n],"<font color='#FF0000'>".$newstr[$n]."</font>",$result[$i]);

		 ?>	
<li>		 <a href="<?php echo $href;?>"><?php echo $result[$i];?></a><br></li>
			<?php  
			}
	}	
			  ?>
			  <?php
			if($page_num>$page_count1)
			{
				echo $result[1];
					echo $result[1];
		 	 for($i=0;$i<$rows_count6;$i++){
		 	 
		 	 for($n=0;$n<count($newstr);$n++){   //应用FOR循环语句对分词后的词语进行描红
				$href=$result[$i];
				 $result[$i]= str_ireplace($newstr[$n],"<font color='#FF0000'>".$newstr[$n]."</font>",$result[$i]);
			 }
		 ?>	
		<li> <a href="<?php echo $href;?>"><?php echo $result[$i];?></a><br></li>
			<?php  
			}
		}	
?>
</ul>
 </div>
<!--///////////////////////////////////////////////////////////////////////////////////////// //////////////////输出“第一页”、“上一页”、“下一页”、“最后一页”文字的超链接
/////////////////需加html标签-->
<div id="nav">
<?php
			if(!$is_first){
			?>
      <a href="./search.php?page_num=1&keyword=<?php echo $keyword;?>">第一页</a> <br>
      <a href="./search.php?page_num=<?php echo ($page_num-1); ?>&keyword=<?php echo $keyword;?>">上一页</a><br>
            <?php
			}
			else{
			?>
            第一页&nbsp;&nbsp;上一页
            <?php
			}
			if(!$is_last){
			?>
            <a href="./search.php?page_num=<?php echo ($page_num+1); ?>&keyword=<?php echo $keyword;?>">下一页</a> 
            <a href="./search.php?page_num=<?php echo $page_count; ?>&keyword=<?php echo $keyword;?>">最后一页</a>
            <?php
			}
			else
			{
			?>
            下一页&nbsp;&nbsp;最后一页
            <?php
			}
			?>
</div>
	</body>
</html>
