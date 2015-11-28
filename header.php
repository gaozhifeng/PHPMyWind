<div class="header">
	<h1 class="logo"><a href="javascript:;"></a></h1>
	<div class="txt"><a href="javascript:;" onclick="AddFavorite();return false;">加入收藏</a><span>|</span>
	<?php if($cfg_member == 'Y'){if(isset($_COOKIE['username'])){?><a href="member.php?c=default">会员中心</a>&nbsp;&nbsp;<a href="member.php?a=logout">退出</a><?php }else{ ?><a href="member.php?c=login">登录</a>&nbsp;&nbsp;&nbsp;<a href="member.php?c=reg">注册</a><?php }}else{ ?><a href="javascript:;" onclick="this.style.behavior='url(#default#homepage)';this.setHomePage(location.href);">设为首页</a><?php } ?></div>
	<div class="tel"><?php echo $cfg_hotline; ?></div>
</div>
<div class="navArea">
	<div class="navBg">
		<ul class="nav">
			<?php echo GetNav(); ?>
		</ul>
	</div>
</div>
<script type="text/javascript">

$(function(){

	/*当前页面导航高亮*/
	var href = window.location.href.split('/')[window.location.href.split('/').length-1].substr(0,4);
	if(href.length > 0){
		$(function(){
			$("ul.nav a:first[href^='"+href+"']").attr("class","on");
			if($("ul.nav a:first[href^='"+href+"']").size() == 0){
				$("ul.nav a:first[href^='index']").attr("class","on");
			}
		});
	}else{
		$(function(){$("ul.nav a:first[href^='index']").attr("class","on")});
	}

	/*下拉菜单*/
	$(".nav li").hover(function(){
		$(this).parents(".nav > li").find("a:first").addClass("on2");
		$(this).find("ul:first").show(); //鼠标滑过查找li下面的第一个ul显示
	},function(){
		var navobj = $(this).find("ul:first");
		navobj.hide();

		//鼠标离开隐藏li下面的ul
		if(navobj.attr("class") == "nav_sub")
		{
			$(this).find("a:first").removeClass("on2");
		}
	})

	//给li下面ul是s的样式的前一个同辈元素添加css
	$(".nav li ul li ul").prev().addClass("t");
})


//加入收藏
function AddFavorite(){
	if(document.all){
		try{
			window.external.addFavorite(window.location.href,document.title);
		}catch(e){
			alert("加入收藏失败，请使用Ctrl+D进行添加！");
		}
	}else if(window.sidebar){
		window.sidebar.addPanel(document.title, window.location.href, "");
	}else{
		alert("加入收藏失败，请使用Ctrl+D进行添加！");
	}
}
</script>