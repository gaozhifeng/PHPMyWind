<?php
require_once(dirname(__FILE__).'/include/config.inc.php');

//初始化参数检测正确性
$cid = empty($cid) ? 11 : intval($cid);
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
	$(".softConts .pic img").LoadImage({width:80,height:80});
	
	jQuery('.lightbox').lightbox();
	$(".picarr .picture img").LoadImage({width:530,height:350});
	$(".picarr .preview img").LoadImage({width:58,height:45});
	$(".small").click(function(){$("#textarea").css('font-size','12px');});
	$(".big").click(function(){$("#textarea").css('font-size','14px');});
});

/* 选项卡切换 */
function Tabs(tabobj, obj)
{
	var tablist = $("#"+tabobj+" li");
	for(i=0; i<tablist.size(); i++){
		if(tablist[i].id == obj.id){
			$("#"+tabobj+"_title"+i).attr("class","active"); 
			$("#"+tabobj+"_content"+i).show(); 
		} else {
			$("#"+tabobj+"_title"+i).attr("class","normal"); 
			$("#"+tabobj+"_content"+i).hide(); 
		}
	} 
}
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
		<div class="softConts">
			<?php

			//检测文档正确性
			$r = $dosql->GetOne("SELECT * FROM `#@__soft` WHERE id=$id");
			if(@$r)
			{
				//增加一次点击量
				$dosql->ExecNoneQuery("UPDATE `#@__soft` SET hits=hits+1 WHERE id=$id");
				$row = $dosql->GetOne("SELECT * FROM `#@__soft` WHERE id=$id");

			?>
			<!-- 标题区域开始 -->
			<div class="view">
				<div class="pic"><img src="<?php echo $row['picurl']; ?>" alt="软件图标" /></div>
			</div>
			<div class="title"> <span class="softname"><?php echo $row['title']; ?></span> <span class="green">【<?php echo $row['accredit']; ?>】</span> </div>
			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="info">
				<tr>
					<td width="40%">文件类型：<span><?php echo $row['filetype']; ?></span></td>
					<td>更新时间：<span><?php echo GetDateMk($row['posttime']); ?></span></td>
				</tr>
				<tr>
					<td>软件类型：<span><?php echo $row['softtype']; ?></span></td>
					<td>界面语言：<span><?php echo $row['language']; ?></span></td>
				</tr>
				<tr>
					<td>软件大小：<span><?php echo $row['softsize']; ?><?php echo $row['unit']; ?></span></td>
					<td>运行环境：<span><?php echo $row['runos']; ?></span></td>
				</tr>
				<tr>
					<td>官方网站：<span><?php echo $row['website']; ?></span></td>
					<td>演示地址：<span><a href="<?php echo $row['demourl']; ?>" target="_blank">点击访问</a></span></td>
				</tr>
				<tr>
					<td height="80" colspan="2"><a href="<?php echo $row['dlurl']; ?>" target="_blank"><img src="templates/default/images/download_s.png" /></a></td>
				</tr>
			</table>
			<!-- 标题区域结束 -->
			<!-- 内容区域开始 -->
			<ul class="tabs" id="tabs">
				<li id="tabs_title0" onmouseover="Tabs('tabs',this);" class="active"><a href="javascript:;">软件简介</a></li>
				<li id="tabs_title1" onmouseover="Tabs('tabs',this);" class="normal"><a href="javascript:;">软件截图</a></li>
			</ul>
			<div id="tabs_content0">
				<?php
				if($row['content'] != '')
				{
					echo GetContPage($row['content']);
				}
				else
				{
					echo '网站资料更新中...';
				}
				?>
			</div>
			<div id="tabs_content1" class="undis">
				<?php
	
				//判断显示缩略图或组图
				if(empty($row['picarr']))
				{
				?>
				暂无软件截图
				<?php
				}
				else
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
			</div>
			<!-- 内容区域结束 -->
			<!-- 相关文章开始 -->
			<div class="preNext">
				<div class="line"><strong></strong></div>
				<ul class="text">
				<?php

				//获取上一篇信息
				$r = $dosql->GetOne("SELECT * FROM `#@__soft` WHERE classid=".$row['classid']." AND orderid<".$row['orderid']." AND delstate='' AND checkinfo=true ORDER BY orderid DESC");
				if($r < 1)
				{
					echo '<li>上一篇：已经没有了</li>';
				}
				else
				{
					if($cfg_isreurl != 'Y')
						$gourl = 'softshow.php?cid='.$r['classid'].'&id='.$r['id'];
					else
						$gourl = 'softshow-'.$r['classid'].'-'.$r['id'].'-1.html';

					echo '<li>上一篇：<a href="'.$gourl.'">'.$r['title'].'</a></li>';
				}
				
				//获取下一篇信息
				$r = $dosql->GetOne("SELECT * FROM `#@__soft` WHERE classid=".$row['classid']." AND orderid>".$row['orderid']." AND delstate='' AND checkinfo=true ORDER BY orderid ASC");
				if($r < 1)
				{
					echo '<li>下一篇：已经没有了</li>';
				}
				else
				{
					if($cfg_isreurl != 'Y')
						$gourl = 'softshow.php?cid='.$r['classid'].'&id='.$r['id'];
					else
						$gourl = 'softshow-'.$r['classid'].'-'.$r['id'].'-1.html';

					echo '<li>下一篇：<a href="'.$gourl.'">'.$r['title'].'</a></li>';
				}
				?>
				</ul>
				<ul class="actBox">
					<li id="act-pus"><a href="javascript:;" onclick="<?php $c_uname = isset($_COOKIE['username']) ? AuthCode($_COOKIE['username']) : '';if($c_uname != ''){echo 'AddUserFavorite()';}else{echo 'AddFavorite();';} ?>">收藏</a></li>
					<li id="act-pnt"><a href="javascript:;" onclick="window.print();">打印</a></li>
				</ul>
			</div>
			<!-- 相关文章结束 -->
			<?php
			if($cfg_comment == 'Y')
			{
			?>
			<!-- 评论区域开始 -->
			<ul class="commlist">
				<?php
				$dosql->Execute("SELECT * FROM `#@__usercomment` WHERE molds=3 AND aid=$id AND isshow=1 ORDER BY id DESC");
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
					$r = $dosql->GetOne("SELECT COUNT(id) as n FROM `#@__usercomment` WHERE molds=3 AND aid=$id AND isshow=1 ORDER BY id DESC");
					echo $r['n'];
					?>
					</i>
					条评论
				</span>
                <input type="hidden" name="aid" id="aid" value="<?php echo $id; ?>" />
				<input type="hidden" name="molds" id="molds" value="3" />
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
</div>
<div class="cl"></div>
</div>
<!-- /mainbody--> 
<!-- footer-->
<?php require_once('footer.php'); ?>
<!-- /footer-->
</body>
</html>