<?php
require_once(dirname(__FILE__).'/include/config.inc.php');

//初始化参数检测正确性
$cid = empty($cid) ? 12 : intval($cid);
$tid = empty($tid) ? 0 : intval($tid);
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
    $(".goods_list li img").LoadImage({width:220,height:220});
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
	<div class="OneOfTwos">
		<ul class="goodstype">
			<?php
			if($cfg_isreurl=='Y')
				$gourl = 'goods-'.$cid.'-'.$tid.'-1.html';
			else
				$gourl = 'goods.php';
			?>
			<li class="alltype"><a href="<?php echo $gourl; ?>">全部商品类型</a></li>
			<?php
			$dosql->Execute("SELECT * FROM `#@__goodstype` WHERE `parentid`=0 AND `checkinfo`=true ORDER BY orderid ASC");
			while($row = $dosql->GetArray())
			{
				echo '<li><h3>'.$row['classname'].'</h3><ul>';
	
				$dosql->Execute("SELECT * FROM `#@__goodstype` WHERE `parentid`=".$row['id']." AND `checkinfo`=true ORDER BY orderid ASC",$row['id']);
				while($row2 = $dosql->GetArray($row['id']))
				{
					if($cfg_isreurl=='Y')
						$gourl = 'goods-'.$cid.'-'.$row2['id'].'-1.html';
					else
						$gourl = 'goods.php?cid='.$cid.'&tid='.$row2['id'];

					echo '<li><a href="'.$gourl.'"';
					if($tid == $row2['id']) echo 'class="on"';
					echo '>'.$row2['classname'].'</a></li>';
				}
				
				echo '</ul></li>';
			}
			?>
		</ul>
	</div>
	<div class="TwoOfTwos">
		<div class="subCont">
			<ul class="goods_list">
				<?php
				
				if(!empty($tid))
					$sql = "SELECT * FROM `#@__goods` WHERE (classid=$cid OR parentstr LIKE '%,$cid,%') AND `typeid`=$tid AND delstate='' AND checkinfo=true ORDER BY orderid DESC";
				else
					$sql = "SELECT * FROM `#@__goods` WHERE (classid=$cid OR parentstr LIKE '%,$cid,%') AND delstate='' AND checkinfo=true ORDER BY orderid DESC";
				$dopage->GetPage($sql,9);
				while($row = $dosql->GetArray())
				{
					if($row['picurl'] != '') $picurl = $row['picurl'];
					else $picurl = 'templates/default/images/nofoundpic.gif';
					
					if($row['linkurl']=='' and $cfg_isreurl!='Y') $gourl = 'goodsshow.php?cid='.$row['classid'].'&tid='.$row['typeid'].'&id='.$row['id'];
					else if($cfg_isreurl=='Y') $gourl = 'goodsshow-'.$row['classid'].'-'.$row['typeid'].'-'.$row['id'].'-1.html';
					else $gourl = $row['linkurl'];
				?>
				<li> <a href="<?php echo $gourl; ?>" class="img"><img src="<?php echo $picurl; ?>" /></a>
					<div class="info"><a href="<?php echo $gourl; ?>"><?php echo ReStrLen($row['title'],20); ?></a>
						<p><span class="fl">价格 <i class="lt"><?php echo $row['marketprice']; ?></i><i><?php echo $row['salesprice']; ?></i></span><span class="fr">浏览<i class="hits"><?php echo $row['hits']; ?></i></span></p>
					</div>
				</li>
				<?php
				}
				?>
			</ul>
			<div class="cl"></div>
			<?php echo $dopage->GetList(); ?> </div>
	</div>
	<div class="cl"></div>
</div>
<!-- /mainbody--> 
<!-- footer-->
<?php require_once('footer.php'); ?>
<!-- /footer-->
</body>
</html>