<?php /* Smarty version Smarty-3.1.16, created on 2014-03-13 22:36:41
         compiled from "D:\PHP Develop\Apache2.4\htdocs\itask.com\admin\templates\announcements.tpl" */ ?>
<?php /*%%SmartyHeaderCode:239835321bb10ae1436-60949529%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7a15315de17a0bc63f7df29c8bbb3ce74bdead00' => 
    array (
      0 => 'D:\\PHP Develop\\Apache2.4\\htdocs\\itask.com\\admin\\templates\\announcements.tpl',
      1 => 1394721384,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '239835321bb10ae1436-60949529',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.16',
  'unifunc' => 'content_5321bb10afc9c7_69880559',
  'variables' => 
  array (
    'title' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5321bb10afc9c7_69880559')) {function content_5321bb10afc9c7_69880559($_smarty_tpl) {?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</title>
  <link rel="stylesheet" type="text/css" href="../css/style.css" />
   
   <!-- jQuery file -->
  <script src="../jquery-ui-1.10.4.custom/js/jquery-1.10.2.js"></script>
  <script src="../js/jquery.tabify.js" type="text/javascript" charset="utf-8"></script>
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
</head>
<body>
	<div id="panelwrap">
		<?php echo $_smarty_tpl->getSubTemplate ("Command/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

        <div class="center_content">
      		<div id="right_wrap">
            	<?php echo $_smarty_tpl->getSubTemplate ("Command/content.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

            </div>
            <!--end of right content-->
            <div class="sidebar" id="sidebar">
            </div>
            <div class="clear"></div>
        </div>
    </div>
</body>
</html>
        <?php }} ?>
