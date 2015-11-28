<?php require_once(dirname(__FILE__).'/inc/config.inc.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>官方公告</title>
<style type="text/css">
body{margin:0;padding:0;font-size:12px;line-height:25px;font-family:"微软雅黑","宋体";color:#202020;}
html,div,dl,dt,dd,ul,ol,li,h1,h2,h3,h4,h5,h6,pre,form,fieldset,input,textarea,p,blockquote,th,td,p{margin:0;padding:0;}
.loadnews{background:url(templates/images/loading3.gif) no-repeat 0 center;padding-left:21px;color:#307cd5;}
#marqueeBox{color:#202020;white-space:nowrap;}
#marqueeBox a{color:#202020;text-decoration:none;}
#marqueeBox a:hover{text-decoration:underline;}
#marqueeBox span{color:#00F;margin-right:5px;}
</style>
</head>
<body>
<div id="shownews" class="loadnews">正在努力的获取最新消息...</div>
<script type="text/javascript" src="http://phpmywind.com/api/clientnews/clientnews.js"></script>
</body>
</html>