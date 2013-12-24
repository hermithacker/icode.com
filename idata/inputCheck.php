<?PHP
	/**
	*	数据验证
	*/
	$str_post = $_POST["inputUrl"];
	
	if(!$str_post){
		header("index.php");
	}
	else{
		echo "0";
	}
?>