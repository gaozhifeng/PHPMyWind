<?php

/*
**************************
(C)2010-2015 phpMyWind.com
update: 2012-10-23 14:30:33
person: Adu
**************************
*/


//初始化参数
$uid  = isset($_GET['uid'])  ? $_GET['uid']  : '';
$size = isset($_GET['size']) ? $_GET['size'] : '';
$path = get_avatar_filepath($uid,$size);


//返回头像路径
if(is_file($path))
	header('location:'.get_avatar_filepath($uid,$size));
else
	header('location:images/default_avatar.jpg');



/*
 * 获取指定uid的头像文件规范路径
 * 来源：Ucenter base类的get_avatar方法
 *
 * @param  int  $uid
 * @param  string  $size  头像尺寸，可选为'big', 'middle', 'small'
 * @param  string  $type  类型，可选为real或者virtual
 * @return string  头像路径
 */

function get_avatar_filepath($uid, $size='big', $type='')
{
	$size = in_array($size, array('big', 'middle', 'small')) ? $size : 'big';
	$uid = abs(intval($uid));
	$uid = sprintf("%09d", $uid);
	$dir1 = substr($uid, 0, 3);
	$dir2 = substr($uid, 3, 2);
	$dir3 = substr($uid, 5, 2);
	$typeadd = $type == 'real' ? '_real' : '';
	return  $dir1.'/'.$dir2.'/'.$dir3.'/'.substr($uid, -2).$typeadd.'_avatar_'.$size.'.jpg';
}
?>
