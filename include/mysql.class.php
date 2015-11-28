<?php	if(!defined('IN_PHPMYWIND')) exit('Request Error!');

/*
**************************
(C)2010-2015 phpMyWind.com
update: 2014-5-31 21:57:54
person: Feng
**************************
*/


/*
 * 数据库类
 *
 * 调用这个类前,请先设定这些外部变量
 * $GLOBALS['db_host'];
 * $GLOBALS['db_user'];
 * $GLOBALS['db_pwd'];
 * $GLOBALS['db_name'];
 * $GLOBALS['db_tablepre'];
 *
 * 在系统所有文件中不需要单独初始化本类
 * 可直接用 $dosql dosqli 或 $db 进行操作
 * 为了防止错误，操作完后不必关闭数据库
 */


//初始化类
$dosql = new MySql(false);


class MySql
{
    var $db_host;
    var $db_user;
    var $db_pwd;
    var $db_name;
    var $db_tablepre;

	var $linkid;
    var $result;
    var $querystring;
    var $isclose;
	var $safecheck;


    //用外部定义的变量初始类，并连接数据库
    function __construct($pconnect=false,$nconnect=true)
    {
        $this->isclose   = false;
		$this->safecheck = true;
        if($nconnect)
        {
            $this->Init($pconnect);
        }
    }


	//pconnect为长连接，nconnect为短连接
    function MySql($pconnect=false, $nconnect=true)
    {
        $this->__construct($pconnect, $nconnect);
    }


	//初始化变量
    function Init($pconnect=false)
    {
        $this->db_host      = $GLOBALS['db_host'];
        $this->db_user      = $GLOBALS['db_user'];
        $this->db_pwd       = $GLOBALS['db_pwd'];
        $this->db_name      = $GLOBALS['db_name'];
        $this->db_tablepre  = $GLOBALS['db_tablepre'];
        $this->linkid       = 0;
		$this->result['me'] = 0;
        $this->querystring  = '';

		$this->Open($pconnect);
    }


    //打开数据库
    function Open($pconnect=false)
    {
        global $dosql;

        //连接数据库
        if($dosql && !$dosql->isclose)
        {
            $this->linkid = $dosql->linkid;
        }
        else
        {
			//连接前验证参数是否为空
			if($this->db_host == '' || $this->db_user == '' || $this->db_name == '')
			{
				if(!file_exists(PHPMYWIND_DATA . '/install_lock.txt'))
				{
					if(file_exists(PHPMYWIND_DATA . '/../install/index.php'))
					{
						header('location:install/index.php');
						exit;
					}
				}
			}

            $i = 0;
            while(!$this->linkid)
            {
                if($i > 100) break;

                if(!$pconnect)
                {
                    $this->linkid = @mysql_connect($this->db_host, $this->db_user, $this->db_pwd);
                }
                else
                {
                    $this->linkid = @mysql_pconnect($this->db_host, $this->db_user, $this->db_pwd);
                }

                $i++;
            }
        }

        if(!$this->linkid)
        {
			$this->DisplayError('连接数据库失败，可能数据库密码不对或数据库服务器出错！');
            exit();
        }

        if($this->db_name && !@mysql_select_db($this->db_name))
		{
			$this->DisplayError('无法使用数据库！');
			exit();
		}

        $db_info = explode('.',$this->GetVersion());
        $db_info = $db_info[0].'.'.$db_info[1];

        if($db_info > '4.1' && $GLOBALS['db_charset'])
        {
            mysql_query("SET NAMES '".$GLOBALS['db_charset']."', character_set_client=binary, interactive_timeout=3600;", $this->linkid);
        }

		if($db_info >= '5.0')
		{
            mysql_query("SET sql_mode=''");
        }

        return true;
    }


    //设置SQL语句，会自动把SQL语句里的#@__替换为$this->db_tablepre(在配置文件中为$db_tablepre)
    function SetQuery($sql)
    {
		$prefix = '#@__';
        $this->querystring = str_replace($prefix, $this->db_tablepre, $sql);
    }


	//执行一个带返回结果的SQL语句，如SELECT，SHOW等
	function Query($sql='',$id='me')
	{
		$this->Execute($sql,$id);
	}


	//执行一个不返回结果的SQL语句，如update,delete,insert等
	function QueryNone($sql='')
	{
		$this->ExecNoneQuery($sql);
	}


    //执行一个带返回结果的SQL语句，如SELECT，SHOW等 $id的用途在于区别每个记录集
    function Execute($sql, $id='me')
    {
        global $dosql;

        if($dosql->isclose)
        {
            $this->Open(false);
            $dosql->isclose = false;
        }

        if(!empty($sql))
        {
            $this->SetQuery($sql);
        }
		else
		{
            return false;
        }

		//SQL语句安全检查
        if($this->safecheck)
        {
            $this->CheckSql($this->querystring);
        }

        $this->result[$id] = mysql_query($this->querystring, $this->linkid);

        if(empty($this->result[$id]) && $this->result[$id]===false)
        {
            $this->DisplayError(mysql_error().' Error sql: '.$this->querystring);
			exit();
        }
    }


	//执行一个不返回结果的SQL语句，如update,delete,insert等
    function ExecNoneQuery($sql='')
    {
        global $dosql;

        if($dosql->isclose)
        {
            $this->Open(false);
            $dosql->isclose = false;
        }

        if(!empty($sql))
        {
            $this->SetQuery($sql);
        }
		else
		{
            return false;
        }

		//SQL语句安全检查
        if($this->safecheck)
        {
            $this->CheckSql($this->querystring,'update');
        }

        if(mysql_query($this->querystring, $this->linkid))
		{
			return true;
		}
		else
		{
			$this->DisplayError(mysql_error().' Error sql: '.$this->querystring);
			exit();
		}
    }


    //执行一个不与任何表名有关的SQL语句,Create等
    function ExecuteSafeQuery($sql,$id='me')
    {
        global $dosql;

        if($dosql->isclose)
        {
            $this->Open(false);
            $dosql->isclose = false;
        }

        $this->result[$id] = mysql_query($this->linkid,$sql);
    }


    //执行一个SQL语句,返回前一条记录或仅返回一条记录
    function GetOne($sql='',$acctype=MYSQL_ASSOC)
    {
        global $dosql;

        if($dosql->isclose)
        {
            $this->Open(false);
            $dosql->isclose = false;
        }

        if(!empty($sql))
        {
            if(!preg_match("/LIMIT/i", $sql)) $this->SetQuery(preg_replace("/[,;]$/i", '', trim($sql))." LIMIT 0,1;");
            else $this->SetQuery($sql);
        }
		else
		{
            return false;
        }

        $this->Execute($sql,'one');
        $res = $this->GetArray('one', $acctype);
        if(!is_array($res))
        {
            return '';
        }
        else
        {
			$this->FreeResult('one');
			return($res);
        }
    }


    //返回当前的一条记录并把游标移向下一记录
    //MYSQL_ASSOC、MYSQL_NUM、MYSQL_BOTH
    function GetArray($id='me',$acctype=MYSQL_ASSOC)
    {
        if($this->result[$id]==0)
        {
            return false;
        }
        else
        {
            return @mysql_fetch_array($this->result[$id],$acctype);
        }
    }


	//以对象的形式放回当前的一条记录并把游标移向下一记录
    function GetObject($id='me')
    {
        if($this->result[$id]===0)
        {
            return false;
        }
        else
        {
            return mysql_fetch_object($this->result[$id]);
        }
    }


    //检测是否存在某数据表
    function IsTable($tbname)
    {
        $prefix = '#@__';
        $tbname = str_replace($prefix, $this->db_tablepre, $tbname);
        if(mysql_num_rows(@mysql_query("SHOW TABLES LIKE '".$tbname."'", $this->linkid)))
        {
            return true;
        }
        return false;
	}


    //获得MySql的版本号
    function GetVersion($isformat=true)
    {
        global $dosql;

      	if(@$dosql->isclose)
        {
            $this->Open(false);
            $dosql->isclose = false;
        }

        $res = @mysql_query("SELECT VERSION();", $this->linkid);
        $row = @mysql_fetch_array($res);;
        $mysql_version = $row[0];
        @mysql_free_result($res);
        if($isformat)
        {
            $mysql_versions = explode(".",trim($mysql_version));
            $mysql_version = number_format($mysql_versions[0].".".$mysql_versions[1],2);
        }
        return $mysql_version;
    }


    //获取特定表的信息
    function GetTableFields($tbname,$id='me')
    {
        $this->result[$id] = mysql_list_fields($this->db_name, $tbname, $this->linkid);
    }


    //获取字段详细信息
    function GetFieldObject($id='me')
    {
        return mysql_fetch_field($this->result[$id]);
    }


    //获得查询的总记录数
    function GetTotalRow($id='me')
    {
        if($this->result[$id]==0)
        {
            return -1;
        }
        else
        {
            return mysql_num_rows($this->result[$id]);
        }
    }


	//获得指定表数据总记录数
	function GetTableRow($tbname='',$siteid='',$field="id")
	{
		if($tbname == '') return false;

		//是否区分站点
		if($siteid == '')
			$sql = "SELECT `$field` FROM `$tbname`";
		else
			$sql = "SELECT `$field` FROM `$tbname` WHERE `siteid`='$siteid'";

		$this->Execute($sql);
		return $this->GetTotalRow();
	}


    //获取上一步INSERT操作产生的ID
    function GetLastID()
    {
        //如果 AUTO_INCREMENT 的列的类型是 BIGINT，则 mysql_insert_id() 返回的值将不正确。
        //可以在 SQL 查询中用 MySQL 内部的 SQL 函数 LAST_INSERT_ID() 来替代。
        //$rs = mysql_query("Select LAST_INSERT_ID() as lid",$this->linkid);
        //$row = mysql_fetch_array($rs);
        //return $row["lid"];
        return mysql_insert_id($this->linkid);
    }


	//释放记录集占用的资源
    function FreeResult($id='me')
    {
        @mysql_free_result($this->result[$id]);
    }


	//释放全部记录集占用的资源
    function FreeResultAll()
    {
        if(!is_array($this->result))
        {
            return '';
        }
        foreach($this->result as $k=>$v)
        {
            if($v)
            {
                @mysql_free_result($v);
            }
        }
    }


    //关闭数据库
    //mysql能自动管理非持久连接的连接池
    //实际上关闭并无意义并且容易出错，所以取消这函数
    function Close($isok=false)
    {
        $this->FreeResultAll();

        if($isok)
        {
            @mysql_close($this->linkid);
            $this->isclose = true;
            $GLOBALS['dosql'] = NULL;
        }
    }


    //关闭指定的数据库连接
    function CloseLink($dblink)
    {
        @mysql_close($dblink);
    }


    /*
	 * 显示数据链接错误信息
	 *
	 * @param  string  $msg  错误信息
	 * @param  int     $t    错误类型
	 */
    function DisplayError($msg,$t=0)
    {
		global $cfg_diserror;

		//向浏览器输出错误
		switch($t)
		{
			case 0:
			$title = 'PHPMyWind安全警告：MySql Error！';
			break;
			case 1:
			$title = 'PHPMyWind安全警告：请检查您的SQL语句是否合法，您的操作将被强制停止！';
			break;
			default;
		}

        $str  = '<div style="font-family:\'微软雅黑\';font-size:12px;">';
		$str .= '<h3 style="margin:0;padding:0;line-height:30px;color:red;">'.$title.'</h3>';
        $str .= '<strong>错误文件</strong>：'.GetCurUrl().'<br />';
        $str .= '<strong>错误信息</strong>：'.$msg.'';
        $str .= '</div>';

		//判断是否输出错误提示
        if($cfg_diserror == 'Y') echo $str;


		//保存MySql错误日志
		$userIP  = GetIP();
		$getUrl  = GetCurUrl();
		$getTime = GetDateTime(time());
		$logfile = dirname(__FILE__).'/../data/error/mysql_error_trace.php';

		$savemsg = '<?php exit(); ?> Time: '.$getTime.'. || Page: '.$getUrl.' || IP: '.$userIP.' || Error: '.$msg."\r\n";
        Writef($logfile, $savemsg, 'a+');


		//危险错误，强制停止
		if($t == 1) exit();
    }


	//SQL语句过滤程序，由80sec提供，这里作了适当的修改
    function CheckSql($sql, $querytype='select')
    {
        $clean   = '';
        $error   = '';
		$pos     = -1;
        $old_pos = 0;


        //如果是普通查询语句，直接过滤一些特殊语法
        if($querytype == 'select')
        {
            if(preg_match('/[^0-9a-z@\._-]{1,}(union|sleep|benchmark|load_file|outfile)[^0-9a-z@\.-]{1,}/', $sql))
            {
				$this->DisplayError("$sql||SelectBreak",1);
            }
        }

        //完整的SQL检查
        while(true)
        {
            $pos = strpos($sql, '\'', $pos + 1);
            if($pos === false)
            {
                break;
            }
            $clean .= substr($sql, $old_pos, $pos - $old_pos);

            while(true)
            {
                $pos1 = strpos($sql, '\'', $pos + 1);
                $pos2 = strpos($sql, '\\', $pos + 1);
                if($pos1 === false)
                {
                    break;
                }
                else if($pos2 == false || $pos2 > $pos1)
                {
                    $pos = $pos1;
                    break;
                }
                $pos = $pos2 + 1;
            }

            $clean .= '$s$';
            $old_pos = $pos + 1;
        }

        $clean .= substr($sql, $old_pos);
        $clean  = trim(strtolower(preg_replace(array('~\s+~s' ), array(' '), $clean)));

        //老版本的Mysql并不支持union，常用的程序里也不使用union，但是一些黑客使用它，所以检查它
        if(strpos($clean, 'union') !== false && preg_match('~(^|[^a-z])union($|[^[a-z])~s', $clean) != 0)
        {
            $fail  = true;
            $error = 'union detect';
        }

        //发布版本的程序可能比较少包括--,#这样的注释，但是黑客经常使用它们
        else if(strpos($clean, '/*') > 2 || strpos($clean, '--') !== false || strpos($clean, '#') !== false)
        {
            $fail  = true;
            $error = 'comment detect';
        }

        //这些函数不会被使用，但是黑客会用它来操作文件，down掉数据库
        else if(strpos($clean, 'sleep') !== false && preg_match('~(^|[^a-z])sleep($|[^[a-z])~s', $clean) != 0)
        {
            $fail  = true;
            $error = 'slown down detect';
        }
        else if(strpos($clean, 'benchmark') !== false && preg_match('~(^|[^a-z])benchmark($|[^[a-z])~s', $clean) != 0)
        {
            $fail  = true;
            $error = 'slown down detect';
        }
        else if(strpos($clean, 'load_file') !== false && preg_match('~(^|[^a-z])load_file($|[^[a-z])~s', $clean) != 0)
        {
            $fail  = true;
            $error = 'file fun detect';
        }
        else if(strpos($clean, 'into outfile') !== false && preg_match('~(^|[^a-z])into\s+outfile($|[^[a-z])~s', $clean) != 0)
        {
            $fail  = true;
            $error = 'file fun detect';
        }

        //老版本的MYSQL不支持子查询，我们的程序里可能也用得少，但是黑客可以使用它来查询数据库敏感信息
        else if(preg_match('~\([^)]*?select~s', $clean) != 0)
        {
            $fail  = true;
            $error = 'sub select detect';
        }

        if(!empty($fail))
        {
			$this->DisplayError("$sql,$error",1);
        }
        else
        {
            return $sql;
        }
    }
}
?>
