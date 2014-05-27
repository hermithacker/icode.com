<?php /* Smarty version Smarty-3.1.16, created on 2014-04-08 15:21:38
         compiled from "C:\PhpDevelop\wamp\www\\iteamblog.com\admin\templates\task.tpl" */ ?>
<?php /*%%SmartyHeaderCode:29005343a34165ad47-41627846%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5fcae979fc377fbe0b09722e4432f5cb46f5782f' => 
    array (
      0 => 'C:\\PhpDevelop\\wamp\\www\\\\iteamblog.com\\admin\\templates\\task.tpl',
      1 => 1396941695,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '29005343a34165ad47-41627846',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.16',
  'unifunc' => 'content_5343a3417f9783_93828674',
  'variables' => 
  array (
    'title' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5343a3417f9783_93828674')) {function content_5343a3417f9783_93828674($_smarty_tpl) {?>
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
	 	
  });
  </script>
</head>
<body>
	<div id="panelwrap">
		<?php echo $_smarty_tpl->getSubTemplate ("Command/taskheader.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

    </div>
</body>
</html>
        <?php }} ?>
