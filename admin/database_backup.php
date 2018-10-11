<?php	require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('database_backup');


//定义登入常量
define('IN_BKUP', TRUE);


//初始化变量
$action = isset($action) ? $action : 'export';
$dopost = isset($dopost) ? $dopost : '';
$tbname = isset($tbname) ? $tbname : '';
$backup_dir = PHPMYWIND_BACKUP.'/';

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>数据库管理</title>
<link href="templates/style/admin.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/js/forms.func.js"></script>
<script type="text/javascript" src="templates/js/db.func.js"></script>
</head>
<body>
<div class="topToolbar"> <span class="title">数据库管理</span> 
	<?php
	if($dopost == 'struct') echo "<span class='text'>表结构：<a href='?action=$action'>$tbname</a></span>";
	if($dopost == 'sqldir') echo "<span class='text'>目录：<a href='?action=$action'>/$tbname/</a></span>";
	?> <a href="javascript:location.reload();" class="reload">刷新</a></div>
<div class="toolbarTab">
	<ul>
		<li <?php if($action == 'export') echo 'class="on"'; ?>><a href="?action=export">数据库操作</a></li>
		<li class="line">-</li>
		<li <?php if($action == 'import') echo 'class="on"'; ?>><a href="?action=import">数据库还原</a></li>
		<li class="line">-</li>
		<li <?php if($action == 'query') echo 'class="on"'; ?>><a href="?action=query">执行SQL语句</a></li>
	</ul>
</div>
<?php

//判断执行操作
switch($action)
{
	case 'export':


		//备份数据表
		if($dopost == 'backup')
		{
			if(empty($tbname))
			{
				ShowDataMsg('<span class="red">请选择要备份的数据表!</span>','?action='.$action);
				exit();
			}
			else
			{
				require_once('database_done.php');
				exit();
			}
		}


		//查看表结构
		else if($dopost == 'struct')
		{
			if(empty($tbname))
			{
				ShowDataMsg('<span class="red">请选择要查看的数据表!</span>','?action='.$action);
				exit();
			}
			else
			{
				require_once('database_struct.php');
				exit();
			}
		}


		//修复数据表
		else if($dopost == 'repair')
		{
			if(empty($tbname))
			{
				ShowDataMsg('<span class="red">没有指定要修复的表名!</span>','?action='.$action);
				exit();
			}

			if(is_array($tbname))
			{
				foreach($tbname as $k=>$v)
				{
					$dosql->ExecNoneQuery("REPAIR TABLE `$v`");
				}
			}
			else
			{
				$dosql->ExecNoneQuery("REPAIR TABLE `$tbname`");
			}

			ShowDataMsg('<span class="blue">数据表修复完毕!</span>','?action='.$action);
			exit();
		}


		//优化数据表
		else if($dopost == 'optimize')
		{
			if(empty($tbname))
			{
				ShowDataMsg('<span class="red">没有指定要优化表名!</span>','?action='.$action);
				exit();
			}

			if(is_array($tbname))
			{
				foreach($tbname as $k => $v)
				{
					$dosql->ExecNoneQuery("OPTIMIZE TABLE `$v`");
				}
			}
			else
			{
				$dosql->ExecNoneQuery("OPTIMIZE TABLE `$tbname`");
			}
			
			ShowDataMsg('<span class="blue">数据表优化完毕!</span>','?action='.$action);
			exit();
		}


		//无action指令则展示数据表
		else
		{
			$name = $num = $size = array();
			$i = $total_size = 0;

			$dosql->Execute("SHOW TABLE STATUS");
			while($r = $dosql->GetArray())
			{
				$name[$i]    = $r['Name'];
				$rows[$i]    = $r['Rows'];
				$size[$i]    = GetRealSize($r['Data_length']);
				$total_size += $r['Data_length'];
				$i++;
			}
			
			require_once('database_export.php');
			exit();
		}
	break;


	case 'import':


		//还原数据
		if($dopost == 'reset')
		{
			if(empty($tbname))
			{
				ShowDataMsg('<span class="red">请选择要还原的数据表!</span>','?action='.$action);
				exit();
			}
			else
			{
				require_once('database_done.php');
				exit();
			}
		}


		//删除备份目录
		else if($dopost == 'deldir')
		{
			$backup_file = $backup_dir.$tbname.'/';

			if(!file_exists($backup_file))
			{
				ShowDataMsg("<span class='red'>没有找到 $tbname 备份目录！</span>",'?action='.$action);
				exit();
			}
			else
			{
				DelDataDir($backup_file);
				ShowDataMsg("<span class='blue'>删除备份目录 $tbname 成功！</span>",'?action='.$action);
				exit();
			}
		}


		//删除全部目录
		else if($dopost == 'deldirall')
		{
			$oknum = 0;

			for($i=0; $i<count($tbname); $i++)
			{
				$backup_file = $backup_dir.$tbname[$i].'/';
				if(file_exists($backup_file))
				{
					DelDataDir($backup_file);
					$oknum++;
				}
			}

			ShowDataMsg("<span class='blue'>成功删除 $oknum 备份目录！</span>",'?action='.$action);
			exit();
		}


		//删除.sql文件
		else if($dopost == 'del')
		{
			$backup_file = $backup_dir.$dirname.'/'.$tbname;

			if(!file_exists($backup_file))
			{
				ShowDataMsg("<span class='red'>没有找到 $tbname 备份文件！</span>",'?action='.$action.'&dopost=sqldir&tbname='.$dirname);
				exit();
			}
			else
			{
				unlink($backup_file);
				ShowDataMsg("<span class='blue'>删除备份文件 $tbname 成功！</span>",'?action='.$action.'&dopost=sqldir&tbname='.$dirname);
				exit();
			}
		}


		//删除全部.sql文件
		else if($dopost == 'delall')
		{
			$oknum = 0;

			for($i=0; $i<count($tbname); $i++)
			{
				$backup_file = $backup_dir.$dirname.'/'.$tbname[$i];
				if(file_exists($backup_file))
				{
					unlink($backup_file);
					$oknum++;
				}
			}
	
			ShowDataMsg("<span class='blue'>成功删除 $oknum 备份文件！</span>",'?action='.$action.'&dopost=sqldir&tbname='.$dirname);
			exit();
		}


		//展示.sql文件列表
		else if($dopost == 'sqldir')
		{
			$backup_file = $backup_dir.$tbname.'/';

			if(!file_exists($backup_file))
			{
				ShowDataMsg("<span class='red'>没有找到 $tbname 备份目录！</span>",'?action='.$action);
				exit();
			}

			$backup_files = glob($backup_file.'*.txt');

			if(is_array($backup_files))
			{
				$files_size = 0;
				foreach($backup_files as $name)
				{
					$files['name']   = basename($name);
					$files['size']   = GetRealSize(filesize($name));
					$files['mktime'] = GetDateTime(filemtime($name));
					$files_size += filesize($name);
					$bfiles[] = $files;
				}
			}

			require_once('database_sqldir.php');
			exit();
		}


		//无dopost指令则展示备份目录列表
		else
		{

			$handler = opendir($backup_dir);
			$i = $total_size = 0;
			while(($fname = readdir($handler)) !== false)
			{
				if($fname != '.' && $fname != '..' && $fname != 'index.htm' && $fname != 'index.html')
				{
					$files['name'] = $fname;
					$files['mktime'] = GetDateTime(filemtime($backup_dir.$fname));

					$backup_file = glob($backup_dir.$fname.'/*.txt');
					$files_size = 0;

					foreach($backup_file as $name)
					{
						$files_size += filesize($name);
					}

					$files['size'] = GetRealSize($files_size);
					$total_size += $files_size;
					$bfiles[] = $files;
				}
				$i++;
			}

			closedir($handler);
			require_once('database_import.php');
			exit();
		}
	break;


	case 'query':


		//执行SQL语句
		if($dopost == 'runsql')
		{

			//整理需要执行SQL语句
			$sqlquery = trim(stripslashes($sqlquery));


			if(empty($sqlquery))
			{
				ShowDataMsg('<span class="red">请输入要执行的SQL语句。</span>','?action='.$action);
				exit();
			}


			if(preg_match("#^drop(.*)table#i", $sqlquery) or
			   preg_match("#^drop(.*)database#i", $sqlquery))
			{
				ShowDataMsg('<span class="red">删除\'数据表\'或\'数据库\'的语句不允许在这里执行。</span>','?action='.$action);
				exit();
			}


			//运行查询语句
			if(preg_match("#^select #i", $sqlquery) or
			   preg_match("#^show #i", $sqlquery))
			{

				$dosql->Execute($sqlquery);
				if($dosql->GetTotalRow() <= 0)
				{
					ShowDataMsg('<span class="blue">运行SQL：'.$sqlquery.'，无返回记录！</span>','-1');
					exit();
				}
				else
				{
					ShowDataMsg('<span class="blue">运行SQL：'.$sqlquery.'，共有 '.$dosql->GetTotalRow().'条记录，最大返回100条！</span>','-1');
					$j = 0;
					while($row = $dosql->GetArray())
					{
						$j++;
						if($j > 100)
						{
							break;
						}

						echo "<div style='border-bottom:1px dotted #666;padding:10px 0;margin-bottom:8px;'>记录：$j</div>";

						foreach($row as $k=>$v)
						{
							echo "<span style='color:#900'>{$k}：</span><span style='color:#039;'>{$v}</span><br />";
						}
					}
					exit();
				}
			}


			//多行SQL语句
			if($querytype == 2)
			{
				$sqlquery = str_replace("\r", "", $sqlquery);
				$sqls = preg_split("#;[\t]{0,}\n#", $sqlquery);
				$i = 0;
				foreach($sqls as $q)
				{
					$q = trim($q);
					if($q == '') continue;
					
					$dosql->ExecNoneQuery($q);
					$i++;
				}

				ShowDataMsg('<span class="blue">成功执行{'.$i.'}个SQL语句！</span>','?action='.$action);
				exit();
			}

			//单行SQL语句
			else
			{
				$dosql->ExecNoneQuery($sqlquery);
				ShowDataMsg('<span class="blue">成功执行1个SQL语句！</span>','?action='.$action);
				exit();
			}

		}

		//无dopost命令则展示
		else
		{
			require_once('database_query.php');
			exit();
		}
	break;

	
	default:
}


//显示提示信息
function ShowDataMsg($msg, $url_forward='', $ms=1000)
{
	require_once('database_message.php');
}


//删除数据文件夹
function DelDataDir($dirname)
{
	global $action;
	$handler = opendir($dirname);

	while(($fname = readdir($handler)) !== false)
	{
		if($fname != '.' && $fname != '..')
		{
			if(@!unlink($dirname.$fname))
			{
				ShowDataMsg("<span class='red'>删除失败，{$dirname}备份目录中可能还存在其他文件夹，请手动删除！</span>",'?action='.$action,1650);
				exit();
			}
		}
	}

	closedir($handler);
	rmdir($dirname);
}
?>
</body>
</html>