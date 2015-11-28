<?php

class Renren extends RenrenOAuth {

    function __construct($access_token = NULL, $refresh_token = NULL) {
        parent::__construct($access_token, $refresh_token);
    }

    /**
     * 获取是否登录
     * @return boolean
     */
    function verify() {
        if (isset($_SESSION['renren_token']) && $_SESSION['renren_token'] && isset($_SESSION['renren_token']['user']['id']) && !isset($_SESSION['renren_token']['error'])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 获取用户信息
     * @param int $uids
     * @return array
     */
    function get_user_info($uids = 0) {
        $params = array(
            'fields' => array('uid', 'name', 'sex', 'star', 'birthday', 'email_hash', 'tinyurl', 'headurl', 'tinyurl_with_logo', 'headurl_with_logo', 'mainurl', 'university_history', 'hs_history', 'hometown_location'),
            'call_id' => time(),
            'access_token' => $this->get_access_token()
        );
        if ($uids)
            $params['uids'] = $uids;
        $user_info = $this->call_api('users.getInfo', $params);
        return $user_info;
    }

    /**
     * 生成签名SESSION_KEY加密字符
     * @param string $params
     * @return string
     */
    function generate_sig($params) {
        ksort($params);
        $sig = '';
        foreach ($params as $key => $value) {
            $sig .= "{$key}={$value}";
        }
        $sig .= $this->client_secret;
        return md5($sig);
    }

    /**
     * 获取当前人人网用户的SESSION
     * @return array
     */
    function get_session() {
        if (!isset($_COOKIE[$this->client_id . '_user']))
            return '';
        $connect_session = array(
            "user" => $_COOKIE[$this->client_id . '_user'],
            "access_token" => $_COOKIE[$this->client_id . '_access_token'],
            "expires" => $_COOKIE[$this->client_id . '_expires'],
        );
        return $connect_session;
    }

    /**
     * 获取当前用户的ID
     * @return int
     */
    function get_id() {
        return isset($_COOKIE[$this->client_id . '_user']) ? $_COOKIE[$this->client_id . '_user'] : '';
    }

    /**
     * 获取SESSION_Key
     * @return string
     */
    function get_access_token() {
        return isset($_COOKIE[$this->client_id . '_access_token']) ? $_COOKIE[$this->client_id . '_access_token'] : '';
    }

    /**
     * 请求人人网服务器，并获得数据<br />
     * @param string $method 请求的方法
     * @param array $params 可选的参数
     * @return array
     * method 方法:<br />
     * connect.getUnconnectedFriendsCount 此方法返回当前用户在此站点上，但还没有建立connect关系的好友数量<br />
     * connect.registerUsers 用来建立站点用户和校内用户之间的映射关系<br />
     * connect.unregisterUsers 删除站点用户和校内用户之间的映射关系<br />
     * friends.areFriends 判断两组用户是否互为好友关系，比较的两组用户数必须相等。<br />
     * friends.get 得到当前登录用户的好友列表，得到的只是含有好友id的列表。<br />
     * friends.getFriends 得到当前登录用户的好友列表。<br />
     * friends.getAppFriends 查询当前用户安装某个应用的好友列表。此接口在新的0.7版本以后提供使用中<br />
     * invitations.createLink 创建站外邀请的链接地址<br />
     * invitations.getInfo 根据邀请的新用户id得到此次邀请的详细信息（邀请人、邀请时间、被邀请人）<br />
     * notifications.send 给指定的用户发送通知<br />
     * notifications.sendEmail 在取得用户的授权后，给用户发送Email。<br />
     * pages.isFan 判断用户是否为Page（公共主页）的粉丝<br />
     * users.getInfo 得到用户信息,此接口在新的0.5版本以后中增加返回是否为星级和紫豆用户节点<br />
     * users.getLoggedInUser 得到当前session的用户ID<br />
     * users.hasAppPermission 根据用户的id，以及相应在人人网的操作权限(接收email,更新状态等),来判断用户是否可以进行此操作,此接口在新的0.8版本以后提供使用<br />
     * users.isAppUser 判断用户是否已对App授权
     */
    function call_api($method, $params = array()) {
        $post_body = $this->post_body($method, $params);
        //echo $post_body,"\n\n";
        if (function_exists('curl_init')) {
            $request = curl_init();
            curl_setopt($request, CURLOPT_URL, $this->host);
            curl_setopt($request, CURLOPT_POST, 1);
            curl_setopt($request, CURLOPT_POSTFIELDS, $post_body);
            curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($request);
            curl_close($request);
        } else {
            $context = array('http' =>
                array('method' => 'POST',
                    'header' => 'Content-type: application/x-www-form-urlencoded' . "\r\n" .
                    'User-Agent: Renren API PHP5 Client 1.1 ' . "\r\n" .
                    'Content-length: ' . strlen($post_body),
                    'content' => $post_body));
            $contextid = stream_context_create($context);
            $sock = fopen($this->host, 'r', false, $contextid);
            if ($sock) {
                $result = '';
                while (!feof($sock))
                    $result.=fgets($sock, 4096);
                fclose($sock);
            }
        }
        $result = obj2array(json_decode($result));
        return $result;
    }

    /**
     * 发送feed信息
     * @param string $content 内容,不超过140汉字
     * @param string $image 图片地址,可以为空
     * @param string $url 相关链接,不能为空
     * @return bool 
     */
    function upload($content, $image = '', $url = 'http://blog.iplaybus.com') {
        $p = array('call_id' => time(), 'access_token' => $this->get_access_token(), 'name' => cutstr($content, 30, ''), 'description' => $content, 'url' => $url, 'image' => $image);
        return $this->call_api('feed.publishFeed', $p);
    }

    /**
     * 发送的参数主体
     * @param string $method
     * @param array $params
     * @return string
     */
    function post_body($method, $params) {
        //默认的四个参数，其它参数需要手动传过来
        $params['method'] = $method;
        $params['client_id'] = $this->client_id;
        $params['format'] = 'JSON';
        $params['v'] = '1.0';
        $post_params = array();
        foreach ($params as $key => &$val) {
            if (is_array($val))
                $val = implode(',', $val);
            $post_params[] = $key . '=' . ($val);
        }
        $post_params[] = 'sig=' . $this->generate_sig($params);
        return implode('&', $post_params);
    }

}

class RenrenOAuth {

    public $client_id;
    public $client_secret;
    public $access_token;
    public $refresh_token;
    public $http_code;
    public $url;
    public $host = "http://api.xiaonei.com/restserver.do";
    public $timeout = 30;
    public $connecttimeout = 30;
    public $ssl_verifypeer = FALSE;
    public $format = 'json';
    public $decode_json = TRUE;
    public $http_info;
    public $useragent = 'Renren OAuth2.0';
    public $debug = FALSE;
    public static $boundary = '';

    function accessTokenURL() {
        return 'https://graph.renren.com/oauth/token';
    }

    function authorizeURL() {
        return 'https://graph.renren.com/oauth/authorize';
    }

    function __construct($access_token = NULL, $refresh_token = NULL) {
        $this->client_id = RR_AKEY;
        $this->client_secret = RR_SKEY;
        $this->access_token = $access_token;
        $this->refresh_token = $refresh_token;
    }

    /**
     * authorize接口
     *
     * 对应API：{@link http://open.weibo.com/wiki/Oauth2/authorize Oauth2/authorize}
     *
     * @param string $url 授权后的回调地址,站外应用需与回调地址一致,站内应用需要填写canvas page的地址
     * @param string $response_type 支持的值包括 code 和token 默认值为code
     * @param string $state 用于保持请求和回调的状态。在回调时,会在Query Parameter中回传该参数
     * @param string $display 授权页面类型 可选范围:
     *  - default		默认授权页面
     *  - mobile		支持html5的手机
     *  - wap1.2		wap1.2页面
     *  - wap2.0		wap2.0页面
     *  - apponweibo	站内应用专用,站内应用不传display参数,并且response_type为token时,默认使用改display.授权后不会返回access_token，只是输出js刷新站内应用父框架
     * @param bool $forcelogin 是否强制用户重新登录，true：是，false：否。默认false。
     * @param string $language 授权页语言，缺省为中文简体版，en为英文版。英文版测试中，开发者任何意见可反馈至 @微博API
     * @return array
     */
    function getAuthorizeURL($url, $response_type = 'code', $state = NULL, $scope = NULL) {
        $params = array();
        $params['client_id'] = $this->client_id;
        $params['redirect_uri'] = $url;
        $params['response_type'] = $response_type;
        $params['state'] = $state;
        $params['scope'] = $scope;
        return $this->authorizeURL() . "?" . http_build_query($params);
    }

    /**
     * access_token接口
     *
     * 对应API：{@link http://open.weibo.com/wiki/OAuth2/access_token OAuth2/access_token}
     *
     * @param string $type 请求的类型,可以为:code, password, token
     * @param array $keys 其他参数：
     *  - 当$type为code时： array('code'=>..., 'redirect_uri'=>...)
     *  - 当$type为password时： array('username'=>..., 'password'=>...)
     *  - 当$type为token时： array('refresh_token'=>...)
     * @return array
     */
    function getAccessToken($type = 'code', $keys = array()) {
        $params = array();
        $params['client_id'] = $this->client_id;
        $params['client_secret'] = $this->client_secret;
        if ($type === 'token') {
            $params['grant_type'] = 'refresh_token';
            $params['refresh_token'] = $keys['refresh_token'];
        } elseif ($type === 'code') {
            $params['grant_type'] = 'authorization_code';
            $params['code'] = $keys['code'];
            $params['redirect_uri'] = $keys['redirect_uri'];
        } elseif ($type === 'password') {
            $params['grant_type'] = 'password';
            $params['username'] = $keys['username'];
            $params['password'] = $keys['password'];
        } else {
            throw new OAuthException("wrong auth type");
        }
        $response = $this->oAuthRequest($this->accessTokenURL(), 'POST', $params);
        $token = json_decode($response, true);
        if (is_array($token) && !isset($token['error'])) {
            $this->access_token = $token['access_token'];
            $this->refresh_token = $token['refresh_token'];
        } else {
            throw new OAuthException("get access token failed." . $token['error']);
        }
        return $token;
    }

    /**
     * 解析 signed_request
     *
     * @param string $signed_request 应用框架在加载iframe时会通过向Canvas URL post的参数signed_request
     *
     * @return array
     */
    function parseSignedRequest($signed_request) {
        list($encoded_sig, $payload) = explode('.', $signed_request, 2);
        $sig = self::base64decode($encoded_sig);
        $data = json_decode(self::base64decode($payload), true);
        if (strtoupper($data['algorithm']) !== 'HMAC-SHA256')
            return '-1';
        $expected_sig = hash_hmac('sha256', $payload, $this->client_secret, true);
        return ($sig !== $expected_sig) ? '-2' : $data;
    }

    /**
     * @ignore
     */
    function base64decode($str) {
        return base64_decode(strtr($str . str_repeat('=', (4 - strlen($str) % 4)), '-_', '+/'));
    }

    /**
     * 读取jssdk授权信息，用于和jssdk的同步登录
     *
     * @return array 成功返回array('access_token'=>'value', 'refresh_token'=>'value'); 失败返回false
     */
    function getTokenFromJSSDK() {
        $key = "weibojs_" . $this->client_id;
        if (isset($_COOKIE[$key]) && $cookie = $_COOKIE[$key]) {
            parse_str($cookie, $token);
            if (isset($token['access_token']) && isset($token['refresh_token'])) {
                $this->access_token = $token['access_token'];
                $this->refresh_token = $token['refresh_token'];
                return $token;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * 从数组中读取access_token和refresh_token
     * 常用于从Session或Cookie中读取token，或通过Session/Cookie中是否存有token判断登录状态。
     *
     * @param array $arr 存有access_token和secret_token的数组
     * @return array 成功返回array('access_token'=>'value', 'refresh_token'=>'value'); 失败返回false
     */
    function getTokenFromArray($arr) {
        if (isset($arr['access_token']) && $arr['access_token']) {
            $token = array();
            $this->access_token = $token['access_token'] = $arr['access_token'];
            if (isset($arr['refresh_token']) && $arr['refresh_token']) {
                $this->refresh_token = $token['refresh_token'] = $arr['refresh_token'];
            }
            return $token;
        } else {
            return false;
        }
    }

    /**
     * GET wrappwer for oAuthRequest.
     *
     * @return mixed
     */
    function get($url, $parameters = array()) {
        $response = $this->oAuthRequest($url, 'GET', $parameters);
        if ($this->format === 'json' && $this->decode_json) {
            return json_decode($response, true);
        }
        return $response;
    }

    /**
     * POST wreapper for oAuthRequest.
     *
     * @return mixed
     */
    function post($url, $parameters = array(), $multi = false) {
        $response = $this->oAuthRequest($url, 'POST', $parameters, $multi);
        if ($this->format === 'json' && $this->decode_json) {
            return json_decode($response, true);
        }
        return $response;
    }

    /**
     * DELTE wrapper for oAuthReqeust.
     *
     * @return mixed
     */
    function delete($url, $parameters = array()) {
        $response = $this->oAuthRequest($url, 'DELETE', $parameters);
        if ($this->format === 'json' && $this->decode_json) {
            return json_decode($response, true);
        }
        return $response;
    }

    /**
     * Format and sign an OAuth / API request
     *
     * @return string
     * @ignore
     */
    function oAuthRequest($url, $method, $parameters, $multi = false) {
        if (strrpos($url, 'http://') !== 0 && strrpos($url, 'https://') !== 0) {
            $url = "{$this->host}{$url}.{$this->format}";
        }
        switch ($method) {
            case 'GET':
                $url = $url . '?' . http_build_query($parameters);
                return $this->http($url, 'GET');
            default:
                $headers = array();
                if (!$multi && (is_array($parameters) || is_object($parameters))) {
                    $body = http_build_query($parameters);
                } else {
                    $body = self::build_http_query_multi($parameters);
                    $headers[] = "Content-Type: multipart/form-data; boundary=" . self::$boundary;
                }
                return $this->http($url, $method, $body, $headers);
        }
    }

    /**
     * Make an HTTP request
     *
     * @return string API results
     * @ignore
     */
    function http($url, $method, $postfields = NULL, $headers = array()) {
        $this->http_info = array();
        $ci = curl_init();
        /* Curl settings */
        curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($ci, CURLOPT_USERAGENT, $this->useragent);
        curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, $this->connecttimeout);
        curl_setopt($ci, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ci, CURLOPT_ENCODING, "");
        curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, $this->ssl_verifypeer);
        curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, 1);
        curl_setopt($ci, CURLOPT_HEADERFUNCTION, array($this, 'getHeader'));
        curl_setopt($ci, CURLOPT_HEADER, FALSE);
        switch ($method) {
            case 'POST':
                curl_setopt($ci, CURLOPT_POST, TRUE);
                if (!empty($postfields)) {
                    curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
                    $this->postdata = $postfields;
                }
                break;
            case 'DELETE':
                curl_setopt($ci, CURLOPT_CUSTOMREQUEST, 'DELETE');
                if (!empty($postfields)) {
                    $url = "{$url}?{$postfields}";
                }
        }
        if (isset($this->access_token) && $this->access_token)
            $headers[] = "Authorization: OAuth2 " . $this->access_token;
        if (!empty($this->remote_ip)) {
            if (defined('SAE_ACCESSKEY')) {
                $headers[] = "SaeRemoteIP: " . $this->remote_ip;
            } else {
                $headers[] = "API-RemoteIP: " . $this->remote_ip;
            }
        } else {
            if (!defined('SAE_ACCESSKEY')) {
                $headers[] = "API-RemoteIP: " . $_SERVER['REMOTE_ADDR'];
            }
        }
        curl_setopt($ci, CURLOPT_URL, $url);
        curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ci, CURLINFO_HEADER_OUT, TRUE);
        $response = curl_exec($ci);
        $this->http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
        $this->http_info = array_merge($this->http_info, curl_getinfo($ci));
        $this->url = $url;
        if ($this->debug) {
            echo "=====post data======\r\n";
            var_dump($postfields);
            echo "=====headers======\r\n";
            print_r($headers);
            echo '=====request info=====' . "\r\n";
            print_r(curl_getinfo($ci));
            echo '=====response=====' . "\r\n";
            print_r($response);
        }
        curl_close($ci);
        return $response;
    }

    /**
     * 开启调试信息
     *
     * 开启调试信息后，SDK会将每次请求微博API所发送的POST Data、Headers以及请求信息、返回内容输出出来。
     *
     * @access public
     * @param bool $enable 是否开启调试信息
     * @return void
     */
    function set_debug($enable) {
        $this->debug = $enable;
    }

    /**
     * Get the header info to store.
     *
     * @return int
     * @ignore
     */
    function getHeader($ch, $header) {
        $i = strpos($header, ':');
        if (!empty($i)) {
            $key = str_replace('-', '_', strtolower(substr($header, 0, $i)));
            $value = trim(substr($header, $i + 2));
            $this->http_header[$key] = $value;
        }
        return strlen($header);
    }

    /**
     * @ignore
     */
    public static function build_http_query_multi($params) {
        if (!$params)
            return '';
        uksort($params, 'strcmp');
        $pairs = array();
        self::$boundary = $boundary = uniqid('------------------');
        $MPboundary = '--' . $boundary;
        $endMPboundary = $MPboundary . '--';
        $multipartbody = '';
        foreach ($params as $parameter => $value) {
            if (in_array($parameter, array('pic', 'image')) && $value{0} == '@') {
                $url = ltrim($value, '@');
                $content = file_get_contents($url);
                $array = explode('?', basename($url));
                $filename = $array[0];
                $multipartbody .= $MPboundary . "\r\n";
                $multipartbody .= 'Content-Disposition: form-data; name="' . $parameter . '"; filename="' . $filename . '"' . "\r\n";
                $multipartbody .= "Content-Type: image/unknown\r\n\r\n";
                $multipartbody .= $content . "\r\n";
            } else {
                $multipartbody .= $MPboundary . "\r\n";
                $multipartbody .= 'content-disposition: form-data; name="' . $parameter . "\"\r\n\r\n";
                $multipartbody .= $value . "\r\n";
            }
        }
        $multipartbody .= $endMPboundary;
        return $multipartbody;
    }

}