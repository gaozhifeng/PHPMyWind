<?php require_once(dirname(__FILE__).'/inc/config.inc.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>RewriteURL 规则</title>
<style type="text/css">
body{font-size:13px;line-height:22px;font-family:Verdana;padding:0 0 20px 0;}
html,div,dl,dt,dd,ul,ol,li,h1,h2,h3,h4,h5,h6,pre,form,fieldset,input,textarea,p,blockquote,th,td,p{margin:0;padding:0;}
.cl{clear:both;}
.note{text-align:right;padding:10px;}
.note h1{color:#458FCE;font-size:24px;float:left;line-height:36px;}
.note p{float:right;color:#999;line-height:18px;font-size:12px;font-family:"微软雅黑";}
.main{padding:10px;}
.main h2{font-size:18px;line-height:24px;}
.main span{font-size:12px;}
.main pre{margin:20px 0;font-family:Verdana;border-top:2px solid #458FCE;border-bottom:1px solid #DBECEC;background:#fbfbfb;padding:10px;}
.copyright{text-align:center;font-size:12px;color:#666;}
</style>
</head>
<body>
<div class="note">
	<h1>RewriteURL 规则</h1>
	<p>以下规则为系统按设置自动生成<br />
		您也可以自行修改以满足需求</p>
	<div class="cl"></div>
</div>
<div class="main">
	<h2>Apache Web Server(独立主机用户)</h2>
	<span>复制规则粘贴到 Apache配置文件conf/httpd.conf 最后即可</span>
	<pre>
&lt;IfModule mod_rewrite.c&gt;
RewriteEngine On<br />RewriteCond %{QUERY_STRING} ^(.*)$
RewriteRule ^(.*)/index.html$ $1/index.php
RewriteCond %{QUERY_STRING} ^(.*)$
RewriteRule ^(.*)/about-([0-9]+)-([0-9]+)\.html$ $1/about.php?cid=$2&page=$3
RewriteCond %{QUERY_STRING} ^(.*)$
RewriteRule ^(.*)/news-([0-9]+)-([0-9]+)\.html$ $1/news.php?cid=$2&page=$3
RewriteCond %{QUERY_STRING} ^(.*)$
RewriteRule ^(.*)/newsshow-([0-9]+)-([0-9]+)-([0-9]+)\.html$ $1/newsshow.php?cid=$2&id=$3&page=$4
RewriteCond %{QUERY_STRING} ^(.*)$
RewriteRule ^(.*)/product-([0-9]+)-([0-9]+)\.html$ $1/product.php?cid=$2&page=$3
RewriteCond %{QUERY_STRING} ^(.*)$
RewriteRule ^(.*)/productshow-([0-9]+)-([0-9]+)-([0-9]+)\.html$ $1/productshow.php?cid=$2&id=$3&page=$4
RewriteCond %{QUERY_STRING} ^(.*)$
RewriteRule ^(.*)/case-([0-9]+)-([0-9]+)\.html$ $1/case.php?cid=$2&page=$3
RewriteCond %{QUERY_STRING} ^(.*)$
RewriteRule ^(.*)/caseshow-([0-9]+)-([0-9]+)-([0-9]+)\.html$ $1/caseshow.php?cid=$2&id=$3&page=$4
RewriteCond %{QUERY_STRING} ^(.*)$
RewriteRule ^(.*)/join-([0-9]+)\.html$ $1/join.php?page=$2
RewriteCond %{QUERY_STRING} ^(.*)$
RewriteRule ^(.*)/joinshow-([0-9]+)\.html$ $1/joinshow.php?id=$2
RewriteCond %{QUERY_STRING} ^(.*)$
RewriteRule ^(.*)/message-([0-9]+)\.html$ $1/message.php?page=$2
RewriteCond %{QUERY_STRING} ^(.*)$
RewriteRule ^(.*)/contact-([0-9]+)-([0-9]+)\.html$ $1/contact.php?cid=$2&page=$3
RewriteCond %{QUERY_STRING} ^(.*)$
RewriteRule ^(.*)/soft-([0-9]+)-([0-9]+)\.html$ $1/soft.php?cid=$2&page=$3
RewriteCond %{QUERY_STRING} ^(.*)$
RewriteRule ^(.*)/softshow-([0-9]+)-([0-9]+)-([0-9]+)\.html$ $1/softshow.php?cid=$2&id=$3&page=$4
RewriteCond %{QUERY_STRING} ^(.*)$
RewriteRule ^(.*)/goods-([0-9]+)-([0-9]+)-([0-9]+)\.html$ $1/goods.php?cid=$2&tid=$3&page=$4
RewriteCond %{QUERY_STRING} ^(.*)$
RewriteRule ^(.*)/goodsshow-([0-9]+)-([0-9]+)-([0-9]+)-([0-9]+)\.html$ $1/goodsshow.php?cid=$2&tid=$3&id=$4&page=$5
RewriteCond %{QUERY_STRING} ^(.*)$
RewriteRule ^(.*)/vote-([0-9]+)\.html$ $1/vote.php?id=$2
RewriteCond %{QUERY_STRING} ^(.*)$
RewriteRule ^(.*)/(\w+)\.html$ $1/$2.php?
&lt;/IfModule&gt;</pre>
	<h2>Apache Web Server(虚拟主机用户)</h2>
	<span>复制规则粘贴到记事本，保存为.htaccess文件，存放在在根目录即可</span>
	<pre># 将 RewriteEngine 模式打开
RewriteEngine On

# 修改以下语句中的 / 改为您的系统目录地址，如果程序放在根目录中则无需修改
RewriteBase /

# Rewrite 系统规则请勿修改<br />RewriteCond %{QUERY_STRING} ^(.*)$
RewriteRule ^index.html$ index.php
RewriteCond %{QUERY_STRING} ^(.*)$
RewriteRule ^about-([0-9]+)-([0-9]+)\.html$ about.php?cid=$1&page=$2
RewriteCond %{QUERY_STRING} ^(.*)$
RewriteRule ^news-([0-9]+)-([0-9]+)\.html$ news.php?cid=$1&page=$2
RewriteCond %{QUERY_STRING} ^(.*)$
RewriteRule ^newsshow-([0-9]+)-([0-9]+)-([0-9]+)\.html$ newsshow.php?cid=$1&id=$2&page=$3
RewriteCond %{QUERY_STRING} ^(.*)$
RewriteRule ^product-([0-9]+)-([0-9]+)\.html$ product.php?cid=$1&page=$2
RewriteCond %{QUERY_STRING} ^(.*)$
RewriteRule ^productshow-([0-9]+)-([0-9]+)-([0-9]+)\.html$ productshow.php?cid=$1&id=$2&page=$3
RewriteCond %{QUERY_STRING} ^(.*)$
RewriteRule ^case-([0-9]+)-([0-9]+)\.html$ case.php?cid=$1&page=$2
RewriteCond %{QUERY_STRING} ^(.*)$
RewriteRule ^caseshow-([0-9]+)-([0-9]+)-([0-9]+)\.html$ caseshow.php?cid=$1&id=$2&page=$3
RewriteCond %{QUERY_STRING} ^(.*)$
RewriteRule ^join-([0-9]+)\.html$ join.php?page=$1
RewriteCond %{QUERY_STRING} ^(.*)$
RewriteRule ^joinshow-([0-9]+)\.html$ joinshow.php?id=$1
RewriteCond %{QUERY_STRING} ^(.*)$
RewriteRule ^message-([0-9]+)\.html$ message.php?page=$1
RewriteCond %{QUERY_STRING} ^(.*)$
RewriteRule ^contact-([0-9]+)-([0-9]+)\.html$ contact.php?cid=$1&page=$2
RewriteCond %{QUERY_STRING} ^(.*)$
RewriteRule ^soft-([0-9]+)-([0-9]+)\.html$ soft.php?cid=$1&page=$2
RewriteCond %{QUERY_STRING} ^(.*)$
RewriteRule ^softshow-([0-9]+)-([0-9]+)-([0-9]+)\.html$ softshow.php?cid=$1&id=$2&page=$3
RewriteCond %{QUERY_STRING} ^(.*)$
RewriteRule ^goods-([0-9]+)-([0-9]+)-([0-9]+)\.html$ goods.php?cid=$1&tid=$2&page=$3
RewriteCond %{QUERY_STRING} ^(.*)$
RewriteRule ^goodsshow-([0-9]+)-([0-9]+)-([0-9]+)-([0-9]+)\.html$ goodsshow.php?cid=$1&tid=$2&id=$3&page=$4
RewriteCond %{QUERY_STRING} ^(.*)$
RewriteRule ^vote-([0-9]+)\.html$ vote.php?id=$1
RewriteCond %{QUERY_STRING} ^(.*)$
RewriteRule ^(\w+)\.html$ $1.php?
</pre>
	<h2>IIS Web Server(独立主机用户)</h2>
	<span>复制规则粘贴到httpd.ini即可</span>
	<pre># 将 RewriteEngine 模式打开
[ISAPI_Rewrite]

# 3600 = 1 hour
CacheClockRate 3600

RepeatLimit 32

# Protect httpd.ini and httpd.parse.errors files
# from accessing through HTTP<br />RewriteRule ^(.*)/index.html$ $1/index.php
RewriteRule ^(.*)/about-([0-9]+)-([0-9]+)\.html$ $1/about\.php\?cid=$2&page=$3
RewriteRule ^(.*)/news-([0-9]+)-([0-9]+)\.html$ $1/news\.php\?cid=$2&page=$3
RewriteRule ^(.*)/newsshow-([0-9]+)-([0-9]+)-([0-9]+)\.html$ $1/newsshow\.php\?cid=$2&id=$3&page=$4
RewriteRule ^(.*)/product-([0-9]+)-([0-9]+)\.html$ $1/product\.php\?cid=$2&page=$3
RewriteRule ^(.*)/productshow-([0-9]+)-([0-9]+)-([0-9]+)\.html$ $1/productshow\.php\?cid=$2&id=$3&page=$4
RewriteRule ^(.*)/case-([0-9]+)-([0-9]+)\.html$ $1/case\.php\?cid=$2&page=$3
RewriteRule ^(.*)/caseshow-([0-9]+)-([0-9]+)-([0-9]+)\.html$ $1/caseshow\.php\?cid=$2&id=$3&page=$4
RewriteRule ^(.*)/join-([0-9]+)\.html$ $1/join\.php\?page=$2
RewriteRule ^(.*)/joinshow-([0-9]+)\.html$ $1/joinshow\.php\?id=$2
RewriteRule ^(.*)/message-([0-9]+)\.html$ $1/message\.php\?page=$2
RewriteRule ^(.*)/contact-([0-9]+)-([0-9]+)\.html$ $1/contact\.php\?cid=$2&page=$3
RewriteRule ^(.*)/soft-([0-9]+)-([0-9]+)\.html$ $1/soft\.php\?cid=$2&page=$3
RewriteRule ^(.*)/softshow-([0-9]+)-([0-9]+)-([0-9]+)\.html$ $1/softshow\.php\?cid=$2&id=$3&page=$4
RewriteRule ^(.*)/goods-([0-9]+)-([0-9]+)-([0-9]+)\.html$ $1/goods\.php\?cid=$2&tid=$3&page=$4
RewriteRule ^(.*)/goodsshow-([0-9]+)-([0-9]+)-([0-9]+)-([0-9]+)\.html$ $1/goodsshow\.php\?cid=$2&tid=$3&id=$4&page=$5
RewriteRule ^(.*)/vote-([0-9]+)\.html$ $1/vote\.php\?id=$2
RewriteRule ^(.*)/(\w+)\.html$ $1/$2\.php\?
</pre>
	<h2>IIS7 Web Server(独立主机用户)</h2>
	<span>复制规则粘贴到记事本，保存为web.config文件，存放在在根目录即可</span>
	<pre>&lt;?xml version=&quot;1.0&quot; encoding=&quot;UTF-8&quot;?&gt;<br />&lt;configuration&gt;<br />&lt;system.webServer&gt;<br />&lt;!--伪静态开始--&gt;<br />&lt;rewrite&gt;<br />&lt;rules&gt;<br />&lt;rule name="index"&gt;
	&lt;match url="^(.*/)*index.html$" /&gt;
	&lt;action type="Rewrite" url="{R:1}/index.php" /&gt;
&lt;/rule&gt;
&lt;rule name="about"&gt;
	&lt;match url="^(.*/)*about-([0-9]+)-([0-9]+)\.html$" /&gt;
	&lt;action type="Rewrite" url="{R:1}/about.php\?cid={R:2}&amp;amp;page={R:3}" /&gt;
&lt;/rule&gt;
&lt;rule name="news"&gt;
	&lt;match url="^(.*/)*news-([0-9]+)-([0-9]+)\.html$" /&gt;
	&lt;action type="Rewrite" url="{R:1}/news.php\?cid={R:2}&amp;amp;page={R:3}" /&gt;
&lt;/rule&gt;
&lt;rule name="newsshow"&gt;
	&lt;match url="^(.*/)*newsshow-([0-9]+)-([0-9]+)-([0-9]+)\.html$" /&gt;
	&lt;action type="Rewrite" url="{R:1}/newsshow.php\?cid={R:2}&amp;amp;id={R:3}&amp;amp;page={R:4}" /&gt;
&lt;/rule&gt;
&lt;rule name="product"&gt;
	&lt;match url="^(.*/)*product-([0-9]+)-([0-9]+)\.html$" /&gt;
	&lt;action type="Rewrite" url="{R:1}/product.php\?cid={R:2}&amp;amp;page={R:3}" /&gt;
&lt;/rule&gt;
&lt;rule name="productshow"&gt;
	&lt;match url="^(.*/)*productshow-([0-9]+)-([0-9]+)-([0-9]+)\.html$" /&gt;
	&lt;action type="Rewrite" url="{R:1}/productshow.php\?cid={R:2}&amp;amp;id={R:3}&amp;amp;page={R:4}" /&gt;
&lt;/rule&gt;
&lt;rule name="case"&gt;
	&lt;match url="^(.*/)*case-([0-9]+)-([0-9]+)\.html$" /&gt;
	&lt;action type="Rewrite" url="{R:1}/case.php\?cid={R:2}&amp;amp;page={R:3}" /&gt;
&lt;/rule&gt;
&lt;rule name="caseshow"&gt;
	&lt;match url="^(.*/)*caseshow-([0-9]+)-([0-9]+)-([0-9]+)\.html$" /&gt;
	&lt;action type="Rewrite" url="{R:1}/caseshow.php\?cid={R:2}&amp;amp;id={R:3}&amp;amp;page={R:4}" /&gt;
&lt;/rule&gt;
&lt;rule name="join"&gt;
	&lt;match url="^(.*/)*join-([0-9]+)\.html$" /&gt;
	&lt;action type="Rewrite" url="{R:1}/join.php\?page={R:2}" /&gt;
&lt;/rule&gt;
&lt;rule name="joinshow"&gt;
	&lt;match url="^(.*/)*joinshow-([0-9]+)\.html$" /&gt;
	&lt;action type="Rewrite" url="{R:1}/joinshow.php\?id={R:2}" /&gt;
&lt;/rule&gt;
&lt;rule name="message"&gt;
	&lt;match url="^(.*/)*message-([0-9]+)\.html$" /&gt;
	&lt;action type="Rewrite" url="{R:1}/message.php\?page={R:2}" /&gt;
&lt;/rule&gt;
&lt;rule name="contact"&gt;
	&lt;match url="^(.*/)*contact-([0-9]+)-([0-9]+)\.html$" /&gt;
	&lt;action type="Rewrite" url="{R:1}/contact.php\?cid={R:2}&amp;amp;page={R:3}" /&gt;
&lt;/rule&gt;
&lt;rule name="soft"&gt;
	&lt;match url="^(.*/)*soft-([0-9]+)-([0-9]+)\.html$" /&gt;
	&lt;action type="Rewrite" url="{R:1}/soft.php\?cid={R:2}&amp;amp;page={R:3}" /&gt;
&lt;/rule&gt;
&lt;rule name="softshow"&gt;
	&lt;match url="^(.*/)*softshow-([0-9]+)-([0-9]+)-([0-9]+)\.html$" /&gt;
	&lt;action type="Rewrite" url="{R:1}/softshow.php\?cid={R:2}&amp;amp;id={R:3}&amp;amp;page={R:4}" /&gt;
&lt;/rule&gt;
&lt;rule name="goods"&gt;
	&lt;match url="^(.*/)*goods-([0-9]+)-([0-9]+)-([0-9]+)\.html$" /&gt;
	&lt;action type="Rewrite" url="{R:1}/goods.php\?cid={R:2}&amp;amp;tid={R:3}&amp;amp;page={R:4}" /&gt;
&lt;/rule&gt;
&lt;rule name="goodsshow"&gt;
	&lt;match url="^(.*/)*goodsshow-([0-9]+)-([0-9]+)-([0-9]+)-([0-9]+)\.html$" /&gt;
	&lt;action type="Rewrite" url="{R:1}/goodsshow.php\?cid={R:2}&amp;amp;tid={R:3}&amp;amp;id={R:4}&amp;amp;page={R:5}" /&gt;
&lt;/rule&gt;
&lt;rule name="vote"&gt;
	&lt;match url="^(.*/)*vote-([0-9]+)\.html$" /&gt;
	&lt;action type="Rewrite" url="{R:1}/vote.php\?id={R:2}" /&gt;
&lt;/rule&gt;
&lt;rule name="custom"&gt;
	&lt;match url="^(.*/)*(\w+)\.html$" /&gt;
	&lt;action type="Rewrite" url="{R:1}/{R:2}.php\?" /&gt;
&lt;/rule&gt;
&lt;/rules&gt;<br />&lt;/rewrite&gt;<br />&lt;!--伪静态结束--&gt;<br />&lt;directoryBrowse enabled=&quot;true&quot; showFlags=&quot;Date, Time, Size, Extension, LongDate&quot; /&gt;<br />&lt;/system.webServer&gt;<br />&lt;/configuration&gt;</pre>
	<h2>Nginx Web Server</h2>
	<span>复制规则粘贴到nginx.conf或虚拟主机段的根目录</span>
	<pre>rewrite ^([^\.]*)/index.html$ $1/index.php last;
rewrite ^([^\.]*)/about-([0-9]+)-([0-9]+)\.html$ $1/about.php?cid=$2&page=$3 last;
rewrite ^([^\.]*)/news-([0-9]+)-([0-9]+)\.html$ $1/news.php?cid=$2&page=$3 last;
rewrite ^([^\.]*)/newsshow-([0-9]+)-([0-9]+)-([0-9]+)\.html$ $1/newsshow.php?cid=$2&id=$3&page=$4 last;
rewrite ^([^\.]*)/product-([0-9]+)-([0-9]+)\.html$ $1/product.php?cid=$2&page=$3 last;
rewrite ^([^\.]*)/productshow-([0-9]+)-([0-9]+)-([0-9]+)\.html$ $1/productshow.php?cid=$2&id=$3&page=$4 last;
rewrite ^([^\.]*)/case-([0-9]+)-([0-9]+)\.html$ $1/case.php?cid=$2&page=$3 last;
rewrite ^([^\.]*)/join-([0-9]+)\.html$ $1/join.php?page=$2 last;
rewrite ^([^\.]*)/joinshow-([0-9]+)\.html$ $1/joinshow.php?id=$2 last;
rewrite ^([^\.]*)/message-([0-9]+)\.html$ $1/message.php?page=$2 last;
rewrite ^([^\.]*)/contact-([0-9]+)-([0-9]+)\.html$ $1/contact.php?cid=$2&page=$3 last;
rewrite ^([^\.]*)/soft-([0-9]+)-([0-9]+)\.html$ $1/soft.php?cid=$2&page=$3 last;
rewrite ^([^\.]*)/softshow-([0-9]+)-([0-9]+)-([0-9]+)\.html$ $1/softshow.php?cid=$2&id=$3&page=$4 last;
rewrite ^([^\.]*)/goods-([0-9]+)-([0-9]+)-([0-9]+)\.html$ $1/goods.php?cid=$2&tid=$3&page=$4 last;
rewrite ^([^\.]*)/goodsshow-([0-9]+)-([0-9]+)-([0-9]+)-([0-9]+)\.html$ $1/goodsshow.php?cid=$2&tid=$3&id=$4&page=$5 last;
rewrite ^([^\.]*)/vote-([0-9]+)\.html$ $1/vote.php?id=$2 last;
rewrite ^([^\.]*)/(\w+)\.html$ $1/$2.php? last;
if (!-e $request_filename) {
	return 404;
}</pre>
</div>
<div class="copyright">© 2015 phpMyWind.com All Rights Reserved.</div>
</body>
</html>
