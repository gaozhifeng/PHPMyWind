<?php	if(!defined('IN_PHPMYWIND')) exit('Request Error!');

/*
**************************
(C)2010-2015 phpMyWind.com
update: 2012-10-22 16:08:34
person: Adu
**************************
*/


/**
 * 基础controller，改动自UCenter base类
 * 本文件的参考过以下程序，在此一并致谢！
 * - Comsenz UCenter {@link http://www.comsenz.com}
 */

class Controller_Base{

    public $input = array();

    public $config;

    /**
     * 构造函数，初始化参数(ok)
     *
     */
    public function __construct(){
        $this->config = common::getInstanceOf('config');
    }

    /**
     * 初始化输入（ok）
     *
     * @param string $getagent 指定的agent
     */
    public function init_input($getagent = '') {
        $input = common::getgpc('input', 'R');
        if($input) {
            $input = common::authcode($input, 'DECODE', $this->config->authkey);
            parse_str($input, $this->input);
            $this->input = common::addslashes($this->input, 1, TRUE);
            $agent = $getagent ? $getagent : $this->input['agent'];

            if(($getagent && $getagent != $this->input['agent']) || (!$getagent && md5($_SERVER['HTTP_USER_AGENT']) != $agent)) {
                exit('Access denied for agent changed');
            } elseif(time() - $this->input('time') > 3600) {
                exit('Authorization has expired');
            }
        }
        if(empty($this->input)) {
            exit('Invalid input');
        }
    }

    /**
     * 查找$this->input是否存在指定索引的变量？（ok）
     *
     * @param string $k 要查找的索引
     * @return mixed
     */
	public function input($k) {
		return isset($this->input[$k]) ? (is_array($this->input[$k]) ? $this->input[$k] : trim($this->input[$k])) : NULL;
	}

}
