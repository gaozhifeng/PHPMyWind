<?php if(!defined('IN_BKUP')) exit('Request Error!'); ?>

<form name="form" id="form" method="post" action="?action=export">
	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="dataTable">
		<tr align="left" class="head">
			<td width="5%" height="36" class="firstCol"><input type="checkbox" name="checkall" onclick="CheckAllBtn(form)" value="check" checked="checked" /></td>
			<td width="30%">表名</td>
			<td width="25%">记录数</td>
			<td width="25%">数据大小[共<?php if(isset($total_size)){echo GetRealSize($total_size);}else{echo '0B';} ?>]</td>
			<td width="15%" class="endCol">操作</td>
		</tr>
		<?php 
		if(is_array($name))
		{
			$i = 0;
			foreach($name as $i => $tbname)
			{
		?>
		<tr align="left" class="dataTr" onmouseover="this.className='dataTrOn'" onmouseout="this.className='dataTr'">
			<td height="36" class="firstCol"><input type="checkbox" name="tbname[]" value="<?php echo $tbname; ?>" checked="checked" /></td>
			<td><?php echo $tbname?></td>
			<td><?php echo $rows[$i]?></td>
			<td><?php echo $size[$i]?></td>
			<td class="action endCol"><span><a href="?action=export&dopost=struct&tbname=<?php echo $tbname; ?>">结构</a></span> | <span><a href="?action=export&dopost=repair&tbname=<?php echo $tbname?>">修复</a></span> | <span class="nb"><a href="?action=export&dopost=optimize&tbname=<?php echo $tbname?>">优化</a></span></td>
		</tr>
		<?php
			}
			$i++;
		}
		?>
	</table>
	<div class="bottomToolbar"> <span class="selArea"><span>选择：</span><a href="javascript:CheckAll(form,true);">全部</a> - <a href="javascript:CheckAll(form,false);">无</a> - <a href="#" onclick="Repair('?action=export&dopost=repair')">修复</a> - <a href="#" onclick="Repair('?action=export&dopost=optimize')">优化</a> </span> <a href="#" onclick="Repair('?action=export&dopost=backup')" class="dataBtn">开始备份数据</a> <span class="inputArea">备份表结构：
		<input type="checkbox" name="isstruct" value="1" checked="checked" />
		&nbsp;
		分卷大小：
		<input type="text" name="fsize" value="2048" size="5" style="text-indent:3px;" />
		KB&nbsp; </span> </div>
</form>
<div class="page">
	<div class="pageText">共有<span><?php echo $i; ?></span>个表</div>
</div>
<?php

//判断是否启用快捷工具栏
if($cfg_quicktool == 'Y')
{
?>
<div class="quickToolbar">
	<div class="qiuckWarp">
		<div class="quickArea"><span class="selArea"><span>选择：</span><a href="javascript:CheckAll(form,true);">全部</a> - <a href="javascript:CheckAll(form,false);">无</a> - <a href="#" onclick="Repair('?action=export&dopost=repair')">修复</a> - <a href="#" onclick="Repair('?action=export&dopost=optimize')">优化</a> </span> <a href="#" onclick="Repair('?action=export&dopost=backup')" class="dataBtn">开始备份数据</a> <span class="inputArea">备份表结构：
		<input type="checkbox" name="isstruct" value="1" checked="checked" />
		&nbsp;
		分卷大小：
		<input type="text" name="fsize" value="2048" size="5" style="text-indent:3px;" />
		KB&nbsp; </span><span class="pageSmall">
			<div class="pageText">共有<span><?php echo $i; ?></span>个表</div>
			</span></div>
		<div class="quickAreaBg"></div>
	</div>
</div>
<?php
}
?>