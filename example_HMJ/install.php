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
    $phpfunction .= "<p>�Ƿ�֧��Զ�̴򿪣��� ��֧�ֽ����ܲɼ�</p>";
} else {
    $phpfunction .= "<p>�Ƿ�֧��Զ�̴򿪣��� ��֧�ֽ����ܲɼ�</p>";
}

if (function_exists('imagecreate')) {
    $phpfunction .= "<p>�Ƿ�֧��GD���� ��֧�ֽ�����ʹ��ˮӡ</p>";
} else {
    $phpfunction .= "<p>�Ƿ�֧��GD���� ��֧�ֽ�����ʹ��ˮӡ</p>";
}

if (function_exists('iconv')) {
    $phpfunction .= "<p>�Ƿ�֧��iconv�������� ��֧��ˮӡ���ֲ���������</p>";
} else {
    $phpfunction .= "<p>�Ƿ�֧��iconv�������� ��֧��ˮӡ���ֲ���������</p>";
}

if (function_exists('dl')) {
    $phpfunction .= "<p>��ȫģʽ�Ƿ�򿪣��� �򿪳�����ִ���</p>";
} else {
    $phpfunction .= "<p>��ȫģʽ�Ƿ�򿪣��� �򿪳�����ִ���</p>";
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
	echo  '�޸�cjrule��ɹ�����';
	$turn_url = 'index.php';
	}else {
				echo  '�޸�cjrule��ʧ�� ���ݱ����� ���������� ����';
				$turn_url = 'install.php';
			}
			
	if($result=$hsndle->query($sql2))
	{
	echo  '�޸�export��ɹ�����';
	$turn_url = 'index.php';
	}else {
				echo  '�޸�export��ʧ�� ���ݱ����� ���������� ����';
				$turn_url = 'install.php';
			}
			
			
		
	$turn_text = 	ob_get_contents()	;
	ob_end_clean();
	$turn_time = 2;

	require_once('template/hint.htm');
//	echo '��������Ч����ID����';
	exit;	
		
}

//---------1-----
if($step == "" or $step == 1 )
{
$title = '��װ��֪';	
$pages = <<<PAGE
<p dir="ltr">��ӭʹ�� hmj�ɼ��� V1.1���б��ű��������������������ذ�װ�����ķ������ڡ�������ȷ�����°�װ����:</p>
					
					<ul>
						<li>MySQL ��������/IP ��ַ 
						</li>
						<li>MySQL �û��������� 
						</li>
						<li>MySQL ���ݿ����� (���û�д��������ݿ��Ȩ��) 
						</li>
						<li>config.php �ļ�Ȩ��Ϊ 0777 (*nixϵͳ) </li>
					</ul>
					<p dir="ltr">��</p>
					<p dir="ltr">
<textarea style="PADDING-RIGHT: 4px; PADDING-LEFT: 4px; FONT-SIZE: 12px; PADDING-BOTTOM: 4px; PADDING-TOP: 4px" name="textarea" rows="14" cols="70">����˵��
hmj�ɼ�������huangmingj(qq:170104966)������д
��һ����PHP���Ա�д�Ļ���PHP+MySQL�������²ɼ�ϵͳ��

������WINDOWSƽ̨��д���Ƽ����ذ�װapache+php+mysql���ڱ������вɼ���

�����ص�
1.֧�ֶ�վ��ɼ�
2.���߳�����ɼ�
3.ͼƬSWF�ɼ�������
4.��ҳ���²ɼ�֧��
5.��Ѱ�滻����
6.�����滻��������
7.�������ƹ���ķ���HTML����

��Э�顷
�����δע��汾ֻ�������ѧϰ���������Թ���ʹ��
��һ����ʼ��װ hmj�ɼ��� ������Ϊ��ȫͬ�Ȿ��
ȨЭ��

��Ȩ���� HMJ(c) 2005-2006</textarea>
PAGE;

$steps = 2;
$next = '��һ�ΰ�װ >';

}

//---------2-----
if($step == "2" )
{
$title = '��װ��֪';	

$pages .= $phpfunction;	

$pages .= <<<PAGE2

								<tr>
									<td noWrap width="30%" style="font-family: Verdana, Tahoma, ����; font-size: 12px; line-height: 100%">
									<p>��������ַ:</p></td>
									<td style="font-family: Verdana, Tahoma, ����; font-size: 12px; line-height: 100%">
									<input class="formfield" value="localhost" name="servername"></td>
								</tr>
								<tr>
									<td noWrap width="30%" style="font-family: Verdana, Tahoma, ����; font-size: 12px; line-height: 100%">
									<p>���ݿ���:</p></td>
									<td style="font-family: Verdana, Tahoma, ����; font-size: 12px; line-height: 100%">
									<input class="formfield" value="hmjcj" name="dbname"></td>
								</tr>
								<tr>
									<td noWrap width="30%" style="font-family: Verdana, Tahoma, ����; font-size: 12px; line-height: 100%">
									<p>���ݿ��û���:</p></td>
									<td style="font-family: Verdana, Tahoma, ����; font-size: 12px; line-height: 100%">
									<input class="formfield" value="root" name="dbusername"></td>
								</tr>
								<tr>
									<td noWrap width="30%" style="font-family: Verdana, Tahoma, ����; font-size: 12px; line-height: 100%">
									<p>���ݿ��û�����:</p></td>
									<td style="font-family: Verdana, Tahoma, ����; font-size: 12px; line-height: 100%">
									<input class="formfield" name="dbpassword"></td>
								</tr>
								
								<tr>
									<td style="font-family: Verdana, Tahoma, ����; font-size: 12px; line-height: 100%" colspan="2" height="100">
								
								<p>������޷�ȷ�����ϵ�������Ϣ���������ķ�������ϵ�������޷�Ϊ���ṩ�κΰ�����</p>
								<p>&nbsp;<br>���ڿ���ʱû�趨�� ���ݿ�ǰ�� ������ȷ�ϰ�װ���Ŀ� �����±���
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
$next = '��һ�� >';

}

//---------3-----
if($step == "3" )
{
					$pages ="";
					$steps = 4;
					$next = '��һ�� >';
	
		if(trim($_POST['dbname'])=="" or trim($_POST['servername'])=="" or trim($_POST['dbusername'])=="")
		{
	   $title = '�������ݿ����';	
	   $pages = '<p><font color="#FF0000">�뷵�ز�ȷ������ѡ�������ȷ��д��</font></p>';
	   $steps = 2;
		 $next = '< ��һ��';
		} 
			
				$file = "config.php";

			if (file_exists($file)){
			@chmod ($file, 0777);
		}else {
		 $pages = '<p><font color="#FF0000">�޷���config.php �����Ƿ���� �������Ƿ�Ϊ777</font></p>';
	   $steps = 2;
		 $next = '< ��һ��';
			
			}
			
			   $fp = @fopen($file,"w+");
			   
			   $filecontent = "<?php

\$mysql_server_name = '$servername';	// database server
// ���ݿ������	

\$mysql_username = '$dbusername';	// database username	
// ���ݿ��û���	

\$mysql_password = '$dbpassword'; // database password	
// ���ݿ�����	

\$mysql_database = '$dbname';	// database name	
// ���ݿ���

";
	 if(!@fwrite($fp,$filecontent))
	 {
	 	 $pages = '<p><font color="#FF0000">�޷�д��config.php ���������Ƿ�Ϊ777</font></p>';
	   $steps = 2;
		 $next = '< ��һ��';
	 	}
	@fclose($fp);
			
				$link = @mysql_connect($servername,$dbusername,$dbpassword);
			

					
					if ($link) {
		$pages .= "<p>���ݿ���������ӳɹ�</p>";
		if (@mysql_select_db($dbname)) {
		$pages .= "<p>�������ݿ�ɹ�</p>";
		} else {
			$pages .= "<p>�������ݿ�ʧ�ܡ������Դ������ݿ� $dbname</p>";
			if (@mysql_create_db($dbname)) {
				$pages .= "<p>���ݿⴴ���ɹ�</p>";
			} else {
				$pages .= "<p>���ݿⴴ��ʧ��</p>";
			}
		}
	} else {
		$pages .= "<p>���ݿ����������ʧ��</p>";

	}
	
	@mysql_close($link);
			
					$title = '�������ݿ�';	

}

//---------4-----
if($step == 4 )
{
require_once ("config.php");
$dblink =@mysql_connect($mysql_server_name, $mysql_username, $mysql_password) or die("�޷��������ݿ�����");
@mysql_select_db($mysql_database,$dblink) or die ("�޷����ӵ�ָ�������ݿ�");
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

INSERT INTO cjrule VALUES (2, 1, '17173��ü��̬', 3, '', '', 'http://mm.17173.com/mmnews/news.php?find=&page=[��ҳ]', '1', '24', '<a href=\'[����]\' target=\'_blank\'>[����]</a>', '', '<table width="100%" border="0" cellspacing="0" cellpadding="00" >[����]<td height="40" valign="bottom"><div align="center"><font color="#e1139e">', '', '', 0, '', '', '');
INSERT INTO cjrule VALUES (4, 1, '������ҳ > �������� > ���������ȵ����ż���', 3, '', '', 'http://ent.163.com/special/00031HI0/entnews[��ҳ].html', '2', '5', '<li><a href="[����]">[����]</a></li>', '', '<!-- main -->[����]<!-- page -->', '', '', 1, '<div id="main" style="background:#F9FCFE;">\r\n<div class="page">[��ҳ����]</div>\r\n<div id="text">', '', '');
INSERT INTO cjrule VALUES (5, 1, '��üƵ�� > ��Ϸ��ü> ��ϷCOS', 3, '', '', 'http://mm.17173.com/show/cosplay.php?find=&page=[��ҳ]', '1', '13', '<a href=\'[����]\' target=\'_blank\'>[����]</a>', '', '<table width="100%" border="0" cellspacing="0" cellpadding="00" >[����]<td height="40" valign="bottom"><div align="center"><font color="#e1139e">', '', '', 0, '', '', '');
INSERT INTO cjrule VALUES (13, 1, 'howaa', 3, '', '', 'http://www.howaa.com/pics/gril/class_gril.asp?page=[��ҳ]', '1', '250', ' <a target="_blank" href="[����]">[����]</a></div>', '', '<script src="/JS/AdsJs/bwtextAD.js"></script><br>[����]<div align="center" style="margin:0 0 30 0">', '', 'howaa', 0, '', '', '');
INSERT INTO cjrule VALUES (21, 1, '���� > �������� > ��������', 3, '', '', 'http://news.sina.com.cn/photo/w/14[��ҳ].shtml  ', '300', '421', '<p><a href=[����] target=_blank>[����]</a><FONT', '', '<tr><td class=l17><font id="zoom" class=f14>[����]</font>\r\n	<br clear=all>\r\n	</td></tr>\r\n	\r\n	</table>\r\n\r\n	</div>\r\n\r\n<SCRIPT>', '', '', 0, '', '', '');
INSERT INTO cjrule VALUES (22, 1, 'TOM����Ƶ��', 3, '', '', 'http://flash.ent.tom.com/flash_pic_list.php?categoryid=35&action=Search&stype=User&perpage=12&colcount=1&order_rule=CreateTime&page=[��ҳ]', '1', '52', '<A HREF="[����]" TARGET="_blank"><IMG WIDTH="135" HEIGHT="90" BORDER="0" SRC="[����]"></A></TD>\r\n	</TR> \r\n	</TABLE><!--flashͼƬ������--></TD><TD><TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0" BGCOLOR="#FFFFFF"> \r\n<TR> <TD> <TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="1"> <TR BGCOLOR="#E6E6E6"> \r\n<TD>&nbsp;�Ƽ��Ǽ���<!--flash�Ǽ�-->[����]<!--flash�Ǽ�-->&nbsp;&nbsp;&nbsp;���ƣ�<!--flash����-->[����]<!--flash����-->', '', '</TABLE><!--flash���ƿ�ʼ--> <BR>[����]<tr height=30> <td align=center width=300><nobr></td><td align=center width=300><B>', '', '', 0, '', '', '');
INSERT INTO cjrule VALUES (15, 1, '�����ȷ� >> ʱ�в�ױ', 3, '', '', 'http://www.fadmy.com/Class/mlxf_sscz/mlxf_sscz0[��ҳ].asp', '1', '26', '��<a href=[����] target=_blank>[����]</a>', '', '<table width="98%" border="0" cellspacing="0" cellpadding="0" height="100%">[����]<td height="35"><p align="right">ת��', '', '', 1, '<hr noshade color=#C0C0C0 size=1>[��ҳ����]</tr>\r\n<tr>\r\n<td height="35"><p align="right">', '', '');
INSERT INTO cjrule VALUES (16, 1, '���� > ʱ���ȷ�', 3, '', '', 'http://www.pclady.com.cn/dress/ssxf/index_[��ҳ].html', '1', '20', '<a href="[����]" target="_blank">[����]</a><BR>', '', 'http://www.pclady.com.cn/dress/nq/0508/pic/20060126banner01.jpg" border=0>[����]<br><TABLE cellSpacing=1 cellPadding=0 width=500 align=center bgColor=#ffcdcd border=0>', '', '', 1, '<BR CLEAR=all> <DIV ALIGN=center>[��ҳ����]<div id=demo style=overflow:hidden;height:140;width:496;>', '', '');
INSERT INTO cjrule VALUES (19, 1, 'pconline', 2, '', 'http://www.pconline.com.cn/mobile/news/hgxz/index_1.html', '', '', '', '<a href="[����]" target="_blank" class=iblue>[����]</a> <BR>', '', '<table width="98%" border="0" align="center" cellpadding="0" cellspacing="0">[����]<td width="546" colspan="3" align="CENTER"', '', '', 2, '', '<a href="[����ҳ]" >[��һҳ]</a>', '');

INSERT INTO export VALUES (21, '', 'ecms(��Ѱ�)', 'localhost', 'root', '', 'ecms', 'phome_ecms_news', 'id|classid|newspath|userid|username|checked|truetime|dokey|filename|newstempid|checkuser|docheckuser|viewcheckuser|notdocheckuser|totaldown|title|newstime|befrom|newstext', '|1|[ʱ���ʽ:Y-m-d]|1|hmj�ɼ���|1|1137066547|1|[���:5,4]|1|,|,|,|0|1|[����]|[�ɼ�ʱ���ʽ:Y-m-d]|17173|[����]', 1140684122, '');
INSERT INTO export VALUES (26, '', 'pw', '127.0.0.1', 'root', '', 'phpwind', 'pw_threads|pw_tmsgs', 'tid|fid|author|authorid|subject|ifcheck|postdate|lastpost|lastposter#tid|ifsign|content', '[���±��]|5|huangmingj|1|[����]|1|[ʱ��]|[ʱ��]|huangmingj#[���±��]|3|[����]', 1140691276, 'pw_forums|topic|[��][��������]|WHERE fid =5\r\npw_forums|article|[��][��������]|WHERE fid =5');
INSERT INTO export VALUES (49, '4', 'dz 3.0', '127.0.0.1', 'root', '', 'dbdz', 'cdb_posts|cdb_threads', 'fid|tid|first|author|authorid|subject|dateline|message#tid|fid|author|authorid|subject|dateline', '1|[���±��]|1|huangmingj|1|[����]|[ʱ��]|[����] #[���±��]|1|huangmingj|1|[����]|[ʱ��]', 1140685721, ' �� ');
INSERT INTO export VALUES (30, '', 'nc', '127.0.0.1', 'root', '', 'get', 'datas', 'id|link_id|rules|title|body|author|url|date', '|[���±��]|10|[����]|[����]|[����]|[��ַ]|[�ɼ�ʱ��] ', 1140685603, '');
INSERT INTO export VALUES (50, '', 'pw432', '127.0.0.1', 'root', '', 'pw_432', 'pw_threads|pw_tmsgs', 'tid|fid|author|authorid|subject|ifcheck|postdate|lastpost|lastposter#tid|ifsign|content', '[���±��]|2|huangmingj|1|[����]|1|[ʱ��]|[ʱ��]|huangmingj#[���±��]|3|[����]', 1140832843, 'pw_forumdata|tpost|[��][��������]|WHERE fid = 2\r\npw_forumdata|topic|[��][��������]|WHERE fid = 2\r\npw_forumdata|article|[��][��������]|WHERE fid = 2');
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
				   $result .= "<p>���ڽ�����: ".$regs[1]." ���� ";
				   @mysql_query($query);
					if ($query)
					{
						$result .= "�ɹ�</p>\n";
					} else {
						$result .= "ʧ��</p>\n";
					}
               } else {
                   @mysql_query($query);
               }

           }
    }

$pages .= $result;

$steps = 5;
$next = '��һ�� >';

}

//---------5-----
if($step == 5 )
{
$pages = <<<PAGE2
								<tr>
									&nbsp;<td noWrap width="30%" style="font-family: Verdana, Tahoma, ����; font-size: 12px; line-height: 100%">�����˺�:</td>
									<td style="font-family: Verdana, Tahoma, ����; font-size: 12px; line-height: 100%">
									<input ��class="formfield" value="" name="name"></td>
								</tr>
								<tr>
									<td noWrap width="30%" style="font-family: Verdana, Tahoma, ����; font-size: 12px; line-height: 100%">
									��������:</td>
									<td style="font-family: Verdana, Tahoma, ����; font-size: 12px; line-height: 100%">
									<input class="formfield" name="pass"></td>
								</tr>
								<tr>
PAGE2;

					$title = '���������˺�';	
					$steps = ��;
					$next = '��һ�� >';

}


//---------��-----
if($step == "��" )
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

	<p>��װ�����Ѿ�˳��ִ����ϣ��뾡��ɾ�� install.php�����ⱻ���˶������á�</p>
			   <p>��л��ʹ��hmj�ɼ���.</p>
			   <p></p>
			   <p><li><a href="index.php">����ɼ���</a>
PAGE2;

					$title = '���';	
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

<title>hmj�ɼ��� V 1.0 ��װҳ��</title>
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
							<font color="#ffffff"><strong>hmj�ɼ��� V 1.0</strong></font></td>
						</tr>
					</table>
					
					<table border="0" width="100%" cellpadding="2">
						<tr>
							<td><b>{$title}</b> <br>
��
{$pages}
</td>
						</tr>
					</table>
					
<table border="0" width="604" height="27" cellpadding="10">
	<tr>
		<td width="273">
		<p dir="ltr"><a href="install.php?upgrade=1.1">�Ѱ�װ���ı����������ݿ⣬��ȷ��config.php�䱸��ȷ�������������1.1</a></td>
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