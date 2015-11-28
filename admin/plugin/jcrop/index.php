<?php	require_once(dirname(__FILE__).'/../../inc/config.inc.php');

/*
**************************
(C)2010-2015 phpMyWind.com
update: 2012-9-15 12:14:40
person: Feng
**************************
*/


//初始化参数
$imgurl = isset($imgurl) ? $imgurl : '';
$frame  = isset($frame ) ? $frame  : '';


//引入水印文件
require_once(PHPMYWIND_DATA.'/watermark/watermark.inc.php');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>jcrop</title>
</head>
<body>
<link href="jcrop.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="jquery.min.js"></script>
<script type="text/javascript" src="jcrop.min.js"></script>
<div class="W">
	<div class="Bg" id="Bg"> </div>
	<div class="jcorp_pop">
		<div class="jcorp_warp">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="board">
				<tr>
					<td height="315" align="center" valign="middle"><img src="../../../<?php echo $imgurl; ?>" id="jcrop_img" /></td>
				</tr>
			</table>
		</div>
		<div class="jcorp_par">
			<form id="crop" action="jcrop.php" method="post" onsubmit="return CheckCoords();">
				<label>X1
					<input type="text" name="x1" id="x1" size="4" class="jcorp_txt" />
				</label>
				<label>Y1
					<input type="text" name="y1" id="y1" size="4" class="jcorp_txt" />
				</label>
				<label>X2
					<input type="text" name="x2" id="x2" size="4" class="jcorp_txt" />
				</label>
				<label>Y2
					<input type="text" name="y2" id="y2" size="4" class="jcorp_txt" />
				</label>
				<label>宽度
					<input type="text" name="w" id="w" size="4" class="jcorp_txt" />
				</label>
				<label>高度
					<input type="text" name="h" id="h" size="4" class="jcorp_txt" />
				</label>
				<label>
					<input type="checkbox" name="wm" id="wm" value="true" <?php if($cfg_markswitch=='Y') echo 'checked="checked"' ?> />
					水印
				</label>
			</form>
		</div>
		<div class="jcorp_btn"> <span class="txt">您可以用鼠标在图片上进行拖动选取</span> <span class="btn" id="SaveBtn">保存</span> &nbsp; <span class="btn" id="CancelBtn">关闭</span> </div>
		<!--[if IE 6]>
	<iframe frameborder="0" style="width:100%;height:100px;background-color:transparent;position:absolute;top:0;left:0;z-index:-1;"></iframe>
	<![endif]-->
	</div>
</div>
<script type="text/javascript">
$(function(){

	$('#jcrop_img').Jcrop({
		onChange  : ShowCoords,
		onSelect  : ShowCoords,
		onRelease : ClearCoords
	});


	//鼠标离控件左上角的相对位置
	var _top;


	//初始化窗口位置
	_top = parseInt($(window.parent.window).height()/2) - 204 + $(window.parent.document).scrollTop();
	$(".jcorp_pop").css("top", _top);


	//浏览器窗口发生变化时窗口位置
	$(window).resize(function(){
		_top = parseInt($(window.parent.window).height()/2)- 204 + $(window.parent.document).scrollTop();
		$(".jcorp_pop").css("top", _top);
	});


	$("#SaveBtn").click(function(){

		if(!parseInt($('#w').val()))
		{
			alert('亲，请选择裁切区域再保存！');
			return false;
		}

		var x1   = $("#x1").val();
		var y1   = $("#y1").val();
		var x2   = $("#x2").val();
		var y2   = $("#y2").val();
		var iw   = $("#w").val();
		var ih   = $("#h").val();
		var wm   = $("#wm").val();

		$.ajax({
			url : "jcrop.php?imgurl=<?php echo $imgurl; ?>&x1="+ x1 +"&y1="+ y1 +"&x2="+ x2 +"&y2="+ y2 +"&iw="+ iw +"&ih=" + ih +"&wm=" + wm,
			type:'get',
			dataType:'html',
			beforeSend:function(){
			},
			success:GetReturn
		});
	});

	$("#CancelBtn").click(function(){
		CloseJcrop();
	});

});

/*
 * 获取返回信息
*/
function GetReturn(data, textStatus, xmlHttp)
{
	if(data == true)
	{
		CloseJcrop();
	}
	else
	{
		alert("图片裁切未成功！");
	}
}

/*
 * 关闭裁切窗口
*/
function CloseJcrop()
{
	$("#<?php echo $frame ;?>", window.parent.document).remove();
	return;
}

/*
 * 显示裁切参数
*/
function ShowCoords(c)
{
	$('#x1').val(c.x);
	$('#y1').val(c.y);
	$('#x2').val(c.x2);
	$('#y2').val(c.y2);
	$('#w').val(c.w);
	$('#h').val(c.h);
}

/*
 * 清除裁切区域
*/
function ClearCoords()
{
	$('#coords input[type=text]').val('');
}

</script>
</body>
</html>
