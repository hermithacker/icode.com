
<?PHP
  require_once("../global.php");
  require_once("../config.php");
  //引入数据库类
  require_once("../includes/idbcontroler.class.php");
  //定义数据库的变量
  $dbcontroler = new idbcontroler($dbarray["HOST"],$dbarray["NAME"],$dbarray["PASSWORD"],$dbarray["DATABASE"],$dbarray["PORT"],$dbarray["charset"]);
  
  //获取tilte的数据集
  $data = $dbcontroler->getOne("SELECT announceTitle FROM test.announcements;");
  $dataMenus   = array(0=>"categories",1=>"edit section",2=>"templates");
  $dataModules = array("Settings","Add a category","Edit categories","Categories","Options","Admin settings","Help");
  
  $smarty->assign("dataMenus",$dataMenus);
  $smarty->assign("dataModules",$dataModules);
  $smarty->assign("title","登陆主页面");
  $smarty->assign("name",$data);
  
  //获取数据表的内容
  $smarty->assign("tablename",'新闻公告');
  $results = $dbcontroler->query("SELECT 1 FROM test.announcements;");
  $filedname =  $dbcontroler->list_fields("announcements");
  //截取数组，只保留显示部分的值
  //print_r($filedname);
  //$filedname = array_splice($filedname,0,-3);
  //print_r($filedname);exit();
  //表的字段名列表
  $smarty->assign("tableHeaderName",$filedname);
  $nums = $dbcontroler->num_rows($results); 
  //表查询的总条数
  $smarty->assign("tableDataCount",$nums);
  //将查询的数据集转化成对象
  $resultslim = $dbcontroler->query("SELECT * FROM test.announcements limit 1,8;");
  
  $otableData = array();
  while($obj = $dbcontroler->fetch_assoc($resultslim)){
  		array_push($otableData,$obj);
  }
  $smarty->assign("tableData",$otableData);

  $smarty->display("announcements.tpl");
  
?>