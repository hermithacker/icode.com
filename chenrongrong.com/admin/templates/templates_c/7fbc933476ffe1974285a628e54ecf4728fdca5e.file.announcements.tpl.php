<?php /* Smarty version Smarty-3.1.16, created on 2014-03-14 09:29:57
         compiled from "C:\PhpDevelop\wamp\www\\itask.com\admin\templates\announcements.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2947553225b950fd899-29881867%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7fbc933476ffe1974285a628e54ecf4728fdca5e' => 
    array (
      0 => 'C:\\PhpDevelop\\wamp\\www\\\\itask.com\\admin\\templates\\announcements.tpl',
      1 => 1394760311,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2947553225b950fd899-29881867',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'title' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.16',
  'unifunc' => 'content_53225b952bb265_49609243',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53225b952bb265_49609243')) {function content_53225b952bb265_49609243($_smarty_tpl) {?>
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
            	<?php echo $_smarty_tpl->getSubTemplate ("Command/sidebar.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

            </div>
            <div class="clear"></div>
        </div>
        <?php echo $_smarty_tpl->getSubTemplate ("Command/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

    </div>
</body>
</html>
        <?php }} ?>
