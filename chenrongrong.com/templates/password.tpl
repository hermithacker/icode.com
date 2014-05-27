{%*
	网页的头部
*%}
{%config_load file="../web.conf"%}
<!DOCTYPE html>
<head xmlns="http://www.w3.org/1999/html">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>iWeb</title>
    <meta name="author" content=">{%#author#%}</"/>
	<meta name="copyright" content="{%#copyright#%}"/>
	<meta name="keywords" content="自己的信息站点 展现平凡的自己">
	<meta name="title" content="自己站点"/>
    <meta name="description" content="展现平凡的自己"/>
	<link href="../css/base_.css" rel="stylesheet" type="text/css" />
	<link href="../css/password.css" rel="stylesheet" type="text/css" />
    
	<script type="text/javascript" src="../js/jquery-1.10.2.js"></script>
	<script type="text/javascript" src="../js/jquery.md5.js"></script>
	<script type="text/javascript" src="../js/ZeroClipboard.js"></script>
</head>
<body>
<div class="content">
<form name="fsubname" action="../controller/password.php">
  <div class="leftarea">
    <h2><span>1</span>输入</h2>
    <div id="inputtxt">
        <label for="password">记忆密码</label>
        <input id="password" type="password" name="password" value="" tabindex="1" placeholder="输入记忆密码" />
        <span>+</span>	
        <label for="key">区分代号</label> 
        <input id="key" type="text" name="key" value="" tabindex="2" placeholder="输入记忆代码" />
    </div>
    <h2><span>2</span>输出</h2>
    <div id="outputtxt">
      <p>最终密码<span id="code" class="code">&nbsp;</span>
        <span id="copycode" class="copycode">点击复制</span>
        <span id="copyok" class="copyok">√复制成功</span>
      </p>
    </div>
    {%*规则说明*%}
    <ul id="tab" class="nav nav-tabs">
    	<li class="active"><a href="#info">规则说明</a></li>
    </ul>
    <div class="clearfix"></div>
    <div id="info">
    	<div><span>1</span>密码口令的位数应在8～12位</div>
        <div><span>2</span>应使用字母和数字结合的方式</div>
        <div><span>3</span>避免使用有规律的字母或数字组合</div>
        <div><span>4</span>避免使用开头或结尾的字母数字</div>
        <div><span>5</span>是否有必要经常性的更换密码口令</div>
    </div>
  </div>
  <div class="rightarea">
    <h5><span>网站账号验证</span></h5>
    <div class="webname">
    	<div class="search">
        	<input id="search" type="text" name="search" value="" tabindex="4" placeholder="输入网站信息" />
            <span><a href="#1">搜索</a></span>
        </div>
        {%*显示5个常用网站信息*%}
        {%foreach $websiteUseTop5 as $resault%}
    	<div class="webitem">
        	<a href='{%$resault["websites"]%}' title='{%$resault["company"]%}'>
            	<img src="../images/weblogo/{%$resault.company%}.png" alt='{%$resault["company"]%}' width="50" height="50" title='{%$resault["company"]%}' />
            </a>
            <span><a href='{%$resault["websites"]%}'>{%$resault["webname"]%}</a></span>
            <span><a href='{%$websiteAlexa%}?domain={%$resault["websites"]%}' title="Alexa排名">排名>></a></span>
            {%if $resault["ismember"]==1 %}
            <span>已注册账号</span>
            {%else%}
             <span style="color:red;">未注册账号</span>
            {%/if%}
        </div>
        {%/foreach%}
    </div>
    {%*常用链接网站*%}
    <h5><span>常用链接</span></h5>
    <div class="linking">
    	<div class="row"><a href=""><span class="rank">1</span>百度<span class="tags">标签1,标签2</span></a></div>
    </div>
  </div>
  <div class="clearfix"></div>
</form>
</div>
</body>
<script>
function countCode(){
	var password = $("#password").val();
	var key = $("#key").val();
	if(password && key){
		var md5one = $.md5(password,key);
		var md5two = $.md5(md5one,'rong');
		var md5three = $.md5(md5one,'chen');
		//计算大小写
		var rule = md5three.split("");
		var source = md5two.split("");
		for(var i=0;i<=31;i++){ 
			if(isNaN(source[i])){
				str ="wuyichangjianiyaozuoshenme";
				if(str.search(rule[i]) > -1){
					source[i] = source[i].toUpperCase();
				}
			}
		}
		var code32 = source.join("");
		var code1 = code32.slice(0,1);
		if(isNaN(code1)){
			var code16 = code32.slice(0,16);
		}else{
			var code16 = "K" + code32.slice(1,16);
		}
		$("#code").text(code16);
	}
}
var clip = new ZeroClipboard.Client(); // 新建一个对象
ZeroClipboard.setMoviePath( '../js/ZeroClipboard.swf' );
clip.setHandCursor(true); // 设置鼠标为手型
clip.setText("chenrongrong.com"); // 设置要复制的文本。
clip.glue("copycode"); // 和上一句位置不可调换

$(function(){ //页面载入后执行的内容
	//监视内容改变
	$('#password').change(countCode);
	$('#key').change(countCode);
	$('#password').keyup(countCode);
	$('#key').keyup(countCode);
	//调整复制按钮位置
	$(window).resize(function(){clip.reposition();});
	//复制按钮的事件
	clip.addEventListener( "mouseOver", function(client){
		client.setText($("#code").text()); 
		//重新设置要复制的值
		$('#copycode').addClass("copycodeh");
		$('#code').addClass("codeh");
	});
	clip.addEventListener( "mouseOut", function(client){
		$('#copycode').removeClass("copycodeh");
		$('#code').removeClass("codeh");
	});
	clip.addEventListener( "complete", function(){
		$('#copyok').fadeTo(0,0).css("border-color","rgb(53,249,8)").css("background-color","rgb(53,249,8)").fadeTo('normal',1).fadeTo(2000,1).fadeTo(3000,0);
	});
});

</script>
