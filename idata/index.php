<?PHP
/**
* 互联网数据抓取宝典
* 
*
* By Laurence Chen  2013年11月28日
*/
	require_once("config.php");

	require_once("includes/iUrl.class.php");
	require_once("includes/mysql.class.php");//引入数据库类
	require_once("includes/pinyin.php");//将汉字转换成拼音

	$db	= new mysql("localhost","root","","igetdata");//初始化数据库类
	
	/* 测试数据
	//http://www.i3dmo.com/   
	//www.china-designer.com/         
	//www.57zfw.com/
	*/
	$host_str = $_POST["inputUrl"];
	define("HOST","www.china-designer.com/index.htm");
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
	makeDirs(LOG_PATH);//建立日志目录
	makeDirs(CACHE);//建立缓存目录
	makeDirs(LOGO_IMG);//创建图片存储目录
	makeDirs(PRO_IMG);//创建图片存储目录

	//抓取招标信息的主控程序
	function getTender(){
		//最小页数
		$min=1;
		//最大页数10页
		$max=10;
		$m_list_page_url = array();
		showMsg("开始找到招标信息所有分页的链接");
		
		for($i=$max ; $i>=$min;$i--){
			$m_list_page_url[] = "http://www.57zfw.com/zbmore.aspx?page=".$i;
		}
		showMsg("开始招标信息的详细信息的链接");
		foreach($m_list_page_url as $url){
			echo $url.".................................................<br/>";
			$info_url = get_info_url($url);
			foreach($info_url as $t_url){
				$tender_info = get_tender_info("http://www.57zfw.com/".$t_url);
				showMsg("开始添加到数据库");
				$id=insert_tender($tender_info);
				if($id>0){
					showMsg("保存数据完成...................................");
				}
				else{
					showErrMsg("保存失败，失败可能是已经入库或者保存数据库失败！");
					continue;
				}
			}
			//break;
		}
	}


	//保存招标信息到数据库 // 表名：Tender
	function insert_tender($tender_info){
		global $db;
		if(($db->getOne("select Tenderid from Tender where TenderName='".trim($tender_info[0])."'"))){
			showErrMsg("此招标信息&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".trim($tender_info[0])."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;已经入库");
			return 0;
		}
		else{
			$data=array(
				'TenderName'=>trim($tender_info[0]),//招标人姓名
				'TenderType'=>trim($tender_info[9]),//服务模式
				'TenderType1'=>trim($tender_info[6]),//装修户型
				'TenderArea'=>trim($tender_info[3]),//装修面积
				'Tendermoney'=>trim($tender_info[7]),//预算价格
				'TenderFloor'=>trim($tender_info[8]),//房屋用途
				'starttime'=>trim($tender_info[5]),//交房时间
				'Tendertell'=>'13678457456',
				'Tenderqq'=>'932133186',
				'Tenderadss'=>'重庆',
				'Tendercount'=>'暂无详细说明',//详细说明
				'endtime'=>trim($tender_info[11]),//装修风格
				'ctime'=>trim($tender_info[12])//发布时间
				);
				print_r($data);
			$db->Add('tender',$data);
			return $db->insert_id();
		}
			return 0;
	}
	//查找详细页面的信息
	function get_tender_info($url){
		echo $url."<br/>";
		$ugs = get_page_ugs($url);
		$ugs->noReturn();
		$ugs->cut('<div class="main620t">','<div id="newspagecode">');
		$content = $ugs->getContent();
		//echo $content;exit;
		preg_match_all('/<dd>(.*?)<\/dd>/is',$content,$reg);
		//print_r($reg);exit;
		return $reg[1];
	}

	//查找到每一页的详细信息的链接
	function get_info_url($url){
		echo $url."<br/>";
		$ugs = get_page_ugs($url);
		$ugs->noReturn();
		$ugs->cut('<div class="main620t">','<div id="newspagecode">');
		$content = $ugs->value_;
		//$content = iconv("gb2312","UTF-8",$content);
		//echo $content;
		//preg_match_all('/<a class=\"f13zb\" target=\"_blank\" href=\"(.*?)\" title=\"(.*?)\">(.*?)<\/a>/is',$content,$reg);//
		preg_match_all('/<a\s.*?title=\"(.*?)\"\s.*?href=\"(.*?)\".*?/is',$content,$reg);

		//print_r($reg[2]);exit;
		return $reg[2];

	}
?>