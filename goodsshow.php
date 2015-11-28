<?php
require_once(dirname(__FILE__).'/include/config.inc.php');

//初始化参数检测正确性
$cid = empty($cid) ? 12 : intval($cid);
$tid = empty($tid) ? 0 : intval($tid);
$id  = empty($id)  ? 0 : intval($id);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php echo GetHeader(1,$cid,$id); ?>
<link href="templates/default/style/webstyle.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="templates/default/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/default/js/comment.js"></script>
<script type="text/javascript" src="templates/default/js/cloudzoom.js"></script>
<script type="text/javascript" src="templates/default/js/top.js"></script>
<script type="text/javascript">

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


/* 属性选择 */
function SelAttr(attrobj,attrid,attrvalue)
{
	//取消已选中样式
	$("#attrdiv_"+attrid+" a").attr("class","");

	//选中样式
	$(attrobj).attr("class","selected");

	//选中复制
	$("#attrid_"+attrid).val(attrvalue);
	
}


/* 立即购买 */
function BuyNow()
{
	AddShoppingCart("buy");
}

<?php
$row = $dosql->Execute('SELECT * FROM `#@__goodsattr` WHERE `goodsid`='.$tid." AND `checkinfo`=true");
$ajaxstr = '';
if($dosql->GetTotalRow() > 0)
{
	while($row = $dosql->GetArray())
	{
		$ajaxstr .= ', \'attrid_'.$row['id'].'\':$("#attrid_'.$row['id'].'").val()';
	}
}
?>

/* 加入购物车 */
function AddShoppingCart(a)
{
	$.ajax({
		url  : 'shoppingcart.php?a=addshopingcart',
		type : 'post',
		data : {'typeid':$("#typeid").val(), 'goodsid':$("#goodsid").val(), 'buynum':$("#buynum").val() <?php echo $ajaxstr; ?>},
		dataType:'html',
		//beforeSend:function(){},
		success:function(data){
			if(a == "buy"){
				location.href = "shoppingcart.php?a=buynow";
			} else {
				alert("加入购物车成功！");
			}
		}
	});
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
<div class="subnotice"><strong>网站公告：</strong> <?php echo Info(1); ?> </div>
<!-- /notice--> 
<!-- mainbody-->
<div class="subBody">
	<div class="subTitle"> <span class="catname"><?php echo GetCatName($cid); ?></span> <a href="javascript:history.go(-1);" class="goback">&gt;&gt; 返回</a> <span class="fr">您当前所在位置：<?php echo GetPosStr($cid,$id); ?></span>
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
		<!-- 详细区域开始 -->
		<div class="goodsConts">
			<?php

			//检测文档正确性
			$r = $dosql->GetOne("SELECT * FROM `#@__goods` WHERE id=$id");
			if(@$r)
			{
			//增加一次点击量
			$dosql->ExecNoneQuery("UPDATE `#@__goods` SET hits=hits+1 WHERE id=$id");
			$row = $dosql->GetOne("SELECT * FROM `#@__goods` WHERE id=$id");
			?>
			<h1 class="title"><?php echo $row['title']; ?></h1>
			<div class="goodsarea"> 
				<!-- 组图区域开始-->
				<?php
				//判断显示缩略图或组图
				if(!empty($row['picarr']))
				{
					$picarr = unserialize($row['picarr']);
					$picarrBig = explode(',',$picarr[0]);
				?>
				<div class="fl"> <a id="zoompic" class="cloud-zoom" href="<?php echo $picarrBig[0]; ?>" alt="<?php echo $picarrBig[1]; ?>" rel="adjustX:10, adjustY:0"> <img src="<?php echo $picarrBig[0]; ?>" /></a>
					<ul class="zoomlist">
						<?php
						foreach($picarr as $v)
						{
							$picarrSmall = explode(',',$v);
						?>
						<li><a rel="useZoom: 'zoompic', smallImage: '<?php echo $picarrSmall[0]; ?>'" alt="<?php echo $picarrBig[1]; ?>" class="cloud-zoom-gallery" href="<?php echo $picarrSmall[0]; ?>"> <img src="<?php echo $picarrSmall[0]; ?>" /></a></li>
						<?php
						}
						?>
						<div class="cl"></div>
					</ul>
					<div class="cl"></div>
				</div>
				<?php
				}
				else if(!empty($row['picurl']))
				{
				?>
				<div class="fl"> <a id="zoompic" class="cloud-zoom" href="<?php echo $row['picurl']; ?>" rel="adjustX:10, adjustY:0"> <img src="<?php echo $row['picurl']; ?>" /></a>
					<ul class="zoomlist">
						<li><a rel="useZoom: 'zoompic', smallImage: '<?php echo $row['picurl']; ?>' " class="cloud-zoom-gallery" href="<?php echo $row['picurl']; ?>"> <img src="<?php echo $row['picurl']; ?>" /></a></li>
						<div class="cl"></div>
					</ul>
					<div class="cl"></div>
				</div>
				<?php
				}
				?>
				<!-- 组图区域结束 --> 
				<!-- 商品信息开始 -->
				<div class="fr">
					<ul class="tb-meta">
						<li> <span>市场价</span><strong class="lt"><?php echo $row['marketprice']; ?></strong>元 </li>
						<li> <span>促销价</span><strong class="price"><?php echo $row['salesprice']; ?></strong>元 </li>
						<li> <span>浏览数</span><?php echo $row['hits']; ?> 次</li>
						<li> <span>配　送</span><?php if($row['payfreight']==0){echo '买家承担运费';}else{echo '商家承担运费';} ?></li>
					</ul>
					<div class="tb-skin">
						<p class="tb-note-title"><span>请选择您要的商品信息</span><a href="shoppingcart.php" class="end">结算购物车</a></p>
						<form name="gform" id="gform" method="post">
							<dl class="tb-prop">
							<?php

							//将商品属性id与值组成数组
							$rowattr = String2Array($row['attrstr']);
							$row2 = $dosql->Execute('SELECT * FROM `#@__goodsattr` WHERE `goodsid`='.$row['typeid']." AND `checkinfo`=true");
							if($dosql->GetTotalRow() > 0)
							{
								$i = 0;
								while($row2 = $dosql->GetArray())
								{
							?>
								<dt><?php echo $row2['attrname']; ?>：</dt>
								<dd>
									<?php
								if(!empty($rowattr[$row2['id']]))
								{
									echo '<div id="attrdiv_'.$row2['id'].'">';
									$dfvalue = '';
									$rowattrs = explode('|',$rowattr[$row2['id']]);
									foreach($rowattrs as $k=>$v)
									{
										echo '<a href="javascript:;" onclick="SelAttr(this,'.$row2['id'].',\''.$v.'\');"';
										if($k == 0)
										{
											$dfvalue = $v;
											echo 'class="selected"';
										}
										echo '>'.$v.'</a>';
									}
									echo '<input type="hidden" name="attrid_'.$row2['id'].'" id="attrid_'.$row2['id'].'" value="'.$dfvalue.'" />';
									echo '</div>';
								}
								?>
								</dd>
								<?php
									$i++;
								}
							}
							?>
							</dl>
							<span>数量：</span>
							<input name="buynum" type="text" class="buynum" id="buynum" value="1" />
							&nbsp;&nbsp;件
							<div class="tb-action"> <a href="javascript:;" id="buynow" onclick="BuyNow();" title="点击此按钮，到下一步确认购买信息。">立刻购买</a> <a href="javascript:;" id="addcart" onclick="AddShoppingCart();" title="点击此按钮，将商品加入到购物车。">加入购物车</a></div>
							<input name="typeid" type="hidden" id="typeid" value="<?php echo $tid; ?>" />
							<input name="goodsid" type="hidden" id="goodsid" value="<?php echo $id; ?>" />
						</form>
						<div class="cl"></div>
					</div>
				</div>
				<!-- 商品信息结束 -->
				<div class="cl"></div>
			</div>
			<!-- 内容区域开始 -->
			<ul class="tabs" id="tabs">
				<li id="tabs_title0" onmouseover="Tabs('tabs',this);" class="active"><a href="javascript:;">商品描述</a></li>
				<li id="tabs_title1" onmouseover="Tabs('tabs',this);" class="normal"><a href="javascript:;">宝贝评价</a></li>
			</ul>
			<div id="tabs_content0">
				<?php
				if($row['content'] != '')
					echo GetContPage($row['content']);
				else
					echo '网站资料更新中...';
				?>
				<!-- 相关文章开始 -->
				<div class="preNext">
					<div class="line"><strong></strong></div>
					<ul class="text">
						<?php
	
						//获取上一篇信息
						$r = $dosql->GetOne("SELECT * FROM `#@__goods` WHERE classid=".$row['classid']." AND orderid<".$row['orderid']." AND delstate='' AND checkinfo=true ORDER BY orderid DESC");
						if($r < 1)
						{
							echo '<li>上一篇：已经没有了</li>';
						}
						else
						{
							if($cfg_isreurl != 'Y')
								$gourl = 'goodsshow.php?cid='.$r['classid'].'&tid='.$r['typeid'].'&id='.$r['id'];
							else
								$gourl = 'goodsshow-'.$r['classid'].'-'.$r['typeid'].'-'.$r['id'].'-1.html';
		
							echo '<li>上一篇：<a href="'.$gourl.'">'.$r['title'].'</a></li>';
						}
						
						//获取下一篇信息
						$r = $dosql->GetOne("SELECT * FROM `#@__goods` WHERE classid=".$row['classid']." AND orderid>".$row['orderid']." AND delstate='' AND checkinfo=true ORDER BY orderid ASC");
						if($r < 1)
						{
							echo '<li>下一篇：已经没有了</li>';
						}
						else
						{
							if($cfg_isreurl != 'Y')
								$gourl = 'goodsshow.php?cid='.$r['classid'].'&tid='.$r['typeid'].'&id='.$r['id'];
							else
								$gourl = 'goodsshow-'.$r['classid'].'-'.$r['typeid'].'-'.$r['id'].'-1.html';
		
							echo '<li>下一篇：<a href="'.$gourl.'">'.$r['title'].'</a></li>';
						}
						?>
					</ul>
					<ul class="actBox">
						<li id="act-pus"><a href="javascript:;" onclick="<?php $c_uname = isset($_COOKIE['username']) ? AuthCode($_COOKIE['username']) : '';if($c_uname != ''){echo 'AddUserFavorite()';}else{echo 'AddFavorite();';} ?>">收藏</a></li>
						<li id="act-pnt"><a href="javascript:;" onclick="window.print();">打印</a></li>
					</ul>
                    <input type="hidden" name="aid" id="aid" value="<?php echo $id; ?>" />
					<input type="hidden" name="molds" id="molds" value="4" />
				</div>
				<!-- 相关文章结束 --> 
			</div>
			<div id="tabs_content1" class="undis"> 
				<!-- 评论区域开始 -->
				<?php
				if($cfg_comment == 'Y')
				{
					$dosql->Execute("SELECT * FROM `#@__usercomment` WHERE molds=4 AND aid=$id AND isshow=1 ORDER BY id DESC");
					if($dosql->GetTotalRow() > 0)
					{
						echo '<ul class="commlist">';
						while($row2 = $dosql->GetArray())
						{
							echo '<li><span class="uname">'.$row2['uname'].'</span><p>'.$row2['body'].'</p><span class="time">'.GetDateTime($row2['time']).'</span></li>';
						}
						echo '</ul>';
					}
					else
					{
						echo '该宝贝暂无评价！';
					}
					?>
				<div class="commnum"> <span> <i>
					<?php
						$r = $dosql->GetOne("SELECT COUNT(id) as n FROM `#@__usercomment` WHERE molds=4 AND aid=$id AND isshow=1 ORDER BY id DESC");
						echo $r['n'];
						?>
					</i> 条评论 </span> </div>
				<div class="commnet">
					<form name="form" id="form" action="" method="post">
						<div class="msg">
							<textarea name="comment" id="comment">说点什么吧...</textarea>
						</div>
						<div class="toolbar">
							<div class="options"> 不想登录？直接点击发布即可作为游客留言。 </div>
							<button class="button" type="button">发 布</button>
						</div>
					</form>
				</div>
				<!-- 评论区域结束 -->
				<?php
				}
				?>
			</div>
			<!-- 内容区域结束 -->
			<?php
			}
			?>
		</div>
		<!-- 详细区域结束 --> 
	</div>
	<div class="cl"></div>
</div>
<!-- /mainbody--> 
<!-- footer-->
<?php require_once('footer.php'); ?>
<!-- /footer-->
</body>
</html>