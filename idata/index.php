<?PHP
/**
* ����������ץȡ����
* 
*
* By Laurence Chen  2013��11��28��
*/
	require_once("config.php");

	require_once("includes/iUrl.class.php");
	require_once("includes/mysql.class.php");//�������ݿ���
	require_once("includes/pinyin.php");//������ת����ƴ��

	$db	= new mysql("localhost","root","","igetdata");//��ʼ�����ݿ���
	
	/* ��������
	//http://www.i3dmo.com/   
	//www.china-designer.com/         
	//www.57zfw.com/
	*/
	$host_str = $_POST["inputUrl"];
	define("HOST","www.china-designer.com/index.htm");
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
	makeDirs(LOG_PATH);//������־Ŀ¼
	makeDirs(CACHE);//��������Ŀ¼
	makeDirs(LOGO_IMG);//����ͼƬ�洢Ŀ¼
	makeDirs(PRO_IMG);//����ͼƬ�洢Ŀ¼

	//ץȡ�б���Ϣ�����س���
	function getTender(){
		//��Сҳ��
		$min=1;
		//���ҳ��10ҳ
		$max=10;
		$m_list_page_url = array();
		showMsg("��ʼ�ҵ��б���Ϣ���з�ҳ������");
		
		for($i=$max ; $i>=$min;$i--){
			$m_list_page_url[] = "http://www.57zfw.com/zbmore.aspx?page=".$i;
		}
		showMsg("��ʼ�б���Ϣ����ϸ��Ϣ������");
		foreach($m_list_page_url as $url){
			echo $url.".................................................<br/>";
			$info_url = get_info_url($url);
			foreach($info_url as $t_url){
				$tender_info = get_tender_info("http://www.57zfw.com/".$t_url);
				showMsg("��ʼ��ӵ����ݿ�");
				$id=insert_tender($tender_info);
				if($id>0){
					showMsg("�����������...................................");
				}
				else{
					showErrMsg("����ʧ�ܣ�ʧ�ܿ������Ѿ������߱������ݿ�ʧ�ܣ�");
					continue;
				}
			}
			//break;
		}
	}


	//�����б���Ϣ�����ݿ� // ������Tender
	function insert_tender($tender_info){
		global $db;
		if(($db->getOne("select Tenderid from Tender where TenderName='".trim($tender_info[0])."'"))){
			showErrMsg("���б���Ϣ&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".trim($tender_info[0])."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;�Ѿ����");
			return 0;
		}
		else{
			$data=array(
				'TenderName'=>trim($tender_info[0]),//�б�������
				'TenderType'=>trim($tender_info[9]),//����ģʽ
				'TenderType1'=>trim($tender_info[6]),//װ�޻���
				'TenderArea'=>trim($tender_info[3]),//װ�����
				'Tendermoney'=>trim($tender_info[7]),//Ԥ��۸�
				'TenderFloor'=>trim($tender_info[8]),//������;
				'starttime'=>trim($tender_info[5]),//����ʱ��
				'Tendertell'=>'13678457456',
				'Tenderqq'=>'932133186',
				'Tenderadss'=>'����',
				'Tendercount'=>'������ϸ˵��',//��ϸ˵��
				'endtime'=>trim($tender_info[11]),//װ�޷��
				'ctime'=>trim($tender_info[12])//����ʱ��
				);
				print_r($data);
			$db->Add('tender',$data);
			return $db->insert_id();
		}
			return 0;
	}
	//������ϸҳ�����Ϣ
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

	//���ҵ�ÿһҳ����ϸ��Ϣ������
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