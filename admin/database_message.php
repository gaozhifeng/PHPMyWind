<?php if(!defined('IN_BKUP')) exit('Request Error!'); ?>

<table width="100%" border="0" cellpadding="0" cellspacing="0" class="dataTable">
	<tr align="center" class="dataTrOn">
		<td height="200">
		<?php
		if($url_forward=='-1' || $url_forward=='')
		{
			echo $msg;
		?>
			<br />
			<br />
			<a href="javascript:history.go(-1);">[点这里返回上一页]</a>
		<?php
		}

		else if($url_forward == "close")
		{
			echo $msg;
		?>
			<br />
			<br />
			<a href="javascript:window.close();">[点这里关闭本页]</a>
			<?php
		}

		else if($url_forward)
		{
		?>
			<img src="templates/images/loading.gif">
			<br />
			<br />
			<?php echo $msg; ?>
			<br />
			<br />
			<a href="<?php echo $url_forward; ?>">[如果您的浏览器没有自动跳转，请点击这里]</a>
			<script type="text/javascript">
			function Redirect(url)
			{
				location.href = url;
			}
			setTimeout("Redirect('<?php echo $url_forward; ?>');", <?php echo $ms; ?>);
			</script>
			<?php 
			}
			?>
		</td>
	</tr>
</table>