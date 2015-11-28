<?php	if(!defined('IN_PHPMYWIND')) exit('Request Error!');

/*
**************************
(C)2010-2015 phpMyWind.com
update: 2012-10-22 16:08:34
person: Adu
**************************
*/


/**
 * 参数类
 * 本文件参考过以下程序，在此一并致谢！
 *     - PHP框架LotusPHP{@link http://code.google.com/p/lotusphp/}
 */

class config extends ArrayObject{

    /**
     * 构建函数
     *
     */
    public function __construct(){
    }

    /**
     * 对参数进行设置(ok)
     *
     * @param array $newConfig 新的参数数组
     */
    public function set( $newConfig = array() ){
        foreach ($newConfig as $key => $value){
            $this->$key = $value;
        }
    }

    public function __get($name){
        $this->$name = null;
        return null;
    }
}

