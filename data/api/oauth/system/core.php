<?php	require_once(dirname(__FILE__).'/../../../../include/common.inc.php');

/*
**************************
(C)2010-2015 phpMyWind.com
update: 2014-5-2 15:31:38
person: Feng/Karson
**************************
*/


//是否启用一键登录
if($cfg_oauth == 'N')
{
	echo '对不起，系统没有启用一键登录功能！';
	exit();
}


//系统支持检查
if(!function_exists('curl_init'))
{
    echo '对不起，您的服务器不支持 PHP 的 Curl 模块，请安装或与服务器管理员联系。';
    exit();
}


@session_start();


//QQ
define('QQ_AKEY', $cfg_qq_appid);
define('QQ_SKEY', $cfg_qq_appkey);
define('QQ_SCOPE', 'get_user_info,add_share,list_album,add_album,upload_pic,add_topic,add_one_blog,add_weibo,check_page_fans,add_t,add_pic_t,del_t,get_repost_list,get_info,get_other_info,get_fanslist,get_idolist,add_idol,del_idol,get_tenpay_addr');


//新浪微博
define('WB_AKEY', $cfg_weibo_appid);
define('WB_SKEY', $cfg_weibo_appkey);
define('WB_SCOPE', '');


//人人网
/*define('RR_AKEY', '');
define('RR_SKEY', '');
define('RR_SCOPE', 'publish_blog photo_upload publish_checkin publish_feed publish_share write_guestbook send_invitation send_request send_message email read_user_blog read_user_checkin read_user_feed');*/


//引用核心文件
require_once(dirname(__FILE__).'/function.php');
require_once(dirname(__FILE__).'/weibo.php');
//require_once(dirname(__FILE__).'/renren.php');
require_once(dirname(__FILE__).'/qq.php');


set_exception_handler('exception_error');


//初始化
$weibo  = new weibo();
//$renren = new Renren();
$qq     = new QQ();

?>
