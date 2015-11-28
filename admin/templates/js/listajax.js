
/*
**************************
(C)2010-2015 phpMyWind.com
update: 2014-5-28 00:05:59
person: Feng
**************************
*/

//获取列表
function GetList(par_tbn, par_cid)
{
	pag     = par_tbn;
	tbn     = par_tbn;
	cid     = par_cid;
	tid     = "";
	flag    = "all";
	page    = 1;
	keyword = "";


	$.ajax({
		url : pag+"_do.php?tbname="+tbn+"&cid="+cid+"&flag="+flag,
		type:'get',
		dataType:'html',
		beforeSend:function(){
			$("body").append('<div class="loading4"></div><div class="masklayer"></div>');
			$(".masklayer").css("height",$(document).height()).show();
		},
		success:ShowList
	});
}


//输出列表
function ShowList(data, textStatus, xmlHttp)
{
	$("#list").html(data);
	$(".masklayer").hide();

	//判断快捷操作栏
	QuickToolBar();
}


//输出列表
function GetDone(data, textStatus, xmlHttp)
{
	$("#list").html(data);
	$(".masklayer").hide();
	$(".loading4").hide();

	//判断快捷操作栏
	QuickToolBar();
}


//选择栏目函数
function GetType(par_cid, classname, obj)
{
	cid = par_cid;

	if(cid == ''){
		obj.html(classname);
	}else{
		obj.parent().parent().find("a.btn").html(classname);
	}

	$.ajax({
		url : pag+"_do.php?tbname="+tbn+"&cid="+cid+"&tid="+tid+"&flag="+flag,
		type:'get',
		dataType:'html',
		beforeSend:function(){
			$(".masklayer").css("height",$(document).height()).show();
			$(".loading4").show();
		},
		success:GetDone
	});
}


//选择分类函数(目前只对于商品分类生效)
function GetType2(par_tid, classname, obj)
{
	tid = par_tid;

	if(tid == ''){
		obj.html(classname);
	}else{
		obj.parent().parent().find("a.btn").html(classname);
	}

	$.ajax({
		url : pag+"_do.php?tbname="+tbn+"&cid="+cid+"&tid="+tid+"&flag="+flag,
		type:'get',
		dataType:'html',
		beforeSend:function(){
			$(".masklayer").css("height",$(document).height()).show();
			$(".loading4").show();
		},
		success:GetDone
	});
}


//显示属性
function GetFlag(par_flag)
{
	flag = par_flag;

	$.ajax({
		url : pag+"_do.php?tbname="+tbn+"&cid="+cid+"&tid="+tid+"&flag="+flag+"&keyword="+encodeURI(keyword),
		type:'get',
		dataType:'html',
		beforeSend:function(){
			$(".masklayer").css("height",$(document).height()).show();
			$(".loading4").show();
		},
		success:GetDone
	});
}


//显示查询列表
function GetSearch()
{
	keyword = $("#keyword").val();
	if(keyword == '' || keyword == '请填写关键字')
	{
		$("#keyword").val("请填写关键字");
		return;
	}

	$.ajax({
		url : pag+"_do.php?tbname="+tbn+"&cid="+cid+"&tid="+tid+"&flag="+flag+"&keyword="+encodeURI(keyword),
		type:'get',
		dataType:'html',
		beforeSend:function(){
			$(".masklayer").css("height",$(document).height()).show();
			$(".loading4").show();
		},
		success:GetDone
	});
}


//显示分页
function PageList(par_page)
{
	page = par_page;

	$.ajax({
		url : pag+"_do.php?tbname="+tbn+"&cid="+cid+"&tid="+tid+"&flag="+flag+"&keyword="+encodeURI(keyword)+"&page="+page,
		type:'get',
		dataType:'html',
		beforeSend:function(){
			$(".masklayer").css("height",$(document).height()).show();
			$(".loading4").show();
		},
		success:GetDone
	});
}


//删除信息
function ClearInfo(par_id)
{
	if(confirm("确定要删除选中的信息吗？"))
	{
		$.ajax({
			url : pag+"_do.php?tbname="+tbn+"&cid="+cid+"&tid="+tid+"&flag="+flag+"&keyword="+encodeURI(keyword)+"&page="+page+"&action=del&id="+par_id,
			type:'get',
			dataType:'html',
			beforeSend:function(){
				$(".masklayer").css("height",$(document).height()).show();
				$(".loading4").show();
			},
			success:GetDone
		});
	}
	else
	{
		return false;
	}
}


//删除信息
function AjaxClearAll()
{
	var ckobj = $("input[type='checkbox'][name!='checkid'][name^='checkid']:checked");

	if(ckobj.size() > 0)
	{
		if(confirm("确定要删除选中的信息吗？"))
		{
			var ids = '';

			ckobj.each(function(){
				if($(this).val() != 'on'){
					ids += $(this).val() + ',';
				}
			});

			ids = ids.slice(0,-1);

			$.ajax({
				url : pag+"_do.php?tbname="+tbn+"&cid="+cid+"&tid="+tid+"&flag="+flag+"&keyword="+encodeURI(keyword)+"&page="+page+"&action=delall&ids="+ids,
				type:'get',
				dataType:'html',
				beforeSend:function(){
					$(".masklayer").css("height",$(document).height()).show();
					$(".loading4").show();
				},
				success:GetDone
			});
		}
		else
		{
			return false;
		}
	}
	else
	{
		alert('没有任何选中信息！');
		return false;
	}
}


//更改审核状态
function CheckInfo(par_id,state)
{
	id = par_id;
	$.ajax({
		url : tbn+"_save.php?action=check&id="+id+"&checkinfo="+encodeURI(state),
		type:'get',
		dataType:'html',
		success:function(data){$("#check"+id).html(data);}
	});
}


/******************************* 回收站 *******************************/


//显示回收站
function ShowRecycle()
{
	var recycle_title;

	if(pag == "infolist"){
		recycle_title = "信息列表回收站";

	}else if(pag == "infoimg"){
		recycle_title = "图片列表回收站";

	}else if(pag == "soft"){
		recycle_title = "软件列表回收站";

	}else if(pag == "goods"){
		recycle_title = "商品列表回收站";

	}else{
		recycle_title = "参数获取失败";
	}


	$("body").append("<div id=\"recycle\" class=\"recycle\"><div class=\"header\"><span class=\"title\">"+recycle_title+"：</span> <span class=\"close\"><a href=\"javascript:HideRecycle()\"></a></span><div class=\"cl\"></div></div><form name=\"form\" id=\"form\" method=\"post\"><div class=\"list\"></div><div class=\"footer\"><span class=\"sel\"><span>选择：</span> <a href=\"javascript:RecycleCheckAll(true);\">全部</a> - <a href=\"javascript:RecycleCheckAll(false);\">无</a> - <a href=\"javascript:;\" onclick=\"RecycleReAll('resetall')\">还原</a> - <a href=\"javascript:;\" onclick=\"RecycleReAll('delall')\">删除</a></span><a href=\"javascript:;\" onclick=\"RecycleReAll('empty')\" class=\"btn\">清空</a> </div></form></div>")

	$.ajax({
		url : "ajax_do.php?type="+tbn+"&action=recycel",
		type:'get',
		dataType:'html',
		beforeSend:function(){
			$(".masklayer").css("height",$(document).height()).show();
			$(".loading4").show();
			$("#recycle").show();
			$("#recycle .list").html('<div class="loading">列表加载中...</div>');
		},
		success:RecycleDone
	});
}


//隐藏回收站
function HideRecycle()
{
	$.ajax({
		url : pag+"_do.php?tbname="+tbn+"&cid="+cid+"&tid="+tid+"&flag="+flag+"&keyword="+encodeURI(keyword)+"&page="+page,
		type:'get',
		dataType:'html',
		beforeSend:function(){
			$("#recycle").remove();
			$(".masklayer").css("height",$(document).height()).show();
			$(".loading4").show();
		},
		success:GetDone
	});
}


//回收站选择所有
function RecycleCheckAll(value)
{
	$("#recycle input[type='checkbox'][name^='checkid'][disabled!='true']").attr("checked",value);
}


//回收站内容操作
function RecycleRe(action,id)
{
	$.ajax({
		url : "ajax_do.php?type="+tbn+"&action="+action+"&id="+id,
		type: "get",
		dataType:"html",
		success:RecycleDone
	});
}


//操作所有
function RecycleReAll(action)
{
	var ids = '';

	$("#recycle input[type='checkbox'][id^='checkid']:checked").each(function(){
		ids += $(this).val() + ',';
	});

	ids = ids.slice(0,-1);
	if(ids=='' && action!='empty')
	{
		alert('没有任何选中信息！');
		return false;
	}

	$.ajax({
		url : "ajax_do.php?type="+tbn+"&action="+action+"&ids="+ids,
		type: "get",
		dataType:"html",
		success:RecycleDone
	});
}


//完成操作
function RecycleDone(data)
{
	$("#recycle .list").html(data);
}
