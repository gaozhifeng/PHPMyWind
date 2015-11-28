<?php require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('soft'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>软件信息管理</title>
<link href="templates/style/admin.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/js/forms.func.js"></script>
<script type="text/javascript" src="templates/js/listajax.js"></script>
<script type="text/javascript" src="templates/js/loadimage.js"></script>
<script type="text/javascript">
$(function(){
	GetList('soft','<?php echo ($cid = isset($cid) ? $cid : ''); ?>');
})
</script>
</head>
<body>
<div class="topToolbar"> <span class="title">软件信息管理</span>
<span class="alltype">
	<a href="javascript:;" onclick="GetType('','查看全部',$(this))" class="btn">查看全部</a>
	<span class="drop">
	<?php GetMgrAjaxType('#@__infoclass',3); ?>
	</span>
</span>
<a href="javascript:location.reload();" class="reload">刷新</a></div>
<form name="form" id="form" method="post">
	<div id="list">
		<div class="loading">读取列表中...</div>
	</div>
</form>
</body>
</html>