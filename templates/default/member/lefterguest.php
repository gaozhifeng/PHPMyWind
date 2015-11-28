<?php if(!defined('IN_MEMBER')) exit('Request Error!'); ?>
<div class="leftarea">
	<div class="userinfo">
		<div class="avater"><img src="<?php echo $cfg_webpath; ?>/data/avatar/index.php?uid=0&size=middle" /></div>
		<div>
			<?php
			if(check_app_login('qq'))
				echo '<span class="ounameqq">'.$_SESSION['app']['qq']['name'].'</span>';
			else if(check_app_login('weibo'))
				echo '<span class="ounameweibo">'.$_SESSION['app']['weibo']['name'].'</span>';
				
			?>
			<br />浏览用户
		</div>
		<div class="cl"></div>
	</div>
	<div class="act"> <a href="?c=default" <?php if($c=='default') echo 'class="on"'; ?>>个人中心</a> </div>
</div>
