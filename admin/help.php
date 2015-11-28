<?php require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('help'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>开发帮助中心</title>
<style type="text/css">
body{font-family:"微软雅黑",Arial,"宋体";font-size:12px;line-height:22px;margin:0;padding:20px;color:#333;}
html,div,dl,dt,dd,ul,ol,li,h1,h2,h3,h4,h5,h6,pre,form,fieldset,input,textarea,p,blockquote,th,td,p{margin:0;padding:0;}
a{color:#333;text-decoration:none;}
a:hover{text-decoration:underline;}
a:focus{outline:none;}

h1{margin-bottom:20px;padding-bottom:10px;border-bottom:1px solid #e5e5e5;line-height:32px;font-size:18px;color:#333;font-family:"微软雅黑";}
h1 i{font-size:9px;}
p{white-space:normal;word-break:break-all;}
p strong{color:#00F;font-weight:normal;}
.hr_1,.hr_10,.preline{font-size:1px;line-height:1px;clear:both;_overflow:hidden;}
.hr_1{height:1px;}.hr_10{height:10px;}.preline{height:10px;}
.uselist{margin-bottom:20px;}
.uselist div strong{color:#00F;font-weight:normal;}
.uselist h2{margin-bottom:5px;font-size:14px;color:#607280;}
.uselist h3{margin:10px 0 3px;font-size:12px;background:#f4f4f4;color:#000;padding-left:6px;font-weight:normal;}
.uselist em{color:red;font-style:normal;font-family:Verdana;}
.sdir{width:145px;display:inline-block;}

.toptxt{position:absolute;right:20px;top:30px;font-size:12px;color:#ccc;}
.toptxt a{color:#666;}
.author{margin-top:10px;}
.author h2{color:#000;font-size:14px;}
.footer{margin-top:20px;text-align:center;color:#ccc;}

/*定义新型浏览器特性*/
::-webkit-scrollbar{width:10px;height:10px}
::-webkit-scrollbar-button:vertical{display:none}
::-webkit-scrollbar-track:vertical{background:000}
::-webkit-scrollbar-track-piece{background:#f6f6f6}
::-webkit-scrollbar-thumb:vertical{background:#d0d0d0;}
::-webkit-scrollbar-thumb:vertical:hover{background:#3B3B3B}
::-webkit-scrollbar-corner:vertical{background:#535353}
::-webkit-scrollbar-resizer:vertical{background:#FF6E00}
</style>
</head>
<body>
<h1>开发帮助 <i>v1.6</i></h1>
<div class="toptxt"><strong><a href="home.php">系统首页</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="javascript:location.reload();">刷新</a></strong></div>
<div class="uselist">
	<h2>单页信息调用</h2>
	函数名称：<strong>Info($classid, $num=0, $gourl='')</strong><br />
	函数位置：include/func.class.php<br />
	函数说明：$classid 必填，栏目的id；$num 可空，字数显示，0或空为不限制；$gourl 可空，截取后跳转的连接。
	<h3>- 截取模式演示</h3>
	代码：<em>&lt;?php echo Info(3,20); ?&gt;</em><br />
	效果：百度，全球最大的中文搜索引擎、最大的中...
<h3>- 截取有链接模式演示</h3>
	代码：<em>&lt;?php echo Info(3,20,'http://www.baidu.com/'); ?&gt;</em><br />
	效果：百度，全球最大的中文搜索引擎、最大的中...<a href="http://www.baidu.com/">[更多>>]</a>
	<h3>- 不截取模式演示</h3>
	代码：<em>&lt;?php echo Info(3); ?&gt;</em><br />
	效果：百度，全球最大的中文搜索引擎、最大的中文网站。2000年1月创立于北京中关村。<br />
	1999年底，身在美国硅谷的李彦宏看到了中国互联网及中文搜索引擎服务的巨大发展潜力，抱着技术改变世界的梦想，他毅然辞掉硅谷的高薪工作，携搜索引擎专利技术，于2000年1月1日在中关村创建了百度公司。从最初的不足10人发展至今，员工人数超过12000人。如今的百度，已成为中国最受欢迎、影响力最大的中文网站。<br />
	百度拥有数千名研发工程师，这是中国乃至全球最为优秀的技术团队，这支队伍掌握着世界上最为先进的搜索引擎技术，使百度成为中国掌握世界尖端科学核心技术的中国高科技企业，也使中国成为美国、俄罗斯、和韩国之外，全球仅有的4个拥有搜索引擎核心技术的国家之一。 </div>
<div class="hr_10"></div>
<div class="uselist">
	<h2>列表信息调用</h2>
	代码说明：						通过Execute方法执行SQL语句，然后通过while循环将GetArray方法获取出来的记录循环出来
	<h3>- 演示代码</h3>
	<em>&lt;?php <br />
	$dosql-&gt;Execute(&quot;SELECT * FROM `#@__infolist` WHERE (classid=4 or parentid=4) AND delstate='' AND checkinfo=true ORDER BY orderid,id DESC LIMIT 0,3&quot;);<br />
	while($row = $dosql-&gt;GetArray())<br />
	{<br />
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;if($row['linkurl'] == '') $gourl = 'newsshow.php?cid='.$row['classid'].'&amp;amp;id='.$row['id'];<br />
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;else $gourl = $row['linkurl'];<br />
	?&gt;</em><br />
	&lt;a href=&quot;<em>&lt;?php echo $gourl; ?&gt;</em>&quot; style=&quot;color:<em>&lt;?php echo $row['colorval']; ?&gt;</em>;font-weight:<em>&lt;?php echo $row['boldval']; ?&gt;</em>;&quot;&gt;<em>&lt;?php echo ReStrLen($row['title'],14); ?&gt;</em>&lt;/a&gt;<em>&lt;?php echo GetDateMk($row['posttime']); ?&gt;<br />
	&lt;?php<br />
	}<br />
	?&gt;</em>
	<h3>- 演示效果</h3>
	百度数据报告进入全国两会引起...　 2011-05-29 <br />
	首届百度高校互联网产品设计大...　 2011-05-29 <br />
	百度启动“应用基金奖励计划”　　2011-05-29 <br />
</div>
<div class="hr_10"></div>
<div class="uselist">
	<h2>单条记录调用</h2>
	<h3>- 演示代码</h3>
	<em>&lt;?php <br>
	$row = $dosql-&gt;GetOne(&quot;SELECT * FROM `#@__infoclass` WHERE id=4&quot;);<br>
	print_r($row); <br>
	?&gt;</em>
	<h3>- 打印$row</h3>
	Array
	(
	[id] => 4
	[classname] => 新闻动态
	[parentid] => 0
	[parentstr] => 0,
	[linkurl] =>
	[picurl] =>
	[content] =>
	[orderid] => 3
	[checkinfo] => true
	[infotype] => 1
	[classlevel] => 0
	)</div>
<div class="hr_10"></div>
<div class="uselist">
	<h2>常用类使用说明</h2>
	<h3>- <strong>$dosql</strong> 数据库操作类<br />
		- 所在位置：include/mysql.class.php</h3>
	<div>
		<div class="preline"></div>
		具体方法：<strong>$dosql-&gt;QueryNone($sql)</strong><br />
		方法说明：与ExecNoneQuery方法功能相同，执行一个不返回结果的SQL语句，如update,delete,insert等。
		<div class="preline"></div>
		具体方法：<strong>$dosql-&gt;Query($sql,$id)</strong><br />
		方法说明：与Execute方法功能相同，执行一个带返回结果的SQL语句，如SELECT，SHOW等。$id的用途在于区别每个记录集，普通查询可以省略，循环多个记录集需填写。
		<div class="preline"></div>
		具体方法：<strong>$dosql-&gt;ExecNoneQuery($sql)</strong><br />
		方法说明：执行一个不返回结果的SQL语句，如update,delete,insert等。
		<div class="preline"></div>
		具体方法：<strong>$dosql-&gt;Execute($sql,$id)</strong><br />
		方法说明：执行一个带返回结果的SQL语句，如SELECT，SHOW等。$id的用途在于区别每个记录集，普通查询可以省略，循环多个记录集需填写。
		<div class="preline"></div>
		具体方法：<strong>$dosql-&gt;GetOne($sql, $acctype=MYSQL_ASSOC)</strong><br />
		方法说明：执行一个SQL语句,返回前一条记录或仅返回一条记录。$acctype为返回记录集方式，MYSQL_ASSOC为字段名（默认），MYSQL_NUM为字段数比如row[2]，MYSQL_BOTH为字段名、数都可以。
		<div class="preline"></div>
		具体方法：<strong>$dosql-&gt;GetArray($id,$acctype)</strong><br />
		方法说明：返回当前的一条记录并把游标移向下一记录，$id可以省略，即为Execute等查询出来的记录集ID。$acctype同上。
		<div class="preline"></div>
		具体方法：<strong>$dosql-&gt;GetTotalRow($id)</strong><br />
		方法说明：获得查询的总记录数，$id可以省略。
		<div class="preline"></div>
		具体方法：<strong>$dosql-&gt;IsTable($tbname)</strong><br />
		方法说明：检测是否存在某数据表。
		<div class="preline"></div>
		具体方法：<strong>$dosql-&gt;GetTableRow($tbname)</strong><br />
		方法说明：获得指定表数据总记录数。</div>
	<div class="preline"></div>
	<div>具体方法：<strong>$dosql-&gt;GetLastID()</strong><br />
		方法说明：获取上一步INSERT操作产生的ID</div>
	<br />
	<h3>- <strong>$dopage</strong> 分页类<br />
		- 所在位置：include/page.class.php和admin/inc/page.class.php；两个文件存在差异，后者面向后台页码由后台性能设置里统一设置，不能随意设置，前者可随意设置；URL参数过滤也不同。</h3>
	<div class="preline"></div>
	<div>具体方法：<strong>$dopage-&gt;GetPage($sql,$pagenum=20)</strong><br />
		方法说明：获取分页信息，将数据序列分页<br />
		参数说明：$sql 必填，执行查询的SQL语句；$pagenum 必填，每页显示的记录数，默认为20。
		<div class="preline"></div>
		具体方法：<strong>$dopage-&gt;GetList()</strong><br />
		参数说明：无参数<br />
		方法说明：将GetPage()结果进行展示，必须和GetPage()同时使用。</div>
</div>
<div class="hr_10"></div>
<div class="uselist">
	<h2>常用函数使用说明</h2>
	<h3>- 前台常用函数<br />
		- 所在位置：include/func.class.php</h3>
	<div>
		<div class="preline"></div>
		函数名称：<strong>Info($cid=0, $num=0, $gourl='')</strong><br />
		参数说明：$cid 单页的ID；$num 显示的字数，可为空；$gourl 跳转的地址，可为空<br />
		函数说明：获取指定的单页内容。
	<div class="preline"></div>
		函数名称：<strong>InfoPic($cid=0)</strong><br />
		参数说明：$cid 单页的ID<br />
		函数说明：获取单页的缩略图地址。
		<div class="preline"></div>
		函数名称：<strong>GetHeader($sid=1,$cid=0,$id=0,$str='')</strong><br />
		参数说明：$sid 当前站点id；$cid 当前页面栏目id；$id 是否为内容页；$str 自定义的头部标题<br />
		函数说明：获取SEO头部信息。
		<div class="preline"></div>
		函数名称：<strong>GetCatName($cid=0)</strong><br />
		参数说明：$cid 栏目id<br />
		函数说明：获取当前栏目名称。
		<div class="preline"></div>
		函数名称：<strong>GetPosStr($cid,$id=0,$sign='&nbsp;&gt;&nbsp;')</strong><br />
		参数说明：$cid 当前页面栏目id, $id 当前页面文章id, $sign 栏目之间分隔符<br />
		函数说明：获取当前页面位置。
		<div class="preline"></div>
		函数名称：<strong>GetFragment($id=0,$t=0)</strong><br />
		参数说明：$id 碎片ID；$t 调用的内容，0为内容，1为标识名称，2为缩略图，3为跳转连接 <br />
		函数说明：碎片数据调用。
		<div class="preline"></div>
		函数名称：<strong>GetQQ()</strong><br />
		参数说明：无参数<br />
		函数说明：获取客服QQ
		<div class="preline"></div>
		函数名称：<strong>GetTopID($str,$i=1)</strong><br />
		参数说明：$str parentstr字符串, $id 获取字符串中的第几位<br />
		函数说明：获取parentstr的第一位</div>
	<div class="preline"></div>
	<h3>- 系统核心函数<br />
		- 所在位置：include/common.func.php</h3>
	<div>
		<div class="preline"></div>
		函数名称：<strong>ReStrLen($str, $len=10, $etc='...')</strong><br />
		参数说明：$str 为要截取的字符串；$len 为要截取的长度；$etc 为截取后显示的样式，可省略，省略后为...。<br />
		函数说明：字符串截取函数。
		<div class="preline"></div>
		函数名称：<strong>GetCurUrl()</strong><br />
		参数说明：无参数<br />
		函数说明：获得当前的页面文件的url。
		<div class="preline"></div>
		函数名称：<strong>GetIP()</strong><br />
		参数说明：无参数<br />
		函数说明：获得当前IP地址。
		<div class="preline"></div>
		函数名称：<strong>GetRealSize($size)</strong><br />
		参数说明：要转换的值，必填<br />
		函数说明：将B单位文件大小转换成KB GB TB。
		<div class="preline"></div>
		函数名称：<strong>GetDirSize($dir)</strong><br />
		参数说明：需要计算的目录，必填<br />
		函数说明：计算给定文件夹的大小。
		<div class="preline"></div>
		函数名称：<strong>MyDate($format, $timest)</strong><br />
		参数说明：$format 可以省略，省略则按Y-m-d H:i:s进行格式化；$timest 必填，格式为时间戳。<br />
		函数说明：返回格林威治标准时。
		<div class="preline"></div>
		函数名称：<strong>GetDateTime($mktime)</strong><br />
		参数说明：$mktime 必填，格式为时间戳。<br />
		函数说明：返回格式化(Y-m-d H:i:s)的时间。
		<div class="preline"></div>
		函数名称：<strong>GetDateMk($mktime)</strong><br />
		参数说明：$mktime 必填，格式为时间戳。<br />
		函数说明：返回格式化(Y-m-d)的日期。
		<div class="preline"></div>
		函数名称：<strong>GetMkTime($dtime)</strong><br />
		参数说明：$dtime 必填，要转换为时间戳的日期。<br />
		函数说明：返回格式化日期的时间戳。
		<div class="preline"></div>
		函数名称：<strong>MkDirs($dir)</strong><br />
		参数说明：$dir 要创建的文件夹目录<br />
		函数说明：创建目录（支持多级）。
		<div class="preline"></div>
		函数名称：<strong>ShowMsg($msg='', $gourl')</strong><br />
		参数说明：$msg 必填，显示要提示的问题；$gourl 可省略，提示后返回的地址，省略后默认返回上一步。<br />
		函数说明：显示提示信息并返回
		<div class="preline"></div>
		函数名称：<strong>Readf($file)</strong><br />
		参数说明：$file 为要读取文件地址。<br />
		函数说明：读取文件内容。
		<div class="preline"></div>
		函数名称：<strong>Writef($file,$string,$mode)</strong><br />
		参数说明：$file 为要写入文件地址；$string 为写入的内容；$mode 为写入模式，可以省略，省略后为直接写入，原内容将被覆盖，也可以改为追加模式(a)。<br />
		函数说明：写入文件内容。
		<div class="preline"></div>
		函数名称：<strong>IsHttpUrl($url)</strong><br />
		参数说明：必填。<br />
		函数说明：判断是否是包含'http://'的url。
		<div class="preline"></div>
		函数名称：<strong>ClearHtml($str)</strong><br />
		参数说明：必填。<br />
		函数说明：清除给定字符串的HTML代码。
		<div class="preline"></div>
		函数名称：<strong>GetRandStr($length=6)</strong><br />
		参数说明：为空则默认为6。<br />
		函数说明：获取指定长度随机字符串。</div>
</div>
<div class="hr_10"></div>
<div class="uselist">
	<h2>目录结构</h2>
	<div>目录名称：<strong>admin/</strong><br />
		目录说明：后台管理目录。存放所有后台文件。<br />
		<span class="sdir">admin/editor/</span>后台编辑器存放目录。<br />
		<span class="sdir">admin/inc/</span>后台公用文件引用目录。<br />
		<span class="sdir">admin/plugin/</span>后台插件存放目录。<br />
		<span class="sdir">admin/templates/</span>资源文件，例如：图片、样式表、Js。<br />
		<div class="preline"></div>
		目录名称：<strong>data/</strong><br />
		目录说明：存放系统相关模块。<br />
		<span class="sdir">data/api/</span>接口文件存放目录。<br />
		<span class="sdir">data/avatar/</span>用户头像上传程序与头像存放目录。<br />
		<span class="sdir">data/backup/</span>数据库备份目录，存放数据库备份文件。<br />
		<span class="sdir">data/captcha/</span>验证码图像文件存放目录，在生成图片验证码失效时会自动显示该目录存放的图片。<br />
		<span class="sdir">data/error/</span>数据库错误日志，可查看系统运行中的数据库错误或非法信息。<br />
		<span class="sdir">data/httpfile/</span>上传、下载文件类存在目录 <br />
		<span class="sdir">data/sessions/</span>session存放目录<br />
		<span class="sdir">data/watermark/</span>水印文件目录<br />
		<div class="preline"></div>
		目录名称：<strong>include/</strong><br />
		目录说明：公用文件引用目录。
		<div class="preline"></div>
		目录名称：<strong>install/</strong><br />
		目录说明：安装文件目录。
		<div class="preline"></div>
		目录名称：<strong>uploads/</strong><br />
		目录说明：文件上传目录。
		<div class="preline"></div>
		目录名称：<strong>templates/</strong><br />
		目录说明：前台资源文件，例如：图片、样式表、Js。</div>
</div>
<div class="hr_10"></div>
<div class="author">
	<h2>作者语</h2>
	<div class="hr_10"></div>
	<p>　　PHPMyWind 前台与后台完全采用PHP代码开发完成。PHPMyWind 主要面向的开发者就是企业建站行业的同学们。我们需要的是简单的，快速的，稳定的，拆分性较强的CMS。可能每个站只有几个栏目，亦或网站功能较为特殊需要定制。所以，对模块化要求极强。因此，我们总结上述几点是企业建站开发者们最需要的几点需求，以此为突破口，开发过程中时刻遵循于此。我们希望，开发者在开发的过程中感觉快速、舒适，这是我们的追求。</p>
	<div class="hr_10"></div>
	<p>　　PHPMyWind 从2010年开发至今已有4年时间，其间已经过4次重写。结构，代码写法逐步成熟。系统内置多个类以及函数，大幅提升开发速度。如目前对系统类使用不惯得同学们，也可以采用PHP内置原生函数开发。每个前台页面保证在头部调用 '/include/config.inc.php' 即可初始化系统代码，进行开发工作。前台只作为示例展示，与后台关联程度不高，但也可直接使用。目前您可视PHPMyWind为一款CMS核心，在它之上制作丰富的网站。</p>
	<div class="hr_10"></div>
	<p>　　更多关于我们的信息可访问 <a href="http://phpmywind.com/" target="_blank">http://phpmywind.com</a></p>
</div>
<div class="footer">© 2015 phpMyWind.com</div>
</body>
</html>
