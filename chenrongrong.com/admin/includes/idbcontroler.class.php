<?php
/**
※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※
【文件名】: idbcontroler.class.php
【功  能】: mysqli数据库操作类
【作  者】: Laurence
【版  本】: version 1.0
【修改日期】: 2014/2/13
※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※
**/
//header('Content-Type:text/html; charset=utf-8');
basename($_SERVER['PHP_SELF'])=='idbcontroler.class.php' && header('Location:http://'.$_SERVER['HTTP_HOST']); //禁止直接访问本页

class idbcontroler{
    private $host;           // 数据库主机
    private $user;           // 数据库用户名
    private $pass;           // 数据库密码
    private $data;           // 数据库名
    private $port;           // 数据库端口号
    private $conn;           // 数据库连接
    private $sql;            // sql语句
    private $code;           // 数据库编码，GBK,UTF8,GB2312
    private $result;         // 执行query命令的结果数据集

    private $showErr = true;     // 是否开启错误提示,具有安全隐患,默认开启,建议仅在本地调试时使用
    private $errLog  = true;     // 是否开启错误日志,默认开启,推荐开启
    public  $errDir  = 'error/'; // 错误日志保存路径,须开启错误日志才有效,可以在类实例化以后自定义

    private $pageNo=1;       // 当前页
    private $pageAll=1;      // 总页数
    private $rowsAll = 0;    // 总记录
    private $pageSize=10;    // 每页显示记录条数

	public $querynum = 0;    //计数->统计执行时间
	public $totaltime = 0;
	public $exe_time = 0;

	
	 /******************************************************************
    -- 函数名：__construct($host,$user,$pass,$data,$port,$code,$conn)
    -- 作  用：构造函数
    -- 参  数：$host 数据库主机地址(必填)
              $user 数据库用户名(必填)
              $pass 数据库密码(必填)
              $data 数据库名(必填)
              $conn 数据库连接标识,
              $code 数据库编码(必填)
    -- 返回值：无
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
    -- 函数名：__get($name)
    -- 作  用：获取属性的值
    -- 参  数：$name 属性名称(必填)
    -- 返回值：属性值
    -- 实  例：__get("host")
    *******************************************************************/
	public function __get($name){return $this->$name;}

	 /******************************************************************
    -- 函数名： __set($name,$value)
    -- 作  用：设置属性的值
    -- 参  数：$name 属性名称(必填)
			   $value 属性值(必填)
    -- 返回值：无
    *******************************************************************/
    public function __set($name,$value){$this->$name=$value;}
	
	 /******************************************************************
    -- 函数名：connect()
    -- 作  用：数据库的连接函数,连接时设置编码方式
    -- 参  数：无
    -- 返回值：连接地址
    -- 实  例：connect()
    *******************************************************************/
    private function connect(){
		//mysqli的持久连接
		$this->conn = mysqli_connect($this->host,$this->user,$this->pass,$this->data,$this->port); 
        if (!$this->conn) 
			$this->show_error('无法连接数据库');
		//选择操作当前的数据库
		$this->select_db($this->data);
		//设置数据库的编码方式
		$this->query('SET NAMES '.$this->code);
        $this->query("SET CHARACTER_SET_CLIENT='{$this->code}'");
        $this->query("SET CHARACTER_SET_RESULTS='{$this->code}'");
    }
	
	/******************************************************************
    -- 函数名：select_db($data)
    -- 作  用：数据库选择
    -- 参  数：$num 信息值(选填)
    -- 返回值：字符串
    -- 实  例：select_db("test")
    *******************************************************************/
    public function select_db($data){
        $result= mysqli_select_db($this->conn,$data);
        if (!$result) 
			$this->show_error('不存在名字为'.$data.'数据库');
        return $result;
    }


	/******************************************************************
    -- 函数名：get_info($num)
    -- 作  用：取得 MySQL 服务器信息
    -- 参  数：$num 信息值(选填)
    -- 返回值：字符串
    -- 实  例：get_info(1)
    *******************************************************************/
    public function get_info($num){
        switch ($num){
            case 1:
                return mysqli_get_server_info($this->conn); // 取得 MySQL 服务器信息
                break;
			case 2:
                return mysqli_get_server_version($this->conn); // 取得 MySQL 服务器版本信息
                break;
            case 3:
                return mysqli_get_host_info($this->conn);   // 取得 MySQL 主机信息
                break;
            case 4:
                return mysqli_get_proto_info($this->conn);  // 取得 MySQL 协议信息
                break;
			case 5:
                return mysqli_get_client_info($this->conn);  // 取得客户端信息
                break;
            default:
                return mysqli_get_client_version($this->conn); // 取得 MySql 客户端版本信息
        }
    }

	/******************************************************************
    -- 函数名：query($sql)
    -- 作  用：数据库执行语句，可执行查询添加修改删除等任何sql语句
    -- 参  数：$sql sql语句(必填)
    -- 返回值：布尔值
    -- 实  例：query("SET NAMES utf8")
    *******************************************************************/
    public function query($sql){
        $sql=trim($sql);
        if (empty($sql)) 
			$this->show_error('SQL语句为空');
        $this->sql=$sql;
        $this->result= mysqli_query($this->conn,$this->sql);
        if (!$this->result){
            $this->show_error('SQL语句有误,执行失败',true);
        }
        return $this->result;
    }
	
	/******************************************************************
    -- 函数名：querytime($sql)
    -- 作  用：数据库执行语句，可执行查询添加修改删除等任何sql语句
    -- 参  数：$sql sql语句(必填)
    -- 返回值：布尔值
    -- 实  例：query("SET NAMES utf8")
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
    -- 函数名：create_database($data)
    -- 作  用：创建添加新的数据库
    -- 参  数：$data 数据库名称(必填)
    -- 返回值：字符串
    -- 实  例：create_database("test")
    *******************************************************************/
    public function create_database($data=''){
        $this->query("CREATE DATABASE {$data}");
    }
	
	/******************************************************************
    -- 函数名：list_databases()
    -- 作  用：列出当前服务器中的所有数据库
    -- 参  数：无
    -- 返回值：数组
    -- 实  例：list_databases()
    *******************************************************************/
    public function list_databases(){
        $this->query('SHOW DATABASES');
        $dbs=array();
        while ($row=$this->fetch_array()) 
			$dbs[]=$row['Database'];
        return $dbs;
    }

    /******************************************************************
    -- 函数名：list_tables($data)
    -- 作  用：列出数据库中的所有表
    -- 参  数：$data 数据库名称(必填)
    -- 返回值：数组
    -- 实  例：list_tables($dbarray["DATABASE"])
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
    -- 函数名：list_fields($table,$data)
    -- 作  用：列出表中的所有字段名
    -- 参  数：$table 数据表名称(必填)
			   $data  数据库名称(选填)
    -- 返回值：数组
    -- 实  例：list_fields("news")
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
    -- 函数名：copy_tables($tb1,$tb2,$where)
    -- 作  用：复制表的结构和数据
    -- 参  数：$tb1 新表名(必填)
               $tb2 待复制表的表名(必填)
               $Condition 复制条件(选填)
    -- 返回值：布尔
    -- 实  例：copy_tables("iinews","news")
    *******************************************************************/
    public function copy_tables($tb1,$tb2,$Condition=''){
		$this->query("CREATE TABLE {$tb1} SELECT * FROM {$tb2} {$Condition}");
	}
	
	/******************************************************************
    -- 函数名：Get($Table,$Fileds,$Condition,$Rows)
    -- 作  用：查询数据
    -- 参  数：$Table 表名(必填)
               $Fileds 字段名，默认为所有(选填)
               $Condition 查询条件(选填)
               $Rows 待查询记录条数，为0表示不限制(选填)
    -- 返回值：布尔,返回是否执行成功.不显示结果集
    -- 实  例：Get("news")
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
    -- 函数名：GetResault($Table,$Fileds,$Condition,$Rows)
    -- 作  用：查询数据
    -- 参  数：$Table 表名(必填)
               $Fileds 字段名，默认为所有(选填)
               $Condition 查询条件(选填)
               $Rows 待查询记录条数，为0表示不限制(选填)
    -- 返回值：数组，显示结果集
    -- 实  例：GetResault("news")
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
    -- 函数名：Add($Table,$Data,$Values)
    -- 作  用：添加数据
    -- 参  数：$Table 表名(必填)
              $Data 待添加数据, 推荐使用数组类型(必填)
              $Values 待添加数据值, 如果为字符串时使用(选填)
    -- 返回值：布尔 
    -- 实  例：Add("news",array(
				"memberid"=>"2",
				"newstitle"=>"test",
				"newsAuthor"=>"wang",
				"newsSummary"=>"www.baidu.com",
				"newstype"=>"title",
				"newscount"=>"1",
				"ctime"=>"2014-02-14"))			数组方式 【注意】具有中文字符以及使用符号的问题
             
			 $DB->Add('mydb','`user`,`password`,`age`',"'admin','123456','18'" 字符串类型
    *******************************************************************/
    public function Add($Table,$Data=array(),$Values=''){
		$Fileds = "";
		$Values = "";
        if (!isset($Data)||empty($Data)){
            $this->show_error('待添加数据为空',false);
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
    -- 函数名：Set($Table,$Data,$Condition,$unQuot)
    -- 作  用：更改数据
    -- 参  数：$Table 表名(必填)
               $Data 待更改数据,可以为数组(必填)
               $Condition 更改条件(必填)  不添加添加将更改整个表的数据，不建议这样操作
               $unQuot 不需要加引号的字段，用于字段的加减运算等情况，多个字段用,分隔或者写入一个数组(选填)
    -- 返回值：布尔
    -- 实  例：Add("news",array(
				"memberid"=>"2",
				"newstitle"=>"test",
				"newsAuthor"=>"wang",
				"newsSummary"=>"www.baidu.com",
				"newstype"=>"title",
				"newscount"=>"1",
				"ctime"=>"2014-02-14") 数组类型
              $DB->Set('mydb',"user='admin',password='123456'",'WHERE id=1') 字符串类型
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
    -- 函数名：Del($Table,$Condition)
    -- 作  用：删除数据
    -- 参  数：$Table 表名(必填)
              $Condition 删除条件(必填) 
    -- 返回值：布尔
    -- 实  例：Del("news","newsid=1") 【注意】需要填写条件
    *******************************************************************/
    public function Del($Table,$Condition=''){
		return $this->query("DELETE FROM {$Table} ".($Condition? " WHERE {$Condition}":''));
	}
	
	/******************************************************************
    -- 函数名：fetch_array($Table,$Condition)
    -- 作  用：根据从结果集取得的行生成关联数组
    -- 参  数：$result 结果集(选填)
               $type 数组类型，可以接受以下值：MYSQL_ASSOC，MYSQL_NUM 和 MYSQL_BOTH(选填)
    -- 返回值：数组
    -- 实  例：fetch_array();
    *******************************************************************/
    public function fetch_array($result='',$type=MYSQL_BOTH){
        if (empty($result)) 
			$result=$this->result;
        if (!$result) 
			$this->show_error('未获取到查询结果',true);
        return mysqli_fetch_array($result,$type);
    }
	/******************************************************************
    -- 函数名：fetch_assoc($result)
    -- 作  用：获取关联数组,使用$row['字段名']
    -- 参  数：$result 结果集(选填)
    -- 返回值：数组
    -- 实  例：fetch_assoc();
    *******************************************************************/
    public function fetch_assoc($result=''){
        if (empty($result)) 
			$result=$this->result;
        if (!$result) 
			$this->show_error('未获取到查询结果',true);
        return  mysqli_fetch_assoc($result);
    }
	/******************************************************************
    -- 函数名：fetch_row($result)
    -- 作  用：获取数字索引数组,使用$row[0],$row[1],$row[2]
    -- 参  数：$result 结果集(选填)
    -- 返回值：数组
    -- 实  例：fetch_row();
    *******************************************************************/
    public function fetch_row($result=''){
        if (empty($result)) 
			$result=$this->result;
        if (!$result) 
			$this->show_error('未获取到查询结果',true);
        return mysqli_fetch_row($result);
    }
	/******************************************************************
    -- 函数名：fetch_obj($result)
    -- 作  用：将当前行当作对象数组返回,使用$row->content
    -- 参  数：$result 结果集(选填)
    -- 返回值：对象
    -- 实  例：fetch_obj();
    *******************************************************************/
	public function fetch_obj($result=''){
        if (empty($result)) $result=$this->result;
        if (!$result) $this->show_error('未获取到查询结果',true);
        return mysqli_fetch_object($result);
    }
	
	/******************************************************************
    -- 函数名：insert_id()
    -- 作  用：取得上一步 INSERT 操作产生的 ID
    -- 参  数：$result 结果集(选填)
    -- 返回值：int
    -- 实  例：insert_id()
    *******************************************************************/
    public function insert_id(){
		return mysqli_insert_id($this->conn);
	}
	
	/******************************************************************
    -- 函数名：data_seek($id)
    -- 作  用：指向确定的一条数据记录
    -- 参  数：$id(0...rows-1)(必填)
    -- 返回值：数组
    -- 实  例：data_seek(144);
    *******************************************************************/
	public function data_seek($id){
        if ($id>0) $id=$id-1;
        if (!mysqli_data_seek($this->result,$id)) 
			$this->show_error('指定的数据为空');
        return $this->result;
    }

	/******************************************************************
    函数名：num_fields($result)
    作  用：返回结果集中字段的数目
    参  数：$result(必填)
    返回值：字符串
    实  例：num_fields();
    *******************************************************************/
    public function num_fields($result=''){
        if (empty($result)) 
			$result=$this->result;
        if (!$result) $this->show_error('未获取到查询结果',true);
        return mysqli_num_fields($result);
    }

	/******************************************************************
    函数名：num_rows($result)
    作  用：根据select查询结果计算结果集条数
    参  数：$result(必填)
    返回值：字符串
    实  例：num_rows()
    *******************************************************************/
    public function num_rows($result=''){
        if (empty($result)) 
			$result=$this->result;
        $rows=mysqli_num_rows($result);
        if ($result==null){
            $rows=0;
            $this->show_error(' 未获取到查询结果',true);
        }
        return $rows>0?$rows:0;
    }
	
	/******************************************************************
    函数名：affected_rows()
    作  用：根据insert,update,delete执行结果取得影响行数
    参  数：$result(必填)
    返回值：字符串
    实  例：mysqli_query($link, "CREATE TABLE Language SELECT * from CountryLanguage");
			printf("Affected rows (INSERT): %d\n", affected_rows($link));
    *******************************************************************/
    public function affected_rows(){
		return mysqli_affected_rows($this->conn);
	}

	/******************************************************************
    函数名：getQuery($unset)
    作  用：获取地址栏参数
    参  数：$unset表示不需要获取的参数，多个参数请用,分隔(选填)
    返回值：字符串
    实  例：getQuery('page,sort')
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
    函数名：getPage($Table,$Fileds,$Condition,$pageSize)
    作  用：获取分页信息
    参  数：$Table 表名(必填)
            $Fileds 字段名，默认所有字段(选填)
            $Condition 查询条件(选填)
            $pageSize 每页显示记录条数，默认10条(选填)
    返回值：字符串
    实  例：getPage("member")
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

    // 构造分页limit语句，和getPage()函数搭配使用
    public function limit($str=false){
        $offset=($this->pageNo-1)*$this->pageSize;
        return $str?' LIMIT '.$offset.','.$this->pageSize:$offset;
    }

	/******************************************************************
    函数名：showPage($number)
    作  用： 显示分页，必须和getPage()函数搭配使用
    参  数：$number Bool(选填)
    返回值：字符串
    实  例：getPage("member");showPage();
    *******************************************************************/
    public function showPage($number=true){
        $pageBar='';
        if ($this->pageAll>1){
            $pageBar.='<ul class="page">'.chr(10);
            $url=$this->getQuery('page');
            $url=empty($url)?'?page=':'?'.$url.'&page=';
            if ($this->pageNo>1){
                $pageBar.='<li><a href="'.$url.'1">首页</a></li>'.chr(10);
                $pageBar.='<li><a href="'.$url.($this->pageNo-1).'">上页</a></li> '.chr(10);
            }else{
                $pageBar.='<li class="stop"><span>首页</span></li>'.chr(10);
                $pageBar.='<li class="stop"><span>上页</span></li>'.chr(10);
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
                $pageBar.='<li><a href="'.$url.($this->pageNo+1).'">下页</a>'.chr(10);
                $pageBar.='<li><a href="'.$url.$this->pageAll.'">尾页</a></li>'.chr(10);
            }else{
                $pageBar.='<li class="stop"><span>下页</span></li>'.chr(10);
                $pageBar.='<li class="stop"><span>尾页</span></li>'.chr(10);
            }
            $pageBar.='<li class="stop"><span>';
            $pageBar.="页次:{$this-> pageNo}/{$this->pageAll} {$this->pageSize}条/页 总记录:{$this->rowsAll} 转到:";
            $pageBar.="<input id=\"page\" value=\"{$this->pageNo}\" type=\"text\" onblur=\"goPage('{$url}',{$this->pageAll});\" />";
            $pageBar.='</span></li></ul>'.chr(10);
        }
        echo $pageBar;
    }
	
	/******************************************************************
    -- 函数名：drop($table)
    -- 作  用：删除表(请慎用,无法恢复)
    -- 参  数：$table 要删除的表名，默认为所有(选填)
    -- 返回值：无
    -- 实  例：$DB->drop('mydb')
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
    -- 函数名：makeSql($table)
    -- 作  用：从数据表读取信息并生成SQL语句,导出到数据库
    -- 参  数：$table 待读取的表名(必填)
    -- 返回值：字符串
    -- 实  例：makeSql("news");
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
    -- 函数名：readSql($filePath)
    -- 作  用：读取SQL文件并过滤注释
    -- 参  数：$filePath SQL文件路径(必填)
    -- 返回值：字符串/布尔/数组
    -- 实  例：无
    *******************************************************************/
    public function readSql($filePath){
        if (!file_exists($filePath)) return false;
        $sql=file_get_contents($filePath);
        if (empty($sql)) return '';
		$sql=preg_replace('/(?<!\\/)\\/\\*([^*\\/]|\\*(?!\\/)|\\/(?<!\\*))*((?=\\*\\/))(\\*\\/)/','',$sql); //过滤批量注释
        $sql=preg_replace('/(--.*)|[\f\n\r\t\v]* /',' ',$sql); //过滤单行注释与回车换行符
        $sql=preg_replace('/ {2,}/',' ',$sql); //将两个以上的连续空格替换为一个，可以省略这一步
        $arr=explode(';',$sql);
        $sql=array();
        foreach ($arr as $str){
            $str=trim($str);
            if (!empty($str)) $sql[]=$str;
        }
        return $sql;
    }
	
	 /******************************************************************
    -- 函数名：saveSql($sqlPath,$table)
    -- 作  用：将当前数据库信息保存为SQL文件
    -- 参  数：$sqlPath SQL文件保存路径，如果为空则自动以当前日期为文件名并保存到当前目录(选填)
              $table 待保存的表名，为空着表示保存所有信息(选填)
    -- 返回值：字符串
    -- 实  例：$DB->saveSql('../mydb.sql');
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
    -- 函数名：loadSql($filePath)
    -- 作  用：从SQL文件导入信息到数据库
    -- 参  数：$filePath SQL文件路径(必填)
    -- 返回值：字符串
    -- 实  例：$DB->loadSql('../mydb.sql');
    *******************************************************************/
    public function loadSql($filePath){
        $val = $this->readSql($filePath);
        if ($val == false) 
			$this->show_error($filePath.'不存在');
        elseif (empty($val)) 
			$this->show_error($filePath.'中无有效数据');
        else{
            $errList='';
            foreach ($val as $sql){
                $result=$this->query($sql);
                if (!$result) $errList.='执行语句'.$sql.'失败<br />';
            }
            return $errList;
        }
        return false;
    }

	/******************************************************************
    -- 函数名：free($result)
    -- 作  用：释放结果集
    -- 参  数：$result (选填)
    -- 返回值：字符串
    -- 实  例：free();
    *******************************************************************/
    public function free($result=''){
        if (empty($result)) $result=$this->result;
        if ($result){
            @mysqli_free_result($result);
        }
    }
	
	/******************************************************************
    -- 函数名：close()
    -- 作  用: 关闭数据库
    -- 参  数：
    -- 返回值：字符串
    -- 实  例：free();
    *******************************************************************/
    public function close(){
        mysqli_close($this->conn);
    }

	/******************************************************************
    -- 函数名：__destruct()
    -- 作  用：析构函数，自动释放结果集、关闭数据库,垃圾回收机制
    -- 参  数：
    -- 返回值：字符串
    -- 实  例： __destruct();
    *******************************************************************/
    public function __destruct(){
        $this->free();
        $this->close();
    }
	
	/******************************************************************
    -- 函数名：getAll($sql)
    -- 作  用：执行SQL语句获取所有数据
    -- 参  数：$sql 当前执行的SQL语句 [必填]
    -- 返回值：数组
    -- 实  例： getAll("SELECT * FROM news limit 0,10");
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
    -- 函数名：getRow($sql)
    -- 作  用：从sql查询中取得一行
    -- 参  数：$sql 当前执行的SQL语句 [必填]
			   $limited = false 是否取1行数据的标记 [选填]
    -- 返回值：数组
    -- 实  例： getRow("SELECT * FROM news limit 0,10");
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
    -- 函数名：getOne($sql)
    -- 作  用：获取一个数据
    -- 参  数：$sql 当前执行的SQL语句 [必填]
			   $limited = false 是否取1行数据的标记 [选填]
    -- 返回值：数组
    -- 实  例： getOne("SELECT * FROM news limit 0,10");
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
     -- 函数名： getip()
     -- 作  用：获得客户端真实的IP地址
     -- 参  数：无
     -- 返回值：字符串
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
     -- 函数名：show_error($message,$flag)
     -- 作  用：输出显示错误信息
     -- 参  数：$msg 错误信息(必填)
                $flag 显示错误的SQL语句，在SQL语句错误时使用(选填)
     -- 返回值：文件
    *******************************************************************/
    public function show_error($msg='',$flag=false){
        //$err='['.mysqli_errno($this->conn).']'.mysqli_error($this->conn);
		$err='['.mysqli_connect_errno().']'.mysqli_connect_error();
		$fileName ="";

        if ($flag) $sql='错误的SQL语句：'.$this->sql;
        if ($this->errLog){
            $dir = trim($this->errDir,'/');
            $this-> makeDirs($dir);
            $filePath=$dir.'/'.date('Y-m-d').'.log';
            $text=" 错误事件：".$msg."\r\n错误原因：".$err."\r\n".($sql?$sql."\r\n":'')."客户端 IP：".$this->getip()."\r\n记录时间：".date('Y-m-d H:i:s')."\r\n\r\n";
            $log=' 错误日志：__'.(error_log($text,3,$filePath)?'此错误信息已被自动记录到日志'.$fileName:'写入错误信息到日志失败');
        }
        if ($this->showErr){
            echo '<fieldset class="errlog">
				<legend>错误信息提示</legend>
				<label class="tip">错误事件：'.$err.'</label>
				<label class="msg">错误原因：'.$msg.'</label>
				<label class="sql">'.$sql.'</label>
				<label class="log">'.$log.'</label>
				</fieldset>';
            exit();
        }
    }

	 /******************************************************************
     -- 函数名：makeDirs($dirs,$mode)
     -- 作  用：自动建立目录,主要是为了给错误日志和导出SQL文件提供支持
     -- 参  数：$dirs 创建目录的路径地址(必填)
                $mode 最大可能的访问权(选填)
     -- 返回值：Bool 是否正确创建
    *******************************************************************/
    public function makeDirs($dirs='',$mode='0777'){
        $dirs=str_replace('\\','/',trim($dirs));
        if (!empty($dirs) && !file_exists($dirs)){
            $this->makeDirs(dirname($dirs));
            mkdir($dirs,$mode) or die ('建立目录'.$dirs.'失败,请尝试手动建立!');
        }
    }

   // 取得结果数据
    public function result($result=''){
        if (empty($result)) 
			$result=$this->result;
        if ($result==null) 
			$this->show_error('未获取到查询结果',true);
        return mysql_result($result);
    }
}


/******************************************************************Mysqli的函数说明
mysqli_affected_rows -返回一个MySQL操作受影响的行数 
mysqli_character_set_name -返回数据库连接的默认字符集
mysqli_close -关闭当前打开数据库连接 
mysqli_connect_errno -返回最后一次操作的错误代码 
mysqli_connect_error -返回最后一次操作的错误字符串描述 
mysqli_connect -打开一个新的连接到MySQL服务器 
mysqli_data_seek -在当前记录结果集中任意移动行指针 
mysqli_errno -返回错误代码最近函数调用 
mysqli_error -返回一个字符串描述过去的错误 
mysqli_fetch_array -从结果集中取得一行作为关联，数字数组，或两者兼施 
mysqli_fetch_assoc -从结果集中取得一行作为关联数组 
mysqli_fetch_field -返回明年领域中的结果集 
mysqli_fetch_object -返回当前行的结果集作为一个对象 
mysqli_fetch_row -取得结果集中取得一行作为枚举数组 
mysqli_free_result -释放内存与结果 
mysqli_get_client_info -返回MySQL客户端版本作为一个字符串 
mysqli_get_client_version -取得MySQL客户端信息 
mysqli_get_host_info -返回一个字符串代表的连接类型使用 
mysqli_get_proto_info -返回版本的MySQL使用协议 
mysqli_get_server_info -返回版本的MySQL服务器 
mysqli_get_server_version -返回版本的MySQL服务器作为一个整数 
mysqli_insert_id -返回自动生成的编号使用最后查询 
mysqli_num_fields -获取的若干领域中的结果 
mysqli_num_rows -获取的行数的结果 

mysqli_autocommit -开启或关闭 autocommit 数据库修改 
mysqli_bind_param -别名mysqli_stmt_bind_param （ ） 
mysqli_bind_result -别名mysqli_stmt_bind_result （ ） 
mysqli_change_user -更改指定数据库连接的用户 
mysqli_client_encoding -别名mysqli_character_set_name （ ） 
mysqli_commit -当前事物 
mysqli_debug -调试 
mysqli_disable_reads_from_master -禁用读取主对像 
mysqli_disable_rpl_parse -禁用RPL解析 
mysqli_dump_debug_info -转储调试信息的日志 
mysqli_embedded_connect -打开一个连接到嵌入式MySQL服务器 
mysqli_enable_reads_from_master -启用内容由主 
mysqli_enable_rpl_parse -启用RPL解析 
mysqli_escape_string -别名mysqli_real_escape_string （ ） 
mysqli_execute -别名mysqli_stmt_execute （ ） 
mysqli_fetch_field_direct -获取元数据的一个单一的领域 
mysqli_fetch_fields -返回一个数组对象代表领域的结果集 
mysqli_fetch_lengths -返回长度列的当前行的结果集 
mysqli_fetch -别名mysqli_stmt_fetch （ ） 
mysqli_field_count -返回的列数最近查询 
mysqli_field_seek -设为结果指针到指定的外地抵消 
mysqli_field_tell -获取当前外地抵消的结果指针 
mysqli_get_metadata -别名mysqli_stmt_result_metadata （ ） 
mysqli_info -检索信息，最近执行的查询 
mysqli_init -初始化MySQLi并返回一个资源使用mysqli_real_connect （ ） 
mysqli_kill -要求服务器要杀死一个MySQL线程 
mysqli_master_query -强制执行查询总在主/从设置 
mysqli_more_results -检查是否有任何更多的查询结果来自一个多查询 
mysqli_multi_query -执行查询数据库 
mysqli_next_result -准备明年的结果multi_query 
mysqli_options -设置选项 
mysqli_param_count -别名mysqli_stmt_param_count （ ） 
mysqli_ping -的Ping一个服务器连接，或尝试重新连接，如果已下降 
mysqli_prepare -准备一个SQL语句的执行 
mysqli_query -执行查询数据库 
mysqli_real_connect -打开一个连接到MySQL服务器 
mysqli_real_escape_string -转义特殊字符的字符串，用于SQL语句，并考虑到当前的字符集的连接 
mysqli_real_query -执行一个SQL查询 
mysqli_report -启用或禁用内部报告功能 
mysqli_rollback -回滚当前事务 
mysqli_rpl_parse_enabled -检查是否启用RPL解析 
mysqli_rpl_probe - RPL探针 
mysqli_rpl_query_type -返回RPL查询类型 
mysqli_select_db -选择的默认数据库数据库查询 
mysqli_send_long_data -别名mysqli_stmt_send_long_data （ ） 
mysqli_send_query -发送查询并返回 
mysqli_server_end -关机嵌入式服务器 
mysqli_server_init -初始化嵌入式服务器 
mysqli_set_charset -集的默认客户端字符集 
mysqli_set_opt -别名mysqli_options （ ） 
mysqli_sqlstate -返回SQLSTATE错误从一个MySQL操作 
mysqli_ssl_set -用于建立安全连接使用SSL 
mysqli_stat -获取当前的系统状态 
mysqli_stmt_affected_rows -返回总数列改变，删除或插入的最后执行的声明 
mysqli_stmt_bind_param -绑定变量一份声明作为参数 
mysqli_stmt_bind_result -绑定变量的一份声明中存储的结果 
mysqli_stmt_close -关闭一份声明 
mysqli_stmt_data_seek -寻找一个任意行声明的结果集 
mysqli_stmt_errno -返回错误代码的最新声明呼吁 
mysqli_stmt_error -返回一个字符串描述最后声明错误 
mysqli_stmt_execute -执行一个准备查询 
mysqli_stmt_fetch -获取结果一份准备好的声明中纳入约束变量 
mysqli_stmt_free_result -免储存记忆的结果给予处理的声明 
mysqli_stmt_init -初始化了言，并返回一个对象，用于mysqli_stmt_prepare 
mysqli_stmt_num_rows -返回的行数报表结果集 
mysqli_stmt_param_count -返回一些参数给定的声明 
mysqli_stmt_prepare -准备一个SQL语句的执行 
mysqli_stmt_reset -重置一份声明 
mysqli_stmt_result_metadata -返回结果集元数据的一份书面声明 
mysqli_stmt_send_long_data -发送数据块 
mysqli_stmt_sqlstate -返回SQLSTATE错误行动从以往的声明 
mysqli_stmt_store_result -转让的结果集由一份声明 
mysqli_store_result -转让的结果集的最后查询 
mysqli_thread_id -返回线程ID为当前连接 
mysqli_thread_safe -返回是否线程安全考虑或不 
mysqli_use_result -开创检索结果集 
mysqli_warning_count -返回一些警告过去查询提供链接
*/
?>