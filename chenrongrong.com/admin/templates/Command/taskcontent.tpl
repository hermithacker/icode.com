{*
<!--
	功能说明:
    
	$tablename  : 显示在表头的中文名称
    
    $tableHeaderName =['','Product','Details','Price','Date','Category','User','Edit','Delete'] 表头
    
    $tableDataCount : 查询出来的数据总数
    
    $tableData ={
    			 {id=>'1',name=>'admin',value=>'1111',date=>'201/3/14'},
    			 {id=>'2',name=>'user',value=>'2222',date=>'201/3/14'},
                 {id=>'3',name=>'pass',value=>'3333',date=>'201/3/14'}};	 查询出来的数据集合{显示1,8行}
-->
*}

<div id="right_content">             
      <h2>{$tablename}</h2>
      <div style="width:758px; overflow-x:scroll;">
      <table id="rounded-corner" width="300">
        <thead>
            <tr>
                 {foreach $tableHeaderName as $var}
                  <th>{$var}</th>
                 {/foreach}
                 <th>Edit</th>
                 <th>Delete</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td colspan="12">合计数据量：{$tableDataCount}</td>
            </tr>
        </tfoot>
        <tbody>
            {section name=tabletemp loop=$tableData}
                {if ($smarty.section.tabletemp.index is even)}
                    <tr class="odd">
                        <td><input type="checkbox" name="{$tableData[tabletemp].announceId}" /></td>
                        {*<td>{$tableData[tabletemp].announceId}</td>*}
                        <td>{$tableData[tabletemp].showcode}</td>
                        <td>{$tableData[tabletemp].announceTitle}</td>
                        <td>{$tableData[tabletemp].announceContent}</td>
                        <td>{$tableData[tabletemp].publishTime}</td>
                        <td>{$tableData[tabletemp].Publisher}</td>
                        <td>{$tableData[tabletemp].announceIntro}</td>
                        <td>{$tableData[tabletemp].type}</td> 
                        <td>{$tableData[tabletemp].filename}</td>
                        <td><a href="#"><img src="../images/edit.png" alt="" title="" border="0" /></a></td>
                        <td><a href="#"><img src="../images/trash.gif" alt="" title="" border="0" /></a></td>
                    </tr>
                 {/if}
                 {if ($smarty.section.tabletemp.index is not even)}
                     <tr class="even">
                        <td><input type="checkbox" name="{$tableData[tabletemp].announceId}" /></td>
                        {*<td>{$tableData[tabletemp].announceId}</td>*}
                        <td>{$tableData[tabletemp].showcode}</td>
                        <td>{$tableData[tabletemp].announceTitle}</td>
                        <td>{$tableData[tabletemp].announceContent}</td>
                        <td>{$tableData[tabletemp].publishTime}</td>
                        <td>{$tableData[tabletemp].Publisher}</td>
                        <td>{$tableData[tabletemp].announceIntro}</td>
                        <td>{$tableData[tabletemp].type}</td> 
                        <td>{$tableData[tabletemp].filename}</td>
                        <td><a href="#"><img src="../images/edit.png" alt="" title="" border="0" /></a></td>
                        <td><a href="#"><img src="../images/trash.gif" alt="" title="" border="0" /></a></td>
                    </tr>
                {/if}
            {/section}
        </tbody>
     </table>
     </div>
     <div class="form_sub_buttons">
        <a href="#" class="button green">选中编辑</a>
        <a href="#" class="button red">选中删除</a>
     </div>
     <ul id="tabsmenu" class="tabsmenu">
        <li class="active"><a href="#tab1">Form Design Structure</a></li>
        <li><a href="#tab2">添加说明</a></li>
        <li><a href="#tab3">更新记录</a></li>
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
</div>