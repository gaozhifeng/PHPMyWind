<?php	require_once(dirname(__FILE__).'/inc/config.inc.php');

/*
**************************
(C)2010-2015 phpMyWind.com
update: 2014-5-22 21:58:57
person: Feng
**************************
*/


//初始化参数
$action = isset($action) ? $action : '';


//设置当前站点
if($action == 'selsite')
{
	if(isset($sitekeyvalue))
	{
		$r = $dosql->GetOne("SELECT `id`,`sitekey` FROM `#@__site` WHERE `sitekey`='$sitekeyvalue'");
		if(isset($r['id']))
		{
			$_SESSION['siteid']  = $r['id'];
			$_SESSION['sitekey'] = $r['sitekey'];
		}
		else
		{
			$r = $dosql->GetOne("SELECT `id`,`sitekey` FROM `#@__site` ORDER BY `id` ASC");
			if(isset($r['id']))
			{
				$_SESSION['siteid']  = $r['id'];
				$_SESSION['sitekey'] = $r['sitekey'];
			}
			else
			{
				$_SESSION['siteid']  = '';
				$_SESSION['sitekey'] = '';
			}
		}
	}

	//大后台不刷新左侧菜单
	if(!empty($_SESSION['t_adminlevel']))
		echo 2;
	else if($_SESSION['adminlevel'] == 1)
		echo 1;
	else
		echo 2;

	exit();
}


//设置当前权限
if($action == 'selpriv')
{
	//超级管理员才可以切换权限
	if($_SESSION['adminlevel'] == 1 && !empty($privid))
	{
		//第一次进行切换
		if(empty($_SESSION['t_adminlevel']) &&
		   $privid == 1)
		{
			echo 2;
		}

		//如果进行过切换，但本次切换和上次切换相同
		else if(!empty($_SESSION['t_adminlevel']) &&
		   $_SESSION['t_adminlevel'] == $privid)
		{
			echo 2;
		}

		//如果进行过切换，本次为新的切换
		else if(!empty($_SESSION['t_adminlevel']) &&
		   $_SESSION['t_adminlevel'] != $privid)
		{
			$_SESSION['t_adminlevel'] = $privid;

			if($privid == 1)
			{
				$_SESSION['t_adminlevel'] = '';
				unset($_SESSION['t_adminlevel']);
			}

			echo 1;
		}
		else
		{
			$_SESSION['t_adminlevel'] = $privid;

			echo 1;
		}
	}

	exit();
}


//网站信息配置
//删除登录背景
if($action == 'delloginbg')
{
	if(isset($delloginbg))
	{
		unlink(dirname(__FILE__).'/'.$delloginbg);
	}

	echo TRUE;
	exit();
}


//修改单页信息
//单页二级类别
if($action == 'infomaintype')
{
	IsModelPriv('info');

	$r = $dosql->GetOne("SELECT * FROM `#@__info` WHERE `classid`=$classid AND `mainid`=$mainid");
	if(is_array($r))
		echo $r['content'].'[-||-]'.$r['picurl'].'[-||-]'.GetDateTime($r['posttime']);
	else
		echo '[-||-][-||-]'.GetDateTime(time());

	exit();
}


//自定义字段
//栏目选择权限
if($action == 'diyfield')
{
	IsModelPriv('diyfield');

	$type = isset($type) ? $type : '';

	$dosql->Execute("SELECT * FROM `#@__infoclass` WHERE `infotype`='$type' ORDER BY orderid ASC");
	if($dosql->GetTotalRow())
	{
		while($row = $dosql->GetArray())
		{
			echo '<span><input type="checkbox" name="classid[]" value="'.$row['id'].'" /> '.$row['classname'].'</span>';
		}
	}
	else
	{
		echo '该模型下暂无栏目';
	}

	exit();
}


//自定义字段
//添加信息权限
if($action == 'infoclass')
{
	$id  = isset($id)  ? $id  : 0;
	$row = isset($row) ? $row : '';


	$r = $dosql->GetOne("SELECT `infotype` FROM `#@__infoclass` WHERE `id`=$id");
	if(isset($r['infotype']))
		$type = $r['infotype'];
	else
		$type = '';


	echo GetDiyField($type, $id, $row);
	exit();
}


//栏目管理
//获取栏目缩略图大小
if($action == 'catpsize')
{
	IsModelPriv('infoclass');

	$str = '';

	$r = $dosql->GetOne("SELECT `picwidth`,`picheight` FROM `#@__infoclass` WHERE `id`=".$pid);
	if(isset($r['picwidth']))
		$str .= $r['picwidth'];

	if(isset($r['picheight']))
		$str .= '|'.$r['picheight'];

	echo $str;
	exit();
}


//会员管理
//检测用户是否存在
if($action == 'checkuser')
{
	IsModelPriv('member');

	$r = $dosql->GetOne("SELECT `username` FROM `#@__member` WHERE username='$username'");

	if(!is_array($r))
		echo '<span class="reok">可以使用</span>';
	else
		echo '<span class="renok">用户名已存在</span>';
	exit();
}


//会员管理
//获取级联
if($action == 'getarea')
{
	$datagroup = isset($datagroup) ? $datagroup : '';
	$level     = isset($level)     ? $level     : '';
	$v         = isset($areaval)   ? $areaval   : '0';


	$str = '<option value="-1">--</option>';
	$sql = "SELECT * FROM `#@__cascadedata` WHERE level=$level And ";

	if($v == 0)
		$sql .= "datagroup='$datagroup'";
	else if($v % 500 == 0)
		$sql .= "datagroup='$datagroup' AND datavalue>$v AND datavalue<".($v + 500);
	else
		$sql .= "datavalue LIKE '$v.%%%' AND datagroup='$datagroup'";

	$sql .= " ORDER BY orderid ASC, datavalue ASC";


	$dosql->Execute($sql);
	while($row = $dosql->GetArray())
	{
		$str .= '<option value="'.$row['datavalue'].'">'.$row['dataname'].'</option>';
	}

	if($str == '') $str .= '<option value="-1">--</option>';
	echo $str;
	exit();
}


//商品信息管理
//获取商品属性
if($action == 'goodsattr')
{
	IsModelPriv('goods');


	if($tid != '-1')
	{
		$dosql->Execute("SELECT * FROM `#@__goodsattr` WHERE `goodsid`=$tid");
		if($dosql->GetTotalRow() > 0)
		{
			$i = 1;
			while($row = $dosql->GetArray())
			{

	 			echo '<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr>
					  <td width="25%" height="40" align="right">'.$row['attrname'].'：</td>
					  <td><input type="text" name="attrvalue[]" id="attrvalue[]" class="input" />
					      <input type="hidden" name="attrid[]" id="attrid[]" value="'.$row['id'].'">';

				if($i <= 1)
					echo '<span class="cnote">不同属性值用 <span class="maroon2">|</span> 隔开，例如：黑色|白色 等</span>';

				echo '</td></tr></table>';

				$i++;
			}
		}
		else
		{
			echo '<div style="text-align:center;color:#9C0;">暂无自定义属性，您可以在商品类别中进行绑定</div>';
		}
	}
	else
	{
		echo '<div style="text-align:center;color:#9C0;">请选择商品类别获取自定义属性</div>';
	}

	exit();
}


//信息模块
//回收站
if($action == 'recycel' or $action == 'reset' or $action == 'del' or
   $action == 'resetall' or $action == 'delall' or $action == 'empty')
{
	if(isset($type))
	{
		$tbname = '#@__'.$type;
	}
	else
	{
		echo '参数错误，没有获取到表名称';
		exit();
	}


	//选择执行操作
	switch($action)
	{
		case 'reset':
			$sql = "UPDATE `$tbname` SET delstate='', deltime=0 WHERE id=$id";
			$dosql->ExecNoneQuery($sql);
			break;

		case 'del':
			$sql = "DELETE FROM `$tbname` WHERE id=$id";
			$dosql->ExecNoneQuery($sql);
			break;

		case 'resetall':
			$sql = "UPDATE `$tbname` SET delstate='', deltime=0 WHERE id IN ($ids)";
			$dosql->ExecNoneQuery($sql);
			break;

		case 'delall':
			$sql = "DELETE FROM `$tbname` WHERE id IN ($ids)";
			$dosql->ExecNoneQuery($sql);
			break;

		case 'empty':
			$sql = "DELETE FROM `$tbname` WHERE delstate='true'";
			$dosql->ExecNoneQuery($sql);
			break;
		default:
	}

	//Ajax输出数据
	$dosql->Execute("SELECT * FROM `$tbname` WHERE delstate='true' ORDER BY deltime DESC");

	if($dosql->GetTotalRow() == 0)
	{
		echo '暂无内容';
		exit();
	}
	else
	{
		while($row = $dosql->GetArray())
		{

			$r = $dosql->GetOne("SELECT `classname` FROM `#@__infoclass` WHERE id=".$row['classid']);
			if(isset($r['classname']))
				$classname = $r['classname'].' ['.$row['classid'].']';
			else
				$classname = '分类已删 ['.$row['classid'].']';

			 echo '<table width="98%" border="0" align="center" cellpadding="0" cellspacing="0" class="dataTable">
					<tr align="left" class="dataTr" onmouseover="this.className=\'dataTrOn\'" onmouseout="this.className=\'dataTr\'">
					 <td width="30" height="28" class="firstCol"><input type="checkbox" name="checkid[]" id="checkid[]" value="'.$row['id'].'" /></td>
					 <td width="30">'.$row['id'].'</td>
					 <td><span class="title" title="删除日期：'.GetDateTime($row['deltime'])."\n".'所属栏目：'.$classname.'">'.$row['title'].'</span></td>
					 <td width="90" class="action endCol"><span><a href="javascript:;" onclick="RecycleRe(\'reset\','.$row['id'].')">还原</a></span><span class="nb"><a href="javascript:;" onclick="RecycleRe(\'del\','.$row['id'].')">删除</a></span></td>
			  	 </tr>
		      	</table>';
		}

		exit();
	}
}


//管理首页
//是否保存便签
if($action == 'adminnotes')
{
	$uname    = $_SESSION['admin'];
	$body     = trim($body);
	$posttime = time();
	$postip   = GetIP();

	if($dosql->GetOne("SELECT `uname` FROM `#@__adminnotes` WHERE uname='$uname'"))
	{
		$sql = "UPDATE `#@__adminnotes` SET body='$body', posttime='$posttime', postip='$postip' WHERE uname='$uname'";
		$dosql->ExecNoneQuery($sql);
	}
	else
	{
		$sql = "INSERT INTO `#@__adminnotes` (uname, body, posttime, postip) VALUES ('$uname', '$body', '$posttime', '$postip')";
		$dosql->ExecNoneQuery($sql);
	}

	exit();
}


//删除记事本
if($action == 'deladminnotes')
{
	$sql = "DELETE FROM `#@__adminnotes` WHERE `uname`='".$_SESSION['admin']."'";
	$dosql->ExecNoneQuery($sql);
	exit();
}


//生成验证缓存
if($action == 'updataauth')
{
	$fdir  = PHPMYWIND_DATA.'/cache/auth/';
	$fname = 'auth_'.$cfg_auth_key.'.php';


	//是否存在缓存
	Writef($fdir.$fname, $jsonStr);


	echo TRUE;
	exit();
}


//切换访问设备
if($action = 'selsiteeq')
{
	$eq = isset($eq) ? $eq : 'pc';

	if($eq == 'pc')
		$_SESSION['siteeq'] = 'pc';
	else if($eq == 'mobile')
		$_SESSION['siteeq'] = 'mobile';
	else
		$_SESSION['siteeq'] = 'pc';

	echo TRUE;
	exit();
}


//无条件返回
else
{
	exit('Request Error!');
}
?>
