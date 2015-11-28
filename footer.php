<!-- weblink-->

<div class="weblink">
	<?php
	$dosql->Execute("SELECT * FROM `#@__weblink` WHERE classid=1 AND checkinfo=true ORDER BY orderid,id DESC");
	while($row = $dosql->GetArray())
	{
	?>
	<a href="<?php echo $row['linkurl']; ?>" target="_blank"><?php echo $row['webname']; ?></a>
	<?php
	}
	?>
</div>
<!-- /weblink-->
<!-- footer-->
<div class="footer"><?php echo $cfg_copyright ?><br />网站采用 <a href="http://phpmywind.com" target="_blank">PHPMyWind</a> 核心</div>
<!-- /footer-->
<?php

echo GetQQ();

//将流量统计代码放在页面最底部
$cfg_countcode;

?>