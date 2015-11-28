<?php require_once(dirname(__FILE__).'/inc/config.inc.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>左侧菜单</title>
<link href="templates/style/menu.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/js/tinyscrollbar.js"></script>
<script type="text/javascript" src="templates/js/leftmenu.js"></script>
<style type="text/css">
.menubox a.infoattr{top:42px;}
.menubox a.infosrc{top:69px;}
.menubox a.usertype{top:42px;}
.menubox a.adtype{top:69px;}
.menubox a.weblinktype{top:96px;}
.menubox a.goodsinfoattr{top:42px;}
</style>
</head>
<body>
<div class="quickBtn"> <span class="quickBtnLeft"><a href="infolist_add.php" target="main">添列表</a></span> <span class="quickBtnRight"><a href="infoimg_add.php" target="main">添图片</a></span> </div>

<div class="tGradient"></div>
<div id="scrollmenu">
	<div class="scrollbar">
		<div class="track">
			<div class="thumb">
				<div class="end"></div>
			</div>
		</div>
	</div>
	<div class="viewport">
		<div class="overview">
			<!--scrollbar start-->
			<?php

			//获取管理员模块权限
			$dosql->Execute("SELECT `model` FROM `#@__adminprivacy` WHERE `groupid`=".$cfg_adminlevel." AND `model`<>'category'");
			$modelPriv = array();
			while($row = $dosql->GetArray())
			{
				$modelPriv[] = $row['model'];
			}

			if(empty($modelPriv))
			{
				echo '<div class="tc" style="width:180px;">~(>_<)~<br />您暂无任何可操作权限</div>';
			}
			else
			{
				//构成管理菜单数组
				$leftMenu01_Str = '';
				$leftMenu01_Arr = array('admin'=>'<a href="admin.php" target="main">管理员管理</a>',
									'site'=>'<a href="site.php" target="main">站点配置管理</a>',
									'web_config'=>'<a href="web_config.php" target="main">网站信息配置</a>',
									'upload_filemgr_sql'=>'<a href="upload_filemgr_sql.php" target="main">上传文件管理</a>',
									'database_backup'=>'<a href="database_backup.php" target="main">数据库管理</a>');

				$leftMenu02_Str = '';
				$leftMenu02_Arr = array('infoclass'=>'<a href="infoclass.php" target="main">栏目管理</a>',
									'maintype'=>'<a href="maintype.php" target="main">二级类别管理</a>',
									'info'=>'<a href="info.php" target="main">单页信息管理</a>',
									'infolist'=>'<a href="infolist.php" target="main">列表信息管理</a>',
									'infoimg'=>'<a href="infoimg.php" target="main">图片信息管理</a>',
									'soft'=>'<a href="soft.php" target="main">软件下载管理</a>',
									'fragment'=>'<a href="fragment.php" target="main">碎片数据管理</a>',
									'diymenu'=>'<a href="diymenu.php" target="main">自定义菜单项</a>',
									'diymodel'=>'<a href="diymodel.php" target="main">自定义模型</a>',
									'diyfield'=>'<a href="diyfield.php" target="main">自定义字段</a>',
									'infoflag'=>'<a href="infoflag.php" target="main" title="信息标记管理" class="infoattr"></a>',
									'infosrc'=>'<a href="infosrc.php" target="main" title="信息来源管理" class="infosrc"></a>');

				$leftMenu03_Str = '';
				$leftMenu03_Arr = array('member'=>'<a href="member.php" target="main">用户管理</a>',
									'userfavorite'=>'<a href="userfavorite.php" target="main">用户收藏管理</a>',
									'usercomment'=>'<a href="usercomment.php" target="main">用户评论管理</a>',
									'message'=>'<a href="message.php" target="main">留言模块管理</a>',
									'admanage'=>'<a href="admanage.php" target="main">广告模块管理</a>',
									'weblink'=>'<a href="weblink.php" target="main">友情链接管理</a>',
									'job'=>' <a href="job.php" target="main">招聘模块管理</a>',
									'vote'=>'<a href="vote.php" target="main">投票模块管理</a>',
									'cascade'=>'<a href="cascade.php" target="main">级联数据管理</a>',
									'usergroup'=>'<a href="usergroup.php" target="main" title="用户组管理" class="usertype"></a>',
									'adtype'=>'<a href="adtype.php" target="main" title="广告位管理" class="adtype"></a>',
									'weblinktype'=>'<a href="weblinktype.php" target="main" title="友情链接类别" class="weblinktype"></a>');

				$leftMenu04_Str = '';
				$leftMenu04_Arr = array('goodstype'=>'<a href="goodstype.php" target="main">商品类别管理</a>',
									'goodsbrand'=>'<a href="goodsbrand.php" target="main">品牌类型管理</a>',
									'goods'=>'<a href="goods.php" target="main">商品列表管理</a>',
									'goodsorder'=>'<a href="goodsorder.php" target="main">商品订单管理</a>',
									'postmode'=>'<a href="postmode.php" target="main">配送方式管理</a>',
									'paymode'=>'<a href="paymode.php" target="main">支付方式管理</a>',
									'getmode'=>'<a href="getmode.php" target="main">货到方式管理</a>',
									'goodsflag'=>'<a href="goodsflag.php" target="main" title="商品信息属性管理" class="goodsinfoattr"></a>');

				$leftMenu05_Str = '';
				$leftMenu05_Arr = array('mobile'=>'<a href="mobile.php" target="main">手机网站设置</a>',
									'nav'=>'<a href="nav.php" target="main">导航菜单设置</a>',
									'editfile'=>'<a href="editfile.php" target="main">默认模板管理</a>');

				$leftMenu06_Str = '';
				$leftMenu06_Arr = array('syscount'=>'<a href="syscount.php" target="main">数据统计</a>',
									'upload_file'=>'<a href="upload_file.php" target="main">上传新文件</a>',
									'check_bom'=>'<a href="check_bom.php" target="main">BOM检查</a>',
									'help'=>'<a href="help.php" target="main">开发帮助</a>');



				//比对权限，构成字符串
				foreach($leftMenu01_Arr as $k=>$v)
				{
					if(in_array($k,$modelPriv))
					{
						$leftMenu01_Str .= $v;
					}
				}

				foreach($leftMenu02_Arr as $k=>$v)
				{
					if(in_array($k,$modelPriv))
					{
						$leftMenu02_Str .= $v;
					}
				}

				foreach($leftMenu03_Arr as $k=>$v)
				{
					if(in_array($k,$modelPriv))
					{
						$leftMenu03_Str .= $v;
					}
				}

				foreach($leftMenu04_Arr as $k=>$v)
				{
					if(in_array($k,$modelPriv))
					{
						$leftMenu04_Str .= $v;
					}
				}

				foreach($leftMenu05_Arr as $k=>$v)
				{
					if(in_array($k,$modelPriv))
					{
						$leftMenu05_Str .= $v;
					}
				}

				foreach($leftMenu06_Arr as $k=>$v)
				{
					if(in_array($k,$modelPriv))
					{
						$leftMenu06_Str .= $v;
					}
				}


				if($leftMenu01_Str != '')
				{
					echo '<div class="menubox"><div class="title on" id="t1" onclick="DisplayMenu(\'leftmenu01\');" title="点击切换显示或隐藏"> 网站系统管理 </div><div id="leftmenu01">';
					echo $leftMenu01_Str;
					echo '</div></div><div class="hr_5"></div>';
				}

				if($leftMenu02_Str != '')
				{
					//如果01菜单为空，初始化02菜单
					if($leftMenu01_Str != '')
					{
						echo '<div class="menubox"><div class="title" onclick="DisplayMenu(\'leftmenu02\');" title="点击切换显示或隐藏"> 栏目内容管理 </div><div id="leftmenu02" style="display:none">';
					}
					else
					{
						echo '<div class="menubox"><div class="title on" id="t1" onclick="DisplayMenu(\'leftmenu02\');" title="点击切换显示或隐藏"> 栏目内容管理 </div><div id="leftmenu02">';
					}

					echo $leftMenu02_Str;
					echo '</div></div><div class="hr_5"></div>';
				}

				if($leftMenu03_Str != '')
				{
					//如果02菜单为空，初始化03菜单
					if($leftMenu01_Str != '' or
					   $leftMenu02_Str != '')
					{
						echo '<div class="menubox"><div class="title" onclick="DisplayMenu(\'leftmenu03\');" title="点击切换显示或隐藏"> 模块扩展管理 </div><div id="leftmenu03" style="display:none">';
					}
					else
					{
						echo '<div class="menubox"><div class="title on" id="t1" onclick="DisplayMenu(\'leftmenu03\');" title="点击切换显示或隐藏"> 模块扩展管理 </div><div id="leftmenu03">';
					}

					echo $leftMenu03_Str;
					echo '</div></div><div class="hr_5"></div>';
				}

				if($leftMenu04_Str != '')
				{
					//如果03菜单为空，初始化04菜单
					if($leftMenu01_Str != '' or
					   $leftMenu02_Str != '' or
					   $leftMenu03_Str != '')
					{
						echo '<div class="menubox"><div class="title" onclick="DisplayMenu(\'leftmenu04\');" title="点击切换显示或隐藏"> 商品订单管理 </div><div id="leftmenu04" style="display:none">';
					}
					else
					{
						echo '<div class="menubox"><div class="title on" id="t1" onclick="DisplayMenu(\'leftmenu04\');" title="点击切换显示或隐藏"> 商品订单管理 </div><div id="leftmenu04">';
					}

					echo $leftMenu04_Str;
					echo '</div></div><div class="hr_5"></div>';
				}

				if($leftMenu05_Str != '')
				{
					//如果04菜单为空，初始化05菜单
					if($leftMenu01_Str != '' or
					   $leftMenu02_Str != '' or
					   $leftMenu03_Str != '' or
					   $leftMenu04_Str != '')
					{
						echo '<div class="menubox"><div class="title" onclick="DisplayMenu(\'leftmenu05\');" title="点击切换显示或隐藏"> 界面模板管理 </div><div id="leftmenu05" style="display:none">';
					}
					else
					{
						echo '<div class="menubox"><div class="title on" id="t1" onclick="DisplayMenu(\'leftmenu05\');" title="点击切换显示或隐藏"> 界面模板管理 </div><div id="leftmenu05">';
					}

					echo $leftMenu05_Str;
					echo '</div></div><div class="hr_5"></div>';
				}

				if($leftMenu06_Str != '')
				{
					//如果05菜单为空，初始化06菜单
					if($leftMenu01_Str != '' or
					   $leftMenu02_Str != '' or
					   $leftMenu03_Str != '' or
					   $leftMenu04_Str != '' or
					   $leftMenu05_Str != '')
					{
						echo '<div class="menubox"><div class="title" onclick="DisplayMenu(\'leftmenu06\');" title="点击切换显示或隐藏"> 帮助与更新 </div><div id="leftmenu06" style="display:none">';
					}
					else
					{
						echo '<div class="menubox"><div class="title on" id="t1" onclick="DisplayMenu(\'leftmenu06\');" title="点击切换显示或隐藏">帮助与更新 </div><div id="leftmenu06">';
					}

					echo $leftMenu06_Str;
					echo '</div></div><div class="hr_5"></div>';
				}
			}

			//显示自定义菜单
			$dosql->Execute("SELECT * FROM `#@__diymenu` WHERE siteid='$cfg_siteid' AND checkinfo='true' AND parentid=0 ORDER BY orderid ASC");
			$i = 100;
			while($row = $dosql->GetArray())
			{
			?>
			<div class="menubox">
				<div class="title" onclick="DisplayMenu('leftmenu<?php echo $i; ?>');" title="点击切换显示或隐藏"><?php echo $row['classname']; ?></div>
				<div id="leftmenu<?php echo $i; ?>" class="undis">
				<?php
				$dosql->Execute("SELECT * FROM `#@__diymenu` WHERE checkinfo='true' and parentid=".$row['id']." ORDER BY orderid ASC",$i);
				while($row2 = $dosql->GetArray($i))
				{
					echo '<a href="'.$row2['linkurl'].'" target="main">'.$row2['classname'].'</a>';
				}
				?>
				</div>
			</div>
			<div class="hr_5"></div>
			<?php
				$i++;
			}
			?>
			<!--scrollbar end-->
		</div>
	</div>
</div>
<div class="bGradient"></div>
<div class="copyright"> © 2015 <a href="http://phpMyWind.com/" target="_blank">phpMyWind.com</a><br />
	All Rights Reserved. </div>
<div class="tabMenu">
	<a href="left_menu_user_name.php" title="切换到名称菜单" class="model"></a>
</div>
<?php
function GetModelPriv($m='')
{
	global $dosql,$id;

	$r = $dosql->GetOne("SELECT * FROM `#@__adminprivacy` WHERE `groupid`=$id AND `model`='$m'");
	if(isset($r) && is_array($r))
	{
		return TRUE;
	}
}
?>
</body>
</html>
