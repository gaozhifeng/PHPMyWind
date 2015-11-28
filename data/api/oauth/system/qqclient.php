<?php

/*
**************************
(C)2010-2015 phpMyWind.com
update: 2014-5-5 11:12:57
person: Feng/Karson
**************************
*/


class QQclient {

    private $APIMap;

    function __construct($access_token, $refresh_token = NULL) {
        $this->oauth = new QQ($access_token, $refresh_token);
        /*
         * 初始化APIMap
         * 加#表示非必须，无则不传入url(url中不会出现该参数)， "key" => "val" 表示key如果没有定义则使用默认值val
         * 规则 array( baseUrl, argListArr, method)
         */
        $this->APIMap = array(
            /*                       qzone                    */
            "add_blog" => array(
                "https://graph.qq.com/blog/add_one_blog",
                array("title", "format" => "json", "content" => null),
                "POST"
            ),
            "add_topic" => array(
                "https://graph.qq.com/shuoshuo/add_topic",
                array("richtype", "richval", "con", "#lbs_nm", "#lbs_x", "#lbs_y", "format" => "json", "#third_source"),
                "POST"
            ),
            "get_user_info" => array(
                "https://graph.qq.com/user/get_user_info",
                array("format" => "json"),
                "GET"
            ),
            "add_one_blog" => array(
                "https://graph.qq.com/blog/add_one_blog",
                array("title", "content", "format" => "json"),
                "GET"
            ),
            "add_album" => array(
                "https://graph.qq.com/photo/add_album",
                array("albumname", "#albumdesc", "#priv", "format" => "json"),
                "POST"
            ),
            "upload_pic" => array(
                "https://graph.qq.com/photo/upload_pic",
                array("picture", "#photodesc", "#title", "#albumid", "#mobile", "#x", "#y", "#needfeed", "#successnum", "#picnum", "format" => "json"),
                "POST"
            ),
            "list_album" => array(
                "https://graph.qq.com/photo/list_album",
                array("format" => "json")
            ),
            "add_share" => array(
                "https://graph.qq.com/share/add_share",
                array("title", "url", "#comment", "#summary", "#images", "format" => "json", "#type", "#playurl", "#nswb", "site", "fromurl"),
                "POST"
            ),
            "check_page_fans" => array(
                "https://graph.qq.com/user/check_page_fans",
                array("page_id" => "314416946", "format" => "json")
            ),
            /*                    wblog                             */
            "add_t" => array(
                "https://graph.qq.com/t/add_t",
                array("format" => "json", "content", "#clientip", "#longitude", "#compatibleflag"),
                "POST"
            ),
            "add_pic_t" => array(
                "https://graph.qq.com/t/add_pic_t",
                array("content", "pic", "format" => "json", "#clientip", "#longitude", "#latitude", "#syncflag", "#compatiblefalg"),
                "POST"
            ),
            "del_t" => array(
                "https://graph.qq.com/t/del_t",
                array("id", "format" => "json"),
                "POST"
            ),
            "get_repost_list" => array(
                "https://graph.qq.com/t/get_repost_list",
                array("flag", "rootid", "pageflag", "pagetime", "reqnum", "twitterid", "format" => "json")
            ),
            "get_info" => array(
                "https://graph.qq.com/user/get_info",
                array("format" => "json")
            ),
            "get_other_info" => array(
                "https://graph.qq.com/user/get_other_info",
                array("format" => "json", "#name", "fopenid")
            ),
            "get_fanslist" => array(
                "https://graph.qq.com/relation/get_fanslist",
                array("format" => "json", "reqnum", "startindex", "#mode", "#install", "#sex")
            ),
            "get_idollist" => array(
                "https://graph.qq.com/relation/get_idollist",
                array("format" => "json", "reqnum", "startindex", "#mode", "#install")
            ),
            "add_idol" => array(
                "https://graph.qq.com/relation/add_idol",
                array("format" => "json", "#name-1", "#fopenids-1"),
                "POST"
            ),
            "del_idol" => array(
                "https://graph.qq.com/relation/del_idol",
                array("format" => "json", "#name-1", "#fopenid-1"),
                "POST"
            ),
            /*                           pay                          */
            "get_tenpay_addr" => array(
                "https://graph.qq.com/cft_info/get_tenpay_addr",
                array("ver" => 1, "limit" => 5, "offset" => 0, "format" => "json")
            )
        );
    }

    //调用相应api
    private function _applyAPI($arr, $argsList, $baseUrl, $method) {
        $pre = "#";
        $keysArr = array(
            "oauth_consumer_key" => QQ_AKEY,
            "access_token" => $_SESSION['qq_token']["access_token"],
            "openid" => $_SESSION['qq_token']["openid"]
        );
        $optionArgList = array(); //一些多项选填参数必选一的情形
        foreach ($argsList as $key => $val) {
            $tmpKey = $key;
            $tmpVal = $val;
            if (!is_string($key)) {
                $tmpKey = $val;
                if (strpos($val, $pre) === 0) {
                    $tmpVal = $pre;
                    $tmpKey = substr($tmpKey, 1);
                    if (preg_match("/-(\d$)/", $tmpKey, $res)) {
                        $tmpKey = str_replace($res[0], "", $tmpKey);
                        $optionArgList[$res[1]][] = $tmpKey;
                    }
                } else {
                    $tmpVal = null;
                }
            }
            //-----如果没有设置相应的参数
            if (!isset($arr[$tmpKey]) || $arr[$tmpKey] === "") {
                if ($tmpVal == $pre) {//则使用默认的值
                    continue;
                } else if ($tmpVal) {
                    $arr[$tmpKey] = $tmpVal;
                } else {
                    if ($v = $_FILES[$tmpKey]) {
                        $filename = dirname($v['tmp_name']) . "/" . $v['name'];
                        move_uploaded_file($v['tmp_name'], $filename);
                        $arr[$tmpKey] = "@$filename";
                    } else {
                        throw new Exception("api调用参数错误,未传入参数$tmpKey");
                    }
                }
            }
            $keysArr[$tmpKey] = $arr[$tmpKey];
        }
        //检查选填参数必填一的情形
        foreach ($optionArgList as $val) {
            $n = 0;
            foreach ($val as $v) {
                if (in_array($v, array_keys($keysArr))) {
                    $n++;
                }
            }
            if (!$n) {
                $str = implode(",", $val);
                throw new Exception("api调用参数错误", $str . "必填一个");
            }
        }
        if ($method == "POST") {
            if ($baseUrl == "https://graph.qq.com/blog/add_one_blog") {
                $this->oauth->ssl_verifypeer = 1;
            } else {
                $this->oauth->ssl_verifypeer = 0;
            }
            $response = $this->oauth->post($baseUrl, $keysArr);
        } else if ($method == "GET") {
            $response = $this->oauth->get($baseUrl, $keysArr);
        }
        return $response;
    }

    public function __call($name, $arg) {
        //如果APIMap不存在相应的api
        if (empty($this->APIMap[$name])) {
            throw new Exception("不存在的API: <span style='color:red;'>$name</span>");
        }
        //从APIMap获取api相应参数
        $baseUrl = $this->APIMap[$name][0];
        $argsList = $this->APIMap[$name][1];
        $method = isset($this->APIMap[$name][2]) ? $this->APIMap[$name][2] : "GET";
        if (empty($arg)) {
            $arg[0] = null;
        }
        //对于get_tenpay_addr，特殊处理，php json_decode对\xA312此类字符支持不好
        if ($name == "get_tenpay_addr") {
            $this->oauth->decode_json = false;
            $responseArr = $this->simple_json_parser($this->_applyAPI($arg[0], $argsList, $baseUrl, $method));
        } else {
            $responseArr = obj2array($this->_applyAPI($arg[0], $argsList, $baseUrl, $method));
        }
        //检查返回ret判断api是否成功调用
        if ($responseArr['ret'] == 0) {
            return $responseArr;
        } else {
            throw new Exception($responseArr['msg']);
        }
    }

    //php 对象到数组转换
    private function objToArr($obj) {
        if (!is_object($obj) && !is_array($obj)) {
            return $obj;
        }
        $arr = array();
        foreach ($obj as $k => $v) {
            $arr[$k] = $this->objToArr($v);
        }
        return $arr;
    }

    public function get_access_token() {
        return $_SESSION['qq_token']["access_token"];
    }

    //简单实现json到php数组转换功能
    private function simple_json_parser($json) {
        $json = str_replace("{", "", str_replace("}", "", $json));
        $jsonValue = explode(",", $json);
        $arr = array();
        foreach ($jsonValue as $v) {
            $jValue = explode(":", $v);
            $arr[str_replace('"', "", $jValue[0])] = (str_replace('"', "", $jValue[1]));
        }
        return $arr;
    }

}
