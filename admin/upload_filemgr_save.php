<?php	require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('upload_filemgr_sql');

/*
**************************
(C)2010-2015 phpMyWind.com
update: 2014-5-30 17:52:19
person: Feng
**************************
*/


//初始化参数
$mode   = isset($mode)   ? $mode   : '';
$action = isset($action) ? $action : '';
$gourl  = 'upload_filemgr_sql.php';


//判断是否为目录模式
if($mode == 'dir')
{

	//初始化参数
	$gourl    = isset($dirname)  ? 'upload_filemgr_dir.php?dirname='.$dirname : 'upload_filemgr_dir.php';
	$dirname  = isset($dirname)  ? $dirname  : '';
	$filename = isset($filename) ? $filename : '';


	//删除文件
	if($action == 'delfile')
	{
		$file_path = '../'.$dirname.$filename;
		$file_url  = $dirname.$filename;

		//验证删除文件规则
		$match = "/^(\.\.\/uploads)\/(\w+)\/(\d+)\/(\w+)\.(\w{3})$/";
		$flag = preg_match($match, $file_path);

		if($flag && file_exists($file_path))
		{
			if(@unlink($file_path))
			{
				//删除此附件记录
				$dosql->ExecNoneQuery("DELETE FROM `#@__uploads` WHERE path='$file_url'");
				header("location:$gourl");
				exit();
			}
			else
			{
				ShowMsg('未知错误，文件删除失败！', $gourl);
				exit();
			}
		}
		else
		{
			$dosql->ExecNoneQuery("DELETE FROM `#@__uploads` WHERE path='$file_url'");
			ShowMsg('该文件已不存在！', $gourl);
			exit();
		}
	}


	//删除目录
	else if($action == 'deldir')
	{

		//初始化参数
		$dir_path = '../'.$dirname.$filename;

		//验证删除目录规则
		$match = "/^(\.\.\/uploads)\/(\w+)\/(\d+)\/$/";
		$flag = preg_match($match, $dir_path);

		if($flag && file_exists($dir_path))
		{
			if(@rmdir($dir_path))
			{
				header("location:$gourl");
				exit();
			}
			else
			{
				ShowMsg('文件夹删除失败！可能是由于文件夹中还存在文件。', $gourl);
				exit();
			}
		}
		else
		{
			ShowMsg('在目录中未找到该文件，请尝试刷新文件列表！', $gourl);
			exit();
		}
	}


	//批量删除
	else if($action == 'delall')
	{
		$idsnum   = count($checkid);
		$file_arr = '';

		for($i=0; $i<$idsnum; $i++)
		{
			$file_path = '../'.$dirname.$checkid[$i];
			$file_url = $dirname.$checkid[$i];

			//验证删除文件规则
			$match = "/^(\.\.\/uploads)\/(\w+)\/(\d+)\/(\w+)\.(\w{3})$/";
			$flag = preg_match($match, $file_path);

			if($flag && file_exists($file_path))
				@unlink($file_path);
			else
				$file_arr .= $checkid[$i].'|';

			$dosql->ExecNoneQuery("DELETE FROM `#@__uploads` WHERE path='$file_url'");
		}

		if($file_arr == '')
		{
			header("location:$gourl");
			exit();
		}
		else
		{
			ShowMsg($file_arr.'在目录中检测已不在，未进行删除操作！', $gourl);
			exit();
		}
	}

	else
	{
		header("location:$gourl");
		exit();
	}
}


//判断是否为数据模式
else if($mode == 'sql')
{

	//初始化参数
	$gourl = 'upload_filemgr_sql.php';
	$path  = isset($path) ? $path : '';


	//删除文件
	if($action == 'del')
	{

		//初始化参数
		$file_path = '../'.$path;

		//验证删除文件规则
		$match = "/^(\.\.\/uploads)\/(\w+)\/(\d+)\/(\w+)\.(\w{3})$/";
		$flag = preg_match($match, $file_path);

		if($flag && file_exists($file_path))
		{
			if(@unlink($file_path))
			{
				$dosql->ExecNoneQuery("DELETE FROM `#@__uploads` WHERE id=$id");
				header("location:$gourl");
				exit();
			}
			else
			{
				ShowMsg('未知错误，文件删除失败！', $gourl);
				exit();
			}
		}
		else
		{
			$dosql->ExecNoneQuery("DELETE FROM `#@__uploads` WHERE id=$id");
			ShowMsg('该文件已不存在！', $gourl);
			exit();
		}
	}

	//批量删除
	else if($action == 'delall')
	{
		$idsnum = count($checkid);
		$file_arr = '';

		for($i=0; $i<$idsnum; $i++)
		{
			$file_path = '../'.$checkid[$i];

			//验证删除文件规则
			$match = "/^(\.\.\/uploads)\/(\w+)\/(\d+)\/(\w+)\.(\w{3})$/";
			$flag = preg_match($match, $file_path);

			if($flag && file_exists($file_path))
				@unlink($file_path);
			else
				$file_arr .= $checkid[$i].'|';

			$dosql->ExecNoneQuery("DELETE FROM `#@__uploads` WHERE path='$checkid[$i]'");
		}

		if($file_arr == '')
		{
			header("location:$gourl");
			exit();
		}
		else
		{
			ShowMsg($file_arr.'在目录中检测已不在，未进行删除操作！', $gourl);
			exit();
		}
	}

	else
	{
		header("location:$gourl");
		exit();
	}
}


//无条件返回
else
{
    header("location:$gourl");
	exit();
}
?>
