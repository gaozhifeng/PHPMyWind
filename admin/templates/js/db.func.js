
/*
**************************
(C)2010-2015 phpMyWind.com
update: 2012-8-6 9:19:43
person: Feng
**************************
*/


/*
 * 全选函数(文字按钮)
 *
 * @parma  form  string  表单的名称
 * @parma  mode  string  选择多选的状态
 */

function CheckAll(form, mode)
{
	for(var i=0; i<form.elements.length; i++)
	{
		var e = form.elements[i];
		if(e.name.indexOf('tbname') != -1 && e.disabled != true)
		{
			e.checked = mode;
		}
	}

	form.checkall.checked = mode;
}


/*
 * 全选函数(多选按钮)
 *
 * @parma  form  string  表单的名称
 */

function CheckAllBtn(form)
{
	for(var i=0; i<form.elements.length; i++)
	{
		var e = form.elements[i];
		if(e.name.indexOf('tbname') != -1 && e.disabled != true)
		{
			e.checked = form.checkall.checked;
		}
	}
}


/*
 * 删除提示
 *
 * @parma  form  string  表单的名称
 */
function ConfDel(form)
{
	for(var i=1; i < form.elements.length; i++)
	{
		var e = form.elements[i];
		if((e.name).indexOf('tbname') != -1)
		{
			if(e.checked == true)
			{
				var ischecked = 'true';
			}
		}
	}
	if(ischecked == 'true')
	{
		if(confirm("确定要删除选中的备份文件吗？")) return true;
		else return false;
	}
	else
	{
		alert('没有选中任何备份文件！');
		return false;
	}
}


/*
 * 表单提交函数
 *
 * @parma  dopost  string  操作字符串
 */
function Repair(dopost)
{
    document.forms[0].action = dopost;
    document.forms[0].submit();
}


/*
 * 隐藏层函数
 */
function HideDiv(id)
{
	document.getElementById(id).style.display = 'none';
}
