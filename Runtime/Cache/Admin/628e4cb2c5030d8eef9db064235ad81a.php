<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
	<title>商品管理</title>
	<link rel="shortcut icon" type="image/ico" href="/favicon.ico" />
	<link rel="stylesheet" type="text/css" href="/Public/Admin/layui/css/layui.css" />
	<link rel="stylesheet" type="text/css" href="/Public/Admin/resource/css/style.css" />
	<!--<script src="/Public/Admin/layui/jquery-1.8.3.js"></script>-->
</head>
<body class="body">
	<ul class="layui-tab-title mt-page mb-form">
		<li><a href="<?php echo U('GoodsType/index');?>">类别列表</a></li>
		<li class="layui-this"><a href="javascript:void(0);">添加新类别</a></li>
	</ul>
	<form method="post" autocomplete="off" action="<?php echo U('GoodsType/doadd');?>" class="layui-form">
		<div class="layui-form-item">
			<label class="layui-form-label">类别级别</label>
			<div class="layui-input-inline">
				<div style="height:38px;line-height:38px;padding-left:10px;">一级类</div>
				<input type="hidden" name="pid" value="0">
			</div>
		</div>
		<style type="text/css">.layui-form-item select{display: inline-block;}</style>
		<div class="layui-form-item">
			<label class="layui-form-label">类别名称</label>
			<div class="layui-input-inline">
				<input type="text" name="type_name" maxlength="11" lay-verify="required" class="layui-input">
			</div>
			<div class="layui-form-mid layui-word-aux">该类别的名称，必须填写</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">排序编号</label>
			<div class="layui-input-inline">
				<input type="text" name="type_sort" maxlength="2" value="0" lay-verify="required" class="layui-input">
			</div>
			<div class="layui-form-mid layui-word-aux">该类别的排序编号</div>
		</div>
		<div class="layui-form-item">
			<div class="layui-input-block">
				<button class="layui-btn" lay-submit lay-filter="Submit">立即提交</button>
			</div>
		</div>
	</form>
	<script src="/Public/Admin/layui/layui.js"></script>
	<script>
		layui.use(['form','jquery'], function() {
			var form = layui.form();
		});
	</script>
	<script>

//		function getChild(that){
//			var pid = $(that).val();
//			$(that).parent().nextAll().remove();
//			if(pid != 0){
//				var url = "/admin.php?m=admin&c=GoodsType&a=getChildType&id="+pid;
//				$.getJSON(url,function(childs){
//					var str = ''
//					if(childs.code == 200){
//						$(that).removeAttr('name');
//						str+='<div class="layui-input-inline"><select class="layui-input layui-select" name="pid" onchange="getChild(this)">';
//						$.each(childs.data,function(item,element){
//							str+='<option value="'+element.id+'">'+element.type_name+'</option>';
//						});
//						str+='</select></div>';
//					}
//					$(that).parent().after(str);
//				});
//			}
//
//		}
	</script>
</body>
</html>