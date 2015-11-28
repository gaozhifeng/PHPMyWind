<?php	require_once(dirname(__FILE__).'/../../inc/config.inc.php');

/*
**************************
(C)2010-2015 phpMyWind.com
update: 2012-9-15 10:50:00
person: Feng
**************************
*/


//初始化参数
$action      = isset($action)      ? $action      : '';
$iswatermark = isset($iswatermark) ? $iswatermark : '';
$timestamp   = isset($timestamp)   ? $timestamp   : '';
$verifyToken = md5('unique_salt'.$timestamp);


//判断上传状态
if(!empty($_FILES) && $token==$verifyToken && isset($sessionid))
{

	//引入上传类
	require_once(PHPMYWIND_DATA.'/httpfile/upload.class.php');
	$upload_info = UploadFile('Filedata', $iswatermark);


	/* 返回上传状态，是数组则表示上传成功
	   非数组则是直接返回发生的问题 */
	if(!is_array($upload_info))
		echo '0,'.$upload_info;
	else
		echo implode(',', $upload_info);

	exit();
}


//删除元素
if($action == 'del')
{
	//验证删除文件规则
	$match = "/^(uploads)\/(\w+)\/(\d+)\/(\w+)\.(\w{3})$/";
	$flag = preg_match($match, $filename);

	if($flag)
	{
		$dosql->ExecNoneQuery("DELETE FROM `#@__uploads` WHERE path='$filename'");
		unlink(PHPMYWIND_ROOT .'/'. $filename);
	}
	exit();
}
?>
