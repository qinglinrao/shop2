<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="format-detection" content="telephone=no" />
    <title>Query order</title>
    <link rel="stylesheet" href="/Public/Goods/css/commen.css">
</head>
<body style="padding-bottom: 45px;">
<form action="<?php echo U('index/dosearch');?>" method="post">
<div class="ant-row ant-form-item search">
    <div class="ant-form-item-label">
        <label for="utel" class="ant-form-item-required" title=""><strong>Cell phone number</strong></label>
    </div>
    <div class="ant-form-item-control-wrapper">
        <div class="ant-form-item-control">
            <input type="text" name="phone" class="ant-input ant-input-lg form-content-input" placeholder="Please enter your mobile number" value="" id="utel">
            <input type="hidden" name="model" value="<?php echo ($model); ?>">
        </div>
    </div>
</div>

<div class="searchBtn-bar">
    <input type="submit" class="searchBtn" value="Query">
</div>
    </form>

<?php if(isset($list)): ?><div style="height: 5px;background: #f5f5f5;margin-top: 10px;"></div>
    <div class="dingdan">
    <div class="dd-item">
        <span class="dd-title">Order number</span>
        <span class="dd-txt"><?php echo ($vo["order_id"]); ?></span>
    </div>
    <div class="dd-item">
        <span class="dd-title">Order information</span>
    </div>
    <div class="dd-item">
        <span class="dd-title">goods name</span>
        <span class="dd-txt"><?php echo ($vo["goods_title"]); ?></span>
    </div>
    <div class="dd-item">
        <span class="dd-title"><?php echo ($vo["color"]); ?> <?php echo ($vo["size"]); ?> <?php echo ($vo["weight"]); ?></span>
        <span class="dd-txt">×<i>2</i></span>
    </div>
    <div class="dd-item">
        <span class="dd-title">Logistics information</span>
        <span class="dd-txt"><?php echo ($vo["wl_info"]); ?></span>
    </div>
    <div class="dd-item">
        <span class="dd-title">Order status</span>
        <span class="dd-txt">
            <?php if(($vo["statue"]) == "1"): ?>未付款<?php endif; ?>
                <?php if(($vo["statue"]) == "2"): ?>已付款<?php endif; ?>
                <?php if(($vo["statue"]) == "3"): ?>配送中<?php endif; ?>
                <?php if(($vo["statue"]) == "4"): ?>已送达<?php endif; ?>
                <?php if(($vo["statue"]) == "5"): ?>未评价<?php endif; ?>
                <?php if(($vo["statue"]) == "6"): ?>已评价<?php endif; ?>
                <?php if(($vo["statue"]) == "7"): ?>待退货<?php endif; ?>
                <?php if(($vo["statue"]) == "8"): ?>已退货<?php endif; ?>
                <?php if(($vo["statue"]) == "9"): ?>已完成<?php endif; ?>
        </span>
    </div>
    <div class="dd-item">
        <span class="dd-txt">
            <a class="dd-btn"  target="_self" href="<?php echo U('index/evaluate', array('id'=>$vo[good_id],'model'=>$model) );?>">Have evaluation</a>
        </span>
    </div>
</div><?php endif; ?>

<div class="return">
    <a target="_self" href="<?php echo U('index/index', array('id'=>$vo[good_id],'model'=>$model) );?>">Return</a>
</div>
</body>
</html>