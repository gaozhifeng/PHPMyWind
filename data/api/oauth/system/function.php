<?php

/*
**************************
(C)2010-2015 phpMyWind.com
update: 2014-5-5 11:13:14
person: Feng/Karson
**************************
*/


/**
 * 获取指定应用的用户UID
 * @param string $app 应用名称 renren/weibo/qq 默认renren
 * @return mixed
 */
function get_app_uid($app = 'renren') {
    $uid = 0;
    if ($app == 'renren') {
        $uid = isset($_SESSION['renren_token']['uid']) ? $_SESSION['renren_token']['uid'] : 0;
    } else if ($app == 'weibo') {
        $uid = isset($_SESSION['weibo_token']['uid']) ? $_SESSION['weibo_token']['uid'] : 0;
    } else if ($app == 'qq') {
        $uid = isset($_SESSION['qq_token']['uid']) ? $_SESSION['qq_token']['uid'] : 0;
    }
    return $uid;
}

/**
 * 获取指定应用的信息
 * @param string $app 应用名称 renren/weibo/qq 默认renren
 * @return mixed
 */
function get_app_info($app = 'renren') {
    $result = array();
    if ($app == 'renren') {
        global $renren;
        $result = $renren->get_user_info();
        if (!isset($result[0]) || !is_array($result[0])) {
            exit("读取人人网用户数据失败！");
        }
        $result = $result[0];
        //$result['face'] = $result['headurl'];
        $result['face'] = $result['tinyurl'];
        $result['gender'] = $result['sex'] == 1 ? '男' : '女';
        $result['birthday'] = strtotime($result['birthday']);
    } else if ($app == 'weibo') {
        $weiboclient = new Weiboclient($_SESSION['weibo_token']['access_token']);
        $result = $weiboclient->show_user_by_id($_SESSION['weibo_token']['uid']);
        $result['uid'] = $result['id'];
        unset($result['id']);
        $result['face'] = $result['profile_image_url'];
        $result['gender'] = $result['gender'] == 'm' ? '男' : '女';
        $result['birthday'] = '';
    } else if ($app == 'qq') {
        $qqclient = new qqclient($_SESSION['qq_token']['access_token'], $_SESSION['qq_token']['openid']);
        $result = $qqclient->get_user_info();
        $result['name'] = $result['nickname'];
        $result['uid'] = $_SESSION['qq_token']['openid'];
        $result['face'] = $result['figureurl_2'];
        $result['gender'] = $result['gender'];
        $result['birthday'] = '';
    }
    return $result;
}

/**
 * 获取应用的中文名称
 * @param string $app renren/weibo/qq 默认renren
 * @return string
 */
function get_app_name($app = 'renren') {
    $app_array = array('renren' => '人人网', 'weibo' => '新浪微博', 'qq' => '腾讯QQ');
    $app_name = isset($app_array[$app]) ? $app_array[$app] : '';
    return $app_name;
}

/**
 * 跳转,客户端跳转
 * @param string $url 跳转链接
 */
function go($url) {
    echo "<script type='text/javascript'>location.href='{$url}';</script>";
}

/**
 * 判断应用是否已经登录
 * @param string $app 应用名称 renren/weibo/qq 默认renren
 * @return bool
 */
function check_app_login($app = 'renren') {
    if (!in_array($app, array('renren', 'weibo', 'qq')))
        return false;
    global $renren, $weibo, $qq;
    return $$app->verify() ? true : false;
}

/**
 * 多级对象转数组
 * @param obj $object 待转换的对象
 * @return array
 */
function obj2array($object = NULL) {
    $array = (array) $object;
    foreach ($array as $key => $val) {
        //判断是否为对象或数组，因为数组中可能还会存在对象
        if (is_object($val) || is_array($val)) {
            $val = obj2array($val);
        }
        $array[$key] = $val;
    }
    return $array;
}

/**
 * 获取客户端IP
 * @return string 获取的IP地址
 */
function get_ip() {
    if (!empty($_SERVER["HTTP_CLIENT_IP"]))
        $ip = $_SERVER["HTTP_CLIENT_IP"];
    else if (!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
        $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
    else if (!empty($_SERVER["REMOTE_ADDR"]))
        $ip = $_SERVER["REMOTE_ADDR"];
    else
        $ip = '';
    preg_match("/[\d\.]{7,15}/", $ip, $ips);
    $ip = isset($ips[0]) ? $ips[0] : 'Unknown';
    unset($ips);
    return $ip;
}

/**
 * HTML转文本简化,只替换style,frame,script,br
 * @param string $str 需要替换的字符
 * @return string
 */
function html2text($str) {
    $str = preg_replace("/<sty(.*)\\/style>|<scr(.*)\\/script>|<scr(.*)\\/script>|<!--(.*)-->/isU", '', $str);
    return strip_tags(str_replace(array('<br />', '<br>', '<br/>'), "\n", $str));
}

/**
 * 中文字符截取
 * @param string $string 截取的字符数据
 * @param int $length 截取的字符长度
 * @param string $dot 截取后的字符加上的字符，默认为...
 * @param string $charset 字符编码，默认为utf-8
 * @return string 截取后的数据
 */
function cutstr($string, $length, $dot = '...', $charset = 'utf-8') {
    if (strlen($string) <= $length || $length <= 0)
        return $string;
    $string = str_replace(array('&nbsp;', '&amp;', '&quot;', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '&lt;', '&gt;', '&middot;', '&hellip;'), array(' ', '&', '"', "'", '“', '”', '—', '<', '>', '·', '…'), $string);
    $strcut = '';
    if (strtolower($charset) == 'utf-8') {
        $n = $tn = $noc = 0;
        while ($n < strlen($string)) {
            $t = ord($string[$n]);
            if ($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
                $tn = 1;
                $n++;
                $noc++;
            } else if (194 <= $t && $t <= 223) {
                $tn = 2;
                $n += 2;
                $noc += 2;
            } else if (224 <= $t && $t < 239) {
                $tn = 3;
                $n += 3;
                $noc += 2;
            } else if (240 <= $t && $t <= 247) {
                $tn = 4;
                $n += 4;
                $noc += 2;
            } else if (248 <= $t && $t <= 251) {
                $tn = 5;
                $n += 5;
                $noc += 2;
            } else if ($t == 252 || $t == 253) {
                $tn = 6;
                $n += 6;
                $noc += 2;
            } else {
                $n++;
            }
            if ($noc >= $length)
                break;
        }
        if ($noc > $length)
            $n -= $tn;
        $strcut = substr($string, 0, $n);
    } else {
        for ($i = 0; $i < $length - strlen($dot) - 1; $i++)
            $strcut .= ord($string[$i]) > 127 ? $string[$i] . $string[++$i] : $string[$i];
    }
    $strcut = str_replace(array('&', '"', '<', '>'), array('&', '"', '&lt;', '&gt;'), $strcut);
    return $strcut . $dot;
}

/**
 * 异常处理
 * @param type $e
 */
function exception_error($e) {
    echo $e->getMessage();
}
