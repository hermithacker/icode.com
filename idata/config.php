<?PHP

	//不加限制，不过要小心使用 设置php内存使用限制 "-1" 时为不加限制
	ini_set('memory_limit', '-1');
	//设置程序最大运行时间、默认为60秒，单位为s
	ini_set('max_execution_time',30*60*60*60);


?>