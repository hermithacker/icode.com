<?PHP
/*
**文件名：global.php
**功能说明：设置默认常量
**创建时间：2014年3月13日
**修改记录：
————2014年3月14日  添加了  AppPath变量	  By Laurence 
*/	

header("Content-Type:text/html;charset=utf-8");  // 设置全局编码
error_reporting(E_ALL && ~E_NOTICE);				 // 取消通知提示

define("DEBUG",0);								 //Smarty模板bug开启：0表示不开启
//define("APP_PATH",$_SERVER['DOCUMENT_ROOT']);    //站点根目录
define("APP_PATH",$_SERVER['DOCUMENT_ROOT']."/iteamblog.com/admin/");
define("SMARTY_PATH",APP_PATH."libs/");			 //smarty引用路径
define("TPL_PATH",APP_PATH."templates/");	     //自定义前台模板templates目录路
define("CACHE_PATH",APP_PATH."cache/");          //缓存目录
define("CSTART",0);				                 //缓存开启状态
define("CTIME",60*60*24*7);                      //smarty缓存时间设置
define('LEFT','{');                              //smarty左边界
define('right','}');                             //smarty右边界

//smarty 前后台配置说明
include SMARTY_PATH.'Smarty.class.php';			 //引入smarty模板
$smarty = new Smarty();
$smarty->setTemplateDir(TPL_PATH);
$smarty->setCompileDir(TPL_PATH.'/templates_c/');  //php模板机制解析文件

$smarty->setCacheDir(CACHE_PATH);
$smarty->setCaching(CSTART);
$smarty->setcache_lifetime(CTIME);
$smarty->left_delimiter=LEFT;
$smarty->right_delimiter=right;
$smarty->debugging=DEBUG;

?>