<?php require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('admingroup'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>修改管理组</title>
<link href="templates/style/admin.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/js/checkf.func.js"></script>
<script type="text/javascript" src="templates/js/forms.func.js"></script>
</head>
<body>
<?php
$row = $dosql->GetOne("SELECT * FROM `#@__admingroup` WHERE `id`=$id");
?>
<div class="formHeader"> <span class="title">修改管理组</span> <a href="javascript:location.reload();" class="reload">刷新</a> </div>
<form name="form" id="form" method="post" action="admingroup_save.php" onsubmit="return cfm_admingroup();">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="formTable">
		<tr>
			<td width="25%" height="40" align="right">管理组名称：</td>
			<td width="75%"><input type="text" name="groupname" id="groupname" class="input" value="<?php echo $row['groupname']; ?>" />
				<span class="maroon">*</span><span class="cnote">带<span class="maroon">*</span>号表示为必填项</span></td>
		</tr>
		<tr>
			<td height="118" align="right">管理组描述：</td>
			<td><textarea name="description" id="description" class="textarea"><?php echo $row['description']; ?></textarea></td>
		</tr>
		<tr>
			<td height="40" align="right">默认进入站：</td>
			<td><?php
				$dosql->Execute("SELECT * FROM `#@__site` ORDER BY `id` ASC");
				while($row2 = $dosql->GetArray())
				{
					if($row['groupsite'] == $row2['id'])
						$checked = 'checked="checked"';
					else
						$checked = '';

					echo '<input type="radio" name="groupsite" value="'.$row2['id'].'" '.$checked.' />&nbsp;'.$row2['sitename'].'&nbsp;&nbsp;';
				}
				?><span class="cnote">登录成功后自动进入的站点</span></td>
		</tr>
		<tr>
			<td height="40" align="right">管理组状态：</td>
			<td><input type="radio" name="checkinfo" value="true" <?php if($row['checkinfo'] == 'true') echo 'checked="checked"'; ?> />
				启用&nbsp;
				<input type="radio" name="checkinfo" value="false" <?php if($row['checkinfo'] == 'false') echo 'checked="checked"'; ?> />
				未启用</td>
		</tr>
		<tr>
			<td height="35" align="right">模块权限：</td>
			<td><?php
			if($id != 1)
			{
			?>
				<div class="purviewTitle"><strong>网站系统管理</strong></div>
				<div class="purviewList"> <span>
					<input type="checkbox" name="model[]" value="admin" <?php echo GetModelPriv('admin'); ?> />
					管理员管理</span> <span>
					<input type="checkbox" name="model[]" value="site" <?php echo GetModelPriv('site'); ?> />
					站点配置管理</span> <span>
					<input type="checkbox" name="model[]" value="web_config" <?php echo GetModelPriv('web_config'); ?> />
					网站信息配置</span> <span>
					<input type="checkbox" name="model[]" value="upload_filemgr_sql" <?php echo GetModelPriv('upload_filemgr_sql'); ?> />
					上传文件管理</span> <span>
					<input type="checkbox" name="model[]" value="database_backup" <?php echo GetModelPriv('database_backup'); ?> />
					数据库管理</span></div>
				<div class="purviewTitle"><strong>栏目内容管理</strong></div>
				<div class="purviewList"> <span>
					<input type="checkbox" name="model[]" value="infoclass" <?php echo GetModelPriv('infoclass'); ?> />
					栏目管理</span> <span>
					<input type="checkbox" name="model[]" value="maintype" <?php echo GetModelPriv('maintype'); ?> />
					二级类别管理</span> <span>
					<input type="checkbox" name="model[]" value="info" <?php echo GetModelPriv('info'); ?> />
					单页信息管理</span> <span>
					<input type="checkbox" name="model[]" value="infolist" <?php echo GetModelPriv('infolist'); ?> />
					列表信息管理</span> <span>
					<input type="checkbox" name="model[]" value="infoimg" <?php echo GetModelPriv('infoimg'); ?> />
					图片信息管理</span> <span>
					<input type="checkbox" name="model[]" value="soft" <?php echo GetModelPriv('soft'); ?> />
					软件下载管理</span> <span>
					<input type="checkbox" name="model[]" value="fragment" <?php echo GetModelPriv('fragment'); ?> />
					碎片数据管理</span> <span>
					<input type="checkbox" name="model[]" value="diymodel" <?php echo GetModelPriv('diymodel'); ?> />
					自定义模型</span> <span>
					<input type="checkbox" name="model[]" value="diyfield" <?php echo GetModelPriv('diyfield'); ?> />
					自定义字段</span> <span>
					<input type="checkbox" name="model[]" value="infoflag" <?php echo GetModelPriv('infoflag'); ?> />
					信息标记管理</span> <span>
					<input type="checkbox" name="model[]" value="infosrc" <?php echo GetModelPriv('infosrc'); ?> />
					信息来源管理</span></div>
				<div class="purviewTitle"><strong>模块扩展管理</strong></div>
				<div class="purviewList"> <span>
					<input type="checkbox" name="model[]" value="member" <?php echo GetModelPriv('member'); ?> />
					用户管理</span> <span>
					<input type="checkbox" name="model[]" value="usergroup" <?php echo GetModelPriv('usergroup'); ?> />
					用户组管理</span> <span>
					<input type="checkbox" name="model[]" value="userfavorite" <?php echo GetModelPriv('userfavorite'); ?> />
					用户收藏管理</span> <span>
					<input type="checkbox" name="model[]" value="usercomment" <?php echo GetModelPriv('usercomment'); ?> />
					用户评论管理</span> <span>
					<input type="checkbox" name="model[]" value="message" <?php echo GetModelPriv('message'); ?> />
					留言模块管理</span> <span>
					<input type="checkbox" name="model[]" value="admanage" <?php echo GetModelPriv('admanage'); ?> />
					广告模块管理</span> <span>
					<input type="checkbox" name="model[]" value="adtype" <?php echo GetModelPriv('adtype'); ?> />
					广告位管理</span> <span>
					<input type="checkbox" name="model[]" value="weblink" <?php echo GetModelPriv('weblink'); ?> />
					友情链接管理</span> <span>
					<input type="checkbox" name="model[]" value="weblinktype" <?php echo GetModelPriv('weblinktype'); ?> />
					友情链接分类</span> <span>
					<input type="checkbox" name="model[]" value="job" <?php echo GetModelPriv('job'); ?> />
					招聘模块管理</span> <span>
					<input type="checkbox" name="model[]" value="vote" <?php echo GetModelPriv('vote'); ?> />
					投票模块管理</span> <span>
					<input type="checkbox" name="model[]" value="cascade" <?php echo GetModelPriv('cascade'); ?> />
					级联数据管理</span> </div>
				<div class="purviewTitle"><strong>商品订单管理</strong></div>
				<div class="purviewList"> <span>
					<input type="checkbox" name="model[]" value="goodstype" <?php echo GetModelPriv('goodstype'); ?> />
					商品类别管理</span> <span>
					<input type="checkbox" name="model[]" value="goodsbrand" <?php echo GetModelPriv('goodsbrand'); ?> />
					品牌类型管理</span> <span>
					<input type="checkbox" name="model[]" value="goods" <?php echo GetModelPriv('goods'); ?> />
					商品列表管理</span> <span>
					<input type="checkbox" name="model[]" value="goodsorder" <?php echo GetModelPriv('goodsorder'); ?> />
					商品订单管理</span> <span>
					<input type="checkbox" name="model[]" value="postmode" <?php echo GetModelPriv('postmode'); ?> />
					配送方式管理</span> <span>
					<input type="checkbox" name="model[]" value="paymode" <?php echo GetModelPriv('paymode'); ?> />
					支付方式管理 </span> <span>
					<input type="checkbox" name="model[]" value="getmode" <?php echo GetModelPriv('getmode'); ?> />
					货到方式管理</span> <span>
					<input type="checkbox" name="model[]" value="goodsflag" <?php echo GetModelPriv('goodsflag'); ?> />
					商品信息属性</span></div>
				<div class="purviewTitle"><strong>模板界面管理</strong></div>
				<div class="purviewList"> <span>
					<input type="checkbox" name="model[]" value="nav" <?php echo GetModelPriv('nav'); ?> />
					导航菜单设置</span> <span>
					<input type="checkbox" name="model[]" value="diymenu" <?php echo GetModelPriv('diymenu'); ?> />
					自定义菜单项</span> <span>
					<input type="checkbox" name="model[]" value="mobile" <?php echo GetModelPriv('mobile'); ?> />
					手机网站设置</span> <span>
					<input type="checkbox" name="model[]" value="editfile" <?php echo GetModelPriv('editfile'); ?> />
					默认模板设置</span></div>
				<div class="purviewTitle"><strong>帮助与更新</strong></div>
				<div class="purviewList"> <span>
					<input type="checkbox" name="model[]" value="syscount" <?php echo GetModelPriv('syscount'); ?> />
					数据统计</span> <span>
					<input type="checkbox" name="model[]" value="upload_file" <?php echo GetModelPriv('upload_file'); ?> />
					上传新文件</span> <span>
					<input type="checkbox" name="model[]" value="check_bom" <?php echo GetModelPriv('check_bom'); ?> />
					BOM检查</span> <span>
					<input type="checkbox" name="model[]" value="help" <?php echo GetModelPriv('help'); ?> />
					开发帮助</span></div>
				<div class="purviewSel"><a href="javascript:;" onclick="SelModel(true)">全选</a>&nbsp;&nbsp;<a href="javascript:;" onclick="SelModel(false)">反选</a></div>
				<?php
			}
			else
			{
				echo '<strong class="maroon2">所有权限</strong>';
			}
			?></td>
		</tr>
		<tr class="nb">
			<td height="40" align="right">栏目权限：</td>
			<td><?php
			if($id != 1)
			{
				$dosql->Execute("SELECT * FROM `#@__site` ORDER BY `id` ASC");
				$i = 1;

				while($row2 = $dosql->GetArray())
				{
					echo '<div class="purviewTitle"><strong>'.$row2['sitename'].'</strong></div>';
					Show($row2['id'],$row['id']);
					echo '<div class="purviewSel"><a href="javascript:;" onclick="SelPriv('.$row2['id'].',true)">全选</a>&nbsp;&nbsp;<a href="javascript:;" onclick="SelPriv('.$row2['id'].',false)">反选</a></div>';
					$i++;
				}
			}
			else
			{
				echo '<strong class="maroon2">所有权限</strong>';
			}
			?></td>
		</tr>
		<?php
		if(empty($id) or $id != 1)
		{
		?>
		<tr class="nb">
			<td height="35" align="right">&nbsp;</td>
			<td><ul class="tipsList">
					<li>选中【查看】权限，即有该栏目内容信息列表页查看权限，不选择则栏目内容在管理列表中会被隐藏</li>
					<li>选中【添加】权限，即有添加下级栏目与栏目内容权限</li>
					<li>选中【修改】权限，即有信息列表管理页审核权限与栏目和栏目内容修改权限</li>
					<li>选中【删除】权限，即有该栏目与栏目内容删除权限</li>
				</ul></td>
		</tr>
		<?php
		}
		?>
	</table>
	<div class="formSubBtn">
		<input type="submit" class="submit" value="提交" />
		<input type="button" class="back" value="返回" onclick="history.go(-1);" />
		<input type="hidden" name="action" id="action" value="update" />
		<input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
	</div>
</form>
<?php

function Show($siteid=1, $groupid=0, $id=0, $i=0)
{
	global $dosql;

	$dosql->Execute("SELECT * FROM `#@__infoclass` WHERE `siteid`=$siteid AND `parentid`=$id ORDER BY `orderid` ASC", $id);
	$i++;

	while($row = $dosql->GetArray($id))
	{
		switch($row['infotype'])
		{
			case 0:
				$addurl   = 'info_update.php?id='.$row['id'];
				$infotype = ' <i title="栏目属于[单页]类型">[单页]</i>';
				break;
			case 1:
				$addurl   = 'infolist_add.php?cid='.$row['id'];
				$infotype = ' <i title="栏目属于[列表]类型">[列表]<i>';
				break;
			case 2:
				$addurl   = 'infoimg_add.php?cid='.$row['id'];
				$infotype = ' <i title="栏目属于[图片]类型">[图片]<i>';
				break;
			case 3:
				$addurl   = 'soft_add.php?cid='.$row['id'];
				$infotype = ' <i title="栏目属于[下载]类型">[下载]<i>';
				break;
			case 4:
				$addurl   = 'soft_add.php?cid='.$row['id'];
				$infotype = ' <i title="栏目属于[商品]类型">[商品]<i>';
				break;
			default:
				$r = $dosql->GetOne("SELECT * FROM `#@__diymodel` WHERE `id`=".$row['infotype']);
				if(isset($r) && is_array($r))
				{
					$addurl   = 'modeldata_add.php?m='.$r['modelname'].'&cid='.$row['id'];
					$infotype = ' <i title="栏目属于['.$r['modeltitle'].']类型">['.$r['modeltitle'].']</i>';
				}
				else
				{
					$addurl   = 'javascript:;';
					$infotype = ' 没有获取到类型';
				}
		}


		//设置$classname
			$classname = '';

		//设置空格
		for($n = 1; $n < $i; $n++)
			$classname .= '&nbsp;&nbsp;';

		//设置折叠
		if($row['parentid'] == '0')
			$classname .= '<span class="minusSign" id="rowid_'.$row['id'].'" onclick="DisplayRows('.$row['id'].');">';
		else
			$classname .= '<span class="subType">';

		$classname .= $row['classname'].'</span>';

		//信息类型
		$classname .= '<span class="infoTypeTxt">'.$infotype.'</span>';


		//选择权限
		$cktop = '';
		$r_list = $dosql->GetOne("SELECT * FROM `#@__adminprivacy` WHERE `groupid`=$groupid AND `siteid`=$siteid AND `model`='category' AND `classid`=".$row['id']." AND `action`='list'");
		if(isset($r_list) && is_array($r_list))
		{
			$cklist = 'checked="checked"';
			$cktop = 'checked="checked"';
		}
		else
		{
			$cklist = '';
		}
		
		$r_add = $dosql->GetOne("SELECT * FROM `#@__adminprivacy` WHERE `groupid`=$groupid AND `siteid`=$siteid AND `model`='category' AND `classid`=".$row['id']." AND `action`='add'");
		if(isset($r_add) && is_array($r_add))
		{
			$ckadd = 'checked="checked"';
			$cktop = 'checked="checked"';
		}
		else
		{
			$ckadd = '';
		}
		
		$r_update = $dosql->GetOne("SELECT * FROM `#@__adminprivacy` WHERE `groupid`=$groupid AND `siteid`=$siteid AND `model`='category' AND `classid`=".$row['id']." AND `action`='update'");
		if(isset($r_update) && is_array($r_update))
		{
			$ckupdate = 'checked="checked"';
			$cktop = 'checked="checked"';
		}
		else
		{
			$ckupdate = '';
		}
		
		$r_del = $dosql->GetOne("SELECT * FROM `#@__adminprivacy` WHERE `groupid`=$groupid AND `siteid`=$siteid AND `model`='category' AND `classid`=".$row['id']." AND `action`='del'");
		if(isset($r_del) && is_array($r_del))
		{
			$ckdel = 'checked="checked"';
			$cktop = 'checked="checked"';
		}
		else
		{
			$ckdel = '';
		}
?>
<div rel="rowpid_<?php echo GetTopID($row['parentstr']); ?>">
	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="dataTable">
		<tr align="left" class="dataTr">
			<td width="3%" height="32"><input type="checkbox" name="siteid[<?php echo $siteid; ?>]" value="<?php echo $siteid; ?>" onclick="SelRole(<?php echo $siteid; ?>,<?php echo $row['id']; ?>,this);" <?php echo $cktop; ?> /></td>
			<td align="left"><?php echo $classname; ?></td>
			<td width="30%" class="privTxt"><span>
				<input type="checkbox" name="priv[<?php echo $siteid; ?>][<?php echo $row['id']; ?>][]" value="list" <?php echo $cklist; ?> />
				查看</span> <span>
				<input type="checkbox" name="priv[<?php echo $siteid; ?>][<?php echo $row['id']; ?>][]" value="add" <?php echo $ckadd; ?> />
				添加</span> <span>
				<input type="checkbox" name="priv[<?php echo $siteid; ?>][<?php echo $row['id']; ?>][]" value="update" <?php echo $ckupdate; ?> />
				修改</span> <span>
				<input type="checkbox" name="priv[<?php echo $siteid; ?>][<?php echo $row['id']; ?>][]" value="del" <?php echo $ckdel; ?> />
				删除</span></td>
		</tr>
	</table>
</div>
<?php
		Show($siteid, $groupid, $row['id'], $i+2);
	}
}


function GetModelPriv($m='')
{
	global $dosql,$id;

	$r = $dosql->GetOne("SELECT * FROM `#@__adminprivacy` WHERE `groupid`=$id AND `model`='$m'");
	if(isset($r) && is_array($r))
	{
		return 'checked="checked"'; 
	}
}
?>
</body>
</html>