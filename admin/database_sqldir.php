<?php if(!defined('IN_BKUP')) exit('Request Error!'); ?>

<form name="form" id="form" method="post" action="?action=import">
	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="dataTable">
		<tr align="left" class="head">
			<td width="50%" height="36" class="firstCol">文件名</td>
			<td width="30%">文件大小[共<?php if(isset($files_size)){echo GetRealSize($files_size);}else{echo '0B';} ?>]</td>
			<td width="20%">备份时间</td>
		</tr>
		<?php 
		if(isset($bfiles) && is_array($bfiles))
		{
			foreach($bfiles as $s)
			{
		?>
		<tr align="left" class="dataTr" onmouseover="this.className='dataTrOn'" onmouseout="this.className='dataTr'">
			<td height="36" class="firstCol"><?php echo $s['name']; ?></td>
			<td><?php echo $s['size']; ?></td>
			<td class="number"><?php echo $s['mktime']; ?></td>
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
	<div class="bottomToolbar">
		<input type="hidden" name="dirname" value="<?php echo $tbname; ?>" />
		<a href="#" onclick="Repair('?action=import&dopost=reset');" class="dataBtn">还原备份文件</a> </div>
</form>
<div class="page">
	<div class="pageText">共<span><?php echo $conut; ?></span>个SQL文件</div>
</div>