<?php if(!defined('IN_MEMBER')) exit('Request Error!'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $cfg_webname; ?> - 会员中心 - 编辑资料</title>
<link href="<?php echo $cfg_webpath; ?>/templates/default/style/member.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo $cfg_webpath; ?>/templates/default/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $cfg_webpath; ?>/templates/default/js/member.js"></script>
</head>

<body>
<div class="header">
	<?php require_once(dirname(__FILE__).'/header.php'); ?>
</div>
<div class="mainbody">
	<div class="leftarea">
		<?php require_once(dirname(__FILE__).'/lefter.php'); ?>
	</div>
	<div class="rightarea">
		<form name="form" id="form" method="post" action="?a=saveedit" onsubmit="return cfm_upmember();">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td colspan="2" height="26"><h3 class="subtitle">账号设置</h3></td>
				</tr>
				<tr>
					<td width="20%" height="40" align="right">旧密码：</td>
					<td width="80%"><input name="oldpassword" type="password" id="oldpassword" class="class_input" /></td>
				</tr>
				<tr>
					<td width="20%" height="40" align="right">新密码：</td>
					<td width="80%"><input name="password" type="password" id="password" class="class_input" /></td>
				</tr>
				<tr>
					<td height="40" align="right">确　认： </td>
					<td><input name="repassword" type="password" id="repassword" class="class_input" /></td>
				</tr>
				<tr>
					<td height="40" align="right">提　问： </td>
					<td><select name="question" id="question">
						<?php
						$dosql->Execute("SELECT * FROM `#@__cascadedata` WHERE `datagroup`='question' AND level=0 ORDER BY orderid ASC, datavalue ASC");
						while($row = $dosql->GetArray())
						{
							if($r_user['question'] == $row['datavalue'])
								$selected = 'selected="selected"';
							else
								$selected = '';
		
							echo '<option value="'.$row['datavalue'].'" '.$selected.'>'.$row['dataname'].'</option>';
						}
						?>
						</select></td>
				</tr>
				<tr>
					<td height="40" align="right">回　答： </td>
					<td><input type="text" name="answer" id="answer" class="class_input" value="<?php echo $r_user['answer']; ?>" /></td>
				</tr>
				<tr>
					<td height="30" colspan="2"></td>
				</tr>
				<tr>
					<td colspan="2" height="26"><h3 class="subtitle">个人资料</h3></td>
				</tr>
				<tr>
					<td height="40" align="right">姓　名：</td>
					<td><input type="text" name="cnname" id="cnname" class="class_input" value="<?php echo $r_user['cnname']; ?>" /></td>
				</tr>
				<tr>
					<td height="40" align="right">英文名：</td>
					<td><input type="text" name="enname" id="enname" class="class_input" value="<?php echo $r_user['enname']; ?>" /></td>
				</tr>
				<tr>
					<td height="40" align="right">性　别：</td>
					<td><input name="sex" type="radio" value="0" <?php if($r_user['sex'] == '0') echo 'checked="checked"'; ?> />
						男&nbsp;
						<input name="sex" type="radio" value="1" <?php if($r_user['sex'] == '1') echo 'checked="checked"'; ?> />
						女</td>
				</tr>
				<tr>
					<td height="40" align="right">生　日：</td>
					<td><label>
							<input type="radio" name="birthtype" value="0" <?php if($r_user['birthtype'] == '0') echo 'checked="checked"'; ?> />
							公历生日 </label>
						<label>
							<input type="radio" name="birthtype" value="1" <?php if($r_user['birthtype'] == '1') echo 'checked="checked"'; ?> />
							农历生日</label></td>
				</tr>
				<tr>
					<td height="40" align="right">&nbsp;</td>
					<td><select name="birth_year" id="birth_year">
							<option value="-1">请选择</option>
							<?php
							$nowyear = MyDate('Y',time());
							for($nowyear; $nowyear>=1900; $nowyear--)
							{
								if($r_user['birth_year'] == $nowyear)
									$selected = 'selected="selected"';
								else
									$selected = '';
							?>
							<option value="<?php echo $nowyear; ?>" <?php echo $selected; ?>><?php echo $nowyear; ?></option>
							<?php
							}
							?>
						</select>
						年
						<select name="birth_month" id="birth_month">
							<option value="-1">--</option>
							<?php
							for($nowmonth=1; $nowmonth<=12; $nowmonth++)
							{
								if($nowmonth <= 9) $nowmonth = '0'.$nowmonth;
		
								if($r_user['birth_month'] == $nowmonth)
									$selected = 'selected="selected"';
								else
									$selected = '';
							?>
							<option value="<?php echo $nowmonth; ?>" <?php echo $selected; ?>><?php echo $nowmonth; ?></option>
							<?php
							}
							?>
						</select>
						月
						<select name="birth_day" id="birth_day">
							<option value="-1">--</option>
							<?php
							for($nowday=1; $nowday<=31; $nowday++)
							{
								if($nowday < 10) $nowday = '0'.$nowday;
		
								if($r_user['birth_day'] == $nowday)
									$selected = 'selected="selected"';
								else
									$selected = '';
							?>
							<option value="<?php echo $nowday; ?>" <?php echo $selected; ?>><?php echo $nowday; ?></option>
							<?php
							}
							?>
						</select>
						日</td>
				</tr>
				<tr>
					<td height="40" align="right">星　座：</td>
					<td><select name="astro" id="astro">
							<option value="-1">请选择</option>
							<?php
							$dosql->Execute("SELECT * FROM `#@__cascadedata` WHERE `datagroup`='astro' AND level=0 ORDER BY orderid ASC, datavalue ASC");
							while($row = $dosql->GetArray())
							{
								if($r_user['astro'] == $row['datavalue'])
									$selected = 'selected="selected"';
								else
									$selected = '';
		
								echo '<option value="'.$row['datavalue'].'" '.$selected.'>'.$row['dataname'].'</option>';
							}
							?>
						</select></td>
				</tr>
				<tr>
					<td height="40" align="right">血　型：</td>
					<td><select name="bloodtype" id="bloodtype">
							<option value="-1">请选择</option>
							<?php
							$dosql->Execute("SELECT * FROM `#@__cascadedata` WHERE `datagroup`='bloodtype' AND level=0 ORDER BY orderid ASC, datavalue ASC");
							while($row = $dosql->GetArray())
							{
								if($r_user['bloodtype'] == $row['datavalue'])
									$selected = 'selected="selected"';
								else
									$selected = '';
		
								echo '<option value="'.$row['datavalue'].'" '.$selected.'>'.$row['dataname'].'</option>';
							}
							?>
						</select></td>
				</tr>
				<tr>
					<td height="40" align="right">行　业：</td>
					<td><select name="trade" id="trade">
							<option value="-1">请选择</option>
							<?php
							$dosql->Execute("SELECT * FROM `#@__cascadedata` WHERE `datagroup`='trade' AND level=0 ORDER BY orderid ASC, datavalue ASC");
							while($row = $dosql->GetArray())
							{
								if($r_user['trade'] == $row['datavalue'])
									$selected = 'selected="selected"';
								else
									$selected = '';
		
								echo '<option value="'.$row['datavalue'].'" '.$selected.'>'.$row['dataname'].'</option>';
							}
							?>
						</select></td>
				</tr>
				<tr>
					<td height="40" align="right"> 现居地：</td>
					<td><select name="live_prov" id="live_prov" onchange="SelProv(this.value,'live');">
							<option value="-1">请选择</option>
							<?php
							$dosql->Execute("SELECT * FROM `#@__cascadedata` WHERE `datagroup`='area' AND level=0 ORDER BY orderid ASC, datavalue ASC");
							while($row = $dosql->GetArray())
							{
								if($r_user['live_prov'] === $row['datavalue'])
									$selected = 'selected="selected"';
								else
									$selected = '';
		
								echo '<option value="'.$row['datavalue'].'" '.$selected.'>'.$row['dataname'].'</option>';
							}
							?>
						</select>
						<select name="live_city" id="live_city" onchange="SelCity(this.value,'live');">
							<option value="-1">--</option>
							<?php
							$dosql->Execute("SELECT * FROM `#@__cascadedata` WHERE `datagroup`='area' AND level=1 AND datavalue>".$r_user['live_prov']." AND datavalue<".($r_user['live_prov'] + 500)." ORDER BY orderid ASC, datavalue ASC");
							while($row = $dosql->GetArray())
							{
								if($r_user['live_city'] === $row['datavalue'])
									$selected = 'selected="selected"';
								else
									$selected = '';
		
								echo '<option value="'.$row['datavalue'].'" '.$selected.'>'.$row['dataname'].'</option>';
							}
							?>
						</select>
						<select name="live_country" id="live_country">
							<option value="-1">--</option>
							<?php
							$dosql->Execute("SELECT * FROM `#@__cascadedata` WHERE `datagroup`='area' AND level=2 AND datavalue LIKE '".$r_user['live_city'].".%%%' ORDER BY orderid ASC, datavalue ASC");
							while($row = $dosql->GetArray())
							{
								if($r_user['live_country'] === $row['datavalue'])
									$selected = 'selected="selected"';
								else
									$selected = '';
		
								echo '<option value="'.$row['datavalue'].'" '.$selected.'>'.$row['dataname'].'</option>';
							}
							?>
						</select></td>
				</tr>
				<tr>
					<td height="40" align="right">家　乡：</td>
					<td><select name="home_prov" id="home_prov" onchange="SelProv(this.value,'home');">
							<option value="-1">请选择</option>
							<?php
							$dosql->Execute("SELECT * FROM `#@__cascadedata` WHERE `datagroup`='area' AND level=0 ORDER BY orderid ASC, datavalue ASC");
							while($row = $dosql->GetArray())
							{
								if($r_user['live_prov'] === $row['datavalue'])
									$selected = 'selected="selected"';
								else
									$selected = '';
		
								echo '<option value="'.$row['datavalue'].'" '.$selected.'>'.$row['dataname'].'</option>';
							}
							?>
						</select>
						<select name="home_city" id="home_city" onchange="SelCity(this.value,'home');">
							<option value="-1">--</option>
							<?php
							$dosql->Execute("SELECT * FROM `#@__cascadedata` WHERE `datagroup`='area' AND level=1 AND datavalue>".$r_user['home_prov']." AND datavalue<".($r_user['home_prov'] + 500)." ORDER BY orderid ASC, datavalue ASC");
							while($row = $dosql->GetArray())
							{
								if($r_user['home_city'] === $row['datavalue'])
									$selected = 'selected="selected"';
								else
									$selected = '';
		
								echo '<option value="'.$row['datavalue'].'" '.$selected.'>'.$row['dataname'].'</option>';
							}
							?>
						</select>
						<select name="home_country" id="home_country">
							<option value="-1">--</option>
							<?php
							$dosql->Execute("SELECT * FROM `#@__cascadedata` WHERE `datagroup`='area' AND level=2 AND datavalue LIKE '".$r_user['home_city'].".%%%' ORDER BY orderid ASC, datavalue ASC");
							while($row = $dosql->GetArray())
							{
								if($r_user['home_country'] === $row['datavalue'])
									$selected = 'selected="selected"';
								else
									$selected = '';
		
								echo '<option value="'.$row['datavalue'].'" '.$selected.'>'.$row['dataname'].'</option>';
							}
							?>
						</select></td>
				</tr>
				<tr>
					<td height="40" align="right">证件号码：</td>
					<td><select name="cardtype" id="cardtype">
							<option value="-1">请选择</option>
							<option value="0" <?php if($r_user['cardtype'] == '0') echo 'selected="selected"'; ?>>身份证</option>
							<option value="1" <?php if($r_user['cardtype'] == '1') echo 'selected="selected"'; ?>>护照号</option>
							<option value="2" <?php if($r_user['cardtype'] == '2') echo 'selected="selected"'; ?>>驾驶证</option>
						</select>
						<input type="text" name="cardnum" id="cardnum" class="class_input" style="width:250px" value="<?php echo $r_user['cardnum']; ?>" /></td>
				</tr>
				<tr>
					<td height="116" align="right">个人说明：</td>
					<td><textarea name="intro" id="intro" class="class_areatext"><?php echo $r_user['intro']; ?></textarea></td>
				</tr>
				<tr>
					<td height="30" colspan="2"></td>
				</tr>
				<tr>
					<td colspan="2" height="26"><h3 class="subtitle">联系方式</h3></td>
				</tr>
				<tr>
					<td height="40" align="right">E-mail：</td>
					<td><input type="text" name="email" id="email" class="class_input" value="<?php echo $r_user['email']; ?>" /></td>
				</tr>
				<tr>
					<td height="40" align="right">QQ号码：</td>
					<td><input type="text" name="qqnum" id="qqnum" class="class_input" value="<?php echo $r_user['qqnum']; ?>" /></td>
				</tr>
				<tr>
					<td height="40" align="right">手　机：</td>
					<td><input type="text" name="mobile" id="mobile" class="class_input" value="<?php echo $r_user['mobile']; ?>" /></td>
				</tr>
				<tr>
					<td height="40" align="right">固定电话：</td>
					<td><input type="text" name="telephone" id="telephone" class="class_input" value="<?php echo $r_user['telephone']; ?>" /></td>
				</tr>
				<tr>
					<td height="40" align="right">通信地址：</td>
					<td><select name="address_prov" id="address_prov" onchange="SelProv(this.value,'address');">
							<option value="-1">请选择</option>
							<?php
							$dosql->Execute("SELECT * FROM `#@__cascadedata` WHERE `datagroup`='area' AND level=0 ORDER BY orderid ASC, datavalue ASC");
							while($row = $dosql->GetArray())
							{
								if($r_user['address_prov'] === $row['datavalue'])
									$selected = 'selected="selected"';
								else
									$selected = '';
		
								echo '<option value="'.$row['datavalue'].'" '.$selected.'>'.$row['dataname'].'</option>';
							}
							?>
						</select>
						<select name="address_city" id="address_city" onchange="SelCity(this.value,'address');">
							<option value="-1">--</option>
							<?php
							$dosql->Execute("SELECT * FROM `#@__cascadedata` WHERE `datagroup`='area' AND level=1 AND datavalue>".$r_user['address_prov']." AND datavalue<".($r_user['address_prov'] + 500)." ORDER BY orderid ASC, datavalue ASC");
							while($row = $dosql->GetArray())
							{
								if($r_user['address_city'] === $row['datavalue'])
									$selected = 'selected="selected"';
								else
									$selected = '';
		
								echo '<option value="'.$row['datavalue'].'" '.$selected.'>'.$row['dataname'].'</option>';
							}
							?>
						</select>
						<select name="address_country" id="address_country">
							<option value="-1">--</option>
							<?php
							$dosql->Execute("SELECT * FROM `#@__cascadedata` WHERE `datagroup`='area' AND level=2 AND datavalue LIKE '".$r_user['address_city'].".%%%' ORDER BY orderid ASC, datavalue ASC");
							while($row = $dosql->GetArray())
							{
								if($r_user['address_country'] === $row['datavalue'])
									$selected = 'selected="selected"';
								else
									$selected = '';
		
								echo '<option value="'.$row['datavalue'].'" '.$selected.'>'.$row['dataname'].'</option>';
							}
							?>
						</select></td>
				</tr>
				<tr>
					<td height="40" align="right">&nbsp;</td>
					<td><input type="text" name="address" id="address" class="class_input" value="<?php echo $r_user['address']; ?>" /></td>
				</tr>
				<tr>
					<td height="40" align="right">邮　编：</td>
					<td><input type="text" name="zipcode" id="zipcode" class="class_input" value="<?php echo $r_user['zipcode']; ?>" /></td>
				</tr>
				<tr>
					<td height="30" colspan="2"></td>
				</tr>
				<tr>
					<td colspan="2" height="26"><h3 class="subtitle">绑定账号</h3></td>
				</tr>
				<tr>
					<td height="40" align="right">QQ绑定：</td>
					<td><?php if($r_user['qqid'] != ''){echo $r_user['qqid'].'&nbsp;&nbsp;<span class="removeoauth"><a href="?a=removeoqq">解除绑定</a></span>';}else{echo '暂无绑定';} ?></td>
				</tr>
				<tr>
					<td height="40" align="right">微博绑定：</td>
					<td><?php if($r_user['weiboid'] != ''){echo $r_user['weiboid'].'&nbsp;&nbsp;<span class="removeoauth"><a href="?a=removeoweibo">解除绑定</a></span>';}else{echo '暂无绑定';} ?></td>
				</tr>
			</table>
			<div class="btn_area">
				<input type="submit" class="btn" value="保 存" />
				<input type="button" class="btn" value="取 消" onclick="history.go(-1)"  />
				<input type="hidden" name="action" id="action" value="update" />
				<input type="hidden" name="id" id="id" value="<?php echo $r_user['id']; ?>" />
			</div>
		</form>
	</div>
	<div class="cl"></div>
</div>
<div class="footer"><?php echo $cfg_copyright; ?></div>
</body>
</html>