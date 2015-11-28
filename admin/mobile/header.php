<?php	if(!defined('IN_MOBILE')) exit('Request Error!'); ?>
<div class="header"> <a href="javascript:;" id="logo" class="logo"></a>
	<div class="user"> <span class="name">Hi, <?php echo $_SESSION['admin']; ?></span> </div>
	<div class="txt"><a href="javascript:;" onClick="SelSiteEQ();">电脑版</a> | <a href="logout.php">退出</a></div>
</div>
<div class="nav">
	<ul>
		<li <?php if($c == 'index') echo 'class="on"'; ?>><a href="?c=index">首页</a></li>
		<li <?php if($c == 'infoclass') echo 'class="on"'; ?>><a href="?c=infoclass">栏目</a></li>
		<li <?php if($c == 'info') echo 'class="on"'; ?>><a href="?c=info">单页</a></li>
		<li <?php if($c == 'infolist') echo 'class="on"'; ?>><a href="?c=infolist">列表</a></li>
		<li <?php if($c == 'infoimg') echo 'class="on"'; ?>><a href="?c=infoimg">图片</a></li>
		<div class="cl"></div>
	</ul>
</div>