<?php require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('cascade'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>级联数据管理</title>
<link href="templates/style/admin.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/js/forms.func.js"></script>
</head>
<body>
<?php

//初始化参数
$sign = isset($sign) ? $sign : '';
$str  = '';
$v    = '';

if($sign != '')
{
	$r = $dosql->GetOne("SELECT `groupname` FROM `#@__cascade` WHERE `groupsign`='$sign'");
	$signstr = $r['groupname'].'&nbsp;|&nbsp;'.$sign;
}
else
{
	$signstr = '<strong>无标识</strong>';
}
?>
<div class="topToolbar"> <span class="title">级联数据管理</span> <span class="text">级联标识：<a href="cascade.php"><?php echo $signstr; ?></a></span> <a href="javascript:location.reload();" class="reload">刷新</a></div>
<form name="form" id="form" method="post" action="cascadedata_save.php?action=save">
	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="dataTable">
		<tr align="left" class="head">
			<td width="5%" height="36" class="firstCol"><input type="checkbox" name="checkid" id="checkid" onclick="CheckAll(this.checked);"></td>
			<td width="5%">枚举值</td>
			<td width="50%">
			<?php

			if($sign != '')
			{
				$r = $dosql->GetOne("SELECT MAX(level) as level FROM `#@__cascadedata` WHERE `datagroup`='$sign'");
				if(isset($r['level']))
				{
					//获取级联层级，循环获取变量
					for($i=0; $i<=$r['level']; $i++)
					{
						for($u=0; $u<=$r['level']; $u++)
						{
							$_GET['v'.$i] = isset($_GET['v'.$i]) ? $_GET['v'.$i] : '';
						}
			
						if($i=='0' and $_GET['v'.$i]=='')
						{
							$_GET['v'.$i] = '0';
						}
			
						if($_GET['v'.$i] != '')
						{
							$str .= '<select name="select" onchange="location.href=\'?sign='.$sign;

							for($z=1; $z<=$i; $z++)
							{
								$str .= '&v'.$z.'='.$_GET['v'.$z];
							}
			
							$str .= '&v'.($i+1).'=\'+this.value;">';
							$str .= '<option value="0">请选择</option>';
			
							$sql = "SELECT * FROM `#@__cascadedata` WHERE level=$i AND ";
			
							
							//判断是否为1级联动
							if($_GET['v'.$i] == 0)
								$sql .= "datagroup='$sign'";
							else if($_GET['v'.$i] % 500 == 0)
								$sql .= "datagroup='$sign' AND datavalue>".$_GET['v'.$i]." AND datavalue<".($_GET['v'.$i] + 500);
							else
								$sql .= "datavalue LIKE '".$_GET['v'.$i].".%%%' AND datagroup='$sign'";
							
							$sql .= " ORDER BY orderid ASC, datavalue ASC";
			
							$dosql->Execute($sql);
							while($row = $dosql->GetArray())
							{
								$s = $i+1;
								if(@$_GET['v'.$s] == $row['datavalue'])
									$selected = 'selected="selected"';
								else
									$selected = '';

								$str .= '<option value="'.$row['datavalue'].'" '.$selected.'>'.$row['dataname'].'</option>';
							}
			
							$str .= '</select>&nbsp;&nbsp;';
						}
					}
			
					echo $str;
				}
			}
			?>
			</td>
			<td width="20%" align="center">排序</td>
			<td width="20%" class="endCol">操作</td>
		</tr>
		<?php
		if($sign != '')
		{
			for($i=1; $i<=$r['level']+1; $i++)
			{
				if(!empty($_GET['v'.$i]))
				{
					$v = $_GET['v'.$i];
				}
			}
		
			if($v != '')
			{
				//判断是否为1级联动
				if($v % 500 == 0)
					$sql = "WHERE datagroup='$sign' AND datavalue>$v AND datavalue<".($v + 500);
				else
					$sql = "WHERE datavalue LIKE '$v.%%%' AND datagroup='$sign'";
			}
			else
			{
				$sql = "WHERE level=0 AND datagroup='$sign'";
			}
		
			$sql = "SELECT * FROM `#@__cascadedata` $sql ORDER BY orderid ASC, datavalue ASC";
			
			$dosql->Execute($sql);
			if($dosql->GetTotalRow() > 0)
			{
				while($row = $dosql->GetArray())
				{
		?>
		<tr align="left" class="dataTr">
			<td height="36" class="firstCol"><input type="checkbox" name="checkid[]" id="checkid[]" value="<?php echo $row['id']; ?>"></td>
			<td><?php echo $row['datavalue']; ?></td>
			<td><input type="text" name="dataname[]" id="dataname[]" class="inputd" value="<?php echo $row['dataname']; ?>" />
			<input type="hidden" name="id[]" id="id[]" value="<?php echo $row['id']; ?>" /></td>
			<td align="center"><input type="text" name="orderid[]" id="orderid[]" class="inputls" value="<?php echo $row['orderid']; ?>" /></td>
			<td class="action endCol"><a href="cascadedata_save.php?action=delclass&amp;sign=<?php echo $sign; ?>&amp;id=<?php echo $row['id']; ?>" onclick="return ConfDel(0);">删除</a></td>
		</tr>
		<?php
				}
			}
			else
			{
		?>
		<tr align="center">
			<td colspan="5" class="dataEmpty">暂时没有相关的记录</td>
		</tr>
		<?php
			}
		}
		else
		{
		?>
		<tr>
			<td colspan="5" class="dataEmpty">暂时没有相关的记录</td>
		</tr>
		<?php
		}
		?>
		<tr align="center">
			<td height="36" colspan="5"><strong>新增一个级联数据</strong></td>
		</tr>
		<tr align="left" class="dataTrOn">
			<td height="36">&nbsp;</td>
			<td><input name="datavalue_add" type="text" id="datavalue_add" class="inputls" value="" /></td>
			<td class="title"><input type="text" name="dataname_add" id="dataname_add" class="input" value="" /></td>
			<td align="center">
			<?php
			$r2 = $dosql->GetOne("SELECT Max(orderid) AS orderid FROM `#@__cascadedata` WHERE `datagroup`='$sign'");
			$orderid = (empty($r2['orderid']) ? 1 : ($r2['orderid'] + 1));
			?>
			<input name="orderid_add" type="text" id="orderid_add" class="inputls" value="<?php echo $orderid; ?>" /></td>
			<td>
			<?php
			$level = 0;
			for($i=1; $i<=$r['level']+1; $i++)
			{
				if(!empty($_GET['v'.$i]))
				{
					$level = $i;
				}
			}
			?>
			<input type="hidden" name="level_add" id="level_add" value="<?php echo $level; ?>" />
			<input type="hidden" name="datagroup_add" id="datagroup_add" value="<?php echo $sign; ?>" />
			<input type="hidden" name="sign" id="sign" value="<?php echo $sign; ?>" />
			</td>
		</tr>
	</table>
</form>
<div class="bottomToolbar"><span class="selArea"><span>选择：</span> <a href="javascript:CheckAll(true);">全部</a> - <a href="javascript:CheckAll(false);">无</a> - <a href="javascript:SubUrlParam('cascadedata_save.php?action=delallclass');" onclick="return ConfDelAll(2);">删除</a>　<span>操作：</span><a href="javascript:UpOrderID('cascadedata_save.php');">更新排序</a></span> <a href="javascript:void(0);" onclick="form.submit();return false;" class="dataBtn">更新全部</a><a href="cascade.php" class="dataBack">返回级联</a></div>
<div class="page">
	<div class="pageText">共有<span>
	<?php
	$r = $dosql->GetOne("SELECT COUNT(id) as `total` FROM `#@__cascadedata` WHERE `datagroup`='$sign'");
	echo $r['total'];
	?>
	</span>条记录</div>
</div>
<?php

//判断是否启用快捷工具栏
if($cfg_quicktool == 'Y')
{
?>
<div class="quickToolbar">
	<div class="qiuckWarp">
		<div class="quickArea"><span class="selArea"><span>选择：</span> <a href="javascript:CheckAll(true);">全部</a> - <a href="javascript:CheckAll(false);">无</a> - <a href="javascript:SubUrlParam('cascadedata_save.php?action=delallclass');" onclick="return ConfDelAll(2);">删除</a>　<span>操作：</span><a href="javascript:UpOrderID('cascadedata_save.php');">更新排序</a></span> <a href="javascript:void(0);" onclick="form.submit();return false;" class="dataBtn">更新全部</a><span class="pageSmall">
			<div class="pageText">共有<span><?php echo $r['total']; ?></span>条记录&nbsp;&nbsp;<a href="cascade.php">返回级联</a></div>
			</span></div>
		<div class="quickAreaBg"></div>
	</div>
</div>
<?php
}
?>
</body>
</html>