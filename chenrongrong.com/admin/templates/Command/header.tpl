{*
<!--
	功能说明：页面的木块导航菜单
    
    参数说明：需要传递的参数列表：
        $title : 页面名称
        $name:   登陆名
        $dataModules: 模块数组
-->
*}
<div class="header">
    <div class="title"><a href="#">{$title}</a></div>
    <div class="header_right">Welcome {$name}, <a href="#" class="settings">Settings</a> <a href="#" class="logout">Logout</a> </div>
    <div class="menu">
        <ul>
            <li><a href="#" class="selected">Main page</a></li>
            {foreach $dataModules as $var}
            <li><a href="#" >{$var}</a></li>
            {/foreach}
        </ul>
    </div>
</div>
{*
 <!--
 
 	功能说明：模块下面显示的子子菜单项
 
 	参数说明：传递的参数列表: 
    	$dataMenus  功能列表
        
 -->
 *}
<div class="submenu">
    <ul>
    	<li><a href="#" class="selected">settings</a></li>	
    	{foreach $dataMenus as $var}
         <li><a href="#" >{$var}</a></li>
    	{/foreach}
    </ul>
</div> 
