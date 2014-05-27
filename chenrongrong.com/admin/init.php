<?PHP
	error_reporting(E_ALL^E_NOTICE);
	header("Content-Type:text/html;charset=utf-8");
	//不加限制，不过要小心使用 设置php内存使用限制 "-1" 时为不加限制
	ini_set('memory_limit', '-1');
	//设置程序最大运行时间、默认为60秒，单位为s
	ini_set('max_execution_time',30*60*60*60);
	//设置时区
	date_default_timezone_set('Asia/Shanghai');
	//载入数据库文件
	include("config.php");
	require_once("includes/idbcontroler.class.php");//引入数据库类
	
	$db	= new idbcontroler($dbarray["HOST"],$dbarray["NAME"],$dbarray["PASSWORD"],$dbarray["DATABASE"],$dbarray["PORT"],$dbarray["charset"]);
	
?>