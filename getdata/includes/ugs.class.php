<?php
// 兼容 php4 php5
// 程序作者 袁吉
// 联系方法 415192821@qq.com QQ 415192821
// 简单调用方法
/*
<?
include ("ugs.php"); // 你可以下载本ugs.phps 然后重命名为ugs.php
$ugs = new ugs();
$url = "http://domainname.com/path_to_your_target?param";
$ugs->seturl($url);
$ugs->gather();
//............这里可以调用本类里的其它方法，对$ugs->value_ 做调整,
以满足您的要求
$content=$ugs->getcontent();
print($content);
?>
*/ 

class ugs {
	var $value_; //'目标内容
	var $src_; //'目标URL地址
	function seturl($url) {
		$this->src_ = $url;
	}
	function getcontent() {
		return $this->value_;
	}
	// 获取目标
	function getfile($url) {
		$url_parsed = parse_url($url);
		$host = $url_parsed["host"];
		$port = $url_parsed["port"];
		if ($port == 0)
			$port = 80;
		$path = $url_parsed["path"];
		if (empty ($path))
			$path = "/";
		if ($url_parsed["query"] != "")
			$path .= "?" . $url_parsed["query"];
		$out = "GET $path HTTP/1.1\r\nHost: $host\r\n\r\n";
		$fp = @fsockopen($host, $port, $errno, $errstr, 100);
		if($fp){//如果sock连接成功
			fwrite($fp, $out);
			$body = false;
			while (!feof($fp)) {
				$s = fgets($fp, 1024);
				if ($body)
					$in .= $s;
				if ($s == "\r\n")
					$body = true;
			}
			fclose($fp);
			return $in;
		}else{//否则输出错误
			echo "$errstr ($errno)<br />\n";
			return false;
		}
	}

	function getfile_curl($url) {//curl的详细说明，见后面的备注
		$curl = "/usr/local/bin/curl "; // path to your curl
		$curl_options = " -s --connect-timeout 10 --max-time 10 ";
		// curl 用法请参考 curl --help 或者 man curl
		// curl 参数非常之丰富,可以模拟各种浏览器(agent) 可以设置referer
		$cmd = "$curl $curl_options $url ";
		@ exec($cmd, $o, $r);
		if ($r != 0) {
			return "超时";
		} else {
			$o = join("", $o);
			return $o;
		}
	}
	function gather_curl($curl) {
		$http = $this->getfile_curl($this->src_);
		return $this->value_ = $http;
	}
	function gather_array($url) {
		return file($url);
	}
	// 开始收集
	function gather() {
/*		if(file_exists("temp/uggbootsky/".$this->src_.".html")){
			return $this->value_ = file_get_contents("temp/uggbootsky/".$this->src_.".html");
		}*/
		$http = $this->getfile($this->src_);
		return $this->value_ = $http;
	}
	// 处理本地文件
	function gather_local($toline = true) {
		if ($toline) {
			$http = file($this->src_);
			return $this->value_ = $this->BytesToBstr($http);
		} else {
			$http = file($this->src_);
			return $this->value_ = $http;
		}

	}
	// 删除回车换行
	function noReturn() {
		$this->value_ = str_replace("\n", "", $this->value_);
		$this->value_ = str_replace("\r", "", $this->value_);
	}
	//'对收集到的内容中的个别字符串用新值更换/方法
	//'参数分别是旧字符串,新字符串
	function change($oldStr, $str) {
		$this->value_ = str_replace($oldStr, $str, $this->value_);
	}
	//'按指定首尾字符串对收集的内容进行裁减（不包括首尾字符串）方法
	// $no 必须是 1,2 3 ... 不允许是0
	//$comprise 可以选择 start 或者 end 或者 all 或者 什么都不填
	function cut($start, $end, $no = '1', $comprise = '') {
		$string = explode($start, $this->value_);
		//print_r($string);
		$string = explode($end, $string[$no]);
		//print_r($string);
		switch ($comprise) {
			case 'start' :
				$string = $start . $string[0];
				break;
			case 'end' :
				$string = $string[0] . $end;
				break;
			case 'all' :
				$string = $start . $string[0] . $end;
				break;
			default :
				$string = $string[0];
		}
		return $this->value_ = $string;
	}
	//'按指定首尾字符串对收集的内容用新值进行替换（不包括首尾字符串）方法
	// '参数分别是首字符串,尾字符串,新值,新值位空则为过滤
	function filt($head, $bot, $str, $no = '1', $comprise = '') {
		$tmp_v = $this->value_;
		$tmp = $this->cut($head, $bot, $no, $comprise);
		return $this->value_ = str_replace($tmp, $str, $tmp_v);
	}
	//'将收集的内容中的绝对URL地址改为本地相对地址
	// 还没实现
	function local() {

	}
	//'对收集的内容中的符合正则表达式的字符串用新值进行替换/方法
	//'参数是你自定义的正则表达式,新值
	function replaceByReg($patrn, $str) {
		return $this->value_ = join("", preg_replace($patrn, $str, $this->value_));
	}
	//调试显示
	function debug() {
		$tempstr = "<SCRIPT>function runEx(){var winEx2 = window.open(\"\", \"winEx2\", \"width=500,height=300,status=yes,menubar=no,scrollbars=yes,resizable=yes\"); winEx2.document.open(\"text/html\", \"replace\"); winEx2.document.write(unescape(event.srcElement.parentElement.children[0].value)); winEx2.document.close(); }function saveFile(){var win=window.open('','','top=10000,left=10000');win.document.write(document.all.asdf.innerText);win.document.execCommand('SaveAs','','javascript.htm');win.close();}</SCRIPT><center><TEXTAREA id=asdf name=textfield rows=32 wrap=VIRTUAL cols=\"120\">" . $this->value_ . "</TEXTAREA><BR><BR><INPUT name=Button onclick=runEx() type=button value=\"查看效果\"> <INPUT name=Button onclick=asdf.select() type=button value=\"全选\"> <INPUT name=Button onclick=\"asdf.value=''\" type=button value=\"清空\"> <INPUT onclick=saveFile(); type=button value=\"保存代码\"></center>";
		echo $tempstr;
	}

	/*   函数   sub_str($text,   $length)  
	**   功能   从文本中截取指定长度字符串,考虑了对中文的处理  
	**   参数   $text   要截取的文本  
	**   参数   $length   要截取的字符串长度  
	*/  
	function sub_str($text,$length,$other_str=false){  
		for($i=0;   $i<$length;   $i++){  
			$chr   =   substr($text,   $i,   1);  
			if(ord($chr)   >   0x80){//字符是中文  
				$length++;  
				$i++;  
			}  
		}  
		$str   =   substr($text,0,$length);    
		return   $other_str?$str.$other_str:$str;  
	}
//另外的一个实现函数，效率没有上面的好	
/*function cnSubstr($string,$strlen,$other=true){  
	for($i=0;$i<$strlen;$i++){  
		if(ord(substr($string,$i,1))>0xa0){  
			$j++;  
		}  
		if($j%2!=0){  
			$strlen++;  
		}
		$rstr = substr($string,0,$strlen);  
		if(strlen($string)>$strlen   &&   $other){  
			$rstr.='...';
		}  
	}  
	return   $rstr;  
}*/
	
	function GrabImage($url,$filename="") {//最佳的保存图片的函数
		set_time_limit(24 * 60 * 60);//php set_time_limit函数的功能是设置当前页面执行多长时间不过期哦。
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
}

/*  测试代码
$ugs = new ugs();
$url = "http://www.songsongni.com/";
$ugs->seturl($url);
$ugs->gather();
//............这里可以调用本类里的其它方法，对$ugs->value_ 做调整,以满足您的要求
$content=$ugs->getcontent();
echo $content;
*/
/*备注 curl函数详细说明*/
/*
PHP 支持curl函数(允许你用不同的协议连接和沟通不同的服务器). curl
是使用URL语法的传送文件工具，支持FTP、FTPS、HTTP HTPPS SCP SFTP TFTP TELNET DICT
FILE和LDAP。curl 支持SSL证书、HTTP POST、HTTP PUT 、FTP
上传，kerberos、基于HTT格式的上传、代理、cookie、用户＋口令证明、文件传送恢复、http代理通道和大量其他有用的技巧。
　　以下是一个例子：是把PHP的主页取回放到一个文件中。
　　例 1. 使用PHP的CURL模块取回PHP主页
$ch = curl_init (”http://www.php.net/”);
$fp = fopen (”php_homepage.txt”, “w”);
curl_setopt ($ch, CURLOPT_FILE, $fp);
curl_setopt ($ch, CURLOPT_HEADER, 0);
curl_exec ($ch);
curl_close ($ch);
fclose ($fp);
?>
　　curl相关函数列表:
　　curl_init — 初始化一个CURL会话
　　curl_setopt — 为CURL调用设置一个选项
　　curl_exec — 执行一个CURL会话
　　curl_close — 关闭一个CURL会话
　　curl_version — 返回当前CURL版本
　　1>curl_init — 初始化一个CURL会话
　　描述
　　int curl_init ([string url])
　
　curl_init()函数将初始化一个新的会话，返回一个CURL句柄供 curl_setopt(), curl_exec(),和
curl_close()
函数使用。如果可选参数被提供，那么CURLOPT_URL选项将被设置成这个参数的值。你可以使用curl_setopt()函数人工设置。
　　例 1. 初始化一个新的CURL会话，且取回一个网页
$ch = curl_init();
curl_setopt ($ch, CURLOPT_URL, “http://www.zend.com/”);
curl_setopt ($ch, CURLOPT_HEADER, 0);
curl_exec ($ch);
curl_close ($ch);
?>
　　参见：curl_close(), curl_setopt()
　　2>curl_setopt — 为CURL调用设置一个选项
　　描述
　　bool curl_setopt (int ch, string option, mixed value)
　　curl_setopt()函数将为一个CURL会话设置选项。option参数是你想要的设置，value是这个选项给定的值。
　　下列选项的值将被作为长整形使用(在option参数中指定)：
　　CURLOPT_INFILESIZE: 当你上传一个文件到远程站点，这个选项告诉PHP你上传文件的大小。
　　CURLOPT_VERBOSE: 如果你想CURL报告每一件意外的事情，设置这个选项为一个非零值。
　　CURLOPT_HEADER: 如果你想把一个头包含在输出中，设置这个选项为一个非零值。
　　CURLOPT_NOPROGRESS: 如果你不会PHP为CURL传输显示一个进程条，设置这个选项为一个非零值。
　　注意：PHP自动设置这个选项为非零值，你应该仅仅为了调试的目的来改变这个选项。
　　CURLOPT_NOBODY: 如果你不想在输出中包含body部分，设置这个选项为一个非零值。
　　CURLOPT_FAILONERROR: 如果你想让PHP在发生错误(HTTP代码返回大于等于300)时，不显示，设置这个选项为一人非零值。默认行为是返回一个正常页，忽略代码。
　　CURLOPT_UPLOAD: 如果你想让PHP为上传做准备，设置这个选项为一个非零值。
　　CURLOPT_POST: 如果你想PHP去做一个正规的HTTP POST，设置这个选项为一个非零值。这个POST是普通的 application/x-www-from-urlencoded 类型，多数被HTML表单使用。
　　CURLOPT_FTPLISTONLY: 设置这个选项为非零值，PHP将列出FTP的目录名列表。
　　CURLOPT_FTPAPPEND: 设置这个选项为一个非零值，PHP将应用远程文件代替覆盖它。
　　CURLOPT_NETRC: 设置这个选项为一个非零值，PHP将在你的 ~./netrc 文件中查找你要建立连接的远程站点的用户名及密码。
　　CURLOPT_FOLLOWLOCATION: 设置这个选项为一个非零值(象 “Location: “)的头，服务器会把它当做HTTP头的一部分发送(注意这是递归的，PHP将发送形如 “Location: “的头)。
　　CURLOPT_PUT: 设置这个选项为一个非零值去用HTTP上传一个文件。要上传这个文件必须设置CURLOPT_INFILE和CURLOPT_INFILESIZE选项.
　　CURLOPT_MUTE: 设置这个选项为一个非零值，PHP对于CURL函数将完全沉默。
　　CURLOPT_TIMEOUT: 设置一个长整形数，作为最大延续多少秒。
　　CURLOPT_LOW_SPEED_LIMIT: 设置一个长整形数，控制传送多少字节。
　　CURLOPT_LOW_SPEED_TIME: 设置一个长整形数，控制多少秒传送CURLOPT_LOW_SPEED_LIMIT规定的字节数。
　　CURLOPT_RESUME_from: 传递一个包含字节偏移地址的长整形参数，(你想转移到的开始表单)。
　　CURLOPT_SSLVERSION: 传递一个包含SSL版本的长参数。默认PHP将被它自己努力的确定，在更多的安全中你必须手工设置。
　　CURLOPT_TIMECONDITION: 传递一个长参数，指定怎么处理CURLOPT_TIMEVALUE参数。你可以设置这个参数为TIMECOND_IFMODSINCE 或TIMECOND_ISUNMODSINCE。这仅用于HTTP。
　　CURLOPT_TIMEVALUE: 传递一个从1970-1-1开始到现在的秒数。这个时间将被CURLOPT_TIMEVALUE选项作为指定值使用，或被默认 TIMECOND_IFMODSINCE使用。
　　下列选项的值将被作为字符串：
　　CURLOPT_URL: 这是你想用PHP取回的URL地址。你也可以在用curl_init()函数初始化时设置这个选项。
　　CURLOPT_USERPWD: 传递一个形如[username]:[password]风格的字符串,作用PHP去连接。
　　CURLOPT_PROXYUSERPWD: 传递一个形如[username]:[password] 格式的字符串去连接HTTP代理。
　　CURLOPT_RANGE: 传递一个你想指定的范围。它应该是”X-Y”格式，X或Y是被除外的。HTTP传送同样支持几个间隔，用逗句来分隔(X-Y,N-M)。
　　CURLOPT_POSTFIELDS: 传递一个作为HTTP “POST”操作的所有数据的字符串。
　　CURLOPT_REFERER: 在HTTP请求中包含一个”referer”头的字符串。
　　CURLOPT_USERAGENT: 在HTTP请求中包含一个”user-agent”头的字符串。
　
　CURLOPT_FTPPORT: 传递一个包含被ftp
“POST”指令使用的IP地址。这个POST指令告诉远程服务器去连接我们指定的IP地址。这个字符串可以是一个IP地址，一个主机名，一个网络界面名
(在UNIX下)，或是’-’(使用系统默认IP地址)。
　　CURLOPT_COOKIE: 传递一个包含HTTP cookie的头连接。
　　CURLOPT_SSLCERT: 传递一个包含PEM格式证书的字符串。
　　CURLOPT_SSLCERTPASSWD: 传递一个包含使用CURLOPT_SSLCERT证书必需的密码。
　　CURLOPT_COOKIEFILE: 传递一个包含cookie数据的文件的名字的字符串。这个cookie文件可以是Netscape格式，或是堆存在文件中的HTTP风格的头。
　
　CURLOPT_CUSTOMREQUEST:
当进行HTTP请求时，传递一个字符被GET或HEAD使用。为进行DELETE或其它操作是有益的，更Pass a string to be
used instead of GET or HEAD when doing an HTTP request. This is useful
for doing or another, more obscure, HTTP request.
　　注意: 在确认你的服务器支持命令先不要去这样做。
　　下列的选项要求一个文件描述(通过使用fopen()函数获得)：
　　CURLOPT_FILE: 这个文件将是你放置传送的输出文件，默认是STDOUT.
　　CURLOPT_INFILE: 这个文件是你传送过来的输入文件。
　　CURLOPT_WRITEHEADER: 这个文件写有你输出的头部分。
　　CURLOPT_STDERR: 这个文件写有错误而不是stderr。
　　3>curl_exec — 执行一个CURL会话
　　描述
　　bool curl_exec (int ch)
　　在你初始化一个CURL会话，及为这个会话设置了所有的选项后，这个函数将被调用。它的目的仅仅是执行预先确定的CURL会话(通过给定的ch参数)。
　　4>curl_close – 关闭一个CURL会话
　　描述
　　void curl_close (int ch)
　　这个函数关闭一个CURL会话，并且释放所有的资源。CURL句柄(ch参数)也被删除。
　　5>curl_version — 返回当前CURL版本
　　描述
　　string curl_version (void)
　　curl_version()函数返回一个包含CURL版本的字符串。



*/
?>