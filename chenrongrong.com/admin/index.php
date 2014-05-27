<?PHP
  require_once("config.php");
  //注意Location后面有一个" "
  $url = "controler/UserControler/mainControler.php";
  //$url = "../../controler/UserControler/mainControler.php";
  //echo $url;exit();
  //header("Location: $url");
	
  echo "<a href='$url' title='测试'>主页面</a>";
?>