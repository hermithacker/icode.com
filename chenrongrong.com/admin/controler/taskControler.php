<?PHP
  require_once("../global.php");
  require_once("../config.php");
  
  require_once("../includes/menu.class.php");
  
  $menu = new menu();
  $fathermenu = $menu->getparentmenulist();
  
  $childmenu = $menu->getchlidmenulist("100000");
  
  $smarty->assign("title","菜单");
  $smarty->assign("name","admin");
  
  $smarty->assign("fathermenu",$fathermenu);
  $smarty->assign("childmenu",$childmenu);
  
  $smarty->display("task.tpl");
  
?>