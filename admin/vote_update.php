<?php require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('vote'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>修改投票信息</title>
<link href="templates/style/admin.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/js/checkf.func.js"></script>
<script type="text/javascript" src="plugin/calendar/calendar.js"></script>
<script type="text/javascript">
$(function(){
	$(".addNewTr").click(function(){
		str = '<tr><td height="40" align="right"><span class="delVoteTxt"><a href="javascript:;" onclick="delThisTr($(this))">删</a></span>：</td><td><input type="text" name="options[]" id="options[]" class="input" /></td></tr>';
		$("#voteOption").append(str);
	});
})

function delThisTr(trobj)
{
	trobj.parent().parent().parent().remove();
}
</script>
</head>
<body>
<?php
$row = $dosql->GetOne("SELECT * FROM `#@__vote` WHERE `id`=$id");
?>
<div class="formHeader"> <span class="title">修改投票信息</span> <a href="javascript:location.reload();" class="reload">刷新</a> </div>
<form name="form" id="form" method="post" action="vote_save.php" onsubmit="return cfm_vote();">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="formTable">
		<tr>
			<td width="25%" height="40" align="right">投票标题：</td>
			<td width="75%"><input type="text" name="title" id="title" class="input" value="<?php echo $row['title']; ?>" />
				<span class="maroon">*</span><span class="cnote">带<span class="maroon">*</span>号表示为必填项</span></td>
		</tr>
		<tr>
			<td height="118" align="right">投票描述：</td>
			<td><textarea name="content" id="content" class="textarea"><?php echo $row['content']; ?></textarea></td>
		</tr>
	</table>
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="formTable" id="voteOption">
		<?php
		$dosql->Execute("SELECT `optionid` FROM `#@__votedata` WHERE voteid=".$row['id']);
		$optionid_str = '';
		while($row3 = $dosql->GetArray())
		{
			$optionid_str .= $row3['optionid'].',';
		}
		
		
		$dosql->Execute("SELECT * FROM `#@__voteoption` WHERE voteid=".$row['id']);
		$i = 0;
		while($row2 = $dosql->GetArray())
		{
		?>
		<tr>
			<td width="25%" height="40" align="right"><?php if($i==0){?>
				投票项目：
				<?php }else{echo '<span class="delVoteTxt"><a href="vote_save.php?id='.$id.'&action=delopt&option_id='.$row2['id'].'">删</a></span>：';} ?></td>
			<td width="75%"><input type="text" name="options[]" id="options[]" class="input" value="<?php echo $row2['options']; ?>" />
				<input type="hidden" name="option_id[]" id="option_id[]" value="<?php echo $row2['id']; ?>">
				&nbsp;<strong title="票数" style="color:#8d5a00;">(<?php echo substr_count($optionid_str, $row2['id']); ?>)</strong>
				<?php if($i==0){?>
				<span class="cnote"><a href="javascript:;" class="addNewTr" title="新增一行"></a></span>
				<?php } ?></td>
		</tr>
		<?php
			$i++;
		}
		if($i == 0)
		{
		?>
		<tr>
			<td width="25%" height="40" align="right">投票项目：</td>
			<td width="75%"><input type="text" name="options_new[]" id="options_new[]" class="input" />
				<span class="cnote"><a href="javascript:;" class="addNewTr" title="新增一行"></a></span></td>
		</tr>
		<?php	
		}
		?>
	</table>
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="formTable">
		<tr>
			<td width="25%" height="40" align="right">开始时间：</td>
			<td width="75%"><input type="text" name="starttime" id="starttime" class="inputms" value="<?php echo GetDateTime($row['starttime']); ?>" readonly="readonly" />
				<script type="text/javascript">
				Calendar.setup({
					inputField     :    "starttime",
					ifFormat       :    "%Y-%m-%d %H:%M:%S",
					showsTime      :    true,
					timeFormat     :    "24"
				});
				</script></td>
		</tr>
		<tr>
			<td height="40" align="right">结束时间：</td>
			<td><input type="text" name="endtime" id="endtime" class="inputms" value="<?php echo GetDateTime($row['endtime']); ?>" readonly="readonly" />
				<script type="text/javascript">
				Calendar.setup({
					inputField     :    "endtime",
					ifFormat       :    "%Y-%m-%d %H:%M:%S",
					showsTime      :    true,
					timeFormat     :    "24"
				});
				</script></td>
		</tr>
		<tr>
			<td height="40" align="right">游客投票：</td>
			<td><input type="radio" name="isguest" value="true" <?php if($row['isguest'] == 'true') echo 'checked="checked"'; ?> />
				允许&nbsp;
				<input type="radio" name="isguest" value="false" <?php if($row['isguest'] == 'false') echo 'checked="checked"'; ?> />
				不允许</td>
		</tr>
		<tr>
			<td height="40" align="right">查看投票：</td>
			<td><input type="radio" name="isview" value="true" <?php if($row['isview'] == 'true') echo 'checked="checked"'; ?> />
				允许&nbsp;
				<input type="radio" name="isview" value="false" <?php if($row['isview'] == 'false') echo 'checked="checked"'; ?> />
				不允许</td>
		</tr>
		<tr>
			<td height="40" align="right">投票间隔：</td>
			<td><input type="text" name="intval" id="intval" class="input" value="<?php echo $row['intval']; ?>" />
				<span class="cnote">分钟为单位，0 表示此IP地址只能投一次</span></td>
		</tr>
		<tr class="nb">
			<td height="40" align="right">是否多选：</td>
			<td><input type="radio" name="isradio" value="1" <?php if($row['isradio'] == '1') echo 'checked="checked"'; ?> />
				单选&nbsp;
				<input type="radio" name="isradio" value="2" <?php if($row['isradio'] == '2') echo 'checked="checked"'; ?> />
				多选</td>
		</tr>
		<tr class="nb">
			<td colspan="2" height="26"><div class="line"></div></td>
		</tr>
		<tr>
			<td height="40" align="right">排列排序：</td>
			<td><input type="text" name="orderid" id="orderid" class="inputos" value="<?php echo $row['orderid']; ?>" /></td>
		</tr>
		<tr>
			<td height="40" align="right">更新时间：</td>
			<td><input type="text" name="posttime" id="posttime" class="inputms" value="<?php echo GetDateTime($row['posttime']); ?>" readonly="readonly" />
				<script type="text/javascript">
				Calendar.setup({
					inputField     :    "posttime",
					ifFormat       :    "%Y-%m-%d %H:%M:%S",
					showsTime      :    true,
					timeFormat     :    "24"
				});
				</script></td>
		</tr>
		<tr class="nb">
			<td height="40" align="right">审　核：</td>
			<td><input type="radio" name="checkinfo" value="true" <?php if($row['checkinfo'] == 'true') echo 'checked="checked"'; ?> />
				是&nbsp;
				<input type="radio" name="checkinfo" value="false" <?php if($row['checkinfo'] == 'false') echo 'checked="checked"'; ?> />
				否<span class="cnote">选择“否”则该信息不会显示在前台。</span></td>
		</tr>
	</table>
	<div class="formSubBtn">
		<input type="submit" class="submit" value="提交" />
		<input type="button" class="back" value="返回" onclick="history.go(-1);" />
		<input type="hidden" name="action" id="action" value="update" />
		<input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
	</div>
</form>
</body>
</html>