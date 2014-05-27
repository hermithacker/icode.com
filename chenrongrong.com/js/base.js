// 框架中的JS文件
var fileadded = "";

function init(){
	checkLoadFile('../js/jquery-1.10.2.js','js');
	//设置页面居中内容的宽度
	//document.getElementsByClassName("content").item(0).style.width = setPageWidth();
	//将页面的访问源记录到数据库中
	getRefer();
}

//加载CSS和JS文件
function loadJsAndCssFile(filename,type){
	if(type=="js"){
		var fileref = document.createElement('script');
		fileref.setAttribute("type","text/javascript");
		fileref.setAttribute("src",filename);
	}
	else if (type=="css"){ //判断文件类型 
	  var fileref=document.createElement("link");
	  fileref.setAttribute("rel", "stylesheet");
	  fileref.setAttribute("type", "text/css");
	  fileref.setAttribute("href", filename);
	}
	if(typeof fileref!="undefined"){
		//判断是否存在重复的文件名
	   document.getElementsByTagName("head")[0].appendChild(fileref);
	}  
}
//添加之前检查一下是否相同
function checkLoadFile(filename,type){
	if (fileadded.indexOf("["+filename+"]")==-1){
	   loadJsAndCssFile(filename, type);
	   fileadded+="["+filename+"]" ;
	}
}

//设置页面居中内容的宽度
function setPageWidth(){
	var w = window.screen.width;
	return w * 0.8 + "px";
}

//获取访问的来源
function getRefer(){
	/*
		不明(直接输入,收藏夹,其他)
		广告链接
		搜索引擎
		其他网站
	*/
	var where = document.referrer;
	if(where == ""){
		 where = "unknown";
	}
	return where;
}


