<?php
/**
* 获取目标网站的内容
* 根据网站的网址进行对内容的解析
* 
* By Laurence Chen 2013年11月27日
*
*/
	class Url{
		private  $content;  //网站内容
		private  $url ;		//网站地址

		/**
		*函数：getFileContent($url)
		*功能：获取指定路径完整的文件内容
		*参数：访问的url地址
		*
		* 2013年11月27日
		* 返回路径网站的全部内容
		*/
		function getFileContent($url){
			$url_parsed = parse_url($url);
			$host = "";
			$port = 0;
			$path = "";
			if(array_key_exists("host",$url_parsed)){
				$host = $url_parsed["host"];
			}
			if(array_key_exists("port",$url_parsed)){
				$port = $url_parsed["port"];
			}else{
				$port = 80;
			}
			if(array_key_exists("path",$url_parsed)){
				$path = $url_parsed["path"];
			}else{
				$path = "/";
			}
			if(array_key_exists("query",$url_parsed)){
				$path .= "?" . $url_parsed["query"];
			}
			//拼接的请求头
			$out = "GET $path HTTP/1.0\r\nHost: $host\r\n\r\n";
			$fp = @fsockopen($host, $port, $errno, $errstr, 30);
			if($fp){
				//如果sock连接成功
				fwrite($fp, $out);
				$body = false;
				$in = "";
				while (!feof($fp)) {
					$s = fgets($fp, 1024);
					if ($body)
						$in .= $s;
					if ($s == "\r\n")
						$body = true;
				}
				fclose($fp);
				return $in;
			}else{
				//否则输出错误   todo：记录到日志文件
				echo "$errstr ($errno)<br />\n";
				return false;
			}
		}
		
		/**
		*函数：gather_array($url)
		*功能：把整个文件读入一个数组中
		*数组的形式是：value对应文件的每一个行
		*参数：文件路径
		*2013年11月27日
		*/
		function gather_array($url) {
			return file($url);
		}
		
		/**
		*函数：gather()
		*功能：开始收集制定路径的内容
		*返回：路径文件的内容
		*2013年11月27日
		*/
		function gather() {
			$http =$this->getFileContent($this->url);
			return $this->content=$http;
		}

		/**
		*函数：noReturn();
		*功能：针对收集到的文本进行删除回车,换行符操作
		*对网页的显示没有任何影响，但将网页的源文件中\n和\r去掉了
		*2013年11月28日
		*/
		function noReturn() {
			$this->content = str_replace("\n", "", $this->content);
			$this->content = str_replace("\r", "", $this->content);
		}

		/**
		*函数：change($oldStr, $str);
		*功能：对收集到的内容中的个别字符串用新值更换
		*更改任意想要更改的字符串
		*参数：$oldStr 旧字符串
		*参数：$str    新字符串
		*2013年11月28日
		*/
		function change($oldStr, $str) {
			$this->content = str_replace($oldStr, $str, $this->content);
		}

		/**
		*函数：cut($start, $end, $no = '1', $comprise = '')
		*功能：按指定首尾字符串对收集的内容进行裁减（不包括首尾字符串）
		*起始字符串就是起始位置的分割符，得到中间的内容
		*参数：$start 开始位置的分割符
		*参数：$end   结束位置的分割符
		*参数：$no    必须是 1,2 3 ... 不允许是0，
		*参数：$comprise 可以选择 start 或者 end 或者 all 或者 什么都不填
		* $no 返回第几个匹配项;
		* $comprise可以选择是否带分隔符以及带哪一个分隔符
		* 2013年11月28日
		* 示例：
		* "(piece1) (piece2) (piece3) (piece4) (piece5) (piece6)";
		* echo $url->cut("(",")",2);
		* 结果：piece2
		*/
		function cut($start, $end, $no = '1', $comprise = '') {
			$string = explode($start, $this->content);
			$string = explode($end, $string[$no]);
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
			return $this->content = $string;
		}

		//'按指定首尾字符串对收集的内容用新值进行替换（不包括首尾字符串）方法
		// '参数分别是首字符串,尾字符串,新值,新值位空则为过滤
		/**
		*函数：filt($head, $bot, $str, $no = '1', $comprise = '')
		*功能：将分割的数据，在收集的内容中，用自定义字符创替换
		*参数：$head  分割开始位置（可以是数组）
		*参数：$bot   分割结束位置（可以是数组）
		*参数：$str   自定义的字符串
		*参数：$no    必须是 1,2 3 ... 不允许是0，
		*参数：$comprise 可以选择 start 或者 end 或者 all 或者 什么都不填
		* 2013年11月28日
		*/
		function filt($head, $bot, $str, $no = '1', $comprise = '') {
			$tmp_v = $this->content;
			$tmp = $this->cut($head, $bot, $no, $comprise);
			//将 $tmp_v 中全部的 $tmp 都被 $str 替换之后的结果
			return $this->content = str_replace($tmp, $str, $tmp_v);
		}

		/**
		* 函数：replaceByReg($patrn, $str)
		* 功能：对收集的内容中的符合正则表达式的字符串用新值进行替换
		* 参数：$patrn 自定义的正则表达式
		* 参数：$str   自定义的字符串
		* 2013年11月28日
		*
		*/
		function replaceByReg($patrn, $str) {
			return $this->content = join("", preg_replace($patrn, $str, $this->content));
		}

		/*函数:sub_str($text,$length)  
		* 功能:从文本中截取指定长度字符串,考虑了对中文的处理  
		* 参数:$text 要截取的文本  
		* 参数:$length要截取的字符串长度  
		* 2013年11月28日
		*/  
		function sub_str($text,$length,$other_str=false){  
			for($i=0;$i<$length;$i++){  
				$chr=substr($text,$i,1);  
				if(ord($chr)>0x80){
					//字符是中文  
					$length++;  
					$i++;
				}  
			}  
			$str=substr($text,0,$length);    
			return   $other_str?$str.$other_str:$str;  
		}
	
		/**
		* 函数：GrabImage($url,$filename="")	
		* 功能：最佳的保存图片的函数
		* 参数：$url :图片的路径地址(.gif // .png // .jpg)
		* 参数：$filename:自定义保存的文件名
		* 2013年11月28日
		*/
		function GrabImage($url,$filename="") {
			//php set_time_limit函数的功能是设置当前页面执行多长时间不过期哦。
			set_time_limit(24*60*60);
			if($url==""):return false;endif;
			
			if($filename=="") {
				//查找指定字符在字符串中的最后一次出现
				$ext=strrchr($url,"."); 
				if($ext!=".gif" && $ext!=".png" &&$ext!=".jpg"):return false;endif;
				$filename=date("dMYHis").$ext;
			}
			
			//打开输出控制缓冲
			ob_start();
			//缓冲的内容
			readfile($url);
			//返回输出缓冲区的内容
			$img = ob_get_contents();
			ob_end_clean();
			$size = strlen($img);
			$fp2=@fopen($filename, "a");
			fwrite($fp2,$img);
			fclose($fp2);
			return $filename;
		} 
	
	//-------------------------------------------------------------//
		/**
		 * 获取网站地址
		 * @return the $content
		 */
		public function getContent() {
			return $this->content;
		}

		/**
		 * 获取网站的路径 
		 * @return the $url
		 */
		public function getUrl() {
			return $this->url;
		}

		/**
		 * 重新设置网站的内容
		 * @param field_type $content
		 */
		public function setContent($content) {
			$this->content = $content;
		}

		/**
		 * 重新设置网站的路径
		 * @param field_type $url
		 */
		public function setUrl($url) {
			$this->url = $url;
		}
	 
		//调试Bug的页面显示
		function debug() {
			$tempstr = "<SCRIPT>function runEx(){var winEx2 = window.open(\"\", \"winEx2\", \"width=500,height=300,status=yes,menubar=no,scrollbars=yes,resizable=yes\"); winEx2.document.open(\"text/html\", \"replace\"); winEx2.document.write(unescape(event.srcElement.parentElement.children[0].value)); winEx2.document.close(); }function saveFile(){var win=window.open('','','top=10000,left=10000');win.document.write(document.all.asdf.innerText);win.document.execCommand('SaveAs','','javascript.htm');win.close();}</SCRIPT><center><TEXTAREA id=asdf name=textfield rows=32 wrap=VIRTUAL cols=\"120\">" . $this->content . "</TEXTAREA><BR><BR><INPUT name=Button onclick=runEx() type=button value=\"查看效果\"> <INPUT name=Button onclick=asdf.select() type=button value=\"全选\"> <INPUT name=Button onclick=\"asdf.value=''\" type=button value=\"清空\"> <INPUT onclick=saveFile(); type=button value=\"保存代码\"></center>";
			echo $tempstr;
		}
}

/**测试类的脚本代码
	//$url = new Url();
	//$url->setUrl("http://blog.jobbole.com");
	//echo $url->gather();  //这就是内容
	//$url->GrabImage("http://cdn2.jobbole.com/2011/11/Web-Coding.png","");
	//function sub_str($text,$length,$other_str=false)
	
	//echo $url->sub_str("这就是一个坑，需要转化asbcdde",8);
	//$url->setUrl("http://blog.jobbole.com");
	//$url->gather();  //这就是内容
	//$url->noReturn();
	//echo $url->getContent();

	//$str = "(piece1) (piece2) (piece3) (piece4) (piece5) (piece6)";
	//$url->setContent($str);
	//echo $url->cut("(",")",2,"all");

	//$search  = array('A', 'B', 'C', 'E');
	//$replace = array('D');
	//$subject = 'A';

	//echo str_replace($search, $replace, $subject);

	//--------------------------------------------以上是测试通过的文件
		/**
		*Curl:加载libcurl库允许你与各种的服务器使用各种类型的协议进行连接和通讯
		*libcurl目前支持http、https、ftp、gopher、telnet、dict、file和ldap协议
		*
		*/
/*
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
		
		//获取Curl
		function gather_curl($curl) {
			$http=$this->getfile_curl($this->url);
			return $this->content = $http;
		}
		//-------------------------------------------------------------//
		// 处理本地文件
		function gather_local($toline = true) {
			if ($toline) {
				$http = file($this->url);
				return $this->content = $this->BytesToBstr($http);
			} else {
				$http = file($this->url);
				return $this->content = $http;
			}
		}

		//'将收集的内容中的绝对URL地址改为本地相对地址
		function local() {
			
		}
*/

?>
