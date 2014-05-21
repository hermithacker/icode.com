<?php
/*
功能 www.china-designer.com/会员信息数据抓取程序
时间 ：2010-6-26 17:15:58
作者 ：李陈鑫
备注 ： 
1、所有目录后面都要加“/”；
2、变量名里面的“关键词”用“_”隔开；
3、尽量减少程序的耦合度
*/
ini_set('memory_limit', '-1');//不加限制，不过要小心使用 设置php内存使用限制 "-1" 时为不加限制
ini_set('max_execution_time',30*60*60*60);//设置程序最大运行时间、默认为60秒
include("includes/ugs.class.php");
include("includes/mysql.class.php");//引入数据库类
include("includes/pinyin.php");//将汉字转换成拼音
$db	= new mysql("localhost","root","","test");//初始化数据库类

define("HOST","www.china-designer.com/index.htm");////http://www.i3dmo.com/   ///www.china-designer.com/         ///www.57zfw.com/
define("PATTERN",2);//抓取模式，1、为写缓存！2、为存数据库+写缓存3、3写缓存 + 读数据库抓图片
define('LOG_PATH', "error/".HOST.'/');//日志所在目录
define('TEMP','temp/');//临时存储目录
define('CACHE',TEMP."cache/".HOST);//缓存所在目录
define('LOGO_IMG','images/logo_img/');//会员头像保存文件夹
define('PRO_IMG','images/pro_img/');//会员头像保存文件夹
makeDirs(LOG_PATH);//建立日志目录
makeDirs(CACHE);//建立缓存目录
makeDirs(LOGO_IMG);//创建图片存储目录
makeDirs(PRO_IMG);//创建图片存储目录
$is_show_msg = true;
$goods_num=0;
//抓取会员信息
//echo "关闭数据抓取";exit;
main();//抓取会员信息的主控程序


//抓取资源模块信息http://www.i3dmo.com/

//getresources_3d();
//getresources_cad();
//getresources_pic();
//*****************************************************************************//
//抓取招标信息http://www.57zfw.com/zbmore.aspx
//gettender();
//*****************************************************************************//
//抓取招标信息的主控程序
function gettender(){
	$min=1;//最小页数
	$max=1;//最大页数10页
	$m_list_page_url = array();
	showMsg("开始找到招标信息所有分页的链接");
	/*for($i=$max ; $i>=$min;$i--){
		$m_list_page_url[] = "http://www.57zfw.com/zbmore.aspx?page=".$i;
	}*/
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

//保存招标信息到数据库
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
	$content = $ugs->value_;
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

//*****************************************************************************//
//抓取3d模块文件的主控程序
function getresources_3d(){/////////http://www.i3dmo.com/cadsc.asp  cad 1页  页面   http://www.i3dmo.com/3Dtietu.asp  3d贴图页面  28页
	//http://www.i3dmo.com/3Dmoban.asp   3d模型  页面   71页
	$min=2;//最小页数
	$max=2;//最大页数1213页
	$m_list_page_url = array();
	showMsg("开始收索3d模块分页信息");
	for($i=$min ; $i<=$max;$i++ ){
		$m_list_page_url[] = "http://www.i3dmo.com/3dmoban.asp?page=".$i."&fla=&flb=&id=";
	}
	showMsg("开始抓取每页的所有模块的链接");
	foreach($m_list_page_url as $url){
		$page_info_url = getresources_infourl($url);//取得每个模块的url数组
		//print_r($page_info_url);
		foreach($page_info_url[1] as $purl){
			$ID=explode('=',$purl);
			$IdArray[]=$ID[1];//处理链接得到  ID 号
			echo $ID[1].'--';
		}
		//$IdArray;
		$PicArray=$page_info_url[2];//得到 图片地址
		$TxtArray=$page_info_url[3];//得到 名称
		//print_r($page_info_url);
		showMsg("开始抓取每个为对应的内容存入数据库");
		foreach($IdArray as $key=>$val){
			showMsg("开始下载ID为&nbsp;&nbsp;&nbsp;&nbsp;".$val."&nbsp;&nbsp;&nbsp;&nbsp;的资源模块..................");
			$adss="http://www.i3dmo.com/3d_downto.asp?id=".$val;
				get_file($adss,$val.'.rar');//下载文件
			$adss_mo='http://localhost/52zsj/getdata/images/resources/'.$val.'.rar';
			showMsg("资源文件下载成功.........");
			showMsg("开始下载图片.........");
				$ext=strrchr($PicArray[$key],".");
				$filename=get_original_image('http://www.i3dmo.com/'.$PicArray[$key],'images/resources/'.$val.'_pic'.$ext);//下载网上图片保存到本地
			showMsg("图片下载成功.........开始保存数据库");
			global $db;
			if(($db->getOne("select resourcesid from resources where resourcesid='".$val."'"))){
				showErrMsg("此资源模块已经入库");
				continue;//继续执行下一次循环
			}
			else{
				$db->Add('resources',array('resourcesid'=>$val,'memberid'=>'1','resourcesname'=>$TxtArray[$key],'resourcestype'=>'3DMAX','resourcespic'=>'http://localhost/52zsj/getdata/'.$filename,'resourcesadss'=>$adss_mo,'ctime'=>date('Y-m-d')));

			}
			showMsg("保存数据库成功");
			//break;
		}

		//print_r($TxtArray);
		//get_file($adss_file,'images/resources',date('dMYHis'));
	}

}

//抓取cad模块文件的主控程序
function getresources_cad(){/////////http://www.i3dmo.com/cadsc.asp  cad 1页  页面   http://www.i3dmo.com/3Dtietu.asp  3d贴图页面  28页
	//http://www.i3dmo.com/3Dmoban.asp   3d模型  页面   71页
	$min=1;//最小页数
	$max=1;//最大页数1213页
	$m_list_page_url = array();
	showMsg("开始收索3d模块分页信息");
	for($i=$min ; $i<=$max;$i++ ){
		$m_list_page_url[] = "http://www.i3dmo.com/cadsc.asp";
	}
	showMsg("开始抓取每页的所有模块的链接");
	foreach($m_list_page_url as $url){
		$page_info_url = getresources_infourl($url);//取得每个模块的url数组
		//print_r($page_info_url);
		foreach($page_info_url[1] as $purl){
			$ID=explode('=',$purl);
			$IdArray[]=$ID[1];//处理链接得到  ID 号
			echo $ID[1].'--';
		}
		//$IdArray;
		$PicArray=$page_info_url[2];//得到 图片地址
		$TxtArray=$page_info_url[3];//得到 名称
		//print_r($page_info_url);
		showMsg("开始抓取每个为对应的内容存入数据库");
		foreach($IdArray as $key=>$val){
			showMsg("开始下载ID为&nbsp;&nbsp;&nbsp;&nbsp;".$val."&nbsp;&nbsp;&nbsp;&nbsp;的资源模块..................");
			$adss="http://www.i3dmo.com/3d_downto.asp?id=".$val;
				get_file($adss,$val.'.rar');//下载文件
			$adss_mo='http://localhost/52zsj/getdata/images/resources/'.$val.'_pic.rar';
			showMsg("资源文件下载成功.........");
			showMsg("开始下载图片.........");
				$ext=strrchr($PicArray[$key],".");
				$filename=get_original_image('http://www.i3dmo.com/'.$PicArray[$key],'images/resources/'.$val.'_pic'.$ext);//下载网上图片保存到本地
			showMsg("图片下载成功.........开始保存数据库");
			global $db;
			if(($db->getOne("select resourcesid from resources where resourcesid='".$val."'"))){
				showErrMsg("此资源模块已经入库");
				continue;//继续执行下一次循环
			}
			else{
				$db->Add('resources',array('resourcesid'=>$val,'memberid'=>'1','resourcesname'=>$TxtArray[$key],'resourcestype'=>'CAD','resourcespic'=>'http://localhost/52zsj/getdata/'.$filename,'resourcesadss'=>$adss_mo,'ctime'=>date('Y-m-d')));

			}
			showMsg("保存数据库成功");
			//break;
		}

		//print_r($TxtArray);
		//get_file($adss_file,'images/resources',date('dMYHis'));
	}

}


//抓取材质图片模块文件的主控程序
function getresources_pic(){/////////http://www.i3dmo.com/cadsc.asp  cad 1页  页面   http://www.i3dmo.com/3Dtietu.asp  3d贴图页面  28页
	//http://www.i3dmo.com/3Dmoban.asp   3d模型  页面   71页
	$min=20;//最小页数
	$max=20;//最大页数28页
	$m_list_page_url = array();
	showMsg("开始收索3d模块分页信息");
	for($i=$min ; $i<=$max;$i++ ){
		$m_list_page_url[] = "http://www.i3dmo.com/3Dtietu.asp?page=".$i."&fla=&flb=&id=";
	}
	showMsg("开始抓取每页的所有模块的链接");
	foreach($m_list_page_url as $url){
		$page_info_url = getresources_infourl($url);//取得每个模块的url数组
		//print_r($page_info_url);
		foreach($page_info_url[1] as $purl){
			$ID=explode('=',$purl);
			$IdArray[]=$ID[1];//处理链接得到  ID 号
			echo $ID[1].'--';
		}
		//$IdArray;
		$PicArray=$page_info_url[2];//得到 图片地址
		$TxtArray=$page_info_url[3];//得到 名称
		//print_r($page_info_url);
		showMsg("开始抓取每个为对应的内容存入数据库");
		foreach($IdArray as $key=>$val){
			showMsg("开始下载ID为&nbsp;&nbsp;&nbsp;&nbsp;".$val."&nbsp;&nbsp;&nbsp;&nbsp;的资源模块..................");
			$adss="http://www.i3dmo.com/3d_downto.asp?id=".$val;
				//get_file($adss,$val.'.rar');//下载文件
			$adss_mo='http://localhost/52zsj/getdata/images/resources/'.$val.'.rar';
			showMsg("资源文件下载成功.........");
			showMsg("开始下载图片.........");
				$ext=strrchr($PicArray[$key],".");
				$filename=get_original_image('http://www.i3dmo.com/'.$PicArray[$key],'images/resources/'.$val.'_pic'.$ext);//下载网上图片保存到本地
			showMsg("图片下载成功.........开始保存数据库");
			global $db;
			if(($db->getOne("select resourcesid from resources where resourcesid='".$val."'"))){
				showErrMsg("此资源模块已经入库");
				continue;//继续执行下一次循环
			}
			else{
				$db->Add('resources',array('resourcesid'=>$val,'memberid'=>'1','resourcesname'=>$TxtArray[$key],'resourcestype'=>'PIC','resourcespic'=>'http://localhost/52zsj/getdata/'.$filename,'resourcesadss'=>'http://localhost/52zsj/getdata/'.$filename,'ctime'=>date('Y-m-d')));

			}
			showMsg("保存数据库成功");
			//break;
		}

		//print_r($TxtArray);
		//get_file($adss_file,'images/resources',date('dMYHis'));
	}

}



//取得每个页面获取的信息
function get_resources_info($url){
	//$content=file_get_contents($url);
	$ugs = get_page_ugs($url);
	$ugs->noReturn();
	$ugs->cut('<td height="200" align="center" bgcolor="#1E1E1E">','</td>');
	$content = $ugs->value_;
	$content = iconv("GB2312","UTF-8",$content);
	//echo $content;
	preg_match_all("/<a href=\"(.*?)\"/i",$content,$reg);//获取下载地址
	//print_r($reg[1][0]);
	$com=file_get_contents("http://www.3dxia.com".$reg[1][0]);
	//echo $com;
	return $reg[1][0];
	//exit;
}

//取得每页的每个模块的链接
function getresources_infourl($url){
	$ugs = get_page_ugs($url);
	$ugs->noReturn();
	$ugs->cut('<table width="700" border="0" cellpadding="0" cellspacing="0">','<br/>');
	$content = $ugs->value_;
	$content = iconv("gb2312","UTF-8",$content);
	//echo $content;exit;
	preg_match_all('/<a href=\"(.*?)\"><img src=\"(.*?)\" alt=\"(.*?)\" width=\"176\" height=\"113\" border=\"0\" \/><\/a>/is',$content,$reg);
	unset($reg[0]);
	//print_r($reg);exit;
	return $reg;
}


//*****************************************************************************//
function main(){
	$min=17;//最小页数
	$max=17;//最大页数
	$m_list_page_url = array();
	for($i=$min ; $i<=$max;$i++ ){
		$m_list_page_url[] = "http://www.china-designer.com/ezx/new_designer_s/?keywords=&mainbest=0&designerclassid=-1&location=4&sel_city=0&job=2&charges=0&PageSize=12&vType=2&birthday1=0&birthday2=0&AccountID=&PageNO=".$i;
	}

	//得到重庆会员的家园编号
	$home_id_arr = array();
	showMsg("开始抓取&nbsp;&nbsp;$min&nbsp;&nbsp;页到&nbsp;&nbsp;$max&nbsp;&nbsp;页的所有会员编号",true);
	foreach($m_list_page_url as $url){
		$home_id_arr = array_merge($home_id_arr,get_home_id($url));
	}
	showMsg('会员编号抓取完毕.............................');

	//获取单个设计师
	//$home_id_arr=array(203145);
	//进入读取会员编号的会员信息页面
	$m_info_list = array();
	foreach($home_id_arr as $id){
		$url_member = "http://www.china-designer.com/home/".$id."_1_1.htm";
		$url_proarea = "http://www.china-designer.com/home/".$id."_2.htm";//设计师作品
		$url_news = "http://www.china-designer.com/home/".$id."_4.htm";//设计师文章

		showMsg('<font style="font-size:20px;color:blue">开始抓取会员编号为'.$id.'的会员资料信息</font>');
		$m_info_list = get_m_info($url_member);//获取会员信息编号
		//print_r($m_info_list);exit;
		$memberid=insert_member($m_info_list);//添加到数据库去或者是已经插入啦的取得会员ID号
		showMsg("<font style='font-size:20px;color:red'>插入数据库的会员ID为：".$memberid."</font>");
		//die($memberid);

		showMsg('<font style="font-size:20px;color:blue">开始抓取会员文章信息</font>');
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
		//$m_proarea_list=array();
		//print_r($m_proarea_list);exit;
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
//保存会员作品信息到数据库
function insert_proarea($pro_info,$memberid){
	global $db;
	//print_r($pro_info);exit;
	$title=trim($pro_info[0][0]);//作品名称  可能是  默认文件夹
	$count=trim($pro_info[1][0]);//作品说明  介绍
	if($title=="默认文件夹"){
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
					'proareapicadss'=>'http://localhost/52zsj/getdata/'.$filename,//图片地址
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
//抓取每个作品下的每个做作品图片的信息
function get_pro_info_count($url){
	$ugs = get_page_ugs($url);
	$ugs->noReturn();
	$ugs->cut('<div class="works-catalogInfo-space">','</div><div class="clear"></div>');
	$content = $ugs->value_;
	$content = iconv("gb2312","UTF-8",$content);
	//echo $content;exit;
	//<img src="//"   border="0" alt="//" />
	preg_match_all("/<span  class=\"title\">(.*?)<\/span>/i",$content,$title);///作品图片标题
	//print_r($title);
	preg_match_all("/<span class=\"ftColor-hui\"><a\s.*?<img src=\"(.*?)\"   border=\"0\" alt=\"(.*?)\" \/>/i",$content,$val);//作品标签属性
	//print_r($val);
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


//保存会员文章到数据库
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
//保存会员信息到数据库
function insert_member($m_info_list){
	global $db;
	//print_r($m_info_list);exit;
	//去除js代码和空格代码
	$memberadss=$m_info_list[26];
	$adss = preg_replace("'<script(.*?)<\/script>'is","",$memberadss);
	$adss = str_replace("&nbsp;","",$adss);
	$adss = str_replace("  ","",$adss);
	///////////
	$loginname=pinyin(iconv("UTF-8","gb2312",$m_info_list[4]));
	if(strlen($loginname)>15){
		$loginname=substr($loginname,0,15);
	}
	if(strlen($loginname)<6){
		$loginname=$loginname.'sjs';
	}
	showMsg("会员ID：$m_info_list[2]会员名称：$loginname &nbsp;&nbsp;会员信息到数据库");
	if(($db->getOne("select memberid from member where loginname='".$loginname."'"))){
		$memberquery=$db->GetRs("member","memberid","where loginname='$loginname'");
		$memberid=$memberquery['memberid'];
		showErrMsg("会员ID：$m_info_list[2]会员名称：".$member_info_m['loginname']."已存入数据库>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>");
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
			'logopwd'=>'admin888',
			'membertypeid'=>'1',
			'memberlogo'=>'http://localhost/52zsj/getdata/'.$filename,//会员头像
			'membername'=>trim($m_info_list[4]),//会员名称
			'membersex'=>trim($m_info_list[8]),//会员性别
			'memberadss'=>$adss,//所在区域
			'membertell'=>'13678457456',//会员电话
			'memberqq'=>'932133186',//会员QQ
			'memberemail'=>'932133186@qq.com',//会员邮箱
			'memberwork'=>trim($m_info_list[16]),//工作单位
			'memberpost'=>$m_info_list[30],//会员职位
			'membercount'=>delhtml(trim($m_info_list[54])),//会员介绍
			'DateBirth'=>$DateBirth,//出生年月
			'College'=>trim($m_info_list[20]),//毕业学院
			'Professional'=>trim($m_info_list[22]),//专业
			'Workdate'=>trim($m_info_list[18]),//工作年限
			'Education'=>trim($m_info_list[24]),//学历
			'sjstype'=>trim(delhtml($m_info_list[28])),//设计师类型
			'sjszc'=>trim(delhtml($m_info_list[50])),//设计师专长
			'workname'=>trim(delhtml($m_info_list[46])),//设计收费
			'ctime'=>date('Y-m-d')//注册时间
		);
		//print_r ($member_info_m);exit;
		$db->Add('member',$member_info_m);
		showMsg("-----------会员ID：$m_info_list[2]会员名称：$m_info_list[4]&nbsp;&nbsp;会员信息入库成功----------------------");
		return $db->insert_id();
	}

}

function get_m_proarea($url){
	//echo $url;exit;
	$ugs = get_page_ugs($url);
	$ugs->noReturn();
	$ugs->cut('<div class="title_box">','</div class="title_box">');
	$content = $ugs->value_;
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
//获取会员每个作品信息
function get_m_pro_info($url){
	$ugs = get_page_ugs($url);
	$ugs->noReturn();
	$ugs->cut('<div class="works-catalogInfo-space">','</div><div class="clear"></div>');
	$content = $ugs->value_;
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
	//print_r($nextpage[1]);//exit;
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
	//print_r($pro_url_info_one_url);
	$member_proarea[0]=$title[2];
	$member_proarea[1]=$conet[1];
	$member_proarea[2]=$pro_url_info_one_url;
	return $member_proarea;
}


//获取下一页的数据
function get_m_pro_urllist($url){
	$ugs = get_page_ugs($url);
	$ugs->noReturn();
	$ugs->cut('works-catalogInfo-space','</div><div class="clear"></div>');
	$content = $ugs->value_;
	$content = iconv("gb2312","UTF-8",$content);
	//echo $content;exit;
	preg_match_all("/<td align=\"left\"><a href=\"(.*?)\" target=_blank id=\"(.*?)\">(.*?)<\/a>/is",$content,$reg);
	//print_r($reg[1]);exit;
	return $reg[1];
}
//抓取文章信息
function get_news_list($url){
	$ugs = get_page_ugs($url);
	$ugs->noReturn();
	$ugs->cut('<div class="white-space maxWidth1">','</div><div class="clear"></div>');
	$content = $ugs->value_;
	$content = iconv("gb2312","UTF-8",$content);
	//echo $content;
	$m_newslist_url=array();
	preg_match_all('/<A href=(.*?)>(.*?)<\/a>/s',$content,$nextpage);//匹配是否有下一页/pageNO=(\d)>/is
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
//读取文章分页下一页的信息
function get_news_next($url){
	$ugs = get_page_ugs($url);
	$ugs->noReturn();
	$ugs->cut('<div class="white-space maxWidth1">','</div><div class="clear"></div>');
	$content = $ugs->value_;
	$content = iconv("gb2312","UTF-8",$content);
	//echo $url;
	//echo $content;exit;
	preg_match_all("/<a href=\"(.*?)\"/is",$content,$newurl);//匹配文章标题上的链接
	$newurl=array_unique($newurl[1]);
	return $newurl;
}
//取得文章的详细信息
function get_news_info($url){
	$ugs = get_page_ugs($url);
	$ugs->noReturn();
	$ugs->cut('<div class="article-show">','</div><div class="clear"></div>');
	$content = $ugs->value_;
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

//获取会员基本信息资料
function get_m_info($url){
	$ugs = get_page_ugs($url);
	$ugs->noReturn();
	$ugs->cut('<div class="modulecontent">','<center>');
	$content = $ugs->value_;
	//将字符串 content 从 gb2312 转换编码到 UTF-8
	$content = iconv("gb2312","UTF-8",$content);
	//测试: echo $content;exit;
	$member_info = array();
	preg_match_all('/<td[^>]*>(.*)<\\/td>/U',$content,$reg);//获取会员资料
	preg_match_all('/<img src=\"(.*?)\" title=\".*?\" id=\"PreviewImagesReplace\"/is',$content,$regimg);//获取图片头像
	//print_r($regimg[1][0]);exit;
	//get_original_image($regimg[1][0]);//下载网上图片保存到本地
	$member_info=array_merge($regimg[1],$reg[1]);
	//print_r($member_info);exit;
	return $member_info;
}
//会员列表页面的 会员家园ID编号
function get_home_id($url){
	$ugs = get_page_ugs($url);
	$ugs->noReturn();
	$content = $ugs->value_;
	preg_match_all('/<input name="c_accountid" type="checkbox" value="(\\d+)" \\/>/sim',$content,$reg);
	print_r($reg[1]);
	return $reg[1];
}
/*更具url获取html文件*/
function get_page_ugs($url){
	$file_name = CACHE.md5($url).".html";//获取缓存文件名（包括路径）
	$ugs = new ugs();
	$ugs->seturl($url);
	if(file_exists($file_name)){
		$ugs -> value_ = file_get_contents($file_name);
	}else{
		$ugs->gather();//抓取
		$content = $ugs->getcontent();
		file_put_contents($file_name,$content);
	}
	return $ugs;
}
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
**功能:抓取原图到本地
**
**
**格式限制:.gif/.jpg/.png/.bmp
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
/*返回普通消息格式化后的字符串*/
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
//下载远程文件保存到本地
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

 //清除HTML标签
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