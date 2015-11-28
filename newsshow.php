<?php
require_once(dirname(__FILE__).'/include/config.inc.php');

//初始化参数检测正确性
$cid = empty($cid) ? 4 : intval($cid);
$id  = empty($id)  ? 0 : intval($id);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php echo GetHeader(1,$cid,$id); ?>
<link href="templates/default/style/webstyle.css" type="text/css" rel="stylesheet" />
<link href="templates/default/style/lightbox.css" type="text/css" rel="stylesheet" />
<!--[if IE 6]><link href="templates/default/style/lightbox.ie6.css" rel="stylesheet" type="text/css"/><![endif]-->
<script type="text/javascript" src="templates/default/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/default/js/loadimage.js"></script>
<script type="text/javascript" src="templates/default/js/slidespro.js"></script>
<script type="text/javascript" src="templates/default/js/lightbox.js"></script>
<script type="text/javascript" src="templates/default/js/comment.js"></script>
<script type="text/javascript" src="templates/default/js/top.js"></script>
<script type="text/javascript">
$(function(){
	jQuery('.lightbox').lightbox();
	$(".picarr .picture img").LoadImage({width:530,height:350});
	$(".picarr .preview img").LoadImage({width:58,height:45});
	$(".small").click(function(){$("#textarea").css('font-size','12px');});
	$(".big").click(function(){$("#textarea").css('font-size','14px');});
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
	<div class="subTitle"> <span class="catname"><?php echo GetCatName($cid); ?></span> <a href="javascript:history.go(-1);" class="goback">&gt;&gt; 返回</a> <span class="fr">您当前所在位置：<?php echo GetPosStr($cid,$id); ?></span>
		<div class="cl"></div>
	</div>
	<div class="OneOfTwo">
		<!-- 详细区域开始 -->
		<div class="listConts">
			<?php

			//检测文档正确性
			$row = $dosql->GetOne("SELECT * FROM `#@__infolist` WHERE id=$id");
			if(is_array($row))
			{
				//增加一次点击量
				$dosql->ExecNoneQuery("UPDATE `#@__infolist` SET hits=hits+1 WHERE id=$id");
			?>
			<!-- 标题区域开始 -->
			<h1 class="title"><?php echo $row['title']; ?></h1>
			<div class="info"><small>更新时间：</small><?php echo GetDateTime($row['posttime']); ?><small>点击次数：</small><?php echo $row['hits']; ?>次<small>字号：</small><span class="small" title="切换到小字体">T</span>|<span class="big" title="切换到大字体">T</span></div>
			<!-- 标题区域结束 -->
			<!-- 摘要区域开始 -->
			<?php
			//判断是否显示描述
			if(!empty($row['description'])) echo '<div class="desc">'.$row['description'].'</div>';
			?>
			<!-- 摘要区域结束 -->
			<!-- 组图区域开始-->
			<?php
			//判断显示缩略图或组图
			if(!empty($row['picarr']))
			{
				$picarr = unserialize($row['picarr']);
			?>
			<div class="picarr">
				<div class="picture">
					<?php
					foreach($picarr as $k)
					{
						$v = explode(',', $k);
					?>
					<a href="<?php echo $v[0]; ?>" class="lightbox" alt="<?php echo $v[1]; ?>"><img src="<?php echo $v[0]; ?>" /></a>
					<?php
					}
					?>
				</div>
				<ul class="preview">
					<?php
					foreach($picarr as $k)
					{
						$v = explode(',', $k);
					?>
					<li><a href="javascript:void(0);"><img src="<?php echo $v[0]; ?>" /></a></li>
					<?php
					}
					?>
					<div class="cl"></div>
				</ul>
				<div class="cl"></div>
			</div>
			<?php
			}
			?>
			<!-- 组图区域结束 -->
			<!-- 内容区域开始 -->
			<div id="textarea">
				<?php
				if($row['content'] != '')
					echo GetContPage($row['content']);
				else
					echo '网站资料更新中...';
				?>
			</div>
			<div class="author"><?php echo $row['source']; ?> (编辑：<?php echo $row['author']; ?>)</div>
			<!-- 内容区域结束 -->
			<!-- 相关文章开始 -->
			<div class="preNext">
				<div class="line"><strong></strong></div>
				<ul class="text">
				<?php

				//获取上一篇信息
				$r = $dosql->GetOne("SELECT * FROM `#@__infolist` WHERE classid=".$row['classid']." AND orderid<".$row['orderid']." AND delstate='' AND checkinfo=true ORDER BY orderid DESC");
				if($r < 1)
				{
					echo '<li>上一篇：已经没有了</li>';
				}
				else
				{
					if($cfg_isreurl != 'Y')
						$gourl = 'newsshow.php?cid='.$r['classid'].'&id='.$r['id'];
					else
						$gourl = 'newsshow-'.$r['classid'].'-'.$r['id'].'-1.html';

					echo '<li>上一篇：<a href="'.$gourl.'">'.$r['title'].'</a></li>';
				}
				
				//获取下一篇信息
				$r = $dosql->GetOne("SELECT * FROM `#@__infolist` WHERE classid=".$row['classid']." AND orderid>".$row['orderid']." AND delstate='' AND checkinfo=true ORDER BY orderid ASC");
				if($r < 1)
				{
					echo '<li>下一篇：已经没有了</li>';
				}
				else
				{
					if($cfg_isreurl != 'Y')
						$gourl = 'newsshow.php?cid='.$r['classid'].'&id='.$r['id'];
					else
						$gourl = 'newsshow-'.$r['classid'].'-'.$r['id'].'-1.html';

					echo '<li>下一篇：<a href="'.$gourl.'">'.$r['title'].'</a></li>';
				}
				?>
				</ul>
				<ul class="actBox">
					<li id="act-pus"><a href="javascript:;" onclick="<?php $c_uname = isset($_COOKIE['username']) ? AuthCode($_COOKIE['username']) : '';if($c_uname != ''){echo 'AddUserFavorite()';}else{echo 'AddFavorite();';} ?>">收藏</a></li>
					<li id="act-pnt"><a href="javascript:;" onclick="window.print();">打印</a></li>
				</ul>
                <input type="hidden" name="aid" id="aid" value="<?php echo $id; ?>" />
				<input type="hidden" name="molds" id="molds" value="1" />
			</div>
			<!-- 相关文章结束 -->
			<?php
			if($cfg_comment == 'Y')
			{
			?>
			<!-- 评论区域开始 -->
			<ul class="commlist">
				<?php
				$dosql->Execute("SELECT * FROM `#@__usercomment` WHERE molds=1 AND aid=$id AND isshow=1 ORDER BY id DESC");
				while($row = $dosql->GetArray())
				{
					echo '<li><span class="uname">'.$row['uname'].'</span><p>'.$row['body'].'</p><span class="time">'.GetDateTime($row['time']).'</span></li>';
				}
				?>
			</ul>
			<div class="commnum">
				<span>
					<i>
					<?php
					$r = $dosql->GetOne("SELECT COUNT(id) as n FROM `#@__usercomment` WHERE molds=1 AND aid=$id AND isshow=1 ORDER BY id DESC");
					echo $r['n'];
					?>
					</i>
					条评论
				</span>
			</div>
			<div class="commnet">
				<form name="form" id="form" action="" method="post">
					<div class="msg">
						<textarea name="comment" id="comment">说点什么吧...</textarea>
					</div>
					<div class="toolbar">
						<div class="options">
							不想登录？直接点击发布即可作为游客留言。
						</div>
						<button class="button" type="button">发 布</button>
					</div>
				</form>
			</div>
			<!-- 评论区域结束 -->
			<?php
			}
			}
			?>
		</div>
		<!-- 详细区域结束 -->
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