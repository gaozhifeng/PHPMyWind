<?php	if(!defined('IN_MOBILE')) exit('Request Error!'); ?>
<div class="navside">
	<ul>
		<li><span><a <?php if(empty($cid)){ echo "class='on'";}?> href="4g.php">首　　页</a></span></li>
		<?php
		$dosql->Execute("SELECT * FROM `#@__infoclass` WHERE `infotype` IN (0,1,2) AND parentid=0 AND checkinfo='true' ORDER BY orderid ASC");
		if($dosql->GetTotalRow() > 0)
		{
			while($row = $dosql->GetArray())
			{
				switch ($row['infotype'])
				{
				case 0:
					$m = 'info';
				break;
				case 1:
					$m = 'list';
				break;
				case 2:
					$m = 'img';
				break;
				default:
					echo 'No number between 1 and 3';
				}
		?>
		<li><span><a <?php if($cid == $row['id']){echo 'class="on"';} ?> href="?m=<?php echo $m; ?>&cid=<?php echo $row['id']; ?>"><?php echo $row['classname']; ?></a></span></li>
		<?php
			}
		}
		?>
        <div class="cl"></div>
	</ul>
</div>