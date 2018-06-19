<?php
namespace Home\Controller;
use Think\Controller;

class OrderController extends Controller {

    // 检测管理员登录
    protected function _initialize() {
//		if (!session('?admin_id')) {
//			$this->redirect('Public/login');
//		} else {
        // $auth = new \Think\Auth();
        // if (!$auth->check(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME, session('member_id'))) {
        // 	$this->error('操作权限不足');
        // }
//		}
    }

    # 不在服务范围的邮编。
    public function no_server_code(){
        //实例化redis
        $redis = new \Redis();
        //连接
        $redis->connect('127.0.0.1', 6379);
        //检测是否连接成功
        //echo "Server is running: " . $redis->ping();

        # 这个项目的redis前缀固定是shop2_
        $key = 'shop2_no_server_code';
        $data = $redis->get($key);

        if($data){
            $txt_arr = json_decode($data, true);
        }else{
            # 读取不在范围的邮编。$siteinfo_file = include('Config/siteinfo.config.php');
            $path = 'Config/no_server_code.txt';
            # $file = fopen($path, 'r');

            $content = file_get_contents($path);
            $content = mb_convert_encoding ( $content, 'UTF-8','Unicode');

            # 这里不能使用PHP_EOL，因为txt文件是windows系统的。
            $array = explode("\r\n", $content);
            $txt_arr = array();
            for($i=0; $i<count($array); $i++)
            {
                $txt_arr[] = $array[$i];
                //echo $array[$i].'<br />';
            }

            //缓存1天。
            $redis->set($key, json_encode($txt_arr), 60*60*24);
        }
        return $txt_arr;

    }

    #上面那个方法有问题。--todo
    public function no_server_code2(){

        $noserver_file = include('Config/noserver.config.php');
        return $noserver_file;

        # 读取不在范围的邮编。$siteinfo_file = include('Config/siteinfo.config.php');
        $path = 'Config/no_server_code.txt';
        # $file = fopen($path, 'r');

        $content = file_get_contents($path);
        $content = mb_convert_encoding ( $content, 'UTF-8','Unicode');

        # 这里不能使用PHP_EOL，因为txt文件是windows系统的。
        $array = explode("\r\n", $content);
        $txt_arr = array();
        for($i=0; $i<count($array); $i++)
        {
            $txt_arr[] = $array[$i];
            //echo $array[$i].'<br />';
        }

        return $txt_arr;

    }

    public function test(){
        $noserver_file = include('Config/noserver.config.php');
        print_r($noserver_file);exit;

        $path = 'Config/no_server_code.txt';
        # $file = fopen($path, 'r');

        $content = file_get_contents($path);
        $content = mb_convert_encoding ( $content, 'UTF-8','Unicode');

        $array = explode("\r\n", $content);
        $txt_arr = array();
        for($i=0; $i<count($array); $i++)
        {
            $txt_arr[] = $array[$i];
        }

        $str = '<?php return'.PHP_EOL;
        $str .= var_export($txt_arr, true);
        $str .= ';';
        $noserver_file = 'Config/noserver.config.php';
        file_put_contents($noserver_file, $str);
    }

    public function createOrder()
    {
        //
        $goodId = I('post.goodId');
        $sizeId = I('post.sizeId');
        $userName = I('post.userName');
        $phone = I('post.phone');
        $address = I('post.address');
        $code = I('post.code');
        $email = I('post.email');
        $remark = I('post.remark');
        $payType = I('post.payType');
        $goodCount = I('post.goodCount');
        $refer = I('post.refer');

        # 这里需要安全验证的操作--todo
        if(!$goodId || !$phone){
            exit;
        }
        //验证数据
        $check = 123;

        //将地址信息写入用户表
        $userModel = M('member');
        $userData = array(
            'phone' => $phone,
            'username' => $userName,
            'address' => $address,
            'email' => $email,
            'create_at' => date('Y-m-d H:i:s'),
            'utype' => 1,
            'code' => $code,
        );

        //判断是返回英文还是中文。
        $model = I('get.model');
        if($model == 'CN'){
            $msg1 = '商品有误';
            $msg2 = '用户信息有误';
            $msg3 = '订单生成成功';
        }else{
            $msg1 = 'something is wrong';
            $msg2 = 'user is wrong';
            $msg3 = 'Success of order generation';
        }

        //根据商品id，查询商品信息
        $goodsInfo = $goodInfo = M('goods')->find($goodId);
        if (empty($goodsInfo)) {

            $res = array('code'=>1,'data'=>array('msg'=>$msg1));
            echo json_encode($res);
            die();
        }

        $userModel->create($userData);
        $userId = $userModel -> add();
        if ($userId <= 0) {
            $res = array('code'=>1,'data'=>array('msg'=>$msg2));
            echo json_encode($res);
            die();
        }

        $strDesc = '';

        $price = $goodsInfo['goods_trprice']*$goodCount;

        $priceAmount = $price;
        //判断商品是否为团购商品
        $tuanFlag = 0;
        if ($goodsInfo['goods_istuan'] > 0) {
            $tuanInfo = M('tourdiy')->find($goodsInfo['goods_istuan']);
            $time = time();
            if (strtotime($tuanInfo['start_at']) < $time && strtotime($tuanInfo['end_at']) > $time) {
                $tuanFlag = 1;
                $strDesc .= '团购价格为'.$goodsInfo['goods_twprice'];
                $priceAmount = $goodsInfo['goods_twprice']*$goodCount;
            }
        }

        //判断商品是否为促销商品
        $promotionFlag = 0;
        $goodsCountAmount = $goodCount;
        if ($goodsInfo['goods_promotion'] > 0) {
            //查询促销信息
            $promotion = M('promotion')->find($goodsInfo['goods_promotion']);
            if ($promotion) {
                $promotionFlag = 1;
                if ($promotion['ptype'] == 1 && $goodCount >= $promotion['first']) {
                    $strDesc .= '/'.'买'.$promotion['first'].'送'.$promotion['second'];
                    $goodsCountAmount = $goodCount + $promotion['second'];
                } else if ($promotion['ptype'] == 2 && $goodCount >= $promotion['first']) {
                    $strDesc .= '/'.'买'.$promotion['first'].'打'.$promotion['second'].'折';
                    $num = $promotion['second']/10;
                    $priceAmount = $price*$num;
                }
            }
        }
        //运费
        $price += $goodInfo['goods_price'];
        $priceAmount += $goodInfo['goods_price'];
        $status = 1;
        //如果为货到付款,修改订单状态为代发货
        if ($payType == 5) {
            $status = 10;
        }

        //将数据写入订单中
        $orderId = date('YmdHis').rand(1000,9999);
        $orderData = array(
            'order_id' => $orderId,
            'good_id' => $goodId,
            'size_id' => $sizeId,     //sizeId是不准的。
            'good_count' => $goodsCountAmount,
            'money' => $priceAmount,
            'user_id' => $userId,
            'from' => $refer,
            'statue' => $status,
            'create_at' => date('Y-m-d H:i:s'),
            'old_price' => $price,
            'old_count' => $goodCount,
            'content' => $strDesc,
            'remark' => $remark,
            'pay_type' => $payType,
        );


        # 判断邮编是否合法
        if($code){
            if(in_array((string)$code, $this->no_server_code2(), true)){
                $orderData['is_useful'] = 0;
                $orderData['is_useful_remark'] = '邮编不在配送服务范围';
            }
        }


        //添加订单
        $orderModel = M('orders');
        $orderModel->create($orderData);
        $orderId = $orderModel -> add();


        //添加记录到新的订单规格表。
        $color = I('post.color');
        $weight = I('post.weight');
        $size = I('post.size');
        /*if($color || $weight || $size){*/
            $sizeData = array();
            $sizeData['color'] = $color;
            $sizeData['weight'] = $weight;
            $sizeData['size'] = $size;
            $sizeData['user_id'] = $userId;
            $sizeData['good_id'] = $goodId;
            $sizeData['order_id'] = $orderId;
            $sizeData['num'] = $goodsCountAmount;
            $sizeData['add_time'] = date('Y-m-d H:i:s');

            $orderSizeModel = M('orders_size');
            $orderSizeModel->create($sizeData);
            $orderSizeModel -> add();
        /*}*/

        $res = array('code'=>0,'data'=>array("orderId"=>$orderId,'msg'=>$msg3));
        echo json_encode($res);
    }


    /**
     * 订单详情页
     */
    public function orderInfo()
    {
        $orderId = I('get.orderId');
        if ($orderId <= 0) {
            echo '订单错误';
            die();
        }

        //根据订单id查询订单信息
        $orderInfo = M('orders')->find($orderId);
        if (empty($orderInfo)) {
            echo '订单不存在';
            die();
        }

        $userId = $orderInfo['user_id'];
        if ($userId <= 0) {
            echo '订单的用户id为空';
            die();
        }
        //查询用户信息
        $userInfo = M('member')->find($userId);
        $orderInfo['wl_info'] = json_decode($orderInfo['wl_info']);
        $orderInfo['pw_info'] = json_decode($orderInfo['pw_info']);
        switch($orderInfo['statue']) {
            case 1:
                $orderInfo['status_desc'] = '待买家付款';
                break;
            case 2:
                $orderInfo['status_desc'] = '待买家发货';
                break;
            case 3:
                $orderInfo['status_desc'] = '已发货';
                break;
            case 4:
                $orderInfo['status_desc'] = '已送达';
                break;
            case 5:
                $orderInfo['status_desc'] = '已发货，待用户评价';
                break;
            case 6:
                $orderInfo['status_desc'] = '已发货，用户已评价';
                break;
            case 7:
                $orderInfo['status_desc'] = '待买家退货';
                break;
            case 8:
                $orderInfo['status_desc'] = '退货完成';
                break;
            case 9:
                $orderInfo['status_desc'] = '订单完成';
                break;
            case 10:
                $orderInfo['status_desc'] = '待买家发货';
                break;
        }

        switch ($orderInfo['pay_type']) {
            case 1:
                $orderInfo['pay_type_desc'] = '微信支付';
                break;
            case 2:
                $orderInfo['pay_type_desc'] = '支付宝支付';
                break;
            case 3:
                $orderInfo['pay_type_desc'] = '信用卡支付';
                break;
            case 4:
                $orderInfo['pay_type_desc'] = 'paypal支付';
                break;
            case 5:
                $orderInfo['pay_type_desc'] = '货到付款';
                break;
        }

        //查询商品信息
        $goodId = $orderInfo['good_id'];
        $goodsInfo = $goodInfo = M('goods')->find($goodId);
        if (empty($goodsInfo)) {
            echo '订单商品不存在';
            die();
        }

        if ($goodsInfo['goods_country'] == 'UN') {

        } else if ($goodsInfo['goods_country'] == 'CK') {

        }

    }



}
