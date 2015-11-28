<?php if(!defined('IN_BKUP')) exit('Request Error!'); ?>

<table width="100%" border="0" cellpadding="0" cellspacing="0" class="dataTable">
	<tr align="left" class="head">
		<td height="36" class="firstCol" colspan="2">字段[备注]</td>
		<td>类型(长度)</td>
		<td>整理</td>
		<td>允许为空</td>
		<td>默认值</td>
		<td align="right" class="endCol">额外</td>
	</tr>
	<?php

	//"SHOW FULL COLUMNS FROM `$tbname`"
	$dosql->Execute("SHOW FULL COLUMNS FROM `$tbname`");
	$i = 0;
	while($r = $dosql->GetArray())
	{
		if($r['Comment'])
			$comment = '<span style="font-size:10px;color:#999;padding-left:5px;">['.$r['Comment'].']</span>';
		else
			$comment = '';
	?>
	<tr align="left" class="dataTr" onmouseover="this.className='dataTrOn'" onmouseout="this.className='dataTr'">
		<td width="8%" height="36" class="firstCol"><?php echo $r['Field'];if($r['Key'] == 'PRI') echo ' <img src="templates/images/database_key.gif" title="主键" />'; ?></td>
		<td width="22%"><?php echo $comment; ?></td>
		<td width="15%"><?php echo $r['Type']; ?></td>
		<td width="15%"><?php echo $r['Collation'];?></td>
		<td width="15%"><?php if($r['Null'] == 'NO'){echo '否';} else{echo '是';}  ?></td>
		<td width="10%"><?php if($r['Default'] == ''){echo '无';} else{echo $r['Default'];} ?></td>
		<td width="15%" class="endCol"><?php echo $r['Extra']; ?></td>
	</tr>
	<?php
		$i++;
	}
	?>
</table>
<div class="page">
	<div class="pageText">共<span><?php echo $i; ?></span>个字段</div>
</div>
