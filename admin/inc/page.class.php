<?php	if(!defined('IN_PHPMYWIND')) exit('Request Error!');

/*
 * 分页类
 *
**************************
(C)2010-2015 phpMyWind.com
update: 2014-5-31 21:54:19
person: Feng
**************************
*/


$dopage = new Page();

class Page
{

	var $page;      //当前页码
	var $totalpage; //总共页数
	var $pagenum;   //每页记录数
	var $total;     //总共记录数

    function __construct()
    {
		$this->Init();
    }

    function Page()
    {
		$this->__construct();
    }

	function Init()
    {
		$this->page     = @$GLOBALS['page'];
		$this->pagenum  = @$GLOBALS['cfg_pagenum'];
    }

	//获取分页变量
	function GetPage($sql,$pagenum='', $ordermode='desc')
	{
		global $dosql;

		$dosql->Execute($sql);
		$this->total = $dosql->GetTotalRow();

		if(!empty($pagenum)) $this->pagenum = $pagenum;
		if(empty($this->pagenum)) $this->pagenum = 20;

		$this->totalpage = ceil($this->total / $this->pagenum);

		if(!isset($this->page) || !intval($this->page) || $this->page<=0 || $this->page > $this->totalpage)
		{
			$this->page = 1;
		}

		$startnum = ($this->page-1) * $this->pagenum;

		$sql .= " ORDER BY id $ordermode LIMIT $startnum, $this->pagenum";

		return $dosql->Execute($sql);
	}

	//显示分页列表
	function GetList()
	{
		$pagetxt = '';

		if($this->total <= $this->pagenum)
		{
			$pagetxt = '<div class="pageText">共<span>'.$this->totalpage.'</span>页<span>'.$this->total.'</span>条记录</div>';
		}

		else
		{
			//获取除page参数外的其他参数
			$query_string = explode('&',$_SERVER["QUERY_STRING"]);

			if($query_string[0] != '')
			{
				$query_string_temp = '';

				foreach($query_string as $k)
				{
					$query_string2 = explode('=', $k);
					if(strstr($query_string2[0],'page') == '')
					{
						$query_string2[0] = isset($query_string2[0]) ? $query_string2[0] : '';
						$query_string2[1] = isset($query_string2[1]) ? $query_string2[1] : '';

						$query_string_temp .= $query_string2[0].'='.$query_string2[1].'&';
					}
				}

				$nowurl = '?'.$query_string_temp;
			}
			else
			{
				$nowurl = '?';
			}

			$previous = $this->page - 1;

			if($this->totalpage == $this->page)
			{
				$next = $this->page;
			}
			else
			{
				$next = $this->page + 1;
			}

			$pagetxt = '<div class="pageList">';

			//上一页 第一页
			if($this->page > 1)
			{
				$pagetxt .= '<a href="'.$nowurl.'page=1">&lt;&lt;</a>';
				$pagetxt .= '<a href="'.$nowurl.'page='.$previous.'">&lt;</a>';
			}
			else
			{
				$pagetxt .= '<a href="javascript:;">&lt;&lt;</a>';
				$pagetxt .= '<a href="javascript:;">&lt;</a>';
			}

			//当总页数小于10
			if($this->totalpage < 10)
			{
				for($i=1; $i <= $this->totalpage; $i++)
				{
					if($this->page == $i)
					{
						$pagetxt .= '<a href="javascript:;" class="on">'.$i.'</a>';
					}
					else
					{
						$pagetxt .= '<a href="'.$nowurl.'page='.$i.'" class="num">'.$i.'</a>';
					}
				}
			}
			else
			{
				if($this->page==1 or $this->page==2 or $this->page==3)
				{
					$m = 1;
					$b = 7;
				}
				//如果页面大于前三页并且小于后三页则显示当前页前后各三页链接
				if($this->page>3 and $this->page<$this->totalpage-2)
				{
					$m = $this->page-3;
					$b = $this->page+3;
				}
				//如果页面为最后三页则显示最后7页链接
				if($this->page==$this->totalpage or $this->page==$this->totalpage-1 or $this->page==$this->totalpage-2)
				{
					$m = $this->totalpage - 7;
					$b = $this->totalpage;
				}
				if($this->page > 4)
				{
					$pagetxt .= '<a href="javascript:;">...</a>';
				}
				//显示数字页码
				for($i=$m; $i<=$b; $i++)
				{
					if($this->page == $i)
					{
						$pagetxt .= '<a href="'.$nowurl.'page='.$i.'" class="on">'.$i.'</a>';
					}
					else
					{
						$pagetxt .= '<a href="'.$nowurl.'page='.$i.'" class="num">'.$i.'</a>';
					}
				}
				if($this->page < $this->totalpage-3)
				{
					$pagetxt .= '<a href="javascript:;">...</a>';
				}
			}

			//下一页 最后页
			if($this->page < $this->totalpage)
			{
				$pagetxt .= '<a href="'.$nowurl.'page='.$next.'">&gt;</a>';
				$pagetxt .= '<a href="'.$nowurl.'page='.$this->totalpage.'">&gt;&gt;</a>';
			}
			else
			{
				$pagetxt .= '<a href="javascript:;">&gt;</a>';
				$pagetxt .= '<a href="javascript:;">&gt;&gt;</a>';
			}
			$pagetxt .= '</div>';
		}

		return $pagetxt;
	}



	//显示分页列表
	function GetListSmall()
	{
		$pagetxt = '';

		if($this->total <= $this->pagenum)
		{
			$pagetxt = '<div class="pageText">共<span>'.$this->total.'</span>条记录</div>';
		}

		else
		{
			//获取除page参数外的其他参数
			$query_string = explode('&',$_SERVER["QUERY_STRING"]);

			if($query_string[0] != '')
			{
				$query_string_temp = '';

				foreach($query_string as $k)
				{
					$query_string2 = explode('=', $k);
					if(strstr($query_string2[0],'page') == '')
					{
						$query_string2[0] = isset($query_string2[0]) ? $query_string2[0] : '';
						$query_string2[1] = isset($query_string2[1]) ? $query_string2[1] : '';

						$query_string_temp .= $query_string2[0].'='.$query_string2[1].'&';
					}
				}

				$nowurl = '?'.$query_string_temp;
			}
			else
			{
				$nowurl = '?';
			}

			$previous = $this->page - 1;

			if($this->totalpage == $this->page)
			{
				$next = $this->page;
			}
			else
			{
				$next = $this->page + 1;
			}

			$pagetxt = '<div class="pageList">';

			//上一页 第一页
			if($this->page > 1)
			{
				$pagetxt .= '<a href="'.$nowurl.'page=1">&lt;&lt;</a>';
				$pagetxt .= '<a href="'.$nowurl.'page='.$previous.'">&lt;</a>';
			}
			else
			{
				$pagetxt .= '<a href="javascript:;">&lt;&lt;</a>';
				$pagetxt .= '<a href="javascript:;">&lt;</a>';
			}

			//显示页码
			$pagetxt .= '<a href="javascript:;" class="on">'.$this->page.'</a>';


			//下一页 最后页
			if($this->page < $this->totalpage)
			{
				$pagetxt .= '<a href="'.$nowurl.'page='.$next.'">&gt;</a>';
				$pagetxt .= '<a href="'.$nowurl.'page='.$this->totalpage.'">&gt;&gt;</a>';
			}
			else
			{
				$pagetxt .= '<a href="javascript:;">&gt;</a>';
				$pagetxt .= '<a href="javascript:;">&gt;&gt;</a>';
			}
			$pagetxt .= '</div>';
		}

		return $pagetxt;
	}


	//ajax分页
	function AjaxPage()
	{
		global $cid, $flag, $keyword;

		$pagetxt = '';

		//上一页
		$previous = $this->page - 1;

		//下一页
		if($this->totalpage == $this->page)
		{
			$next = $this->page;
		}
		else
		{
			$next = $this->page + 1;
		}

		if($this->total <= $this->pagenum)
		{
			$pagetxt = '<div class="pageText">共<span>'.$this->totalpage.'</span>页<span>'.$this->total.'</span>条记录</div>';
		}
		else
		{
			//正常的分页链接
			$pagetxt = '<div class="pageList">';

			//显示首页的裢接
			if($this->page > 1)
			{
				$pagetxt .= '<a href="javascript:;" onclick="PageList(\'1\',\''.$cid.'\',\''.$flag.'\',\''.$keyword.'\')">&lt;&lt;</a>';
				$pagetxt .= '<a href="javascript:;" onclick="PageList(\''.$previous.'\',\''.$cid.'\',\''.$flag.'\',\''.$keyword.'\')">&lt;</a>';
			}
			else
			{
				$pagetxt .= '<a href="javascript:;">&lt;&lt;</a>';
				$pagetxt .= '<a href="javascript:;">&lt;</a>';
			}

			//如果分页小于10页则显示正常分页链接否则显示带省略号的分页链接
			if($this->totalpage < 10)
			{
				//显示数字页码
				for($i=1; $i <= $this->totalpage; $i++)
				{
					if($this->page == $i)
					{
						$pagetxt .= '<a href="javascript:;" class="on">'.$i.'</a>';
					}
					else
					{
						$pagetxt .= '<a href="javascript:;" onclick="PageList(\''.$i.'\',\''.$cid.'\',\''.$flag.'\',\''.$keyword.'\')" class="num">'.$i.'</a>';
					}
				}
			}
			else
			{
				//如果页面为前三页则显示1到7页链接
				if($this->page==1 or $this->page==2 or $this->page==3)
				{
					$m = 1;
					$b = 7;
				}

				//如果页面大于前三页并且小于后三页则显示当前页前后各三页链接
				if ($this->page>3 and $this->page<$this->totalpage-2)
				{
					$m = $this->page-3;
					$b = $this->page+3;
				}

				//如果页面为最后三页则显示最后7页链接
				if($this->page==$this->totalpage or $this->page==$this->totalpage-1 or $this->page==$this->totalpage-2)
				{
					$m = $this->totalpage-7;
					$b = $this->totalpage;
				}
				if($this->page > 4)
				{
					$pagetxt .= '<a href="javascript:;">...</a>';
				}

				//显示数字页码
				for($i=$m; $i <= $b; $i++)
				{
					if($this->page == $i)
					{
						$pagetxt .= '<a href="javascript:;" class="on">'.$i.'</a>';
					}
					else
					{
						$pagetxt .= '<a href="javascript:;" onclick="PageList(\''.$i.'\',\''.$cid.'\',\''.$flag.'\',\''.$keyword.'\')" class="num">'.$i.'</a>';
					}
				}
				if($this->page < $this->totalpage-3)
				{
					$pagetxt .= '<a href="javascript:;">...</a>';
				}
			}

			//显示下一页的裢接
			if($this->page < $this->totalpage)
			{
				$pagetxt .= '<a href="javascript:;" onclick="PageList(\''.$next.'\',\''.$cid.'\',\''.$flag.'\',\''.$keyword.'\')">&gt;</a>';
				$pagetxt .= '<a href="javascript:;" onclick="PageList(\''.$this->totalpage.'\',\''.$cid.'\',\''.$flag.'\',\''.$keyword.'\')">&gt;&gt;</a>';
			}
			else
			{
				$pagetxt .= '<a href="javascript:;">&gt;</a>';
				$pagetxt .= '<a href="javascript:;">&gt;&gt;</a>';
			}

			$pagetxt .= '</div>';
		}

		return $pagetxt;
	}


	//ajax分页
	function AjaxPageSmall()
	{
		global $cid, $flag, $keyword;

		$pagetxt = '';

		//上一页
		$previous = $this->page - 1;

		//下一页
		if($this->totalpage == $this->page)
		{
			$next = $this->page;
		}
		else
		{
			$next = $this->page + 1;
		}

		if($this->total <= $this->pagenum)
		{
			$pagetxt = '<div class="pageText">共<span>'.$this->total.'</span>条记录</div>';
		}
		else
		{
			//正常的分页链接
			$pagetxt = '<div class="pageList">';

			//显示首页的裢接
			if($this->page > 1)
			{
				$pagetxt .= '<a href="javascript:;" onclick="PageList(\'1\',\''.$cid.'\',\''.$flag.'\',\''.$keyword.'\')">&lt;&lt;</a>';
				$pagetxt .= '<a href="javascript:;" onclick="PageList(\''.$previous.'\',\''.$cid.'\',\''.$flag.'\',\''.$keyword.'\')">&lt;</a>';
			}
			else
			{
				$pagetxt .= '<a href="javascript:;">&lt;&lt;</a>';
				$pagetxt .= '<a href="javascript:;">&lt;</a>';
			}

			//显示页码
			$pagetxt .= '<a href="javascript:;" class="on">'.$this->page.'</a>';


			//显示下一页的裢接
			if($this->page < $this->totalpage)
			{
				$pagetxt .= '<a href="javascript:;" onclick="PageList(\''.$next.'\',\''.$cid.'\',\''.$flag.'\',\''.$keyword.'\')">&gt;</a>';
				$pagetxt .= '<a href="javascript:;" onclick="PageList(\''.$this->totalpage.'\',\''.$cid.'\',\''.$flag.'\',\''.$keyword.'\')">&gt;&gt;</a>';
			}
			else
			{
				$pagetxt .= '<a href="javascript:;">&gt;</a>';
				$pagetxt .= '<a href="javascript:;">&gt;&gt;</a>';
			}

			$pagetxt .= '</div>';
		}

		return $pagetxt;
	}
}
?>
