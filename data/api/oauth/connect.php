<?php	require_once(dirname(__FILE__).'/system/core.php');

/*
**************************
(C)2010-2015 phpMyWind.com
update: 2014-5-2 17:23:54
person: Feng/Karson
**************************
*/


//初始化连接
$connect = new Connect();
$method  = isset($method) ? $method : '';


if(substr($method, 0, 1) == '_' ||
   is_numeric(substr($method, 0, 1)))
{
    exit('参数请求错误！');
}


if(method_exists($connect, $method))
    $connect->$method();
else
    echo '请求的方法['.$method.']未找到';


//连接类
class Connect
{

    function __construct()
	{
        global $weibo, $renren, $qq;

        $this->weibo  = $weibo;
        $this->renren = $renren;
        $this->qq     = $qq;
        $this->url    = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME'];
    }


    //创建新浪微博的授权链接
    function weibo_token()
	{
        $state    = uniqid('weibo_', true);
        $callback = "{$this->url}?method=weibo_callback";
        $url = $this->weibo->getAuthorizeURL($callback, 'code', $state);
        $_SESSION['weibo_state'] = $state;

        go($url);
    }


    //新浪网微博授权回调
    function weibo_callback()
	{
        $state = $_REQUEST['state'];
        $code  = $_REQUEST['code'];

        if(!isset($_SESSION['weibo_state']) ||
		   $state !== $_SESSION['weibo_state'])
           exit("参数错误！请返回重新使用新浪微博登录！");

        $keys = array('code' => $code, 'redirect_uri' => "{$this->url}?method=weibo_callback");
        $token = $this->weibo->getAccessToken('code', $keys);

        if(isset($token['error']))
            exit('验证错误！请返回重新使用新浪微博登录！');

        $_SESSION['weibo_token'] = $token;

        go('connect.php?method=callback&app=weibo');
    }


    //创建QQ的授权链接
    function qq_token()
	{
        $state = uniqid('qq_', true);
        $callback = "{$this->url}?method=qq_callback";
        $url = $this->qq->getAuthorizeURL($callback, 'code', $state, QQ_SCOPE);
        $_SESSION['qq_state'] = $state;

        go($url);
    }


    //QQ授权回调
    function qq_callback()
	{

        $state = $_REQUEST['state'];
        $code  = $_REQUEST['code'];

        if (!isset($_SESSION['qq_state']) ||
		    $state !== $_SESSION['qq_state'])
            exit('参数错误！请返回重新使用QQ登录！');

        $keys = array('code' => $code, 'redirect_uri' => "{$this->url}?method=qq_callback");
        $token = $this->qq->getAccessToken('code', $keys);

        if(isset($token['error']))
            exit('验证错误！请返回重新使用QQ登录！');

        $_SESSION['qq_token'] = $token;
        $openid = $this->qq->getOpenID();
        $_SESSION['qq_token'] = array('access_token' => $token['access_token'], 'openid' => $openid, 'uid' => $openid, 'expires_in' => $token['expires_in']);

        go("connect.php?method=callback&app=qq");
    }


    //创建人人的授权链接
    function renren_token()
	{
        $state = uniqid('renren_', true);
        $callback = "{$this->url}?method=renren_callback";
        $url = $this->renren->getAuthorizeURL($callback, 'code', $state, RR_SCOPE);
        $_SESSION['renren_state'] = $state;

        go($url);
    }


    //人人授权回调
    function renren_callback()
	{
        $state = $_REQUEST['state'];
        $code = $_REQUEST['code'];
        if (!isset($_SESSION['renren_state']) || $state !== $_SESSION['renren_state'])
            exit("参数错误！请返回重新使用renren登录！");
        $keys = array('code' => $code, 'redirect_uri' => "{$this->url}?method=renren_callback");
        $token = $this->renren->getAccessToken('code', $keys);
        if (isset($token['error']))
            exit("验证错误！请返回重新使用renren登录！");
        $token['uid'] = $token['user']['id'];
        $_SESSION['renren_token'] = $token;
        $exprie = $token['expires_in'] + time();
        setcookie($this->renren->client_id . '_access_token', $token['access_token'], $exprie, '/');
        setcookie($this->renren->client_id . '_user', $token['user']['id'], $exprie, '/');
        setcookie($this->renren->client_id . '_expires', $exprie, $exprie, '/');

        go("connect.php?method=callback&app=renren");
    }


    //验证成功后的跳转地址
    function callback()
	{
		global $cfg_webpath;

        $app = isset($_GET['app']) ? $_GET['app'] : '';

        if (in_array($app, array('renren', 'weibo', 'qq')))
            $_SESSION['app'][$app] = get_app_info($app);

        go($cfg_webpath.'/member.php?a=login&method=callback&app='.$app);
    }


    //退出/注销
    function clear()
	{
		global $cfg_webpath;

        $app = isset($_GET['app']) ? $_GET['app'] : '';

        if(in_array($app, array('renren', 'weibo', 'qq')))
		{
            $_SESSION['app'][$app] = '';
            $_SESSION["{$app}_token"] = '';
        }

        go($cfg_webpath.'/member.php?a=login');
    }

}
