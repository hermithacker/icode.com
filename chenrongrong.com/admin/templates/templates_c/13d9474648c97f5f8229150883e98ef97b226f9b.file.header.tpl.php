<?php /* Smarty version Smarty-3.1.16, created on 2014-03-14 09:29:57
         compiled from "C:\PhpDevelop\wamp\www\\itask.com\admin\templates\Command\header.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2460853225b95356388-52513617%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '13d9474648c97f5f8229150883e98ef97b226f9b' => 
    array (
      0 => 'C:\\PhpDevelop\\wamp\\www\\\\itask.com\\admin\\templates\\Command\\header.tpl',
      1 => 1394720024,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2460853225b95356388-52513617',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'title' => 0,
    'name' => 0,
    'dataModules' => 0,
    'var' => 0,
    'dataMenus' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.16',
  'unifunc' => 'content_53225b953aace2_37342867',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53225b953aace2_37342867')) {function content_53225b953aace2_37342867($_smarty_tpl) {?>
<div class="header">
    <div class="title"><a href="#"><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</a></div>
    <div class="header_right">Welcome <?php echo $_smarty_tpl->tpl_vars['name']->value;?>
, <a href="#" class="settings">Settings</a> <a href="#" class="logout">Logout</a> </div>
    <div class="menu">
        <ul>
            <li><a href="#" class="selected">Main page</a></li>
            <?php  $_smarty_tpl->tpl_vars['var'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['var']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['dataModules']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['var']->key => $_smarty_tpl->tpl_vars['var']->value) {
$_smarty_tpl->tpl_vars['var']->_loop = true;
?>
            <li><a href="#" ><?php echo $_smarty_tpl->tpl_vars['var']->value;?>
</a></li>
            <?php } ?>
        </ul>
    </div>
</div>

<div class="submenu">
    <ul>
    	<li><a href="#" class="selected">settings</a></li>	
    	<?php  $_smarty_tpl->tpl_vars['var'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['var']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['dataMenus']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['var']->key => $_smarty_tpl->tpl_vars['var']->value) {
$_smarty_tpl->tpl_vars['var']->_loop = true;
?>
         <li><a href="#" ><?php echo $_smarty_tpl->tpl_vars['var']->value;?>
</a></li>
    	<?php } ?>
    </ul>
</div> 
<?php }} ?>
