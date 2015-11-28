<?php	if(!defined('IN_BKUP')) exit('Request Error!');

/*
**************************
(C)2010-2015 phpMyWind.com
update: 2014-5-30 12:18:29
person: Feng
**************************
*/


@set_time_limit(0);


//跳转到一下页的Js
$dojs = '<script type="text/javascript">
function GotoNextPage(){
    document.gonext.submit();
}
setTimeout("GotoNextPage()",500);
</script>';


//完成操作的Js
$donejs = '<script type="text/javascript">
function redirect(url){
	location.href = url;
}
setTimeout("redirect(\'?action='.$action.'\')", 1000);
</script>';


//替换转移行
function RpLine($str)
{
	$str = str_replace("\r", "\\r", $str);
	$str = str_replace("\n", "\\n", $str);
	return $str;
}


//输出执行信息
function PutInfo($msg1,$msg2='')
{
	echo '<table width="100%" border="0" cellpadding="0" cellspacing="0" class="dataTable"><tr align="center" class="dataTrOn"><td height="200">'.$msg1.$msg2.'</td></tr></table>';
}


//执行备份操作
if($dopost == 'backup')
{

	if(empty($isstruct)) $isstruct = 0;  //表结构是否备份
	if(empty($startpos)) $startpos = 0;  //当前备份条数（分卷号）
	if(empty($nowtable)) $nowtable = ''; //当前备份表
	if(empty($fsize))    $fsize = 2048;  //分卷大小，单位KB
	$fsizeb = $fsize * 1024;             //分卷大小，单位K


	$tmsg = '<div class="loading" style="width:115px;margin:0;">正在执行备份操作...</div><br />';


	//第一次跳转表名属于形式，之后为字符形式
	//$tbales 为字符串形式表名集
	//$tbname 为数组形式表名集
	if(!is_array($tbname))
	{
		$tbname = explode(',', $tbname);
	}
	$tables = implode(',', $tbname);


	//设置此次备份的时间目录
	//如果备份时间为空，则认为首次，创建目录
	if(empty($backup_date))
	{
		$backup_date = MyDate('Y_mdHis',time()).'_'.GetRandStr();
		mkdir($backup_dir .= $backup_date);
	}
	else
	{
		$backup_dir .= $backup_date;
	}


	//当前备份表为空，则认为首次，备份表结构
	if($nowtable == '')
	{

		//如果备份表结构，则执行
		if($isstruct == 1)
		{

			//表结构文件
			$tbstruct_file = $backup_dir.'/table_struct_'.GetRandStr().'.txt';


			//创建并写入表结构备份文件
			$fp = fopen($tbstruct_file, 'w');
			foreach($tbname as $t)
			{
				fwrite($fp, "DROP TABLE IF EXISTS `$t`;\r\n\r\n");
				$dosql->Execute("SHOW CREATE TABLE ".$dosql->db_name.".".$t);
				$row = $dosql->GetArray('me', MYSQL_BOTH);
				fwrite($fp, $row[1].";\r\n\r\n");
			}
			fclose($fp);

			$tmsg .= "<span class='blue'>数据表结构备份完成...</span><br /><br />";;
		}


		$tmsg .= "<span class='blue'>正在进行数据备份的初始化工作，请稍后...</span>";
		$doform = "<form name='gonext' method='post' action='?action=$action'>
		<input type='hidden' name='dopost' value='backup' />
		<input type='hidden' name='isstruct' value='$isstruct' />
		<input type='hidden' name='tbname' value='$tables' />
		<input type='hidden' name='nowtable' value='$tbname[0]' />
		<input type='hidden' name='fsize' value='$fsize' />
		<input type='hidden' name='startpos' value='0' />
		<input type='hidden' name='backup_date' value='$backup_date' /></form>
		$dojs";
		PutInfo($tmsg, $doform);
		exit();
	}


	//开始备份表数据
	else
	{
		$fsn         = 0;  //字段数
		$fields      = ''; //字段名称
		$bakstr      = ''; //备份字符串
		$nowtable    = isset($nowtable) ? $nowtable : ''; //当前表
		$intable     = "INSERT INTO `$nowtable` VALUES("; //备份插入字符串
		$backup_file = "$backup_dir/{$nowtable}_{$startpos}_".GetRandStr().'.txt'; //当前备份文件


		//分析表里的字段信息
		$j = 0;
		$dosql->GetTableFields($nowtable);
		while($r = $dosql->GetFieldObject())
		{
			$fields[$j] = trim($r->name);
			$j++;
		}
		$fsn = $j-1;


		//读取表的内容
		$m = 0;
		$dosql->Execute("SELECT * FROM `$nowtable`");
		while($row = $dosql->GetArray())
		{
			if($m < $startpos)
			{
				$m++;
				continue;
			}


			//检测数据是否达到规定大小
			//达到后跳转页面
			if(strlen($bakstr) > $fsizeb)
			{
				$fp = fopen($backup_file,'w');
				fwrite($fp,$bakstr);
				fclose($fp);

				$tmsg .= "<span class='red'>完成到{$m}条记录的备份，继续备份{$nowtable}表...</span>";
				$doform = "<form name='gonext' method='post' action='?action=$action'>
				<input type='hidden' name='dopost' value='backup' />
				<input type='hidden' name='isstruct' value='$isstruct' />
				<input type='hidden' name='tbname' value='$tables' />
				<input type='hidden' name='nowtable' value='$nowtable' />
				<input type='hidden' name='fsize' value='$fsize' />
				<input type='hidden' name='startpos' value='$m'>
				<input type='hidden' name='backup_date' value='$backup_date' /></form>
				$dojs";
				PutInfo($tmsg,$doform);
				exit();
			}


			//形成插入样式
			$line = $intable;
			for($j=0; $j<=$fsn; $j++)
			{
				if($j < $fsn)
					$line .= "'".RpLine(addslashes($row[$fields[$j]]))."',";
				else
					$line .= "'".RpLine(addslashes($row[$fields[$j]]))."');\r\n";
			}

			$bakstr .= $line;
			$m++;
		}


		//如果数据比卷设置值小
		if($bakstr != '')
		{
			$fp = fopen($backup_file, 'w');
			fwrite($fp, $bakstr);
			fclose($fp);
		}


		//执行下一个表
		$tbnum = count($tbname);
		for($i=0; $i<$tbnum; $i++)
		{
			if($tbname[$i] == $nowtable)
			{
				if(isset($tbname[$i+1]))
				{
					$nowtable = $tbname[$i+1];
					$startpos = 0;
					break;
				}
				else
				{
					PutInfo("<strong class='blue'>完成所有数据备份！</strong><br /><br /><a href='?action=$action'>[如果您的浏览器没有自动跳转，请点击这里]</a>",$donejs);
					exit();
				}
			}
		}

		$tmsg .= "<span class='red'>完成到{$m}条记录的备份，继续备份 [{$nowtable}] 表...</span>";
		$doform = "<form name='gonext' method='post' action='?action=$action'>
		<input type='hidden' name='dopost' value='backup' />
		<input type='hidden' name='isstruct' value='$isstruct' />
		<input type='hidden' name='tbname' value='$tables' />
		<input type='hidden' name='nowtable' value='$nowtable' />
		<input type='hidden' name='fsize' value='$fsize' />
		<input type='hidden' name='startpos' value='$startpos'>
		<input type='hidden' name='backup_date' value='$backup_date' /></form>
		$dojs";

		PutInfo($tmsg,$doform);
		exit();
		//分页备份代码结束
	}
}


//执行还原操作
if($dopost == 'reset')
{

	$tmsg = '<div class="loading" style="width:135px;margin:0;">正在准备还原其它数据...</div><br />';
	$backup_dir .= $dirname.'/';
	$structfile = '';


	//第一次跳转应该是数组形式，之后就变成了字符形式
	if(!is_array($tbname))
		$bakfiles = explode(',', $tbname);
	else
		$bakfiles = $tbname;


	//设置当前记录条数
	if(empty($startgo))
	{
		$startgo = 0;
	}


	//循环备份文件
	foreach($bakfiles as $fname)
	{

		if(!preg_match("#txt$#", $fname))
		{
			continue;
		}


		//如果包含表结构
		if(preg_match("#table_struct#", $fname))
		{
			$structfile = $fname;
		}


		//备份文件列表，排除表结构
		if(filesize($backup_dir.$fname) > 0)
		{
			if($fname != $structfile)
			{
				$filelists[] = $fname;
			}
		}
		else
		{
		 	$filelists = '';
		}


		//如果包含配置表，则需要更新配置文件
		if(preg_match("#webconfig#", $fname))
			$conftb = 1;

		if(empty($conftb)) $conftb = '';
	}


	//设置文件列表字符串
	if(!empty($filelists) && is_array($filelists))
		$bakfilesTmp = implode(',', $filelists);
	else
		$bakfilesTmp = '';


	//开始还原表结构
    if($startgo==0 && $structfile!='')
    {
        $tbdata = '';
        $fp = fopen($backup_dir.$structfile, 'r');
        while(!feof($fp))
        {
            $tbdata .= fgets($fp, 1024);
        }
        fclose($fp);


        $querys = explode(';', $tbdata);
        foreach($querys as $q)
        {
			if(trim($q) == '') continue;
			$dosql->ExecNoneQuery(trim($q).';');
        }


        $tmsg = "$tmsg<span class='blue'>完成数据表结构还原，准备还原数据...</span>";
        $doform = "<form name='gonext' method='post' action='?action=$action'>
		<input type='hidden' name='dopost' value='reset' />
		<input type='hidden' name='dirname' value='$dirname' />
        <input type='hidden' name='startgo' value='1' />
		<input type='hidden' name='conftb' value='$conftb' />
        <input type='hidden' name='tbname' value='$bakfilesTmp' /></form>
		{$dojs}";
        PutInfo($tmsg, $doform);
        exit();
    }


	//开始还原表数据
    else
    {

		$oknum = 0;
        $nowfile = $bakfiles[0];
        $bakfilesTmp = preg_replace("#".$nowfile."[,]{0,1}#", "", $bakfilesTmp);


		//如果备份文件不为空
		if(filesize($backup_dir.$nowfile) > 0)
        {
            $fp = fopen($backup_dir.$nowfile, 'r');
            while(!feof($fp))
            {
                $line = trim(fgets($fp, 512*1024));
                if($line == '') continue;
                $rs = $dosql->ExecNoneQuery($line);
                $oknum++;
            }
            fclose($fp);
        }


		//当需要还原的文件为空
        if($bakfilesTmp == '')
        {

			//如果还原了webconfig表
			//重新生成配置文件
			if($conftb == 1)
			{
				//生成全局配置文件
				$config_cache = PHPMYWIND_INC.'/config.cache.php';
				$str = '<?php	if(!defined(\'IN_PHPMYWIND\')) exit(\'Request Error!\');'."\r\n\r\n";
				$dosql->Execute("SELECT `varname`,`vartype`,`varvalue`,`vargroup` FROM `#@__webconfig` ORDER BY orderid ASC");
				while($row = $dosql->GetArray())
				{
					//强制去掉 '
					//强制去掉最后一位 /
					$vartmp = str_replace("'",'',$row['varvalue']);

					if(substr($vartmp, -1) == '\\')
					{
						$vartmp = substr($vartmp,1,-1);
					}

					if($row['vartype'] == 'number')
					{
						if($row['varvalue'] == '')
						{
							$vartmp = 0;
						}

						$str .= "\${$row['varname']} = ".$vartmp.";\r\n";
					}
					else
					{
						$str .= "\${$row['varname']} = '".$vartmp."';\r\n";
					}
				}
				$str .= '?>';
				Writef($config_cache,$str);
			}

            PutInfo("<strong class='blue'>完成所有数据还原！</strong><br /><br /><a href='?action=$action'>[如果您的浏览器没有自动跳转，请点击这里]</a>".$donejs);
			exit();
        }


        $tmsg = "$tmsg<div class='red'>成功还原 [{$nowfile}] 的{$oknum}条记录...</div>";
        $doform = "<form name='gonext' method='post' action='?action=$action'>
		<input type='hidden' name='dopost' value='reset' />
		<input type='hidden' name='dirname' value='$dirname' />
        <input type='hidden' name='startgo' value='1' />
		<input type='hidden' name='conftb' value='$conftb' />
        <input type='hidden' name='tbname' value='$bakfilesTmp' /></form>
		{$dojs}";
        PutInfo($tmsg, $doform);
        exit();
		//还原操作结束
    }
}
?>
