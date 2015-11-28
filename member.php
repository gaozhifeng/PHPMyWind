<?php	require_once(dirname(__FILE__).'/include/config.inc.php');

/*
**************************
(C)2010-2015 phpMyWind.com
update: 2014-6-17 22:44:56
person: Feng
**************************
*/


//定义入口常量
define('IN_MEMBER', TRUE);


//初始化参数
$c  = isset($c)  ? $c : 'login';
$a  = isset($a)  ? $a : '';
$d  = isset($d)  ? $d : '';
$id = isset($id) ? intval($id) : 0;


//检测是否启用会员
//允许在不开启会员功能的情况下进行游客评论
if($cfg_member == 'N' && $a != 'savecomment')
{
	ShowMsg('抱歉，本站没有启用会员功能！','-1');
	exit();
}


//一键登录文件
if($cfg_oauth == 'Y')
{
	require_once(PHPMYWIND_DATA.'/api/oauth/system/core.php');
}


//初始登录信息
if(!empty($_COOKIE['username']) &&
   !empty($_COOKIE['lastlogintime']) &&
   !empty($_COOKIE['lastloginip']))
{
	$c_uname     = AuthCode($_COOKIE['username']);
	$c_logintime = AuthCode($_COOKIE['lastlogintime']);
	$c_loginip   = AuthCode($_COOKIE['lastloginip']);
}
else
{
	$c_uname     = '';
	$c_logintime = '';
	$c_loginip   = '';
}


//验证是否登录和用户合法
if($a=='saveedit'    or $a=='getarea'    or $a=='savefavorite' or
   $a=='delfavorite' or $a=='delcomment' or $a=='delmsg' or
   $a=='delorder'    or $a=='avatar'     or $a=='getgoods' or
   $a=='applyreturn' or $a=='perfect'    or $a=='binding' or
   $a=='removeoqq'   or $a=='removeoweibo')
{
	if(!empty($c_uname))
	{
		//guest为一键登录未绑定账号时的临时用户
		if($c_uname != 'guest')
		{
			$r = $dosql->GetOne("SELECT `id`,`expval` FROM `#@__member` WHERE `username`='$c_uname'");
			if(!is_array($r))
			{
				setcookie('username',      '', time()-3600);
				setcookie('lastlogintime', '', time()-3600);
				setcookie('lastloginip',   '', time()-3600);
				ShowMsg('该用户已不存在！','?c=login');
				exit();
			}
			else if($r['expval'] <= 0)
			{
				ShowMsg('抱歉，您的账号被禁止登录！','?c=login');
				exit();
			}
		}
	}
	else
	{
		header('location:?c=login');
		exit();
	}
}


//登录账户
if($a == 'login')
{

	//一键登录
	if($cfg_oauth == 'Y' && isset($method) && $method == 'callback')
	{

		//初始化参数
		$logintime = time();
		$loginip   = GetIP();


		//检测账号登录状态
		if(check_app_login('qq') or check_app_login('weibo'))
		{
			//检查一键登录账号类型
			if(check_app_login('qq'))
				$sql = "SELECT * FROM `#@__member` WHERE `qqid`='".$_SESSION['app']['qq']['uid']."'";
			else if(check_app_login('weibo'))
				$sql = "SELECT * FROM `#@__member` WHERE `weiboid`='".$_SESSION['app']['weibo']['idstr']."'";

			$row = $dosql->GetOne($sql);

			//合作账号没有绑定过
			if(empty($row['id']))
			{
				//设置COOKIE时间
				$cookie_time = time() + ini_get('session.gc_maxlifetime');

				//发放临时账号登录
				setcookie('username',      AuthCode('guest'    ,'ENCODE'), $cookie_time);
				setcookie('lastlogintime', AuthCode($logintime ,'ENCODE'), $cookie_time);
				setcookie('lastloginip',   AuthCode($loginip   ,'ENCODE'), $cookie_time);

				header('location:?c=default');
				exit();
			}

			//已经绑定账号
			else
			{
				//验证成功，查看是否被禁止登录
				if($row['expval'] <= 0)
				{
					ShowMsg('抱歉，您的账号被禁止登录！','?c=login');
					exit();
				}

				//验证成功，开始登录
				else
				{

					//删除禁止登录
					if(is_array($r))
					{
						$dosql->ExecNoneQuery("DELETE FROM `#@__failedlogin` WHERE `username`='".$row['username']."'");
					}

					$cookie_time = time()+3600;

					setcookie('username',      AuthCode($row['username'] ,'ENCODE'), $cookie_time);
					setcookie('lastlogintime', AuthCode($row['logintime'],'ENCODE'), $cookie_time);
					setcookie('lastloginip',   AuthCode($row['loginip']  ,'ENCODE'), $cookie_time);


					//每天登录增加10点经验
					if(MyDate('d',time()) != MyDate('d',$row['logintime']))
					{
						$dosql->ExecNoneQuery("UPDATE `#@__member` SET `expval`='".($row['expval'] + 10)."' WHERE `username`='".$row['username']."'");
					}

					$dosql->ExecNoneQuery("UPDATE `#@__member` SET `loginip`='$loginip',`logintime`='$logintime' WHERE `id`=".$row['id']);


					header('location:?c=default');
					exit();
				}
			}
		}

		else
		{
			header('location:?c=login');
			exit();
		}
	}


	//注册用户登录
	else
	{

		//初始化参数
		$username = empty($username) ? '' : $username;
		$password = empty($password) ? '' : md5(md5($password));
		$validate = empty($validate) ? '' : strtolower($validate);


		//验证输入数据
		if($username == '' or
		   $password == '' or
		   $validate == '')
		{
			header('location:?c=login');
			exit();
		}


		//删除所有已过时记录
		$dosql->ExecNoneQuery("DELETE FROM `#@__failedlogin` WHERE (UNIX_TIMESTAMP(NOW())-time)/60>15");


		//判断是否被暂时禁止登录
		$r = $dosql->GetOne("SELECT * FROM `#@__failedlogin` WHERE username='$username'");
		if(is_array($r))
		{
			$min = round((time()-$r['time']))/60;
			if($r['num']==0 and $min<=15)
			{
				ShowMsg('您的密码已连续错误6次，请15分钟后再进行登录！','?c=login');
				exit();
			}
		}


		//检测数据正确性
		if($validate != strtolower(GetCkVdValue()))
		{
			ResetVdValue();
			ShowMsg('验证码不正确！','?c=login');
			exit();
		}
		else
		{

			$row = $dosql->GetOne("SELECT `id`,`password`,`logintime`,`loginip`,`expval` FROM `#@__member` WHERE `username`='$username'");


			//密码错误
			if(!is_array($row) or $password!=$row['password'])
			{
				$logintime = time();
				$loginip   = GetIP();

				$r = $dosql->GetOne("SELECT * FROM `#@__failedlogin` WHERE `username`='$username'");
				if(is_array($r))
				{
					$num = $r['num']-1;

					if($num == 0)
					{
						$dosql->ExecNoneQuery("UPDATE `#@__failedlogin` SET `time`=$logintime, `num`=$num WHERE `username`='$username'");
						ShowMsg('您的密码已连续错误6次，请15分钟后再进行登录！','?c=login');
						exit();
					}
					else if($r['num']<=5 and $r['num']>0)
					{
						$dosql->ExecNoneQuery("UPDATE `#@__failedlogin` SET `time`=$logintime, `num`=$num WHERE `username`='$username'");
						ShowMsg('用户名或密码不正确！您还有'.$num.'次尝试的机会！','?c=login');
						exit();
					}
				}
				else
				{
					$dosql->ExecNoneQuery("INSERT INTO `#@__failedlogin` (username, ip, time, num, isadmin) VALUES ('$username', '$loginip', '$logintime', 5, 0)");
					ShowMsg('用户名或密码不正确！您还有5次尝试的机会！','?c=login');
					exit();
				}
			}


			//密码正确，查看是否被禁止登录
			else if($row['expval'] <= 0)
			{
				ShowMsg('抱歉，您的账号被禁止登录！','?c=login');
				exit();
			}


			//用户名密码正确
			else
			{

				$logintime = time();
				$loginip = GetIP();


				//删除禁止登录
				if(is_array($r))
				{
					$dosql->ExecNoneQuery("DELETE FROM `#@__failedlogin` WHERE `username`='$username'");
				}


				//是否自动登录
				if(isset($autologin))
					$cookie_time = time()+14*24*60*60;
				else
					$cookie_time = time()+3600;

				setcookie('username',      AuthCode($username        ,'ENCODE'), $cookie_time);
				setcookie('lastlogintime', AuthCode($row['logintime'],'ENCODE'), $cookie_time);
				setcookie('lastloginip',   AuthCode($row['loginip']  ,'ENCODE'), $cookie_time);


				//每天登录增加10点经验
				if(MyDate('d',time()) != MyDate('d',$row['logintime']))
				{
					$dosql->ExecNoneQuery("UPDATE `#@__member` SET `expval`='".($row['expval'] + 10)."' WHERE `username`='$username'");
				}

				$dosql->ExecNoneQuery("UPDATE `#@__member` SET `loginip`='$loginip',`logintime`='$logintime' WHERE `id`=".$row['id']);


				header('location:?c=default');
				exit();
			}
		}
	}
}


//注册账户
else if($a == 'reg')
{

	//初始化参数
	$username   = empty($username)   ? '' : $username;
	$password   = empty($password)   ? '' : md5(md5($password));
	$repassword = empty($repassword) ? '' : md5(md5($repassword));
	$email      = empty($email)      ? '' : $email;
	$validate   = empty($validate)   ? '' : strtolower($validate);


	//验证输入数据
	if($username   == '' or
	   $password   == '' or
	   $repassword == '' or
	   $email      == '' or
	   $validate   == '')
	{
		header('location:?c=reg');
		exit();
	}


	//验证数据准确性
	if($validate != strtolower(GetCkVdValue()))
	{
		ResetVdValue();
		ShowMsg('验证码不正确！','?c=reg');
		exit();
	}

	if($password != $repassword)
	{
		header('location:?c=reg');
		exit();
	}

    $uname_len = strlen($username);
	$upwd_len  = strlen($_POST['password']);
	if($uname_len<6 or $uname_len>16 or $upwd_len<6 or $upwd_len>16)
	{
		header('location:?c=reg');
		exit();
	}

	if(preg_match("/[^0-9a-zA-Z_@!\.-]/",$username) or
	   preg_match("/[^0-9a-zA-Z_-]/",$password) or
	   !preg_match("/^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)+$/", $email))
	{
		header('location:?c=reg');
		exit();
	}

	$r = $dosql->GetOne("SELECT `id` FROM `#@__member` WHERE `username`='$username'");
	if(isset($r['id']))
	{
		ShowMsg('用户名已存在！','?c=reg');
		exit();
	}

	$r = $dosql->GetOne("SELECT `id` FROM `#@__member` WHERE `email`='$email'");
	if(isset($r['id']))
	{
		ShowMsg('您填写的邮箱已被注册！','?c=reg');
		exit();
	}


	//添加用户数据
	$regtime  = time();
	$regip    = GetIP();

	$sql = "INSERT INTO `#@__member` (username, password, email, expval, regtime, regip, logintime, loginip) VALUES ('$username', '$password', '$email', '10', '$regtime', '$regip', '$regtime', '$regip')";
	if($dosql->ExecNoneQuery($sql))
	{
		header('location:?c=login&d='.md5('reg'));
		exit();
	}
}


//退出账户
else if($a == 'logout')
{
	setcookie('username',      '', time()-3600);
	setcookie('lastlogintime', '', time()-3600);
	setcookie('lastloginip',   '', time()-3600);

	header('location:?c=login');
	exit();
}


//找回密码
else if($a == 'findpwd2')
{
	if(!isset($_POST['username']))
	{
		header('location:?c=findpwd');
		exit();
	}


	//检测验证码
	$validate = empty($validate) ? '' : strtolower($validate);
	if($validate == '' || $validate != strtolower(GetCkVdValue()))
	{
		ResetVdValue();
		ShowMsg('验证码不正确！','?c=findpwd');
		exit();
	}
	else
	{
		$r = $dosql->GetOne("SELECT `id` FROM `#@__member` WHERE `username`='$username'");
		if(!isset($r['id']))
		{
			ShowMsg('请输入正确的账号信息！','?c=findpwd');
			exit();
		}
	}
}


//找回密码
else if($a == 'quesfind')
{
	if(!isset($_POST['uname']))
	{
		header('location:?c=findpwd');
		exit();
	}


	//验证输入数据
	if($question == '-1' or $answer == '')
	{
		header('location:?c=findpwd');
		exit();
	}


	$r = $dosql->GetOne("SELECT `question`,`answer` FROM `#@__member` WHERE `username`='$uname'");
	if($r['question']==0 or !isset($r['answer']))
	{
		ShowMsg('此账号未填写验证问题，请选择其他方式找回！','?c=findpwd');
		exit();
	}
	else if($question != $r['question'] or $answer != $r['answer'])
	{
		ShowMsg('您填写的验证问题或答案不符！','?c=findpwd');
		exit();
	}
	else
	{
		//验证通过，采用SESSION存储用户名
		@session_start();
		$_SESSION['fid_'.$uname] = $uname;
	}
}


//设置新密码
else if($a == 'setnewpwd')
{
	@session_start();

	if(isset($_SESSION['fid_'.$_POST['uname']]))
	{

		if($_SESSION['fid_'.$_POST['uname']] != $_POST['uname'])
		{
			ShowMsg('非法操作，找回用户名与上一步输入不符合！','?c=findpwd');
			unset($_SESSION['fid_'.$_POST['uname']]);
			exit();
		}
	}
	else
	{
		header('location:?c=findpwd');
		exit();
	}


	//初始化参数
	$uname      = empty($uname)      ? '' : $uname;
	$password   = empty($password)   ? '' : md5(md5($password));
	$repassword = empty($repassword) ? '' : md5(md5($repassword));


	//验证输入数据
	if($uname == '' or
	   $password == '' or
	   $repassword == '' or
	   $password != $repassword or
	   preg_match("/[^0-9a-zA-Z_-]/",$password))
	{
		header('location:?c=findpwd');
		exit();
	}


	if($dosql->ExecNoneQuery("UPDATE `#@__member` SET password='$password' WHERE username='$uname'"))
	{
		header("location:?c=login&d=".md5('newpwd'));
		unset($_SESSION['fid_'.$_POST['uname']]);
		exit();
	}
}


//找回密码
else if($a == 'mailfind')
{
	if(!isset($_POST['uname']))
	{
		header('location:?c=findpwd');
		exit();
	}


	//验证输入数据
	if($email == '')
	{
		header('location:?c=findpwd');
		exit();
	}


	$r = $dosql->GetOne("SELECT `email` FROM `#@__member` WHERE `username`='$uname'");
	if($r['email'] == $email)
	{

	}
	else
	{
		ShowMsg('您填写的邮箱不符！','?c=findpwd');
		exit();
	}
}


//更新资料
else if($a == 'saveedit')
{

	//检测数据完整性
	if($password!=$repassword or $email=='')
	{
		header('location:?c=edit');
		exit();
	}


	//HTML转义变量
	$answer    = htmlspecialchars($answer);
	$cnname    = htmlspecialchars($cnname);
	$enname    = htmlspecialchars($enname);
	$cardnum   = htmlspecialchars($cardnum);
	$intro     = htmlspecialchars($intro);
	$email     = htmlspecialchars($email);
	$qqnum     = htmlspecialchars($qqnum);
	$mobile    = htmlspecialchars($mobile);
	$telephone = htmlspecialchars($telephone);
	$address   = htmlspecialchars($address);
	$zipcode   = htmlspecialchars($zipcode);


	//检测旧密码是否正确
	if($password != '')
	{
		$oldpassword = md5(md5($oldpassword));
		$r = $dosql->GetOne("SELECT `password` FROM `#@__member` WHERE `username`='$c_uname'");
		if($r['password'] != $oldpassword)
		{
			ShowMsg('抱歉，旧密码错误！','-1');
			exit();
		}
	}

	$sql = "UPDATE `#@__member` SET ";
	if($password != '')
	{
		$password = md5(md5($password));
		$sql .= "password='$password', ";
	}
	@$sql .= "question='$question', answer='$answer', cnname='$cnname', enname='$enname', sex='$sex', birthtype='$birthtype', birth_year='$birth_year', birth_month='$birth_month', birth_day='$birth_day', astro='$astro', bloodtype='$bloodtype', trade='$trade', live_prov='$live_prov', live_city='$live_city', live_country='$live_country', home_prov='$home_prov', home_city='$home_city', home_country='$home_country', cardtype='$cardtype', cardnum='$cardnum', intro='$intro', email='$email', qqnum='$qqnum', mobile='$mobile', telephone='$telephone', address_prov='$address_prov', address_city='$address_city', address_country='$address_country', address='$address', zipcode='$zipcode' WHERE id='$id' AND `username`='$c_uname'";
	if($dosql->ExecNoneQuery($sql))
	{
		ShowMsg('资料更新成功！','?c=edit');
		exit();
	}
}


//获取级联
else if($a == 'getarea')
{

	//初始化参数
	$datagroup = isset($datagroup) ? $datagroup     : '';
	$level     = isset($level)     ? intval($level) : '';
	$v         = isset($areaval)   ? $areaval       : '0';

	if($datagroup == '' or $level == '' or $v == '')
	{
		header('location:?c=default');
		exit();
	}

	$str = '<option value="-1">--</option>';
	$sql = "SELECT * FROM `#@__cascadedata` WHERE `level`=$level And ";

	if($v == 0)
		$sql .= "datagroup='$datagroup'";
	else if($v % 500 == 0)
		$sql .= "`datagroup`='$datagroup' AND `datavalue`>'$v' AND `datavalue`<'".($v + 500)."'";
	else
		$sql .= "`datavalue` LIKE '$v.%%%' AND `datagroup`='$datagroup'";

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


//保存评论
else if($a == 'savecomment')
{
	//是否开去文章评论功能
	if($cfg_comment == 'N') exit();

	//初始化参数
	$aid   = isset($aid)   ? intval($aid)   : '';
	$molds = isset($molds) ? intval($molds) : '';
	$body  = isset($body)  ? htmlspecialchars($body) : '';
	$link  = isset($_SERVER['HTTP_REFERER']) ? htmlspecialchars($_SERVER['HTTP_REFERER'],ENT_QUOTES) : '';

	if($aid == '' or $molds == '' or $body == '')
	{
		header('location:?c=default');
		exit();
	}

	$reply = '';

	if(empty($c_uname))
	{
		$uid   = '-1';
		$uname = '游客';
	}
	else
	{
		$r = $dosql->GetOne("SELECT `id`,`expval`,`integral` FROM `#@__member` WHERE `username`='$c_uname'");
		$uid   = $r['id'];
		$uname = $c_uname;
	}


	$time  = time();
	$ip    = GetIP();

	$dosql->ExecNoneQuery("INSERT INTO `#@__usercomment` (aid,molds,uid,uname,body,reply,link,time,ip,isshow) VALUES ('$aid','$molds','$uid','$uname','$body','$reply','$link','$time','$ip','1')");


	$r = $dosql->GetOne("SELECT `id` FROM `#@__usercomment` WHERE `aid`='$aid' AND `molds`='$molds' AND `uid`='$uid'");
	if(empty($r['id']) && !empty($c_uname) && $uid != '-1')
	{
		//评论一条增加1经验值2积分
		$dosql->ExecNoneQuery("UPDATE `#@__member` SET expval='".($r['expval'] + 1)."', integral='".($r['integral'] + 2)."' WHERE `username`='$c_uname'");
	}

	echo json_encode(array('1',$uname,$body,GetDateTime($time)));
	exit();
}


//删除评论
else if($a == 'delcomment')
{
	//是否开去文章评论功能
	if($cfg_comment == 'N') exit();

	if(is_array($checkid))
	{
		foreach($checkid as $v)
		{
			//参数过滤
			$v = intval($v);
			$dosql->ExecNoneQuery("DELETE FROM `#@__usercomment` WHERE `id`=$v AND `uname`='$c_uname'");
		}
	}

	header('location:?c=comment');
	exit();
}


//保存收藏
else if($a == 'savefavorite')
{

	$aid   = isset($aid)   ? intval($aid)   : '';
	$molds = isset($molds) ? intval($molds) : '';
	$link  = isset($_SERVER['HTTP_REFERER']) ? htmlspecialchars($_SERVER['HTTP_REFERER'],ENT_QUOTES) : '';

	if($aid == '' or $molds == '' or $link == '')
	{
		header('location:?c=default');
		exit();
	}

	$r = $dosql->GetOne("SELECT `id`,`expval`,`integral` FROM `#@__member` WHERE `username`='$c_uname'");
	$uid   = $r['id'];
	$uname = $c_uname;
	$time  = time();
	$ip    = GetIP();

	$r2 = $dosql->GetOne("SELECT `aid`,`molds` FROM `#@__userfavorite` WHERE `aid`=$aid and `molds`=$molds");
	if(!is_array($r2))
	{
		$dosql->ExecNoneQuery("INSERT INTO `#@__userfavorite` (aid,molds,uid,uname,link,time,ip,isshow) VALUES ('$aid','$molds','$uid','$uname','$link','$time','$ip','1')");

		//收藏一条增加1经验值2积分
		$dosql->ExecNoneQuery("UPDATE `#@__member` SET expval='".($r['expval'] + 1)."', integral='".($r['integral'] + 2)."' WHERE `username`='$c_uname'");
		echo '1';
		exit();
	}
	else
	{
		echo '2';
		exit();
	}
}


//删除收藏
else if($a == 'delfavorite')
{
	if(is_array($checkid))
	{
		foreach($checkid as $v)
		{
			//参数过滤
			$v = intval($v);
			$dosql->ExecNoneQuery("DELETE FROM `#@__userfavorite` WHERE `id`=$v AND `uname`='$c_uname'");
		}
	}

	header('location:?c=favorite');
	exit();
}


//删除留言
else if($a == 'delmsg')
{
	if(is_array($checkid))
	{
		foreach($checkid as $v)
		{
			//参数过滤
			$v = intval($v);
			$dosql->ExecNoneQuery("DELETE FROM `#@__message` WHERE `id`=$v AND `nickname`='$c_uname'");
		}
	}

	header('location:?c=msg');
	exit();
}


//删除订单
else if($a == 'delorder')
{
	if(is_array($checkid))
	{
		foreach($checkid as $v)
		{
			//参数过滤
			$v = intval($v);

			$r = $dosql->GetOne("SELECT `checkinfo` FROM `#@__goodsorder` WHERE `id`=$v");
			$checkinfo = explode(',', $r['checkinfo']);
			if(in_array('overorder',  $checkinfo))
				$dosql->ExecNoneQuery("DELETE FROM `#@__goodsorder` WHERE `id`=$v AND `username`='$c_uname'");
		}
	}

	header('location:?c=order');
	exit();
}


//确认收货
else if($a == 'getgoods')
{
	$r = $dosql->GetOne("SELECT `checkinfo` FROM `#@__goodsorder` WHERE `username`='$c_uname' AND `id`=$id");
	$checkinfo = explode(',',$r['checkinfo']);

	if(!in_array('getgoods', $checkinfo))
	{
		$checkinfo = $r['checkinfo'].',getgoods';
	}

	$dosql->ExecNoneQuery("UPDATE `#@__goodsorder` SET checkinfo='$checkinfo' WHERE `username`='$c_uname' AND `id`=$id");
	header('location:?c=ordershow&id='.$id);
	exit();
}


//申请退款
else if($a == 'applyreturn')
{
	$r = $dosql->GetOne("SELECT `checkinfo` FROM `#@__goodsorder` WHERE `username`='$c_uname' AND `id`=$id");
	$checkinfo = explode(',',$r['checkinfo']);

	if(!in_array('applyreturn', $checkinfo))
	{
		$checkinfo = $r['checkinfo'].',applyreturn';
	}

	$dosql->ExecNoneQuery("UPDATE `#@__goodsorder` SET checkinfo='$checkinfo' WHERE `username`='$c_uname' AND `id`=$id");
	header('location:?c=ordershow&id='.$id);
	exit();
}


//支付余额
else if($a == 'pay')
{
	//
	header('location:orderpay.php');
	exit();
}


//完善账号
else if($a == 'perfect')
{
	//初始化参数
	$username   = empty($username)   ? '' : $username;
	$password   = empty($password)   ? '' : md5(md5($password));
	$repassword = empty($repassword) ? '' : md5(md5($repassword));
	$email      = empty($email)      ? '' : $email;


	//验证输入数据
	if($username == '' or
	   $password == '' or
	   $repassword == '' or
	   $email == '')
	{
		header('location:?c=perfect');
		exit();
	}


	if($password != $repassword)
	{
		header('location:?c=perfect');
		exit();
	}


    $uname_len = strlen($username);
	$upwd_len  = strlen($_POST['password']);
	if($uname_len<6 or $uname_len>16 or $upwd_len<6 or $upwd_len>16)
	{
		header('location:?c=perfect');
		exit();
	}

	if(preg_match("/[^0-9a-zA-Z_@!\.-]/",$username) or
	   preg_match("/[^0-9a-zA-Z_-]/",$password) or
	   !preg_match("/^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)+$/", $email))
	{
		header('location:?c=perfect');
		exit();
	}

	$r = $dosql->GetOne("SELECT `id` FROM `#@__member` WHERE `username`='$username'");
	if(isset($r['id']))
	{
		ShowMsg('用户名已存在！','-1');
		exit();
	}

	$r = $dosql->GetOne("SELECT `id` FROM `#@__member` WHERE `email`='$email'");
	if(isset($r['id']))
	{
		ShowMsg('您填写的邮箱已被注册！','-1');
		exit();
	}


	//添加用户数据
	$regtime  = time();
	$regip    = GetIP();


	if(check_app_login('qq'))
	{
		$r = $dosql->GetOne("SELECT `id` FROM `#@__member` WHERE `qqid`='".$_SESSION['app']['qq']['uid']."'");
		if(isset($r['id']))
			ShowMsg('该QQ已与其他账号绑定！','-1');
		else
			$sql = "INSERT INTO `#@__member` (username, password, email, expval, regtime, regip, logintime, loginip, qqid) VALUES ('$username', '$password', '$email', '10', '$regtime', '$regip', '$regtime', '$regip', '".$_SESSION['app']['qq']['uid']."')";
	}

	else if(check_app_login('weibo'))
	{
		$r = $dosql->GetOne("SELECT `id` FROM `#@__member` WHERE `qqid`='".$_SESSION['app']['weibo']['idstr']."'");
		if(isset($r['id']))
			ShowMsg('该微博已与其他账号绑定！','-1');
		else
			$sql = "INSERT INTO `#@__member` (username, password, email, expval, regtime, regip, logintime, loginip, weiboid) VALUES ('$username', '$password', '$email', '10', '$regtime', '$regip', '$regtime', '$regip', '".$_SESSION['app']['weibo']['idstr']."')";
	}

	$dosql->ExecNoneQuery($sql);


	//用绑定账号登录
	$cookie_time = time()+3600;
	setcookie('username',      AuthCode($username ,'ENCODE'), $cookie_time);
	setcookie('lastlogintime', AuthCode($regtime  ,'ENCODE'), $cookie_time);
	setcookie('lastloginip',   AuthCode($regip    ,'ENCODE'), $cookie_time);

	ShowMsg('完善账号成功！','?c=default');
	exit();

}


//绑定账号
else if($a == 'binding')
{
	//初始化参数
	$username = empty($username) ? '' : $username;
	$password = empty($password) ? '' : md5(md5($password));


	//验证输入数据
	if($username == '' or $password == '')
	{
		header('location:?c=binding');
		exit();
	}

	$row = $dosql->GetOne("SELECT `id`,`password`,`logintime`,`loginip`,`expval` FROM `#@__member` WHERE `username`='$username'");

	//密码错误
	if(!is_array($row) or $password!=$row['password'])
	{
		ShowMsg('您输入的用户名或密码错误！','-1');
		exit();
	}
	else
	{
		if(check_app_login('qq'))
		{
			$r = $dosql->GetOne("SELECT `id` FROM `#@__member` WHERE `qqid`='".$_SESSION['app']['qq']['uid']."'");
			if(isset($r['id']))
			{
				ShowMsg('该QQ已与其他账号绑定！','-1');
			}
			else
			{
				$qqid = $_SESSION['app']['qq']['uid'];
				$sql = "UPDATE `#@__member` SET `qqid`='$qqid' WHERE `username`='$username'";
			}
		}

		else if(check_app_login('weibo'))
		{
			$r = $dosql->GetOne("SELECT `id` FROM `#@__member` WHERE `qqid`='".$_SESSION['app']['weibo']['idstr']."'");
			if(isset($r['id']))
			{
				ShowMsg('该微博已与其他账号绑定！','-1');
			}
			else
			{
				$weiboid = $_SESSION['app']['weibo']['idstr'];
				$sql = "UPDATE `#@__member` SET `weiboid`='$weiboid' WHERE `username`='$username'";
			}
		}

		$dosql->ExecNoneQuery($sql);

		//用绑定账号登录
		$cookie_time = time()+3600;
		setcookie('username',      AuthCode($username        ,'ENCODE'), $cookie_time);
		setcookie('lastlogintime', AuthCode($row['logintime'],'ENCODE'), $cookie_time);
		setcookie('lastloginip',   AuthCode($row['loginip']  ,'ENCODE'), $cookie_time);

		ShowMsg('绑定账号成功！','?c=default');
		exit();
	}

}


//移除绑定QQ
else if($a == 'removeoqq')
{
	$r = $dosql->GetOne("SELECT `id` FROM `#@__member` WHERE `username`='$c_uname' AND `qqid`<>''");
	if(empty($r) && !is_array($r))
	{
		ShowMsg('错误的操作，您没有绑定QQ账号！','-1');
	}
	else
	{
		$dosql->ExecNoneQuery("UPDATE `#@__member` SET `qqid`='' WHERE `username`='$c_uname'");
		ShowMsg('解除QQ绑定成功！','?c=edit');
	}

	exit();
}


//移除绑定微博
else if($a == 'removeoweibo')
{
	$r = $dosql->GetOne("SELECT `id` FROM `#@__member` WHERE `username`='$c_uname' AND `weiboid`<>''");
	if(empty($r) && !is_array($r))
	{
		ShowMsg('错误的操作，您没有绑定微博账号！','-1');
	}
	else
	{
		$dosql->ExecNoneQuery("UPDATE `#@__member` SET `weiboid`='' WHERE `username`='$c_uname'");
		ShowMsg('解除微博绑定成功！','?c=edit');
	}

	exit();
}




//加载模板页面
if($c == 'login')
{
	if(!empty($c_uname))
	{
		$r = $dosql->GetOne("SELECT `id` FROM `#@__member` WHERE `username`='$c_uname'");
		if(is_array($r))
		{
			header('location:?c=default');
			exit();
		}
		else
		{
			setcookie('username',      '', time()-3600);
			setcookie('lastlogintime', '', time()-3600);
			setcookie('lastloginip',   '', time()-3600);
			ShowMsg('该用户已不存在！','?c=login');
			exit();
		}
	}
	else
	{
		require_once(PHPMYWIND_TEMP.'/default/member/login.php');
		exit();
	}
}

if($c=='default'  or $c=='edit'   or $c=='comment' or
   $c=='favorite' or $c=='order'  or $c=='ordershow' or
   $c=='msg'      or $c=='avatar' or $c=='perfect' or
   $c=='binding')
{
	if(!empty($c_uname))
	{
		//guest为同步登录未绑定账号时的临时用户
		if($c_uname != 'guest')
		{
			$r = $dosql->GetOne("SELECT `id`,`expval` FROM `#@__member` WHERE `username`='$c_uname'");
			if(!is_array($r))
			{
				setcookie('username',      '', time()-3600);
				setcookie('lastlogintime', '', time()-3600);
				setcookie('lastloginip',   '', time()-3600);
				ShowMsg('该用户已不存在！','?c=login');
				exit();
			}
			else if($r['expval'] <= 0)
			{
				ShowMsg('抱歉，您的账号被禁止登录！','?c=login');
				exit();
			}
		}
	}
	else
	{
		header('location:?c=login');
		exit();
	}
}



//会员中心
if($c == 'default')
{
	if($c_uname != 'guest')
		require_once(PHPMYWIND_TEMP.'/default/member/default.php');
	else
		require_once(PHPMYWIND_TEMP.'/default/member/defaultguest.php');

	exit();
}


//上传头像
else if($c == 'avatar')
{
	require_once(PHPMYWIND_TEMP.'/default/member/avatar.php');
	exit();
}


//编辑资料
else if($c == 'edit')
{
	require_once(PHPMYWIND_TEMP.'/default/member/edit.php');
	exit();
}


//评论列表
else if($c == 'comment')
{
	require_once(PHPMYWIND_TEMP.'/default/member/comment.php');
	exit();
}


//收藏列表
else if($c == 'favorite')
{
	require_once(PHPMYWIND_TEMP.'/default/member/favorite.php');
	exit();
}


//订单列表
else if($c == 'order')
{
	require_once(PHPMYWIND_TEMP.'/default/member/order.php');
	exit();
}


//订单详情
else if($c == 'ordershow')
{
	require_once(PHPMYWIND_TEMP.'/default/member/ordershow.php');
	exit();
}


//留言列表
else if($c == 'msg')
{
	require_once(PHPMYWIND_TEMP.'/default/member/msg.php');
	exit();
}

//完善绑定账号
else if($c == 'perfect')
{
	if(isset($c_uname) && $c_uname == 'guest')
		require_once(PHPMYWIND_TEMP.'/default/member/perfect.php');
	else if(isset($c_uname) && $c_uname != 'guest')
		header('location:?c=default');
	else
		header('location:?c=login');

	exit();
}


//绑定已有账号
else if($c == 'binding')
{
	if(isset($c_uname) && $c_uname == 'guest')
		require_once(PHPMYWIND_TEMP.'/default/member/binding.php');
	else if(isset($c_uname) && $c_uname != 'guest')
		header('location:?c=default');
	else
		header('location:?c=login');

	exit();
}


//用户注册
else if($c == 'reg')
{
	require_once(PHPMYWIND_TEMP.'/default/member/reg.php');
	exit();
}


//找回密码
else if($c == 'findpwd')
{
	require_once(PHPMYWIND_TEMP.'/default/member/findpwd.php');
	exit();
}


//找回密码
else if($c == 'findpwd2')
{
	if(!isset($_POST['username']))
		header('location:?c=findpwd');
	else
		require_once(PHPMYWIND_TEMP.'/default/member/findpwd2.php');

	exit();
}


//找回密码
else if($c == 'findpwd3')
{
	if(!isset($_POST['uname']))
		header('location:?c=findpwd');
	else
		require_once(PHPMYWIND_TEMP.'/default/member/findpwd3.php');

	exit();
}


else
{
	header('location:?c=login');
	exit();
}



//验证码获取函数
function GetCkVdValue()
{
	if(!isset($_SESSION)) session_start();
	return isset($_SESSION['ckstr']) ? $_SESSION['ckstr'] : '';
}


//验证码重置函数
function ResetVdValue()
{
	if(!isset($_SESSION)) session_start();
	$_SESSION['ckstr'] = '';
}
