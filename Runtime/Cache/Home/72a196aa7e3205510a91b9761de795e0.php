<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="format-detection" content="telephone=no" />
    <title>購買成功</title>
    <link rel="stylesheet" href="/Public/Goods/css/commen.css">
    <script src="/Public/Goods/js/jQuery-3.3.1.js"></script>
</head>
<body style="padding-bottom: 45px;">
<div class="dingdan">
    <div class="dd-item">
        <span class="dd-title">訂單號</span>
        <span class="dd-txt"><?php echo ($info["order_id"]); ?></span>
    </div>
    <div class="dd-item">
        <span class="dd-title">訂單信息</span>
        <span class="dd-txt"><?php echo ($info["status_desc"]); ?></span>
    </div>
    <div class="dd-item">
        <span class="dd-title">商品標題</span>
        <span class="dd-txt"><?php echo ($info["goods_title"]); ?></span>
    </div>
    <div class="dd-item">
        <span class="dd-title"><?php echo ($info["color"]); ?> <?php echo ($info["size"]); ?> <?php echo ($info["weight"]); ?></span>
        <span class="dd-txt">×<i><?php echo ($info["good_count"]); ?></i></span>
    </div>
</div>
<?php if(($info["goods_istuan"]) != "0"): ?><div class="dingdan">拼團訂單請稍後，若拼團不成功，資金將自動全額原路返還。</div><?php endif; ?>
<div class="dingdan" style="text-align: center;border: none;">其他用戶也購買了</div>
<div class="otherBuy">
    <ul class="otherBuy-list">
        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="otherBuy-list-item <?php if(($key)%2 == 0): ?>left<?php else: ?>right<?php endif; ?>"><a target="_self" src="<?php echo U('index/index',array('id'=>$info['good_id']));?>"><img src="<?php echo ($vo["goods_img"]); ?>"></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
    </ul>
</div>
<div style="height: 5px;background: #f5f5f5;margin-top: 10px;"></div>
<div class="return">
    <a target="_self" href="<?php echo U('index/index',array('id'=>$info['good_id'],'model'=>$model));?>">返回</a>
</div>
<!--分享图层-->
<div class="sharebox">
    <div class="sharetxt">點擊右上角分享</div>
</div>
<script>
    /* 分享图层点击事件*/
    $('.sharebox').click(function(){
        $(this).fadeOut(200);
    });
</script>
</body>
</html>