<?php

$step  = $_POST['step'];
$servername  = $_POST['servername'];
$dbname  = $_POST['dbname'];
$dbusername  = $_POST['dbusername'];
$dbpassword  = $_POST['dbpassword'];
$name  = $_POST['name'];
$pass  = $_POST['pass'];

$phpfunction = '';
if (function_exists('file_get_contents')) {
    $phpfunction .= "<p>是否支持远程打开：是 不支持将不能采集</p>";
} else {
    $phpfunction .= "<p>是否支持远程打开：否 不支持将不能采集</p>";
}

if (function_exists('imagecreate')) {
    $phpfunction .= "<p>是否支持GD：是 不支持将不能使用水印</p>";
} else {
    $phpfunction .= "<p>是否支持GD：否 不支持将不能使用水印</p>";
}

if (function_exists('iconv')) {
    $phpfunction .= "<p>是否支持iconv函数：是 不支持水印文字不能用中文</p>";
} else {
    $phpfunction .= "<p>是否支持iconv函数：否 不支持水印文字不能用中文</p>";
}

if (function_exists('dl')) {
    $phpfunction .= "<p>安全模式是否打开：否 打开程序出现错误</p>";
} else {
    $phpfunction .= "<p>安全模式是否打开：是 打开程序出现错误</p>";
}


$upgrade = $_GET['upgrade'];
if($upgrade == 1.1 )
{
	require_once('config.php');
	require_once('mysql.php');
	
	ob_start();
	$hsndle=new MYSQL($mysql_server_name, $mysql_username, $mysql_password);
	$hsndle->MYsqlcon();
	$hsndle->mysqlSelect($mysql_database);
	
	$sql = "ALTER TABLE `cjrule` ADD `cookie` TEXT NOT NULL ;";
	$sql2 = "ALTER TABLE `export` ADD `newnum` TEXT NOT NULL ;";
	

	
	if($result=$hsndle->query($sql))
	{
	echo  '修改cjrule表成功！！';
	$turn_url = 'index.php';
	}else {
				echo  '修改cjrule表失败 数据表不存在 或则已升级 ！！';
				$turn_url = 'install.php';
			}
			
	if($result=$hsndle->query($sql2))
	{
	echo  '修改export表成功！！';
	$turn_url = 'index.php';
	}else {
				echo  '修改export表失败 数据表不存在 或则已升级 ！！';
				$turn_url = 'install.php';
			}
			
			
		
	$turn_text = 	ob_get_contents()	;
	ob_end_clean();
	$turn_time = 2;

	require_once('template/hint.htm');
//	echo '请输入有效规则ID！！';
	exit;	
		
}

//---------1-----
if($step == "" or $step == 1 )
{
$title = '安装须知';	
$pages = <<<PAGE
<p dir="ltr">欢迎使用 hmj采集器 V1.1，中本脚本将帮助您将程序完整地安装在您的服务器内。请您先确认以下安装配置:</p>
					
					<ul>
						<li>MySQL 主机名称/IP 地址 
						</li>
						<li>MySQL 用户名和密码 
						</li>
						<li>MySQL 数据库名称 (如果没有创建新数据库的权限) 
						</li>
						<li>config.php 文件权限为 0777 (*nix系统) </li>
					</ul>
					<p dir="ltr">　</p>
					<p dir="ltr">
<textarea style="PADDING-RIGHT: 4px; PADDING-LEFT: 4px; FONT-SIZE: 12px; PADDING-BOTTOM: 4px; PADDING-TOP: 4px" name="textarea" rows="14" cols="70">程序说明
hmj采集器是由huangmingj(qq:170104966)独立编写
是一个用PHP语言编写的基于PHP+MySQL网络文章采集系统。

由于在WINDOWS平台编写，推荐本地安装apache+php+mysql，在本地运行采集。

程序特点
1.支持多站点采集
2.多线程无误采集
3.图片SWF采集到本地
4.分页文章采集支持
5.搜寻替换数据
6.正则替换文章内容
7.帮助编制规则的分析HTML功能

《协议》
本软件未注册版本只限于软件学习交流及测试功能使用
您一旦开始安装 hmj采集器 即被视为完全同意本授
权协议

版权所有 HMJ(c) 2005-2006</textarea>
PAGE;

$steps = 2;
$next = '第一次安装 >';

}

//---------2-----
if($step == "2" )
{
$title = '安装须知';	

$pages .= $phpfunction;	

$pages .= <<<PAGE2

								<tr>
									<td noWrap width="30%" style="font-family: Verdana, Tahoma, 宋体; font-size: 12px; line-height: 100%">
									<p>服务器地址:</p></td>
									<td style="font-family: Verdana, Tahoma, 宋体; font-size: 12px; line-height: 100%">
									<input class="formfield" value="localhost" name="servername"></td>
								</tr>
								<tr>
									<td noWrap width="30%" style="font-family: Verdana, Tahoma, 宋体; font-size: 12px; line-height: 100%">
									<p>数据库名:</p></td>
									<td style="font-family: Verdana, Tahoma, 宋体; font-size: 12px; line-height: 100%">
									<input class="formfield" value="hmjcj" name="dbname"></td>
								</tr>
								<tr>
									<td noWrap width="30%" style="font-family: Verdana, Tahoma, 宋体; font-size: 12px; line-height: 100%">
									<p>数据库用户名:</p></td>
									<td style="font-family: Verdana, Tahoma, 宋体; font-size: 12px; line-height: 100%">
									<input class="formfield" value="root" name="dbusername"></td>
								</tr>
								<tr>
									<td noWrap width="30%" style="font-family: Verdana, Tahoma, 宋体; font-size: 12px; line-height: 100%">
									<p>数据库用户密码:</p></td>
									<td style="font-family: Verdana, Tahoma, 宋体; font-size: 12px; line-height: 100%">
									<input class="formfield" name="dbpassword"></td>
								</tr>
								
								<tr>
									<td style="font-family: Verdana, Tahoma, 宋体; font-size: 12px; line-height: 100%" colspan="2" height="100">
								
								<p>如果您无法确认以上的配置信息，请与您的服务商联系，我们无法为您提供任何帮助。</p>
								<p>&nbsp;<br>由于开发时没设定好 数据库前沿 所以请确认安装到的库 无以下表名
								cjclass
								cjrule
								datas
								export
								links
								</p>
								</td>
								</tr>

PAGE2;

$steps = 3;
$next = '下一步 >';

}

//---------3-----
if($step == "3" )
{
					$pages ="";
					$steps = 4;
					$next = '下一步 >';
	
		if(trim($_POST['dbname'])=="" or trim($_POST['servername'])=="" or trim($_POST['dbusername'])=="")
		{
	   $title = '连接数据库错误';	
	   $pages = '<p><font color="#FF0000">请返回并确认所有选项均已正确填写。</font></p>';
	   $steps = 2;
		 $next = '< 上一步';
		} 
			
				$file = "config.php";

			if (file_exists($file)){
			@chmod ($file, 0777);
		}else {
		 $pages = '<p><font color="#FF0000">无法打开config.php 请检查是否存在 或属性是否为777</font></p>';
	   $steps = 2;
		 $next = '< 上一步';
			
			}
			
			   $fp = @fopen($file,"w+");
			   
			   $filecontent = "<?php

\$mysql_server_name = '$servername';	// database server
// 数据库服务器	

\$mysql_username = '$dbusername';	// database username	
// 数据库用户名	

\$mysql_password = '$dbpassword'; // database password	
// 数据库密码	

\$mysql_database = '$dbname';	// database name	
// 数据库名

";
	 if(!@fwrite($fp,$filecontent))
	 {
	 	 $pages = '<p><font color="#FF0000">无法写入config.php 请检查属性是否为777</font></p>';
	   $steps = 2;
		 $next = '< 上一步';
	 	}
	@fclose($fp);
			
				$link = @mysql_connect($servername,$dbusername,$dbpassword);
			

					
					if ($link) {
		$pages .= "<p>数据库服务器连接成功</p>";
		if (@mysql_select_db($dbname)) {
		$pages .= "<p>连接数据库成功</p>";
		} else {
			$pages .= "<p>连接数据库失败　正尝试创建数据库 $dbname</p>";
			if (@mysql_create_db($dbname)) {
				$pages .= "<p>数据库创建成功</p>";
			} else {
				$pages .= "<p>数据库创建失败</p>";
			}
		}
	} else {
		$pages .= "<p>数据库服务器连接失败</p>";

	}
	
	@mysql_close($link);
			
					$title = '建立数据库';	

}

//---------4-----
if($step == 4 )
{
require_once ("config.php");
$dblink =@mysql_connect($mysql_server_name, $mysql_username, $mysql_password) or die("无法创建数据库连接");
@mysql_select_db($mysql_database,$dblink) or die ("无法连接到指定的数据库");
@mysql_query($query, $dblink);


$mysql_data =<<<sql
CREATE TABLE cjclass (
  cid int(10) unsigned NOT NULL auto_increment,
  cname varchar(75) NOT NULL default '',
  PRIMARY KEY  (cid)
) TYPE=MyISAM;
CREATE TABLE cjrule (
  id int(10) unsigned NOT NULL auto_increment,
  cid int(10) NOT NULL default '0',
  name varchar(75) NOT NULL default '',
  index_type int(10) NOT NULL default '1',
  onepage varchar(150) NOT NULL default '',
  manypage text NOT NULL,
  oneurl text NOT NULL,
  url_start varchar(10) NOT NULL default '0',
  url_end varchar(10) NOT NULL default '0',
  link_rule text NOT NULL,
  jclinks varchar(200) NOT NULL default '',
  body_rule text NOT NULL,
  author text NOT NULL,
  source text NOT NULL,
  formss int(1) NOT NULL default '1',
  form_code text NOT NULL,
  form_next text NOT NULL,
  cookie text NOT NULL,
  PRIMARY KEY  (id),
  KEY name (name),
  KEY cid (cid)
) TYPE=MyISAM;
CREATE TABLE datas (
  id int(10) unsigned NOT NULL auto_increment,
  link_id int(10) unsigned NOT NULL default '0',
  cjrule int(10) unsigned NOT NULL default '0',
  title varchar(150) NOT NULL default '',
  body text NOT NULL,
  author text NOT NULL,
  source text NOT NULL,
  url varchar(150) NOT NULL default '',
  date int(20) NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY cjrule (cjrule)
) TYPE=MyISAM;
CREATE TABLE export (
  id int(10) unsigned NOT NULL auto_increment,
  rules text NOT NULL,
  name varchar(200) NOT NULL default '',
  host varchar(200) NOT NULL default '',
  user varchar(200) NOT NULL default '',
  password varchar(200) NOT NULL default '',
  db_name varchar(200) NOT NULL default '',
  article_table varchar(200) NOT NULL default '',
  field_list tinytext NOT NULL,
  value_list tinytext NOT NULL,
  date int(10) unsigned NOT NULL default '0',
  newnum text NOT NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;
CREATE TABLE links (
  id int(10) unsigned NOT NULL auto_increment,
  title varchar(150) NOT NULL default '',
  url varchar(150) NOT NULL default '',
  rules int(8) unsigned NOT NULL default '0',
  date int(10) unsigned NOT NULL default '0',
  adopt int(1) unsigned NOT NULL default '1',
  import int(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY url (url,adopt,import),
  KEY adopt (adopt),
  KEY import (import),
  KEY rules (rules)
) TYPE=MyISAM;

INSERT INTO cjrule VALUES (2, 1, '17173美眉动态', 3, '', '', 'http://mm.17173.com/mmnews/news.php?find=&page=[分页]', '1', '24', '<a href=\'[连接]\' target=\'_blank\'>[标题]</a>', '', '<table width="100%" border="0" cellspacing="0" cellpadding="00" >[内容]<td height="40" valign="bottom"><div align="center"><font color="#e1139e">', '', '', 0, '', '', '');
INSERT INTO cjrule VALUES (4, 1, '网易首页 > 娱乐中心 > 过往娱乐热点新闻检索', 3, '', '', 'http://ent.163.com/special/00031HI0/entnews[分页].html', '2', '5', '<li><a href="[连接]">[标题]</a></li>', '', '<!-- main -->[内容]<!-- page -->', '', '', 1, '<div id="main" style="background:#F9FCFE;">\r\n<div class="page">[分页区域]</div>\r\n<div id="text">', '', '');
INSERT INTO cjrule VALUES (5, 1, '美眉频道 > 游戏美眉> 游戏COS', 3, '', '', 'http://mm.17173.com/show/cosplay.php?find=&page=[分页]', '1', '13', '<a href=\'[连接]\' target=\'_blank\'>[标题]</a>', '', '<table width="100%" border="0" cellspacing="0" cellpadding="00" >[内容]<td height="40" valign="bottom"><div align="center"><font color="#e1139e">', '', '', 0, '', '', '');
INSERT INTO cjrule VALUES (13, 1, 'howaa', 3, '', '', 'http://www.howaa.com/pics/gril/class_gril.asp?page=[分页]', '1', '250', ' <a target="_blank" href="[连接]">[标题]</a></div>', '', '<script src="/JS/AdsJs/bwtextAD.js"></script><br>[内容]<div align="center" style="margin:0 0 30 0">', '', 'howaa', 0, '', '', '');
INSERT INTO cjrule VALUES (21, 1, '新浪 > 新闻中心 > 国际新闻', 3, '', '', 'http://news.sina.com.cn/photo/w/14[分页].shtml  ', '300', '421', '<p><a href=[连接] target=_blank>[标题]</a><FONT', '', '<tr><td class=l17><font id="zoom" class=f14>[内容]</font>\r\n	<br clear=all>\r\n	</td></tr>\r\n	\r\n	</table>\r\n\r\n	</div>\r\n\r\n<SCRIPT>', '', '', 0, '', '', '');
INSERT INTO cjrule VALUES (22, 1, 'TOM动画频道', 3, '', '', 'http://flash.ent.tom.com/flash_pic_list.php?categoryid=35&action=Search&stype=User&perpage=12&colcount=1&order_rule=CreateTime&page=[分页]', '1', '52', '<A HREF="[连接]" TARGET="_blank"><IMG WIDTH="135" HEIGHT="90" BORDER="0" SRC="[变数]"></A></TD>\r\n	</TR> \r\n	</TABLE><!--flash图片和链接--></TD><TD><TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0" BGCOLOR="#FFFFFF"> \r\n<TR> <TD> <TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="1"> <TR BGCOLOR="#E6E6E6"> \r\n<TD>&nbsp;推荐星级：<!--flash星级-->[变数]<!--flash星级-->&nbsp;&nbsp;&nbsp;名称：<!--flash名称-->[标题]<!--flash名称-->', '', '</TABLE><!--flash名称开始--> <BR>[内容]<tr height=30> <td align=center width=300><nobr></td><td align=center width=300><B>', '', '', 0, '', '', '');
INSERT INTO cjrule VALUES (15, 1, '美丽先锋 >> 时尚彩妆', 3, '', '', 'http://www.fadmy.com/Class/mlxf_sscz/mlxf_sscz0[分页].asp', '1', '26', '·<a href=[连接] target=_blank>[标题]</a>', '', '<table width="98%" border="0" cellspacing="0" cellpadding="0" height="100%">[内容]<td height="35"><p align="right">转自', '', '', 1, '<hr noshade color=#C0C0C0 size=1>[分页区域]</tr>\r\n<tr>\r\n<td height="35"><p align="right">', '', '');
INSERT INTO cjrule VALUES (16, 1, '服饰 > 时尚先锋', 3, '', '', 'http://www.pclady.com.cn/dress/ssxf/index_[分页].html', '1', '20', '<a href="[连接]" target="_blank">[标题]</a><BR>', '', 'http://www.pclady.com.cn/dress/nq/0508/pic/20060126banner01.jpg" border=0>[内容]<br><TABLE cellSpacing=1 cellPadding=0 width=500 align=center bgColor=#ffcdcd border=0>', '', '', 1, '<BR CLEAR=all> <DIV ALIGN=center>[分页区域]<div id=demo style=overflow:hidden;height:140;width:496;>', '', '');
INSERT INTO cjrule VALUES (19, 1, 'pconline', 2, '', 'http://www.pconline.com.cn/mobile/news/hgxz/index_1.html', '', '', '', '<a href="[连接]" target="_blank" class=iblue>[标题]</a> <BR>', '', '<table width="98%" border="0" align="center" cellpadding="0" cellspacing="0">[内容]<td width="546" colspan="3" align="CENTER"', '', '', 2, '', '<a href="[上下页]" >[下一页]</a>', '');

INSERT INTO export VALUES (21, '', 'ecms(免费版)', 'localhost', 'root', '', 'ecms', 'phome_ecms_news', 'id|classid|newspath|userid|username|checked|truetime|dokey|filename|newstempid|checkuser|docheckuser|viewcheckuser|notdocheckuser|totaldown|title|newstime|befrom|newstext', '|1|[时间格式:Y-m-d]|1|hmj采集器|1|1137066547|1|[随机:5,4]|1|,|,|,|0|1|[标题]|[采集时间格式:Y-m-d]|17173|[内容]', 1140684122, '');
INSERT INTO export VALUES (26, '', 'pw', '127.0.0.1', 'root', '', 'phpwind', 'pw_threads|pw_tmsgs', 'tid|fid|author|authorid|subject|ifcheck|postdate|lastpost|lastposter#tid|ifsign|content', '[文章编号]|5|huangmingj|1|[标题]|1|[时间]|[时间]|huangmingj#[文章编号]|3|[内容]', 1140691276, 'pw_forums|topic|[加][导出总数]|WHERE fid =5\r\npw_forums|article|[加][导出总数]|WHERE fid =5');
INSERT INTO export VALUES (49, '4', 'dz 3.0', '127.0.0.1', 'root', '', 'dbdz', 'cdb_posts|cdb_threads', 'fid|tid|first|author|authorid|subject|dateline|message#tid|fid|author|authorid|subject|dateline', '1|[文章编号]|1|huangmingj|1|[标题]|[时间]|[内容] #[文章编号]|1|huangmingj|1|[标题]|[时间]', 1140685721, ' 发 ');
INSERT INTO export VALUES (30, '', 'nc', '127.0.0.1', 'root', '', 'get', 'datas', 'id|link_id|rules|title|body|author|url|date', '|[文章编号]|10|[标题]|[内容]|[作者]|[网址]|[采集时间] ', 1140685603, '');
INSERT INTO export VALUES (50, '', 'pw432', '127.0.0.1', 'root', '', 'pw_432', 'pw_threads|pw_tmsgs', 'tid|fid|author|authorid|subject|ifcheck|postdate|lastpost|lastposter#tid|ifsign|content', '[文章编号]|2|huangmingj|1|[标题]|1|[时间]|[时间]|huangmingj#[文章编号]|3|[内容]', 1140832843, 'pw_forumdata|tpost|[加][导出总数]|WHERE fid = 2\r\npw_forumdata|topic|[加][导出总数]|WHERE fid = 2\r\npw_forumdata|article|[加][导出总数]|WHERE fid = 2');
sql;

	$result  = '';
    $a_query = explode(";",$mysql_data);
    while (list(,$query) = each($a_query)) {
           $query = trim($query);
           if ($query) {
               if (strstr($query,'CREATE TABLE')) {
                   ereg('CREATE TABLE ([^ ]*)',$query,$regs);
                   if ($_POST['delete_existing'] == 1) {
                       @mysql_query("DROP TABLE IF EXISTS $regs[1]");
                   }
				   $result .= "<p>正在建立表: ".$regs[1]." …… ";
				   @mysql_query($query);
					if ($query)
					{
						$result .= "成功</p>\n";
					} else {
						$result .= "失败</p>\n";
					}
               } else {
                   @mysql_query($query);
               }

           }
    }

$pages .= $result;

$steps = 5;
$next = '下一步 >';

}

//---------5-----
if($step == 5 )
{
$pages = <<<PAGE2
								<tr>
									&nbsp;<td noWrap width="30%" style="font-family: Verdana, Tahoma, 宋体; font-size: 12px; line-height: 100%">管理账号:</td>
									<td style="font-family: Verdana, Tahoma, 宋体; font-size: 12px; line-height: 100%">
									<input 　class="formfield" value="" name="name"></td>
								</tr>
								<tr>
									<td noWrap width="30%" style="font-family: Verdana, Tahoma, 宋体; font-size: 12px; line-height: 100%">
									管理密码:</td>
									<td style="font-family: Verdana, Tahoma, 宋体; font-size: 12px; line-height: 100%">
									<input class="formfield" name="pass"></td>
								</tr>
								<tr>
PAGE2;

					$title = '建立管理账号';	
					$steps = ６;
					$next = '下一步 >';

}


//---------６-----
if($step == "６" )
{
	
	$contents = "";
	$file = "config.php";
	$fp = @fopen($file,"rb");

  $contents .= @fread($fp, filesize ($file));
  
	if (preg_match ("/\$user(.*)/i", $contents)) {
	    
	  $contents =  preg_replace("/\$user(.*)/is", "", $contents);
	}
  
  $contents .= "
  \$user = '$name';
  \$pass = '$pass';

?>";
  $fp = @fopen($file,"wb+");
	fwrite($fp,$contents);
	fclose($fp);
	$contents = "";
$pages = <<<PAGE2

	<p>安装程序已经顺利执行完毕，请尽快删除 install.php，以免被他人恶意利用。</p>
			   <p>感谢您使用hmj采集器.</p>
			   <p></p>
			   <p><li><a href="index.php">进入采集器</a>
PAGE2;

					$title = '完成';	
					$next = '';

}





$show = <<<EOT
<html>

<head>
<meta http-equiv="Content-Language" content="zh-cn">
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<style>
<!--
TD {
	FONT-SIZE: 9pt; COLOR: #444444; ; FONT-FAMILY: tahoma, arial, helvetica, sans-serif
}
A:active {
	COLOR: #444444
}
A:visited {
	COLOR: #444444; TEXT-DECORATION: none
}
A:hover {
	COLOR: #444444; TEXT-DECORATION: underline
}
A:link {
	COLOR: #444444; TEXT-DECORATION: none
}

BODY {
	SCROLLBAR-FACE-COLOR: #336699; FONT-WEIGHT: normal; FONT-SIZE: 14px; MARGIN: 10px; TEXT-DECORATION: none ;margin-top: 0px;
}
-->
</style>

<title>hmj采集器 V 1.0 安装页面</title>
<style>
</style>
</head>

<body>


			<div align="center">

  <FORM action="" method="post">
			<table border="2" width="608" height="433" style="border-collapse: collapse" cellpadding="0" cellspacing="0">
				<tr>
					<td bgcolor="#D6D3CE" valign="top">
					<table border="0" width="100%" style="border-collapse: collapse" height="18" cellpadding="0">
						<tr>
							<td background="image/install.gif">
							<p dir="ltr">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<font color="#ffffff"><strong>hmj采集器 V 1.0</strong></font></td>
						</tr>
					</table>
					
					<table border="0" width="100%" cellpadding="2">
						<tr>
							<td><b>{$title}</b> <br>
　
{$pages}
</td>
						</tr>
					</table>
					
<table border="0" width="604" height="27" cellpadding="10">
	<tr>
		<td width="273">
		<p dir="ltr"><a href="install.php?upgrade=1.1">已安装过的必须升级数据库，请确认config.php配备正确点击这里升级到1.1</a></td>
		<td width="315" align="right"><INPUT type="hidden" value="{$steps}" name="step"><input type="submit" value="{$next}"></td>
	</tr>
</table>
					
					</td>
				</tr>
			</table>
</FORM>

			</div>


</body>

</html>
EOT;



echo $show;

?>