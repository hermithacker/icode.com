<?php /* Smarty version Smarty-3.1.16, created on 2014-03-13 23:08:38
         compiled from "D:\PHP Develop\Apache2.4\htdocs\itask.com\admin\templates\Command\content.tpl" */ ?>
<?php /*%%SmartyHeaderCode:147715321c33da43e27-58847332%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '25d0946f8ffcce97bff07913cb3f9a20660b8ded' => 
    array (
      0 => 'D:\\PHP Develop\\Apache2.4\\htdocs\\itask.com\\admin\\templates\\Command\\content.tpl',
      1 => 1394723313,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '147715321c33da43e27-58847332',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.16',
  'unifunc' => 'content_5321c33da95eb4_26258694',
  'variables' => 
  array (
    'tablename' => 0,
    'tableHeaderName' => 0,
    'var' => 0,
    'tableDataCount' => 0,
    'tableData' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5321c33da95eb4_26258694')) {function content_5321c33da95eb4_26258694($_smarty_tpl) {?>

<div id="right_content">             
      <h2><?php echo $_smarty_tpl->tpl_vars['tablename']->value;?>
</h2>
      <table id="rounded-corner" width="300">
        <thead>
            <tr>
                 <?php  $_smarty_tpl->tpl_vars['var'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['var']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['tableHeaderName']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['var']->key => $_smarty_tpl->tpl_vars['var']->value) {
$_smarty_tpl->tpl_vars['var']->_loop = true;
?>
                  <th><?php echo $_smarty_tpl->tpl_vars['var']->value;?>
</th>
                 <?php } ?>
                 <th>Edit</th>
                 <th>Delete</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td colspan="12">合计数据量：<?php echo $_smarty_tpl->tpl_vars['tableDataCount']->value;?>
</td>
            </tr>
        </tfoot>
        <tbody>
            <?php if (isset($_smarty_tpl->tpl_vars['smarty']->value['section']['tabletemp'])) unset($_smarty_tpl->tpl_vars['smarty']->value['section']['tabletemp']);
$_smarty_tpl->tpl_vars['smarty']->value['section']['tabletemp']['name'] = 'tabletemp';
$_smarty_tpl->tpl_vars['smarty']->value['section']['tabletemp']['loop'] = is_array($_loop=$_smarty_tpl->tpl_vars['tableData']->value) ? count($_loop) : max(0, (int) $_loop); unset($_loop);
$_smarty_tpl->tpl_vars['smarty']->value['section']['tabletemp']['show'] = true;
$_smarty_tpl->tpl_vars['smarty']->value['section']['tabletemp']['max'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['tabletemp']['loop'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['tabletemp']['step'] = 1;
$_smarty_tpl->tpl_vars['smarty']->value['section']['tabletemp']['start'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['tabletemp']['step'] > 0 ? 0 : $_smarty_tpl->tpl_vars['smarty']->value['section']['tabletemp']['loop']-1;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['tabletemp']['show']) {
    $_smarty_tpl->tpl_vars['smarty']->value['section']['tabletemp']['total'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['tabletemp']['loop'];
    if ($_smarty_tpl->tpl_vars['smarty']->value['section']['tabletemp']['total'] == 0)
        $_smarty_tpl->tpl_vars['smarty']->value['section']['tabletemp']['show'] = false;
} else
    $_smarty_tpl->tpl_vars['smarty']->value['section']['tabletemp']['total'] = 0;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['tabletemp']['show']):

            for ($_smarty_tpl->tpl_vars['smarty']->value['section']['tabletemp']['index'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['tabletemp']['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']['tabletemp']['iteration'] = 1;
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['tabletemp']['iteration'] <= $_smarty_tpl->tpl_vars['smarty']->value['section']['tabletemp']['total'];
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['tabletemp']['index'] += $_smarty_tpl->tpl_vars['smarty']->value['section']['tabletemp']['step'], $_smarty_tpl->tpl_vars['smarty']->value['section']['tabletemp']['iteration']++):
$_smarty_tpl->tpl_vars['smarty']->value['section']['tabletemp']['rownum'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['tabletemp']['iteration'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['tabletemp']['index_prev'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['tabletemp']['index'] - $_smarty_tpl->tpl_vars['smarty']->value['section']['tabletemp']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['tabletemp']['index_next'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['tabletemp']['index'] + $_smarty_tpl->tpl_vars['smarty']->value['section']['tabletemp']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['tabletemp']['first']      = ($_smarty_tpl->tpl_vars['smarty']->value['section']['tabletemp']['iteration'] == 1);
$_smarty_tpl->tpl_vars['smarty']->value['section']['tabletemp']['last']       = ($_smarty_tpl->tpl_vars['smarty']->value['section']['tabletemp']['iteration'] == $_smarty_tpl->tpl_vars['smarty']->value['section']['tabletemp']['total']);
?>
                <?php if (((1 & $_smarty_tpl->getVariable('smarty')->value['section']['tabletemp']['index']))) {?>
                    <tr class="odd">
                        
                        <td><?php echo $_smarty_tpl->tpl_vars['tableData']->value[$_smarty_tpl->getVariable('smarty')->value['section']['tabletemp']['index']]['announceId'];?>
</td>
                        <td><?php echo $_smarty_tpl->tpl_vars['tableData']->value[$_smarty_tpl->getVariable('smarty')->value['section']['tabletemp']['index']]['showcode'];?>
</td>
                        <td><?php echo $_smarty_tpl->tpl_vars['tableData']->value[$_smarty_tpl->getVariable('smarty')->value['section']['tabletemp']['index']]['announceTitle'];?>
</td>
                        <td><?php echo $_smarty_tpl->tpl_vars['tableData']->value[$_smarty_tpl->getVariable('smarty')->value['section']['tabletemp']['index']]['announceContent'];?>
</td>
                        <td><?php echo $_smarty_tpl->tpl_vars['tableData']->value[$_smarty_tpl->getVariable('smarty')->value['section']['tabletemp']['index']]['publishTime'];?>
</td>
                        <td><?php echo $_smarty_tpl->tpl_vars['tableData']->value[$_smarty_tpl->getVariable('smarty')->value['section']['tabletemp']['index']]['Publisher'];?>
</td>
                        <td><?php echo $_smarty_tpl->tpl_vars['tableData']->value[$_smarty_tpl->getVariable('smarty')->value['section']['tabletemp']['index']]['announceIntro'];?>
</td>
                        <td><?php echo $_smarty_tpl->tpl_vars['tableData']->value[$_smarty_tpl->getVariable('smarty')->value['section']['tabletemp']['index']]['type'];?>
</td> 
                        <td><?php echo $_smarty_tpl->tpl_vars['tableData']->value[$_smarty_tpl->getVariable('smarty')->value['section']['tabletemp']['index']]['filename'];?>
</td>
                        <td><a href="#"><img src="../../images/edit.png" alt="" title="" border="0" /></a></td>
                        <td><a href="#"><img src="../../images/trash.gif" alt="" title="" border="0" /></a></td>
                    </tr>
                    
                <?php }?>
            <?php endfor; endif; ?>
        </tbody>
     </table>
     <div class="form_sub_buttons">
        <a href="#" class="button green">选中编辑</a>
        <a href="#" class="button red">选中删除</a>
     </div>
     <ul id="tabsmenu" class="tabsmenu">
        <li class="active"><a href="#tab1">Form Design Structure</a></li>
        <li><a href="#tab2">Tab two</a></li>
        <li><a href="#tab3">Tab three</a></li>
        <li><a href="#tab4">Tab four</a></li>
    </ul>
    <div id="tab1" class="tabcontent">
        <h3>Tab one title</h3>
        <div class="form">
            <div class="form_row">
            <label>Name:</label>
            <input type="text" class="form_input" name="" />
            </div>
             
            <div class="form_row">
            <label>Email:</label>
            <input type="text" class="form_input" name="" />
            </div>
            
            <div class="form_row">
            <label>Subject:</label>
            <select class="form_select" name="">
            <option>Select one</option>
            </select>
            </div>
            
             <div class="form_row">
            <label>Message:</label>
            <textarea class="form_textarea" name=""></textarea>
            </div>
            <div class="form_row">
            <input type="submit" class="form_submit" value="Submit" />
            </div> 
            <div class="clear"></div>
        </div>
    </div>
    
     <div id="tab2" class="tabcontent">
        <h3>Tab two title</h3>
        <ul class="lists">
            <li>Consectetur adipisicing elit  error sit voluptatem accusantium doloremqu sed</li>
            <li>Sed do eiusmod tempor incididunt</li>
            <li>Ut enim ad minim veniam is iste natus error sit</li>
            <li>Consectetur adipisicing elit sed</li>
            <li>Sed do eiusmod tempor  error sit voluptatem accus antium dolor emqu incididunt</li>
            <li>Ut enim ad minim veniam</li>
            <li>Consectetur adipisi  error sit voluptatem accusantium doloremqu cing elit sed</li>
            <li>Sed do eiusmod tempor in is iste natus error sit cididunt</li>
            <li>Ut enim ad minim ve is iste natus error sitniam</li>
        </ul>
    </div>
    
     <div id="tab3" class="tabcontent">
        <h3>Tab three title</h3>
        <p>Lorem ipsum <a href="#">dolor sit amet</a>, consectetur adipisicing elit, sed do eiusmod tempor incididunt 
            ut labore et dolore magna
        </p>
    </div> 
    
     <div id="tab4" class="tabcontent">
        <h3>Tab four title</h3>
        <p>
            Nemo 
        </p>
    </div> 
    
     <div class="toogle_wrap">
        <div class="trigger"><a href="#">Toggle with text</a></div>
        <div class="toggle_container">
            <p>
                Lorem ipsum <a href="#">dolor sit amet</a>, con
            </p>
        </div>
    </div>
</div><?php }} ?>
