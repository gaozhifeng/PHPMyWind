<?php	require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('web_config');

/*
**************************
(C)2010-2015 phpMyWind.com
update: 2012-8-22 9:08:29
person: Feng
**************************
*/


//初始化参数
$gourl  = 'web_config.php';
$action = isset($action) ? $action : '';


//引入相关文件
$config_cache  = PHPMYWIND_INC.'/config.cache.php';
$watermark_inc = PHPMYWIND_DATA.'/watermark/watermark.inc.php';
$manageui_inc  = ADMIN_INC.'/manageui.inc.php';


//设置选项卡项
$config_tab_arr = array('基本设置','附件设置','性能设置','URL静态化','核心设置');


//统计当前数组数量
$config_tab_num = count($config_tab_arr);


//更新配置函数
function WriteConfig()
{
	global $dosql, $config_cache, $gourl;


	$str = '<?php	if(!defined(\'IN_PHPMYWIND\')) exit(\'Request Error!\');'."\r\n\r\n";
	$dosql->Execute("SELECT `varname`,`vartype`,`varvalue`,`vargroup` FROM `#@__webconfig` ORDER BY orderid ASC");
	while($row = $dosql->GetArray())
	{
		//强制去掉 '
		//强制去掉最后一位 /
		$vartmp = str_replace("'",'',$row['varvalue']);

		if(substr($vartmp, -1) == '\\')
		{
			$vartmp = substr($vartmp,1,-1);
		}

		if($row['vartype'] == 'number')
		{
			if($row['varvalue'] == '')
			{
				$vartmp = 0;
			}

			$str .= "\${$row['varname']} = ".$vartmp.";\r\n";
		}
		else
		{
			$str .= "\${$row['varname']} = '".$vartmp."';\r\n";
		}
	}
	$str .= '?>';

	if(!Writef($config_cache,$str))
	{
		ShowMsg("变量成功保存，但由于 config.cache.php 无法写入，因此不能更新配置！", $gourl);
		exit();
	}

	RewriteURL();
}


//返回URL重写字符串
function RewriteURL_Str($d,$r)
{

	$num = 1;
	$u   = '';
	$u2  = '';
	$u6  = '';
	$u7  = '';

	foreach($d[1] as $k=>$v)
	{
		if(stripos(',,about,news,newsshow,product,productshow,case,caseshow,join,joinshow,message,contact,soft,softshow,goods,goodsshow,vote,,',','.$v.','))
		{
			$r = str_ireplace(array('{'.$v.'}'),$v,$r);
			$u  .= $v.'.php?';
			$u2 .= $v.'.php?';
			$u6 .= $v.'\.php\?';
			$u7 .= $v.'.php\?';
		}
		else if(stripos(',,file,,',','.$v.','))
		{
			$r = str_ireplace(array('{'.$v.'}'),'(\w+)',$r);
			$u  .= '[-|'.$num.'|-].php?';
			$u2 .= '[-|'.($num+1).'|-].php?';
			$u6 .= '[-|'.($num+1).'|-]\.php\?';
			$u7 .= '[-|'.($num+1).'|-].php\?';
			$num++;
		}
		else
		{
			if(stripos(',,id,cid,tid,page,keyword,,', ','.$v.','))
			{
				if($v == 'keyword')
				{
					$r = str_ireplace($d[0][$k],'(\w+)',$r);
				}
				else
				{
					$r = str_ireplace($d[0][$k],'([0-9]+)',$r);
				}
			}
			else
			{
				$r = str_ireplace($d[0][$k],'(\w+)',$r);
			}

			$u  .= $v.'=[-|'.$num.'|-]&';
			$u2 .= $v.'=[-|'.($num+1).'|-]&';
			$u6 .= $v.'=[-|'.($num+1).'|-]&';
			$u7 .= $v.'=[-|'.($num+1).'|-]&amp;amp;';

			$num++;
		}
	}

	//替换前后缀
	$u    = rtrim($u,'&');
	$u2   = str_ireplace('[-|', '$', str_ireplace('|-]', '', rtrim($u2,'&')));
	$u6   = str_ireplace('[-|', '$', str_ireplace('|-]', '', rtrim($u6,'&')));
	$u7   = str_ireplace('|-]', '}', str_ireplace('[-|', '{R:',rtrim($u7,'&amp;amp;')));
	$newr = str_ireplace('.', '\.', $r);
	$newu = str_ireplace('[-|', '$', str_ireplace('|-]', '', $u));


	return array($newr, $newu, $u6, $u7, $u2);
}


//更新 RewrtieURL 规则
function RewriteURL()
{
	global $cfg_reurl_index, $cfg_reurl_about, $cfg_reurl_news, $cfg_reurl_newsshow,
	       $cfg_reurl_product, $cfg_reurl_productshow, $cfg_reurl_case, $cfg_reurl_caseshow,
		   $cfg_reurl_join, $cfg_reurl_joinshow, $cfg_reurl_message, $cfg_reurl_contact,
		   $cfg_reurl_soft, $cfg_reurl_softshow, $cfg_reurl_goods, $cfg_reurl_goodsshow,
		   $cfg_reurl_vote, $cfg_reurl_custom, $cfg_webpath,
		   $gourl;


	//分析设置的重写规则
	preg_match_all('/\{(.*?)\}/si', $cfg_reurl_about,       $r_about);
	preg_match_all('/\{(.*?)\}/si', $cfg_reurl_news,        $r_news);
	preg_match_all('/\{(.*?)\}/si', $cfg_reurl_newsshow,    $r_newsshow);
	preg_match_all('/\{(.*?)\}/si', $cfg_reurl_product,     $r_product);
	preg_match_all('/\{(.*?)\}/si', $cfg_reurl_productshow, $r_productshow);
	preg_match_all('/\{(.*?)\}/si', $cfg_reurl_case,        $r_case);
	preg_match_all('/\{(.*?)\}/si', $cfg_reurl_caseshow,    $r_caseshow);
	preg_match_all('/\{(.*?)\}/si', $cfg_reurl_join,        $r_join);
	preg_match_all('/\{(.*?)\}/si', $cfg_reurl_joinshow,    $r_joinshow);
	preg_match_all('/\{(.*?)\}/si', $cfg_reurl_message,     $r_message);
	preg_match_all('/\{(.*?)\}/si', $cfg_reurl_contact,     $r_contact);
	preg_match_all('/\{(.*?)\}/si', $cfg_reurl_soft,        $r_soft);
	preg_match_all('/\{(.*?)\}/si', $cfg_reurl_softshow,    $r_softshow);
	preg_match_all('/\{(.*?)\}/si', $cfg_reurl_goods,        $r_goods);
	preg_match_all('/\{(.*?)\}/si', $cfg_reurl_goodsshow,    $r_goodsshow);
	preg_match_all('/\{(.*?)\}/si', $cfg_reurl_vote,        $r_vote);
	preg_match_all('/\{(.*?)\}/si', $cfg_reurl_custom,      $r_custom);

	$r_index       = $cfg_reurl_index;
	$r_about       = RewriteURL_Str($r_about,       $cfg_reurl_about);
	$r_news        = RewriteURL_Str($r_news,        $cfg_reurl_news);
	$r_newsshow    = RewriteURL_Str($r_newsshow,    $cfg_reurl_newsshow);
	$r_product     = RewriteURL_Str($r_product,     $cfg_reurl_product);
	$r_productshow = RewriteURL_Str($r_productshow, $cfg_reurl_productshow);
	$r_case        = RewriteURL_Str($r_case,        $cfg_reurl_case);
	$r_caseshow    = RewriteURL_Str($r_caseshow,    $cfg_reurl_caseshow);
	$r_join        = RewriteURL_Str($r_join,        $cfg_reurl_join);
	$r_joinshow    = RewriteURL_Str($r_joinshow,    $cfg_reurl_joinshow);
	$r_message     = RewriteURL_Str($r_message,     $cfg_reurl_message);
	$r_contact     = RewriteURL_Str($r_contact,     $cfg_reurl_contact);
	$r_soft        = RewriteURL_Str($r_soft,        $cfg_reurl_soft);
	$r_softshow    = RewriteURL_Str($r_softshow,    $cfg_reurl_softshow);
	$r_goods       = RewriteURL_Str($r_goods,        $cfg_reurl_goods);
	$r_goodsshow   = RewriteURL_Str($r_goodsshow,    $cfg_reurl_goodsshow);
	$r_vote        = RewriteURL_Str($r_vote,        $cfg_reurl_vote);
	$r_custom      = RewriteURL_Str($r_custom,      $cfg_reurl_custom);


	//apache独立主机规则
	$apache  = 'RewriteCond %{QUERY_STRING} ^(.*)$'."\r\n";
	$apache .= 'RewriteRule ^(.*)/'.$r_index.'$ $1/index.php'."\r\n";
	$apache .= 'RewriteCond %{QUERY_STRING} ^(.*)$'."\r\n";
	$apache .= 'RewriteRule ^(.*)/'.$r_about[0].'$ $1/'.$r_about[4]."\r\n";
	$apache .= 'RewriteCond %{QUERY_STRING} ^(.*)$'."\r\n";
	$apache .= 'RewriteRule ^(.*)/'.$r_news[0].'$ $1/'.$r_news[4]."\r\n";
	$apache .= 'RewriteCond %{QUERY_STRING} ^(.*)$'."\r\n";
	$apache .= 'RewriteRule ^(.*)/'.$r_newsshow[0].'$ $1/'.$r_newsshow[4]."\r\n";
	$apache .= 'RewriteCond %{QUERY_STRING} ^(.*)$'."\r\n";
	$apache .= 'RewriteRule ^(.*)/'.$r_product[0].'$ $1/'.$r_product[4]."\r\n";
	$apache .= 'RewriteCond %{QUERY_STRING} ^(.*)$'."\r\n";
	$apache .= 'RewriteRule ^(.*)/'.$r_productshow[0].'$ $1/'.$r_productshow[4]."\r\n";
	$apache .= 'RewriteCond %{QUERY_STRING} ^(.*)$'."\r\n";
	$apache .= 'RewriteRule ^(.*)/'.$r_case[0].'$ $1/'.$r_case[4]."\r\n";
	$apache .= 'RewriteCond %{QUERY_STRING} ^(.*)$'."\r\n";
	$apache .= 'RewriteRule ^(.*)/'.$r_caseshow[0].'$ $1/'.$r_caseshow[4]."\r\n";
	$apache .= 'RewriteCond %{QUERY_STRING} ^(.*)$'."\r\n";
	$apache .= 'RewriteRule ^(.*)/'.$r_join[0].'$ $1/'.$r_join[4]."\r\n";
	$apache .= 'RewriteCond %{QUERY_STRING} ^(.*)$'."\r\n";
	$apache .= 'RewriteRule ^(.*)/'.$r_joinshow[0].'$ $1/'.$r_joinshow[4]."\r\n";
	$apache .= 'RewriteCond %{QUERY_STRING} ^(.*)$'."\r\n";
	$apache .= 'RewriteRule ^(.*)/'.$r_message[0].'$ $1/'.$r_message[4]."\r\n";
	$apache .= 'RewriteCond %{QUERY_STRING} ^(.*)$'."\r\n";
	$apache .= 'RewriteRule ^(.*)/'.$r_contact[0].'$ $1/'.$r_contact[4]."\r\n";
	$apache .= 'RewriteCond %{QUERY_STRING} ^(.*)$'."\r\n";
	$apache .= 'RewriteRule ^(.*)/'.$r_soft[0].'$ $1/'.$r_soft[4]."\r\n";
	$apache .= 'RewriteCond %{QUERY_STRING} ^(.*)$'."\r\n";
	$apache .= 'RewriteRule ^(.*)/'.$r_softshow[0].'$ $1/'.$r_softshow[4]."\r\n";
	$apache .= 'RewriteCond %{QUERY_STRING} ^(.*)$'."\r\n";
	$apache .= 'RewriteRule ^(.*)/'.$r_goods[0].'$ $1/'.$r_goods[4]."\r\n";
	$apache .= 'RewriteCond %{QUERY_STRING} ^(.*)$'."\r\n";
	$apache .= 'RewriteRule ^(.*)/'.$r_goodsshow[0].'$ $1/'.$r_goodsshow[4]."\r\n";
	$apache .= 'RewriteCond %{QUERY_STRING} ^(.*)$'."\r\n";
	$apache .= 'RewriteRule ^(.*)/'.$r_vote[0].'$ $1/'.$r_vote[4]."\r\n";
	$apache .= 'RewriteCond %{QUERY_STRING} ^(.*)$'."\r\n";
	$apache .= 'RewriteRule ^(.*)/'.$r_custom[0].'$ $1/'.$r_custom[4]."\r\n";


	//apache虚拟主机规则
	$apache2  = 'RewriteCond %{QUERY_STRING} ^(.*)$'."\r\n";
	$apache2 .= 'RewriteRule ^'.$r_index.'$ index.php'."\r\n";
	$apache2 .= 'RewriteCond %{QUERY_STRING} ^(.*)$'."\r\n";
	$apache2 .= 'RewriteRule ^'.$r_about[0].'$ '.$r_about[1]."\r\n";
	$apache2 .= 'RewriteCond %{QUERY_STRING} ^(.*)$'."\r\n";
	$apache2 .= 'RewriteRule ^'.$r_news[0].'$ '.$r_news[1]."\r\n";
	$apache2 .= 'RewriteCond %{QUERY_STRING} ^(.*)$'."\r\n";
	$apache2 .= 'RewriteRule ^'.$r_newsshow[0].'$ '.$r_newsshow[1]."\r\n";
	$apache2 .= 'RewriteCond %{QUERY_STRING} ^(.*)$'."\r\n";
	$apache2 .= 'RewriteRule ^'.$r_product[0].'$ '.$r_product[1]."\r\n";
	$apache2 .= 'RewriteCond %{QUERY_STRING} ^(.*)$'."\r\n";
	$apache2 .= 'RewriteRule ^'.$r_productshow[0].'$ '.$r_productshow[1]."\r\n";
	$apache2 .= 'RewriteCond %{QUERY_STRING} ^(.*)$'."\r\n";
	$apache2 .= 'RewriteRule ^'.$r_case[0].'$ '.$r_case[1]."\r\n";
	$apache2 .= 'RewriteCond %{QUERY_STRING} ^(.*)$'."\r\n";
	$apache2 .= 'RewriteRule ^'.$r_caseshow[0].'$ '.$r_caseshow[1]."\r\n";
	$apache2 .= 'RewriteCond %{QUERY_STRING} ^(.*)$'."\r\n";
	$apache2 .= 'RewriteRule ^'.$r_join[0].'$ '.$r_join[1]."\r\n";
	$apache2 .= 'RewriteCond %{QUERY_STRING} ^(.*)$'."\r\n";
	$apache2 .= 'RewriteRule ^'.$r_joinshow[0].'$ '.$r_joinshow[1]."\r\n";
	$apache2 .= 'RewriteCond %{QUERY_STRING} ^(.*)$'."\r\n";
	$apache2 .= 'RewriteRule ^'.$r_message[0].'$ '.$r_message[1]."\r\n";
	$apache2 .= 'RewriteCond %{QUERY_STRING} ^(.*)$'."\r\n";
	$apache2 .= 'RewriteRule ^'.$r_contact[0].'$ '.$r_contact[1]."\r\n";
	$apache2 .= 'RewriteCond %{QUERY_STRING} ^(.*)$'."\r\n";
	$apache2 .= 'RewriteRule ^'.$r_soft[0].'$ '.$r_soft[1]."\r\n";
	$apache2 .= 'RewriteCond %{QUERY_STRING} ^(.*)$'."\r\n";
	$apache2 .= 'RewriteRule ^'.$r_softshow[0].'$ '.$r_softshow[1]."\r\n";
	$apache2 .= 'RewriteCond %{QUERY_STRING} ^(.*)$'."\r\n";
	$apache2 .= 'RewriteRule ^'.$r_goods[0].'$ '.$r_goods[1]."\r\n";
	$apache2 .= 'RewriteCond %{QUERY_STRING} ^(.*)$'."\r\n";
	$apache2 .= 'RewriteRule ^'.$r_goodsshow[0].'$ '.$r_goodsshow[1]."\r\n";
	$apache2 .= 'RewriteCond %{QUERY_STRING} ^(.*)$'."\r\n";
	$apache2 .= 'RewriteRule ^'.$r_vote[0].'$ '.$r_vote[1]."\r\n";
	$apache2 .= 'RewriteCond %{QUERY_STRING} ^(.*)$'."\r\n";
	$apache2 .= 'RewriteRule ^'.$r_custom[0].'$ '.$r_custom[1]."\r\n";


	//iis规则
	$iis  = 'RewriteRule ^(.*)/'.$r_index.'$ $1/index.php'."\r\n";
	$iis .= 'RewriteRule ^(.*)/'.$r_about[0].'$ $1/'.$r_about[2]."\r\n";
	$iis .= 'RewriteRule ^(.*)/'.$r_news[0].'$ $1/'.$r_news[2]."\r\n";
	$iis .= 'RewriteRule ^(.*)/'.$r_newsshow[0].'$ $1/'.$r_newsshow[2]."\r\n";
	$iis .= 'RewriteRule ^(.*)/'.$r_product[0].'$ $1/'.$r_product[2]."\r\n";
	$iis .= 'RewriteRule ^(.*)/'.$r_productshow[0].'$ $1/'.$r_productshow[2]."\r\n";
	$iis .= 'RewriteRule ^(.*)/'.$r_case[0].'$ $1/'.$r_case[2]."\r\n";
	$iis .= 'RewriteRule ^(.*)/'.$r_caseshow[0].'$ $1/'.$r_caseshow[2]."\r\n";
	$iis .= 'RewriteRule ^(.*)/'.$r_join[0].'$ $1/'.$r_join[2]."\r\n";
	$iis .= 'RewriteRule ^(.*)/'.$r_joinshow[0].'$ $1/'.$r_joinshow[2]."\r\n";
	$iis .= 'RewriteRule ^(.*)/'.$r_message[0].'$ $1/'.$r_message[2]."\r\n";
	$iis .= 'RewriteRule ^(.*)/'.$r_contact[0].'$ $1/'.$r_contact[2]."\r\n";
	$iis .= 'RewriteRule ^(.*)/'.$r_soft[0].'$ $1/'.$r_soft[2]."\r\n";
	$iis .= 'RewriteRule ^(.*)/'.$r_softshow[0].'$ $1/'.$r_softshow[2]."\r\n";
	$iis .= 'RewriteRule ^(.*)/'.$r_goods[0].'$ $1/'.$r_goods[2]."\r\n";
	$iis .= 'RewriteRule ^(.*)/'.$r_goodsshow[0].'$ $1/'.$r_goodsshow[2]."\r\n";
	$iis .= 'RewriteRule ^(.*)/'.$r_vote[0].'$ $1/'.$r_vote[2]."\r\n";
	$iis .= 'RewriteRule ^(.*)/'.$r_custom[0].'$ $1/'.$r_custom[2]."\r\n";


	//iis7规则
	$iis7  = '&lt;rule name="index"&gt;'."\r\n";
	$iis7 .= '	&lt;match url="^(.*/)*'.$r_index.'$" /&gt;'."\r\n";
	$iis7 .= '	&lt;action type="Rewrite" url="{R:1}/index.php'.'" /&gt;'."\r\n";
	$iis7 .= '&lt;/rule&gt;'."\r\n";

	$iis7 .= '&lt;rule name="about"&gt;'."\r\n";
	$iis7 .= '	&lt;match url="^(.*/)*'.$r_about[0].'$" /&gt;'."\r\n";
	$iis7 .= '	&lt;action type="Rewrite" url="{R:1}/'.$r_about[3].'" /&gt;'."\r\n";
	$iis7 .= '&lt;/rule&gt;'."\r\n";

	$iis7 .= '&lt;rule name="news"&gt;'."\r\n";
	$iis7 .= '	&lt;match url="^(.*/)*'.$r_news[0].'$" /&gt;'."\r\n";
	$iis7 .= '	&lt;action type="Rewrite" url="{R:1}/'.$r_news[3].'" /&gt;'."\r\n";
	$iis7 .= '&lt;/rule&gt;'."\r\n";

	$iis7 .= '&lt;rule name="newsshow"&gt;'."\r\n";
	$iis7 .= '	&lt;match url="^(.*/)*'.$r_newsshow[0].'$" /&gt;'."\r\n";
	$iis7 .= '	&lt;action type="Rewrite" url="{R:1}/'.$r_newsshow[3].'" /&gt;'."\r\n";
	$iis7 .= '&lt;/rule&gt;'."\r\n";

	$iis7 .= '&lt;rule name="product"&gt;'."\r\n";
	$iis7 .= '	&lt;match url="^(.*/)*'.$r_product[0].'$" /&gt;'."\r\n";
	$iis7 .= '	&lt;action type="Rewrite" url="{R:1}/'.$r_product[3].'" /&gt;'."\r\n";
	$iis7 .= '&lt;/rule&gt;'."\r\n";

	$iis7 .= '&lt;rule name="productshow"&gt;'."\r\n";
	$iis7 .= '	&lt;match url="^(.*/)*'.$r_productshow[0].'$" /&gt;'."\r\n";
	$iis7 .= '	&lt;action type="Rewrite" url="{R:1}/'.$r_productshow[3].'" /&gt;'."\r\n";
	$iis7 .= '&lt;/rule&gt;'."\r\n";

	$iis7 .= '&lt;rule name="case"&gt;'."\r\n";
	$iis7 .= '	&lt;match url="^(.*/)*'.$r_case[0].'$" /&gt;'."\r\n";
	$iis7 .= '	&lt;action type="Rewrite" url="{R:1}/'.$r_case[3].'" /&gt;'."\r\n";
	$iis7 .= '&lt;/rule&gt;'."\r\n";

	$iis7 .= '&lt;rule name="caseshow"&gt;'."\r\n";
	$iis7 .= '	&lt;match url="^(.*/)*'.$r_caseshow[0].'$" /&gt;'."\r\n";
	$iis7 .= '	&lt;action type="Rewrite" url="{R:1}/'.$r_caseshow[3].'" /&gt;'."\r\n";
	$iis7 .= '&lt;/rule&gt;'."\r\n";

	$iis7 .= '&lt;rule name="join"&gt;'."\r\n";
	$iis7 .= '	&lt;match url="^(.*/)*'.$r_join[0].'$" /&gt;'."\r\n";
	$iis7 .= '	&lt;action type="Rewrite" url="{R:1}/'.$r_join[3].'" /&gt;'."\r\n";
	$iis7 .= '&lt;/rule&gt;'."\r\n";

	$iis7 .= '&lt;rule name="joinshow"&gt;'."\r\n";
	$iis7 .= '	&lt;match url="^(.*/)*'.$r_joinshow[0].'$" /&gt;'."\r\n";
	$iis7 .= '	&lt;action type="Rewrite" url="{R:1}/'.$r_joinshow[3].'" /&gt;'."\r\n";
	$iis7 .= '&lt;/rule&gt;'."\r\n";

	$iis7 .= '&lt;rule name="message"&gt;'."\r\n";
	$iis7 .= '	&lt;match url="^(.*/)*'.$r_message[0].'$" /&gt;'."\r\n";
	$iis7 .= '	&lt;action type="Rewrite" url="{R:1}/'.$r_message[3].'" /&gt;'."\r\n";
	$iis7 .= '&lt;/rule&gt;'."\r\n";

	$iis7 .= '&lt;rule name="contact"&gt;'."\r\n";
	$iis7 .= '	&lt;match url="^(.*/)*'.$r_contact[0].'$" /&gt;'."\r\n";
	$iis7 .= '	&lt;action type="Rewrite" url="{R:1}/'.$r_contact[3].'" /&gt;'."\r\n";
	$iis7 .= '&lt;/rule&gt;'."\r\n";

	$iis7 .= '&lt;rule name="soft"&gt;'."\r\n";
	$iis7 .= '	&lt;match url="^(.*/)*'.$r_soft[0].'$" /&gt;'."\r\n";
	$iis7 .= '	&lt;action type="Rewrite" url="{R:1}/'.$r_soft[3].'" /&gt;'."\r\n";
	$iis7 .= '&lt;/rule&gt;'."\r\n";

	$iis7 .= '&lt;rule name="softshow"&gt;'."\r\n";
	$iis7 .= '	&lt;match url="^(.*/)*'.$r_softshow[0].'$" /&gt;'."\r\n";
	$iis7 .= '	&lt;action type="Rewrite" url="{R:1}/'.$r_softshow[3].'" /&gt;'."\r\n";
	$iis7 .= '&lt;/rule&gt;'."\r\n";

	$iis7 .= '&lt;rule name="goods"&gt;'."\r\n";
	$iis7 .= '	&lt;match url="^(.*/)*'.$r_goods[0].'$" /&gt;'."\r\n";
	$iis7 .= '	&lt;action type="Rewrite" url="{R:1}/'.$r_goods[3].'" /&gt;'."\r\n";
	$iis7 .= '&lt;/rule&gt;'."\r\n";

	$iis7 .= '&lt;rule name="goodsshow"&gt;'."\r\n";
	$iis7 .= '	&lt;match url="^(.*/)*'.$r_goodsshow[0].'$" /&gt;'."\r\n";
	$iis7 .= '	&lt;action type="Rewrite" url="{R:1}/'.$r_goodsshow[3].'" /&gt;'."\r\n";
	$iis7 .= '&lt;/rule&gt;'."\r\n";

	$iis7 .= '&lt;rule name="vote"&gt;'."\r\n";
	$iis7 .= '	&lt;match url="^(.*/)*'.$r_vote[0].'$" /&gt;'."\r\n";
	$iis7 .= '	&lt;action type="Rewrite" url="{R:1}/'.$r_vote[3].'" /&gt;'."\r\n";
	$iis7 .= '&lt;/rule&gt;'."\r\n";

	$iis7 .= '&lt;rule name="custom"&gt;'."\r\n";
	$iis7 .= '	&lt;match url="^(.*/)*'.$r_custom[0].'$" /&gt;'."\r\n";
	$iis7 .= '	&lt;action type="Rewrite" url="{R:1}/'.$r_custom[3].'" /&gt;'."\r\n";
	$iis7 .= '&lt;/rule&gt;'."\r\n";


	//nginx规则
	$nginx  = 'rewrite ^([^\.]*)/'.$r_index.'$ $1/index.php last;'."\r\n";
	$nginx .= 'rewrite ^([^\.]*)/'.$r_about[0].'$ $1/'.$r_about[4].' last;'."\r\n";
	$nginx .= 'rewrite ^([^\.]*)/'.$r_news[0].'$ $1/'.$r_news[4].' last;'."\r\n";
	$nginx .= 'rewrite ^([^\.]*)/'.$r_newsshow[0].'$ $1/'.$r_newsshow[4].' last;'."\r\n";
	$nginx .= 'rewrite ^([^\.]*)/'.$r_product[0].'$ $1/'.$r_product[4].' last;'."\r\n";
	$nginx .= 'rewrite ^([^\.]*)/'.$r_productshow[0].'$ $1/'.$r_productshow[4].' last;'."\r\n";
	$nginx .= 'rewrite ^([^\.]*)/'.$r_case[0].'$ $1/'.$r_case[4].' last;'."\r\n";
	$nginx .= 'rewrite ^([^\.]*)/'.$r_join[0].'$ $1/'.$r_join[4].' last;'."\r\n";
	$nginx .= 'rewrite ^([^\.]*)/'.$r_joinshow[0].'$ $1/'.$r_joinshow[4].' last;'."\r\n";
	$nginx .= 'rewrite ^([^\.]*)/'.$r_message[0].'$ $1/'.$r_message[4].' last;'."\r\n";
	$nginx .= 'rewrite ^([^\.]*)/'.$r_contact[0].'$ $1/'.$r_contact[4].' last;'."\r\n";
	$nginx .= 'rewrite ^([^\.]*)/'.$r_soft[0].'$ $1/'.$r_soft[4].' last;'."\r\n";
	$nginx .= 'rewrite ^([^\.]*)/'.$r_softshow[0].'$ $1/'.$r_softshow[4].' last;'."\r\n";
	$nginx .= 'rewrite ^([^\.]*)/'.$r_goods[0].'$ $1/'.$r_goods[4].' last;'."\r\n";
	$nginx .= 'rewrite ^([^\.]*)/'.$r_goodsshow[0].'$ $1/'.$r_goodsshow[4].' last;'."\r\n";
	$nginx .= 'rewrite ^([^\.]*)/'.$r_vote[0].'$ $1/'.$r_vote[4].' last;'."\r\n";
	$nginx .= 'rewrite ^([^\.]*)/'.$r_custom[0].'$ $1/'.$r_custom[4].' last;'."\r\n";


	/*
	 * 读取rewriteurl.html
	 * 进行标记替换
	*/
	if(empty($cfg_webpath))
		$webpath = '/';
	else
		$webpath = $cfg_webpath;

	$config_str = Readf(ADMIN_TEMP.'/html/rewriteurl.html');
	$config_str = str_replace('{apache}',  $apache,  $config_str);
	$config_str = str_replace('{apache2}', $apache2, $config_str);
	$config_str = str_replace('{iis}',     $iis,     $config_str);
	$config_str = str_replace('{iis7}',    $iis7,    $config_str);
	$config_str = str_replace('{nginx}',   $nginx,   $config_str);
	$config_str = str_replace('{webpath}', $webpath, $config_str);


	//将替换后的内容写入rewriteurl.php文件
	if(!Writef('rewriteurl.php', $config_str))
	{
		ShowMsg("文件失败 rewriteurl.php 文件失败，可能是由于没有写入权限，因此不能更新配置！", $gourl);
		exit();
	}
}


//更新变量
if($action == 'update')
{
	foreach($_POST as $k=>$v)
	{
		//统计代码转义
		$v = _RunMagicQuotes($v);

		if(!$dosql->ExecNoneQuery("UPDATE `#@__webconfig` SET `varvalue`='$v' WHERE varname='$k'"))
		{
			ShowMsg('更新变量失败，可能有非法字符！', $gourl);
			exit();
		}
	}

	WriteConfig();

	ShowMsg('成功保存变量并更新配置文件！', $gourl);
	exit();
}


//增加新变量
if($action == 'add')
{

	if($varname == '' || preg_match('/[^a-z_]/', $varname))
	{
		ShowMsg('变量名不能为空并必须为[a-z_]组成！', $gourl);
		exit();
	}

	//链接前缀
	$varname = 'cfg_'.$varname;

	if($vartype=='bool' && ($varvalue!='Y' && $varvalue!='N'))
	{
		ShowMsg('布尔变量值必须为\'Y\'或\'N\'！', $gourl);
		exit();
	}

	if($dosql->GetOne("SELECT `varname` FROM `#@__webconfig` WHERE varname='$varname'"))
	{
		ShowMsg('该变量名称已经存在！', $gourl);
		exit();
	}

	//获取OrderID
	$row = $dosql->GetOne("SELECT MAX(orderid) AS orderid FROM `#@__webconfig`");
	$orderid = $row['orderid'] + 1;

	$sql = "INSERT INTO `#@__webconfig` (siteid, varname, varinfo, varvalue, vartype, vargroup, orderid) VALUES ('$cfg_siteid', '$varname', '$varinfo', '$varvalue', '$vartype', '$vargroup', '$orderid')";
	if(!$dosql->ExecNoneQuery($sql))
	{
		ShowMsg('新增变量失败，可能有非法字符！', $gourl);
		exit();
	}

	WriteConfig();
	ShowMsg('成功保存变量并更新配置文件！', $gourl);
	exit();

}


//保存水印设置
if($action == 'update_wmk')
{
	$vars = array('cfg_markswitch','cfg_marktype','cfg_markminwidth','cfg_markminheight','cfg_markpicurl','cfg_marktext','cfg_markcolor','cfg_marksize','cfg_markwhere');
	$str = '';

	foreach($vars as $v)
	{
		${$v} = str_replace("'", "", ${$v});
		$str .= "\${$v} = '".${$v}."';\r\n";
	}

	$str = '<?php	if(!defined(\'IN_PHPMYWIND\')) exit(\'Request Error!\');'."\r\n\r\n".$str."\r\n".'?>';

	if(Writef($watermark_inc, $str))
	{
		ShowMsg('成功更新水印配置！', $gourl);
		exit();
	}
	else
	{
		ShowMsg('保存 watermark.inc.php 文件失败，可能是由于没有写入权限，因此不能更新配置！', $gourl);
		exit();
	}
}


//保存登录背景
if($action == 'update_manageui')
{
	$vars = array('cfg_loginbgimg','cfg_loginbgcolor','cfg_loginbgrepeat','cfg_loginbgpos');
	$str = '';

	foreach($vars as $v)
	{
		${$v} = str_replace("'", "", ${$v});
		$str .= "\${$v} = '".${$v}."';\r\n";
	}

	$str = '<?php	if(!defined(\'IN_PHPMYWIND\')) exit(\'Request Error!\');'."\r\n\r\n".$str."\r\n".'?>';

	if(Writef($manageui_inc, $str))
	{
		ShowMsg('成功更新界面设置！', $gourl);
		exit();
	}
	else
	{
		ShowMsg('保存 manageui.inc.php 文件失败，可能是由于没有写入权限，因此不能更新配置！', $gourl);
		exit();
	}
}


//引入HTML
require_once(ADMIN_TEMP.'/html/web_config.html');
