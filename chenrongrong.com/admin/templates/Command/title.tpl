<!--
	页面的头部模板
    $title : 页面的头部显示名称
    
    
-->
<!DOCTYPE html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>{$title}</title>
  <link rel="stylesheet" type="text/css" href="../../css/style.css" />
   
   <!-- jQuery file -->
  <script src="../../jquery-ui-1.10.4.custom/js/jquery-1.10.2.js"></script>
  <script src="../../js/jquery.tabify.js" type="text/javascript" charset="utf-8"></script>
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