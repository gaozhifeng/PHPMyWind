<?php require_once(dirname(__FILE__).'/include/config.inc.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php echo GetHeader(1,0,0,'人才招聘'); ?>
<link href="templates/default/style/webstyle.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="templates/default/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/default/js/top.js"></script>
</head>
<body>
<!-- header-->
<?php require_once('header.php'); ?>
<!-- /header-->
<!-- banner-->
<div class="subBanner"> <img src="templates/default/images/banner-ir.png" /> </div>
<!-- /banner-->
<!-- notice-->
<div class="subnotice"><strong>网站公告：</strong><?php echo Info(1); ?> </div>
<!-- /notice-->
<!-- mainbody-->
<div class="subBody">
	<div class="subTitle"> <span class="catname">人才招聘</span> <span>您当前所在位置：<a href="<?php echo $cfg_webpath; ?>">首页</a> &gt; <a href="join.php">人才招聘</a></span>
		<div class="cl"></div>
	</div>
	<div class="OneOfTwo">
		<div class="subCont">
			<div class="news_list">
				<ul>
					<?php
					$dopage->GetPage("SELECT * FROM `#@__job` WHERE checkinfo=true ORDER BY orderid DESC",10);
					while($row = $dosql->GetArray())
					{
						if($cfg_isreurl!='Y') $gourl = 'joinshow.php?id='.$row['id'];
						else $gourl = 'joinshow-'.$row['id'].'.html';
						
					?>
					<li><span>[<?php echo GetDateMk($row['posttime']); ?>]</span><strong>&gt;&gt;</strong><a href="<?php echo $gourl; ?>"><?php echo $row['title']; ?></a></li>
					<?php
					}
					?>
				</ul>
			</div>
			<?php echo $dopage->GetList(); ?>
		</div>
	</div>
	<div class="TwoOfTwo">
		<?php require_once('lefter.php'); ?>
	</div>
	<div class="cl"></div>
</div>
<!-- /mainbody-->
<!-- footer-->
<?php require_once('footer.php'); ?>
<!-- /footer-->
</body>
</html>