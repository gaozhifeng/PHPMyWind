/*
**************************
(C)2010-2015 phpMyWind.com
update: 2012-10-22 8:46:47
person: Feng
**************************
*/

$(function() {
    $("#comment").focus(function() {
		if($(this).val() == "说点什么吧..."){
			$(this).val("").css("color","#333");
		}
	}).blur(function(){
		if($(this).val() == ""){
			$(this).val("说点什么吧...").css("color","#999");
		}
	});

	$(".commnet .button").hover(function(){
		$(this).attr("class","button_on");
	},function(){
		$(this).attr("class","button");
	}).click(function(){

		var aid   = $("#aid").val();
		var molds = $("#molds").val();
		var bodys  = $("#comment").val();

		if(bodys == ""){
			$("#comment").val("说点什么吧...");
			return false;
		}
		else if(bodys == "说点什么吧..."){
			return false;
		}


		$.ajax({
			url : "member.php",
			type: "post",
			data:{"a":"savecomment", "aid":aid, "molds":molds, "body":bodys},
			dataType:'html',
			success:function(data){
				if(data != ''){
					var jsonobj = eval('(' + data + ')');
				}

				if(jsonobj[0] == 1)
				{
					alert("评论成功！");

					$(".commlist").prepend("<li><span class=\"uname\">"+jsonobj[1]+"</span><p>"+jsonobj[2]+"</p><span class=\"time\">"+jsonobj[3]+"</span></li>");
					var newnum = parseInt($(".commnum i").html()) + 1;
					$(".commnum i").html(newnum);
					$("#comment").val("说点什么吧...").css("color","#999");
					return;
				}
				else
				{
					alert("评论失败！");
					return;
				}
			}
		});
	});
});


function AddUserFavorite()
{
	var aid   = $("#aid").val();
	var molds = $("#molds").val();

	$.ajax({
		url : "member.php",
		type: "post",
		data:{"a":"savefavorite", "aid":aid, "molds":molds},
		dataType:'html',
		success:function(data){
			if(data == 1){
				alert("收藏成功！");
				return;
			}
			else if(data == 2){
				alert("亲，您已经收藏过该文章！");
				return;
			}
			else{
				alert("收藏失败！");
				return;
			}
		}
	});
}
