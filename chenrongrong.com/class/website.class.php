<?PHP
/*
**站点管理的类
*/
class website{

//获取常用的五个网站
function getWebsiteUseTop5(){
	global $db;
	 //将查询的数据集转化成对象
    $results = $db->query("SELECT webname,company,websites,ismember FROM iprojects.websites ORDER BY `clicktimes` desc limit 0,4;");
    $webdata = array();
    while($obj = $db->fetch_assoc($results)){
		array_push($webdata,$obj);
    }
  	return $webdata;
}

//获取该页面常用的链接
function getWebsiteLink(){
	
}	
}
?>