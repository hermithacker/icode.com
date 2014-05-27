{%*
	网页的头部
*%}
{%config_load file="../web.conf"%}
<!DOCTYPE html><head xmlns="http://www.w3.org/1999/html">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>iWeb</title>
	<link href="../css/reset.css" rel="stylesheet" type="text/css" />
	<link href="../css/base.css" rel="stylesheet" type="text/css" />
    {%*布局样式*%}
    
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
            	 {%*圆形按钮*%}
                 {%include file='widget/daysnews.tpl'%}
                 {%*圆形按钮*%}
                 {%include file='widget/roundbuttons.tpl'%}
            </div>
            <div class="tools sr">
            	{%*时钟*%}
                {%include file='widget/clock.tpl'%}
                {%*每日任务*%}
                {%include file='widget/daystask.tpl'%}
            	{%*友情链接*%}
                {%include file='widget/friendlink.tpl'%}
            </div>
        </div>
        <div class="footer"></div>
    </div>
</body>