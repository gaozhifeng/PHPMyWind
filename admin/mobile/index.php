<?php	if(!defined('IN_MOBILE')) exit('Request Error!'); ?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
<title>PHPMyWind 管理中心</title>
<link href="mobile/templates/style/mobile.css" rel="stylesheet" type="text/css" />
<script src="mobile/templates/js/jquery.min.js"></script>
<script src="mobile/templates/js/frame.js"></script>
</head>

<body>
<?php require_once('header.php'); ?>
<div class="mobileBody">
    <div class="homeSite">
        <h2 class="title">系统</h2>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td height="30" colspan="2">软件版本号： <span title="<?php echo $cfg_vertime; ?>"><?php echo $cfg_vernum; ?></span></td>
            </tr>
            <tr>
                <td width="60%" height="30">服务器版本： <span title="<?php echo $_SERVER['SERVER_SOFTWARE']; ?>"><?php echo ReStrLen($_SERVER['SERVER_SOFTWARE'],7,''); ?></span></td>
                <td width="40%">操作系统： <?php echo PHP_OS; ?></td>
            </tr>
            <tr>
                <td height="30">PHP版本号： <?php echo PHP_VERSION; ?></td>
                <td>GDLibrary： <?php echo ShowResult(function_exists('imageline')); ?></td>
            </tr>
            <tr>
                <td height="30">MySql版本： <?php echo $dosql->GetVersion(); ?></td>
                <td height="28">ZEND支持： <?php echo ShowResult(function_exists('zend_version')); ?></td>
            </tr>
            <tr class="nb">
                <td height="30" colspan="2">支持上传的最大文件：<?php echo ini_get('upload_max_filesize'); ?></td>
            </tr>
        </table>
    </div>
    <div class="homeCount">
    	<h2 class="title">统计</h2>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="30%" height="30">网站栏目数：</td>
                <td width="30%" class="num"><?php echo $dosql->GetTableRow('#@__infoclass',$cfg_siteid); ?></td>
                <td width="30%" class="num">列表信息数：</td>
                <td width="10%" class="num"><?php echo $dosql->GetTableRow('#@__infolist',$cfg_siteid); ?></td>
            </tr>
            <tr>
                <td height="30">单页信息数：</td>
                <td class="num"><?php
						$dosql->Execute("SELECT `id` FROM `#@__infoclass` WHERE `siteid`='$cfg_siteid' AND `infotype`=0");
						echo $dosql->GetTotalRow();
						?></td>
                <td class="num">图片信息数：</td>
                <td class="num"><?php echo $dosql->GetTableRow('#@__infoimg',$cfg_siteid); ?></td>
            </tr>
        </table>
    </div>
    <div class="homeEvent">
        <h2 class="title">日志</h2>
        <ul>
            <?php
		$sql = "SELECT * FROM `#@__sysevent` ORDER BY `id` DESC LIMIT 0,5";

		$dosql->Execute($sql);
		while($row = $dosql->GetArray())
		{
			$r = $dosql->GetOne("SELECT `sitename` FROM `#@__site` WHERE `id`=".$row['siteid']);
			if(empty($r)) $r['sitename'] = '未知站点';
			
			if($row['model'] == 'login')
			{
		?>
            <li><?php echo MyDate('m-d H:i',$row['posttime']); ?>： 用户 <strong><?php echo $row['uname']; ?></strong> 进行了 <span class="blue">登录操作</span> </li>
            <?php
			}
	
			else if($row['model'] == 'logout')
			{
		?>
            <li> <?php echo MyDate('m-d H:i',$row['posttime']); ?>： 用户 <strong><?php echo $row['uname']; ?></strong> 进行了 <span class="blue">退出操作</span> </li>
            <?php
			}
			else if($row['classid'] != 0)
			{
				$r2 = $dosql->GetOne("SELECT `classname` FROM `#@__infoclass` WHERE `id`=".$row['classid']);
				
				if($row['action'] == 'add')
					$action = '添加';
				else if($row['action'] == 'update')
					$action = '修改';
				else if($row['action'] == 'del')
					$action = '删除';
				else
					$action = '';
		?>
            <li><?php echo MyDate('m-d H:i',$row['posttime']); ?>：用户 <strong><?php echo $row['uname']; ?></strong> 在 <span class="maroon2"><?php echo $r['sitename']; ?></span> <?php echo $action; ?>了 <span class="blue"><?php echo $r2['classname']; ?></span> </li>
            <?php
			}
			else
			{
			?>
            <li> <?php echo MyDate('m-d H:i',$row['posttime']); ?>： 用户 <strong><?php echo $row['uname']; ?></strong> 在 <span class="maroon2"><?php echo $r['sitename']; ?></span> 操作了 <span class="blue"><?php echo $row['model']; ?></span> </li>
            <?php
			}
		}
		?>
        </ul>
    </div>
</div>
<?php require_once('footer.php'); ?>
<?php
function ShowResult($revalue)
{
	if($revalue == 1)
		return '<span class="ture">支持</span>';
	else
		return '<span class="flase">不支持</span>';
}
?>
</body>
</html>