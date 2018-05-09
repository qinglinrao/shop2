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
    <li><a href="<?php echo U('Goods/index');?>">商品列表</a></li>
    <li class="layui-this"><a href="javascript:void(0);">商品规格列表</a></li>
    <li><a href="<?php echo U('Goods/configadd',array('goodsId'=>$goodId));?>">添加商品规格</a></li>
</ul>
<div class="layui-form">
    <table class="layui-table" lay-even lay-skin="line">
        <thead>
        <tr>
            <th>编号</th>
            <th>规格图</th>
            <th>颜色</th>
            <th>尺寸</th>
            <th>重量</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php if(!empty($list)): if(is_array($list)): foreach($list as $key=>$vo): ?><tr>
                    <td><?php echo ($vo["id"]); ?></td>
                    <td style="text-align: center" text-align="center"><div style="width: 50px;height: 50px;margin: 0 auto;"><img src="<?php echo ($vo["image"]); ?>" style="width: 100%;height: 100%;"/></div></td>
                    <td><?php echo ($vo["color"]); ?></td>
                    <td><?php echo ($vo["size"]); ?></td>
                    <td><?php echo ($vo["weight"]); ?></td>
                    <td>
                        <a class="layui-btn layui-btn-mini" href="<?php echo U('Goods/editconfig',array('id'=>$vo['id']));?>">编辑</a>
                        <a class="layui-btn layui-btn-mini layui-btn-danger" href="<?php echo U('Goods/delconfig',array('id'=>$vo['id'],'goodId'=>$vo['good_id']));?>">删除</a>
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
        window.location.href = '<?php echo U("Goods/sell");?>&goods_stats=2&id='+idstr;
    })
    $('.stopsell').click(function(){
        var idstr = $(this).attr('rule');
        window.location.href = '<?php echo U("Goods/sell");?>&goods_stats=1&id='+idstr;
    })
</script>
</body>
</html>