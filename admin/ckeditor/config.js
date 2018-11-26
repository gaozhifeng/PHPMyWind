/**
 * @license Copyright (c) 2003-2018, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function( config ) {
	
	 config.language = 'zh-cn';
	 //config.uiColor = '#AADC6E';

	config.extraPlugins = 'autogrow,codesnippet,image2,multiimg';
	//开启编辑器将根据内容量增长和缩小，但它始终至少高250像素，并且永远不会超过600像素的高度：	
	  //config.extraPlugins = 'autogrow';
	config.width = 1040; 
	config.height = 200; 
	config.autoGrow_minHeight = 200;
	config.autoGrow_maxHeight = 1000;
    //使用zenburn的代码高亮风格样式 PS:zenburn效果就是黑色背景
    //如果不设置着默认风格为default

	//工具栏是否可以被收缩
    config.toolbarCanCollapse = true;
    //工具栏的位置
    config.toolbarLocation = 'top';//可选：bottom
	
	// 取消 “拖拽以改变尺寸”功能
    config.resize_enabled = true;

　　// 去掉简单图片 变为image2
　  config.removePlugins = 'image'
　　//config.removePlugins = 'easyimage'; 

	//浏览服务器文件
	//config.filebrowserBrowseUrl ='/ckfinder/ckfinder.html1';
	config.filebrowserUploadUrl= CKEDITOR.basePath+'php/ckeditor.upload.php?token=c86cd332b75aae73cdc0f4e0be2d28d0&timestamp=phpmywind';
	
	// 启用拖拽上传
	config.uploadUrl= CKEDITOR.basePath+'php/ckeditor.upload.php?token=c86cd332b75aae73cdc0f4e0be2d28d0&timestamp=phpmywind';
		/* {
			"uploaded": 1, //返回成功 失败返回0
			"fileName": "foo(2).jpg",
			"url": "/files/foo(2).jpg",
			"error": {
				"message": "A file with the same name already exists. The uploaded file was renamed to \"foo(2).jpg\"."
			}
		} */

		
		
	// Reduce the list of block elements listed in the Format drop-down to the most commonly used.
	config.format_tags='p;h1;h2;h3;pre';
	// Simplify the Image and Link dialog windows. The "Advanced" tab is not needed in most cases.
	
	config.font_names='宋体;楷体;新宋体;黑体;隶书;幼圆;微软雅黑;Arial;Times New Roman;Verdana';
	config.toolbarGroups = [
		{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
		{ name: 'basicstyles', groups: [ 'cleanup', 'basicstyles' ] },
		{ name: 'colors', groups: [ 'colors' ] },
		{ name: 'forms', groups: [ 'forms'] },
		{ name: 'insert', groups: ['insert' ] },
		{ name: 'links', groups: [ 'links' ] },
		{ name: 'tools', groups: [ 'tools' ] },
		'/',
		{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
		{ name: 'styles', groups: [ 'styles' ] },
		{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
		{ name: 'others', groups: [ 'others' ] },
		{ name: 'about', groups: [ 'about' ] }
	];

	config.removeButtons = 'Checkbox,Radio,TextField,Textarea,Select,Form,Button,ImageButton,HiddenField,Language,Smiley,Templates,ShowBlocks,Anchor,HorizontalRule,CreateDiv,Styles,Scayt';
};

