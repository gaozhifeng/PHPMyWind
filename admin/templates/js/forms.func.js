
/*
**************************
(C)2010-2015 phpMyWind.com
update: 2014-5-29 00:12:36
person: Feng
**************************
*/


$(function(){

	$(".dataTr").mouseover(function(){
		$(this).attr("class","dataTrOn");
	}).mouseout(function(){
		$(this).attr("class","dataTr");
	});


	$(".alltype").mouseover(function(){
		$(this).find(".btn").addClass("on");
		$(this).find(".drop").show();
	}).mouseout(function(){
		$(this).find(".btn").removeClass("on");
		$(this).find(".drop").hide();
	});


	QuickToolBar();


	$(window).resize(function(){
		QuickToolBar();
	});


	$(window).scroll(function(){
		QuickToolBar();
	});

}).keydown(function(event){

	//快捷键
	if(event.keyCode == 27){
		window.top.location.href = 'logout.php';
	}
});



//快捷工具栏
function QuickToolBar()
{
	if($(window).scrollTop() < $(document).height() - $(window).height() - 100){
		$(".quickToolbar").show();
	}else{
		$(".quickToolbar").fadeOut(300);
	}
}


//选择所有
function CheckAll(value)
{
	$("input[type='checkbox'][name^='checkid'][disabled!='true']").attr("checked",value);
}



//删除单条提示
function ConfDel(i)
{
	var tips = Array();
	tips[0] = "确定要删除选中的信息吗？";
	tips[1] = "系统会自动删除类别下所有子类别以及信息，确定删除吗？";
	tips[2] = "系统会自动删除类别下所有子类别，确定删除吗？";

	if(confirm(tips[i])) return true;
	else return false;
}



//删除选中提示
function ConfDelAll(i)
{
	var tips = Array();
	tips[0] = "确定要删除选中的信息吗？";
	tips[1] = "系统会自动删除类别下所有子类别以及信息，确定删除吗？";
	tips[2] = "系统会自动删除类别下所有子类别，确定删除吗？";

	if($("input[type='checkbox'][name!='checkid'][name^='checkid']:checked").size() > 0)
	{
		if(confirm(tips[i])) return true;
		else return false;
	}
	else
	{
		alert('没有任何选中信息！');
		return false;
	}
}



//删除所有(包含子分类)
function DelAll(url,par)
{
	var par = arguments[1] ? arguments[1] : "";
	$("#form").attr("action", url+"?action=delall"+par).submit();
}



//删除所有(不包含子分类)
function DelAllNone(url)
{
	$("#form").attr("action", url+"?action=delall2").submit();
}



//提交更新表单
function UpdateForm(url)
{
	$("#form").attr("action", url+"?action=update").submit();
}



//执行特定参数
function SubUrlParam(url)
{
	$("#form").attr("action", url).submit();
}



//更新排序
function UpOrderID(url)
{
	$("#form").attr("action", url+"?action=uporder").submit();
}



//展开合并下级
function DisplayRows(id)
{
	var rowpid = $("div[rel='rowpid_"+id+"']");
	var rowid  = $("span[id='rowid_"+id+"']");

	if(rowid.attr("class") == "minusSign")
	{
		rowpid.slideUp(200);
		rowid.attr("class","plusSign");

		//判断快捷操作栏
		setTimeout("QuickToolBar()",200);
	}
	else
	{
		rowpid.slideDown(200);
		rowid.attr("class","minusSign");

		//判断快捷操作栏
		setTimeout("QuickToolBar()",200);
	}

}



//全部显示行
function ShowAllRows()
{
	$("div[rel^='rowpid'][rel!='rowpid_0']").slideDown(200);
	$("span[id^='rowid']").attr("class","minusSign");

	//判断快捷操作栏
	setTimeout("QuickToolBar()",200);
}



//全部隐藏行
function HideAllRows()
{
	$("div[rel^='rowpid'][rel!='rowpid_0']").slideUp(200, QuickToolBar());
	$("span[id^='rowid']").attr("class","plusSign");

	//判断快捷操作栏
	setTimeout("QuickToolBar()",200);
}



//文件上传提示
function UploadPrompt(string)
{
	if(string == 0)
	{
		$(".uploading").html('<div class="upload_newfile_loading"><img src="templates/images/loading.gif">文件上传中...</div>');
	}
	else
	{
		$('.uploading').html(string);
	}
}



//检查是否存在上传文件
function CheckIsUpload()
{
	if($("#upfile").val() == "")
	{
		UploadPrompt("请选择上传文件！");
		return false;
	}
	else
	{
		return true;
	}
}



//显示div
function ShowDiv(id)
{
	$("#"+id).show();
}



//隐藏div
function HideDiv(id)
{
	$("#"+id).fadeOut();
}
