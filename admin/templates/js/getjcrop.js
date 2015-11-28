/*
**************************
(C)2010-2015 phpMyWind.com
update: 2012-2-21 9:49:35
person: Feng
**************************
*/


/*
 * 获取裁切窗口初始化参数
*/
function GetJcrop(frame, input)
{
	var imgurl = $("#" + input).val();

	if(imgurl.indexOf("uploads/") == -1)
	{
		alert("亲，请检查图片路径是否为本地或不合法！");
		return;
	}

	var urllen  = imgurl.length;
	var urllen2 = urllen - 3;
	var ext     = imgurl.substring(urllen2, urllen)

	if(ext != "jpg" && ext != "png" && ext != "gif" && ext != "bmp")
	{
		alert("亲，请检查该文件是否是图片格式！");
		return;
	}

	$("body").append('<iframe frameborder="0" id="'+ frame +'" src="plugin/jcrop/index.php?imgurl='+ encodeURI(imgurl) +'&frame='+ frame +'&input='+ input +'" allowtransparency="true" style="position:absolute;top:0;left:0;width:100%;height:100%;display:none;z-index:9999;" scrolling="no"></iframe>');

	if($.browser.msie){
		window.frames[1].location.reload();
	}

	$("#" + frame).css("height",$(document).height()).show();

	$(window).resize(function(){
		$("#" + frame).css("height",$(document).height()).show();
	});

}
