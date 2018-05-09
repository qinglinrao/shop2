<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="format-detection" content="telephone=no" />
    <title>評價商品</title>
    <link rel="stylesheet" href="/Public/Goods/css/commen.css">
    <script src="/Public/Home/js/jquery-1.8.3.js"></script>
    <!--<script src="/Public/Goods/js/jQuery-3.3.1.js"></script>-->
    <style>
        .warm-box{
            clear: both;position: fixed; bottom: 150px; width: 100%;text-align: center;display:none;
        }
        .warm{
            border: 1px solid #ff5000;  background: #ff5000;  color: #fff;  border-radius: 5px;  display: inline-block;  height: 45px;  line-height: 45px;  padding: 0px 20px;  box-shadow: 5px 5px 5px #888888;
        }
        .btn{
            background: #ff5000;border: 0px; color: #fff;width: 100%; height: 100%;
        }
    </style>
</head>
<body style="padding-bottom: 45px;">
<div class="am-card-header">
    <div class="am-card-header-content">
        <img src="<?php echo trim($info[image],'.');?>" style="max-width: 106px;">

        <div style="display: flex;flex-direction: column;justify-content: space-between;height: 106px;width: 100%;">
            <div style="font-size: 14px;"><?php echo ($info["goods_title"]); ?>
                <div style="color: rgb(153, 153, 153);font-size: 14px;word-wrap: break-word;margin-top: 10px;margin-bottom: 5px;"><?php echo ($info["goods_subtitle"]); ?>
                </div>
            </div>
            <div class="ps-absolute">
                <?php if(($info["color"]) != ""): ?><span class="gg"><?php echo ($info["color"]); ?></span> /<?php endif; ?>
                <?php if(($info["size"]) != ""): ?><span class="ys"><?php echo ($info["size"]); ?></span> /<?php endif; ?>
                <?php if(($info["weight"]) != ""): ?><span class="cm"><?php echo ($info["weight"]); ?></span><?php endif; ?>
            </div>
            <div class="ps-num">×<i><?php echo ($info["good_count"]); ?></i></div>
        </div>
    </div>
</div>
<div class="order-list-Below">
    <ul>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
    </ul>
</div>
<input id="id" name="id" type="hidden" value="<?php echo ($id); ?>">
<input id="level" name="level" type="hidden" value="">
<div class="textareaBox">
    <textarea id="text" name="text" class="ant-input" placeholder="請輸入評價內容..."></textarea>
</div>
<div class="upload-pic">
    <ul>
        <!--<li class="havpic">-->
            <!--<img src="http://oysj9elq5.bkt.clouddn.com/Fkfk-Dcq2siOmGDmlr336ZkqxchX?imageslim">-->
            <!--<span class="delete-btn">-</span>-->
            <!--<input type="hidden" name="img[]" value="">-->
        <!--</li>-->
    </ul>
    <a class="upload-btn"><input id="eva_file" name="eva_img" type="file" style="width: 100%;height:100%;opacity: 0;" onchange="upload_cover(this)"> </a>
</div>
<div class="warm-box">
    <span class="warm">您已評論</span>
</div>
<div class="return">
    <input id="send" class="btn" type="submit" value="發送評價">
</div>
<script>
    $('#send').click(function(){
        var id = $('#id').val();
        var level = $('#level').val();
        var text = $('#text').val();
        var img_url = '';
        var img = $('.img_url');
        img.each(function(i,e){
            img_url += $(e).val() + ',';
        });
        $.post("<?php echo U('index/doeval',array('id'=>$id));?>",{id:id,level:level,text:text,img_url:img_url},
                function(result){
                    console.log(result);
                    var result = JSON.parse(result);
                    if(result.code == 300){
                        $('.warm').empty().html('您已評論').parent().fadeIn(50);
                        window.setTimeout(function(){
                            $('.warm').parent().fadeOut(1000);
                        },1000);
                    }else if(result.code == 200){
                        $('.warm').empty().html('評論成功').parent().fadeIn(50);
                        window.setTimeout(function(){
                            $('.warm').parent().fadeOut(1000);
                        },1000);
                    }else{
                        $('.warm').empty().html('評論失敗').parent().fadeIn(50);
                        window.setTimeout(function(){
                            $('.warm').parent().fadeOut(1000);
                        },1000);
                    }
                    window.clearTimeout();//去掉定时器
        });
    });
    function fadeOut(){
        $('.warm').empty().parent().fadeOut(200);
    }

    // 删除图片
    $('.upload-pic ul li .delete-btn').live('click',function(){
        $(this).parent().remove();
    });

    $(".order-list-Below ul li").click(
            function(){
                var num = $(this).index()+1;
                $('#level').val(num);
                var len = $(this).index();
                var thats = $(this).parent(".order-list-Below ul").find("li");
                if($(thats).eq(len).attr("class")=="on"){
                    if($(thats).eq(num).attr("class")=="on"){
                        $(thats).removeClass();
                        for (var i=0 ; i<num; i++) {
                            $(thats).eq(i).addClass("on");
                        }
                    }else{
                        $(thats).removeClass();
                        for (var k=0 ; k<len; k++) {
                            $(thats).eq(k).addClass("on");
                        }
                    }
                }else{
                    $(thats).removeClass();
                    for (var j=0 ; j<num; j++) {
                        $(thats).eq(j).addClass("on");
                    }
                }
            }
    );
</script>
<script src="/Public/Admin/resource/js/ajaxfileupload.js"></script>
<script type="text/javascript">
    function upload_cover(obj) {
        ajax_upload(obj.id, function(data) { //function(data)是上传图片的成功后的回调方法
            var html = '<li class="havpic"><img src="'+ data.url +'"><span class="delete-btn">-</span><input class="img_url" type="hidden" name="img[]" value=".'+data.url+'"></li>';
            $('.upload-pic ul').append(html);
            var imgLen = $('.upload-pic ul li').length;
            if(imgLen == 4){
                $('.upload-btn').hide();
            }
        });
    }
    function ajax_upload(feid, callback) { //具体的上传图片方法
        if (image_check(feid)) { //自己添加的文件后缀名的验证
            $.ajaxFileUpload({
                fileElementId: feid,    //需要上传的文件域的ID，即<input type="file">的ID。
                url:'/index/upload', //后台方法的路径
                type: 'post',   //当要提交自定义参数时，这个参数要设置成post
                dataType: 'json',   //服务器返回的数据类型。可以为xml,script,json,html。如果不填写，jQuery会自动判断。
                secureuri: false,   //是否启用安全提交，默认为false。
                async : true,   //是否是异步
                success: function(data) {   //提交成功后自动执行的处理函数，参数data就是服务器返回的数据。
                    //console.log('asdf');
                    if (callback) callback.call(this, data);
                },
                error: function(data, status, e) {  //提交失败自动执行的处理函数。
                    // console.error(e);
                }
            });
        }
    }
    function image_check(feid) { //自己添加的文件后缀名的验证
        var img = document.getElementById(feid);
        return /.(jpg|png|gif|bmp)$/.test(img.value)?true:(function() {
            modals.info('圖片格式僅支持jpg、png、gif、bmp格式，且區分大小寫。');
            return false;
        })();
    }
</script>

</body>
</html>