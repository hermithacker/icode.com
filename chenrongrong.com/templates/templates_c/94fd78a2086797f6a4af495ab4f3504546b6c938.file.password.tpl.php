<?php /* Smarty version Smarty-3.1.16, created on 2014-05-27 16:24:40
         compiled from "C:\PhpDevelop\wamp\www\chenrongrong.com\templates\password.tpl" */ ?>
<?php /*%%SmartyHeaderCode:3038753687535f15244-53034582%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '94fd78a2086797f6a4af495ab4f3504546b6c938' => 
    array (
      0 => 'C:\\PhpDevelop\\wamp\\www\\chenrongrong.com\\templates\\password.tpl',
      1 => 1400655165,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3038753687535f15244-53034582',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.16',
  'unifunc' => 'content_536875360a9bf9_38933296',
  'variables' => 
  array (
    'websiteUseTop5' => 0,
    'resault' => 0,
    'websiteAlexa' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_536875360a9bf9_38933296')) {function content_536875360a9bf9_38933296($_smarty_tpl) {?>
<?php  $_config = new Smarty_Internal_Config("../web.conf", $_smarty_tpl->smarty, $_smarty_tpl);$_config->loadConfigVars(null, 'local'); ?>
<!DOCTYPE html>
<head xmlns="http://www.w3.org/1999/html">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>iWeb</title>
    <meta name="author" content="><?php echo $_smarty_tpl->getConfigVariable('author');?>
</"/>
	<meta name="copyright" content="<?php echo $_smarty_tpl->getConfigVariable('copyright');?>
"/>
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
        
        <?php  $_smarty_tpl->tpl_vars['resault'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['resault']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['websiteUseTop5']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['resault']->key => $_smarty_tpl->tpl_vars['resault']->value) {
$_smarty_tpl->tpl_vars['resault']->_loop = true;
?>
    	<div class="webitem">
        	<a href='<?php echo $_smarty_tpl->tpl_vars['resault']->value["websites"];?>
' title='<?php echo $_smarty_tpl->tpl_vars['resault']->value["company"];?>
'>
            	<img src="../images/weblogo/<?php echo $_smarty_tpl->tpl_vars['resault']->value['company'];?>
.png" alt='<?php echo $_smarty_tpl->tpl_vars['resault']->value["company"];?>
' width="50" height="50" title='<?php echo $_smarty_tpl->tpl_vars['resault']->value["company"];?>
' />
            </a>
            <span><a href='<?php echo $_smarty_tpl->tpl_vars['resault']->value["websites"];?>
'><?php echo $_smarty_tpl->tpl_vars['resault']->value["webname"];?>
</a></span>
            <span><a href='<?php echo $_smarty_tpl->tpl_vars['websiteAlexa']->value;?>
?domain=<?php echo $_smarty_tpl->tpl_vars['resault']->value["websites"];?>
' title="Alexa排名">排名>></a></span>
            <?php if ($_smarty_tpl->tpl_vars['resault']->value["ismember"]==1) {?>
            <span>已注册账号</span>
            <?php } else { ?>
             <span style="color:red;">未注册账号</span>
            <?php }?>
        </div>
        <?php } ?>
    </div>
    
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
<?php }} ?>
