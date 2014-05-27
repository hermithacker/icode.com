<?PHP
//读取数据表的SQL语句汇总
$sys_parentmenus = 'SELECT mid,parmid,msequence,mtext,mname,hypeline,flag,haschild,memo FROM sysmenus';

$sys_childmenus  = 'SELECT mid,parmid,msequence,mtext,mname,hypeline,flag,haschild,memo FROM sysmenus WHERE parmid =';


?>