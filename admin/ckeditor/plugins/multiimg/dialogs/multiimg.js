(function() {  
console.log(CKEDITOR.plugins.getPath( 'multiimg' ));
	var url = CKEDITOR.plugins.getPath( 'multiimg' )+"dialogs/WebUploader/image.php?v=" +Math.random();
    CKEDITOR.dialog.add("multiimgDialog",  
        function(a) { 
            return {
                title: "批量上传图片",  
                minWidth: "695px",  
                minHeight:"380px",  
                contents: [{  
                    id: "tab1",  
                    label: "",  
                    title: "",  
                    expand: true,  
                    width: "420px",  
                    height: "300px",  
                    padding: 0,  
                    elements: [{  
                        type: "html",  
                        style: "width:695px;height:360px",  
                        html: '<iframe id="uploadFrame" name="uploadFrame" src="'+url+'" frameborder="0"></iframe>'  
                    }]  
                }],  
                // when the dialog ended width ensure,"onOK" will be executed.
                onOk: function() {
						
					//obj = $("#uploadFrame").eq(0); //jq 版本 需要引用jq
					var ins = a; 
					var obj = document.getElementById("uploadFrame");
					var iframeWin = obj.contentWindow;
                    var num = iframeWin.fileCount;
					var url_arr = iframeWin.url_arr; // 获取图片数组
                    var imgHtml = "";
					for (var key in url_arr){
						imgHtml += "<p><img src=" + url_arr[key] + " /></p>";   
					}
                   
                    ins.insertHtml(imgHtml);   
                },  
                onShow: function () {  
                    document.getElementById("uploadFrame").setAttribute("src",url);  
                }  
            }  
        })  
})();  

