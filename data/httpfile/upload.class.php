<?php	if(!defined('IN_PHPMYWIND')) exit('Request Error!');

/*
**************************
(C)2010-2015 phpMyWind.com
update: 2014-5-31 22:01:23
person: Feng
**************************
*/


//不限制响应时间
@set_time_limit(0);


//引入水印文件
require_once(PHPMYWIND_DATA.'/watermark/watermark.class.php');


/*
 * 上传函数
 *
 * @access         public
 * @upfile         string  上传表单name
 * @iswatermark    string  是否添加水印
 */
function UploadFile($upfile, $iswatermark='')
{
	global $dosql;
    global $cfg_max_file_size;
	global $cfg_image_dir, $cfg_soft_dir, $cfg_media_dir;
    global $cfg_upload_img_type, $cfg_upload_soft_type, $cfg_upload_media_type;
	global $cfg_markswitch, $cfg_markpicurl, $cfg_markminwidth, $cfg_markminheight, $cfg_markwhere, $cfg_marktext, $cfg_marksize, $cfg_markcolor, $cfg_marktype;


	//检测是否存在
	$tempfile_tn = isset($_FILES[$upfile]['tmp_name']) ? $_FILES[$upfile]['tmp_name'] : '';
    if($tempfile_tn=='' or !is_uploaded_file($tempfile_tn))
	{
		//return '请选择要上传的文件！';
		return '请选择上传文件或您上传的文件超过php.ini设定最大文件上传限制['.ini_get('upload_max_filesize').']！';
	}


	//获取上传文件信息
	$tempfile      = $_FILES[$upfile];
	$tempfile_name = $tempfile['name'];
	$tempfile_size = $tempfile['size'];
	$tempfile_ext  = strtolower(substr(strrchr($tempfile_name, '.'), 1));


	//强制限定的某些文件类型禁止上传
	if(in_array($tempfile_ext, explode('|', 'php|pl|cgi|asp|aspx|jsp|php3|shtm|shtml')))
	{
		return '您上传的文件类型为：['.$tempfile_ext.']，该类文件不允许通过后台上传！';
	}


	//检查文件类型,上传文件目录
	if(in_array($tempfile_ext, explode('|', strtolower($cfg_upload_img_type))))
	{
		$upload_url = 'image';
		$upload_dir = $cfg_image_dir;
	}
	else if(in_array($tempfile_ext, explode('|', strtolower($cfg_upload_soft_type))))
	{
		$upload_url = 'soft';
		$upload_dir = $cfg_soft_dir;
	}
	else if(in_array($tempfile_ext, explode('|', strtolower($cfg_upload_media_type))))
	{
		$upload_url = 'media';
		$upload_dir = $cfg_media_dir;
	}
	else
	{
		return '您上传的文件类型为：['.$tempfile_ext.']，该文件类型不允许上传！';
	}

	$save_type = $upload_url;


	//检查文件大小
	if($tempfile_size > $cfg_max_file_size)
	{
		return '您上传的文件超过系统设定最大文件上传限制['.GetRealSize($cfg_max_file_size).']！';
	}


	//创建文件夹
	if(!file_exists($upload_dir))
	{
		mkdir($upload_dir);
	}


	//检查目录可写权限
	if(@!is_writable($upload_dir))
	{
		return '上传目录没有可写权限！';
	}


	$ymd = date('Ymd');
	$upload_url .= '/'.$ymd;
	$upload_dir .= '/'.$ymd;

	if(!file_exists($upload_dir))
	{
		mkdir($upload_dir);

		$fp = fopen($upload_dir.'/index.htm', 'w');
		fclose($fp);
	}


	//上传文件名称
	$filename = time()+rand(1,9999).'.'.$tempfile_ext;


	//上传文件路径
	$save_url = 'uploads/'.$upload_url.'/'.$filename;
	$save_dir = $upload_dir.'/'.$filename;


	if(file_exists($save_dir))
	{
		return '同名文件已经存在了！';
	}


	//移动临时文件到指定目录
	if(@move_uploaded_file($tempfile_tn, $save_dir))
	{
		if($cfg_markswitch=='Y' &&
		   ($iswatermark=='true' or $iswatermark=='checked') &&
		   in_array($tempfile_ext, array('jpg','png','gif','bmp')))
		{
			WaterMark($save_dir, PHPMYWIND_ROOT.'/'.$cfg_markpicurl, $cfg_markminwidth, $cfg_markminheight, $cfg_markwhere,
			          $cfg_marktext, '黑体', $cfg_marksize, $cfg_markcolor, $cfg_marktype);
		}

		//添加数据库记录
		$dosql->ExecNoneQuery("INSERT INTO `#@__uploads` (name, path, size, type, posttime) VALUES ('$filename', '$save_url', '$tempfile_size', '$save_type', '".time()."')");

		//上传成功，返回数组
		return array($filename, $tempfile_size, $save_url, $save_dir);
	}
	else
	{
		return '发生未知错误，上传失败！';
	}
}
?>
