<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
	<title>系统提示</title>
	<link rel="shortcut icon" type="image/ico" href="/favicon.ico" />
	<link rel="stylesheet" type="text/css" href="/Public/Admin/layui/css/layui.css" />
	<link rel="stylesheet" type="text/css" href="/Public/Admin/resource/css/style.css" />
</head>
<body>
	<div class="my-page-box">
		<?php if (isset($message)) {?>
		<img src="/Public/Admin/resource/images/icon_success.png" alt="成功">
		<p class="msg">操作成功</p>
		<p class="text">提示说明： <?php echo($message); ?></p>
		<?php } else {?>
		<img src="/Public/Admin/resource/images/icon_error.png" alt="失败">
		<p class="msg">操作失败</p>
		<p class="text">失败原因： <?php echo($error); ?></p>
		<?php }?>
		<div class="jump">本提示页面将自动跳转，等待时间：<span id="wait"><?php echo($waitSecond); ?></span> 秒，如果您不想等待，请点击 <a id="href" href="<?php echo($jumpUrl); ?>">此处</a> 手动跳转。</div>
	</div>
	<script src="/Public/Admin/layui/jquery-1.8.3.js"></script>
	<script type="text/javascript">
	$(function(){
		var href = document.getElementById('href').href;
		jumps(<?php echo($waitSecond); ?>, "#wait", href);
	});
	function jumps(count, id, link) {
		window.setTimeout(function () {
			count--;
			if (count > 0) {
				$(id).html(count);
				jumps(count, id, link);
			} else {
				location.href = link;
			}
		}, 1000);
	}
	</script>
</body>
</html>