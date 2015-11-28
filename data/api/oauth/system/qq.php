<?php	require_once('qqclient.php');

/*
**************************
(C)2010-2015 phpMyWind.com
update: 2014-5-5 11:12:38
person: Feng/Karson
**************************
*/


class QQ extends QQOAuth {

    function __construct($access_token = NULL, $refresh_token = NULL) {
        parent::__construct($access_token, $refresh_token);
    }

    function verify() {
        if (isset($_SESSION['qq_token']) && $_SESSION['qq_token'] && isset($_SESSION['qq_token']['uid'])) {
            return true;
        } else {
            return false;
        }
    }

}

class QQOAuth {

    public $client_id;
    public $client_secret;
    public $access_token;
    public $refresh_token;
    public $http_code;
    public $url;
    public $host = "https://graph.qq.com/";
    public $timeout = 30;
    public $connecttimeout = 30;
    public $ssl_verifypeer = FALSE;
    public $format = 'json';
    public $decode_json = TRUE;
    public $http_info;
    public $useragent = 'QQ OAuth2.0';
    public $debug = FALSE;
    public static $boundary = '';

    function accessTokenURL() {
        return 'https://graph.qq.com/oauth2.0/token';
    }

    function authorizeURL() {
        return 'https://graph.qq.com/oauth2.0/authorize';
    }

    function openidURL() {
        return "https://graph.qq.com/oauth2.0/me";
    }

    function __construct($access_token = NULL, $refresh_token = NULL) {
        $this->client_id = QQ_AKEY;
        $this->client_secret = QQ_SKEY;
        $this->access_token = $access_token;
        $this->refresh_token = $refresh_token;
    }

    function get_openid() {
        $params = array(
            "access_token" => $this->recorder->read("access_token")
        );
        $graph_url = $this->combine_url(self::GET_OPENID_URL, $params);
        $response = $this->http($graph_url);
        //--------检测错误是否发生
        if (strpos($response, "callback") !== false) {
            $lpos = strpos($response, "(");
            $rpos = strrpos($response, ")");
            $response = substr($response, $lpos + 1, $rpos - $lpos - 1);
        }
        $user = json_decode($response);
        if (isset($user->error)) {
            $this->show_error($user->error, $user->error_description);
        }
        //------记录openid
        $_SESSION["openid"] = $user->openid;
        return $user->openid;
    }

    function getAuthorizeURL($url, $response_type = 'code', $state = NULL, $scope = NULL) {
        $params = array();
        $params['client_id'] = $this->client_id;
        $params['redirect_uri'] = $url;
        $params['response_type'] = $response_type;
        $params['state'] = $state;
        $params['scope'] = $scope;
        return $this->authorizeURL() . "?" . http_build_query($params);
    }

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
            throw new Exception("wrong auth type");
        }
        $response = $this->oAuthRequest($this->accessTokenURL(), 'POST', $params);
        $response = $this->filterResponse($response);
        parse_str($response, $token);
        if (is_array($token)) {
            $this->access_token = $token['access_token'];
            $this->refresh_token = isset($token['refresh_token']) ? $token['refresh_token'] : '';
        } else {
            throw new Exception("读取access_token错误:{$token['error']}");
        }
        return $token;
    }

    function getOpenID() {
        $params = array();
        $params['access_token'] = $this->access_token;
        $response = $this->oAuthRequest($this->openidURL(), 'POST', $params);
        $response = $this->filterResponse($response);
        $result = json_decode($response, true);
        if (!is_array($result)) {
            throw new Exception("读取OPENID错误");
        }
        return $result['openid'];
    }

    function filterResponse($response) {
        if (strpos($response, "callback") !== false) {
            $lpos = strpos($response, "(");
            $rpos = strrpos($response, ")");
            $response = substr($response, $lpos + 1, $rpos - $lpos - 1);
            $msg = json_decode($response);
            if (isset($msg->error)) {
                throw new Exception("发生一个已知的错误:{$msg->error}");
            }
        }
        return $response;
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
