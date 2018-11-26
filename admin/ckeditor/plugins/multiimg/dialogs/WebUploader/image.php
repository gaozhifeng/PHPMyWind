<?php	require_once(dirname(__FILE__).'/../../../../../inc/config.inc.php');
	//初始化参数
$size  = isset($size)  ? $size  : $cfg_max_file_size;
$num   = isset($num)   ? $num   : 5;
$input = isset($input) ? $input : '';
$area  = isset($area)  ? $area  : '';
$frame = isset($frame) ? $frame : '';
$title = isset($title) ? $title : '';
$type  = isset($type)  ? $type  : 'image';

//获取上传文件类型
function GetUpType($type='image')
{
	global $cfg_upload_img_type,
		   $cfg_upload_soft_type,
		   $cfg_upload_media_type;

	if($type == 'image')
	{
		$uptype =  str_replace("|",",",$cfg_upload_img_type) ;
		return $uptype;
	}

	else if($type == 'soft')
	{
		$uptype =  str_replace("|",",",$cfg_upload_soft_type) ;
		return $uptype;
	}

	else if($type == 'media')
	{
		$uptype =  str_replace("|",",",$cfg_upload_media_type) ;
		return $uptype;
	}

	else if($type == 'all')
	{
		$alltype = $cfg_upload_img_type.'|'.$cfg_upload_soft_type.'|'.$cfg_upload_media_type;
		$uptype =  str_replace("|",",",$alltype) ;
		return $uptype;
	}

	else
	{
		return $type;
	}
}


//获取上传文件描述
function mimeTypes($type)
{

	global $cfg_upload_img_type,
		   $cfg_upload_soft_type,
		   $cfg_upload_media_type;


	if($type == 'image')
	{
		$uptype = explode('|',$cfg_upload_img_type);
		$upstr  = '';
		foreach($uptype as $v)
		{
			if(!empty($v))
			{
				$upstr .= '.'.$v.',';
			}
		}
		return $upstr;
	}

	else if($type == 'soft')
	{
		$uptype = explode('|',$cfg_upload_soft_type);
		$upstr  = '';
		foreach($uptype as $v)
		{
			if(!empty($v))
			{
				$upstr .= '.'.$v.',';
			}
		}
		return $upstr;
	}

	else if($type == 'media')
	{
		$uptype = explode('|',$cfg_upload_media_type);
		$upstr  = '';
		foreach($uptype as $v)
		{
			if(!empty($v))
			{
				$upstr .= '.'.$v.',';
			}
		}
		return $upstr;
	}

	else if($type == 'all')
	{
		$alltype = $cfg_upload_img_type.'|'.$cfg_upload_soft_type.'|'.$cfg_upload_media_type;
		$uptype  = explode('|',$alltype);
		$upstr   = '';
		foreach($uptype as $v)
		{
			if(!empty($v))
			{
				$upstr .= '.'.$v.',';
			}
		}
		return $upstr;
	}

	else
	{
		return $type;
	}
}

?>
<!DOCTYPE html>
<html>
    <head>
        <title>CKEditor multiimg</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!--引入CSS-->
<link rel="stylesheet" type="text/css" href="webuploader.css">

	<!--引入JS-->
<script type="text/javascript" src="jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="webuploader.min.js"></script>
<script type="text/javascript" src="../../../../../plugin/layer/layer.js"></script>
<style>
.mainbody{/*padding:10px 15px;*/ margin:10px 15px;}
.location{padding-bottom:9px; border-bottom:solid 1px #eee; height:22px; line-height:22px; font-size:12px; color:#686f7f; text-overflow:ellipsis; overflow:hidden;}
.location a{display:inline-block; color:#686f7f; text-decoration:none;}
.location a:hover{color:#0065D9; text-decoration:none;}
/*刷新按钮样式*/
.reload{width: 32px;height: 20px;line-height: 20px;text-align: center;position:fixed;right:20px;top:15px;z-index:999;background-color:#16a0d3;}
a.reload:hover{background: #267cb7;text-decoration: none;color: #fff;}
a.reload{color: #fff;}
.tab-content{padding:10px; font-size:12px; color:#666; border:1px solid #eee; border-top:none; box-sizing:border-box; /*overflow:hidden;*/}
.tab-content:after{clear:both; content:"."; display:block; height:0; visibility:hidden;}
.btn{background:#16a0d3; border:none; color:#fff; cursor:pointer; display:inline-block; font-family:"Microsoft Yahei"; font-size:12px; height:32px; line-height:32px; margin:0 1px 0 0; padding:0 20px;}
.btn a{color:#fff; text-decoration:none;}
.uploader-list .item{position:relative;float:left;width:120px;height:120px;border: 1px solid #CCC;cursor: default;margin: 5px 0 5px 5px;}
.uploader-list .item .edui-image-pic{position: absolute;left:-9999px;}
.uploader-list .item .image-close{position:absolute;right:0;background: url('close.png');width:17px;height:17px;cursor:pointer;z-index:1}
.uploader-list .item img{margin:4px}
.uploader-list .item .info{text-indent: 5px;color: white;overflow: hidden;white-space: nowrap;ext-overflow : ellipsis;font-size: 12px;z-index: 10;}
.bottom_float{position: absolute;left: 0px;bottom: 0px;right: 0px;height: 20px;line-height: 20px;background:  rgba(9, 130, 85, 0.6);overflow: hidden;   white-space: nowrap; text-overflow: ellipsis}
.none{display:none} .wu-example{position: relative; padding:0 ; margin: 0; text-align: center;}
/*默认样式*/
.btns{margin:100px 0;}
.btns .webuploader-pick{background: rgb(61, 202, 195); border-radius: 3px; border:0; color: #fff; cursor: pointer; display: inline-block; font-family: "Microsoft Yahei"; font-size: 12px; height: 32px; line-height: 32px; padding: 0 20px;}
.btns .webuploader-pick-hover{background: #00a2d4;}
.btns .webuploader-pick-disable{opacity: 0.6;pointer-events:none;}
.btns .webuploader-container{padding:0 10px}
/*列表模式*/
.list_btn {position:relative;float:left;width:120px;height:120px;border: 0;cursor: default;margin: 5px 0 0 5px;display:none}
.list_btn .webuploader-pick{position:relative;width:120px;height:120px;border:0px ;background-image: url(upload2.png);padding:0px;}
.list_btn .webuploader-container{border:0px;padding:0}
.list_btn .webuploader-container:hover{filter:alpha(Opacity=70);-moz-opacity:0.7;opacity: 0.7}

.btn-default{background: rgb(61, 202, 195);}
.ta-c{text-align: center;}
/*进度条样式*/
.progress-bar{height:20px;background:#0099E3;}
.state{color:#fff;z-index:999}

.footer{position: absolute;right:10px;bottom:30px}
.btn-hover { background-color: #d5e1f2;border: 1px solid #eee;margin:10px;background:#fff;color:#000;margin-bottom:0}
.d5e1f2{border-color:#d5e1f2}
</style>
</head>
<body class="mainbody">

<!--导航栏-->
<div class="location" style="height:auto">
 <span id="urldesc">最多上传*个附件,单文件最大,类型*</span>
   <!--<a href="javascript:location.reload();" class="reload" >刷新</a>-->
</div>
<div class="line10"></div>
<!--/导航栏-->
<!--内容-->


<div class="tab-content  ta-c">
   <div id="uploader" class="wu-example">
    <!--用来存放文件信息-->
        <div id="thelist" class="uploader-list">
			<div class="list_btn" id="btn">
				<div id="picker2"></div>
		</div>
		</div>
		<div class="btns" id="btn">
				<div id="picker">选择文件</div>
		</div>
        <!-- <div class="btn btok" style="display: none;">完成</div>-->
        <div class="clear"></div>
    </div>
</div>

<!--/内容-->
  <script type="text/javascript">
   var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
    //var index = 1; //获取窗口索引
   //var path = '/data/httpfile/ckeditor.multiimg.php';
    var path = '../../../../php/ckeditor.upload.php?type=multiimg&timestamp=<?php echo time();?>&frame=<?php echo $frame;?>&token=<?php echo md5('unique_salt'.time()); ?>';
    var urlt = '图片上传';
	var filetype = '<?php echo mimeTypes($type) ;?>';
	var filetype_none = '<?php echo GetUpType($type) ;?>';
	var numLimit = <?php echo $num;?> ;//每次允许上传数量
	var max_size = '<?php echo $size;?>';// 每次允许上传数量 字节
	var fileCount = 0;//统计图片 不可修改
	var url_arr = {};
    $('#urldesc').html('单次最多上传'+numLimit+'个附件,单文件最大<b style="color:red">'+(max_size/(1024*1024)).toFixed(1)+'M</b>,类型 <b style="color:red">'+filetype_none+'</b>;');
    var uploader = WebUploader.create({
    // swf文件路径
    swf: 'Uploader.swf',
    // 文件接收服务端。
    server:  path ,
    fileNumLimit: numLimit, //每次允许上传数量
	threads:2, //上传线程
	duplicate:true,  // 是否重复上传
    //fileSingleSizeLimit:<{$max_file_size}>,
    // 选择文件的按钮。可选。
    // 内部根据当前运行是创建，可能是input元素，也可能是flash.
  pick: {
    id:$("#picker"), // id
       multiple: true  // false  单选  批量上传 true 
      },
   accept: {
            title: urlt,
            extensions: filetype_none,
            mimeTypes: filetype
        },

    // 不压缩image, 默认如果是jpeg，文件上传前会压缩一把再上传！
    resize: false,
    auto:true
});
  // 添加“上传文件列表”的按钮，
    uploader.addButton({
        id: '#picker2'
    });


// 当有文件被添加进队列的时候
uploader.on( 'fileQueued', function( file ) {
	add_list_btn();
	 uploader.makeThumb( file, function( error, ret ) {
        if ( error ) {
          $('#btn').before( '<div id="' + file.id + '" class="item">' +
			'<div class="image-close none"  onclick="delFile('+file.id+')" ></div>'+
			'预览错误'+
			'<span class="info none bottom_float">' + file.name + '</span>' +
			'<span class="state bottom_float">等待上传...</span>' +
			'<span class="progress-bar bottom_float"></span>' +
		'</div>' );
        } else {
            $('#btn').before( '<div id="' + file.id + '" class="item">' +
			'<div class="image-close none"  onclick="delFile('+file.id+')" ></div>'+
			'<img alt="" src="' + ret + '" />'+
			'<span class="info none bottom_float">' + file.name + '</span>' +
			'<span class="state bottom_float">等待上传...</span>' +
			'<span class="progress-bar bottom_float"></span>' +
		'</div>' );
        }
	
	   
    });
 fileCount++;
});
// 文件上传过程中创建进度条实时显示。
uploader.on( 'uploadProgress', function( file, percentage ) {
    var $li = $( '#'+file.id ),
    $percent = $li.find('span.progress-bar');
    $li.find('span.state').text('上传中'+ parseInt(percentage * 100) + '%  等待完成' );
    $percent.css( 'width', percentage * 100 + '%' );
});
uploader.on( 'uploadSuccess', function( file,response) {
    if(response.error=='success'){
    $( '#'+file.id ).find('span.state').text('已完成 100%');
	$('.image-close').show();
	 url_arr[file.id] = response.url ; //添加上传文件路径到数组
	 console.log(url_arr); 
	 
	//$('.btok').show();
    //var gourl ='go_url("'+response.url+'")';
    //$('.btok').attr('onclick',gourl); 
}else{
     $( '#'+file.id ).find('span.state').text(response.error);
	 $( '#'+file.id ).find('span.state').attr("title",response.error);
	 $( '#'+file.id ).find('span.state').css("background-color","red")
	 $('.image-close').show();
	//parent.layer.msg(response.error, {shade: 0.2,icon: 2}, function(){
	  //location.reload(); 
	  //});
}
    console.log(response); 
});

uploader.on( 'uploadError', function( file ) {
    $( '#'+file.id ).find('span.state').text('上传出错');
	 $( '#'+file.id ).find('span.state').css("background-color","red")
	 $('.image-close').show();
});

uploader.on( 'uploadComplete', function( file ) {
    $( '#'+file.id ).find('.progress').fadeOut();
});
uploader.on( 'uploadAccept', function( file, response ) {
 console.log(response.error); //这里可以得到后台返回的数据
});

uploader.on('error', function (type) {
                switch (type) {
                    case 'Q_EXCEED_NUM_LIMIT':
                          alert('错误：上传文件数量超过限制！');
                        break;
                    case 'Q_EXCEED_SIZE_LIMIT':
                           alert('错误：文件总大小超出限制！');
                        break;
                    case 'F_EXCEED_SIZE':
                          alert('错误：文件大小超出限制！');
                        break;
                    case 'Q_TYPE_DENIED':
                         alert('错误：禁止上传该类型文件！');
                        break;
                    case 'F_DUPLICATE':
                           alert('错误：请勿重复上传该文件！');
                        break;
                    default:
					
                           //parent.layer.msg('错误代码：' + type, {shade: 0.2,icon: 2});
                          alert('错误代码：' + type);
                        break;
                }
            });

function go_url(url){
		for (var key in url_arr){
			url = url_arr[key];   
		}
	if(!(typeof(url) == "undefined")){
		 parent.$('#<?php echo $area;?>').val(url);
	}
    parent.layer.close(index);
}

function go_url2(url){
		fileurl_tmp ='';
		for (var key in url_arr){
			fileurl_tmp += '<li rel="'+ url_arr[key] +'"><input type="text" name="<?php echo $input; ?>[]" value="'+ url_arr[key] +'" /><a href="javascript:void(0);" onclick="ClearPicArr(\''+ url_arr[key] +'\')">删除</a><br /><input type="text" name="<?php echo $input; ?>_txt[]" value="" /><span>描述</span></li>';
		}
	parent.$('#<?php echo $area;?>').append(fileurl_tmp);
    parent.layer.close(index);
}

function add_buns(){
	$('.btns').show();
	$(".list_btn").hide();
}
function add_list_btn(){
	$('.btns').hide();
	$(".list_btn").show();

}
function delFile(file){
	 
	var $li = file.id;
	 fileCount--;
	  $('#'+$li).remove();//删除图片
	  delete url_arr[$li];//删除对象
	 //alert(fileCount);
	 if ( fileCount<1 ) {
           add_buns();
      }
}
</script>


<?php if($frame=='uploadify2'){;?>
<div class="footer">
<div class="btn btn-hover d5e1f2" onclick="go_url2(url_arr)">确认</div>
<div class="btn btn-hover" onclick=" parent.layer.close(index);">取消</div></div>
<?php }if($frame=='uploadify'){;?>
<div class="footer">
<div class="btn btn-hover d5e1f2" onclick="go_url(url_arr)">确认</div>
<div class="btn btn-hover" onclick=" parent.layer.close(index);">取消</div>
<?php };?>

</body>
</html>