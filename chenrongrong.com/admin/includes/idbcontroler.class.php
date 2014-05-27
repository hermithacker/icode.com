<?php
/**
����������������������������������������������������������������������������������������
���ļ�����: idbcontroler.class.php
����  �ܡ�: mysqli���ݿ������
����  �ߡ�: Laurence
����  ����: version 1.0
���޸����ڡ�: 2014/2/13
����������������������������������������������������������������������������������������
**/
//header('Content-Type:text/html; charset=utf-8');
basename($_SERVER['PHP_SELF'])=='idbcontroler.class.php' && header('Location:http://'.$_SERVER['HTTP_HOST']); //��ֱֹ�ӷ��ʱ�ҳ

class idbcontroler{
    private $host;           // ���ݿ�����
    private $user;           // ���ݿ��û���
    private $pass;           // ���ݿ�����
    private $data;           // ���ݿ���
    private $port;           // ���ݿ�˿ں�
    private $conn;           // ���ݿ�����
    private $sql;            // sql���
    private $code;           // ���ݿ���룬GBK,UTF8,GB2312
    private $result;         // ִ��query����Ľ�����ݼ�

    private $showErr = true;     // �Ƿ���������ʾ,���а�ȫ����,Ĭ�Ͽ���,������ڱ��ص���ʱʹ��
    private $errLog  = true;     // �Ƿ���������־,Ĭ�Ͽ���,�Ƽ�����
    public  $errDir  = 'error/'; // ������־����·��,�뿪��������־����Ч,��������ʵ�����Ժ��Զ���

    private $pageNo=1;       // ��ǰҳ
    private $pageAll=1;      // ��ҳ��
    private $rowsAll = 0;    // �ܼ�¼
    private $pageSize=10;    // ÿҳ��ʾ��¼����

	public $querynum = 0;    //����->ͳ��ִ��ʱ��
	public $totaltime = 0;
	public $exe_time = 0;

	
	 /******************************************************************
    -- ��������__construct($host,$user,$pass,$data,$port,$code,$conn)
    -- ��  �ã����캯��
    -- ��  ����$host ���ݿ�������ַ(����)
              $user ���ݿ��û���(����)
              $pass ���ݿ�����(����)
              $data ���ݿ���(����)
              $conn ���ݿ����ӱ�ʶ,
              $code ���ݿ����(����)
    -- ����ֵ����
    *******************************************************************/
    public function __construct($host,$user,$pass,$data,$port='3306',$code='utf8'){
		$this->host=$host;
        $this->user=$user;
        $this->pass=$pass;
        $this->data=$data;
        $this->port=$port;
        $this->code=$code;
        $this->connect();
    }

	 /******************************************************************
    -- ��������__get($name)
    -- ��  �ã���ȡ���Ե�ֵ
    -- ��  ����$name ��������(����)
    -- ����ֵ������ֵ
    -- ʵ  ����__get("host")
    *******************************************************************/
	public function __get($name){return $this->$name;}

	 /******************************************************************
    -- �������� __set($name,$value)
    -- ��  �ã��������Ե�ֵ
    -- ��  ����$name ��������(����)
			   $value ����ֵ(����)
    -- ����ֵ����
    *******************************************************************/
    public function __set($name,$value){$this->$name=$value;}
	
	 /******************************************************************
    -- ��������connect()
    -- ��  �ã����ݿ�����Ӻ���,����ʱ���ñ��뷽ʽ
    -- ��  ������
    -- ����ֵ�����ӵ�ַ
    -- ʵ  ����connect()
    *******************************************************************/
    private function connect(){
		//mysqli�ĳ־�����
		$this->conn = mysqli_connect($this->host,$this->user,$this->pass,$this->data,$this->port); 
        if (!$this->conn) 
			$this->show_error('�޷��������ݿ�');
		//ѡ�������ǰ�����ݿ�
		$this->select_db($this->data);
		//�������ݿ�ı��뷽ʽ
		$this->query('SET NAMES '.$this->code);
        $this->query("SET CHARACTER_SET_CLIENT='{$this->code}'");
        $this->query("SET CHARACTER_SET_RESULTS='{$this->code}'");
    }
	
	/******************************************************************
    -- ��������select_db($data)
    -- ��  �ã����ݿ�ѡ��
    -- ��  ����$num ��Ϣֵ(ѡ��)
    -- ����ֵ���ַ���
    -- ʵ  ����select_db("test")
    *******************************************************************/
    public function select_db($data){
        $result= mysqli_select_db($this->conn,$data);
        if (!$result) 
			$this->show_error('����������Ϊ'.$data.'���ݿ�');
        return $result;
    }


	/******************************************************************
    -- ��������get_info($num)
    -- ��  �ã�ȡ�� MySQL ��������Ϣ
    -- ��  ����$num ��Ϣֵ(ѡ��)
    -- ����ֵ���ַ���
    -- ʵ  ����get_info(1)
    *******************************************************************/
    public function get_info($num){
        switch ($num){
            case 1:
                return mysqli_get_server_info($this->conn); // ȡ�� MySQL ��������Ϣ
                break;
			case 2:
                return mysqli_get_server_version($this->conn); // ȡ�� MySQL �������汾��Ϣ
                break;
            case 3:
                return mysqli_get_host_info($this->conn);   // ȡ�� MySQL ������Ϣ
                break;
            case 4:
                return mysqli_get_proto_info($this->conn);  // ȡ�� MySQL Э����Ϣ
                break;
			case 5:
                return mysqli_get_client_info($this->conn);  // ȡ�ÿͻ�����Ϣ
                break;
            default:
                return mysqli_get_client_version($this->conn); // ȡ�� MySql �ͻ��˰汾��Ϣ
        }
    }

	/******************************************************************
    -- ��������query($sql)
    -- ��  �ã����ݿ�ִ����䣬��ִ�в�ѯ����޸�ɾ�����κ�sql���
    -- ��  ����$sql sql���(����)
    -- ����ֵ������ֵ
    -- ʵ  ����query("SET NAMES utf8")
    *******************************************************************/
    public function query($sql){
        $sql=trim($sql);
        if (empty($sql)) 
			$this->show_error('SQL���Ϊ��');
        $this->sql=$sql;
        $this->result= mysqli_query($this->conn,$this->sql);
        if (!$this->result){
            $this->show_error('SQL�������,ִ��ʧ��',true);
        }
        return $this->result;
    }
	
	/******************************************************************
    -- ��������querytime($sql)
    -- ��  �ã����ݿ�ִ����䣬��ִ�в�ѯ����޸�ɾ�����κ�sql���
    -- ��  ����$sql sql���(����)
    -- ����ֵ������ֵ
    -- ʵ  ����query("SET NAMES utf8")
    *******************************************************************/
	public function querytime($sql,$iscache=false,$cachetime=60){
		$_s = microtime(true);
		$result = $this->mysqli_query($sql);
		if(mysqli_connect_errno()){
			print(mysqli_connect_error());
			return false;
		}
		$this->querynum++;
		$this->totaltime += $this->exe_time = microtime(true)-$_s;
		return $result;
	}

	/******************************************************************
    -- ��������create_database($data)
    -- ��  �ã���������µ����ݿ�
    -- ��  ����$data ���ݿ�����(����)
    -- ����ֵ���ַ���
    -- ʵ  ����create_database("test")
    *******************************************************************/
    public function create_database($data=''){
        $this->query("CREATE DATABASE {$data}");
    }
	
	/******************************************************************
    -- ��������list_databases()
    -- ��  �ã��г���ǰ�������е��������ݿ�
    -- ��  ������
    -- ����ֵ������
    -- ʵ  ����list_databases()
    *******************************************************************/
    public function list_databases(){
        $this->query('SHOW DATABASES');
        $dbs=array();
        while ($row=$this->fetch_array()) 
			$dbs[]=$row['Database'];
        return $dbs;
    }

    /******************************************************************
    -- ��������list_tables($data)
    -- ��  �ã��г����ݿ��е����б�
    -- ��  ����$data ���ݿ�����(����)
    -- ����ֵ������
    -- ʵ  ����list_tables($dbarray["DATABASE"])
    *******************************************************************/
    public function list_tables($data=''){
        if (!empty($data)) 
			$data=' FROM '.$data;
        $this->query('SHOW TABLES '.$data);
        $tables=array();
        while ($row=$this->fetch_row()) 
			$tables[]=$row[0];
        return $tables;
    }

	 /******************************************************************
    -- ��������list_fields($table,$data)
    -- ��  �ã��г����е������ֶ���
    -- ��  ����$table ���ݱ�����(����)
			   $data  ���ݿ�����(ѡ��)
    -- ����ֵ������
    -- ʵ  ����list_fields("news")
    *******************************************************************/
    public function list_fields($table='',$data=''){
        if (empty($table)) 
			return false;
		else
			$table = " FROM ".$table ;
		 if (!empty($data)) 
			$table = " FROM ".$table. " FROM " . $data;
        $result = $this->query('SHOW COLUMNS '.$table);
		$fieldnames=array(); 
		if (mysqli_num_rows($result) > 0) { 
         while ($row = $this->fetch_assoc()) { 
           $fieldnames[] = $row['Field']; 
         } 
       } 
       return $fieldnames; 
    }

	/******************************************************************
    -- ��������copy_tables($tb1,$tb2,$where)
    -- ��  �ã����Ʊ�Ľṹ������
    -- ��  ����$tb1 �±���(����)
               $tb2 �����Ʊ�ı���(����)
               $Condition ��������(ѡ��)
    -- ����ֵ������
    -- ʵ  ����copy_tables("iinews","news")
    *******************************************************************/
    public function copy_tables($tb1,$tb2,$Condition=''){
		$this->query("CREATE TABLE {$tb1} SELECT * FROM {$tb2} {$Condition}");
	}
	
	/******************************************************************
    -- ��������Get($Table,$Fileds,$Condition,$Rows)
    -- ��  �ã���ѯ����
    -- ��  ����$Table ����(����)
               $Fileds �ֶ�����Ĭ��Ϊ����(ѡ��)
               $Condition ��ѯ����(ѡ��)
               $Rows ����ѯ��¼������Ϊ0��ʾ������(ѡ��)
    -- ����ֵ������,�����Ƿ�ִ�гɹ�.����ʾ�����
    -- ʵ  ����Get("news")
    *******************************************************************/
    public function Get($Table,$Fileds='*',$Condition='',$Rows=10){
        if (!$Fileds) 
			$Fileds='*';
        if ($Rows>0) 
			$Condition.=" LIMIT 0,{$Rows}";
        $sql="SELECT {$Fileds} FROM {$Table} {$Condition}";
       
		return  $this->query($sql);
    }

	/******************************************************************
    -- ��������GetResault($Table,$Fileds,$Condition,$Rows)
    -- ��  �ã���ѯ����
    -- ��  ����$Table ����(����)
               $Fileds �ֶ�����Ĭ��Ϊ����(ѡ��)
               $Condition ��ѯ����(ѡ��)
               $Rows ����ѯ��¼������Ϊ0��ʾ������(ѡ��)
    -- ����ֵ�����飬��ʾ�����
    -- ʵ  ����GetResault("news")
    *******************************************************************/
    public function GetResault($Table,$Fileds='*',$Condition='',$Rows=10){
        if (!$Fileds) 
			$Fileds='*';
        if ($Rows>0) 
			$Condition.=" LIMIT 0,{$Rows}";
        $sql="SELECT {$Fileds} FROM {$Table} {$Condition}";
        $this->query($sql);
		return $this->fetch_array();
    }

	/******************************************************************
    -- ��������Add($Table,$Data,$Values)
    -- ��  �ã��������
    -- ��  ����$Table ����(����)
              $Data ���������, �Ƽ�ʹ����������(����)
              $Values ���������ֵ, ���Ϊ�ַ���ʱʹ��(ѡ��)
    -- ����ֵ������ 
    -- ʵ  ����Add("news",array(
				"memberid"=>"2",
				"newstitle"=>"test",
				"newsAuthor"=>"wang",
				"newsSummary"=>"www.baidu.com",
				"newstype"=>"title",
				"newscount"=>"1",
				"ctime"=>"2014-02-14"))			���鷽ʽ ��ע�⡿���������ַ��Լ�ʹ�÷��ŵ�����
             
			 $DB->Add('mydb','`user`,`password`,`age`',"'admin','123456','18'" �ַ�������
    *******************************************************************/
    public function Add($Table,$Data=array(),$Values=''){
		$Fileds = "";
		$Values = "";
        if (!isset($Data)||empty($Data)){
            $this->show_error('���������Ϊ��',false);
            return false;
        }
        if (is_array($Data)){
            foreach ($Data as $key=>$val){
                $key=trim($key);
                if (!empty($key)){
                    $val=$val?mysqli_real_escape_string($this->conn,$val):'';
                    $Fileds.="`{$key}`,"; 
                    $Values.="'{$val}',";
                }
            }
            $Fileds=rtrim($Fileds,',');
            $Values=rtrim($Values,',');
        }
		else 
			$Fileds=$Data;
        return $this->query("INSERT INTO {$Table} ({$Fileds}) VALUES ({$Values})");
    }

	/******************************************************************
    -- ��������Set($Table,$Data,$Condition,$unQuot)
    -- ��  �ã���������
    -- ��  ����$Table ����(����)
               $Data ����������,����Ϊ����(����)
               $Condition ��������(����)  �������ӽ���������������ݣ���������������
               $unQuot ����Ҫ�����ŵ��ֶΣ������ֶεļӼ���������������ֶ���,�ָ�����д��һ������(ѡ��)
    -- ����ֵ������
    -- ʵ  ����Add("news",array(
				"memberid"=>"2",
				"newstitle"=>"test",
				"newsAuthor"=>"wang",
				"newsSummary"=>"www.baidu.com",
				"newstype"=>"title",
				"newscount"=>"1",
				"ctime"=>"2014-02-14") ��������
              $DB->Set('mydb',"user='admin',password='123456'",'WHERE id=1') �ַ�������
    *******************************************************************/
    public function Set($Table,$Data,$Condition,$unQuot=array()){
        if (is_array($Data)){
            $unQuot=is_array($unQuot)?$unQuot:explode(',',$unQuot);
            foreach ($Data as $key=>$val){
                $val=$val?mysqli_real_escape_string($this->conn,$val):'';
                $Values.=$key.'='.(in_array($key,$unQuot)?"{$val},":"'{$val}',");
            }
            $Values=rtrim($Values,',');
        }else $Values=$Data;
        return $this->query("UPDATE {$Table} SET {$Values} {$Condition}");
    }

    /******************************************************************
    -- ��������Del($Table,$Condition)
    -- ��  �ã�ɾ������
    -- ��  ����$Table ����(����)
              $Condition ɾ������(����) 
    -- ����ֵ������
    -- ʵ  ����Del("news","newsid=1") ��ע�⡿��Ҫ��д����
    *******************************************************************/
    public function Del($Table,$Condition=''){
		return $this->query("DELETE FROM {$Table} ".($Condition? " WHERE {$Condition}":''));
	}
	
	/******************************************************************
    -- ��������fetch_array($Table,$Condition)
    -- ��  �ã����ݴӽ����ȡ�õ������ɹ�������
    -- ��  ����$result �����(ѡ��)
               $type �������ͣ����Խ�������ֵ��MYSQL_ASSOC��MYSQL_NUM �� MYSQL_BOTH(ѡ��)
    -- ����ֵ������
    -- ʵ  ����fetch_array();
    *******************************************************************/
    public function fetch_array($result='',$type=MYSQL_BOTH){
        if (empty($result)) 
			$result=$this->result;
        if (!$result) 
			$this->show_error('δ��ȡ����ѯ���',true);
        return mysqli_fetch_array($result,$type);
    }
	/******************************************************************
    -- ��������fetch_assoc($result)
    -- ��  �ã���ȡ��������,ʹ��$row['�ֶ���']
    -- ��  ����$result �����(ѡ��)
    -- ����ֵ������
    -- ʵ  ����fetch_assoc();
    *******************************************************************/
    public function fetch_assoc($result=''){
        if (empty($result)) 
			$result=$this->result;
        if (!$result) 
			$this->show_error('δ��ȡ����ѯ���',true);
        return  mysqli_fetch_assoc($result);
    }
	/******************************************************************
    -- ��������fetch_row($result)
    -- ��  �ã���ȡ������������,ʹ��$row[0],$row[1],$row[2]
    -- ��  ����$result �����(ѡ��)
    -- ����ֵ������
    -- ʵ  ����fetch_row();
    *******************************************************************/
    public function fetch_row($result=''){
        if (empty($result)) 
			$result=$this->result;
        if (!$result) 
			$this->show_error('δ��ȡ����ѯ���',true);
        return mysqli_fetch_row($result);
    }
	/******************************************************************
    -- ��������fetch_obj($result)
    -- ��  �ã�����ǰ�е����������鷵��,ʹ��$row->content
    -- ��  ����$result �����(ѡ��)
    -- ����ֵ������
    -- ʵ  ����fetch_obj();
    *******************************************************************/
	public function fetch_obj($result=''){
        if (empty($result)) $result=$this->result;
        if (!$result) $this->show_error('δ��ȡ����ѯ���',true);
        return mysqli_fetch_object($result);
    }
	
	/******************************************************************
    -- ��������insert_id()
    -- ��  �ã�ȡ����һ�� INSERT ���������� ID
    -- ��  ����$result �����(ѡ��)
    -- ����ֵ��int
    -- ʵ  ����insert_id()
    *******************************************************************/
    public function insert_id(){
		return mysqli_insert_id($this->conn);
	}
	
	/******************************************************************
    -- ��������data_seek($id)
    -- ��  �ã�ָ��ȷ����һ�����ݼ�¼
    -- ��  ����$id(0...rows-1)(����)
    -- ����ֵ������
    -- ʵ  ����data_seek(144);
    *******************************************************************/
	public function data_seek($id){
        if ($id>0) $id=$id-1;
        if (!mysqli_data_seek($this->result,$id)) 
			$this->show_error('ָ��������Ϊ��');
        return $this->result;
    }

	/******************************************************************
    ��������num_fields($result)
    ��  �ã����ؽ�������ֶε���Ŀ
    ��  ����$result(����)
    ����ֵ���ַ���
    ʵ  ����num_fields();
    *******************************************************************/
    public function num_fields($result=''){
        if (empty($result)) 
			$result=$this->result;
        if (!$result) $this->show_error('δ��ȡ����ѯ���',true);
        return mysqli_num_fields($result);
    }

	/******************************************************************
    ��������num_rows($result)
    ��  �ã�����select��ѯ���������������
    ��  ����$result(����)
    ����ֵ���ַ���
    ʵ  ����num_rows()
    *******************************************************************/
    public function num_rows($result=''){
        if (empty($result)) 
			$result=$this->result;
        $rows=mysqli_num_rows($result);
        if ($result==null){
            $rows=0;
            $this->show_error(' δ��ȡ����ѯ���',true);
        }
        return $rows>0?$rows:0;
    }
	
	/******************************************************************
    ��������affected_rows()
    ��  �ã�����insert,update,deleteִ�н��ȡ��Ӱ������
    ��  ����$result(����)
    ����ֵ���ַ���
    ʵ  ����mysqli_query($link, "CREATE TABLE Language SELECT * from CountryLanguage");
			printf("Affected rows (INSERT): %d\n", affected_rows($link));
    *******************************************************************/
    public function affected_rows(){
		return mysqli_affected_rows($this->conn);
	}

	/******************************************************************
    ��������getQuery($unset)
    ��  �ã���ȡ��ַ������
    ��  ����$unset��ʾ����Ҫ��ȡ�Ĳ����������������,�ָ�(ѡ��)
    ����ֵ���ַ���
    ʵ  ����getQuery('page,sort')
    *******************************************************************/
    public function getQuery($unset=''){ 
        if (!empty($unset)){
            $arr=explode(',',$unset);
            foreach ($arr as $val) 
				unset($_GET[$val]);
        }
        foreach ($_GET as $key=>$val) 
			$list[]=$key.'='.urlencode($val);
        return is_array($list)? implode('&',$list):'';
    }

	/******************************************************************
    ��������getPage($Table,$Fileds,$Condition,$pageSize)
    ��  �ã���ȡ��ҳ��Ϣ
    ��  ����$Table ����(����)
            $Fileds �ֶ�����Ĭ�������ֶ�(ѡ��)
            $Condition ��ѯ����(ѡ��)
            $pageSize ÿҳ��ʾ��¼������Ĭ��10��(ѡ��)
    ����ֵ���ַ���
    ʵ  ����getPage("member")
    *******************************************************************/
    public function getPage($Table,$Fileds='*',$Condition='',$pageSize=10){
        if (intval($pageSize)>0){
			$this->pageSize=intval($pageSize);
		}
        $this->pageNo=isset($_GET['page']) && intval($_GET['page'])>0?intval($_GET['page']):1;
        if (empty($Fileds)){$Fileds='*';}
        $sql="SELECT * FROM {$Table} {$Condition}";
        $this->query($sql);
        $this->rowsAll=$this->num_rows();
        if ($this->rowsAll>0){
            $this->pageAll=ceil($this->rowsAll/$this->pageSize);
            if ($this->pageNo>$this->pageAll){$this->pageNo=$this->pageAll;}
            $sql="SELECT {$Fileds} FROM {$Table} {$Condition}".$this->limit(true);
            $this->query($sql);
        }
        return $this->rowsAll;
    }

    // �����ҳlimit��䣬��getPage()��������ʹ��
    public function limit($str=false){
        $offset=($this->pageNo-1)*$this->pageSize;
        return $str?' LIMIT '.$offset.','.$this->pageSize:$offset;
    }

	/******************************************************************
    ��������showPage($number)
    ��  �ã� ��ʾ��ҳ�������getPage()��������ʹ��
    ��  ����$number Bool(ѡ��)
    ����ֵ���ַ���
    ʵ  ����getPage("member");showPage();
    *******************************************************************/
    public function showPage($number=true){
        $pageBar='';
        if ($this->pageAll>1){
            $pageBar.='<ul class="page">'.chr(10);
            $url=$this->getQuery('page');
            $url=empty($url)?'?page=':'?'.$url.'&page=';
            if ($this->pageNo>1){
                $pageBar.='<li><a href="'.$url.'1">��ҳ</a></li>'.chr(10);
                $pageBar.='<li><a href="'.$url.($this->pageNo-1).'">��ҳ</a></li> '.chr(10);
            }else{
                $pageBar.='<li class="stop"><span>��ҳ</span></li>'.chr(10);
                $pageBar.='<li class="stop"><span>��ҳ</span></li>'.chr(10);
            }
            if ($number){
                $arr=array();
                if ($this->pageAll<6){
                    for ($i=0;$i<$this->pageAll;$i++) $arr[]=$i+1;
                }else{
                    if ($this->pageNo<3)
                        $arr=array(1,2,3,4,5);
                    elseif ($this->pageNo<=$this->pageAll && $this->pageNo>($this->pageAll-3))
                        for ($i=1;$i<6;$i++) $arr[]=$this->pageAll-5+$i;
                    else
                        for ($i=1;$i<6;$i++) $arr[]=$this->pageNo-3+$i;
                }
                foreach ($arr as $val){
                    if ($val==$this->pageNo) $pageBar.='<li class="curr"><span>'.$val.'</span></li>'.chr(10);
                    else $pageBar.='<li><a href="'.$url.$val.'">'.$val.'</a></li>'.chr(10);
                }
            }
            if ($this->pageNo<$this->pageAll){
                $pageBar.='<li><a href="'.$url.($this->pageNo+1).'">��ҳ</a>'.chr(10);
                $pageBar.='<li><a href="'.$url.$this->pageAll.'">βҳ</a></li>'.chr(10);
            }else{
                $pageBar.='<li class="stop"><span>��ҳ</span></li>'.chr(10);
                $pageBar.='<li class="stop"><span>βҳ</span></li>'.chr(10);
            }
            $pageBar.='<li class="stop"><span>';
            $pageBar.="ҳ��:{$this-> pageNo}/{$this->pageAll} {$this->pageSize}��/ҳ �ܼ�¼:{$this->rowsAll} ת��:";
            $pageBar.="<input id=\"page\" value=\"{$this->pageNo}\" type=\"text\" onblur=\"goPage('{$url}',{$this->pageAll});\" />";
            $pageBar.='</span></li></ul>'.chr(10);
        }
        echo $pageBar;
    }
	
	/******************************************************************
    -- ��������drop($table)
    -- ��  �ã�ɾ����(������,�޷��ָ�)
    -- ��  ����$table Ҫɾ���ı�����Ĭ��Ϊ����(ѡ��)
    -- ����ֵ����
    -- ʵ  ����$DB->drop('mydb')
    *******************************************************************/
    public function drop($table){
        if ($table){
            $this->query("DROP TABLE IF EXISTS {$table}");
        }else{
            $rst=$this->query('SHOW TABLES');
            while ($row=$this->fetch_array()){
                $this->query("DROP TABLE IF EXISTS {$row[0]}");
            }
        }
    }

	/******************************************************************
    -- ��������makeSql($table)
    -- ��  �ã������ݱ��ȡ��Ϣ������SQL���,���������ݿ�
    -- ��  ����$table ����ȡ�ı���(����)
    -- ����ֵ���ַ���
    -- ʵ  ����makeSql("news");
    *******************************************************************/
    public function makeSql($table){
        $result=$this->query("SHOW CREATE TABLE {$table}");
        $row=$this->fetch_row($result);
        $sqlStr='';
        if ($row){
            $sqlStr.="-- ---------------------------------------------------------------\r\n";
            $sqlStr.="-- Table structure for {$table} \r\n";
            $sqlStr.="-- ---------------------------------------------------------------\r\n";
            $sqlStr.="DROP TABLE IF EXISTS {$table} ;\r\n {$row[1]}; \r\n";
            $this->Get($table);
            $fields=$this->num_fields();
            if ($this->num_rows()>0){
                $sqlStr.="\r\n";
                $sqlStr.="-- ---------------------------------------------------------------\r\n";
                $sqlStr.="-- Records of {$table} \r\n";
                $sqlStr.="-- ---------------------------------------------------------------\r\n";
                while ($row=$this->fetch_row()){
                    $comma='';
                    $sqlStr.="INSERT INTO {$table} VALUES (";
                    for ($i=0;$i<$fields;$i++){
                        $sqlStr.=$comma."'".mysqli_real_escape_string($this->conn,$row[$i])."'";
                        $comma=',';
                    }
                    $sqlStr.=");\r\n";
                }
            }
            $sqlStr.="\r\n";
        }
        return $sqlStr;
    }
	
	 /******************************************************************
    -- ��������readSql($filePath)
    -- ��  �ã���ȡSQL�ļ�������ע��
    -- ��  ����$filePath SQL�ļ�·��(����)
    -- ����ֵ���ַ���/����/����
    -- ʵ  ������
    *******************************************************************/
    public function readSql($filePath){
        if (!file_exists($filePath)) return false;
        $sql=file_get_contents($filePath);
        if (empty($sql)) return '';
		$sql=preg_replace('/(?<!\\/)\\/\\*([^*\\/]|\\*(?!\\/)|\\/(?<!\\*))*((?=\\*\\/))(\\*\\/)/','',$sql); //��������ע��
        $sql=preg_replace('/(--.*)|[\f\n\r\t\v]* /',' ',$sql); //���˵���ע����س����з�
        $sql=preg_replace('/ {2,}/',' ',$sql); //���������ϵ������ո��滻Ϊһ��������ʡ����һ��
        $arr=explode(';',$sql);
        $sql=array();
        foreach ($arr as $str){
            $str=trim($str);
            if (!empty($str)) $sql[]=$str;
        }
        return $sql;
    }
	
	 /******************************************************************
    -- ��������saveSql($sqlPath,$table)
    -- ��  �ã�����ǰ���ݿ���Ϣ����ΪSQL�ļ�
    -- ��  ����$sqlPath SQL�ļ�����·�������Ϊ�����Զ��Ե�ǰ����Ϊ�ļ��������浽��ǰĿ¼(ѡ��)
              $table ������ı�����Ϊ���ű�ʾ����������Ϣ(ѡ��)
    -- ����ֵ���ַ���
    -- ʵ  ����$DB->saveSql('../mydb.sql');
    *******************************************************************/
    public function saveSql($sqlPath='',$table=''){
        if (empty($table)){
            $result=$this->query('SHOW TABLES');
            while ($arr=$this->fetch_row($result)){
                $str=$this->makeSql($arr[0]);
                if (!empty($str)) $sql.=$str;
            }
            $text="/***************************************************************\r\n";
            $text.="-- Database: $this->data\r\n";
            $text.="-- Date Created: ".date('Y-m-d H:i:s')."\r\n";
            $text.="***************************************************************/\r\n\r\n";
        }else{
            $text='';
            $sql=$this->makeSql($table);
        }
        if (empty($sql)) return false;
        $text.=$sql;
        $info = pathinfo($sqlPath);
        $dir = $info['dirname'];
        if (empty($info['extension'])){
            $dir = rtrim($sqlPath,'/');
            $sqlPath = $dir.'/'.date('YmdHis').'.sql';
        }
        $this->makeDirs($dir);
        $link=fopen($sqlPath,'w+');
        if (!is_writable($sqlPath)) return false;
        return fwrite($link,$text);
        fclose($link);
    }

    /******************************************************************
    -- ��������loadSql($filePath)
    -- ��  �ã���SQL�ļ�������Ϣ�����ݿ�
    -- ��  ����$filePath SQL�ļ�·��(����)
    -- ����ֵ���ַ���
    -- ʵ  ����$DB->loadSql('../mydb.sql');
    *******************************************************************/
    public function loadSql($filePath){
        $val = $this->readSql($filePath);
        if ($val == false) 
			$this->show_error($filePath.'������');
        elseif (empty($val)) 
			$this->show_error($filePath.'������Ч����');
        else{
            $errList='';
            foreach ($val as $sql){
                $result=$this->query($sql);
                if (!$result) $errList.='ִ�����'.$sql.'ʧ��<br />';
            }
            return $errList;
        }
        return false;
    }

	/******************************************************************
    -- ��������free($result)
    -- ��  �ã��ͷŽ����
    -- ��  ����$result (ѡ��)
    -- ����ֵ���ַ���
    -- ʵ  ����free();
    *******************************************************************/
    public function free($result=''){
        if (empty($result)) $result=$this->result;
        if ($result){
            @mysqli_free_result($result);
        }
    }
	
	/******************************************************************
    -- ��������close()
    -- ��  ��: �ر����ݿ�
    -- ��  ����
    -- ����ֵ���ַ���
    -- ʵ  ����free();
    *******************************************************************/
    public function close(){
        mysqli_close($this->conn);
    }

	/******************************************************************
    -- ��������__destruct()
    -- ��  �ã������������Զ��ͷŽ�������ر����ݿ�,�������ջ���
    -- ��  ����
    -- ����ֵ���ַ���
    -- ʵ  ���� __destruct();
    *******************************************************************/
    public function __destruct(){
        $this->free();
        $this->close();
    }
	
	/******************************************************************
    -- ��������getAll($sql)
    -- ��  �ã�ִ��SQL����ȡ��������
    -- ��  ����$sql ��ǰִ�е�SQL��� [����]
    -- ����ֵ������
    -- ʵ  ���� getAll("SELECT * FROM news limit 0,10");
    *******************************************************************/
    function getAll($sql)
    {
        $res = $this->query($sql);
        if ($res !== false)
        {
            $arr = array();
            while ($row = $this->fetch_assoc($res))
            {
                $arr[] = $row;
            }

            return $arr;
        }
        else
        {
            return false;
        }
    }

	/******************************************************************
    -- ��������getRow($sql)
    -- ��  �ã���sql��ѯ��ȡ��һ��
    -- ��  ����$sql ��ǰִ�е�SQL��� [����]
			   $limited = false �Ƿ�ȡ1�����ݵı�� [ѡ��]
    -- ����ֵ������
    -- ʵ  ���� getRow("SELECT * FROM news limit 0,10");
    *******************************************************************/
    function getRow($sql, $limited = false)
    {
        if ($limited == true)
        {
            $sql = trim($sql . ' LIMIT 1');
        }

        $res = $this->query($sql);
        if ($res !== false)
        {
            return $this->fetch_assoc($res);
        }
        else
        {
            return false;
        }
    }
	
	/******************************************************************
    -- ��������getOne($sql)
    -- ��  �ã���ȡһ������
    -- ��  ����$sql ��ǰִ�е�SQL��� [����]
			   $limited = false �Ƿ�ȡ1�����ݵı�� [ѡ��]
    -- ����ֵ������
    -- ʵ  ���� getOne("SELECT * FROM news limit 0,10");
    *******************************************************************/
	function getOne($sql, $limited = false)
    {
        if ($limited == true)
        {
            $sql = trim($sql . ' LIMIT 1');
        }
        $res = $this->query($sql);
        if ($res !== false)
        {
            $row = $this->fetch_row($res);

            if ($row !== false)
            {
                return $row[0];
            }
            else
            {
                return '';
            }
        }
        else
        {
            return false;
        }
    }

	 /******************************************************************
     -- �������� getip()
     -- ��  �ã���ÿͻ�����ʵ��IP��ַ
     -- ��  ������
     -- ����ֵ���ַ���
    *******************************************************************/
    public function getip(){
        /*
		if (!$_SERVER['HTTP_X_FORWARDED_FOR']) return $_SERVER['HTTP_X_FORWARDED_FOR'];
        elseif (!$_SERVER['HTTP_CLIENT_IP']) return $_SERVER['HTTP_CLIENT_IP'];
        elseif ($_SERVER['REMOTE_ADDR']) return $_SERVER['REMOTE_ADDR'];
        elseif (getenv('HTTP_X_FORWARDED_FOR')) return getenv('HTTP_X_FORWARDED_FOR');
        elseif (getenv('HTTP_CLIENT_IP')) return getenv('HTTP_CLIENT_IP');
        elseif (getenv('REMOTE_ADDR')) return getenv('REMOTE_ADDR'); 
        else return '';*/
		return "127.0.0.1";
    }

	 /******************************************************************
     -- ��������show_error($message,$flag)
     -- ��  �ã������ʾ������Ϣ
     -- ��  ����$msg ������Ϣ(����)
                $flag ��ʾ�����SQL��䣬��SQL������ʱʹ��(ѡ��)
     -- ����ֵ���ļ�
    *******************************************************************/
    public function show_error($msg='',$flag=false){
        //$err='['.mysqli_errno($this->conn).']'.mysqli_error($this->conn);
		$err='['.mysqli_connect_errno().']'.mysqli_connect_error();
		$fileName ="";

        if ($flag) $sql='�����SQL��䣺'.$this->sql;
        if ($this->errLog){
            $dir = trim($this->errDir,'/');
            $this-> makeDirs($dir);
            $filePath=$dir.'/'.date('Y-m-d').'.log';
            $text=" �����¼���".$msg."\r\n����ԭ��".$err."\r\n".($sql?$sql."\r\n":'')."�ͻ��� IP��".$this->getip()."\r\n��¼ʱ�䣺".date('Y-m-d H:i:s')."\r\n\r\n";
            $log=' ������־��__'.(error_log($text,3,$filePath)?'�˴�����Ϣ�ѱ��Զ���¼����־'.$fileName:'д�������Ϣ����־ʧ��');
        }
        if ($this->showErr){
            echo '<fieldset class="errlog">
				<legend>������Ϣ��ʾ</legend>
				<label class="tip">�����¼���'.$err.'</label>
				<label class="msg">����ԭ��'.$msg.'</label>
				<label class="sql">'.$sql.'</label>
				<label class="log">'.$log.'</label>
				</fieldset>';
            exit();
        }
    }

	 /******************************************************************
     -- ��������makeDirs($dirs,$mode)
     -- ��  �ã��Զ�����Ŀ¼,��Ҫ��Ϊ�˸�������־�͵���SQL�ļ��ṩ֧��
     -- ��  ����$dirs ����Ŀ¼��·����ַ(����)
                $mode �����ܵķ���Ȩ(ѡ��)
     -- ����ֵ��Bool �Ƿ���ȷ����
    *******************************************************************/
    public function makeDirs($dirs='',$mode='0777'){
        $dirs=str_replace('\\','/',trim($dirs));
        if (!empty($dirs) && !file_exists($dirs)){
            $this->makeDirs(dirname($dirs));
            mkdir($dirs,$mode) or die ('����Ŀ¼'.$dirs.'ʧ��,�볢���ֶ�����!');
        }
    }

   // ȡ�ý������
    public function result($result=''){
        if (empty($result)) 
			$result=$this->result;
        if ($result==null) 
			$this->show_error('δ��ȡ����ѯ���',true);
        return mysql_result($result);
    }
}


/******************************************************************Mysqli�ĺ���˵��
mysqli_affected_rows -����һ��MySQL������Ӱ������� 
mysqli_character_set_name -�������ݿ����ӵ�Ĭ���ַ���
mysqli_close -�رյ�ǰ�����ݿ����� 
mysqli_connect_errno -�������һ�β����Ĵ������ 
mysqli_connect_error -�������һ�β����Ĵ����ַ������� 
mysqli_connect -��һ���µ����ӵ�MySQL������ 
mysqli_data_seek -�ڵ�ǰ��¼������������ƶ���ָ�� 
mysqli_errno -���ش����������������� 
mysqli_error -����һ���ַ���������ȥ�Ĵ��� 
mysqli_fetch_array -�ӽ������ȡ��һ����Ϊ�������������飬�����߼�ʩ 
mysqli_fetch_assoc -�ӽ������ȡ��һ����Ϊ�������� 
mysqli_fetch_field -�������������еĽ���� 
mysqli_fetch_object -���ص�ǰ�еĽ������Ϊһ������ 
mysqli_fetch_row -ȡ�ý������ȡ��һ����Ϊö������ 
mysqli_free_result -�ͷ��ڴ����� 
mysqli_get_client_info -����MySQL�ͻ��˰汾��Ϊһ���ַ��� 
mysqli_get_client_version -ȡ��MySQL�ͻ�����Ϣ 
mysqli_get_host_info -����һ���ַ����������������ʹ�� 
mysqli_get_proto_info -���ذ汾��MySQLʹ��Э�� 
mysqli_get_server_info -���ذ汾��MySQL������ 
mysqli_get_server_version -���ذ汾��MySQL��������Ϊһ������ 
mysqli_insert_id -�����Զ����ɵı��ʹ������ѯ 
mysqli_num_fields -��ȡ�����������еĽ�� 
mysqli_num_rows -��ȡ�������Ľ�� 

mysqli_autocommit -������ر� autocommit ���ݿ��޸� 
mysqli_bind_param -����mysqli_stmt_bind_param �� �� 
mysqli_bind_result -����mysqli_stmt_bind_result �� �� 
mysqli_change_user -����ָ�����ݿ����ӵ��û� 
mysqli_client_encoding -����mysqli_character_set_name �� �� 
mysqli_commit -��ǰ���� 
mysqli_debug -���� 
mysqli_disable_reads_from_master -���ö�ȡ������ 
mysqli_disable_rpl_parse -����RPL���� 
mysqli_dump_debug_info -ת��������Ϣ����־ 
mysqli_embedded_connect -��һ�����ӵ�Ƕ��ʽMySQL������ 
mysqli_enable_reads_from_master -������������ 
mysqli_enable_rpl_parse -����RPL���� 
mysqli_escape_string -����mysqli_real_escape_string �� �� 
mysqli_execute -����mysqli_stmt_execute �� �� 
mysqli_fetch_field_direct -��ȡԪ���ݵ�һ����һ������ 
mysqli_fetch_fields -����һ����������������Ľ���� 
mysqli_fetch_lengths -���س����еĵ�ǰ�еĽ���� 
mysqli_fetch -����mysqli_stmt_fetch �� �� 
mysqli_field_count -���ص����������ѯ 
mysqli_field_seek -��Ϊ���ָ�뵽ָ������ص��� 
mysqli_field_tell -��ȡ��ǰ��ص����Ľ��ָ�� 
mysqli_get_metadata -����mysqli_stmt_result_metadata �� �� 
mysqli_info -������Ϣ�����ִ�еĲ�ѯ 
mysqli_init -��ʼ��MySQLi������һ����Դʹ��mysqli_real_connect �� �� 
mysqli_kill -Ҫ�������Ҫɱ��һ��MySQL�߳� 
mysqli_master_query -ǿ��ִ�в�ѯ������/������ 
mysqli_more_results -����Ƿ����κθ���Ĳ�ѯ�������һ�����ѯ 
mysqli_multi_query -ִ�в�ѯ���ݿ� 
mysqli_next_result -׼������Ľ��multi_query 
mysqli_options -����ѡ�� 
mysqli_param_count -����mysqli_stmt_param_count �� �� 
mysqli_ping -��Pingһ�����������ӣ������������ӣ�������½� 
mysqli_prepare -׼��һ��SQL����ִ�� 
mysqli_query -ִ�в�ѯ���ݿ� 
mysqli_real_connect -��һ�����ӵ�MySQL������ 
mysqli_real_escape_string -ת�������ַ����ַ���������SQL��䣬�����ǵ���ǰ���ַ��������� 
mysqli_real_query -ִ��һ��SQL��ѯ 
mysqli_report -���û�����ڲ����湦�� 
mysqli_rollback -�ع���ǰ���� 
mysqli_rpl_parse_enabled -����Ƿ�����RPL���� 
mysqli_rpl_probe - RPL̽�� 
mysqli_rpl_query_type -����RPL��ѯ���� 
mysqli_select_db -ѡ���Ĭ�����ݿ����ݿ��ѯ 
mysqli_send_long_data -����mysqli_stmt_send_long_data �� �� 
mysqli_send_query -���Ͳ�ѯ������ 
mysqli_server_end -�ػ�Ƕ��ʽ������ 
mysqli_server_init -��ʼ��Ƕ��ʽ������ 
mysqli_set_charset -����Ĭ�Ͽͻ����ַ��� 
mysqli_set_opt -����mysqli_options �� �� 
mysqli_sqlstate -����SQLSTATE�����һ��MySQL���� 
mysqli_ssl_set -���ڽ�����ȫ����ʹ��SSL 
mysqli_stat -��ȡ��ǰ��ϵͳ״̬ 
mysqli_stmt_affected_rows -���������иı䣬ɾ�����������ִ�е����� 
mysqli_stmt_bind_param -�󶨱���һ��������Ϊ���� 
mysqli_stmt_bind_result -�󶨱�����һ�������д洢�Ľ�� 
mysqli_stmt_close -�ر�һ������ 
mysqli_stmt_data_seek -Ѱ��һ�������������Ľ���� 
mysqli_stmt_errno -���ش������������������� 
mysqli_stmt_error -����һ���ַ������������������ 
mysqli_stmt_execute -ִ��һ��׼����ѯ 
mysqli_stmt_fetch -��ȡ���һ��׼���õ�����������Լ������ 
mysqli_stmt_free_result -�ⴢ�����Ľ�����账������� 
mysqli_stmt_init -��ʼ�����ԣ�������һ����������mysqli_stmt_prepare 
mysqli_stmt_num_rows -���ص������������� 
mysqli_stmt_param_count -����һЩ�������������� 
mysqli_stmt_prepare -׼��һ��SQL����ִ�� 
mysqli_stmt_reset -����һ������ 
mysqli_stmt_result_metadata -���ؽ����Ԫ���ݵ�һ���������� 
mysqli_stmt_send_long_data -�������ݿ� 
mysqli_stmt_sqlstate -����SQLSTATE�����ж������������� 
mysqli_stmt_store_result -ת�õĽ������һ������ 
mysqli_store_result -ת�õĽ����������ѯ 
mysqli_thread_id -�����߳�IDΪ��ǰ���� 
mysqli_thread_safe -�����Ƿ��̰߳�ȫ���ǻ� 
mysqli_use_result -������������� 
mysqli_warning_count -����һЩ�����ȥ��ѯ�ṩ����
*/
?>