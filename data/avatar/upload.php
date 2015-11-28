<?php	require_once(dirname(__FILE__).'/../../include/common.inc.php');

/*
**************************
(C)2010-2015 phpMyWind.com
update: 2012-10-22 16:08:34
person: Adu
**************************
*/


/**
 * 程序入口文件。。
 * 参考过以下程序，在此一并致谢！
 * - Comsenz UCenter {@link http://www.comsenz.com}
 */


//设置区
$config = array(
    'tmpdir' => 'temp',           							        //临时文件夹（相对于本文件的位置而言）。开头和结尾请不要加反斜杆
    'avatardir' => '',        							    		//存储头像的文件夹（相对于本文件的位置而言），开头和结尾请不要加反斜杆
    'authkey' => $cfg_auth_key,         							//通讯密钥，必须填写，否则脚本无法运行！
    'debug' => true,    											//开启debug记录？
    'uploadsize' => 1024,   										//上传图片文件的最大值，单位是KB
    'uc_api' => '',          										//运行该脚本的网址，末尾请不要加反斜杠（比如http://http://localhost/upload_avatar）。详情请看说明
    'imgtype' => array(1 => '.gif', 2 => '.jpg', 3 => '.png'),      //允许上传的类型，请勿修改此处设置，否则会引起安全隐患问题！
);

//脚本运行区，定义运行开始
define('SYSTEM_PATH', dirname(__FILE__). '/lib');

//错误调试区
if( true === $config['debug'] ){
    set_exception_handler(array('Inter_Error', 'exception_handler'));
    set_error_handler(array('Inter_Error', 'error_handler'), E_ALL);
    Inter_Error::$conf['debugMode'] = false;
    Inter_Error::$conf['logType'] = 'simple';
    Inter_Error::$conf['logDir'] = dirname(__FILE__). '/Log';
    //Inter_Error::$conf['logDir'] = 'R:\TEMP';
}else{
    error_reporting(0);
}

//获取动作名称
if( !isset($_GET['a']) || empty($_GET['a']) || !is_string($_GET['a']) ){
    $action = 'showuploadAction';
}else{
    $action = $_GET['a']. 'Action';
}

//因为这个只有一个控制器，所以直接实例化了
$controller = new Controller_AvatarFlashUpload();
$controller->config->set($config);
//如果没有设置则自动生成运行该脚本的网址（不含脚本名称）
if( empty($controller->config->uc_api) ){
    $controller->config->uc_api = ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https' : 'http' ).
                                  '://'.
                                  /* $_SERVER['HTTP_HOST'].  */
                                  ( isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '') ).
                                  substr( $_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], '/') );
}

//运行控制器指定的动作

if(method_exists($controller, $action)){
    /*
    if(method_exists($controller, $action.'Before')){
        $controller->$action.'Before';
    }
    */
    $result = $controller->$action();
    /*
    if(method_exists($controller, $action.'After')){
        $controller->$action.'After';
    }
    */
    if(is_array($result)){
        echo json_encode($result);
    }else{
        echo $result;
    }
}else{
    exit('NO ACTION FOUND!');
}


/**
 * php 5.0新增的自动加载模式
 * 假如php版本高于5.0，并且在大于等于5.1.2的情况下没有开启spl_autoload_register，则使用此__autoload方法执行之。
 * 本函数依赖于：
 *   - 常量SYSTEM_PATH：框架组件目录
 * @param string $classname 遵循本框架命名规则的class名称
 */
function __autoload($classname){
    $path = SYSTEM_PATH. DIRECTORY_SEPARATOR. str_replace('_', DIRECTORY_SEPARATOR, $classname). '.php';
    require_once($path);
}
?>
