<?php	require_once(dirname(__FILE__).'/inc/config.inc.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>后台首页</title>
<link href="templates/style/admin.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery.min.js"></script>
<script type="text/javascript">
$(function(){
	//控制便签
	$("#homeNote").focus(function(){
		$(".notearea").addClass("borderOn");
		if($.trim($(this).val()) == "点击输入便签内容..."){
			$(this).val("");
		}
	}).blur(function(){
		$(".notearea").removeClass("borderOn");
		if($.trim($(this).val()) == ""){
			$.ajax({
				url : "ajax_do.php",
				type:'post',
				data:{"action":"deladminnotes"},
				dataType:'html',
				success:function(data){	
				}
			});
			$(this).val("点击输入便签内容...");
		}else{
			$.ajax({
				url : "ajax_do.php",
				type:'post',
				data:{"action":"adminnotes", "body":$.trim($(this).val())},
				dataType:'html',
				success:function(data){
				}
			});
		}
	});

	$("#showad").html('<iframe src="showad.php" width="100%" height="25" scrolling="no" frameborder="0" allowtransparency="true"></iframe>');
});
</script>
</head>
<body>
<div class="homeHeader">
	<div class="header"><span class="title">首页</span><a href="javascript:location.reload();" class="reload">刷新</a></div>
	<div class="news">
		<div class="title">官方公告</div >
		<div id="showad"> </div>
	</div>
</div>
<div class="homeUserCont">
	<div class="leftArea">
		<h2 class="title">空间</h2>
		<ul class="status">
			<li>服务器名： <?php echo $_SERVER['SERVER_NAME']; ?> </li>
			<li>服务器IP： <?php echo gethostbyname($_SERVER['SERVER_NAME']).':'.$_SERVER['SERVER_PORT'];; ?> </li>
			<li><span style="border-bottom:none;">服务器系统： <?php echo PHP_OS; ?></span></li>
			<li> 服务器版本： <?php echo ReStrLen($_SERVER['SERVER_SOFTWARE'],12); ?></li>
			<li>PHP&amp;MySQL版本： <?php echo PHP_VERSION; ?>&amp;<?php echo $dosql->GetVersion(); ?></li>
			<li>POST提交内容限制： <?php echo get_cfg_var('post_max_size'); ?></li>
			<li>脚本超时时间： <?php echo get_cfg_var('max_execution_time').'秒'; ?></li>
			<li>脚本上传文件大小限制： <?php echo get_cfg_var('upload_max_filesize') ? get_cfg_var('upload_max_filesize') : '不允许上传附件'; ?></li>
			<li>脚本运行时可占最大内存： <?php echo get_cfg_var('memory_limit') ? get_cfg_var('memory_limit') : '无'; ?></li>
		</ul>
	</div>
	<div class="rightArea">
		<h2 class="title">支持</h2>
		<ul class="status">
			<li>GD扩展： <?php echo showResult(function_exists('imageline')); ?> </li>
			<li>ZEND支持： <?php echo showResult(function_exists('zend_version')); ?> </li>
			<li>Socket支持： <?php echo showResult(function_exists('socket_accept')); ?> </li>
			<li>PDF支持： <?php echo showResult(function_exists('pdf_close')); ?> </li>
			<li>XML解析： <?php echo showResult(function_exists('xml_set_object')); ?> </li>
			<li>FTP登录： <?php echo showResult(function_exists('ftp_login')); ?> </li>
			<li>显示错误信息： <?php echo showResult(get_cfg_var('display_errors')); ?> </li>
			<li>使用URL打开文件： <?php echo showResult(get_cfg_var('allow_url_fopen')); ?> </li>
			<li>压缩文件支持(Zlib)： <?php echo showResult(function_exists('gzclose')); ?> </li>
			
		</ul>
	</div>
	<div class="cl"></div>
</div>
<div class="homeNote">
	<h2 class="title">记事</h2>
	<div class="notearea">
		<textarea name="homeNote" id="homeNote"><?php
		$uname    = $_SESSION['admin'];
		$posttime = time();
		$postip   = GetIP();

		$r = $dosql->GetOne("SELECT `body` FROM `#@__adminnotes` WHERE uname='$uname'");
		if(isset($r['body']))
			echo trim($r['body']);
		else
			echo '点击输入便签内容...';
		?></textarea>
	</div>
</div>
<div class="homeCopy"> 敬请您将在使用中发现的问题或者不适提交给我们，以便改进 <a href="http://phpmywind.com/bbs/" target="_blank" class="feedback">点击提交反馈</a> | <a href="help.php" class="doc">开发帮助</a> </div>
<?php
function showResult($v)
{
	if($v == 1)
		echo'<span class="ture">支持</span>';
	else
		echo'<span class="flase">不支持</span>';
}
?>
</body>
</html>