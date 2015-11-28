/*
**************************
(C)2010-2015 phpMyWind.com
update: 2014-6-24 11:47:57
person: Feng
**************************
*/


//站点设备选择
function SelSiteEQ()
{
	$.get("ajax_do.php?action=selsiteeq&eq=pc",function(data){
		if(data == 1){
			window.top.location.href = "default.php";
		}
	});
}
