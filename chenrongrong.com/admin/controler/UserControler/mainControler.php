<?PHP
  require_once("../../global.php");
  require_once("../../config.php");
  //引入数据库类
  require_once("../../includes/idbcontroler.class.php");
  //定义数据库的变量
  $dbcontroler = new idbcontroler($dbarray["HOST"],$dbarray["NAME"],$dbarray["PASSWORD"],$dbarray["DATABASE"],$dbarray["PORT"],$dbarray["charset"]);
  
  //获取tilte的数据集
  //$data = $dbcontroler->getOne("SELECT loginname FROM test.memberes;");
  $data = $dbcontroler->getOne("SELECT announceTitle FROM test.announcements;");
  	
  $smarty->assign("title","登陆主页面");
  $smarty->assign("name",$data);
  $dataMenus   = array(0=>"categories",1=>"edit section",2=>"templates");
  $dataModules = array("Settings","Add a category","Edit categories","Categories","Options","Admin settings","Help");
  $smarty->assign("dataMenus",$dataMenus);
  $smarty->assign("dataModules",$dataModules);
  $smarty->display("UserManager/main.html");
  
?>