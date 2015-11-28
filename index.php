<?php require_once(dirname(__FILE__).'/include/config.inc.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php echo GetHeader(); ?>
<link href="templates/default/style/webstyle.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="templates/default/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/default/js/slideplay.js"></script>
<script type="text/javascript" src="templates/default/js/srcollimg.js"></script>
<script type="text/javascript" src="templates/default/js/loadimage.js"></script>
<script type="text/javascript" src="templates/default/js/top.js"></script>
<script type="text/javascript">
$(function(){
    $(".imgwrap li img").LoadImage({width:60,height:45});
	$(".newsfocus div img").LoadImage({width:60,height:60});
});
</script>
</head>
<body>
<!-- header-->
<?php require_once('header.php'); ?>
<!-- /header-->
<!-- banner-->
<div class="banner">
	<div id="slideplay">
		<ul>
			<?php
			$dosql->Execute("SELECT * FROM `#@__infoimg` WHERE classid=13 AND delstate='' AND checkinfo=true ORDER BY orderid DESC LIMIT 0,5");
			while($row = $dosql->GetArray())
			{
				if($row['linkurl'] != '')$gourl = $row['linkurl'];
				else $gourl = 'javascript:;';
			?>
			<li><a href="<?php echo $gourl; ?>"><img src="<?php echo $row['picurl']; ?>" alt="<?php echo $row['title']; ?>" /></a></li>
			<?php
			}
			?>
		</ul>
	</div>
</div>
<!-- /banner-->
<!-- notice-->
<div class="notice">
	<div class="notice_a"><strong>网站公告：</strong><?php echo Info(1); ?></div>
	<div class="search">
		<form name="search" id="search" method="get" action="product.php">
			<input type="text" name="keyword" id="keyword" title="输入产品名进行搜索" value="" class="key" />
			<button type="submit" id="search_btn" class="sub"><span>搜索</span></button>
		</form>
	</div>
</div>
<!-- /notice-->
<!-- mainbody-->
<div class="mainbody">
	<!-- mainbody 1of2 2of2-->
	<div class="OneOfTwo">
		<!-- newslist-->
		<div class="newwarp">
			<div class="newstitle">
			<?php
			if($cfg_isreurl!='Y') $gourl = 'news.php';
			else $gourl = 'news.html';
			?>
			<a href="<?php echo $gourl; ?>">更多&gt;&gt;</a></div>
			<div class="newsfocus">
				<?php
				$row = $dosql->GetOne("SELECT * FROM `#@__infolist` WHERE classid=4 AND flag LIKE '%h%' AND delstate='' AND checkinfo=true ORDER BY orderid DESC");
				if(isset($row['id']))
				{
					//获取链接地址
					if($row['linkurl']=='' and $cfg_isreurl!='Y')
						$gourl = 'newsshow.php?cid='.$row['classid'].'&id='.$row['id'];
					else if($cfg_isreurl=='Y')
						$gourl = 'newsshow-'.$row['classid'].'-'.$row['id'].'-1.html';
					else
						$gourl = $row['linkurl'];

					//获取缩略图地址
					if($row['picurl']!='')
						$picurl = $row['picurl'];
					else
						$picurl = 'templates/default/images/nofoundpic.gif';
				?>
				<div><a href="<?php echo $gourl; ?>"><img src="<?php echo $picurl; ?>" /></a></div>
				<h3><a href="<?php echo $gourl; ?>" style="color:<?php echo $row['colorval']; ?>;font-weight:<?php echo $row['boldval']; ?>;"><?php echo ReStrLen($row['title'],16); ?></a></h3>
				<p><?php echo ReStrLen($row['description'],34); ?></p>
				<?php
				}
				else
				{
					echo '网站资料更新中...';
				}
				?>
			</div>
			<ul class="newslist">
				<?php $dosql->Execute("SELECT * FROM `#@__infolist` WHERE (classid=4 or parentid=4) AND delstate='' AND checkinfo=true ORDER BY orderid DESC LIMIT 0,3");
				while($row = $dosql->GetArray())
				{
					//获取链接地址
					if($row['linkurl']=='' and $cfg_isreurl!='Y')
						$gourl = 'newsshow.php?cid='.$row['classid'].'&id='.$row['id'];
					else if($cfg_isreurl=='Y')
						$gourl = 'newsshow-'.$row['classid'].'-'.$row['id'].'-1.html';
					else
						$gourl = $row['linkurl'];
				?>
				<li><span><?php echo MyDate('m-d', $row['posttime']); ?></span>· <a href="<?php echo $gourl; ?>" style="color:<?php echo $row['colorval']; ?>;font-weight:<?php echo $row['boldval']; ?>;"><?php echo ReStrLen($row['title'],19); ?></a></li>
				<?php
				}
				?>
			</ul>
		</div>
		<!-- /newslist-->
		<!-- aboutus-->
		<div class="aboutus"><img src="<?php echo InfoPic(3); ?>" width="154" height="83" />
		<?php
		if($cfg_isreurl!='Y') $gourl = 'about.php';
		else $gourl = 'about.html';
		echo Info(3,147,$gourl);
		?>
		</div>
		<!-- /aboutus-->
		<div class="cl"></div>
	</div>
	<div class="TowOfTow">
		<div class="contact"> <?php echo Info(10); ?> </div>
		<div class="follow"><a href="http://weibo.com/phpMyWind" class="sina" target="_blank">收听新浪微博</a><a href="http://t.qq.com/phpMyWind" class="tqq" target="_blank">收听腾讯微博</a></div>
	</div>
	<div class="cl"></div>
	<!-- /mainbody 1of2 2of2-->
	<!-- product-->
	<div class="scrollimg">
		<div class="imgwrap">
			<ul>
				<?php
				$dosql->Execute("SELECT * FROM `#@__infoimg` WHERE (classid=5 OR parentstr LIKE '%,5,%') AND delstate='' AND checkinfo=true ORDER BY orderid DESC LIMIT 0,18");
				while($row = $dosql->GetArray())
				{
					//获取链接地址
					if($row['linkurl']=='' and $cfg_isreurl!='Y')
						$gourl = 'productshow.php?cid='.$row['classid'].'&id='.$row['id'];
					else if($cfg_isreurl=='Y')
						$gourl = 'productshow-'.$row['classid'].'-'.$row['id'].'-1.html';
					else
						$gourl = $row['linkurl'];

					//获取缩略图地址
					if($row['picurl']!='')
						$picurl = $row['picurl'];
					else
						$picurl = 'templates/default/images/nofoundpic.gif';
				?>
				<li>
					<dl>
						<dt><a href="<?php echo $gourl; ?>"><img src="<?php echo $picurl; ?>" title="<?php echo $row['title']; ?>" /></a></dt>
						<dd><a href="<?php echo $gourl; ?>"><?php echo $row['title']; ?></a><?php echo $row['keywords']; ?></dd>
					</dl>
				</li>
				<?php
				}
				?>
			</ul>
		</div>
	</div>
	<!-- /product-->
</div>
<!-- /mainbody-->
<?php require_once('footer.php'); ?>
</body>
</html>