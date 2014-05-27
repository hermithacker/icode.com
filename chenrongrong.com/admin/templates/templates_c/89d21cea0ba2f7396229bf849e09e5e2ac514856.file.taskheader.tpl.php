<?php /* Smarty version Smarty-3.1.16, created on 2014-04-08 15:25:31
         compiled from "C:\PhpDevelop\wamp\www\\iteamblog.com\admin\templates\Command\taskheader.tpl" */ ?>
<?php /*%%SmartyHeaderCode:8165343a382188b75-67497300%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '89d21cea0ba2f7396229bf849e09e5e2ac514856' => 
    array (
      0 => 'C:\\PhpDevelop\\wamp\\www\\\\iteamblog.com\\admin\\templates\\Command\\taskheader.tpl',
      1 => 1396941929,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '8165343a382188b75-67497300',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.16',
  'unifunc' => 'content_5343a3821b80e9_21226054',
  'variables' => 
  array (
    'title' => 0,
    'name' => 0,
    'fathermenu' => 0,
    'var' => 0,
    'childmenu' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5343a3821b80e9_21226054')) {function content_5343a3821b80e9_21226054($_smarty_tpl) {?>
<div class="header">
    <div class="title"><a href="#"><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</a></div>
    <div class="header_right">欢迎 <?php echo $_smarty_tpl->tpl_vars['name']->value;?>
, <a href="#" class="settings">设置</a> <a href="#" class="logout">退出</a> </div>
    <div class="menu">
        <ul>
            <li><a href="#" class="selected">首页</a></li>
            <?php  $_smarty_tpl->tpl_vars['var'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['var']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['fathermenu']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['var']->key => $_smarty_tpl->tpl_vars['var']->value) {
$_smarty_tpl->tpl_vars['var']->_loop = true;
?>
            <li><a href="#" ><?php echo $_smarty_tpl->tpl_vars['var']->value["mtext"];?>
</a></li>
            <?php } ?>
        </ul>
    </div>
</div>

<div class="submenu">
    <ul>
    	<li><a href="#" class="selected">设置</a></li>	
    	<?php  $_smarty_tpl->tpl_vars['var'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['var']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['childmenu']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['var']->key => $_smarty_tpl->tpl_vars['var']->value) {
$_smarty_tpl->tpl_vars['var']->_loop = true;
?>
         <li><a href="#" ><?php echo $_smarty_tpl->tpl_vars['var']->value["mtext"];?>
</a></li>
    	<?php } ?>
    </ul>
</div> 
<?php }} ?>
