<?php	if(!defined('IN_PHPMYWIND')) exit('Request Error!');

/*
**************************
(C)2010-2015 phpMyWind.com
update: 2014-5-31 21:59:36
person: Feng
**************************
*/



/*
 * 获取栏目分类
 *
 * @access public
 * @param  $type   string  要显示的模型ID
 * @param  $id     int     区别记录集的ID
 * @param  $i      int     option缩进位数
 * @return         string  输出<select>
*/
function CategoryType($type=0, $id=0, $i=0)
{
	global $dosql,$cfg_siteid, $cfg_adminlevel;

	switch($type)
	{
		case 0:
			$tbname = '#@__info';
			break;
		case 1:
			$tbname = '#@__infolist';
			break;
		case 2:
			$tbname = '#@__infoimg';
			break;
		case 3:
			$tbname = '#@__soft';
			break;
		case 4:
			$tbname = '#@__goods';
			break;
		default:
			$r = $dosql->GetOne("SELECT * FROM `#@__diymodel` WHERE `id`=$type");
			if(isset($r) && is_array($r))
				$tbname = $r['modeltbname'];
			else
				$tbname = '';
	}

	if(isset($_GET['id']) && $tbname != '')
	{
		$r = $dosql->GetOne("SELECT `classid` FROM `$tbname` WHERE `id`=".intval($_GET['id']));
	}

	$dosql->Execute("SELECT * FROM `#@__infoclass` WHERE `siteid`='$cfg_siteid' AND `parentid`=$id ORDER BY `orderid` ASC", $id);
	$i++;
	while($row = $dosql->GetArray($id))
	{

		//栏目是否选择
		if($row['id'] == @$r['classid'] or
		   $row['id'] == @$_GET['cid'])
		    $selected = ' selected="selected" ';
		else
			$selected = '';


		//检查栏目权限
		if($cfg_adminlevel != 1)
		{
			if($row['infotype'] == $type)
			{
				$r2 = $dosql->GetOne("SELECT * FROM `#@__adminprivacy` WHERE `groupid`=".$cfg_adminlevel." AND `model`='category' AND `classid`=".$row['id']." AND `action`='add'");
			}

			//管理组非超级管理员，判断是否有权操作栏目
			if($row['infotype'] != $type or empty($r2))
				$disabled = ' disabled="disabled"';
			else
				$disabled = '';
		}
		else
		{
			//栏目是否可用
			if($row['infotype'] != $type)
				$disabled = ' disabled="disabled"';
			else
				$disabled = '';
		}


		//输出下拉选项
		echo '<option value="'.$row['id'].'"'.$selected.$disabled.'>';

		for($p=1; $p<$i; $p++)
			echo '&nbsp;&nbsp;&nbsp;&nbsp;';

		if($row['parentid'] != 0)
			echo '|- ';

		echo $row["classname"].'</option>';


		CategoryType($type, $row['id'], $i);
	}
}


/*
 * 获取自定义类别
 *
 * @access public
 * @param  $tbname   string  显示分类的表名称
 * @param  $tbname2  string  使用分类的表名称
 * @param  $colname  string  使用分类的表字段
 * @param  $id       int     区别记录集的ID
 * @param  $i        int     option缩进位数
 * echo    string            输出<select>
*/
function GetAllType($tbname='', $tbname2='', $colname='', $id=0, $i=0)
{
	global $dosql,$cfg_siteid;

	if(isset($_GET['id']))
	{
		$r = $dosql->GetOne("SELECT `$colname` FROM `$tbname2` WHERE `id`=".intval($_GET['id']));
	}


	//商品分类与商品品牌不区分站点
	if($tbname != '#@__goodstype' &&
	   $tbname != '#@__goodsbrand')
		$sql = "SELECT * FROM `$tbname` WHERE `siteid`='$cfg_siteid' AND `parentid`=$id ORDER BY orderid ASC";
	else
		$sql = "SELECT * FROM `$tbname` WHERE `parentid`=$id ORDER BY orderid ASC";


	$dosql->Execute($sql,$id);
	$i++;

	while($row = $dosql->GetArray($id))
	{
		$selected = '';

		if(isset($r) && is_array($r))
		{
			if($row['id'] == $r["$colname"])
				$selected = 'selected="selected"';
		}

		echo '<option value="'.$row['id'].'" '.$selected.'>';

		for($n=1; $n<$i; $n++)
			echo '&nbsp;&nbsp;&nbsp;&nbsp;';

		if($row['parentid'] != 0)
			echo '|- ';

		echo $row['classname'].'</option>';


		GetAllType($tbname, $tbname2, $colname, $row['id'], $i);
	}
}


/*
 * 展示自定义类别(无下级)
 *
 * @access  public
 * @param   $tbname   string  显示分类的表名称
 * @param   $tbname2  string  使用分类的表名称
 * @param   $colname  string  使用分类的表字段
 * @param   $id       int     区别记录集的ID
 * @param   $i        int     option缩进位数
 * @return  string            输出<select>
*/
function GetTopType($tbname='', $tbname2='', $colname='', $id=0, $i=0)
{
	global $dosql;

	if(isset($_GET['id']))
	{
		$r = $dosql->GetOne("SELECT `$colname` FROM `$tbname2` WHERE `id`=".intval($_GET['id']));
	}

	$dosql->Execute("SELECT * FROM `$tbname` ORDER BY `orderid` ASC",$id);
	$i++;

	while($row = $dosql->GetArray($id))
	{
		$selected = '';

		if(isset($r) && is_array($r))
		{
			if($row['id'] == $r["$colname"])
				$selected = 'selected="selected"';
		}

		echo '<option value="'.$row['id'].'"'.$selected.'>'.$row["classname"].'</option>';
	}
}


/*
 * 管理页类别展示
 *
 * @access  public
 * @param   $tbname  string  显示分类的表名称
 * @param   $id      int     区别记录集的ID
 * @param   $i       int     option缩进位数
 * @return  string           输出类别切换HTML
*/
function GetMgrType($tbname='', $id=0, $i=0)
{
	global $dosql,$cfg_siteid;

	if($tbname != '#@__goodstype' &&
	   $tbname != '#@__goodsbrand')
		$sql = "SELECT * FROM `$tbname` WHERE `siteid`='$cfg_siteid' AND `parentid`=$id ORDER BY `orderid` ASC";
	else
		$sql = "SELECT * FROM `$tbname` WHERE `parentid`=$id ORDER BY `orderid` ASC";


	//开始循环类别
	$dosql->Execute($sql,$id);
	$i++;

	while($row = $dosql->GetArray($id))
	{
		echo '<a href="?tid='.$row['id'].'">';

		for($p=1; $p<$i; $p++)
			echo '　';

		if($row['parentid'] != 0)
			echo '|- ';

		echo $row["classname"].'</a>';


		GetMgrType($tbname, $row['id'], $i);
	}
}


/*
 * 获取ajax信息类型
 *
 * @access  public
 * @param   $tbname   string  显示分类的表名称
 * @param   $type     int     要显示的模型ID
 * @param   $id       int     区别记录集的ID
 * @param   $i        int     option缩进位数
 * @reutnr  string            输出类别切换HTML
*/
function GetMgrAjaxType($tbname='', $type='', $id=0, $i=0)
{
	global $dosql,$cfg_siteid, $cfg_adminlevel;


	//权限验证
	if($cfg_adminlevel != 1)
	{
		$catgoryPriv = array();
		$dosql->Execute("SELECT * FROM `#@__adminprivacy` WHERE `groupid`=".$cfg_adminlevel." AND `model`='category' AND `action`='list'");
		while($row2 = $dosql->GetArray())
		{
			$catgoryPriv[] = $row2['classid'];
		}
	}


	//商品类别暂时不区分站点
	if($tbname != '#@__goodstype')
		$sql = "SELECT * FROM `$tbname` WHERE `siteid`='$cfg_siteid' AND `parentid`=$id ORDER BY `orderid` ASC";
	else
		$sql = "SELECT * FROM `$tbname` WHERE `parentid`=$id ORDER BY `orderid` ASC";


	$dosql->Execute($sql, $id);
	$i++;

	while($row = $dosql->GetArray($id))
	{
		//如果$type为空，则不是栏目的类型
		//如前只有商品分类选择使用$type为空
		if(!empty($type))
		{

			if($row['infotype'] == $type)
			{
				//判断是否存在查看权限
				if($cfg_adminlevel != 1)
				{
					if(in_array($row['id'],$catgoryPriv))
						echo '<a href="javascript:;" onclick="GetType(\''.$row['id'].'\',\''.$row['classname'].'\',$(this))">';
					else
						echo '<a href="javascript:;" onclick="alert(\'亲，您还没有操作本栏目的权限！\');" style="color:#999;">';
				}
				else
				{
					echo '<a href="javascript:;" onclick="GetType(\''.$row['id'].'\',\''.$row['classname'].'\',$(this))">';
				}

				for($p=1; $p<$i; $p++)
					echo '　';

				if($row['parentid'] != 0)
					echo '|- ';

				echo $row['classname'].'</a>';
			}
		}
		else
		{
			echo '<a href="javascript:;" onclick="GetType2(\''.$row['id'].'\',\''.$row["classname"].'\',$(this))">';

			for($p=1; $p<$i; $p++)
				echo '　';

			if($row['parentid'] != 0)
				echo '|- ';

			echo $row['classname'].'</a>';
		}

		GetMgrAjaxType($tbname, $type, $row['id'], $i);
	}
}


/*
 * 显示缩略图
 *
 * @access  public
 * @param   $picurl  string  <img src的值>
 * @return  $str     string  返回缩略图HTML
*/
function GetMgrThumbs($picurl='',$dfurl='templates/images/dfthumb.png')
{
	$str = '<img alt="';

	if($picurl != '')
	{
		if(substr($picurl, 0, 4) == 'http')
			$str .= $picurl;
		else if($picurl != '')
			$str .= '../'.$picurl;
	}
	else
	{
		$str .= $dfurl;
	}

	$str .= '" />';

	return $str;
}


/*
 * 获取排列序号
 *
 * @access  public
 * @param   $tbname   string  获取该表的最大ID
 * @return  $orderid  int     返回当前ID
*/
function GetOrderID($tbname)
{
	global $dosql;

	$r = $dosql->GetOne("SELECT MAX(orderid) AS `orderid` FROM `$tbname`");
	$orderid = (empty($r['orderid']) ? 1 : ($r['orderid'] + 1));

	return $orderid;
}


/*
 * 获取指定ID与类型下所有子ID
 *
 * @access public
 * @param  $tbname  string  要查询的表名
 * @param  $id      int     要获取下级的ID
 * @param  $type    int     要显示的模型ID
 * @return          string  下级所有ID组合
*/
function GetChildID($tbname, $id='', $type='')
{
	global $dosql;

	if(empty($id) and $type=='')
		$sql = "SELECT `id` FROM `$tbname` WHERE `parentstr` Like '0,%'";

	if(!empty($id) and $type=='')
		$sql = "SELECT `id` FROM `$tbname` WHERE `parentstr` Like '%,$id,%'";

	if(empty($id) and $type!='')
		$sql = "SELECT `id` FROM `$tbname` WHERE `parentstr` Like '0,%' AND `infotype`=$type";

	if(!empty($id) and $type!='')
		$sql = "SELECT `id` FROM `$tbname` WHERE `parentstr` Like '%,$id,%' AND `infotype`=$type";


	$dosql->Execute($sql);
	$ids = '';

	while($row = $dosql->GetArray())
	{
		$ids .= $row['id'].',';
	}

	return $id.','.substr($ids,0,-1);
}


/*
 * 获取parentstr的第二位
 *
 * @access public
 * @param  $str    string  要拆分的整型序列如1,2,3
 * @param  $$i     int     为空返回str数组的第二位(第一位为0)
 * @return $topid  int     str的第一位
*/
function GetTopID($str, $i=1)
{
	if($str == '0,')
	{
		$topid = 0;
	}
	else
	{
		$ids = explode(',', $str);
		$topid = isset($ids[$i]) ? $ids[$i] : '';
	}

	return $topid;
}


//获得文章内容里的外部资源
function GetContFile($body)
{

	global $cfg_image_dir;

	//引入下载类
	require_once(PHPMYWIND_DATA.'/httpfile/down.class.php');

	//初始化变量
	$body = stripslashes($body);
	$host = 'http://'.$_SERVER['HTTP_HOST'];

	//过滤图片文件
    $pic_arr = array();
    preg_match_all("/src=[\"|'|\s]{0,}(http:\/\/([^>]*)\.(gif|jpg|png|bmp))/isU", $body, $pic_arr);
    $pic_arr = array_unique($pic_arr[1]);

	//初始化下载类
	$htd = new HttpDown();

    foreach($pic_arr as $k=>$v)
    {

        if(preg_match('#'.$host.'#i', $v)) continue;
        if(!preg_match('#^http:\/\/#i', $v)) continue;


        $htd->OpenUrl($v);


        $type = $htd->GetHead('content-type');


        if($type == 'image/gif')
            $tempfile_ext = 'gif';

        else if($type == 'image/png')
            $tempfile_ext = 'png';

        else if($type == 'image/wbmp')
            $tempfile_ext = 'bmp';

        else
            $tempfile_ext = 'jpg';


		$upload_url = 'image';
		$upload_dir = $cfg_image_dir;

		$ymd = date('Ymd');
		$upload_url .= '/'.$ymd;
		$upload_dir .= '/'.$ymd;

		if(!file_exists($upload_dir))
		{
			mkdir($upload_dir);

			$fp = fopen($upload_dir.'/index.htm', 'w');
			fclose($fp);
		}

		//上传文件名称
		$filename = time()+rand(1,9999).'.'.$tempfile_ext;

		//上传文件路径
		$save_url = 'uploads/'.$upload_url.'/'.$filename;

		//生成本地路径
		$self = explode('/', $_SERVER['PHP_SELF']);
		$self_size = count($self) - 2;
		$self_str  = '';
		for($i=0; $i<$self_size; $i++)
		{
			$self_str .= $self[$i].'/';
		}

		$save_url = $self_str.'uploads/'.$upload_url.'/'.$filename;
		$save_dir = $upload_dir.'/'.$filename;

        $rs = $htd->SaveToBin($save_dir);
        if($rs)
        {
            $body = str_replace(trim($v), $save_url, $body);
        }
    }

    $htd->Close();


	//回传转义字符串
    return _RunMagicQuotes($body);
}


/*
 * 获取一个远程图片
 *
 * @access  public
 * @param   $url       string  获取字段所属模型
 * @return  $save_url  string  返回上传后地址
*/
function GetRemPic($url)
{

	global $cfg_image_dir;

	//引入下载类
	require_once(PHPMYWIND_DATA.'/httpfile/down.class.php');

	//初始化变量
    $htd = new HttpDown();
    $htd->OpenUrl($url);

	//判断文件类型
    $sparr = array('image/pjpeg', 'image/jpeg', 'image/gif', 'image/png', 'image/xpng', 'image/wbmp');
    if(!in_array($htd->GetHead("content-type"), $sparr))
    {
        return FALSE;
    }
    else
    {
        $type = $htd->GetHead("content-type");

        if($type == 'image/gif')
            $tempfile_ext = 'gif';

        else if($type == 'image/png')
            $tempfile_ext = 'png';

        else if($type == 'image/wbmp')
            $tempfile_ext = 'bmp';

        else
            $tempfile_ext = 'jpg';


		$upload_url = 'image';
		$upload_dir = $cfg_image_dir;

		$ymd = date('Ymd');
		$upload_url .= '/'.$ymd;
		$upload_dir .= '/'.$ymd;

		if(!file_exists($upload_dir))
		{
			mkdir($upload_dir);

			$fp = fopen($upload_dir.'/index.htm', 'w');
			fclose($fp);
		}

		//上传文件名称
		$filename = time()+rand(1,9999).'.'.$tempfile_ext;

		//上传文件路径
		$save_url = 'uploads/'.$upload_url.'/'.$filename;
		$save_dir = $upload_dir.'/'.$filename;

        $rs = $htd->SaveToBin($save_dir);
    }

    $htd->Close();
    return ($rs ? $save_url : '');
}


/*
 * 文档自动分页
 *
 * @access  public
 * @param   $body    string  要设置分页内容
 * @param   $spsize  string  自动分页大小
 * @param   $sptag   string  分页标示符
 * @return  $body    string  设置分页符的内容
*/
function ContAutoPage($body, $spsize, $sptag='<hr style="page-break-after:always;" class="ke-pagebreak" />')
{
	//判断是否符合分页条件
    if(strlen($body) < $spsize) return $body;

    $body    = stripslashes($body);
	$body    = str_replace($sptag,'',$body);
    $bodyarr = explode('<', $body);

	//初始化参数
	$body = '';
    $pagebody = '';
    $istable = 0;

    foreach($bodyarr as $i=>$v)
    {
        if($i == 0)
        {
            $pagebody .= $bodyarr[$i];
			continue;
        }

        $bodyarr[$i] = '<'.$bodyarr[$i];

        if(strlen($bodyarr[$i]) > 6)
        {
            $tname = substr($bodyarr[$i], 1, 5);

            if(strtolower($tname) == 'table')
			{
                $istable++;
			}
            else if(strtolower($tname) == '/tabl')
			{
                $istable--;
			}

            if($istable > 0)
            {
                $pagebody .= $bodyarr[$i];
				continue;
            }
            else
            {
                $pagebody .= $bodyarr[$i];
            }
        }
        else
        {
            $pagebody .= $bodyarr[$i];
        }

        if(strlen($pagebody) > $spsize)
        {
            $body .= $pagebody.$sptag;
            $pagebody = '';
        }
    }

    if($pagebody != '')
    {
        $body .= $pagebody;
    }

    return _RunMagicQuotes($body);
}


/*
 * 获取自定义字段
 *
 * @access  public
 * @param   $type  string  要查询的字段模型ID
 * @param   $type  string  要查询的字段栏目ID
 * @param   $row   string  传递外部记录集的变量(编辑时用到)
 * @return         string  返回HTML
*/
function GetDiyField($type='',$id=0,$row='')
{

	global $dosql, $cfg_max_file_size, $cfg_max_file_size;

	$reStr = '';
	$dosql->Execute("SELECT * FROM `#@__diyfield` WHERE infotype='$type' AND checkinfo=true ORDER BY orderid ASC");
	while($r = $dosql->GetArray())
	{
		$catepriv = explode(',',$r['catepriv']);
		if(in_array($id,$catepriv))
		{
			if(isset($row[$r['fieldname']]))
				$fieldvalue = $row[$r['fieldname']];
			else
				$fieldvalue = '';


			$reStr .= '<table width="100%" border="0" cellspacing="0" cellpadding="0" class="diyfieldtb"><tr';
			if($r['fieldtype'] == 'mediumtext')
			{
				$reStr .= ' height="304"';
			}
			$reStr .= '><td height="40" align="right" width="25%">'.$r['fieldtitle'].'：</td><td width="75%">';


			//文本框
			if($r['fieldtype']=='varchar' or $r['fieldtype']=='int' or $r['fieldtype']=='decimal')
			{
				$reStr .= '<input type="text" name="'.$r['fieldname'].'" id="'.$r['fieldname'].'" class="input" value="'.$fieldvalue.'" />';
				if(!empty($r['fieldcheck']))
				{
					$reStr .= '&nbsp;<span class="maroon">*</span>';
				}
				$reStr .= '<span class="cnote">'.$r['fielddesc'].'</span>';
			}


			//多行文本
			else if($r['fieldtype'] == 'text')
			{
				$reStr .= '<textarea name="'.$r['fieldname'].'" id="'.$r['fieldname'].'" class="textarea" style="margin:7px 0;">'.$fieldvalue.'</textarea>';
				if(!empty($r['fieldcheck']))
				{
					$reStr .= '&nbsp;<span class="maroon">*</span>';
				}
				$reStr .= '<span class="cnote">'.$r['fielddesc'].'</span>';
			}


			//单选按钮
			else if($r['fieldtype'] == 'radio')
			{
				if(!empty($r['fieldsel']))
				{
					$fieldsel = explode(',', $r['fieldsel']);
					foreach($fieldsel as $k=>$fieldsel_arr)
					{
						if($fieldsel_arr != '')
						{
							$fieldsel_val = explode('=', $fieldsel_arr);
							$fieldsel_val[1] = isset($fieldsel_val[1]) ? $fieldsel_val[1] : '';

							if($fieldvalue != '')
							{
								if($fieldsel_val[1] == $fieldvalue)
									$checked = 'checked="checked"';
								else
									$checked = '';
							}
							else
							{
								if($k == 0)
									$checked = 'checked="checked"';
								else
									$checked = '';
							}

							$reStr .= '<input type="radio" name="'.$r['fieldname'].'" id="'.$r['fieldname'].'" value="'.$fieldsel_val[1].'" '.$checked.' />&nbsp;'.$fieldsel_val[0];
							if($k < (count($fieldsel)-1)) $reStr .= '&nbsp;&nbsp;&nbsp;';
						}
					}

					if(!empty($r['fieldcheck']))
					{
						$reStr .= '&nbsp;<span class="maroon">*</span>';
					}

					$reStr .= '<span class="cnote">'.$r['fielddesc'].'</span>';
				}

			}


			//多选按钮
			else if($r['fieldtype'] == 'checkbox')
			{
				if(!empty($r['fieldsel']))
				{
					$fieldsel = explode(',', $r['fieldsel']);
					foreach($fieldsel as $k=>$fieldsel_arr)
					{
						if($fieldsel_arr != '')
						{
							$fieldsel_val = explode('=', $fieldsel_arr);
							$fieldsel_val[1] = isset($fieldsel_val[1]) ? $fieldsel_val[1] : '';

							if($fieldvalue != '')
							{
								$fileall = explode(',',$fieldvalue);
								if(is_array($fileall))
								{
									if(in_array($fieldsel_val[1], $fileall))
										$checked = 'checked="checked"';
									else
										$checked = '';
								}
								else
								{
									if($fieldsel_val[1] == $fieldvalue)
										$checked = 'checked="checked"';
									else
										$checked = '';
								}
							}
							else
							{
								$checked = '';
							}

							$reStr .= '<input type="checkbox" name="'.$r['fieldname'].'[]" id="'.$r['fieldname'].'[]" value="'.$fieldsel_val[1].'" '.$checked.' />&nbsp;'.$fieldsel_val[0];
							if($k < (count($fieldsel)-1)) $reStr .= '&nbsp;&nbsp;&nbsp;';
						}
					}
					if(!empty($r['fieldcheck']))
					{
						$reStr .= '&nbsp;<span class="maroon">*</span>';
					}
					$reStr .= '<span class="cnote">'.$r['fielddesc'].'</span>';
				}

			}


			//下拉菜单
			else if($r['fieldtype'] == 'select')
			{
				if(!empty($r['fieldsel']))
				{

					$reStr .= '<select name="'.$r['fieldname'].'" id="'.$r['fieldname'].'">';
					$fieldsel = explode(',', $r['fieldsel']);
					foreach($fieldsel as $k=>$fieldsel_arr)
					{
						if($fieldsel_arr != '')
						{
							$fieldsel_val = explode('=', $fieldsel_arr);
							$fieldsel_val[1] = isset($fieldsel_val[1]) ? $fieldsel_val[1] : '';

							if($fieldvalue != '')
							{
								if($fieldsel_val[1] == $fieldvalue)
									$selected = 'selected="selected"';
								else
									$selected = '';
							}
							else
							{
								$selected = '';
							}

							$fieldsel_val = explode('=', $fieldsel_arr);
							$reStr .= '<option name="'.$r['fieldname'].'" id="'.$r['fieldname'].'" value="'.$fieldsel_val[1].'"'.$selected.'>'.$fieldsel_val[0].'</option>';
							if($k < (count($fieldsel)-1)) $reStr .= '&nbsp;&nbsp;&nbsp;';
						}
					}
					$reStr .= '</select>';
					if(!empty($r['fieldcheck']))
					{
						$reStr .= '&nbsp;<span class="maroon">*</span>';
					}
					$reStr .= '<span class="cnote">'.$r['fielddesc'].'</span>';
				}
			}


			//单个附件
			else if($r['fieldtype'] == 'file')
			{
				$reStr .= '<input type="text" name="'.$r['fieldname'].'" id="'.$r['fieldname'].'" class="input" value="'.$fieldvalue.'" />';
				$reStr .= '&nbsp;<span class="cnote"><span class="grayBtn" onclick="GetUploadify(\'uploadify\',\''.$r['fieldtitle'].'\',\'all\',\'all\',1,'.$cfg_max_file_size.',\''.$r['fieldname'].'\')">上 传</span></span>';
				if(!empty($r['fieldcheck']))
				{
					$reStr .= '&nbsp;<span class="maroon">*</span>';
				}
				if(!empty($r['fielddesc']))
				{
					$reStr .= '<span class="cnote">'.$r['fielddesc'].'</span>';
				}
			}


			//多个附件
			else if($r['fieldtype'] == 'fileall')
			{
				$reStr .= '<fieldset class="picarr"><legend>列表</legend><div>最多可以上传<strong>50</strong>个附件<span onclick="GetUploadify(\'uploadify2\',\''.$r['fieldtitle'].'\',\'all\',\'all\',50,'.$cfg_max_file_size.',\''.$r['fieldname'].'\',\''.$r['fieldname'].'_area\')">开始上传</span></div><ul id="'.$r['fieldname'].'_area">';
				if(isset($fieldvalue))
				{
					if(!empty($fieldvalue))
					{
						$picarr = unserialize($fieldvalue);
						if(isset($picarr) && is_array($picarr))
						{
							foreach($picarr as $v)
							{
								$v = explode(',', $v);
								$reStr .= '<li rel="'.$v[0].'"><input type="text" name="'.$r['fieldname'].'[]" value="'.$v[0].'"><a href="javascript:void(0);" onclick="ClearPicArr(\''.$v[0].'\')">删除</a><br /><input type="text" name="'.$r['fieldname'].'_txt[]" value="'.$v[1].'"><span>描述</span></li>';
							}
						}
					}
				}
				$reStr .= '</ul></fieldset>';
			}


			//日期时间
			else if($r['fieldtype'] == 'datetime')
			{
				if(!empty($fieldvalue))
					$dtime = GetDateTime($fieldvalue);
				else
					$dtime = GetDateTime(time());

				$reStr .= '<input type="text" name="'.$r['fieldname'].'" id="'.$r['fieldname'].'" class="inputms" value="'.$dtime .'" readonly="readonly" />';

				if(!empty($r['fieldcheck']))
				{
					$reStr .= '&nbsp;<span class="maroon">*</span>';
				}

				$reStr .= '<span class="cnote">'.$r['fielddesc'].'</span>';
				$reStr .= '<script type="text/javascript">Calendar.setup({inputField:"'.$r['fieldname'].'",ifFormat:"%Y-%m-%d %H:%M:%S",showsTime:true,timeFormat:"24"});</script>';
			}


			//编辑器模式
			else if($r['fieldtype'] == 'mediumtext')
			{
				$reStr .= '<textarea name="'.$r['fieldname'].'" id="'.$r['fieldname'].'" class="kindeditor">'.$fieldvalue.'</textarea>';
				$reStr .= '<script type="text/javascript">var editor;KindEditor.ready(function(K) {editor = K.create(\'textarea[name="'.$r['fieldname'].'"]\', {allowFileManager:true,width:\'667px\',height:\'280px\',extraFileUploadParams:{sessionid:\''.session_id().'\'}});});</script>';
			}

			$reStr .= '</td></tr></table>';
		}
	}

	return $reStr;
}


/*
 * 获取指定uid的头像文件规范路径
 * 来源：Ucenter base类的get_avatar方法
 *
 * @param  int  $uid
 * @param  string  $size  头像尺寸，可选为'big', 'middle', 'small'
 * @param  string  $type  类型，可选为real或者virtual
 * @return string  头像路径
 */
function get_avatar_filepath($uid, $size='big', $type='')
{
	$size = in_array($size, array('big', 'middle', 'small')) ? $size : 'big';
	$uid = abs(intval($uid));
	$uid = sprintf("%09d", $uid);
	$dir1 = substr($uid, 0, 3);
	$dir2 = substr($uid, 3, 2);
	$dir3 = substr($uid, 5, 2);
	$typeadd = $type == 'real' ? '_real' : '';
	return  $dir1.'/'.$dir2.'/'.$dir3.'/'.substr($uid, -2).$typeadd.'_avatar_'.$size.'.jpg';
}


/*
 * 获取文章作者
 *
 * @return string   文章作者
 */
function GetAuthor()
{
	global $dosql;

	$r = $dosql->GetOne("SELECT `nickname` FROM `#@__admin` WHERE `username`='".$_SESSION['admin']."'");

	if($r['nickname'] != '')
		return $r['nickname'];
	else
		return $_SESSION['admin'];
}



/*
 * 获取栏目缩略图尺寸
 *
 * @param  int  $t  获取类型 0为宽度 1为高度
 * @return int 缩略图尺寸
 */
function GetCatpSize($t=0)
{
	global $dosql;

	if(!empty($_GET['id']))
	{
		$r = $dosql->GetOne("SELECT `picwidth`,`picheight` FROM `#@__infoclass` WHERE `id`=".intval($_GET['id']));

		if($t == 0 && isset($r['picwidth']))
			return $r['picwidth'];
		else if($t == 1 && isset($r['picheight']))
			return $r['picheight'];
		else
			return '';
	}
}



/**
 *  会对空白地方填充满
 *
 * @access    public
 * @param     string  $srcfile   图片路径
 * @param     string  $towidth   转换到的宽度
 * @param     string  $toheight  转换到的高度
 * @param     string  $tofile    输出文件到
 * @param     string  $issave    是否保存
 * @param     string  $issource    是否保留原图
 * @return    bool
 */
function ImageResize($srcfile, $towidth, $toheight, $tofile='', $issave=TRUE, $issource=TRUE)
{

	global $cfg_imgresize;


	//如果不需要存储到它处
	//直接覆盖原来文件位置
	if($tofile == '') $tofile = $srcfile;

	//获取图片信息
	$srcinfo = @getimagesize($srcfile);

	//检测图片扩展名
	if($srcinfo[2] != 1 &&
	   $srcinfo[2] != 2 &&
	   $srcinfo[2] != 3 &&
	   $srcinfo[2] != 6)
	{
		return FALSE;
	}

	switch($srcinfo[2])
	{
		case 1:
			$imgfrom = imagecreatefromgif($srcfile);
			$extname = '.gif';
			$newexts = '_hd.gif';
			break;
		case 2:
			$imgfrom = imagecreatefromjpeg($srcfile);
			$extname = '.jpg';
			$newexts = '_hd.jpg';
			break;
		case 3:
			$imgfrom = imagecreatefrompng($srcfile);
			$extname = '.png';
			$newexts = '_hd.png';
			break;
		case 6:
			$imgfrom = imagecreatefromwbmp($srcfile);
			$extname = '.bmp';
			$newexts = '_hd.bmp';
			break;
	}

	//如果保留原图先将原图另存
	if($issource) {
		$srcfile_hd = str_replace($extname, $newexts, $srcfile);
		@copy($srcfile, $srcfile_hd);
	}

	//获取图片宽高
	$imgwidth  = $srcinfo[0];
	$imgheight = $srcinfo[1];

	if(!$imgwidth || !$imgheight) return FALSE;

	//判断缩略方式
	//裁切方式
	if($cfg_imgresize == 'Y')
	{

		//宽高大于目标高度
		if($imgwidth > $towidth && $imgheight > $toheight)
		{
			//目标图片比例
			$toratio  = $towidth / $toheight;

			//当前图片比例
			$imgratio = $imgwidth / $imgheight;

			//如果目标比例大于当前比例定义高度
			if($toratio > $imgratio)
			{
				$newwidth  = $towidth;
				$newheight = $towidth / $imgratio;
			}
			else
			{
				$newwidth  = $imgratio * $toheight;
				$newheight = $toheight;
			}

			//创建真彩色图像
			$newimg = imagecreatetruecolor($towidth, $toheight);

			//缩放并合并图像
			if(!@imagecopyresampled($newimg, $imgfrom, ($towidth-$newwidth)/2, ($toheight-$newheight)/2, 0, 0, $newwidth, $newheight, $imgwidth, $imgheight))
			{
				return FALSE;
			}
		}

		//宽大于目标高度，高小于目标高度
		else if($imgwidth >= $towidth && $imgheight <= $toheight)
		{
			$newwidth  = $towidth;
			$newheight = $imgheight;

			//创建一张真彩图像
			$newimg = imagecreatetruecolor($newwidth, $newheight);

			//裁切图像
			if(!@imagecopyresampled($newimg, $imgfrom, ($towidth-$newwidth)/2, 0, 0, 0, $newwidth, $newheight, $imgwidth, $imgheight))
			{
				return FALSE;
			}
		}

		//宽小于目标高度，高大于目标高度
		else if($imgwidth <= $towidth && $imgheight >= $toheight)
		{
			$newwidth  = $imgwidth;
			$newheight = $toheight;

			//创建一张真彩图像
			$newimg = imagecreatetruecolor($newwidth, $newheight);

			//裁切图像
			if(!@imagecopyresampled($newimg, $imgfrom, 0, ($toheight-$newheight)/2, 0, 0, $newwidth, $newheight, $imgwidth, $imgheight))
			{
				return FALSE;
			}
		}

		//宽小于目标高度，高小于于目标高度
		else if($imgwidth <= $towidth && $imgheight <= $toheight)
		{
			imagedestroy($newimg);
			imagedestroy($imgfrom);

			return TRUE;
		}

	}
	//填充方式
	else
	{
		//目标图片比例
		$toratio  = $towidth / $toheight;

		//当前图片比例
		$imgratio = $imgwidth / $imgheight;

		//如果目标比例大于当前比例定义高度
		if($toratio > $imgratio)
		{
			$newheight = $toheight;
			$newwidth  = $imgratio * $toheight;
		}
		else
		{
			$newheight = $towidth / $imgratio;
			$newwidth  = $towidth;
		}

		//匹配最终宽高
		if($newwidth > $towidth)
			$newheight = $towidth;

		if($newheight > $toheight)
			$newheight = $toheight;

		//创建真彩色图像
		$newimg = imagecreatetruecolor($towidth, $toheight);

		//为一幅图像分配颜色
		$bgcolor = imagecolorallocate($newimg, 0xff, 0xff, 0xff);

		//画一矩形并填充
		if(!@imagefilledrectangle($newimg, 0, 0, $towidth-1, $toheight-1, $bgcolor))
		{
			return FALSE;
		}

		//缩放并合并图像
		if(!@imagecopyresampled($newimg, $imgfrom, ($towidth-$newwidth)/2, ($toheight-$newheight)/2, 0, 0, $newwidth, $newheight, $imgwidth, $imgheight))
		{
			return FALSE;
		}
	}


	//保存为目标文件
	if($issave)
	{
		switch($srcinfo[2])
		{
			case 1:
				imagegif($newimg, $tofile);
				break;
			case 2:
				imagejpeg($newimg, $tofile, 100);
				break;
			case 3:
				imagepng($newimg, $tofile);
				break;
			case 6:
				imagebmp($newimg, $tofile);
				break;
			default:
				return FALSE;
		}
	}
	//不保存
	else
	{
		switch($srcinfo[2])
		{
			case 1:
				imagegif($newimg);
				break;
			case 2:
				imagejpeg($newimg);
				break;
			case 3:
				imagepng($newimg);
				break;
			case 6:
				imagebmp($newimg);
				break;
			default:
				return FALSE;
		}
	}


	//销毁图像
	imagedestroy($newimg);
	imagedestroy($imgfrom);

	return TRUE;
}


//验证模块权限
function IsModelPriv($m='')
{
	global $dosql, $cfg_adminlevel;

	$sql = "INSERT INTO `#@__sysevent` (uname, groupid, siteid, model, classid, action, posttime, ip) VALUE ('".$_SESSION['admin']."', '0', '".$_SESSION['siteid']."', '$m', '', '', '".time()."', '".GetIP()."')";

	//超级管理员只记录操作日志
	if($cfg_adminlevel == 1)
	{
		//更新操作日志
		SetSysEvent($m);
	}

	//非超级管理员判断权限
	else if($cfg_adminlevel != 1)
	{
		//数据库还原报错问题兼容方法
		if ( strpos(GetCurUrl(), 'database_backup.php') && ( isset($_GET['action']) && $_GET['action'] == 'import') ) {
			return false;
		}

		$r = $dosql->GetOne("SELECT * FROM `#@__adminprivacy` WHERE `groupid`=".$cfg_adminlevel." AND `model`='$m'");
		if(isset($r) && is_array($r))
		{
			//更新操作日志
			SetSysEvent($m);
			return TRUE;
		}
		else
		{
			ShowMsg('亲，您还没有操作本模块的权限！','-1');
			exit();
		}
	}

	else
	{
		return FALSE;
	}

}


//验证栏目权限
function IsCategoryPriv($cid=0,$act='',$return='',$issave='1')
{
	global $dosql, $cfg_adminlevel;

	//超级管理员只记录操作日志
	if($cfg_adminlevel == 1)
	{
		//更新操作日志
		$r = $dosql->GetOne("SELECT `infotype` FROM `#@__infoclass` WHERE `id`=$cid");
		if(isset($r['infotype']))
		{
			switch($r['infotype'])
			{
				case 0:
					$m = 'info';
					break;
				case 1:
					$m = 'infolist';
					break;
				case 2:
					$m = 'infoimg';
					break;
				case 3:
					$m = 'soft';
					break;
				case 4:
					$m = 'goods';
					break;
				default:
					$r2 = $dosql->GetOne("SELECT * FROM `#@__diymodel` WHERE `id`=".$r['infotype']);
					if(isset($r2) && is_array($r2))
						$m = $r2['modelname'];
					else
						$m = '';
			}

            if($issave == 1)
				SetSysEvent($m,$cid,$act);
		}

		return TRUE;
	}

	//非超级管理员判断权限
	else if($cfg_adminlevel != 1)
	{
		$r = $dosql->GetOne("SELECT * FROM `#@__adminprivacy` WHERE `groupid`=".$cfg_adminlevel." AND `model`='category' AND `classid`=$cid AND `action`='$act'");
		if(isset($r) && is_array($r))
		{
			//更新操作日志
			$r = $dosql->GetOne("SELECT `infotype` FROM `#@__infoclass` WHERE `id`=$cid");
			if(isset($r['infotype']))
			{
				switch($r['infotype'])
				{
					case 0:
						$m = 'info';
						break;
					case 1:
						$m = 'infolist';
						break;
					case 2:
						$m = 'infoimg';
						break;
					case 3:
						$m = 'soft';
						break;
					case 4:
						$m = 'goods';
						break;
					default:
						$r2 = $dosql->GetOne("SELECT * FROM `#@__diymodel` WHERE `id`=".$r['infotype']);
						if(isset($r2) && is_array($r2))
							$m = $r2['modelname'];
						else
							$m = '';
				}

				if($issave == 1)
					SetSysEvent($m,$cid,$act);
			}

			return TRUE;
		}
		else
		{
			if($return == '')
			{
				ShowMsg('亲，您还没有操作本栏目的权限！','-1');
				exit();
			}
			else
			{
				return FALSE;
			}
		}
	}
	else
	{
		return FALSE;
	}
}


//更新操作日志
function SetSysEvent($m='', $cid=0, $a='all')
{
	global $dosql;

	//数据库还原报错问题兼容方法
	if ( strpos(GetCurUrl(), 'database_backup.php') && ( isset($_GET['action']) && $_GET['action'] == 'import') ) {
		return false;
	}

	$sql = "INSERT INTO `#@__sysevent` (uname, siteid, model, classid, action, posttime, ip) VALUE ('".$_SESSION['admin']."', '".$_SESSION['siteid']."', '$m', '$cid', '$a', '".time()."', '".GetIP()."')";

	//更新操作日志
	//一分钟内连续操作只记录一次
	$r = $dosql->GetOne("SELECT `posttime` FROM `#@__sysevent` WHERE `uname`='".$_SESSION['admin']."' AND `siteid`=".$_SESSION['siteid']." AND `model`='$m'  AND `action`='$a' ORDER BY id DESC");
	if(!isset($r['posttime']))
		$dosql->ExecNoneQuery($sql);

	else if(isset($r['posttime']) &&
	       ($r['posttime']<time()-60))
		$dosql->ExecNoneQuery($sql);
}


//获取添加权限的自定义字段
function GetDiyFieldCatePriv($model, $cid)
{
	global $dosql;

	$dosql->Execute("SELECT `id`,`catepriv` FROM `#@__diyfield` WHERE infotype=" . $model . " ORDER BY orderid ASC");
	$str = '';
	while($row = $dosql->GetArray())
	{
		if(isset($row['catepriv']))
		{
			$catepriv = explode(',', $row['catepriv']);
			if(in_array($cid, $catepriv))
			{
				$str .= $row['id'] . ',';
			}
		}
	}

	$str = rtrim($str, ',');
	return $str;
}
?>
