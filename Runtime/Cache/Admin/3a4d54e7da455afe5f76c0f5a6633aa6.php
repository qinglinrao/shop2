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
		<li class="layui-this"><a href="javascript:void(0);">类别列表</a></li>
		<li><a href="<?php echo U('GoodsType/add');?>">添加新类别</a></li>
	</ul>
	<div class="layui-form-item layui-form">
		
	</div>
	<div class="layui-form">
		<table class="layui-table" lay-even lay-skin="line">
			<thead>
				<tr>
					<th>编号</th>
					<th>类别名称</th>
					<th>排序编号</th>
					<th>添加时间</th>
					<th>修改时间</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
				<?php if(!empty($list)): if(is_array($list)): foreach($list as $key=>$vo): ?><tr>
					<td><?php echo ($vo["id"]); ?> <?php if(!empty($vo[slist])): ?><button class="layui-btn layui-btn-mini layui-btn-danger showdlr" rule="dlr<?php echo ($vo["id"]); ?>"> + </button><?php endif; ?>
					</td>
					<td class="align-left"><?php echo ($vo["type_name"]); ?></td>
					<td><?php echo ($vo["type_sort"]); ?></td>
					<td><?php echo ($vo["create_at"]); ?></td>
					<td><?php echo ($vo["update_at"]); ?></td>
					<td>
						<a class="layui-btn layui-btn-mini" href="<?php echo U('GoodsType/addchild',array('id'=>$vo['id']));?>">添加子类</a>
						<a class="layui-btn layui-btn-mini" href="<?php echo U('GoodsType/edit',array('id'=>$vo['id'],'pid'=>$vo['pid']));?>">编辑</a>
						<a class="layui-btn layui-btn-mini layui-btn-danger" href="javascript:if(confirm('删除该类别将会同时删除该类别的所有下级分类，您确定要删除吗'))window.location = '<?php echo U('GoodsType/del',array('id'=>$vo['id'],'pid'=>$vo['pid']));?>'">删除</a>
					</td>
				</tr>
					<?php if(is_array($vo["slist"])): foreach($vo["slist"] as $key=>$v): ?><tr class="dlrtr dlr<?php echo ($vo["id"]); ?>" style="display:none">
							<td><?php echo ($v["id"]); ?></td>
							<td class="align-left">　|--- <?php echo ($v["type_name"]); ?></td>
							<td><?php echo ($v["type_sort"]); ?></td>
							<td><?php echo (date('Y-m-d',$v["addtime"])); ?></td>
							<td><?php echo (date('Y-m-d',$v["edittime"])); ?></td>
							<td>
								<a class="layui-btn layui-btn-mini" href="<?php echo U('GoodsType/edit',array('id'=>$v['id'],'pid'=>$v['pid']));?>">编辑</a> 
								<a class="layui-btn layui-btn-mini layui-btn-danger" href="<?php echo U('GoodsType/del',array('id'=>$v['id'],'pid'=>$v['pid']));?>">删除</a>	
							</td>
						</tr><?php endforeach; endif; endforeach; endif; ?>
				<?php else: ?>
				<tr>
					<td colspan="9" class="nodata">没有相关数据</td>
				</tr><?php endif; ?>
			</tbody>
		</table>
	</div>
	<?php echo ($page); ?>
	<script src="/Public/Admin/layui/jquery-1.8.3.js"></script>
	<script type="text/javascript">
		$('.showdlr').click(function(){
			var str = $(this).attr('rule');
			$("."+str).toggle();
		})
	</script>
</body>
</html>