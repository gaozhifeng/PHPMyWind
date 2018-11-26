<?php	require_once(dirname(__FILE__).'/../../inc/config.inc.php');

/*
**************************
(C)2010-2018 phpMyWind.com
update: 2018-10-21 10:50:00
person: 静望黄昏
        ckeditor编辑器上传文件
**************************
*/
$iswatermark = isset($iswatermark) ? $iswatermark : '';
$timestamp   = isset($timestamp)   ? $timestamp   : '';
$token   = isset($token) ? $token   : '';
$verifyToken = md5('unique_salt'.$timestamp);

 //error_log(json_encode($_FILES), 3, 'errors.log');
	//引入上传类
	require_once(PHPMYWIND_DATA.'/httpfile/upload.class.php');

//有上传文件时
if(!empty($_FILES) && $token==$verifyToken)
{
	if(isset($type)){ //区别编辑器批量上传
		$upload_info = UploadFile('file', $iswatermark);
	/* 返回上传状态，是数组则表示上传成功
	   非数组则是直接返回发生的问题 */
		if(!is_array($upload_info)){
			$return=array(
			 "error" => $upload_info         
			);
			}else{
			 //得到上传文件所对应的各个参数,数组结构
			$return = array(
				 'error' => 'success', //上传状态，上传成功时必须返回"success"
				 "original" => $upload_info[0],    //原始文件名
				 "size" => $upload_info[1],        //文件大小
				 "url" => $upload_info[2], 		   //返回的地址
				// "type" => $upload_info[4],       //文件类型
				// "title" => $upload_info[5]        //新文件名
				);
		}
	}else{
			$upload_info = UploadFile('upload', $iswatermark);
			/* 返回上传状态，是数组则表示上传成功
			   非数组则是直接返回发生的问题 */
			if(!is_array($upload_info)){
				$return = array('uploaded'=>'0','error'=>array('message'=>$upload_info));
			}else{
					$return = array('uploaded'=>'1',
					'fileName'=>$upload_info[0],
					'url'=> $upload_info[2],
					//'error'=>array('message'=>'success')
				);
			}
	}
	//判断是否有子目录
	if(!preg_match('/\/$/', $cfg_webpath)){ //最后一个字符不是/
		$cfg_webpath = $cfg_webpath.'/'; 
	}
	if(empty($frame)){
		$return['url'] = $cfg_webpath.$return['url'];
	}
	
	//error_log(json_encode($upload_info), 3, 'errors.log');
	echo json_encode($return);
		
	exit(); 
}
?>
