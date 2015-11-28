<?php require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('soft'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>添加软件信息</title>
<link href="templates/style/admin.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/js/getuploadify.js"></script>
<script type="text/javascript" src="templates/js/checkf.func.js"></script>
<script type="text/javascript" src="templates/js/getjcrop.js"></script>
<script type="text/javascript" src="templates/js/getinfosrc.js"></script>
<script type="text/javascript" src="plugin/colorpicker/colorpicker.js"></script>
<script type="text/javascript" src="plugin/calendar/calendar.js"></script>
<script type="text/javascript" src="editor/kindeditor-min.js"></script>
<script type="text/javascript" src="editor/lang/zh_CN.js"></script>
</head>
<body>
<div class="formHeader"> <span class="title">软件信息添加</span> <a href="javascript:location.reload();" class="reload">刷新</a> </div>
<form name="form" id="form" method="post" action="soft_save.php" onsubmit="return cfm_infolm();">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="formTable">
		<tr>
			<td width="25%" height="40" align="right">栏　目：</td>
			<td width="75%"><select name="classid" id="classid">
					<option value="-1">请选择所属栏目</option>
					<?php CategoryType(3); ?>
				</select>
				<span class="maroon">*</span><span class="cnote">带<span class="maroon">*</span>号表示为必填项</span></td>
		</tr>
		<?php
		if($cfg_maintype == 'Y')
		{
		?>
		<tr>
			<td height="40" align="right">类　别：</td>
			<td><select name="mainid" id="mainid">
					<option value="-1">请选择所属类别</option>
					<?php GetAllType('#@__maintype','#@__soft','mainid'); ?>
				</select>
				<span class="maroon">*</span></td>
		</tr>
		<?php
		}
		?>
		<tr>
			<td height="40" align="right">软件名称：</td>
			<td><input type="text" name="title" id="title" class="input" />
				<span class="maroon">*</span>
				<div class="titlePanel">
					<input type="hidden" name="colorval" id="colorval" />
					<input type="hidden" name="boldval" id="boldval" />
					<span onclick="colorpicker('colorpanel','colorval','title');" class="color" title="标题颜色"> </span> <span id="colorpanel"></span> <span onclick="blodpicker('boldval','title');" class="blod" title="标题加粗"> </span> <span onclick="clearpicker()" class="clear" title="清除属性">[#]</span> &nbsp; </div></td>
		</tr>
		<tr class="nb">
			<td height="40" align="right">属　性：</td>
			<td class="attrArea"><?php
			$dosql->Execute("SELECT * FROM `#@__infoflag` ORDER BY orderid ASC");
			while($row = $dosql->GetArray())
			{
				echo '<span><input type="checkbox" name="flag[]" id="flag[]" value="'.$row['flag'].'" />'.$row['flagname'].'['.$row['flag'].']</span>';
			}
			?></td>
		</tr>
		<tr class="nb">
			<td colspan="2" height="26"><div class="line"> </div></td>
		</tr>
		<tr class="nb">
			<td colspan="2" height="0" id="df"><?php if(isset($cid)) echo GetDiyField(3,$cid); ?></td>
		</tr>
		<tr>
			<td height="40" align="right">文件类型：</td>
			<td><select name="filetype" id="filetype">
					<option value=".exe">.exe</option>
					<option value=".zip">.zip</option>
					<option value=".rar">.rar</option>
					<option value=".iso">.iso</option>
					<option value=".gz">.gz</option>
					<option value="其它">其它</option>
				</select></td>
		</tr>
		<tr>
			<td height="40" align="right">软件类型：</td>
			<td><select name="softtype" id="softtype">
					<option value="国产软件">国产软件</option>
					<option value="国外软件">国外软件</option>
					<option value="汉化补丁">汉化补丁</option>
				</select></td>
		</tr>
		<tr>
			<td height="40" align="right">界面语言：</td>
			<td><select name="language" id="language">
					<option value="简体中文">简体中文</option>
					<option value="英文软件">英文软件</option>
					<option value="繁体中文">繁体中文</option>
					<option value="其它类型">其它类型</option>
				</select></td>
		</tr>
		<tr>
			<td height="40" align="right">授权方式：</td>
			<td><select name="accredit" id="accredit">
					<option value="共享软件">共享软件</option>
					<option value="免费软件">免费软件</option>
					<option value="开源软件">开源软件</option>
					<option value="商业软件">商业软件</option>
					<option value="破解软件">破解软件</option>
					<option value="游戏外挂">游戏外挂</option>
				</select></td>
		</tr>
		<tr>
			<td height="40" align="right">软件大小：</td>
			<td><input type="text" name="softsize" id="softsize" class="input" />
				<select name="unit" id="unit">
					<option value="MB" selected>MB</option>
					<option value="KB">KB</option>
					<option value="GB">GB</option>
				</select></td>
		</tr>
		<tr>
			<td height="40" align="right">运行环境：</td>
			<td><input type="text" name="runos" id="runos" class="input" value="Win7/WinXP/WinNT" /></td>
		</tr>
		<tr>
			<td height="40" align="right">官方网站：</td>
			<td><input type="text" name="website" id="website" class="input" /></td>
		</tr>
		<tr>
			<td height="40" align="right">演示地址：</td>
			<td><input type="text" name="demourl" id="demourl" class="input" /></td>
		</tr>
		<tr>
			<td height="40" align="right">下载地址：</td>
			<td><input type="text" name="dlurl" id="dlurl" class="input" />
				<span class="cnote"><span class="grayBtn" onclick="GetUploadify('uploadify','软件上传','soft','soft',1,<?php echo $cfg_max_file_size; ?>0,'dlurl')">上 传</span> </span></td>
		</tr>
		<tr>
			<td height="40" align="right">文章来源：</td>
			<td><input type="text" name="source" id="source" class="input" />
				<span class="srcArea"> <span class="infosrc">选择
				<ul>
					<?php
					$dosql->Execute("SELECT * FROM `#@__infosrc` ORDER BY `orderid` ASC");
					if($dosql->GetTotalRow() > 0)
					{
						while($row2 = $dosql->GetArray())
						{
					?>
					<li><a href="javascript:;" title="<?php echo $row2['linkurl']; ?>" onclick="GetSrcName('<?php echo $row2['srcname']; ?>');"><?php echo $row2['srcname']; ?></a></li>
					<?php
						}
					}
					else
					{
						echo '<li>暂无来源 <a href="infosrc.php">[添加]</a></li>';
					}
					?>
				</ul>
				</span> </span></td>
		</tr>
		<tr>
			<td height="40" align="right">作者编辑：</td>
			<td><input type="text" name="author" id="author" class="input" value="<?php echo GetAuthor(); ?>" /></td>
		</tr>
		<tr>
			<td height="40" align="right">缩略图片：</td>
			<td><input type="text" name="picurl" id="picurl" class="input" />
				<span class="cnote"><span class="grayBtn" onclick="GetUploadify('uploadify','缩略图上传','image','image',1,<?php echo $cfg_max_file_size; ?>,'picurl')">上 传</span> <span class="rePicTxt">
				<input type="checkbox" name="rempic" id="rempic" value="true" />
				远程</span> <span class="cutPicTxt"><a href="javascript:;" onclick="GetJcrop('jcrop','picurl');return false;">裁剪</a></span> </span></td>
		</tr>
		<tr>
			<td height="40" align="right">跳转链接：</td>
			<td><input type="text" name="linkurl" id="linkurl" class="input" /></td>
		</tr>
		<tr>
			<td height="40" align="right">关键词：</td>
			<td><input type="text" name="keywords" id="keywords" class="input" />
				<span class="cnote">多关键词之间用空格或者“,”隔开</span></td>
		</tr>
		<tr>
			<td height="104" align="right">摘　要：</td>
			<td><textarea name="description" id="description" class="textdesc"></textarea>
				<div class="hr_5"></div>
				最多能输入 <strong>255</strong> 个字符 </td>
		</tr>
		<tr>
			<td height="340" align="right">详细内容：</td>
			<td><textarea name="content" id="content" class="kindeditor"></textarea>
				<script>
				var editor;
				KindEditor.ready(function(K) {
					editor = K.create('textarea[name="content"]', {
						allowFileManager : true,
						width:'667px',
						height:'280px',
						extraFileUploadParams : {
							sessionid :  '<?php echo session_id(); ?>'
						}
					});
				});
				</script>
				<div class="editToolbar">
					<input type="checkbox" name="remote" id="remote" value="true" />
					下载远程图片和资源
					&nbsp;
					<input type="checkbox" name="autothumb" id="autothumb" value="true" />
					提取第一个图片为缩略图
					&nbsp;
					<input type="checkbox" name="autodesc" id="autodesc" value="true" />
					提取
					<input type="text" name="autodescsize" id="autodescsize" class="inputls" value="200" size="3" />
					字到摘要
					&nbsp;
					<input type="checkbox" name="autopage" id="autopage" value="true" />
					自动分页
					<input type="text" name="autopagesize" id="autopagesize" class="inputls" value="5" size="6" />
					KB</div></td>
		</tr>
		<tr class="nb">
			<td height="124" align="right">组　图：</td>
			<td><fieldset class="picarr">
					<legend>列表</legend>
					<div>最多可以上传<strong>50</strong>张图片<span onclick="GetUploadify('uploadify2','组图上传','image','image',50,<?php echo $cfg_max_file_size; ?>,'picarr','picarr_area')">开始上传</span></div>
					<ul id="picarr_area">
					</ul>
				</fieldset></td>
		</tr>
		<tr class="nb">
			<td colspan="2" height="26"><div class="line"> </div></td>
		</tr>
		<tr>
			<td height="40" align="right">点击次数：</td>
			<td><input type="text" name="hits" id="hits" class="inputos" value="<?php echo mt_rand(50, 200); ?>" /></td>
		</tr>
		<tr>
			<td height="40" align="right">排列排序：</td>
			<td><input type="text" name="orderid" id="orderid" class="inputos" value="<?php echo GetOrderID('#@__soft'); ?>" /></td>
		</tr>
		<tr>
			<td height="40" align="right">更新时间：</td>
			<td><input name="posttime" type="text" id="posttime" class="inputms" value="<?php echo GetDateTime(time()); ?>" readonly="readonly" />
				<script type="text/javascript">
				date = new Date();
				Calendar.setup({
					inputField     :    "posttime",
					ifFormat       :    "%Y-%m-%d %H:%M:%S",
					showsTime      :    true,
					timeFormat     :    "24"
				});
				</script></td>
		</tr>
		<tr class="nb">
			<td height="40" align="right">审　核：</td>
			<td><input type="radio" name="checkinfo" value="true" checked="checked"  />
				是 &nbsp;
				<input type="radio" name="checkinfo" value="false" />
				否<span class="cnote">选择“否”则该信息暂时不显示在前台</span></td>
		</tr>
	</table>
	<div class="formSubBtn">
		<input type="submit" class="submit" value="提交" />
		<input type="button" class="back" value="返回" onclick="history.go(-1);" />
		<input type="hidden" name="action" id="action" value="add" />
		<input type="hidden" name="cid" id="cid" value="<?php echo ($cid = isset($cid) ? $cid : ''); ?>" />
	</div>
</form>
</body>
</html>