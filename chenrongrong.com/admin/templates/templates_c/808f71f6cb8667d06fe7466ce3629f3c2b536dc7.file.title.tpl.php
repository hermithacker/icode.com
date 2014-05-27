<?php /* Smarty version Smarty-3.1.16, created on 2014-03-13 22:06:01
         compiled from "D:\PHP Develop\Apache2.4\htdocs\itask.com\admin\templates\Command\title.tpl" */ ?>
<?php /*%%SmartyHeaderCode:262865321bb49eb2e64-06953648%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '808f71f6cb8667d06fe7466ce3629f3c2b536dc7' => 
    array (
      0 => 'D:\\PHP Develop\\Apache2.4\\htdocs\\itask.com\\admin\\templates\\Command\\title.tpl',
      1 => 1394715077,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '262865321bb49eb2e64-06953648',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'title' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.16',
  'unifunc' => 'content_5321bb49eb6ce9_08337485',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5321bb49eb6ce9_08337485')) {function content_5321bb49eb6ce9_08337485($_smarty_tpl) {?><!--
	页面的头部模板
    $title : 页面的头部显示名称
    
    
-->
<!DOCTYPE html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</title>
  <link rel="stylesheet" type="text/css" href="../../css/style.css" />
   
   <!-- jQuery file -->
  <script src="../../jquery-ui-1.10.4.custom/js/jquery-1.10.2.js"></script>
  <script src="../../js/jquery.tabify.js" type="text/javascript" charset="utf-8"></script>
  <script type="text/javascript">
  var $ = jQuery.noConflict();
  $(function() {
	  $('#tabsmenu').tabify();
	  $(".toggle_container").hide(); 
	  $(".trigger").click(function(){
		$(this).toggleClass("active").next().slideToggle("slow");
			return false;
	  });
  });
  </script>
</head><?php }} ?>
