<?php /* Smarty version Smarty-3.1.16, created on 2014-05-27 15:53:32
         compiled from "C:\PhpDevelop\wamp\www\chenrongrong.com\templates\main.tpl" */ ?>
<?php /*%%SmartyHeaderCode:192025382dd7534e191-69458578%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3d5f12288a02b8351b811882d187c0962fc7b385' => 
    array (
      0 => 'C:\\PhpDevelop\\wamp\\www\\chenrongrong.com\\templates\\main.tpl',
      1 => 1401177211,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '192025382dd7534e191-69458578',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.16',
  'unifunc' => 'content_5382dd756a1b69_63749840',
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5382dd756a1b69_63749840')) {function content_5382dd756a1b69_63749840($_smarty_tpl) {?>
<?php  $_config = new Smarty_Internal_Config("../web.conf", $_smarty_tpl->smarty, $_smarty_tpl);$_config->loadConfigVars(null, 'local'); ?>
<!DOCTYPE html><head xmlns="http://www.w3.org/1999/html">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>iWeb</title>
	<link href="../css/reset.css" rel="stylesheet" type="text/css" />
	<link href="../css/base.css" rel="stylesheet" type="text/css" />
    
    
	<link href="../css/layout/main.css" rel="stylesheet" type="text/css" />
   
	<script type="text/javascript" src="../js/base.js"></script>
	<script>
		init();
		checkLoadFile('../css/plugin/weblinks.css','css');
		checkLoadFile('../css/plugin/clock.css','css');
		checkLoadFile('../css/plugin/daystask.css','css');
		checkLoadFile('../js/plugin/clock.js','js');
		checkLoadFile('../css/plugin/roundbuttons.css','css');
		checkLoadFile('../css/plugin/daysnews.css','css');
	</script>
    
</head>
<body>
	<div class="content">
    	<div class="header"></div>
        <div class="middle">
        	<div class="container sl">
            	 
                 <?php echo $_smarty_tpl->getSubTemplate ('widget/daysnews.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

                 
                 <?php echo $_smarty_tpl->getSubTemplate ('widget/roundbuttons.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

            </div>
            <div class="tools sr">
            	
                <?php echo $_smarty_tpl->getSubTemplate ('widget/clock.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

                
                <?php echo $_smarty_tpl->getSubTemplate ('widget/daystask.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

            	
                <?php echo $_smarty_tpl->getSubTemplate ('widget/friendlink.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

            </div>
        </div>
        <div class="footer"></div>
    </div>
</body><?php }} ?>
