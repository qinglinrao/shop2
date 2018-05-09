<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="format-detection" content="telephone=no" />
    <title>查詢訂單</title>
    <link rel="stylesheet" href="/Public/Goods/css/commen.css">
</head>
<body style="padding-bottom: 45px;">
<form action="<?php echo U('index/dosearch');?>" method="post">
<div class="ant-row ant-form-item search">
    <div class="ant-form-item-label"><label for="utel" class="ant-form-item-required" title=""><strong>手機號碼</strong></label></div>
    <div class="ant-form-item-control-wrapper">
        <div class="ant-form-item-control">
            <input type="text" name="phone" class="ant-input ant-input-lg form-content-input" placeholder="請輸入手機號碼" value="" id="utel">
            <input type="hidden" name="model" value="<?php echo ($model); ?>">
        </div>
    </div>
</div>
<div class="searchBtn-bar">
    <input type="submit" class="searchBtn" value="查詢">
</div>
</form>

<?php if(isset($list)): ?><div style="height: 5px;background: #f5f5f5;margin-top: 10px;"></div>
    <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="dingdan">
        <div class="dd-item">
            <span class="dd-title">訂單號</span>
            <span class="dd-txt"><?php echo ($vo["order_id"]); ?></span>
        </div>
        <div class="dd-item">
            <span class="dd-title">訂單信息</span>
        </div>
        <div class="dd-item">
            <span class="dd-title">商品規格</span>
            <span class="dd-txt"><?php echo ($vo["goods_title"]); ?></span>
        </div>
        <div class="dd-item">
            <span class="dd-title"><?php echo ($vo["color"]); ?> <?php echo ($vo["size"]); ?> <?php echo ($vo["weight"]); ?></span>
            <span class="dd-txt">×<i><?php echo ($vo["good_count"]); ?></i></span>
        </div>
        <div class="dd-item">
            <span class="dd-title">物流信息</span>
            <span class="dd-txt"><?php echo ($vo["wl_info"]); ?></span>
        </div>
        <div class="dd-item">
            <span class="dd-title">訂單狀態</span>
            <span class="dd-txt">
                <?php if(($vo["statue"]) == "1"): ?>未付款<?php endif; ?>
                <?php if(($vo["statue"]) == "2"): ?>已付款<?php endif; ?>
                <?php if(($vo["statue"]) == "3"): ?>配送中<?php endif; ?>
                <?php if(($vo["statue"]) == "4"): ?>已送達<?php endif; ?>
                <?php if(($vo["statue"]) == "5"): ?>未評價<?php endif; ?>
                <?php if(($vo["statue"]) == "6"): ?>已評價<?php endif; ?>
                <?php if(($vo["statue"]) == "7"): ?>待退貨<?php endif; ?>
                <?php if(($vo["statue"]) == "8"): ?>已退貨<?php endif; ?>
                <?php if(($vo["statue"]) == "9"): ?>已完成<?php endif; ?>
            </span>
        </div>
        <div class="dd-item">
            <span class="dd-txt">
                <a class="dd-btn" target="_self" href="<?php echo U('index/evaluate', array('id'=>$vo[id],'model'=>$model) );?>">去評價</a>
            </span>
        </div>
    </div><?php endforeach; endif; else: echo "" ;endif; endif; ?>
<div class="return">
    <a target="_self" href="<?php echo U('index/index', array('id'=>$vo[good_id],'model'=>$model) );?>">返回</a>
</div>
</body>
</html>