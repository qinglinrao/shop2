<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
	<title>小商城管理系统</title>
	<link rel="shortcut icon" type="image/ico" href="/favicon.ico" />
	<link rel="stylesheet" type="text/css" href="/Public/Admin/layui/css/layui.css" />
	<link rel="stylesheet" type="text/css" href="/Public/Admin/resource/css/style.css" />
</head>
<body>
	<div class="layui-layout layui-layout-admin skin-0">
		<!-- header -->
		<div class="layui-header my-header">
			<a href="<?php echo U('Index/index');?>"><div class="my-header-logo">小商城管理系统</div></a>
			<div class="my-header-btn">
				<button class="layui-btn layui-btn-small btn-nav"><i class="layui-icon">&#xe603;&#xe602;</i></button>
			</div>
			<ul class="layui-nav" lay-filter="side-left">
				<li class="layui-nav-item"><a href="javascript:void(0);"><i class="layui-icon">&#xe63c;</i>快捷操作</a></li>
				<li class="layui-nav-item"><a href="javascript:void(0);"><i class="layui-icon">&#xe613;</i>用户管理</a></li>
				<li class="layui-nav-item"><a href="javascript:void(0);"><i class="layui-icon">&#xe600;</i>商品、订单管理</a></li>
				<li class="layui-nav-item"><a href="javascript:void(0);"><i class="layui-icon">&#xe632;</i>促销管理</a></li>
				<!--<li class="layui-nav-item"><a href="javascript:void(0);"><i class="layui-icon">&#xe620;</i>系统管理</a></li>-->

			</ul>
			<ul class="layui-nav my-header-user-nav" lay-filter="side-right">
				<li class="layui-nav-item">
					<!--<a class="name" href="javascript:void(0);" id="cache"><i class="layui-icon">&#xe640;</i>清理缓存</a> -->
				</li>
				<li class="layui-nav-item">
					<a class="name" href="javascript:void(0);"><i class="layui-icon icon">&#xe61b;</i>风格主题</a>
					<dl class="layui-nav-child">
						<dd data-skin="0"><a href="javascript:void(0);"><i class="layui-icon">&#xe61e;</i>默认风格</a></dd>
						<dd data-skin="1"><a href="javascript:void(0);"><i class="layui-icon">&#xe61e;</i>纯白风格</a></dd>
						<dd data-skin="2"><a href="javascript:void(0);"><i class="layui-icon">&#xe61e;</i>蓝白风格</a></dd>
					</dl>
				</li>
				<li class="layui-nav-item">
					<a class="name" href="javascript:void(0);"><img src="/Public/Admin/resource/images/default_avatar.jpg" alt="avatar"><?php echo (session('admin_name')); ?></a>
					<dl class="layui-nav-child">
						<dd><a href="javascript:void(0);" href-url="<?php echo U('System/password');?>"><i class="layui-icon">&#xe61e;</i>修改密码</a></dd>
						<dd><a href="<?php echo U('Public/logout');?>"><i class="layui-icon">&#xe61e;</i>退出系统</a></dd>
					</dl>
				</li>
			</ul>
		</div>
		<!-- side -->
		<div class="layui-side my-side">
			<div class="layui-side-scroll">
				<ul class="layui-nav layui-nav-tree left-menu" lay-filter="side">
					<!-- 去除 layui-nav-itemed 即可关闭展开 -->
					<li class="layui-nav-item layui-nav-itemed">
						<a href="javascript:void(0);"><i class="layui-icon">&#xe63c;</i>快捷操作</a>
						<dl class="layui-nav-child">
							<dd class="layui-nav-item"><a href="javascript:void(0);" href-url="<?php echo U('Member/tjrlist');?>"><i class="layui-icon">&#xe621;</i>用户管理</a></dd>
							<dd class="layui-nav-item"><a href="javascript:void(0);" href-url="<?php echo U('Goods/index');?>"><i class="layui-icon">&#xe621;</i>商品管理</a></dd>
							<dd class="layui-nav-item"><a href="javascript:void(0);" href-url="<?php echo U('Evaluate/index');?>"><i class="layui-icon">&#xe621;</i>评论管理</a></dd>
							<dd class="layui-nav-item"><a href="javascript:void(0);" href-url="<?php echo U('Orders/last');?>"><i class="layui-icon">&#xe621;</i>订单统计</a></dd>
							
							<!--<dd class="layui-nav-item"><a href="javascript:void(0);" href-url="<?php echo U('Member/tjrbott');?>"><i class="layui-icon">&#xe621;</i>名下沃牧场</a></dd>-->
							<!--<dd class="layui-nav-item"><a href="javascript:void(0);" href-url="<?php echo U('Member/dlrlist');?>"><i class="layui-icon">&#xe621;</i>沃牧场管理</a></dd>-->
							<!--<dd class="layui-nav-item"><a href="javascript:void(0);" href-url="<?php echo U('Member/area');?>"><i class="layui-icon">&#xe621;</i>沃牧场区域管理</a></dd>-->
							<!--<dd class="layui-nav-item"><a href="javascript:void(0);" href-url="<?php echo U('GoodsType/index');?>"><i class="layui-icon">&#xe621;</i>商品类别管理</a></dd>-->
							<!--<dd class="layui-nav-item"><a href="javascript:void(0);" href-url="<?php echo U('Goods/index');?>"><i class="layui-icon">&#xe621;</i>商品信息管理</a></dd>-->
							<!--<dd class="layui-nav-item"><a href="javascript:void(0);" href-url="<?php echo U('GoodsTj/index');?>"><i class="layui-icon">&#xe621;</i>商品推荐管理</a></dd>-->
							<!--<dd class="layui-nav-item"><a href="javascript:void(0);" href-url="<?php echo U('Orders/index');?>"><i class="layui-icon">&#xe621;</i>待处理订单-->
							<!--　<b style="color: red"><?php echo ($num); ?></b></a></dd>-->
						</dl>
					</li>
				</ul>
				<ul class="layui-nav layui-nav-tree left-menu" lay-filter="side">
					<!-- 去除 layui-nav-itemed 即可关闭展开 -->
					<li class="layui-nav-item layui-nav-itemed">
						<a href="javascript:void(0);"><i class="layui-icon">&#xe613;</i>用户相关</a>
						<dl class="layui-nav-child">
							<dd class="layui-nav-item"><a href="javascript:void(0);" href-url="<?php echo U('Member/tjrlist');?>"><i class="layui-icon">&#xe621;</i>用户管理</a></dd>
							<!--<dd class="layui-nav-item"><a href="javascript:void(0);" href-url="<?php echo U('Excel/index');?>"><i class="layui-icon">&#xe621;</i>数据导出</a></dd>-->
						</dl>
					</li>
				</ul>
				<ul class="layui-nav layui-nav-tree left-menu" lay-filter="side">
					<li class="layui-nav-item layui-nav-itemed">
						<a href="javascript:void(0);"><i class="layui-icon">&#xe600;</i>商品相关</a>
						<dl class="layui-nav-child">
							<dd class="layui-nav-item"><a href="javascript:void(0);" href-url="<?php echo U('GoodsType/index');?>"><i class="layui-icon">&#xe621;</i>类别管理</a></dd>
							<dd class="layui-nav-item"><a href="javascript:void(0);" href-url="<?php echo U('GoodsTag/index');?>"><i class="layui-icon">&#xe621;</i>标签管理</a></dd>
							<dd class="layui-nav-item"><a href="javascript:void(0);" href-url="<?php echo U('Goods/index');?>"><i class="layui-icon">&#xe621;</i>商品管理</a></dd>
							<dd class="layui-nav-item"><a href="javascript:void(0);" href-url="<?php echo U('Team/index');?>"><i class="layui-icon">&#xe621;</i>拼团管理</a></dd>
							<dd class="layui-nav-item"><a href="javascript:void(0);" href-url="<?php echo U('GoodsTj/index');?>"><i class="layui-icon">&#xe621;</i>推荐管理</a></dd>
							<dd class="layui-nav-item"><a href="javascript:void(0);" href-url="<?php echo U('Evaluate/index');?>"><i class="layui-icon">&#xe621;</i>评论管理</a></dd>
						</dl>
					</li>
					<li class="layui-nav-item layui-nav-itemed">
						<a href="javascript:void(0);"><i class="layui-icon">&#xe600;</i>订单相关</a>
						<dl class="layui-nav-child">
							<dd class="layui-nav-item"><a href="javascript:void(0);" href-url="<?php echo U('Orders/index');?>"><i class="layui-icon">&#xe621;</i>订单列表</a></dd>
							<dd class="layui-nav-item"><a href="javascript:void(0);" href-url="<?php echo U('Orders/last');?>"><i class="layui-icon">&#xe621;</i>订单统计</a></dd>
						</dl>
					</li>
				</ul>
				<ul class="layui-nav layui-nav-tree left-menu" lay-filter="side">
					<li class="layui-nav-item layui-nav-itemed">
						<a href="javascript:void(0);"><i class="layui-icon">&#xe632;</i>促销相关</a>
						<dl class="layui-nav-child">
							<dd class="layui-nav-item"><a href="javascript:void(0);" href-url="<?php echo U('Promotion/index',array('type'=>1));?>"><i class="layui-icon">&#xe621;</i>买n送n</a></dd>
						</dl>
						<dl class="layui-nav-child">
							<dd class="layui-nav-item"><a href="javascript:void(0);" href-url="<?php echo U('Promotion/index',array('type'=>2));?>"><i class="layui-icon">&#xe621;</i>买n打n折</a></dd>
						</dl>
					</li>
					<!--<li class="layui-nav-item layui-nav-itemed">-->
						<!--<a href="javascript:void(0);"><i class="layui-icon">&#xe632;</i>文章相关</a>-->
						<!--<dl class="layui-nav-child">-->
							<!--<dd class="layui-nav-item"><a href="javascript:void(0);" href-url="<?php echo U('ArtType/index');?>"><i class="layui-icon">&#xe621;</i>类别管理</a></dd>-->
							<!--<dd class="layui-nav-item"><a href="javascript:void(0);" href-url="<?php echo U('Art/index');?>"><i class="layui-icon">&#xe621;</i>文章管理</a></dd>-->
						<!--</dl>-->
					<!--</li>-->
				</ul>
				<ul class="layui-nav layui-nav-tree left-menu" lay-filter="side">
					<!--<li class="layui-nav-item layui-nav-itemed">-->
						<!--<a href="javascript:void(0);"><i class="layui-icon">&#xe620;</i>系统管理</a>-->
						<!--<dl class="layui-nav-child">-->
							<!--<dd class="layui-nav-item"><a href="javascript:void(0);" href-url="<?php echo U('System/System');?>"><i class="layui-icon">&#xe621;</i>系统设置</a></dd>-->
							<!--<dd class="layui-nav-item"><a href="javascript:void(0);" href-url="<?php echo U('System/admin');?>"><i class="layui-icon">&#xe621;</i>管理人员</a></dd>-->
							<!--<dd class="layui-nav-item"><a href="javascript:void(0);" href-url="<?php echo U('System/payment');?>"><i class="layui-icon">&#xe621;</i>支付接口</a></dd>-->
							<!--<dd class="layui-nav-item"><a href="javascript:void(0);" href-url="<?php echo U('System/sms');?>"><i class="layui-icon">&#xe621;</i>短信接口</a></dd>-->
						<!--</dl>-->
					<!--</li>-->
				</ul>
			</div>
		</div>
		<!-- body -->
		<div class="layui-body my-body">
			<div class="layui-tab layui-tab-card my-tab" lay-filter="card" lay-allowClose="true">
				<ul class="layui-tab-title">
					<li class="layui-this" lay-id="0"><span>管理中心</span></li>
				</ul>
				<div class="layui-tab-content">
					<div class="layui-tab-item layui-show">
						<iframe id="iframe" src="<?php echo U('Index/main');?>" frameborder="0"></iframe>
					</div>
				</div>
			</div>
		</div>
		<!-- footer -->
		<div class="layui-footer my-footer"><p>技术支持：<a href="http://www.tldswl.com/" target="_blank">baozhijian</a></p></div>
	</div>
	<script src="/Public/Admin/layui/layui.js"></script>
	<script src="/Public/Admin/resource/js/index.js"></script>
	<script>
	layui.use(['jquery'], function() {
		var $ = layui.jquery;
		$('#cache').on('click', function() {
			$.post("<?php echo U('System/cache');?>","",function(res) {
				layer.msg(res.msg);
			});
		});
	});
	</script>
</body>
</html>