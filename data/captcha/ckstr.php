<?php

/*
**************************
(C)2010-2015 phpMyWind.com
update: 2014-5-31 22:02:56
person: Feng
**************************
*/


//Session保存路径
$sess_savepath = dirname(__FILE__).'/../sessions/';
if(is_writable($sess_savepath) &&
   is_readable($sess_savepath))
{
	session_save_path($sess_savepath);
}


//开启SESSION
if(!isset($_SESSION)) session_start();


//获取随机字符
$rndstring = '';
for($i=0; $i<4; $i++) $rndstring .= chr(mt_rand(65,90));


//如果支持GD，则绘图
if(function_exists('imagecreate'))
{

	$_SESSION['ckstr'] = strtolower($rndstring);
	$rndstring  = $_SESSION['ckstr'];
	$rndcodelen = strlen($rndstring);


	//创建图片，并设置背景色
	$im = imagecreate(50,20);
	ImageColorAllocate($im, 255,255,255);


	//背景线
	$lineColor1 = ImageColorAllocate($im,240,220,180);
	$lineColor2 = ImageColorAllocate($im,250,250,170);
	for($j=3;$j<=16;$j=$j+3)
	{
		imageline($im, 2, $j, 48, $j, $lineColor1);
	}
	for($j=2;$j<52;$j=$j+(mt_rand(3,6)))
	{
		imageline($im, $j, 2, $j-6, 18, $lineColor2);
	}


	//画边框
	$bordercolor = ImageColorAllocate($im, 0x99,0x99,0x99);
	imagerectangle($im, 0, 0, 49, 19, $bordercolor);


	//输出文字
	$fontColor = ImageColorAllocate($im, 48,61,50);
	for($i=0;$i<$rndcodelen;$i++)
	{
		$bc = mt_rand(0,1);
		$rndstring[$i] = strtoupper($rndstring[$i]);
		imagestring($im, 5, $i*10+6, mt_rand(2,4), $rndstring[$i], $fontColor);
	}

	header("Pragma:no-cache\r\n");
	header("Cache-Control:no-cache\r\n");
	header("Expires:0\r\n");


	//输出特定类型的图片格式，优先级为 gif -> jpg ->png
	if(function_exists("imagejpeg"))
	{
		header("content-type:image/jpeg\r\n");
		imagejpeg($im);
	}
	else
	{
		header("content-type:image/png\r\n");
		imagepng($im);
	}

	ImageDestroy($im);
	exit();
}


//不支持GD
else
{
	//输出字母 ABCD
	$vdcode_path = 'vdcode.jpg';
	$_SESSION['ckstr'] = 'abcd';

	header("content-type:image/jpeg\r\n");
	header("Pragma:no-cache\r\n");
	header("Cache-Control:no-cache\r\n");
	header("Expires:0\r\n");

	$fp = fopen($vdcode_path,'r');
	echo fread($fp, filesize($vdcode_path));
	fclose($fp);
	exit();
}

?>
