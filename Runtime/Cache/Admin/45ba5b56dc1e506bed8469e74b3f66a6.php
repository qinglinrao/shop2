<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>商品信息管理</title>
    <link rel="shortcut icon" type="image/ico" href="/favicon.ico" />
    <link rel="stylesheet" type="text/css" href="/Public/Admin/layui/css/layui.css" />
    <link rel="stylesheet" type="text/css" href="/Public/Admin/resource/css/style.css" />
    <script src="/Public/editor/kindeditor-min.js"></script>
    <script src="/Public/editor/lang/zh_CN.js"></script>
    <!--
    <script>
      KindEditor.ready(function(K) {
             window.editor = K.create('#editor_id');
      });
    </script> -->

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

    </style>
</head>
<body class="body">
<ul class="layui-tab-title mt-page mb-form">
    <li><a href="<?php echo U('Goods/index');?>">商品列表</a></li>
    <li><a href="<?php echo U('Goods/configlist',array('id'=>$goodId));?>">商品规格列表</a></li>
    <li class="layui-this"><a href="javascript:void(0);">添加商品规格</a></li>
</ul>
<form method="post" autocomplete="off" action="<?php echo U('Goods/doaddconfig',array('goodId'=>$goodId));?>" class="layui-form">
    <input type="hidden" value="<?php echo ($goodId); ?>" name = 'good_id'>
    <div class="layui-form-item">
        <label class="layui-form-label">颜色</label>
        <div class="layui-input-inline">
            <input type="text" name="color" maxlength="10" class="layui-input">
        </div>
        <div class="layui-form-mid layui-word-aux">颜色，非必须填写</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">尺寸</label>
        <div class="layui-input-inline">
            <input type="text" name="size" maxlength="10" class="layui-input">
        </div>
        <div class="layui-form-mid layui-word-aux">尺寸，非必须填写</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">重量</label>
        <div class="layui-input-inline">
            <input type="text" name="weight" maxlength="10" class="layui-input">
        </div>
        <div class="layui-form-mid layui-word-aux">重量，非必须填写</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">规格图片</label>

        <div class="layui-input-inline imgyl"  style="width: 128px;">
            <img class="upimg0" src="/Public/Admin/resource/images/nophoto.jpg">
        </div>
        <div class="layui-input-inline imgup" style="width: 120px;height: 160px;margin-left: -139px;">
            <div class="imginp">
                <input type="file" id="upimg0" class="layui-upload-file upload0" name='upimg0' onchange="fileChange(this,'upimg0')" >
                <input name="image" sx='upimg0' type="hidden" value="" />
            </div>
        </div>
    </div>


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
</script>
</body>
</html>