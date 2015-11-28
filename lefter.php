<div class="search_l">
	<div class="search">
		<form name="search" id="search" method="get" action="product.php">
			<input type="text" name="keyword" id="keyword" title="输入产品名进行搜索" value="<?php if(isset($keyword)){echo $keyword;} ?>" class="key" />
			<button type="submit" id="search_btn" class="sub"><span>搜索</span></button>
		</form>
	</div>
	<div class="cl"></div>
</div>
<div class="contact"> <?php echo Info(10); ?> </div>
<div class="follow"><a href="http://weibo.com/phpMyWind" class="sina" target="_blank">收听新浪微博</a><a href="http://t.qq.com/phpMyWind" class="tqq" target="_blank">收听腾讯微博</a></div>
