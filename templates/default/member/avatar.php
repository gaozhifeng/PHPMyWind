<?php if(!defined('IN_MEMBER')) exit('Request Error!'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $cfg_webname; ?> - 会员中心 - 编辑头像</title>
<link href="<?php echo $cfg_webpath; ?>/templates/default/style/member.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo $cfg_webpath; ?>/templates/default/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $cfg_webpath; ?>/templates/default/js/member.js"></script>
</head>

<body>
<div class="header">
	<?php require_once(dirname(__FILE__).'/header.php'); ?>
</div>
<div class="mainbody">
	<div class="leftarea">
		<?php require_once(dirname(__FILE__).'/lefter.php'); ?>
	</div>
	<div class="rightarea">
		<h3 class="subtitle">我的头像</h3>
		<div class="preavatar">
			<img src="<?php echo $cfg_webpath; ?>/data/avatar/index.php?uid=<?php echo $r_user['id']; ?>&size=big" />
			<img src="<?php echo $cfg_webpath; ?>/data/avatar/index.php?uid=<?php echo $r_user['id']; ?>&size=middle" />
			<img src="<?php echo $cfg_webpath; ?>/data/avatar/index.php?uid=<?php echo $r_user['id']; ?>&size=small" />
		</div>
		<h3 class="subtitle">上传头像</h3>
		<div class="upavatar">
			<iframe src="data/avatar/upload.php?uid=<?php echo urlencode(AuthCode($r_user['id'],'ENCODE')); ?>" width="458" height="268" frameborder="0" scrolling="no"></iframe>
			<div>头像上传成功后，点击完成或刷新页面(可按F5键)，才能查看最新的头像效果</div>
		</div>
	</div>
	<div class="cl"></div>
</div>
<div class="footer"><?php echo $cfg_copyright; ?></div>
<script type="text/javascript">
function updateavatar() {
window.location.reload();
}
</script>
</body>
</html>
