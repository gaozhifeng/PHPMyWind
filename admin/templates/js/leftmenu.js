/*
**************************
(C)2010-2015 phpMyWind.com
update: 2014-5-22 17:39:34
person: Feng
**************************
*/


$(function(){

	$(".viewport").css("height", $(".overview").height());
	$("#scrollmenu").tinyscrollbar();

	FirstLoad();

	$(window).resize(function(){
		FirstLoad();
	});

}).keydown(function(event){

	//快捷键
	if(event.keyCode == 27){
		window.top.location.href = 'logout.php';
	}
});



//点击操作
function DisplayMenu(id)
{
	$("div[id^=leftmenu][id!="+id+"]").hide();
	$(".title").removeClass("on");

	var t = $("#"+id);
	t.toggle();

	if(t.css("display") == "block"){
		t.prev().addClass("on");
	}else{
		t.prev().removeClass("on");
	}

	FirstLoad();
}



//载入初始化
function FirstLoad()
{
	if($(".overview").height() > $(window).height()-135)
	{
		if($.browser.msie){
			$(".tGradient").show();
			$(".bGradient").show();
		}else{
			$(".tGradient").fadeIn(100);
			$(".bGradient").fadeIn(100);
		}

		$(".viewport").css({"height":$(window).height()-135, "overflow":"hidden"});
	}
	else
	{
		if($.browser.msie){
			$(".tGradient").hide();
			$(".bGradient").hide();
		}else{
			$(".tGradient").fadeOut(100);
			$(".bGradient").fadeOut(100);
		}

		$(".viewport").css({"height":$(".overview").height(), "overflow":"none"});
	}

	$("#scrollmenu").tinyscrollbar_update("relative");
}
