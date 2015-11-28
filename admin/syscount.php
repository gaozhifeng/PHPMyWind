<?php require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('syscount'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>数据统计</title>
<link href="templates/style/admin.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/js/forms.func.js"></script>
</head>
<body>
<div class="topToolbar"> <span class="title">数据统计</span> <a href="javascript:location.reload();" class="reload">刷新</a></div>
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="dataTable">
	<tr align="left">
		<td height="45" colspan="3" class="firstCol"><strong class="sysCountNum">网站系统管理</strong></td>
	</tr>
	<tr align="left" class="head">
		<td width="45%" height="36" class="firstCol">模块名称</td>
		<td width="10%">数据量</td>
		<td width="45%" class="action endCol">最后操作</td>
	</tr>
	<tr align="left" class="dataTr">
		<td height="36" class="firstCol">管理员管理</td>
		<td><?php echo $dosql->GetTableRow('#@__admin'); ?></td>
		<td class="number endCol"><?php echo GetLastEventTime('admin'); ?></td>
	</tr>
	<tr align="left" class="dataTr">
		<td height="36" class="firstCol">管理组管理</td>
		<td><?php echo $dosql->GetTableRow('#@__admingroup'); ?></td>
		<td class="number endCol"><?php echo GetLastEventTime('admingroup'); ?></td>
	</tr>
	<tr align="left" class="dataTr">
		<td height="36" class="firstCol">站点配置管理</td>
		<td><?php echo $dosql->GetTableRow('#@__site'); ?></td>
		<td class="number endCol"><?php echo GetLastEventTime('site'); ?></td>
	</tr>
	<tr align="left" class="dataTr">
		<td height="36" class="firstCol">网站配置管理</td>
		<td><?php echo $dosql->GetTableRow('#@__webconfig','','varname'); ?></td>
		<td class="number endCol"><?php echo GetLastEventTime('web_config'); ?></td>
	</tr>
	<tr align="left" class="dataTr">
		<td height="33" class="firstCol">上传附件管理</td>
		<td><?php echo $dosql->GetTableRow('#@__uploads'); ?></td>
		<td class="number endCol"><?php echo GetLastEventTime('upload_filemgr_sql'); ?></td>
	</tr>
	<tr align="left">
		<td height="45" colspan="3" class="firstCol"><strong class="sysCountNum">栏目内容管理</strong></td>
	</tr>
	<tr align="left" class="head">
		<td width="45%" height="36" class="firstCol">模块名称</td>
		<td width="10%">数据量</td>
		<td width="45%" class="action endCol">最后操作</td>
	</tr>
	<tr align="left" class="dataTr">
		<td height="36" class="firstCol">栏目管理</td>
		<td><?php echo $dosql->GetTableRow('#@__infoclass'); ?></td>
		<td class="number endCol"><?php echo GetLastEventTime('infoclass'); ?></td>
	</tr>
	<tr align="left" class="dataTr">
		<td height="36" class="firstCol">二级类别管理</td>
		<td><?php echo $dosql->GetTableRow('#@__maintype'); ?></td>
		<td class="number endCol"><?php echo GetLastEventTime('maintype'); ?></td>
	</tr>
	<tr align="left" class="dataTr">
		<td height="36" class="firstCol">单页信息管理</td>
		<td><?php echo $dosql->GetTableRow('#@__info'); ?></td>
		<td class="number endCol"><?php echo GetLastEventTime('info'); ?></td>
	</tr>
	<tr align="left" class="dataTr">
		<td height="36" class="firstCol">列表信息管理</td>
		<td><?php echo $dosql->GetTableRow('#@__infolist'); ?></td>
		<td class="number endCol"><?php echo GetLastEventTime('infolist'); ?></td>
	</tr>
	<tr align="left" class="dataTr">
		<td height="36" class="firstCol">图片信息管理</td>
		<td><?php echo $dosql->GetTableRow('#@__infoimg'); ?></td>
		<td class="number endCol"><?php echo GetLastEventTime('infoimg'); ?></td>
	</tr>
	<tr align="left" class="dataTr">
		<td height="36" class="firstCol">软件下载管理</td>
		<td><?php echo $dosql->GetTableRow('#@__soft'); ?></td>
		<td class="number endCol"><?php echo GetLastEventTime('soft'); ?></td>
	</tr>
	<tr align="left" class="dataTr">
		<td height="36" class="firstCol">信息标记管理</td>
		<td><?php echo $dosql->GetTableRow('#@__infoflag'); ?></td>
		<td class="number endCol"><?php echo GetLastEventTime('infoflag'); ?></td>
	</tr>
	<tr align="left" class="dataTr">
		<td height="36" class="firstCol">信息来源管理</td>
		<td><?php echo $dosql->GetTableRow('#@__infosrc'); ?></td>
		<td class="number endCol"><?php echo GetLastEventTime('infosrc'); ?></td>
	</tr>
	<tr align="left" class="dataTr">
		<td height="36" class="firstCol">碎片数据管理</td>
		<td><?php echo $dosql->GetTableRow('#@__fragment'); ?></td>
		<td class="number endCol"><?php echo GetLastEventTime('fragment'); ?></td>
	</tr>
	<tr align="left" class="dataTr">
		<td height="36" class="firstCol">自定义菜单项</td>
		<td><?php echo $dosql->GetTableRow('#@__diymenu'); ?></td>
		<td class="number endCol"><?php echo GetLastEventTime('diymenu'); ?></td>
	</tr>
	<tr align="left" class="dataTr">
		<td height="36" class="firstCol">自定义模型</td>
		<td><?php echo $dosql->GetTableRow('#@__diymodel'); ?></td>
		<td class="number endCol"><?php echo GetLastEventTime('diymodel'); ?></td>
	</tr>
	<tr align="left" class="dataTr">
		<td height="36" class="firstCol">自定义字段</td>
		<td><?php echo $dosql->GetTableRow('#@__diyfield'); ?></td>
		<td class="number endCol"><?php echo GetLastEventTime('diyfield'); ?></td>
	</tr>
	<tr align="left">
		<td height="45" colspan="3" class="firstCol"><strong class="sysCountNum">模块扩展管理</strong></td>
	</tr>
	<tr align="left" class="head">
		<td width="45%" height="36" class="firstCol">模块名称</td>
		<td width="10%">数据量</td>
		<td width="45%" class="action endCol">最后操作</td>
	</tr>
	<tr align="left" class="dataTr">
		<td height="36" class="firstCol">用户管理</td>
		<td><?php echo $dosql->GetTableRow('#@__member'); ?></td>
		<td class="number endCol"><?php echo GetLastEventTime('member'); ?></td>
	</tr>
	<tr align="left" class="dataTr">
		<td height="36" class="firstCol">用户组管理</td>
		<td><?php echo $dosql->GetTableRow('#@__usergroup'); ?></td>
		<td class="number endCol"><?php echo GetLastEventTime('usergroup'); ?></td>
	</tr>
	<tr align="left" class="dataTr">
		<td height="36" class="firstCol">用户收藏管理</td>
		<td><?php echo $dosql->GetTableRow('#@__userfavorite'); ?></td>
		<td class="number endCol"><?php echo GetLastEventTime('userfavorite'); ?></td>
	</tr>
	<tr align="left" class="dataTr">
		<td height="36" class="firstCol">用户评论管理</td>
		<td><?php echo $dosql->GetTableRow('#@__usercomment'); ?></td>
		<td class="number endCol"><?php echo GetLastEventTime('usercomment'); ?></td>
	</tr>
	<tr align="left" class="dataTr">
		<td height="36" class="firstCol">留言模块管理</td>
		<td><?php echo $dosql->GetTableRow('#@__message'); ?></td>
		<td class="number endCol"><?php echo GetLastEventTime('message'); ?></td>
	</tr>
	<tr align="left" class="dataTr">
		<td height="36" class="firstCol">广告模块管理</td>
		<td><?php echo $dosql->GetTableRow('#@__admanage'); ?></td>
		<td class="number endCol"><?php echo GetLastEventTime('admanage'); ?></td>
	</tr>
	<tr align="left" class="dataTr">
		<td height="36" class="firstCol">广告位管理</td>
		<td><?php echo $dosql->GetTableRow('#@__adtype'); ?></td>
		<td class="number endCol"><?php echo GetLastEventTime('adtype'); ?></td>
	</tr>
	<tr align="left" class="dataTr">
		<td height="36" class="firstCol">友情链接管理</td>
		<td><?php echo $dosql->GetTableRow('#@__weblink'); ?></td>
		<td class="number endCol"><?php echo GetLastEventTime('weblink'); ?></td>
	</tr>
	<tr align="left" class="dataTr">
		<td height="36" class="firstCol">友情链接类型</td>
		<td><?php echo $dosql->GetTableRow('#@__weblinktype'); ?></td>
		<td class="number endCol"><?php echo GetLastEventTime('weblinktype'); ?></td>
	</tr>
	<tr align="left" class="dataTr">
		<td height="36" class="firstCol">招聘模块管理</td>
		<td><?php echo $dosql->GetTableRow('#@__job'); ?></td>
		<td class="number endCol"><?php echo GetLastEventTime('job'); ?></td>
	</tr>
	<tr align="left" class="dataTr">
		<td height="36" class="firstCol">投票模块管理</td>
		<td><?php echo $dosql->GetTableRow('#@__vote'); ?></td>
		<td class="number endCol"><?php echo GetLastEventTime('vote'); ?></td>
	</tr>
	<tr align="left" class="dataTr">
		<td height="36" class="firstCol">级联数据管理</td>
		<td><?php echo $dosql->GetTableRow('#@__cascadedata'); ?></td>
		<td class="number endCol"><?php echo GetLastEventTime('cascade'); ?></td>
	</tr>
	<tr align="left">
		<td height="45" colspan="3" class="firstCol"><strong class="sysCountNum">商品订单管理</strong></td>
	</tr>
	<tr align="left" class="head">
		<td width="45%" height="36" class="firstCol">模块名称</td>
		<td width="10%">数据量</td>
		<td width="45%" class="action endCol">最后操作</td>
	</tr>
	<tr align="left" class="dataTr">
		<td height="36" class="firstCol">商品类别管理</td>
		<td><?php echo $dosql->GetTableRow('#@__goodstype'); ?></td>
		<td class="number endCol"><?php echo GetLastEventTime('goodstype'); ?></td>
	</tr>
	<tr align="left" class="dataTr">
		<td height="36" class="firstCol">品牌类型管理</td>
		<td><?php echo $dosql->GetTableRow('#@__goodsbrand'); ?></td>
		<td class="number endCol"><?php echo GetLastEventTime('goodsbrand'); ?></td>
	</tr>
	<tr align="left" class="dataTr">
		<td height="36" class="firstCol">商品列表管理</td>
		<td><?php echo $dosql->GetTableRow('#@__goods'); ?></td>
		<td class="number endCol"><?php echo GetLastEventTime('goods'); ?></td>
	</tr>
	<tr align="left" class="dataTr">
		<td height="36" class="firstCol">商品信息属性</td>
		<td><?php echo $dosql->GetTableRow('#@__goodsflag'); ?></td>
		<td class="number endCol"><?php echo GetLastEventTime('goodsflag'); ?></td>
	</tr>
	<tr align="left" class="dataTr">
		<td height="36" class="firstCol">商品订单管理</td>
		<td><?php echo $dosql->GetTableRow('#@__goodsorder'); ?></td>
		<td class="number endCol"><?php echo GetLastEventTime('goodsorder'); ?></td>
	</tr>
	<tr align="left" class="dataTr">
		<td height="36" class="firstCol">配送方式管理</td>
		<td><?php echo $dosql->GetTableRow('#@__postmode'); ?></td>
		<td class="number endCol"><?php echo GetLastEventTime('postmode'); ?></td>
	</tr>
	<tr align="left" class="dataTr">
		<td height="36" class="firstCol">支付方式管理</td>
		<td><?php echo $dosql->GetTableRow('#@__paymode'); ?></td>
		<td class="number endCol"><?php echo GetLastEventTime('paymode'); ?></td>
	</tr>
	<tr align="left" class="dataTr">
		<td height="36" class="firstCol">货到方式管理</td>
		<td><?php echo $dosql->GetTableRow('#@__getmode'); ?></td>
		<td class="number endCol"><?php echo GetLastEventTime('getmode'); ?></td>
	</tr>
	<tr align="left">
		<td height="45" colspan="3" class="firstCol"><strong class="sysCountNum">界面模板管理</strong></td>
	</tr>
	<tr align="left" class="head">
		<td width="45%" height="36" class="firstCol">模块名称</td>
		<td width="10%">数据量</td>
		<td width="45%" class="action endCol">最后操作</td>
	</tr>
	<tr align="left" class="dataTr">
		<td height="36" class="firstCol">导航菜单设置</td>
		<td><?php echo $dosql->GetTableRow('#@__nav'); ?></td>
		<td class="number endCol"><?php echo GetLastEventTime('nav'); ?></td>
	</tr>
	<tr align="left">
		<td height="45" colspan="3" class="firstCol"><strong class="sysCountNum">帮助与更新</strong></td>
	</tr>
	<tr align="left" class="head">
		<td width="45%" height="36" class="firstCol">模块名称</td>
		<td width="10%">数据量</td>
		<td width="45%" class="action endCol">最后操作</td>
	</tr>
	<tr align="left" class="dataTr">
		<td height="36" class="firstCol">操作日志管理</td>
		<td><?php echo $dosql->GetTableRow('#@__sysevent'); ?></td>
		<td class="number endCol"><?php echo GetLastEventTime('sysevent'); ?></td>
	</tr>
</table>
<div class="mgr_divb"></div>
<div class="page">
	<div class="pageText">共有<span>5</span>大项<span>39</span>个小项</div>
</div>
<?php

//获取模块最后操作时间
function GetLastEventTime($m='')
{
	global $dosql;
	
	$r = $dosql->GetOne("SELECT MAX(posttime) as time FROM `#@__sysevent` WHERE `id`<>0 AND `model`='$m'");

	if(isset($r['time']))
		return GetDateTime($r['time']);
	else
		return '暂无最新更新';
}

?>
</body>
</html>