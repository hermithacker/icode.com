// JavaScript Document
/*
	d:请求的数组数据
*/
function autopage(d){
	var pagecount = d.length;
	var currentpage = 1 ;   //当前页
	var pageHtml = [];
	pageHtml.push('<div id="pg" class="pages">');
	pageHtml.push('<span class="count">');
	pageHtml.push('	Pages: '+currentpage+' / '+pagecount+'');
	pageHtml.push('</span>');
	pageHtml.push('<span class="number">');
	if(currentpage<=1){
		pageHtml.push('<span title="首页">«</span>');
		pageHtml.push('<span title="上一页">‹</span>');
	}else{
		pageHtml.push('<span title="首页"><a href="javascript:toPage(1);">«</a></span>');
		pageHtml.push('<span title="上一页"><a href="javascript:toPage('+parseInt(currentpage-1)+');">‹</a></span>');
	}
	for(var i=1;i<=pagecount;i++){
		pageHtml.push('<span title="第'+i+'页"><a href="javascript:toPage('+i+');">['+i+']</a></span>');
	}
	if(currentpage>=pagecount){
		pageHtml.push('<span title="下一页">›</span>');	
		pageHtml.push('<span title="最后一页">»</span>');
	}else{
		pageHtml.push('<span title="下一页"><a href="javascript:toPage('+parseInt(currentpage+1)+');">›</a></span>');
		pageHtml.push('<span title="尾页"><a href="javascript:toPage('+pagecount+');">»</a></span>');
	}
	pageHtml.push('</span></div>');
}


//改进版本
function autopage(){
	var pagecount = 42;		//总页数
	var showcount = 20;		//显示页数
	var startpage = 1;		//起始页
	var currentpage = 31;   //当前页
	var pageHtml = [];
	pageHtml.push('<div id="pg" class="pages">');
	pageHtml.push('<span class="count">');
	pageHtml.push('	Pages: '+currentpage+' / '+pagecount+'');
	pageHtml.push('</span>');
	pageHtml.push('<span class="number">');
	if(currentpage<=1){
		pageHtml.push('<span title="首页">«</span>');
		pageHtml.push('<span title="上一页">‹</span>');
	}else{
		pageHtml.push('<span title="首页"><a href="javascript:toPage(1);">«</a></span>');
		pageHtml.push('<span title="上一页"><a href="javascript:toPage('+parseInt(currentpage-1)+');">‹</a></span>');
	}
	if(pagecount>showcount){
		//循环到最后
		var endPage = startpage+showcount;
		if((endPage-currentpage)<=5){
			startpage = parseInt(currentpage/10)*10+1;
			endPage = startpage+showcount;
		}
		if(pagecount<endPage){
			endPage = pagecount;
			for(var i=startpage;i<=endPage;i++){
				pageHtml.push('<span title="第'+i+'页"><a href="javascript:toPage('+i+');">['+i+']</a></span>');
			}
		}
		else{
			for(var i=startpage;i<=endPage;i++){
				pageHtml.push('<span title="第'+i+'页"><a href="javascript:toPage('+i+');">['+i+']</a></span>');
			}
			pageHtml.push('<span>...</span>');
		}
	}else{
		for(var i=startpage;i<pagecount;i++){
			pageHtml.push('<span title="第'+i+'页"><a href="javascript:toPage('+i+');">['+i+']</a></span>');
		}
	}
	if(currentpage>=pagecount){
		pageHtml.push('<span title="下一页">›</span>');	
		pageHtml.push('<span title="最后一页">»</span>');
	}else{
		pageHtml.push('<span title="下一页"><a href="javascript:toPage('+parseInt(currentpage+1)+');">›</a></span>');
		pageHtml.push('<span title="尾页"><a href="javascript:toPage('+pagecount+');">»</a></span>');
	}
	pageHtml.push('</span></div>');
	document.getElementById('page').innerHTML = pageHtml.join("");
}