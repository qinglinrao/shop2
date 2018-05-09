<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
	<title>小商城管理系统</title>
	<link rel="shortcut icon" type="image/ico" href="/favicon.ico" />
	<link rel="stylesheet" type="text/css" href="/Public/Admin/layui/css/layui.css" />
	<link rel="stylesheet" type="text/css" href="/Public/Admin/resource/css/login.css" />
</head>
<body>
	<div id="large-header" class="login-header">
		<div class="swiper-container swiper-container-horizontal swiper-container-3d swiper-container-flip show">
			<div class="swiper-wrapper">
				<!--管理员登录-->
				<div class="swiper-slide loginBox swiper-no-swiping">
					<!--
					<div class="imgBox">
						<img src="/Public/Admin/resource/images/login_logo.png" />
					</div>
					-->
					<h3>小商城管理系统</h3>
					<form autocomplete="off" class="layui-form">
						<div class="layui-form-item">
							<div class="layui-input-block">
								<input type="text" name="username" lay-verify="required" placeholder="请输入登录账号" class="layui-input">
							</div>
						</div>
						<div class="layui-form-item">
							<div class="layui-input-block">
								<input type="password" name="userpass" lay-verify="required" placeholder="请输入登录密码" class="layui-input">
							</div>
						</div>
						<div class="layui-form-item">
							<div class="layui-input-inline verifycode">
								<input type="text" name="usercode" lay-verify="required|number" placeholder="请输入验证数字" class="layui-input" onkeyup="value=value.replace(/[^\d]/gi,'');">
							</div>
							<div class="l30 pb-10 clearfix mt-10">
								<img src="<?php echo U('public/verify');?>" name="verify" id="verify" class="verify" onclick="javascript:document.getElementById('verify').src='<?php echo U('public/verify');?>'+'&random='+Math.random();" title="看不清？鼠标点一下更换验证数字" />
							</div>
						</div>
						<div class="layui-form-item loginBtnBox">
							<div class="layui-input-block">
								<button class="layui-btn" lay-submit lay-filter="loginSubmit">登录系统</button>
							</div>
						</div>
						<div class="copyright">技术支持：<a href="http://www.tldswl.com/" target="_blank">baozhjian</a></div>
					</form>
				</div>
			</div>
		</div>
		<canvas id="login-canvas"></canvas>
	</div>
	<script src="/Public/Admin/layui/layui.js"></script>
	<script src="/Public/Admin/resource/js/tweenlite.min.js"></script>
	<script src="/Public/Admin/resource/js/easepack.min.js"></script>
	<script src="/Public/Admin/resource/js/canvas.js"></script>
	<script>
	layui.use(['form','jquery'], function() {
		var form = layui.form();
		$ = layui.jquery;
		form.on('submit(loginSubmit)', function(data) {
			$.post("<?php echo U('public/login');?>",data.field,function(res) {
				if (res.status == 'success') {
					layer.msg(res.msg, {icon: 1, shift:5, time:2000}, function(){
						window.location.href = "<?php echo U('index/index');?>";
					});
				} else {
					layer.msg(res.msg, {icon: 2, shift:6});
					$("[name='verify']").attr("src","<?php echo U('public/verify');?>"+"&random="+ Math.random());
					if (res.type == 1) {
						$("input[name='username']").val('');
						$("input[name='userpass']").val('');
					}
					if (res.type == 2) {
						$("input[name='userpass']").val('');
					}
					$("input[name='usercode']").val('');
				}
			});
			return false;
		});
	});
	</script>
</body>
</html>