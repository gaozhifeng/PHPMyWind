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

			//栏目名称管理
			$dosql->Execute("SELECT * FROM `#@__adminprivacy` WHERE `siteid`='$cfg_siteid' AND `groupid`='$cfg_adminlevel' AND `model`='category' AND (`action`='list' OR `action`='add') GROUP BY `classid` ORDER BY `classid` ASC");

			if($dosql->GetTotalRow() < 1)
			{
				echo '<div class="tc" style="width:180px;">~(>_<)~<br />您暂无任何可操作栏目</div>';
			}
			else
			{
				$i = 1;
				while($row = $dosql->GetArray())
				{

					$dosql->Execute("SELECT * FROM `#@__infoclass` WHERE `id`=".$row['classid'],$i);
					while($row2 = $dosql->GetArray($i))
					{
						if($i == 1)
							echo '<div class="menubox"><div class="title on" onclick="DisplayMenu(\'leftmenu'.$i.'\');" title="点击切换显示或隐藏">'.$row2['classname'].'</div><div id="leftmenu'.$i.'">';
						else
							echo '<div class="menubox"><div class="title" onclick="DisplayMenu(\'leftmenu'.$i.'\');" title="点击切换显示或隐藏">'.$row2['classname'].'</div><div id="leftmenu'.$i.'" style="display:none">';


						switch($row2['infotype'])
						{
							case 0:
								echo '<a href="info_update.php?id='.$row2['id'].'" target="main">'.$row2['classname'].'</a>';
								break;
							case 1:
								echo '<div class="hr_1"></div>';
								if(IsCategoryPriv($row2['id'],'list',$cfg_siteid,0))
									echo '<a href="infolist.php?cid='.$row2['id'].'" target="main">'.$row2['classname'].'管理</a>';
								if(IsCategoryPriv($row2['id'],'add',$cfg_siteid,0))
									echo '<a href="infolist_add.php?cid='.$row2['id'].'" target="main">'.$row2['classname'].'添加</a>';
								break;
							case 2:
								echo '<div class="hr_1"></div>';
								if(IsCategoryPriv($row2['id'],'list',$cfg_siteid,0))
									echo '<a href="infoimg.php?cid='.$row2['id'].'" target="main">'.$row2['classname'].'管理</a>';
								if(IsCategoryPriv($row2['id'],'add',$cfg_siteid,0))
									echo '<a href="infoimg_add.php?cid='.$row2['id'].'" target="main">'.$row2['classname'].'添加</a>';
								break;
							case 3:
								echo '<div class="hr_1"></div>';
								if(IsCategoryPriv($row2['id'],'list',$cfg_siteid,0))
									echo '<a href="soft.php?cid='.$row2['id'].'" target="main">'.$row2['classname'].'管理</a>';
								if(IsCategoryPriv($row2['id'],'add',$cfg_siteid,0))
									echo '<a href="soft_add.php?cid='.$row2['id'].'" target="main">'.$row2['classname'].'添加</a>';
								break;
							case 4:
								echo '<div class="hr_1"></div>';
								if(IsCategoryPriv($row2['id'],'list',$cfg_siteid,0))
									echo '<a href="goods.php?cid='.$row2['id'].'" target="main">'.$row2['classname'].'管理</a>';
								if(IsCategoryPriv($row2['id'],'add',$cfg_siteid,0))
									echo '<a href="goods_add.php?cid='.$row2['id'].'" target="main">'.$row2['classname'].'添加</a>';
								break;
							default:
								$r = $dosql->GetOne("SELECT * FROM `#@__diymodel` WHERE `id`=".$row2['infotype']);
								if(isset($r) && is_array($r))
								{
									echo '<div class="hr_1"></div>';
									if(IsCategoryPriv($row2['id'],'list',$cfg_siteid))
										echo '<a href="modeldata.php?m='.$r['modelname'].'" target="main">'.$row2['classname'].'管理</a>';
									if(IsCategoryPriv($row2['id'],'add',$cfg_siteid))
										echo '<a href="modeldata_add.php?m='.$r['modelname'].'&cid='.$row2['id'].'" target="main">'.$row2['classname'].'添加</a>';
								}
						}
						echo '</div></div><div class="hr_5"></div>';
					}

					$i++;
				}
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
	<a href="left_menu_user.php" title="切换到功能菜单" class="name"></a>
</div>
</body>
</html>
