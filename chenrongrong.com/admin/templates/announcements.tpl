{*
	网站页面实现的基本模板文件
*}
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>{$title}</title>
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
		{include file="Command/header.tpl"}
        <div class="center_content">
      		<div id="right_wrap">
            	{include file="Command/content.tpl"}
            </div>
            <!--end of right content-->
            <div class="sidebar" id="sidebar">
            	{include file="Command/sidebar.tpl"}
            </div>
            <div class="clear"></div>
        </div>
        {include file="Command/footer.tpl"}
    </div>
</body>
</html>
        