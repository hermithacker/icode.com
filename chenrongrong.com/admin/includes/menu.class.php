<?PHP

require_once("../init.php");
require_once("sql.php");

class menu{
	
//获取后台的菜单列表
function getparentmenulist(){
	global $db;
	global $sys_parentmenus;
	$res = $db->query($sys_parentmenus);
	$menuData = array();
  	while($obj = $db->fetch_assoc($res)){
		//判断是否显示
		if($obj["flag"]==1 && $obj["parmid"]==0 && $obj["haschild"]==1){
			array_push($menuData,$obj);
		}
  	}
	
	return $menuData;
}

//获取后台的菜单列表
function getchlidmenulist($parentmenuid){
	global $db;
	global $sys_childmenus;
	$res = $db->query($sys_childmenus.$parentmenuid);
	
	$menuData = array();
	while($obj = $db->fetch_assoc($res)){
		//判断是否显示
		if($obj["flag"]==1 && $obj["haschild"]==0){
			array_push($menuData,$obj);
		}
	}
	
	return $menuData;
}

}

?>