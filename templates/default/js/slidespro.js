/*
**************************
(C)2010-2015 phpMyWind.com
update: 2012-6-19 14:01:37
person: Feng
**************************
*/

$(function(){
	var Current;
	$(".picture a").eq(0).show();
	$(".preview a").click(function(){
		Current = $(".preview a").index($(this));
		$(".picture a").hide().eq(Current).fadeIn(300);
	});
})
