<?PHP
error_reporting(E_ALL^E_NOTICE);
header("Content-Type:text/html;charset=utf-8");
//�������ƣ�����ҪС��ʹ�� ����php�ڴ�ʹ������ "-1" ʱΪ��������
ini_set('memory_limit', '-1');
//���ó����������ʱ�䡢Ĭ��Ϊ60�룬��λΪs
ini_set('max_execution_time',30*60*60*60);

ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
//����ʱ��
date_default_timezone_set('Asia/Shanghai');


//���ݿ���������
$db["HOST"]       ="localhost";
$db["NAME"]       ="root";
$db["PASSWORD"]   ="";
$db["DATABASE"]   ="iprojects";
$db["PORT"]	   	  ="3306";
$db["charset"]    ="utf8";

?>