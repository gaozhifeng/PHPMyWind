<?php require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('check_bom'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>BOM检查</title>
<link href="templates/style/admin.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/js/forms.func.js"></script>
</head>
<body>
<div class="topToolbar"> <span class="title">BOM检查</span> <a href="javascript:location.reload();" class="reload">刷新</a></div>
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="dataTable">
	<tr class="head">
		<td height="30" colspan="2">&nbsp;技巧提示</td>
	</tr>
	<tr>
		<td align="left" colspan="2"><div class="checkBomTxt">BOM只有在WINDOWS下采用“记事本”存储为UTF-8时才会有。在UTF-8编码文件中BOM在文件头部，占用三个字节，以暗码的形式存在，用来标示该文件属于UTF-8编码，现在已经有很多软件识别BOM头，但是还有些不能识别BOM头，例如PHP就不能识别BOM头，这也是用记事本编辑UTF-8编码后执行就会出错的原因了。<br />
				<span class="red">具体表现为：Cannot modify header information – headers already sent by ….</span></div></td>
	</tr>
	<?php
	if(isset($action))
	{
		//初始化参数
		$autocl = isset($autocl) ? $autocl : '';

		//修改此行为需要检测的目录，点表示当前目录
		$check_dir = PHPMYWIND_ROOT.'/'.$dir;

		if(is_dir($check_dir) &&
		   ($dh = opendir($check_dir)))
		{
			while(($file = readdir($dh)) !== false)
			{
				$gbfile = mb_convert_encoding($file, 'utf-8', 'gb2312');
				if($file!='.' && $file!='..' &&
				   !is_dir($check_dir.'/'.$file))
				{
	?>
	<tr class="dataTr">
		<td width="50%" height="30"><span style="padding-left:10px;">文件名：</span> <?php echo $gbfile; ?></td>
		<td><?php echo CheckBOM($check_dir.'/'.$file, $autocl); ?></td>
	</tr>
	<?php
				}
			}
			closedir($dh);
		}
		else
		{
	?>
	<tr>
		<td height="100" colspan="2" align="center"><strong>目录 <span class="blue"><?php echo $dir.'/'; ?></span> 打开失败，请检查该目录是否存在或可读！</strong></td>
	</tr>
	<?php
		}
	}
	?>
</table>
<div class="bottomToolbar"> <a href="javascript:void(0);" onclick="form.submit();return false;" class="dataBtn">开始检查BOM</a>
	<form name="form" action="?" method="get">
		<span class="inputArea">处理方式：
		<input type="checkbox" name="autocl" id="autocl" value="1" checked="checked" />
		<input type="hidden" name="action" id="action" value="check" />
		&nbsp;自动清除&nbsp;&nbsp;
		检查目录：
		<input type="text" name="dir" id="dir" value="<?php echo ($dir = isset($dir) ? $dir : '/'); ?>" size="8" />
		&nbsp;(默认为根目录)
		&nbsp;&nbsp;<a href="check_bom.php">清空查询</a>&nbsp;&nbsp;&nbsp;</span>
	</form>
</div>
<?php

/*
 * 函数说明：BOM检查
 *
 * @param  $filename   string   检查的文件
 * @param  $autoclear  int      是否自动清除
 * @return             string   返回查询结果
 */
function CheckBOM($filename, $autoclear)
{
	$contents   = file_get_contents($filename);
	$charset[1] = substr($contents, 0, 1);
    $charset[2] = substr($contents, 1, 1);
    $charset[3] = substr($contents, 2, 1);

	if(ord($charset[1])==239 &&
	   ord($charset[2])==187 &&
	   ord($charset[3])==191)
	{
		//自动清除
		if($autoclear == 1)
		{
			$contstr = substr($contents,3);
			Writef($filename, $contstr);

        	return '<span class="red">发现BOM, 已自动清除</span>';
        }
		else
		{
			return '<span class="red">发现BOM</span>';
        }
    }
    else
	{
		return '<span class="green">没有发现BOM</span>';
	}
}
?>
</body>
</html>