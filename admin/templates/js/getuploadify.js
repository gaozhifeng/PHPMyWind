/*
**************************
(C)2010-2015 phpMyWind.com
update: 2012-4-27 11:35:55
person: Feng
**************************
*/


/*
 * 获取上传窗口函数
 *
 * @access   public
 * @frame    string  调用iframeID
 * @title    string  弹出窗口标题
 * @type     string  可上传文件类型,可以是直接的类型或是image|soft|media
 * @desc     string  可上传文件描述,可以是直接的描述或是image|soft|media
 * @num      string  可上传数量
 * @size     string  可上传文件大小
 * @input    string  处理后返回值写入input
 * @area     string  多附件时返回的内容区域
 */

function GetUploadify(frame,title,type,desc,num,size,input,area)
{
	$("body").append('<iframe frameborder="0" id="'+ frame +'" src="plugin/uploadify/index.php?title='+ encodeURI(title) +'&type='+ type +'&desc='+ encodeURI(desc) +'&num='+ num +'&size='+ size +'&frame='+ frame +'&input='+ input +'&area='+ area +'" allowtransparency="true" style="position:absolute;top:0;left:0;width:100%;height:100%;display:none;z-index:9999;" scrolling="no"></iframe>');

	$("#" + frame).css("height",$(document).height()).show();

	$(window).resize(function(){
		$("#" + frame).css("height",$(document).height()).show();
	});
}



/*
 * 删除组图input
 *
 * @access   public
 * @val      string  删除的图片input
 */

function ClearPicArr(val)
{
	$("li[rel='"+ val +"']").remove();
	$.get(
		'plugin/uploadify/uploadify.php',
		{action:"del", filename:val},
		function(){}
	);
}
