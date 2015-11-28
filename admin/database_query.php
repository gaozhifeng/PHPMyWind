<?php if(!defined('IN_BKUP')) exit('Request Error!'); ?>

<form name="form" id="form" method="post" action="?action=query">
	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="dataTable">
		<tr class="dataTr">
			<td align="center" valign="middle" class="textAreaBox"><textarea name="sqlquery"></textarea></td>
		</tr>
	</table>
	<div class="bottomToolbar"> <span class="selArea">
		<input value="0" type="radio" name="querytype" />
		单行命令（支持简单查询）
		<input value="2" checked="checked" type="radio" name="querytype" />
		多行命令 </span>
		<input type="hidden" name="dopost" value="runsql" />
		<a href="#" onclick="form.submit();" class="dataBtn">执行语句</a> </div>
</form>
