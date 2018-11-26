/*
 * 获取上传窗口函数
 *
 * @access   public
 * @frame     string   创建上传入口id 
 * @title    string  弹出窗口标题
 * @type     string  可上传文件类型,可以是直接的类型或是image|soft|media
 * @desc     string  可上传文件描述,可以是直接的描述或是image|soft|media
 * @num      string  可上传数量
 * @size     string  可上传文件大小
 * @input    string  处理后返回值写入input
 * @area     string  多附件时返回的内容区域
 */
 


function GetUploadify(frame,title,type,desc,num,size,input,area)
{	
	layer.open({
	  title:title,
	  type: 2,
	  area: ['576px', '430px'],
	  fixed: false, //不固定
	  maxmin: true,
	  content: 'ckeditor/plugins/multiimg/dialogs/WebUploader/image.php?frame='+ frame +'&type='+ type +'&desc='+ encodeURI(desc) +'&num='+ num +'&size='+ size +'&frame='+ frame +'&input='+ input +'&area='+ area,
	});
}

/*
 * 删除组图input
 *
 * @access   public
 * @val      string  删除的图片input
 */

function ClearPicArr(val)
{
	$("li[rel='"+ val +"']").remove();
}

