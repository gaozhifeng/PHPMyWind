
/*
**************************
(C)2010-2015 phpMyWind.com
update: 2012-1-2 10:09:06
person: Feng
**************************
*/

$(function(){}).keydown(function(event){

	//快捷键
	if(event.keyCode == 27){
		window.top.location.href = 'logout.php';
	}
});



//验证管理员添加
function cfm_admin()
{
	if($("#username").val() == "")
	{
		alert("请输入用户名！");
		$("#username").focus();
		return false;
	}
	if($("#username").val().length<5 || $("#username").val().length>20)
	{
		alert("用户名长度不得小于5位或大于20位！");
		$("#username").focus();
		return false;
	}
	if($("#password").val() == "")
	{
		alert("请输入密码！");
		$("#password").focus();
		return false;
	}
	if($("#password").val().length<5 || $("#password").val().length>20)
	{
		alert("密码由5-16个字符组成，区分大小写！");
		$("#password").focus();
		return false;
	}
	if($("#repassword").val() == "")
	{
        alert ("请输入确认密码！");
        $("#repassword").focus();
        return false;
    }
	if($("#password").val() != $("#repassword").val())
	{
        alert ("两次密码不同！");
        $("#repassword").focus();
        return false;
    }
}


//验证管理员修改
function cfm_upadmin()
{
	if($("#oldpwd").val()!="" || $("#password").val()!="" || $("#repassword").val()!="")
	{
		if($("#oldpwd").val() == "")
		{
			alert("请填写旧密码！");
			$("#oldpwd").focus();
			return false;
		}
		else if($("#password").val() == "")
		{
			alert("请填写新密码！");
			$("#password").focus();
			return false;
		}
		else if($("#repassword").val() == "")
		{
			alert("请填写重复密码！");
			$("#repassword").focus();
			return false;
		}

		if($("#oldpwd").val()!="" && $("#password").val()!="" && $("#repassword").val()!="")
		{
			if($("#oldpwd").val().length<5 || $("#oldpwd").val().length>20)
			{
				alert("密码由5-20个字符组成，区分大小写！");
				$("#oldpwd").focus();
				return false;
			}
			if($("#password").val().length<5 || $("#password").val().length>20)
			{
				alert("密码由5-20个字符组成，区分大小写！");
				$("#password").focus();
				return false;
			}
			if($("#repassword").val() == "")
			{
				alert("请填写确认密码！");
				$("#repassword").focus();
				return false;
			}
			if($("#password").val() != $("#repassword").val())
			{
				alert("两次密码不同！");
				$("#repassword").focus();
				return false;
			}
		}

	}
}


//验证管理组添加
function cfm_admingroup()
{
	if($("#groupname").val() == "")
	{
		alert("请输入管理组名称！");
		$("#groupname").focus();
		return false;
	}
}


//验证站点添加
function cfm_site()
{
	if($("#site_name").val() == "")
	{
		alert("请输入站点名称！");
		$("#site_name").focus();
		return false;
	}
	if($("#site_key").val() == "")
	{
		alert("请输入站点标识！");
		$("#site_key").focus();
		return false;
	}
	if($("#webname").val() == "")
	{
		alert("请输入站点标题！");
		$("#webname").focus();
		return false;
	}
	if($("#weburl").val() == "")
	{
		alert("请输入站点地址！");
		$("#weburl").focus();
		return false;
	}
}


//验证栏目管理
function cfm_infoclass()
{
	if($("#classname").val() == "")
	{
		alert("请填写栏目名称！");
		$("#classname").focus();
		return false;
	}
}


//验证类别通用
function cfm_btype()
{
	if($("#classname").val() == "")
	{
		alert("请填写栏目名称！");
		$("#classname").focus();
		return false;
	}
}


//验证列表信息用
function cfm_infolm()
{
	if($("#classid").val() == "-1")
	{
		alert("请选择所属栏目！");
		$("#classid").focus();
		return false;
	}
	if($("#title").val() == "")
	{
		alert("请填写信息标题！");
		$("#title").focus();
		return false;
	}
}


//验证广告位
function cfm_adtype()
{
	if($("#classname").val() == "")
	{
		alert("请填写广告位名称！");
		$("#classname").focus();
		return false;
	}
	if($("#width") == "")
	{
		alert("请填写广告位宽度！");
		$("#width").focus();
		return false;
	}
	if($("#height") == "")
	{
		alert("请填写广告位高度！");
		$("#height").focus();
		return false;
	}
}


//验证广告
function cfm_admanager()
{
	if($("#title").val() == "")
	{
		alert("请填写广告标识！");
		$("#title").focus();
		return false;
	}
	if($("#classid").val() == "-1")
	{
		alert("请选择投放范围！");
		$("#classid").focus();
		return false;
	}
}


//验证友情链接
function cfm_weblink()
{
	if($("#classid").val() == "-1")
	{
		alert("请选择链接类型！");
		$("#classid").focus();
		return false;
	}
	if($("#webname").val() == "")
	{
		alert("请填写站点名称！");
		$("#webname").focus();
		return false;
	}
	if($("#linkurl").val() == "")
	{
		alert("请填写链接地址！");
		$("#linkurl").focus();
		return false;
	}
}


//验证留言
function cfm_msg()
{
	if($("#nickname").val() == "")
	{
		alert("请填写昵称！");
		$("#nickname").focus();
		return false;
	}
}


//验证会员
function cfm_member()
{
	var ckuname = /^[0-9a-zA-Z_@\.-]+$/;
	if($("#username").val() == "")
	{
		alert("用户名不能为空！");
		$("#username").focus();
		return false;
	}
	else if($("#username").val().length < 6 || $("#username").val().length > 16)
	{
		alert("用户名长度为6~16位字符！");
		$("#username").focus();
		return false;
	}
	else if(!ckuname.test($("#username").val()))
	{
		alert("请使用[数字/字母/中划线/下划线/@.]！");
		$("#username").focus();
		return false;
	}

	var ckupwd = /^[0-9a-zA-Z_-]+$/;
	if($("#password").val() == "")
	{
		alert("密码不能为空！");
		$("#password").focus();
		return false;
	}
	else if($("#password").val().length < 6 || $("#password").val().length > 16)
	{
		alert("密码长度为6~16位字符！");
		$("#password").focus();
		return false;
	}
	else if(!ckupwd.test($("#password").val()))
	{
		alert("请使用[数字/字母/中划线/下划线]！");
		$("#password").focus();
		return false;
	}

	if($("#repassword").val() == "")
	{
		alert("确认密码不能为空！");
		$("#repassword").focus();
		return false;
	}
	else if($("#password").val() != $("#repassword").val())
	{
		alert("两次输入的密码不相同！");
		$("#repassword").focus();
		return false;
	}

	var ckmail = /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
	if($("#email").val() == "")
	{
		alert("E-mail不能为空！");
		$("#email").focus();
		return false;
	}
	else if(!ckmail.test($("#email").val()))
	{
		alert("E-mail格式不正确！");
		$("#email").focus();
		return false;
	}
}


//验证会员修改
function cfm_upmember()
{
	if($("#password").val() != "")
	{
		var ckupwd = /^[0-9a-zA-Z_-]+$/;
		if($("#password").val() == "")
		{
			alert("密码不能为空！");
			$("#password").focus();
			return false;
		}
		else if($("#password").val().length < 6 || $("#password").val().length > 16)
		{
			alert("密码长度为6~16位字符！");
			$("#password").focus();
			return false;
		}
		else if(!ckupwd.test($("#password").val()))
		{
			alert("请使用[数字/字母/中划线/下划线]！");
			$("#password").focus();
			return false;
		}

		if($("#repassword").val() == "")
		{
			alert("确认密码不能为空！");
			$("#repassword").focus();
			return false;
		}
		else if($("#password").val() != $("#repassword").val())
		{
			alert("两次输入的密码不相同！");
			$("#repassword").focus();
			return false;
		}

		var ckmail = /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
		if($("#email").val() == "")
		{
			alert("E-mail不能为空！");
			$("#email").focus();
			return false;
		}
		else if(!ckmail.test($("#email").val()))
		{
			alert("E-mail格式不正确！");
			$("#email").focus();
			return false;
		}
	}
	else
	{
		var ckmail = /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
		if($("#email").val() == "")
		{
			alert("E-mail不能为空！");
			$("#email").focus();
			return false;
		}
		else if(!ckmail.test($("#email").val()))
		{
			alert("E-mail格式不正确！");
			$("#email").focus();
			return false;
		}
	}
}


//验证商品添加
function cfm_goods()
{
	if($("#classid").val() == "-1")
	{
		alert("请选择所属类别！");
		$("#classid").focus();
		return false;
	}
	if($("#title").val() == "")
	{
		alert("请填写商品名称！");
		$("#title").focus();
		return false;
	}
	if($("#marketprice").val() == "")
	{
		alert("请填写商品市场价！");
		$("#marketprice").focus();
		return false;
	}
	if($("#salesprice").val() == "")
	{
		alert("请填写商品销售价！");
		$("#salesprice").focus();
		return false;
	}
	if($("#goodsid").val() == "")
	{
		alert("请填写商品编号！");
		$("#goodsid").focus();
		return false;
	}
}


//验证评论
function cfm_review()
{
	if($("#goodsid").val() == "-1")
	{
		alert("请选择商品名称！");
		$("#goodsid").focus();
		return false;
	}
	if($("#nickname").val() == "")
	{
		alert("请填写用户昵称！");
		$("#nickname").focus();
		return false;
	}
	if($("#content").val() == "")
	{
		alert("请填写评论内容！");
		$("#content").focus();
		return false;
	}
}


//验证配送区域
function cfm_postarea()
{
	if($("#classname").val() == "")
	{
		alert("请填写配送区域名称！");
		$("#classname").focus();
		return false;
	}
	if($("#freight").val() == "")
	{
		alert("请填写配送区域价格！");
		$("#freight").focus();
		return false;
	}
}


//验证自定义菜单
function cfm_diymenu()
{
	if($("#classname").val() == "")
	{
		alert("请填写菜单名称！");
		$("#classname").focus();
		return false;
	}
}


//自定义模型
function cfm_diymodel()
{
	if($("#modelname").val() == "")
	{
		alert("请填写模型标识！");
		$("#modelname").focus();
		return false;
	}
	if($("#modeltitle").val() == "")
	{
		alert("请填写模型名称！");
		$("#modeltitle").focus();
		return false;
	}
	if($("#modeltbname").val() == "")
	{
		alert("请填写模型表名！");
		$("#modeltbname").focus();
		return false;
	}
}


//投票选项
function cfm_vote()
{
	if($("#title").val() == "")
	{
		alert("请填写投票标题！");
		$("#title").focus();
		return false;
	}
}


//导航菜单
function cfm_nav()
{
	if($("#classname").val() == "")
	{
		alert("请填写导航名称！");
		$("#classname").focus();
		return false;
	}
	if($("#linkurl").val() == "")
	{
		alert("请填写链接地址！");
		$("#linkurl").focus();
		return false;
	}
}


//自定义字段验证
function cfm_diyfield()
{
	if($("#fieldname").val() == "")
	{
		alert("请填写字段名称！");
		$("#fieldname").focus();
		return false;
	}
	if($("#fieldtitle").val() == "")
	{
		alert("请填写字段标题！");
		$("#fieldtitle").focus();
		return false;
	}
	if($("#fieldlong").val() == "")
	{
		var fieldtype = $('input[name="fieldtype"]:checked').val();
		if(fieldtype != "text" && fieldtype != "mediumtext" && fieldtype != "fileall" && fieldtype != "datetime")
		{
			alert("请填写字段长度！");
			$("#fieldlong").focus();
			return false;
		}
	}
}


//自定义模型数据添加
function cfm_modeldata()
{
	if($("#classid").val() == "-1")
	{
		alert("请选择所属栏目！");
		$("#classid").focus();
		return false;
	}
}


//碎片数据
function cfm_fragment()
{
	if($("#title").val() == "")
	{
		alert("请填写碎片数据标识！");
		$("#title").focus();
		return false;
	}
}


//管理组权限选择
function SelPriv(siteid, obj)
{
	if (obj) {
		$("input[type='checkbox'][name^='siteid["+siteid+"]']").attr('checked', 'checked');
		$("input[type='checkbox'][name^='priv["+siteid+"]']").attr('checked', 'checked');
	} else {
		$("input[type='checkbox'][name^='siteid["+siteid+"]']").removeAttr('checked');
		$("input[type='checkbox'][name^='priv["+siteid+"]']").removeAttr('checked');
	}
}

function SelRole(siteid, name, obj)
{
	if (obj.checked) {
		$("input[type='checkbox'][name='priv["+siteid+"]["+name+"][]']").attr('checked', 'checked');
	} else {
		$("input[type='checkbox'][name='priv["+siteid+"]["+name+"][]']").removeAttr('checked');
	}
}


function SelModel(obj)
{
	if (obj) {
		$("input[type='checkbox'][name='model[]']").attr('checked', 'checked');
	} else {
		$("input[type='checkbox'][name='model[]']").removeAttr('checked');
	}
}
