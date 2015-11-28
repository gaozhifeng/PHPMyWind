/*
**************************
(C)2010-2015 phpMyWind.com
update: 2014-5-30 16:51:36
person: Feng
**************************
*/


/*
 * 获取栏目缩略图大小
 *
 * @access   public
 * @pid      int     栏目的id
 */

function GetCatpSize(pid)
{
	$.ajax({
		url : "ajax_do.php?action=catpsize&pid=" + pid,
		type: "get",
		dataType:"html",
		beforeSend:function(){
		},
		success:function(data){
			if(data != ''){
				$size = data.split("|");
				$("#picwidth").val($size[0]);
				$("#picheight").val($size[1]);
			}
		}
	});
}
