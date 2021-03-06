<?PHP
/**
*** 互联网数据抓取宝典
*** 
*** By Laurence Chen  2013年11月28日
*/
	require_once("config.php");
	//控制错误的显示级别
	error_reporting(E_ALL^E_NOTICE);
	require_once("includes/iUrl.class.php");
	require_once("includes/mysql.class.php");//引入数据库类
	require_once("includes/pinyin.php");//将汉字转换成拼音

	$db	= new mysql("localhost","root","","igetdata");//初始化数据库类
	
	/* 测试数据
	//http://www.i3dmo.com/   
	//www.china-designer.com/         
	//www.57zfw.com/
	*/
	//$host_str =urldecode($_GET["param"]);
	//define("HOST",$host_str);
	
	define("HOST","www.china-designer.com/");  //[注意:]传入的url格式: www.china-designer.com/ 不能有:http://
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


	main();
/**
** 采集"http://www.china-designer.com"的主方法
**************************************************************
**1_需要解析网站传递数据的参数名(了解网站的架构设计方式,点击查看传参的内容)
**	{keywords= 
	mainbest=0 
	designerclassid=-1
	location=4
	sel_city=0
	job=2
	charges=0
	PageSize=12
	vType=2
	birthday1=0
	birthday2=0
	AccountID=
	PageNO=$i}
**
**
**
*/
function main(){
	$min=17;//最小页数
	$max=17;//最大页数
	$m_list_page_url = array();
	for($i=$min ; $i<=$max;$i++ ){
		$m_list_page_url[] = "http://www.china-designer.com/ezx/new_designer_s/?keywords=&mainbest=0&designerclassid=-1&
		location=4&sel_city=0&job=2&charges=0&PageSize=12&vType=2&birthday1=0&birthday2=0&AccountID=&PageNO=".$i;
	}

	//得到重庆会员的家园编号
	$home_id_arr = array();
	showMsg("开始抓取&nbsp;&nbsp;$min&nbsp;&nbsp;页到&nbsp;&nbsp;$max&nbsp;&nbsp;页的所有会员编号",true);
	foreach($m_list_page_url as $url){
		$home_id_arr = array_merge($home_id_arr,get_home_id($url));
	}
	showMsg('会员编号抓取完毕.............................',true);

	//进入读取会员编号的会员信息页面
	$m_info_list = array();
	foreach($home_id_arr as $id){
		$url_member = "http://www.china-designer.com/home/".$id."_1_1.htm";
		$url_proarea = "http://www.china-designer.com/home/".$id."_2.htm";//设计师作品
		$url_news = "http://www.china-designer.com/home/".$id."_4.htm";//设计师文章

		showMsg('<font style="font-size:20px;color:blue">开始抓取会员编号为'.$id.'的会员资料信息</font>');
		$m_info_list = get_m_info($url_member);//获取会员信息编号
		//print_r($m_info_list);exit;   //可以测试出是否取出会员的信息
		$memberid=insert_member($m_info_list);//添加到数据库去或者是已经插入啦的取得会员ID号
		showMsg("<font style='font-size:20px;color:red'>插入数据库的会员ID为：".$memberid."</font>",true);
		//die($memberid);

		showMsg('<font style="font-size:20px;color:blue">开始抓取会员文章信息</font>',true);
		$m_newslist_url=get_news_list($url_news);
		foreach($m_newslist_url as $k=>$v){
			if($v!="#"){
				$news_info=get_news_info("http://www.china-designer.com/home/".$v);
				showMsg('开始保存文章到数据库');
				insert_news($news_info,$memberid,$m_info_list['4']);
				showMsg('完成保存文章到数据库');
			}
		}

		showMsg('<font style="font-size:20px;color:blue">开始抓取会员编号为'.$id.'的作品信息</font>');
		$m_proarea_list = get_m_proarea($url_proarea);//获取单个会员的 作品url列表
		
		showMsg('<br/>此会员共有'.count($m_proarea_list).'个作品信息');
		//循环抓取设计师作品信息
		foreach($m_proarea_list as $pro_url){
				$get_m_pro_info = get_m_pro_info('http://www.china-designer.com/home/'.$pro_url);//获取每个作品信息的页面
				showMsg('开始保存作品到数据库');
				insert_proarea($get_m_pro_info,$memberid);
				showMsg('作品保存到数据库成功');
				//break;//只获取一个会员的信息，调试用
		}
		showMsg('<font style="font-size:20px;color:blue">完成抓取会员ID为'.$id.'的作品和文章信息</font>');

		//break;//只获取一个会员的信息，调试用
	}

}

/*
**功能：会员列表页面的 会员家园ID编号
**时间：2013年12月25日
**
**By:Laurence
**检查完毕 [$reg:是一个二维数组,[0]表示复选框] 
*/
function get_home_id($url){
	$clurl = get_page_url($url);
	$clurl-> noReturn();
	$content = $clurl->getContent();
	preg_match_all('/<input name="c_accountid" type="checkbox" value="(\\d+)" \\/>/sim',$content,$reg);
	
	return $reg[1];
}
/*
**功能：将网络路径的文件打开并保存在本地的文件中
**时间：2013年12月25日
**
**By:Laurence
**检查完毕
*/
function get_page_url($url){
	//获取缓存文件名（包括路径）
	$file_name = CACHE.md5($url).".html";
	$clurl = new url();
	$clurl->setUrl($url);
	if(file_exists($file_name)){
		$clurl -> setContent(file_get_contents($file_name));
	}else{
		//抓取指定路径的内容
		var_dump($clurl);
		$clurl-> gather();
		$content = $clurl->getContent();
		file_put_contents($file_name,$content);
	}
	return $clurl;
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
	$clurl = get_page_url($url);
	//取出换行字符
	$clurl-> noReturn();
	$clurl-> cut('<div class="modulecontent">','<center>');
	//获取裁剪后的内容
	$content = $clurl-> getContent();
	$content = iconv("gb2312","UTF-8",$content);
	
	$member_info = array();
	preg_match_all('/<td[^>]*>(.*)<\\/td>/U',$content,$reg);//获取会员资料
	preg_match_all('/<img src=\"(.*?)\" title=\".*?\" id=\"PreviewImagesReplace\"/is',$content,$regimg);//获取图片头像
	//下载网上图片保存到本地
	get_original_image($regimg[1][0]);
	$member_info=array_merge($regimg[1],$reg[1]);
	return $member_info;
}

/**
***功能:将会员信息插入到数据库中
***
***参数:$m_info_list:一维数组
***
***检查完毕
**/
function insert_member($m_info_list){
	global $db;
	
	//去除js代码和空格代码
	$memberadss=$m_info_list[26];
	$adss = preg_replace("'<script(.*?)<\/script>'is","",$memberadss);
	$adss = str_replace("&nbsp;","",$adss);
	$adss = str_replace("  ","",$adss);
	
	$loginname=pinyin(iconv("UTF-8","gb2312",$m_info_list[4]));
	// echo $loginname; exit; 调试pingyin这个类库是否引入

	if(strlen($loginname)>15){
		$loginname=substr($loginname,0,15);
	}
	if(strlen($loginname)<6){
		$loginname=$loginname.'ers'; //拼接登陆名称
	}
	showMsg("会员ID：$m_info_list[2]会员名称：$loginname &nbsp;&nbsp;会员信息到数据库",true);
	if(($db->getOne("select memberid from member where loginname='".$loginname."'"))){
		$memberquery=$db->GetRs("member","memberid","where loginname='$loginname'");
		$memberid=$memberquery['memberid'];
		showErrMsg("会员ID：$m_info_list[2]会员名称：".$member_info_m['loginname']."已存入数据库>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>",true);
		return $memberid;
	}
	else{
		$ext=strrchr($m_info_list[0],".");
		$filename=get_original_image($m_info_list[0],'images/logo_img/'.$loginname.$ext);//下载网上图片保存到本地
		$DateBirth=$m_info_list[10];//出生年月
		if($DateBirth!=''){
			$DateBirth=str_replace("年","-",$DateBirth);
			$DateBirth=str_replace("月","-",$DateBirth);
			$DateBirth=str_replace("日","-",$DateBirth);
		}
		$member_info_m=array(
			'loginname'=>$loginname,
			'logopwd'=>'adminadmin',  //默认密码
			'membertypeid'=>'1',
			'memberlogo'=>'http://localhost:8888/idata/'.$filename,//会员头像      // 发布修改:URL
			'membername'=>trim($m_info_list[4]),//会员名称
			'membersex'=>trim($m_info_list[8]),//会员性别
			'memberadss'=>$adss,//所在区域
			'membertell'=>'11111111111',//会员电话
			'memberqq'=>'999999999',//会员QQ
			'memberemail'=>'999999999@qq.com',//会员邮箱
			'memberwork'=>trim($m_info_list[12]),//工作单位
			'memberpost'=>$m_info_list[30],//会员职位
			'membercount'=>delhtml(trim($m_info_list[54])),//会员介绍
			'DateBirth'=>$DateBirth,//出生年月
			'College'=>trim($m_info_list[20]),//毕业学院
			'Professional'=>trim($m_info_list[22]),//专业
			'Workdate'=>trim($m_info_list[18]),//工作年限
			'Education'=>trim($m_info_list[20]),//学历
			'sjstype'=>trim(delhtml($m_info_list[24])),//设计师类型
			'sjszc'=>trim(delhtml($m_info_list[46])),//设计师专长
			'workname'=>trim(delhtml($m_info_list[42])),//设计收费
			'ctime'=>date('Y-m-d')//注册时间
		);
		$db->Add('member',$member_info_m);
		showMsg("-----------会员ID：$m_info_list[2]会员名称：$m_info_list[4]&nbsp;&nbsp;会员信息入库成功----------------------",true);
		return $db->insert_id();
	}
}
/*
**功能:保存会员作品信息到数据库
**参数:$pro_info作品信息，$memberid:会员编号
**
**检查完毕
**By Laurence.Chen 2014年1月27日
**
*/
function insert_proarea($pro_info,$memberid){
	global $db;
	$title=trim($pro_info[0][0]);//作品名称  可能是  默认文件夹
	$count=trim($pro_info[1][0]);//作品说明  介绍
	if($title=="默认文件夹"){   //???? --> $title,"默认文件夹" 的字符串长度不一样
		$title="我的作品";
		$count="";
	}
	if(false){//($db->getOne("select proareaid from proarea where proareaname='".$title."' and memberid=".$memberid))
		showErrMsg("此作品---".$title."---已经入库>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>");
		return;
	}
	else{
		$minaji=mt_rand(45, 150);//生成随机面积
		$money=$minaji*1000;
		$db->Add('proarea',array(
			'memberid'=>$memberid,
			'proareaname'=>$title,//标题
			'proareaarea'=>$minaji,//作品面积
			'proareacount'=>$count,//作品说明
			'proareamoney'=>$money,//作品金额
		));
		$pro_id=$db->insert_id();
		
		foreach($pro_info[2] as $info_url){
			$pro_info_count=get_pro_info_count("http://www.china-designer.com/home/".$info_url);
			$filename=get_original_image($pro_info_count[1]);//下载网上图片保存到本地
			if($filename==''){
				echo '作品图片下载失败';
				continue;
			}
			else{
				echo '作品图片下载成功';

				$db->Add('proareapic',array(
					'proareaid'=>$pro_id,
					'proareapicname'=>$pro_info_count[0],//标题
					'proareapiccount'=>'',//说明
					'proareapicadss'=>'http://localhost:8888/idata/'.$filename,//图片地址   //发布修改: URL 
					'proart'=>$pro_info_count[2],//标签属性
					'protype'=>$pro_info_count[3],//图片设计类型
					'ctime'=>date('Y-m-d'),//上传时间
				));
				echo "作品图片保存数据库成功";
			}
			//break;
		}
	}
}

/*
***功能:抓取每个作品下的每个做作品图片的信息
***参数:页面的url 
***
***By laurence.Chen 2014年2月8日
***检查完毕
*/
function get_pro_info_count($url){
	$clurl = get_page_url($url);
	$clurl->noReturn();
	$clurl->cut('<div class="works-catalogInfo-space">','</div><div class="clear"></div>');
	$content = $clurl->getContent();
	$content = iconv("gb2312","UTF-8",$content);
	//echo $content;exit;
	//<img src="//"   border="0" alt="//" />
	preg_match_all("/<span  class=\"title\">(.*?)<\/span>/i",$content,$title);///作品图片标题
	//print_r($title);exit;
	preg_match_all("/<span class=\"ftColor-hui\"><a\s.*?<img src=\"(.*?)\"   border=\"0\" alt=\"(.*?)\" \/>/i",$content,$val);//作品标签属性
	//print_r($val);exit;
	//处理标签属性开始
	$t=strrchr($val[2][0],'-');
	if(stripos($t,'万')>0){
		$t=substr($t,stripos($t,'万'),strlen($t));
		if(stripos($t,',')>0){
			$t=substr($t,stripos($t,',')+1,strlen($t));
		}
		if(trim($t)=='万' || trim($t)=='万以上'){
			$t='';
		}
	}
	//处理标签属性结束
	//echo $t;
	preg_match_all("/<strong>项目类型<\/strong>：(.*?)<div/",$content,$key);
	//print_r($key);
	$pro_info_row[0]=delhtml($title[1][0]);
	$pro_info_row[1]=delhtml($val[1][0]);
	$pro_info_row[2]=$t;
	$pro_info_row[3]=delhtml($key[1][0]);
	//print_r($pro_info_row);exit;
	return $pro_info_row;
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

/*
**功能:获取图片信息
**
**返回:文件夹下"images/pro_img/"的图片信息
**格式限制:.gif/.jpg/.png/.bmp
**
** By Laurence.Chen 2014年2月8日
**检查完毕
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
**功能: 保存会员文章到数据库
**
** By Laurence.Chen  2014年2月8日
** 检查完毕
*/
function insert_news($news_info,$memberid,$memberbname){
	global $db;
	if(($db->getOne("select newsid from news where newstitle='".trim($news_info[0])."'"))){
		showErrMsg("此文章已经入库>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>");
		return;
	}
	else{
		$db->Add('news',array(
			'memberid'=>$memberid,
			'newstitle'=>delhtml(trim($news_info[0])),
			'newsAuthor'=>$memberbname,
			'newsSummary'=>'http://www.52zsj.com',
			'newstype'=>delhtml(trim($news_info[1])),
			'newscount'=>delhtml(trim($news_info[2])),
			'ctime'=>date('Y-m-d')
		));
	}
	return;
}


/*
**功能:获取单个作者文章的URL
**参数:网页的url
**
** By Laurence.Chen 2014年1月27日
** 检查完毕
*/
function get_m_proarea($url){
	//echo $url;exit;
	$clurl = get_page_url($url);
	$clurl->noReturn();
	$clurl->cut('<div class="title_box">','</div class="title_box">');
	$content = $clurl->getContent();
	$content = iconv("gb2312","UTF-8",$content);
	//echo $content;exit;
	$member_proarea = array();
	preg_match_all("'<\s*a\s.*?href\s*=\s*([\"\'])?(?(1)(.*?)\\1|([^\s\>]+))[^>]*>?(.*?)</a>'isx",$content,$reg);//获取会员作品名称的链接
	$cont=count($reg[2])-1;
		unset($reg[2][0]);//去掉第一个元素
		unset($reg[2][1]);//去掉第二个元素
		if(count($reg[2])!=1){
			unset($reg[2][$cont]);//去掉左后一个元素
		}
	//print_r($reg[2]);exit;
	return $reg[2];
}

/*
**功能:获取会员每个作品信息
**参数:网页地址
**
**By Laurence 2014年1月27日
**检查完毕
*/
function get_m_pro_info($url){
	$clurl = get_page_url($url);
	$clurl->noReturn();
	$clurl->cut('<div class="works-catalogInfo-space">','</div><div class="clear"></div>');
	$content = $clurl->getContent();
	$content = iconv("gb2312","UTF-8",$content);
	//echo $content;exit;
	$member_proimg = array();
	$member_proname	= array();
	preg_match_all('/<span class=\"title\" id=\"(\d+)">(.*)<\/span>/isU',$content,$title);//匹配作品名称
	showMsg('<br/><br/>作品标题：'.$title[2][0]);
	preg_match_all('/<span id=\"bakup\">(.*)<\/span>/isU',$content,$conet);//匹配作品说明
	showMsg('作品介绍：'.$conet[1][0]);
	///<A href=.*pageNO=(.*?)>末页<\/a>/iU
	preg_match_all('/下一页<\/a>.*<A href=(.*?)>末页<\/a>/m',$content,$nextpage);//匹配是否有下一页/pageNO=(\d)>/is
	//print_r($nextpage[1]); exit;
	$p = explode('pageNO=',$nextpage[1][0]);
	//print_r($p);exit;
	showMsg('作品图片页数：'.$p[1].'&nbsp;&nbsp;页');
	$page_url=array();
	if($nextpage[1]){
		for($page=1;$page<=$p[1];$page++){
			$page_url[$page]="http://www.china-designer.com/home/".$p[0]."pageNO=".$page;
		}
	}
	else{
			$page_url[]=$url;
	}
	showMsg("开始抓取每页作品信息的url链接......................");
	$pro_url_info_one_url=array();
	foreach($page_url as $p_url){
		$m_pro_info_array_url=get_m_pro_urllist($p_url);
		$pro_url_info_one_url=array_merge($pro_url_info_one_url,$m_pro_info_array_url);
		//break;
	}
	showMsg("这个文件夹下的前部url链接抓取完毕......................");
	//print_r($pro_url_info_one_url);print_r($m_pro_info_array_url);exit;
	$member_proarea[0]=$title[2];
	$member_proarea[1]=$conet[1];
	$member_proarea[2]=$pro_url_info_one_url;
	return $member_proarea;
}


/*
**功能:获取作品信息的下一页的数据
**参数:网页地址的URL
**
**
**By Laurence.Chen 2014年1月27日
**检查完毕
*/
function get_m_pro_urllist($url){
	$url = get_page_url($url);
	$url->noReturn();
	$url->cut('works-catalogInfo-space','</div><div class="clear"></div>');
	$content = $url->getContent();
	$content = iconv("gb2312","UTF-8",$content);
	//echo $content;exit;
	preg_match_all("/<td align=\"left\"><a href=\"(.*?)\" target=_blank id=\"(.*?)\">(.*?)<\/a>/is",$content,$reg);
	//print_r($reg[1]);exit;
	return $reg[1];
}

/*
**功能:抓取文章信息
**
**By Laurence.Chen 2014年2月8日
**检查完毕
*/
function get_news_list($url){
	$clurl = get_page_url($url);
	$clurl->noReturn();
	$clurl->cut('<div class="white-space maxWidth1">','</div><div class="clear"></div>');
	$content = $clurl->getContent();
	$content = iconv("gb2312","UTF-8",$content);
	//echo $content; exit;
	$m_newslist_url=array();
	preg_match_all('/<A href=(.*?)>(.*?)<\/a>/s',$content,$nextpage);  //匹配是否有下一页/pageNO=(\d)>/is
	//去除重复的url
	if(count($nextpage[1])>2){
		unset($nextpage[1][count($nextpage[1])-1]);
		unset($nextpage[1][count($nextpage[1])-1]);
	}
	//print_r($nextpage[1]);
	preg_match_all("/<a href=\"(.*?)\"/is",$content,$newurl);//匹配文章标题上的链接
	$newurl=array_unique($newurl[1]);
	$m_newslist_url=array_merge($m_newslist_url,$newurl);
	if($nextpage[1]){
		foreach($nextpage[1] as $key=>$nexturl){
			$newsurl="http://www.china-designer.com/home/".$nexturl;
			$next_news_url=get_news_next($newsurl);
			$m_newslist_url=array_merge($m_newslist_url,$next_news_url);
		}
	}
	$m_newslist_url=array_unique($m_newslist_url);
	//取得所有文章的详细页面url
	//print_r($m_newslist_url);exit;
	return $m_newslist_url;
}
/*
**功能:读取文章分页下一页的信息
**
**
**By Laurence.Chen 2014年2月8日
**检查完毕
*/
function get_news_next($url){
	$url = get_page_url($url);
	$url->noReturn();
	$url->cut('<div class="white-space maxWidth1">','</div><div class="clear"></div>');
	$content = $url->getContent();
	$content = iconv("gb2312","UTF-8",$content);
	//echo $url;
	//echo $content;exit;
	preg_match_all("/<a href=\"(.*?)\"/is",$content,$newurl);//匹配文章标题上的链接
	$newurl=array_unique($newurl[1]);
	return $newurl;
}
/*
**功能:取得文章的详细信息
**
**
**By Laurence.Chen 2014年2月8日
**检查完毕
*/
function get_news_info($url){
	$url = get_page_url($url);
	$url->noReturn();
	$url->cut('<div class="article-show">','</div><div class="clear"></div>');
	$content = $url->getContent();
	$content = iconv("gb2312","UTF-8",$content);
	//echo $url;
	//echo $content;exit;
	preg_match_all("/<h3>(.*)<\/h3>/U",$content,$newtitle);//匹配文章标题
	//print_r($newtitle[1][0]);
	showMsg("文章标题：".$newtitle[1][0]);
	preg_match_all("/<p class=\"info\"> 类别:(.*\s)/U",$content,$newtype);//匹配文章类别
	//print_r($newtype[1][0]);
	showMsg("文章类型：".$newtype[1][0]);
	preg_match_all("/<div class=\"content\">(.*?)<center>/is",$content,$newcount);//匹配文章内容
	//print_r($newcount[1]);
	$news_info[0]=$newtitle[1][0];
	$news_info[1]=$newtype[1][0];
	$news_info[2]=$newcount[1][0];
	//print_r($news_info);
	return $news_info;
}

/*************************↓↓↓↓以下是公用的方法↓↓↓↓*************************************************/
/**
***功能: 创建路径目录
***参数: 在当前项目中的路径
***
***【注意】传入的dir中的格式: 需要满足文件夹的命令规则:{大小写字母+_+数字}
**/
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

/**
***功能: 创建文件目录的方法
***参数: 在当前项目中的路径
***
***【注意】传入的dir中的格式: 需要满足文件夹的命令规则:{大小写字母+_+数字}
**/
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

/**
**功能:返回错误消息格式化后的字符串
**
**参数:
**	$masage:字符串
*/
function showErrMsg($masage){
	echo '<div style="color:#F00;font-size:12px">'.$masage.'................</div>';
}

/**
**功能:下载远程文件保存到本地
**
**参数:
**	$url:文件路径
**	$newfname:新的文件夹名称
*/
function get_file($url,$newfname){
	 set_time_limit (24 * 60 * 60);
	 $destination_folder = 'images/resources/';//文件下载保存目录
	 $newfname = $destination_folder . $newfname;
	 $file = fopen ($url, "r");
	 if ($file) {
	 $newf = fopen ($newfname, "wb");
	 if ($newf)
	 while(!feof($file)) {
		fwrite($newf, fread($file, 1024 * 8 ), 1024 * 8 );
	 }
	 }
	 if ($file) {
		fclose($file);
	 }
	 if ($newf) {
		fclose($newf);
	 }
}

 /*
 **功能:清除HTML标签
 **
 **
 **By Laurence.Chen  2014年2月8日
 **检查完毕
 */
function delhtml($str){
	$st=-1; //开始
	$et=-1; //结束
	$stmp=array();
	$stmp[]="&nbsp;";
	$len=strlen($str);
	for($i=0;$i<$len;$i++){
	 $ss=substr($str,$i,1);
	 if(ord($ss)==60){ //ord("<")==60
	  $st=$i;
	 }
	 if(ord($ss)==62){ //ord(">")==62
	  $et=$i;
	  if($st!=-1){
	  $stmp[]=substr($str,$st,$et-$st+1);
	  }
	 }
	}
	$str=str_replace($stmp,"",$str);
	$str=preg_replace("'<!--(.*?)-->'is","",$str);
	return trim($str);
}
?>