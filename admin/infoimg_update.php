<?php require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('infoimg'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>修改图片信息</title>
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
<?php
$row = $dosql->GetOne("SELECT * FROM `#@__infoimg` WHERE `id`=$id");
?>
<div class="formHeader"> <span class="title">修改图片信息</span> <a href="javascript:location.reload();" class="reload">刷新</a> </div>
<form name="form" id="form" method="post" action="infoimg_save.php" onsubmit="return cfm_infolm();">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="formTable">
		<tr>
			<td width="25%" height="40" align="right">栏　目：</td>
			<td width="75%"><select name="classid" id="classid">
					<option value="-1">请选择所属栏目</option>
					<?php CategoryType(2); ?>
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
					<?php GetAllType('#@__maintype','#@__infoimg','mainid'); ?>
				</select>
				<span class="maroon">*</span></td>
		</tr>
		<?php
		}
		?>
		<tr>
			<td height="40" align="right">标　题：</td>
			<td><input type="text" name="title" id="title" class="input" value="<?php echo $row['title']; ?>" <?php echo 'style="color:'.$row['colorval'].';font-weight:'.$row['boldval'].';"'; ?> />
				<span class="maroon">*</span>
				<div class="titlePanel">
					<input type="hidden" name="colorval" id="colorval" value="<?php echo $row['colorval']; ?>" />
					<input type="hidden" name="boldval" id="boldval" value="<?php echo $row['boldval']; ?>" />
					<span onclick="colorpicker('colorpanel','colorval','title');" class="color" title="标题颜色"> </span> <span id="colorpanel"></span> <span onclick="blodpicker('boldval','title');" class="blod" title="标题加粗"> </span> <span onclick="clearpicker()" class="clear" title="清除属性">[#]</span> &nbsp; </div></td>
		</tr>
		<tr class="nb">
			<td height="40" align="right">属　性：</td>
			<td class="attrArea"><?php
			$flagarr = explode(',',$row['flag']);

			$dosql->Execute("SELECT * FROM `#@__infoflag` ORDER BY orderid ASC");
			while($r = $dosql->GetArray())
			{
				echo '<span><input type="checkbox" name="flag[]" id="flag[]" value="'.$r['flag'].'"';

				if(in_array($r['flag'],$flagarr))
				{
					echo 'checked="checked"';
				}

				echo ' />'.$r['flagname'].'['.$r['flag'].']</span>';
			}
			?></td>
		</tr>
		<tr class="nb">
			<td colspan="2" height="26"><div class="line"> </div></td>
		</tr>
		<tr class="nb">
			<td colspan="2" height="0" id="df"><?php
			echo GetDiyField('2',$row['classid'],$row);
			?></td>
		</tr>
		<tr>
			<td height="40" align="right">文章来源：</td>
			<td><input type="text" name="source" id="source" class="input" value="<?php echo $row['source']; ?>" />
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
			<td><input type="text" name="author" id="author" class="input" value="<?php echo $row['author']; ?>" /></td>
		</tr>
		<tr>
			<td height="40" align="right">缩略图片：</td>
			<td><input type="text" name="picurl" id="picurl" class="input" value="<?php echo $row['picurl']; ?>" />
				<span class="cnote"><span class="grayBtn" onclick="GetUploadify('uploadify','缩略图上传','image','image',1,<?php echo $cfg_max_file_size; ?>,'picurl')">上 传</span> <span class="rePicTxt">
				<input type="checkbox" name="rempic" id="rempic" value="true" />
				远程</span> <span class="cutPicTxt"><a href="javascript:;" onclick="GetJcrop('jcrop','picurl');return false;">裁剪</a></span> </span></td>
		</tr>
		<tr>
			<td height="40" align="right">跳转链接：</td>
			<td><input type="text" name="linkurl" id="linkurl" class="input" value="<?php echo $row['linkurl']; ?>" /></td>
		</tr>
		<tr>
			<td height="40" align="right">关键词：</td>
			<td><input type="text" name="keywords" id="keywords" class="input" value="<?php echo $row['keywords']; ?>" />
				<span class="cnote">多关键词之间用空格或者“,”隔开</span></td>
		</tr>
		<tr>
			<td height="104" align="right">摘　要：</td>
			<td><textarea name="description" id="description" class="textdesc"><?php echo $row['description']; ?></textarea>
				<div class="hr_5"></div>
				最多能输入 <strong>255</strong> 个字符 </td>
		</tr>
		<tr>
			<td height="340" align="right">详细内容：</td>
			<td><textarea name="content" id="content" class="kindeditor"><?php echo $row['content']; ?></textarea>
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
					<input type="text" name="autodescsize" id="autodescsize" value="200" size="3" class="inputls" />
					字到摘要
					&nbsp;
					<input type="checkbox" name="autopage" id="autopage" value="true" />
					自动分页
					<input type="text" name="autopagesize" id="autopagesize" value="5" size="6" class="inputls" />
					KB</div></td>
		</tr>
		<tr class="nb">
			<td height="124" align="right">组　图：</td>
			<td><fieldset class="picarr">
					<legend>列表</legend>
					<div>最多可以上传<strong>50</strong>张图片<span onclick="GetUploadify('uploadify2','组图上传','image','image',50,<?php echo $cfg_max_file_size; ?>,'picarr','picarr_area')">开始上传</span></div>
					<ul id="picarr_area">
						<?php
					if($row['picarr'] != '')
					{
						$picarr = unserialize($row['picarr']);
						foreach($picarr as $v)
						{
							$v = explode(',', $v);
							echo '<li rel="'.$v[0].'"><input type="text" name="picarr[]" value="'.$v[0].'"><a href="javascript:void(0);" onclick="ClearPicArr(\''.$v[0].'\')">删除</a><br /><input type="text" name="picarr_txt[]" value="'.$v[1].'"><span>描述</span></li>';
						}
					}
					?>
					</ul>
				</fieldset></td>
		</tr>
		<tr class="nb">
			<td colspan="2" height="26"><div class="line"> </div></td>
		</tr>
		<tr>
			<td height="40" align="right">点击次数：</td>
			<td><input type="text" name="hits" id="hits" class="inputos" value="<?php echo $row['hits']; ?>" /></td>
		</tr>
		<tr>
			<td height="40" align="right">排列排序：</td>
			<td><input type="text" name="orderid" id="orderid" class="inputos" value="<?php echo $row['orderid']; ?>" /></td>
		</tr>
		<tr>
			<td height="40" align="right">更新时间：</td>
			<td><input name="posttime" type="text" id="posttime" class="inputms" value="<?php echo GetDateTime($row['posttime']); ?>" readonly="readonly" />
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
			<td><input type="radio" name="checkinfo" value="true" <?php if($row['checkinfo'] == 'true') echo 'checked="checked"'; ?> />
				是 &nbsp;
				<input type="radio" name="checkinfo" value="false" <?php if($row['checkinfo'] == 'false') echo 'checked="checked"'; ?> />
				否<span class="cnote">选择“否”则该信息暂时不显示在前台</span></td>
		</tr>
	</table>
	<div class="formSubBtn">
		<input type="submit" class="submit" value="提交" />
		<input type="button" class="back" value="返回" onclick="history.go(-1);" />
		<input type="hidden" name="action" id="action" value="update" />
		<input type="hidden" name="cid" id="cid" value="<?php echo $row['classid']; ?>" />
		<input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
	</div>
</form>
</body>
</html>