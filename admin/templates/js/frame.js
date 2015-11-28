/*
**************************
(C)2010-2015 phpMyWind.com
update: 2014-5-22 00:05:34
person: Feng
**************************
*/


$(function(){

		//用户信息
		userInfo();

		//左侧菜单
		LeftMenuToggle();

		//锁屏按钮
		bindLockScreen();

		//快捷菜单
		bindQuickMenu();


	}).keydown(function(event){

	//快捷键
	if(event.keyCode == 27){
		window.top.location.href = 'logout.php';
	}
});



//用户信息
function userInfo(){
	$(".userPanel").mouseover(function(){
		$(".userPanel .arrow").addClass("on");
		$(".userPanel .panel").fadeIn(200);
	}).mouseout(function(){
		hidequcikmenu = setTimeout('$(".userPanel .panel").fadeOut(200);$(".userPanel .arrow").removeClass("on");',100);
		$(this).mouseover(function(){clearTimeout(hidequcikmenu);});
	});
}



//权限切换
function SelPrivID(id)
{
	$.get("ajax_do.php?action=selpriv&privid="+id+"&rnd="+parseInt(Math.random()*999),function(data){
		if(data == 1){
			window.top.location.reload(true);
		}
	});
}



//左侧菜单
function LeftMenuToggle()
{
	$("#logo").click(function(){
		if($(this).attr("class") == "logo"){
			$(".left").animate({width:"0"},300,function(){$(this).hide()});
			$(".right").animate({left:"0"},300);
			$(this).addClass("logoOn");
		}else{
			$(".left").show().animate({width:"218px"},300);
			$(".right").animate({left:"219px"},300);
			$(this).removeClass("logoOn");
		}
	});
}



//站点选择
function SelSite(sitekey)
{
	$.get("ajax_do.php?action=selsite&sitekeyvalue="+sitekey,function(data){
		if(data == 1){
			window.top.main.location.reload();
		}else if(data == 2){
			window.top.menu.location.reload();
			window.top.main.location.reload();
		}
	});

	$(".siteList a").attr("class","");
	$("#"+sitekey).attr("class","on");
}



//锁屏函数
function bindLockScreen()
{
	$(".lockScreen").click(function(){
		$(".lockScreenBg").show();
		$.get("lockscreen_do.php",{action:"lock"});
	});
}



//锁屏密码
function CheckPwd()
{
	if($(".lockScreenBg #password").val() == ''){
		$(".lockScreenBg .note").html("请输入解锁密码！");
		setTimeout('$(".lockScreenBg .note").html("&nbsp;")',5000);
	}else{
		$.get("lockscreen_do.php",{action:"check",password:$("#password").val()},function(data){
			if(data == true){
				$(".lockScreenBg").fadeOut(150);
				$(".lockScreenBg #password").val("");
			}else{
				$(".lockScreenBg .note").html("密码错误，请重新输入！");
				setTimeout('$(".lockScreenBg .note").html("&nbsp;")',5000);
			}
		});
	}
}



//快捷菜单
function bindQuickMenu(){
	$(".quick").mouseover(function(){
		$(".quick .quickNav").addClass("on");
		$(".quick .quickmenu").fadeIn(200);
	}).mouseout(function(){
		hidequcikmenu = setTimeout('$(".quick .quickmenu").fadeOut(200);$(".quick .quickNav").removeClass("on");',100);
		$(this).mouseover(function(){clearTimeout(hidequcikmenu);});
	}).find("a").click(function(){
		$(this).blur();
		$(".quick .quickmenu").fadeOut(100);
	});
}



//验证授权
function GetAuth()
{
	$.ajax({
		async    : false,
		url      : "http://phpmywind.com/api/auth/index.php?url="+ url +"&callback=?",
		type     : "GET",
		dataType : "jsonp",
		jsonp    : "jsoncallback",
		timeout  : 5000,

		success:function(data){
			var jsonStr = JSON.stringify(data);
			$.ajax({
				type:'GET',
				url:'ajax_do.php?action=updataauth&jsonStr='+ jsonStr,
				beforeSend: function(){},
				success:function(){}
			})

			if(data.status == "OK"){
				$(".authUser").show();
			}else{
				$(".authUser").hide();
			}
		}
	});
}


//站点设备选择
function SelSiteEQ()
{
	$.get("ajax_do.php?action=selsiteeq&eq=mobile",function(data){
		if(data == 1){
			window.top.location.href = "default_mb.php?c=index";
		}
	});
}
