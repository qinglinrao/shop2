<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
	<title>商品信息管理</title>
	<link rel="shortcut icon" type="image/ico" href="/favicon.ico" />
	<link rel="stylesheet" type="text/css" href="/Public/Admin/layui/css/layui.css" />
	<link rel="stylesheet" type="text/css" href="/Public/Admin/resource/css/style.css" />
	<style type="text/css">
		.layui-form-item select{display: block;}

		.imgyl img{
			width: 120px;height: 120px;
		}
		.imgup{
			position: relative;
			margin-left: -200px;
			margin-top: 8px;
		}
		.imginp{
			position: relative;
			margin-top: 120px;
			z-index: 900;
		}
		.imginp input{
			width: 120px;height: 40px;
		}
		.layui-unselect{display: none;}
	</style>
</head>
<body class="body">
	<ul class="layui-tab-title mt-page mb-form">
		<li><a href="<?php echo U('Goods/index');?>">商品列表</a></li>
		<li class="layui-this"><a href="javascript:void(0);">添加商品</a></li>
		<li><a href="<?php echo U('Goods/addmore');?>">批量上传商品</a></li>
	</ul>
	<form method="post" autocomplete="off" action="<?php echo U('Goods/doadd');?>" class="layui-form">
        <!--<div class="layui-form-item">-->
            <!--<label class="layui-form-label">商品名称</label>-->
            <!--<div class="layui-input-inline">-->
                <!--<input type="text" name="goods_name" maxlength="10" lay-verify="required" class="layui-input">-->
            <!--</div>-->
            <!--<div class="layui-form-mid layui-word-aux">商品名称，必须填写</div>-->
        <!--</div>-->
		<div class="layui-form-item">
			<label class="layui-form-label">国家</label>
			<div class="layui-input-inline">
				<select class="layui-input layui-select" name="goods_country" lay-verify="required">
					<?php if(is_array($country)): foreach($country as $key=>$vo): ?><option value="<?php echo ($vo["acronym"]); ?>"><?php echo ($vo["chinese"]); ?></option><?php endforeach; endif; ?>
				</select>
			</div>
			<div class="layui-form-mid layui-word-aux">商品的国家，必须填写</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">商品主标题</label>
			<div class="layui-input-inline">
				<input type="text" name="goods_title" maxlength="10" lay-verify="required" class="layui-input">
			</div>
			<div class="layui-form-mid layui-word-aux">商品的主标题，必须填写</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">商品副标题</label>
			<div class="layui-input-inline">
				<input type="text" name="goods_subtitle" maxlength="10" lay-verify="required" class="layui-input">
			</div>
			<div class="layui-form-mid layui-word-aux">商品副标题，必须填写</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">商品类别</label>
			<div class="layui-input-inline">
				<select class="layui-input layui-select" name="pid" lay-verify="required">
					<option value="">--请选择--</option>
					<?php if(is_array($list)): foreach($list as $key=>$v): ?><optgroup class="" label="<?php echo ($v["type_name"]); ?>">
							<?php if(is_array($v["slist"])): foreach($v["slist"] as $key=>$vo): ?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["type_name"]); ?></option><?php endforeach; endif; ?>
						</optgroup><?php endforeach; endif; ?>
				</select>
			</div>		
			<div class="layui-form-mid layui-word-aux">选择该商品的类别，若不选可能导致信息无法正常显示，必须选择</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">原价</label>
			<div class="layui-input-inline">
				<input type="text" name="goods_toprice" maxlength="10" lay-verify="toprice" value="0" class="layui-input">
			</div>
			<div class="layui-form-mid layui-word-aux">商品页中划删除线的价格（数字），必填，例如：9.99 、 9.9 、 9</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">零售价</label>
			<div class="layui-input-inline">
				<input type="text" name="goods_trprice" maxlength="10" lay-verify="trprice" value="0" class="layui-input">
			</div>
			<div class="layui-form-mid layui-word-aux">商品页中普通商品的正常价格（数字），必填，例如：9.99 、 9.9 、 9</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">团购价</label>
			<div class="layui-input-inline">
				<input type="text" name="goods_twprice" maxlength="10" lay-verify="twprice" value="0" class="layui-input">
			</div>
			<div class="layui-form-mid layui-word-aux">商品页中拼团商品的价格（数字），必填，例如：9.99 、 9.9 、 9</div>
		</div>
		<!--
		<div class="layui-form-item">
			<label class="layui-form-label">商品规格</label>
			<div class="layui-input-inline">
				<input type="text" name="goods_gg" maxlength="20" lay-verify="required" class="layui-input">
			</div>
			<div class="layui-form-mid layui-word-aux">商品的规格（例如：50斤/袋），必须填写</div>
		</div>
		-->
		<div class="layui-form-item">
			<label class="layui-form-label">运费</label>
			<div class="layui-input-inline">
				<input type="text" name="goods_price" maxlength="10" lay-verify="price" class="layui-input">
			</div>
			<div class="layui-form-mid layui-word-aux">商品运费，填0为包邮，必填，例如：9.99 、 9.9 、 9、0</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">购买须知</label>
			
			<div class="layui-input-inline imgyl"  style="width: 128px;">
				<img class="upimg0" src="/Public/Admin/resource/images/nophoto.jpg">
			</div>
			<div class="layui-input-inline imgup" style="width: 120px;height: 160px;margin-left: -139px;">
				<div class="imginp">
					<input type="file" id="upimg0" class="layui-upload-file upload0" name='upimg0' onchange="fileChange(this,'upimg0')" >
					<input name="goods_notice" sx='upimg0' type="hidden" value="" lay-verify="goods_notice"/>
				</div>
			</div>
			<div class="layui-form-mid layui-word-aux" style="float: left;">购买须知，推荐上传正方形图片<br>后台已做正方形剪裁缩放
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">商品描述长图</label>

			<div class="layui-input-inline imgyl"  style="width: 128px;">
				<img class="upimg6" src="<?php echo ($info["goods_det"]); ?>" onerror="this.src='/Public/Admin/resource/images/nophoto.jpg'">
			</div>
			<div class="layui-input-inline imgup" style="width: 120px;height: 160px;margin-left: -139px;">
				<div class="imginp">
					<input type="file" id="upimg6" class="layui-upload-file upload6" name='upimg6' onchange="fileChange(this,'upimg6')" >
					<input name="goods_det" sx='upimg6' type="hidden" value="" lay-verify="goods_det"/>
				</div>
			</div>
			<div class="layui-form-mid layui-word-aux" style="float: left;">商品描述长图，图片大小推荐小于2M，图片过大会导致页面加载变慢<br>后台未做缩放剪裁
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">商品轮播图</label>

			<div class="imgbox">
				<div class="layui-input-inline imgyl"  style="width: 128px;">
					<img class="upimg1" src="/Public/Admin/resource/images/nophoto.jpg">
				</div>
				<div class="layui-input-inline imgup" style="width: 120px;height: 160px;margin-left: -139px;">
					<div class="imginp">
						<input type="file" id="upimg1" class="layui-upload-file upload1" name='upimg1' onchange="fileChange(this,'upimg1')" >
						<input name="lunbo[]" sx='upimg1' type="hidden" value="" lay-verify="first"/>
					</div>
				</div>
			</div>

			<div class="imgbox">
				<div class="layui-input-inline imgyl"  style="width: 128px;">
					<img class="upimg2" src="/Public/Admin/resource/images/nophoto.jpg">
				</div>
				<div class="layui-input-inline imgup" style="width: 120px;height: 160px;margin-left: -139px;">
					<div class="imginp">
						<input type="file" id="upimg2" class="layui-upload-file upload2" name='upimg2' onchange="fileChange(this,'upimg2')">
						<input name="lunbo[]" sx='upimg2' type="hidden" value="" />
					</div>
				</div>
			</div>

			<div class="imgbox">
				<div class="layui-input-inline imgyl"  style="width: 128px;">
					<img class="upimg3" src="/Public/Admin/resource/images/nophoto.jpg">
				</div>
				<div class="layui-input-inline imgup" style="width: 120px;height: 160px;margin-left: -139px;">
					<div class="imginp">
						<input type="file" id="upimg3" class="layui-upload-file upload3" name='upimg3' onchange="fileChange(this,'upimg3')" >
						<input name="lunbo[]" sx='upimg3' type="hidden" value="" />
					</div>
				</div>
			</div>

			<div class="imgbox">
				<div class="layui-input-inline imgyl"  style="width: 128px;">
					<img class="upimg4" src="/Public/Admin/resource/images/nophoto.jpg">
				</div>
				<div class="layui-input-inline imgup" style="width: 120px;height: 160px;margin-left: -139px;">
					<div class="imginp">
						<input type="file" id="upimg4" class="layui-upload-file  upload4" name='upimg4' onchange="fileChange(this,'upimg4')">
						<input name="lunbo[]" sx='upimg4' type="hidden" value="" />
					</div>
				</div>
			</div>

			<div class="imgbox">
				<div class="layui-input-inline imgyl" id="preview5" style="width: 128px;">
					<img class="upimg5" src="/Public/Admin/resource/images/nophoto.jpg">
				</div>
				<div class="layui-input-inline imgup" style="width: 120px;height: 160px;margin-left: -139px;">
					<div class="imginp">
						<input type="file" id="upimg5" class="layui-upload-file upload5" name='upimg5' onchange="fileChange(this,'upimg5')" >
						<input name="lunbo[]" sx='upimg5' type="hidden" value="" />
					</div>
				</div>
			</div>
			<div class="layui-form-mid layui-word-aux" style="float: left;">商品轮播图，推荐上传正方形图片<br>后台已做正方形剪裁缩放</div>
		</div>
		<!--
		<div class="layui-form-item">
			<label class="layui-form-label">商品详情</label>
			<div class="layui-input-inline">
				<textarea name="article_info" id="editor_id" style="width:700px; height:350px;"></textarea>
			</div>
		</div>-->

		
		<div class="layui-form-item">
			<div class="layui-input-block">
				<button class="layui-btn layui-btn-danger" lay-submit lay-filter="Submit">立即提交</button>
			</div>
		</div>
	</form>
<script src="/Public/Admin/layui/jquery-1.8.3.js"></script>
<script src="/Public/Admin/layui/layui.js"></script>
<script src="/Public/Admin/resource/js/ajaxfileupload.js"></script>
<script type="text/javascript">
var idstr=''
 function fileChange(obj,str){
 	var f = obj.files[0];
 	var src = window.URL.createObjectURL(f)//预览图片url
 	$('.'+str).attr('src',src);
 	idstr = str;
 }	
 	layui.use(['upload'], function() {
 		layui.upload({
			title: '上传图片　',
			elem: '.upload0',
			url: "<?php echo U('Goods/imgup0');?>",
			type: 'images',
			ext: 'png|gif|jpg|jpeg',
			success: function(res) {
				if (res.status == 'error') {
					layer.msg(res.msg, {icon: 2, shift:6});
				} else {
					//alert(idstr);
					$("input[sx='"+idstr+"']").val(res.url);
					
				}
			}
		});
 	});

 	layui.use(['upload'], function() {
 		layui.upload({
			title: '上传图片　',
			elem: '.upload1',
			url: "<?php echo U('Goods/imgup1');?>",
			type: 'images',
			ext: 'png|gif|jpg|jpeg',
			success: function(res) {
				if (res.status == 'error') {
					layer.msg(res.msg, {icon: 2, shift:6});
				} else {
					//alert(idstr);
					$("input[sx='"+idstr+"']").val(res.url);
					
				}
			}
		});
 	});

 	layui.use(['upload'], function() {
 		layui.upload({
			title: '上传图片　',
			elem: '.upload2',
			url: "<?php echo U('Goods/imgup2');?>",
			type: 'images',
			ext: 'png|gif|jpg|jpeg',
			success: function(res) {
				if (res.status == 'error') {
					layer.msg(res.msg, {icon: 2, shift:6});
				} else {
					//alert(idstr);
					$("input[sx='"+idstr+"']").val(res.url);
					
				}
			}
		});
 	});

 	layui.use(['upload'], function() {
 		layui.upload({
			title: '上传图片　',
			elem: '.upload3',
			url: "<?php echo U('Goods/imgup3');?>",
			type: 'images',
			ext: 'png|gif|jpg|jpeg',
			success: function(res) {
				if (res.status == 'error') {
					layer.msg(res.msg, {icon: 2, shift:6});
				} else {
					//alert(idstr);
					$("input[sx='"+idstr+"']").val(res.url);
					
				}
			}
		});
 	});

 	layui.use(['upload'], function() {
 		layui.upload({
			title: '上传图片　',
			elem: '.upload4',
			url: "<?php echo U('Goods/imgup4');?>",
			type: 'images',
			ext: 'png|gif|jpg|jpeg',
			success: function(res) {
				if (res.status == 'error') {
					layer.msg(res.msg, {icon: 2, shift:6});
				} else {
					//alert(idstr);
					$("input[sx='"+idstr+"']").val(res.url);
					
				}
			}
		});
 	});

 	layui.use(['upload'], function() {
 		layui.upload({
			title: '上传图片　',
			elem: '.upload5',
			url: "<?php echo U('Goods/imgup5');?>",
			type: 'images',
			ext: 'png|gif|jpg|jpeg',
			success: function(res) {
				if (res.status == 'error') {
					layer.msg(res.msg, {icon: 2, shift:6});
				} else {
					//alert(idstr);
					$("input[sx='"+idstr+"']").val(res.url);
					
				}
			}
		});
 	});

	layui.use(['upload'], function() {
    layui.upload({
        title: '上传图片　',
        elem: '.upload6',
        url: "<?php echo U('Goods/imgup6');?>",
        type: 'images',
        ext: 'png|gif|jpg|jpeg',
        success: function(res) {
            if (res.status == 'error') {
                layer.msg(res.msg, {icon: 2, shift:6});
            } else {
                //alert(idstr);
                $("input[sx='"+idstr+"']").val(res.url);

            }
        }
    });
});
	layui.use(['form','jquery'], function() {
	var form = layui.form();
	form.verify({
		goods_det: function(value, item){ //value：表单的值、item：表单的DOM对象
			if($(item).val() == ''){
				return '商品描述长图不能为空，请上传图片';
			}
		},
		goods_notice: function(value, item){ //value：表单的值、item：表单的DOM对象
			if($(item).val() == ''){
				return '购买须知不能为空，请上传图片';
			}
		},
		first: function(value, item){ //value：表单的值、item：表单的DOM对象
			if($(item).val() == ''){
				return '轮播图不能为空，最少上传第一张';
			}
		},
		toprice: function(value, item){ //value：表单的值、item：表单的DOM对象
			if( !( /^\d+(\.\d{1,2})?$/.test( $(item).val() ) ) ){
				return '原价输入不合法';
			}
		},
		trprice: function(value, item){ //value：表单的值、item：表单的DOM对象
			if( !( /^\d+(\.\d{1,2})?$/.test( $(item).val() ) ) ){
				return '零售价输入不合法';
			}
		},
		twprice: function(value, item){ //value：表单的值、item：表单的DOM对象
			if( !( /^\d+(\.\d{1,2})?$/.test( $(item).val() ) ) ){
				return '拼团价输入不合法';
			}
		},
		price: function(value, item){ //value：表单的值、item：表单的DOM对象
			if( !( /^\d+(\.\d{1,2})?$/.test( $(item).val() ) ) ){
				return '运费输入不合法';
			}
		}

	});
});
 </script>
</body>
</html>