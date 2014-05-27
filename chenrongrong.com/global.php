<?PHP
define("DEBUG",0);								 //Smarty模板bug开启：0表示不开启

define("APP_PATH",$_SERVER['DOCUMENT_ROOT']."/chenrongrong.com/");
define("SMARTY_PATH",APP_PATH."libs/");			 	//smarty引用路径
define("TPL_PATH",APP_PATH."templates/");	     	//自定义前台模板templates目录路
define("CACHE_PATH",APP_PATH."cache/");          	//缓存目录
define("CSTART",0);				                 	//缓存开启状态
define("CTIME",60*60*24*7);                      	//smarty缓存时间设置
define('LEFT','{%');                              	//smarty左边界
define('right','%}');                             	//smarty右边界

//smarty 前后台配置说明
include SMARTY_PATH.'Smarty.class.php';			 	//引入smarty模板
$smarty = new Smarty();
$smarty->setTemplateDir(TPL_PATH);
$smarty->setCompileDir(TPL_PATH.'/templates_c/');  //php模板机制解析文件

$smarty->setCacheDir(CACHE_PATH);
$smarty->setCaching(CSTART);
$smarty->setcache_lifetime(CTIME);
$smarty->left_delimiter=LEFT;
$smarty->right_delimiter=right;
$smarty->debugging=DEBUG;

//网站数据库信息
require_once("class/idbcontroler.class.php");//引入数据库类
$db	= new idbcontroler($db["HOST"],$db["NAME"],$db["PASSWORD"],$db["DATABASE"],$db["PORT"],$db["charset"]);

?>