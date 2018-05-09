<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
	<title>商品管理</title>
	<link rel="shortcut icon" type="image/ico" href="/favicon.ico" />
	<link rel="stylesheet" type="text/css" href="/Public/Admin/layui/css/layui.css" />
	<link rel="stylesheet" type="text/css" href="/Public/Admin/resource/css/style.css" />
</head>
<body class="body">
	<ul class="layui-tab-title mt-page mb-search">
		<li class="layui-this"><a href="javascript:void(0);">商品列表</a></li>
		<li><a href="<?php echo U('Goods/add');?>">添加商品</a></li>
		<li><a href="<?php echo U('Goods/addmore');?>">批量导入商品</a></li>
	</ul>
	<div class="layui-form-item layui-form">
		<div class="layui-input-inline">
			<input type="text" name="keyword" value="<?php echo ($keyword); ?>" placeholder="输入商品名称" class="layui-input">
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
					<th>轮播图第一张</th>
					<th>主标题</th>
					<!--<th>名称</th>-->
					<th>类别</th>
					<th>国家</th>
					<!--<th>规格</th>-->
					<th>原价</th>
					<th>零售价</th>
					<th>团购价</th>
					<th>运费</th>
					<!--<th>销量</th>-->
					<th>状态</th>
					<th>推荐状态</th>
					<th>拼团状态</th>
					<th>促销标签</th>
					<th>普通标签</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
				<?php if(!empty($list)): if(is_array($list)): foreach($list as $key=>$vo): ?><tr>
					<td><?php echo ($vo["id"]); ?></td>
					<td><div style="width: 50px;height: 50px;margin:0 auto;"><img src="<?php echo ($vo["goods_img"]); ?>" style="width: 100%;height: 100%;"/></div></td>
					<td><?php echo ($vo["goods_title"]); ?></td>
					<td><?php echo ($vo["tname"]); ?></td>
					<td><?php echo ($vo["goods_country"]); ?></td>
					<!--<td><?php echo ($vo["goods_gg"]); ?></td>-->
					<td><?php echo ($vo["goods_toprice"]); ?></td>
					<td><?php echo ($vo["goods_trprice"]); ?></td>
					<td><?php echo ($vo["goods_twprice"]); ?></td>
					<td><?php echo ($vo["goods_price"]); ?></td>
					<td>
						<?php if(($vo["goods_stats"]) == "1"): ?>出售中　<button class="layui-btn layui-btn-mini stopsell"
							 rule="<?php echo ($vo["id"]); ?>">下架</button><?php endif; ?>
						<?php if(($vo["goods_stats"]) == "2"): ?>已下架　<button class="layui-btn layui-btn-mini startsell"
							 rule="<?php echo ($vo["id"]); ?>">上架</button><?php endif; ?> 
					</td>
					<td>
						<?php if(($vo["is_tj"]) == "1"): ?>已推荐<?php endif; ?>
						<?php if(($vo["is_tj"]) == "0"): ?><a class="layui-btn layui-btn-mini" href="<?php echo U('Goods/tj',array('id'=>$vo['id']));?>">推荐</a><?php endif; ?>
					</td>
					<td>
						<?php if(($vo["goods_istuan"]) > "0"): echo ($vo["tuan_name"]); ?> <a class="layui-btn layui-btn-mini" href="<?php echo U('Goods/goods_istuan',array('id'=>$vo['id'],'type'=>2));?>">编辑</a><?php endif; ?>
						<?php if(($vo["goods_istuan"]) == "0"): ?>无 <a class="layui-btn layui-btn-mini" href="<?php echo U('Goods/goods_istuan',array('id'=>$vo['id'],'type'=>1));?>">添加</a><?php endif; ?>
					</td>
					<td>
						<?php if(($vo["goods_promotion"]) > "0"): echo ($vo["pro_name"]); ?> <a class="layui-btn layui-btn-mini" href="<?php echo U('Goods/goods_promotion',array('id'=>$vo['id'],'type'=>2));?>">编辑</a><?php endif; ?>
						<?php if(($vo["goods_promotion"]) == "0"): ?>无 <a class="layui-btn layui-btn-mini" href="<?php echo U('Goods/goods_promotion',array('id'=>$vo['id'],'type'=>1));?>">添加</a><?php endif; ?>
					</td>
					<td>
						<?php if(($vo["goods_tag"]) > "0"): echo ($vo["tag_name"]); ?> <a class="layui-btn layui-btn-mini" href="<?php echo U('Goods/goods_tag',array('id'=>$vo['id'],'type'=>2));?>">编辑</a><?php endif; ?>
						<?php if(($vo["goods_tag"]) == "0"): ?>无 <a class="layui-btn layui-btn-mini" href="<?php echo U('Goods/goods_tag',array('id'=>$vo['id'],'type'=>1));?>">添加</a><?php endif; ?>
					</td>
					<td>
						<a class="layui-btn layui-btn-mini" href="<?php echo U('Goods/edit',array('id'=>$vo['id']));?>">编辑</a>
						<a class="layui-btn layui-btn-mini" href="<?php echo U('Goods/configlist',array('id'=>$vo['id']));?>">规格列表</a>
						<a class="layui-btn layui-btn-mini layui-btn-danger" href="<?php echo U('Goods/del',array('id'=>$vo['id']));?>">删除</a>
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
			window.location.href = '<?php echo U("Goods/index");?>&keyword='+ keyword;
		});
	});
	</script>
	<script src="/Public/Admin/layui/jquery-1.8.3.js"></script>
	<script type="text/javascript">
		$('.startsell').click(function(){
			var idstr = $(this).attr('rule');
			window.location.href = '<?php echo U("Goods/sell");?>&goods_stats=1&id='+idstr;
		})
		$('.stopsell').click(function(){
			var idstr = $(this).attr('rule');
			window.location.href = '<?php echo U("Goods/sell");?>&goods_stats=2&id='+idstr;
		})
	</script>
</body>
</html>