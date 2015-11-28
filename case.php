<?php
require_once(dirname(__FILE__).'/include/config.inc.php');

//初始化参数检测正确性
$cid = empty($cid) ? 8 : intval($cid);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php echo GetHeader(1,$cid); ?>
<link href="templates/default/style/webstyle.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="templates/default/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/default/js/loadimage.js"></script>
<script type="text/javascript" src="templates/default/js/top.js"></script>
<script type="text/javascript">
$(function(){
    $(".caselist a.img img").LoadImage({width:100,height:80});
});
</script>
</head>
<body>
<!-- header-->
<?php require_once('header.php'); ?>
<!-- /header-->
<!-- banner-->
<div class="subBanner"> <img src="templates/default/images/banner-ir.png" /> </div>
<!-- /banner-->
<!-- notice-->
<div class="subnotice"><strong>网站公告：</strong><?php echo Info(1); ?></div>
<!-- /notice-->
<!-- mainbody-->
<div class="subBody">
	<div class="subTitle"> <span class="catname"><?php echo GetCatName($cid); ?></span> <span class="fr">您当前所在位置：<?php echo GetPosStr($cid); ?></span>
		<div class="cl"></div>
	</div>
	<div class="OneOfTwo">
		<div class="subCont">
			<ul class="caselist">
				<?php

				$dopage->GetPage("SELECT * FROM `#@__infoimg` WHERE (classid=$cid OR parentstr LIKE '%,$cid,%') AND delstate='' AND checkinfo=true ORDER BY orderid DESC",5);
				while($row = $dosql->GetArray())
				{
					if($row['picurl'] != '') $picurl = $row['picurl'];
					else $picurl = 'templates/default/images/nofoundpic.gif';
					
					if($row['linkurl']=='' and $cfg_isreurl!='Y') $gourl = 'caseshow.php?cid='.$row['classid'].'&id='.$row['id'];
					else if($cfg_isreurl=='Y') $gourl = 'caseshow-'.$row['classid'].'-'.$row['id'].'-1.html';
					else $gourl = $row['linkurl'];

					$r = $dosql->GetOne("SELECT `classname` FROM `#@__infoclass` WHERE id=".$row['classid']);
					if(isset($r['classname'])) $classname = $r['classname'];
					else $classname = '';

					if($cfg_isreurl!='Y') $gourl2 = 'case.php?cid='.$row['classid'];
					else $gourl2 = 'case-'.$row['classid'].'-1.html';
				?>
				<li>
					<p class="preview"><a href="<?php echo $gourl; ?>" class="img"><img src="<?php echo $picurl; ?>" /></a></p>
					[<a href="<?php echo $gourl2; ?>" class="type"><?php echo $classname; ?></a>] <a href="<?php echo $gourl; ?>" class="title" style="color:<?php echo $row['colorval']; ?>;font-weight:<?php echo $row['boldval']; ?>;"><?php echo $row['title']; ?></a> <span> 日期：<small><?php echo GetDateMk($row['posttime']); ?></small>点击：<small><?php echo $row['hits']; ?></small></span>
					<p class="desc"><?php echo ReStrLen($row['description'],90); ?></p>
					<div class="cl"></div>
				</li>
				<?php
				}
				?>
			</ul>
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