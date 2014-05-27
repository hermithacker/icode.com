<?PHP
	//绝对路径
	//define("APP_PATH", dirname(__FILE__)."/");

	//配置文件：网站基本信息
	$website["name"] = "项目后台管理";
	$website["code"] = "project";
	$website["host"] = "http://localhost:8080/iteamblog.com/admin";

	//数据库连接配置
	$dbarray["HOST"]       ="localhost";
	$dbarray["NAME"]       ="root";
	$dbarray["PASSWORD"]   ="root";
	//$dbarray["PASSWORD"]   ="";
	$dbarray["DATABASE"]   ="iprojects";
	$dbarray["PORT"]	   ="3306";
	$dbarray["charset"]    ="utf8";
	
	//日志文件的存储路径
	$dbarray["log"]        =1;
	$dbarray["logpath"]    = dirname(__FILE__)."/log/";

?>