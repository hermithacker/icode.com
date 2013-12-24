<?php
/**
* ��ȡĿ����վ������
* ������վ����ַ���ж����ݵĽ���
* 
* By Laurence Chen 2013��11��27��
*
*/
	class Url{
		private  $content;  //��վ����
		private  $url ;		//��վ��ַ

		/**
		*������getFileContent($url)
		*���ܣ���ȡָ��·���������ļ�����
		*���������ʵ�url��ַ
		*
		* 2013��11��27��
		* ����·����վ��ȫ������
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
			//ƴ�ӵ�����ͷ
			$out = "GET $path HTTP/1.0\r\nHost: $host\r\n\r\n";
			$fp = @fsockopen($host, $port, $errno, $errstr, 30);
			if($fp){
				//���sock���ӳɹ�
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
				//�����������   todo����¼����־�ļ�
				echo "$errstr ($errno)<br />\n";
				return false;
			}
		}
		
		/**
		*������gather_array($url)
		*���ܣ��������ļ�����һ��������
		*�������ʽ�ǣ�value��Ӧ�ļ���ÿһ����
		*�������ļ�·��
		*2013��11��27��
		*/
		function gather_array($url) {
			return file($url);
		}
		
		/**
		*������gather()
		*���ܣ���ʼ�ռ��ƶ�·��������
		*���أ�·���ļ�������
		*2013��11��27��
		*/
		function gather() {
			$http =$this->getFileContent($this->url);
			return $this->content=$http;
		}

		/**
		*������noReturn();
		*���ܣ�����ռ������ı�����ɾ���س�,���з�����
		*����ҳ����ʾû���κ�Ӱ�죬������ҳ��Դ�ļ���\n��\rȥ����
		*2013��11��28��
		*/
		function noReturn() {
			$this->content = str_replace("\n", "", $this->content);
			$this->content = str_replace("\r", "", $this->content);
		}

		/**
		*������change($oldStr, $str);
		*���ܣ����ռ����������еĸ����ַ�������ֵ����
		*����������Ҫ���ĵ��ַ���
		*������$oldStr ���ַ���
		*������$str    ���ַ���
		*2013��11��28��
		*/
		function change($oldStr, $str) {
			$this->content = str_replace($oldStr, $str, $this->content);
		}

		/**
		*������cut($start, $end, $no = '1', $comprise = '')
		*���ܣ���ָ����β�ַ������ռ������ݽ��вü�����������β�ַ�����
		*��ʼ�ַ���������ʼλ�õķָ�����õ��м������
		*������$start ��ʼλ�õķָ��
		*������$end   ����λ�õķָ��
		*������$no    ������ 1,2 3 ... ��������0��
		*������$comprise ����ѡ�� start ���� end ���� all ���� ʲô������
		* $no ���صڼ���ƥ����;
		* $comprise����ѡ���Ƿ���ָ����Լ�����һ���ָ���
		* 2013��11��28��
		* ʾ����
		* "(piece1) (piece2) (piece3) (piece4) (piece5) (piece6)";
		* echo $url->cut("(",")",2);
		* �����piece2
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

		//'��ָ����β�ַ������ռ�����������ֵ�����滻����������β�ַ���������
		// '�����ֱ������ַ���,β�ַ���,��ֵ,��ֵλ����Ϊ����
		/**
		*������filt($head, $bot, $str, $no = '1', $comprise = '')
		*���ܣ����ָ�����ݣ����ռ��������У����Զ����ַ����滻
		*������$head  �ָʼλ�ã����������飩
		*������$bot   �ָ����λ�ã����������飩
		*������$str   �Զ�����ַ���
		*������$no    ������ 1,2 3 ... ��������0��
		*������$comprise ����ѡ�� start ���� end ���� all ���� ʲô������
		* 2013��11��28��
		*/
		function filt($head, $bot, $str, $no = '1', $comprise = '') {
			$tmp_v = $this->content;
			$tmp = $this->cut($head, $bot, $no, $comprise);
			//�� $tmp_v ��ȫ���� $tmp ���� $str �滻֮��Ľ��
			return $this->content = str_replace($tmp, $str, $tmp_v);
		}

		/**
		* ������replaceByReg($patrn, $str)
		* ���ܣ����ռ��������еķ���������ʽ���ַ�������ֵ�����滻
		* ������$patrn �Զ����������ʽ
		* ������$str   �Զ�����ַ���
		* 2013��11��28��
		*
		*/
		function replaceByReg($patrn, $str) {
			return $this->content = join("", preg_replace($patrn, $str, $this->content));
		}

		/*����:sub_str($text,$length)  
		* ����:���ı��н�ȡָ�������ַ���,�����˶����ĵĴ���  
		* ����:$text Ҫ��ȡ���ı�  
		* ����:$lengthҪ��ȡ���ַ�������  
		* 2013��11��28��
		*/  
		function sub_str($text,$length,$other_str=false){  
			for($i=0;$i<$length;$i++){  
				$chr=substr($text,$i,1);  
				if(ord($chr)>0x80){
					//�ַ�������  
					$length++;  
					$i++;
				}  
			}  
			$str=substr($text,0,$length);    
			return   $other_str?$str.$other_str:$str;  
		}
	
		/**
		* ������GrabImage($url,$filename="")	
		* ���ܣ���ѵı���ͼƬ�ĺ���
		* ������$url :ͼƬ��·����ַ(.gif // .png // .jpg)
		* ������$filename:�Զ��屣����ļ���
		* 2013��11��28��
		*/
		function GrabImage($url,$filename="") {
			//php set_time_limit�����Ĺ��������õ�ǰҳ��ִ�ж೤ʱ�䲻����Ŷ��
			set_time_limit(24*60*60);
			if($url==""):return false;endif;
			
			if($filename=="") {
				//����ָ���ַ����ַ����е����һ�γ���
				$ext=strrchr($url,"."); 
				if($ext!=".gif" && $ext!=".png" &&$ext!=".jpg"):return false;endif;
				$filename=date("dMYHis").$ext;
			}
			
			//��������ƻ���
			ob_start();
			//���������
			readfile($url);
			//�������������������
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
		 * ��ȡ��վ��ַ
		 * @return the $content
		 */
		public function getContent() {
			return $this->content;
		}

		/**
		 * ��ȡ��վ��·�� 
		 * @return the $url
		 */
		public function getUrl() {
			return $this->url;
		}

		/**
		 * ����������վ������
		 * @param field_type $content
		 */
		public function setContent($content) {
			$this->content = $content;
		}

		/**
		 * ����������վ��·��
		 * @param field_type $url
		 */
		public function setUrl($url) {
			$this->url = $url;
		}
	 
		//����Bug��ҳ����ʾ
		function debug() {
			$tempstr = "<SCRIPT>function runEx(){var winEx2 = window.open(\"\", \"winEx2\", \"width=500,height=300,status=yes,menubar=no,scrollbars=yes,resizable=yes\"); winEx2.document.open(\"text/html\", \"replace\"); winEx2.document.write(unescape(event.srcElement.parentElement.children[0].value)); winEx2.document.close(); }function saveFile(){var win=window.open('','','top=10000,left=10000');win.document.write(document.all.asdf.innerText);win.document.execCommand('SaveAs','','javascript.htm');win.close();}</SCRIPT><center><TEXTAREA id=asdf name=textfield rows=32 wrap=VIRTUAL cols=\"120\">" . $this->content . "</TEXTAREA><BR><BR><INPUT name=Button onclick=runEx() type=button value=\"�鿴Ч��\"> <INPUT name=Button onclick=asdf.select() type=button value=\"ȫѡ\"> <INPUT name=Button onclick=\"asdf.value=''\" type=button value=\"���\"> <INPUT onclick=saveFile(); type=button value=\"�������\"></center>";
			echo $tempstr;
		}
}

/**������Ľű�����
	//$url = new Url();
	//$url->setUrl("http://blog.jobbole.com");
	//echo $url->gather();  //���������
	//$url->GrabImage("http://cdn2.jobbole.com/2011/11/Web-Coding.png","");
	//function sub_str($text,$length,$other_str=false)
	
	//echo $url->sub_str("�����һ���ӣ���Ҫת��asbcdde",8);
	//$url->setUrl("http://blog.jobbole.com");
	//$url->gather();  //���������
	//$url->noReturn();
	//echo $url->getContent();

	//$str = "(piece1) (piece2) (piece3) (piece4) (piece5) (piece6)";
	//$url->setContent($str);
	//echo $url->cut("(",")",2,"all");

	//$search  = array('A', 'B', 'C', 'E');
	//$replace = array('D');
	//$subject = 'A';

	//echo str_replace($search, $replace, $subject);

	//--------------------------------------------�����ǲ���ͨ�����ļ�
		/**
		*Curl:����libcurl������������ֵķ�����ʹ�ø������͵�Э��������Ӻ�ͨѶ
		*libcurlĿǰ֧��http��https��ftp��gopher��telnet��dict��file��ldapЭ��
		*
		*/
/*
		function getfile_curl($url) {//curl����ϸ˵����������ı�ע
			$curl = "/usr/local/bin/curl "; // path to your curl
			$curl_options = " -s --connect-timeout 10 --max-time 10 ";
			// curl �÷���ο� curl --help ���� man curl
			// curl �����ǳ�֮�ḻ,����ģ����������(agent) ��������referer
			$cmd = "$curl $curl_options $url ";
			@ exec($cmd, $o, $r);
			if ($r != 0) {
				return "��ʱ";
			} else {
				$o = join("", $o);
				return $o;
			}
		}
		
		//��ȡCurl
		function gather_curl($curl) {
			$http=$this->getfile_curl($this->url);
			return $this->content = $http;
		}
		//-------------------------------------------------------------//
		// �������ļ�
		function gather_local($toline = true) {
			if ($toline) {
				$http = file($this->url);
				return $this->content = $this->BytesToBstr($http);
			} else {
				$http = file($this->url);
				return $this->content = $http;
			}
		}

		//'���ռ��������еľ���URL��ַ��Ϊ������Ե�ַ
		function local() {
			
		}
*/

?>
