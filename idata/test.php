<?php
	require_once("config.php");
	error_reporting(E_ALL^E_NOTICE);

	require_once("includes/iUrl.class.php");
	require_once("includes/mysql.class.php");//引入数据库类
	require_once("includes/pinyin.php");//将汉字转换成拼音
	$db	= new mysql("localhost","root","","igetdata");//初始化数据库类
	
	/* 测试数据
	//http://www.i3dmo.com/   
	//         
	//www.57zfw.com/
	*/
	define("HOST","www.china-designer.com/");
	//抓取模式:
	//1、为写缓存！
	//2、为存数据库+写缓存
	//3、3写缓存 + 读数据库抓图片
	define("PATTERN",2);
	//日志所在目录
	define('LOG_PATH', "error/".HOST.'/');
	//临时存储目录
	define('TEMP','temp/');
	//缓存所在目录
	define('CACHE',TEMP."cache/".HOST);
	//会员头像保存文件夹
	define('LOGO_IMG','images/logo_img/');
	//会员头像保存文件夹
	define('PRO_IMG','images/pro_img/');
	//建立路径文件夹
	makeDirs(LOG_PATH); //建立日志目录
	makeDirs(CACHE);	//建立缓存目录
	makeDirs(LOGO_IMG); //创建图片存储目录
	makeDirs(PRO_IMG);  //创建图片存储目录

	//$url = new url();
	//$url->getFileContent("http://www.china-designer.com/home/137795_1_1.htm");

	get_page_url();
/*
**功能：更具url获取html文件
**时间：2013年12月25日
**
**By:Laurence
**检查完毕
*/
function get_page_url($url){
	//获取缓存文件名（包括路径）
	$file_name = CACHE.md5($url).".html";
	$url = new url();

	$url->setUrl($url);

	if(file_exists($file_name)){
		$url -> setContent(file_get_contents($file_name));
		echo $url -> getContent();
	}else{
	//抓取指定路径的内容
		$url-> gather();
		$content = $url->getContent();
		file_put_contents($file_name,$content);
	}

	return $url;
}
	/*
**功能:获取会员基本信息资料
**参数:
**$url:页面的地址--显示会员基本信息的页面
**
**添加:采集了用户的头像信息
**检查完毕
*/
function get_m_info($url){
	$url = get_page_url($url);
	//取出换行字符
	$url-> noReturn();
	$url-> cut('<div class="modulecontent">','<center>',1);
	//获取裁剪后的内容
	$content = $url-> getContent();
	$content = iconv("gb2312","UTF-8",$content);
	echo $content;
	$member_info = array();
	preg_match_all('/<td[^>]*>(.*)<\\/td>/U',$content,$reg);//获取会员资料
	preg_match_all('/<img src=\"(.*?)\" title=\".*?\" id=\"PreviewImagesReplace\"/is',$content,$regimg);//获取图片头像
	//下载网上图片保存到本地
	get_original_image($regimg[1][0]);
	$member_info=array_merge($regimg[1],$reg[1]);
	return $member_info;
}
/*
**功能:获取图片信息
**
**返回:文件夹下"images/pro_img/"的图片信息
**格式限制:.gif/.jpg/.png/.bmp
**todo: 疑问:curl的插件是否被该项目使用??????????????????????????????????????????????
**存在疑问未解决
**2013年12月25日
*/
function get_original_image($url,$filename="") {
//	set_time_limit(24 * 60 * 60 * 60);//php set_time_limit函数的功能是设置当前页面执行多长时间不过期哦。
	if($url==""):return false;endif;
	$url = preg_replace('/ /','%20',$url);
	//如果未指定图片名字（包括图片存储路径）
	if($filename == "" ){
		$ext=strrchr($url,".");
		if($ext!=".gif" && $ext!=".jpg" && $ext!=".png" && $ext!=".bmp"):return false;endif;
		$filename='images/pro_img/'.date("dMYHis").$ext;
	}
	if (file_exists(dirname($filename)) && is_readable(dirname($filename)) && is_writable(dirname($filename))) {
		try {
			$ch = curl_init($url);
			$fp = @fopen($filename, 'w');
			curl_setopt($ch, CURLOPT_FILE, $fp);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_exec($ch);
			$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);
			fclose($fp);
			if ($code != 200) {
				@unlink($filename);
				GrabImage($url,$filename);
				throw new Exception('无法获得远程文件:'.$url." 到:".$filename);
			}
		} catch(Exception $e) {
			$filename = GrabImage($url,$filename);
			showErrMsg($e->getMessage());
		}
		showMsg("下载文件或者图片成功");
		return $filename;
	}
	return false;
}

/*
**功能:读取图片信息的方法
**参数:
**$url:网站路径
**$filename:文件名
**2013年12月25日
**检查完毕
*/
function GrabImage($url,$filename="") {
	//showMsg("保存图片地址:$url  名称：$filename");
	set_time_limit(24 * 60 * 60 * 60);//php set_time_limit函数的功能是设置当前页面执行多长时间不过期哦。
	if($url==""):return false;endif;
	if($filename=="") {
		$ext=strrchr($url,".");
		if($ext!=".gif" && $ext!=".jpg"):return false;endif;
		$filename=date("dMYHis").$ext;
	}
	ob_start();
	readfile($url);
	$img = ob_get_contents();
	ob_end_clean();
	$size = strlen($img);
	$fp2=@fopen($filename, "a");
	fwrite($fp2,$img);
	fclose($fp2);
	return $filename;
}

/*创建路径目录*/
function mkdir_path($path){
	//$path =dirname($path);
	$dirs = split('[/\\]',$path);
	$temp = dirname(__FILE__);
	//print_r($dirs);exit;
	foreach($dirs as $dirname){
		if($dirname){
			if(!file_exists($temp.'/'.$dirname)){
				mkdir($temp.'/'.$dirname);
			}
			$temp.='/'.$dirname;
			//echo $temp."<br/>";
		}
	}
}
//自动建立目录
function makeDirs($dirs='',$mode='0777'){
	$dirs=str_replace('\\','/',trim($dirs));
	if (!empty($dirs) && !file_exists($dirs)){
		makeDirs(dirname($dirs));//回调
		mkdir($dirs,$mode) or showErrMsg ('建立目录'.$dirs.'失败,请尝试手动建立!');
	}
}

/**
**功能:返回普通消息格式化后的字符串
**
**参数:
**$masage:需要显示字符串消息
**$separation:是否需要分离,在显示格式上有距离的偏差
*/
function showMsg($masage,$separation=false){
	global $is_show_msg;
	if($separation){
		echo '<div style="font-size:12px">'.$masage.'.............</div>';
	}else{
		if($is_show_msg) echo '<div style="margin:3px; margin-left:30px;font-size:12px">'.$masage.'.............</div>';
	}
}
/*返回错误消息格式化后的字符串*/
function showErrMsg($masage){
	echo '<div style="color:#F00;font-size:12px">'.$masage.'................</div>';
}


?>