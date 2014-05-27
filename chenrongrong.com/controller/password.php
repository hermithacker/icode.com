<?PHP
require_once("../config.php");
require_once("../global.php");

require_once("../class/website.class.php");

//常用的站点排名链接
$websiteAlexa = 'http://alexa.chinaz.com/';
$web = new website();
$websiteUseTop5 = $web->getWebsiteUseTop5();

$smarty->assign("websiteAlexa",$websiteAlexa);
$smarty->assign("websiteUseTop5",$websiteUseTop5);
$smarty->display("password.tpl");
?>