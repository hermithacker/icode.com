<?php /* Smarty version Smarty-3.1.16, created on 2014-03-13 13:42:20
         compiled from "C:\PhpDevelop\wamp\www\itask.com\admin\templates\UserManager\login.html" */ ?>
<?php /*%%SmartyHeaderCode:233005321453c2cf792-61221534%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '37680a6400b222787ee41c8bdcdb193160c1e85d' => 
    array (
      0 => 'C:\\PhpDevelop\\wamp\\www\\itask.com\\admin\\templates\\UserManager\\login.html',
      1 => 1394528898,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '233005321453c2cf792-61221534',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'title' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.16',
  'unifunc' => 'content_5321453c3b5816_68720193',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5321453c3b5816_68720193')) {function content_5321453c3b5816_68720193($_smarty_tpl) {?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</title>
  <link rel="stylesheet" type="text/css" href="../../css/style.css" />
</head>
<body>
   <div id="loginpanelwrap">
  	
	<div class="loginheader">
    <div class="logintitle"><a href="#">Panelo Admin</a></div>
    </div>

    <div class="loginform">
        <div class="loginform_row">
        <label>Username:</label>
        <input type="text" class="loginform_input" name="" />
        </div>
        <div class="loginform_row">
        <label>Password:</label>
        <input type="text" class="loginform_input" name="" />
        </div>
        <div class="loginform_row">
        <input type="submit" class="loginform_submit" value="Login" />
        </div> 
        <div class="clear"></div>
    </div>
</div>
</body>
</html><?php }} ?>
