<?php	require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('member');

/*
**************************
(C)2010-2015 phpMyWind.com
update: 2014-5-30 17:16:14
person: Feng
**************************
*/


//初始化参数
$tbname = '#@__member';
$gourl  = 'member.php';


//引入操作类
require_once(ADMIN_INC.'/action.class.php');


//添加会员
if($action == 'add')
{
	if(!isset($enteruser)) $enteruser = '';

	if(preg_match("/[^0-9a-zA-Z_@!\.-]/",$username) || preg_match("/[^0-9a-zA-Z_@!\.-]/",$password))
	{
		ShowMsg('用户名或密码非法！请使用[0-9a-zA-Z_@!.-]内的字符！','-1');
		exit();
	}
	if($password != $repassword)
	{
		ShowMsg('两次输入的密码不一样！','-1');
		exit();
	}

	$r = $dosql->GetOne("SELECT username FROM `$tbname` WHERE username='$username'");
	if(!empty($r['username']))
	{
		ShowMsg('用户名已存在！','-1');
		exit();
	}

	$password = md5(md5($password));
	$regtime  = GetMkTime($regtime);
	$regip    = GetIP();

	$sql = "INSERT INTO `$tbname` (username, password, question, answer, cnname, enname, sex, birthtype, birth_year, birth_month, birth_day, astro, bloodtype, trade, live_prov, live_city, live_country, home_prov, home_city, home_country, cardtype, cardnum, intro, email, qqnum, mobile, telephone, address_prov, address_city, address_country, address, zipcode, enteruser, expval, integral, regtime, regip, logintime, loginip) VALUES ('$username', '$password', '$question', '$answer', '$cnname', '$enname', '$sex', '$birthtype', '$birth_year', '$birth_month', '$birth_day', '$astro', '$bloodtype', '$trade', '$live_prov', '$live_city', '$live_country', '$home_prov', '$home_city', '$home_country', '$cardtype', '$cardnum', '$intro', '$email', '$qqnum', '$mobile', '$telephone', '$address_prov', '$address_city', '$address_country', '$address', '$zipcode', '$enteruser', '$expval', '$integral', '$regtime', '$regip', '$regtime', '$regip')";
	if($dosql->ExecNoneQuery($sql))
	{
		header("location:$gourl");
		exit();
	}
}


//修改会员信息
else if($action == 'update')
{
	if(!isset($enteruser)) $enteruser = '';

	if($password != $repassword)
	{
		ShowMsg('两次输入的密码不一样！','-1');
		exit();
	}

	//删除头像
	if(!empty($delavatar))
	{
		$avatarsize = array(1 => 'big', 2 => 'middle', 3 => 'small');
		foreach($avatarsize as $size)
		{
			file_exists(PHPMYWIND_DATA.'/avatar/'.get_avatar_filepath($id,$size)) &&
			unlink(PHPMYWIND_DATA.'/avatar/'.get_avatar_filepath($id,$size));
		}
	}

	$sql = "UPDATE `$tbname` SET ";
	if($password != '')
	{
		$password = md5(md5($password));
		$sql .= "password='$password', ";
	}
	$sql .= "question='$question', answer='$answer', cnname='$cnname', enname='$enname', sex='$sex', birthtype='$birthtype', birth_year='$birth_year', birth_month='$birth_month', birth_day='$birth_day', astro='$astro', bloodtype='$bloodtype', trade='$trade', live_prov='$live_prov', live_city='$live_city', live_country='$live_country', home_prov='$home_prov', home_city='$home_city', home_country='$home_country', cardtype='$cardtype', cardnum='$cardnum', intro='$intro', email='$email', qqnum='$qqnum', mobile='$mobile', telephone='$telephone', address_prov='$address_prov', address_city='$address_city', address_country='$address_country', address='$address', zipcode='$zipcode', enteruser='$enteruser', expval='$expval', integral='$integral' WHERE id=$id";


	if($dosql->ExecNoneQuery($sql))
	{
		header("location:$gourl");
		exit();
	}
}


//移除绑定QQ
else if($action == 'removeoqq')
{
	$dosql->ExecNoneQuery("UPDATE `#@__member` SET `qqid`='' WHERE `id`='$id'");
	ShowMsg('解除QQ绑定成功！','member_update.php?id='.$id);
	exit();
}


//移除绑定微博
else if($action == 'removeoweibo')
{
	$dosql->ExecNoneQuery("UPDATE `#@__member` SET `weiboid`='' WHERE `id`='$id'");
	ShowMsg('解除微博绑定成功！','member_update.php?id='.$id);
	exit();
}


//无条件返回
else
{
    header("location:$gourl");
	exit();
}
?>
