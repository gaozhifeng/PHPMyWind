<?php	if(!defined('IN_PHPMYWIND')) exit('Request Error!');

/*
**************************
(C)2010-2015 phpMyWind.com
update: 2014-5-31 21:59:40
person: Feng
**************************
*/


/*
 * 操作执行类
 *
 * 调用这个类前,请先确保外部已设定需要用到的变量
 * $GLOBALS['action'];
 * $GLOBALS['dosql'];
 * $GLOBALS['tbname'];
 * $GLOBALS['gourl'];
 */


$doaction = new actClass($action);

class actClass
{

	public $action;
	public $dosql;
	public $tbname;
	public $gourl;

	public $id;
	public $checkid;
	public $parentid;
	public $parentstr;
	public $orderid;
	public $checkinfo;


	//构造函数
    function __construct($action='')
    {
		if($action)
		{
			$this->action = $action;
			$this->Init();
		}
    }


	//兼容PHP4
    function actClass($action='')
    {
		$this->__construct($action='');
    }


	//初始化变量
	function Init()
    {
        $this->tbname    = $GLOBALS['tbname'];
		$this->gourl     = $GLOBALS['gourl'];
        $this->id        = isset($GLOBALS['id'])        ? $GLOBALS['id']        : '';
        $this->checkid   = isset($GLOBALS['checkid'])   ? $GLOBALS['checkid']   : '';
        $this->parentid  = isset($GLOBALS['parentid'])  ? $GLOBALS['parentid']  : '';
		$this->parentstr = isset($GLOBALS['parentstr']) ? $GLOBALS['parentstr'] : '';
		$this->orderid   = isset($GLOBALS['orderid'])   ? $GLOBALS['orderid']   : '';
		$this->checkinfo = isset($GLOBALS['checkinfo']) ? $GLOBALS['checkinfo'] : '';

		$this->Exec_act();
    }


	//执行相应操作
	function Exec_act()
	{
		switch($this->action)
		{
			case 'del':
				$this->Del();
				$this->GoURL();
			break;


			case 'delall':
				$this->DelAll();
				$this->GoURL();
			break;


			case 'del2':
				$this->DelNone();
				$this->GoURL();
			break;


			case 'delall2':
				$this->DelAllNone();
				$this->GoURL();
			break;


			case 'up':
				$this->UpOrderID();
				$this->GoURL();
			break;


			case 'down':
				$this->UpOrderID();
				$this->GoURL();
			break;


			case 'uporder':
				$this->UpAllOrder();
				$this->GoURL();
			break;


			case 'check':
				$this->UpCheck();
				$this->GoURL();
			break;


			default:
		}
	}


	//删除单条记录(包含下级)
	function Del()
	{
		global $dosql;

		$dosql->ExecNoneQuery("DELETE FROM `$this->tbname` WHERE (id=$this->id Or parentstr LIKE '%,$this->id,%')");
	}


	//删除全选记录(包含下级)
	function DelAll()
	{
		global $dosql;

		foreach($this->checkid as $k=>$v)
		{
			$dosql->ExecNoneQuery("DELETE FROM `$this->tbname` WHERE (id=$v Or parentstr LIKE '%,$v,%')");
		}
	}


	//删除单条记录(不包含下级)
	function DelNone()
	{
		global $dosql;

		$dosql->ExecNoneQuery("DELETE FROM `$this->tbname` WHERE id=$this->id");
	}


	//删除全选记录(不包含下级)
	function DelAllNone()
	{
		global $dosql;

		foreach($this->checkid as $k => $v)
		{
			$dosql->ExecNoneQuery("DELETE FROM `$this->tbname` WHERE id=$v");
		}
	}


	//更新单条排序
	function UpOrderID()
	{
		global $dosql;

		if($this->action == 'up' and $this->parentid == '')
		{
			$sql = "SELECT id,orderid FROM `$this->tbname` WHERE orderid<$this->orderid ORDER BY orderid DESC";
		}

		else if($this->action == 'up' and $this->parentid != '')
		{
			$sql = "SELECT id,orderid FROM `$this->tbname` WHERE parentid=$this->parentid AND orderid<$this->orderid ORDER BY orderid DESC";
		}

		if($this->action == 'down' and $this->parentid == '')
		{
			$sql = "SELECT id,orderid FROM `$this->tbname` WHERE orderid>$this->orderid ORDER BY orderid ASC";
		}

		else if($this->action == 'down' and $this->parentid != '')
		{
			$sql = "SELECT id,orderid FROM `$this->tbname` WHERE parentid=$this->parentid AND orderid>$this->orderid ORDER BY orderid ASC";
		}

		$row = $dosql->GetOne($sql);

		if(!empty($row['orderid']) &&
		   !empty($row['id']))
		{
			$newid = $row['id'];
			$neworderid = $row['orderid'];

			$dosql->ExecNoneQuery("UPDATE `$this->tbname` SET orderid=$neworderid WHERE id=$this->id");
			$dosql->ExecNoneQuery("UPDATE `$this->tbname` SET orderid=$this->orderid WHERE id=$newid");
		}
	}


	//更新所有排序
	function UpAllOrder()
	{
		global $dosql;

		foreach($this->id as $k => $v)
		{
			$dosql->ExecNoneQuery("UPDATE `$this->tbname` SET orderid=".$this->orderid[$k]." WHERE id=$v");
		}
	}


	//更新审核状态
	function UpCheck()
	{
		global $dosql;

		if($this->checkinfo == 'true')
		{
			$sql = "UPDATE `$this->tbname` SET checkinfo='false' WHERE id=$this->id";
		}
		else if($this->checkinfo == '1')
		{
			$sql = "UPDATE `$this->tbname` SET isshow='0' WHERE id=$this->id";
		}
		else if($this->checkinfo == 'false')
		{
			$sql = "UPDATE `$this->tbname` SET checkinfo='true' WHERE id=$this->id";
		}
		else if($this->checkinfo == '0')
		{
			$sql = "UPDATE `$this->tbname` SET isshow='1' WHERE id=$this->id";
		}

		$dosql->ExecNoneQuery($sql);
	}


	//获取parentstr
	function GetParentStr()
	{
		global $dosql;

		if($this->parentid == 0)
		{
			$this->parentstr = '0,';
		}
		else
		{
			$row = $dosql->GetOne("SELECT parentstr FROM `$this->tbname` WHERE id=$this->parentid");
			$this->parentstr = $row['parentstr'].$this->parentid.',';
		}

		return $this->parentstr;
	}


	/*
	 * 更新parentstr字段
	 *
	 * @param  $id          int     为类型id
	 * @param  $childtbname string  为涉及到的子表
	 * @param  $field       string  为子表中str字段代表
	 * @param  $field2      string  为子表中的cid字段代表
	*/
	function UpParentStr($id='',$childtbname='',$field='',$field2='',$pstr='')
	{
		global $dosql;

		//获取当前parentstr
		if($pstr == '')
			$parstr = $this->parentstr.$id.',';
		else
			$parstr = $pstr.$id.',';


		//获取当前ID下所有子ID
		$dosql->Execute("SELECT `id` FROM `$this->tbname` WHERE `parentid`=$id",$id);
		while($row = $dosql->GetArray($id))
		{

			//更新类别表parentstr
			$dosql->ExecNoneQuery("UPDATE `$this->tbname` SET `parentstr`='".$parstr."' WHERE `id`=".$row['id']);


			//更新信息表parentstr
			//如果包含多个子信息表,则循环更新子信息表
			if(!empty($childtbname))
			{
				if(is_array($childtbname))
				{
					foreach($childtbname as $k=>$v)
					{
						$dosql->ExecNoneQuery("UPDATE `$v` SET $field='".$parstr."' WHERE $field2=".$row['id']);
					}
				}
				else
				{
					$dosql->ExecNoneQuery("UPDATE `$childtbname` SET $field='".$parstr."' WHERE $field2=".$row['id']);
				}
			}


			//传递下级参数,继续更新
			$this->UpParentStr($row['id'], $childtbname, $field, $field2, $parstr);
		}
	}


	//处理完返回
	function GoURL()
	{
		header('location:'.$this->gourl);
		exit();
	}
}
?>
