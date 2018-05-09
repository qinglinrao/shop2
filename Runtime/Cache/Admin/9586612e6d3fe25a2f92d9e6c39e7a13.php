<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
	<title>代理人管理</title>
	<link rel="shortcut icon" type="image/ico" href="/favicon.ico" />
	<link rel="stylesheet" type="text/css" href="/Public/Admin/layui/css/layui.css" />
	<link rel="stylesheet" type="text/css" href="/Public/Admin/resource/css/style.css" />
</head>
<body class="body">
	<ul class="layui-tab-title mt-page mb-search">
		<li class="layui-this"><a href="javascript:void(0);">用户列表</a></li>
		<li><a href="<?php echo U('Member/tjr_add');?>">添加机器人用户</a></li>
	</ul>
	<div class="layui-form-item layui-form">
		<div class="layui-input-inline">
			<select name="utype">
				<option value="">用户类型</option>
				<option value="0">全部用户</option>
				<option value="1" <?php if($utype == 1): ?>selected<?php endif; ?>>下过单的用户</option>
				<option value="2" <?php if($utype == 2): ?>selected<?php endif; ?>>机器人用户</option>
			</select>
			<div class="layui-unselect layui-form-select layui-form-selected">
				<div class="layui-select-title">
					<input type="text" placeholder="用户类型" value="" readonly="" class="layui-input layui-unselect"><i class="layui-edge"></i>
				</div>
				<dl class="layui-anim layui-anim-upbit">
					<dd lay-value="0" class="">全部用户</dd>
					<dd lay-value="1" class="">下过单的用户</dd>
					<dd lay-value="2" class="">机器人用户</dd>
				</dl>
			</div>
		</div>
		<div class="layui-input-inline">
			<input type="text" name="keyword" value="<?php echo ($keyword); ?>" placeholder="输入姓名或电话" class="layui-input">
		</div>
		<div class="layui-inline">
			<button class="layui-btn" lay-submit lay-filter="search">查找</button>
		</div>
	</div>
	<div class="layui-form">
		<table class="layui-table" lay-even lay-skin="line">
			<thead>
				<tr>
					<th>编号</th>
					<th>姓名</th>
					<th>电话</th>
					<th>地址</th>
					<th>邮箱</th>
					<th>创建时间</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
				<?php if(!empty($list)): if(is_array($list)): foreach($list as $key=>$vo): ?><tr>
					<td><?php echo ($vo["id"]); ?></td>
					<td><?php echo ($vo["username"]); ?></td>
					<td><?php echo ($vo["phone"]); ?></td>
					<td><?php echo ($vo["address"]); ?></td>
					<td><?php echo ($vo["email"]); ?></td>
					<td><?php echo ($vo["create_at"]); ?></td>
					<td>
						<a class="layui-btn layui-btn-mini" href="<?php echo U('Member/tjr_edit',array('id'=>$vo['id']));?>">编辑</a> <a class="layui-btn layui-btn-mini layui-btn-danger" href="<?php echo U('Member/tjr_del',array('id'=>$vo['id']));?>">删除</a>
					</td>
				</tr><?php endforeach; endif; ?>
				<?php else: ?>
				<tr>
					<td colspan="9" class="nodata">没有相关数据</td>
				</tr><?php endif; ?>
			</tbody>
		</table>
	</div>
	<?php echo ($page); ?>
	<script src="/Public/Admin/layui/layui.js"></script>
	<script>
	layui.use(['form','jquery'], function() {
		var form = layui.form();
		$ = layui.jquery;
		form.on('submit(search)', function() {
			keyword = $("input[name='keyword']").val();
			utype = $("select[name='utype']").val();
			window.location.href = '<?php echo U("Member/tjrlist");?>&keyword='+ keyword + '&utype=' + utype;
		});
	});
	</script>
</body>
</html>