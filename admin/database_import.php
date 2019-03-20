<?php if(!defined('IN_BKUP')) exit('Request Error!'); ?>

<form name="form" id="form" method="post" action="?action=import">
	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="dataTable">
		<tr align="left" class="head">
			<td width="35%" height="36" class="firstCol">目录名</td>
			<td width="30%">目录大小[共<?php if(isset($files_size)){echo GetRealSize($files_size);}else{echo '0B';} ?>]</td>
			<td width="20%">创建时间</td>
            <td width="15%">操作</td>
		</tr>
		<?php 
		if(isset($bfiles) && is_array($bfiles))
		{
			foreach($bfiles as $b)
			{
		?>
		<tr align="left" class="dataTr" onmouseover="this.className='dataTrOn'" onmouseout="this.className='dataTr'">
			<td height="36" class="firstCol"><a href="?action=import&dopost=sqldir&tbname=<?php echo $b['name']; ?>" title="查看目录" class="isdir"><?php echo $b['name']; ?></a></td>
			<td><?php echo $b['size']; ?></td>
			<td class="number"><?php echo $b['mktime']; ?></td>
			<td class="action endCol"><span><a href="?action=import&dopost=sqldir&tbname=<?php echo $b['name']; ?>">查看</a></span></span></td>
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
</form>
<ul class="tipsList">
	<li>点击目录或查看可进入备份目录单独还原数据表结构或数据表，也可点击还原备份文件还原所选文件</li>
</ul>
<div class="page">
	<div class="pageText">共<span><?php echo $conut; ?></span>个备份目录</div>
</div>

