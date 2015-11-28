<?php if(!defined('IN_BKUP')) exit('Request Error!'); ?>

<form name="form" id="form" method="post" action="?action=import">
	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="dataTable">
		<tr align="left" class="head">
			<td width="5%" height="36" class="firstCol"><input type="checkbox" name="checkall" onclick="CheckAllBtn(form)" value="check" checked="checked" /></td>
			<td width="30%">文件名</td>
			<td width="30%">文件大小[共<?php if(isset($files_size)){echo GetRealSize($files_size);}else{echo '0B';} ?>]</td>
			<td width="20%">备份时间</td>
			<td width="15%" class="endCol">操作</td>
		</tr>
		<?php 
		if(isset($bfiles) && is_array($bfiles))
		{
			foreach($bfiles as $s)
			{
		?>
		<tr align="left" class="dataTr" onmouseover="this.className='dataTrOn'" onmouseout="this.className='dataTr'">
			<td height="36" class="firstCol"><input name="tbname[]" type="checkbox" value="<?php echo $s['name']; ?>" checked="checked" /></td>
			<td><?php echo $s['name']; ?></td>
			<td><?php echo $s['size']; ?></td>
			<td class="number"><?php echo $s['mktime']; ?></td>
			<td class="action endCol"><span class="nb"><a href="?action=import&dopost=del&dirname=<?php echo $tbname; ?>&tbname=<?php echo $s['name']; ?>">删除</a></span></td>
		</tr>
		<?php 
			}
			$conut = count($bfiles);
		}
		else
		{
		?>
		<tr>
			<td colspan="5" class="dataEmpty">暂时没有备份文件</td>
		</tr>
		<?php
			$conut = 0;
		}
		?>
	</table>
	<div class="bottomToolbar"> <span class="selArea"><span>选择：</span> <a href="javascript:CheckAll(form,true);">全部</a> - <a href="javascript:CheckAll(form,false);">无</a> - <a href="javascript:Repair('?action=import&dopost=delall&dirname=<?php echo $tbname; ?>');" onclick="return ConfDel(form);">删除</a></span>
		<input type="hidden" name="dirname" value="<?php echo $tbname; ?>" />
		<a href="#" onclick="Repair('?action=import&dopost=reset');" class="dataBtn">还原备份文件</a> </div>
</form>
<div class="page">
	<div class="pageText">共<span><?php echo $conut; ?></span>个SQL文件</div>
</div>
<?php

//判断是否启用快捷工具栏
if($cfg_quicktool == 'Y')
{
?>
<div class="quickToolbar">
	<div class="qiuckWarp">
		<div class="quickArea"> <span class="selArea"><span>选择：</span> <a href="javascript:CheckAll(form,true);">全部</a> - <a href="javascript:CheckAll(form,false);">无</a> - <a href="javascript:Repair('?action=import&dopost=delall&dirname=<?php echo $tbname; ?>');" onclick="return ConfDel(form);">删除</a></span>
		<input type="hidden" name="dirname" value="<?php echo $tbname; ?>" />
		<a href="#" onclick="Repair('?action=import&dopost=reset');" class="dataBtn">还原备份文件</a> <span class="pageSmall">
			<div class="pageText">共<span><?php echo $conut; ?></span>个SQL文件</div>
			</span></div>
		<div class="quickAreaBg"></div>
	</div>
</div>
<?php
}
?>