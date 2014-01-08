<?php
	require_once("config.php");
	error_reporting(E_ALL^E_NOTICE);

	require_once("includes/iUrl.class.php");
	require_once("includes/mysql.class.php");//�������ݿ���
	require_once("includes/pinyin.php");//������ת����ƴ��
	$db	= new mysql("localhost","root","","igetdata");//��ʼ�����ݿ���
	
	/* ��������
	//http://www.i3dmo.com/   
	//         
	//www.57zfw.com/
	*/
	define("HOST","www.china-designer.com/");
	//ץȡģʽ:
	//1��Ϊд���棡
	//2��Ϊ�����ݿ�+д����
	//3��3д���� + �����ݿ�ץͼƬ
	define("PATTERN",2);
	//��־����Ŀ¼
	define('LOG_PATH', "error/".HOST.'/');
	//��ʱ�洢Ŀ¼
	define('TEMP','temp/');
	//��������Ŀ¼
	define('CACHE',TEMP."cache/".HOST);
	//��Աͷ�񱣴��ļ���
	define('LOGO_IMG','images/logo_img/');
	//��Աͷ�񱣴��ļ���
	define('PRO_IMG','images/pro_img/');
	//����·���ļ���
	makeDirs(LOG_PATH); //������־Ŀ¼
	makeDirs(CACHE);	//��������Ŀ¼
	makeDirs(LOGO_IMG); //����ͼƬ�洢Ŀ¼
	makeDirs(PRO_IMG);  //����ͼƬ�洢Ŀ¼

	//$url = new url();
	//$url->getFileContent("http://www.china-designer.com/home/137795_1_1.htm");

	get_page_url();
/*
**���ܣ�����url��ȡhtml�ļ�
**ʱ�䣺2013��12��25��
**
**By:Laurence
**������
*/
function get_page_url($url){
	//��ȡ�����ļ���������·����
	$file_name = CACHE.md5($url).".html";
	$url = new url();

	$url->setUrl($url);

	if(file_exists($file_name)){
		$url -> setContent(file_get_contents($file_name));
		echo $url -> getContent();
	}else{
	//ץȡָ��·��������
		$url-> gather();
		$content = $url->getContent();
		file_put_contents($file_name,$content);
	}

	return $url;
}
	/*
**����:��ȡ��Ա������Ϣ����
**����:
**$url:ҳ��ĵ�ַ--��ʾ��Ա������Ϣ��ҳ��
**
**���:�ɼ����û���ͷ����Ϣ
**������
*/
function get_m_info($url){
	$url = get_page_url($url);
	//ȡ�������ַ�
	$url-> noReturn();
	$url-> cut('<div class="modulecontent">','<center>',1);
	//��ȡ�ü��������
	$content = $url-> getContent();
	$content = iconv("gb2312","UTF-8",$content);
	echo $content;
	$member_info = array();
	preg_match_all('/<td[^>]*>(.*)<\\/td>/U',$content,$reg);//��ȡ��Ա����
	preg_match_all('/<img src=\"(.*?)\" title=\".*?\" id=\"PreviewImagesReplace\"/is',$content,$regimg);//��ȡͼƬͷ��
	//��������ͼƬ���浽����
	get_original_image($regimg[1][0]);
	$member_info=array_merge($regimg[1],$reg[1]);
	return $member_info;
}
/*
**����:��ȡͼƬ��Ϣ
**
**����:�ļ�����"images/pro_img/"��ͼƬ��Ϣ
**��ʽ����:.gif/.jpg/.png/.bmp
**todo: ����:curl�Ĳ���Ƿ񱻸���Ŀʹ��??????????????????????????????????????????????
**��������δ���
**2013��12��25��
*/
function get_original_image($url,$filename="") {
//	set_time_limit(24 * 60 * 60 * 60);//php set_time_limit�����Ĺ��������õ�ǰҳ��ִ�ж೤ʱ�䲻����Ŷ��
	if($url==""):return false;endif;
	$url = preg_replace('/ /','%20',$url);
	//���δָ��ͼƬ���֣�����ͼƬ�洢·����
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
				throw new Exception('�޷����Զ���ļ�:'.$url." ��:".$filename);
			}
		} catch(Exception $e) {
			$filename = GrabImage($url,$filename);
			showErrMsg($e->getMessage());
		}
		showMsg("�����ļ�����ͼƬ�ɹ�");
		return $filename;
	}
	return false;
}

/*
**����:��ȡͼƬ��Ϣ�ķ���
**����:
**$url:��վ·��
**$filename:�ļ���
**2013��12��25��
**������
*/
function GrabImage($url,$filename="") {
	//showMsg("����ͼƬ��ַ:$url  ���ƣ�$filename");
	set_time_limit(24 * 60 * 60 * 60);//php set_time_limit�����Ĺ��������õ�ǰҳ��ִ�ж೤ʱ�䲻����Ŷ��
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

/*����·��Ŀ¼*/
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
//�Զ�����Ŀ¼
function makeDirs($dirs='',$mode='0777'){
	$dirs=str_replace('\\','/',trim($dirs));
	if (!empty($dirs) && !file_exists($dirs)){
		makeDirs(dirname($dirs));//�ص�
		mkdir($dirs,$mode) or showErrMsg ('����Ŀ¼'.$dirs.'ʧ��,�볢���ֶ�����!');
	}
}

/**
**����:������ͨ��Ϣ��ʽ������ַ���
**
**����:
**$masage:��Ҫ��ʾ�ַ�����Ϣ
**$separation:�Ƿ���Ҫ����,����ʾ��ʽ���о����ƫ��
*/
function showMsg($masage,$separation=false){
	global $is_show_msg;
	if($separation){
		echo '<div style="font-size:12px">'.$masage.'.............</div>';
	}else{
		if($is_show_msg) echo '<div style="margin:3px; margin-left:30px;font-size:12px">'.$masage.'.............</div>';
	}
}
/*���ش�����Ϣ��ʽ������ַ���*/
function showErrMsg($masage){
	echo '<div style="color:#F00;font-size:12px">'.$masage.'................</div>';
}


?>