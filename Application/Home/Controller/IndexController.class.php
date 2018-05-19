<?php
namespace Home\Controller;
use Think\Controller;
//商品信息
class IndexController extends CommonController {

	//首页
	public function index(){
        header("Content-type: text/html; charset=utf-8");
        $id = I('get.id');
		if($id){
			$goodInfo = M('goods')->find($id);
			if (empty($goodInfo) || $goodInfo['goods_stats'] != 1) {
			    echo '商品还未开始销售';
			    die();
            }
			if($goodInfo['goods_promotion'] != 0){
				$time = date('Y-m-d H:i:s',time());
				$promotion = M('promotion')->field('pro_name,start_at,end_at,statue')->find($goodInfo['goods_promotion']);
				if($promotion['end_at'] > $time && $promotion['start_at'] < $time && $promotion['statue']==1){
					$goodInfo['pro_name'] = $promotion['pro_name'];
				}
			}
			if($goodInfo['goods_tag'] != 0){
				$tagWhere['statue'] = 1;
				$tagWhere['id'] = array('in',$goodInfo['goods_tag']);
				$tag = M('tag')->field('tag_name')->where($tagWhere)->select();
				$this->tag = $tag;
			}
			$imgList = M('goods_image')->where('good_id=%d and stype=1',$id)->select();
			$this->info = $goodInfo;
			$this->imgList = $imgList;

			# 描述长图
            $detailimgList = M('goods_image')->where('good_id=%d and stype=2',$id)->select();
            $this->detailimgList = $detailimgList;

			$sizeList = M('goods_size')->where('good_id=%d',$id)->select();
            $ggid = 0;
			if($sizeList){
			    # 默认选择第一种风格。
			    $ggid = $sizeList[0]['id'];
				$size = array();
				foreach($sizeList as $k=>$v){
					if($v['color'] && !in_array($v['color'],$size['color'])){
						$size['color'][] = $v['color'];
					}
					if($v['size'] && !in_array($v['size'],$size['size'])){
						$size['size'][] = $v['size'];
					}
					if($v['weight'] && !in_array($v['weight'],$size['weight'])) {
						$size['weight'][] = $v['weight'];
					}
				}
				$this->size = $size;
			}
		}
        $this->ggid = $ggid;
		# 规格数量
		$this->size_num = count($this->size) ? count($this->size) : 0;

		if($goodInfo['goods_country'] == 'CN'){
			$html = 'index';
		}else{
			$html = 'index-en';
		}
		$this->model = $goodInfo['goods_country'];
		$province = M('province')->where("father = 0")->select();
		# 評論開關
        $this->assign('comment_switch',False);
        $this->assign('lately_order_switch',False);
		$this->assign('province',$province);
		$this->display($html);
	}
	//更改订单状态接口 get参数 id：订单id;  statue：付款状态（1：未付款，2：已付款）
	function changeOrder(){
		$id = I('get.id');
		$statue = I('get.statue');
		$return = array();
		if($id && $statue){
			//更改订单状态
			$res = M('orders')->where(['id'=>$id])->save(['statue'=>$statue]);
			if($res !== false){
				//修改成功
				$return = array('code'=>200,'msg'=>'订单状态修改成功');
			}else{
				//修改失败
				$return = array('code'=>300,'msg'=>'订单状态修改失败');
			}
		}else{
			//缺少参数
			$return = array('code'=>400,'msg'=>'缺少参数');
		}
		echo json_encode($return);
	}
	//购买成功页面 get参数 id：订单id
	function buysuccess(){
		$id = I('get.id');
		$fields = 'o.order_id,o.good_id,o.size_id,o.good_count,g.goods_title,g.goods_istuan,g.goods_country,o.statue,o.create_at';
		$info = M('orders as o')->join('left join pt_goods as g on o.good_id=g.id')->field($fields)->where('o.id=%d',$id)->find();
		$sizeInfo = M('orders as o')->join('left join pt_goods_size as s on o.good_id=s.good_id')->field('s.color,s.size,s.weight')->where('o.id=%d',$id)->find();
		$tjGoodsList = M('goods')->where('goods_stats=1 and is_tj=1')->order('tj_sort DESC')->field('id')->select();
		$info = array_merge($info,$sizeInfo);
		if($tjGoodsList){
			foreach($tjGoodsList as $k=>$v){
				$itemImg = M('goods_image')->where('stype=1 and good_id=%d', $v['id'])->order('id asc')->limit(1)->field('image')->select();
				$tjGoodsList[$k]['goods_img'] = $itemImg[0]['image'];
			}

		}
		$createTime = strtotime($info['create_at']);
		if($info['goods_istuan'] != 0 && time()-$createTime < 300){
			$info['status_desc'] = '正在拼团中';
		}else{
			switch($info['statue']) {
				case 1:
					$info['status_desc'] = '待买家付款';
					break;
				case 2:
					$info['status_desc'] = '待买家发货';
					break;
				case 3:
					$info['status_desc'] = '已发货';
					break;
				case 4:
					$info['status_desc'] = '已送达';
					break;
				case 5:
					$info['status_desc'] = '已发货，待用户评价';
					break;
				case 6:
					$info['status_desc'] = '已发货，用户已评价';
					break;
				case 7:
					$info['status_desc'] = '待买家退货';
					break;
				case 8:
					$info['status_desc'] = '退货完成';
					break;
				case 9:
					$info['status_desc'] = '订单完成';
					break;
			}
		}
		if($info['goods_country'] == 'CN'){
			$html = 'buysuccess';
		}else{
			# $html = 'buysuccess-en';
            # 修改成功页
			$html = 'buysuccess-new';
		}
		$this->model = $info['goods_country'];
		$this->info = $info;
		$this->list = $tjGoodsList;
		$this->display($html);
	}
	//搜索 根据跳转页的商品加载不同模版
	function search(){
		$model = I('get.model');
//		$info = M('goods')->field('goods_country')->find($id);
		if($model == 'CN'){
			$html = 'search';
		}else{
			$html = 'search-en';
		}
		$this->model = $model;
		$this->display($html);
	}
	//搜索 根据跳转页的商品加载不同模版
	function evaluate(){
		$model = I('get.model');
		$id = I('get.id');
        print_r($model);
        print_r($id);exit;
		$fields = 'o.good_id,o.size_id,o.good_count,g.goods_title,g.goods_subtitle';
		$info = M('orders as o')->join('left join pt_goods as g on o.good_id=g.id')->field($fields)->where('o.id=%d',$id)->find();
		$sizeInfo = M('orders as o')->join('left join pt_goods_size as s on o.good_id=s.good_id')->field('s.color,s.size,s.weight')->where('o.id=%d',$id)->find();
		$itemImg = M('goods_image')->where('stype=1 and good_id=%d', $info['good_id'])->order('id asc')->limit(1)->field('image')->select();
		$info = array_merge($info,$sizeInfo,$itemImg[0]);
		if($model == 'CN'){
			$html = 'evaluate';
		}else{
			$html = 'evaluate-en';
		}
		$this->info = $info;
		$this->model = $model;
		$this->id = $id;
		$this->display($html);
	}

	public function upload() {
		$type = 'images';
		$folder = 'Evaluate';
		$item = 'eva_img';
		$name = "";
		$width = 650;
		$height = 650;
		$isCut = 0;   //1为居中剪裁，0为只等比缩放
		$this->_ajaxupload($type,$folder,$item,$name,$width,$height,$isCut);
	}

	function doeval(){
		$id = I('id');
		$orderInfo = M('orders')->find($id);
		if($orderInfo['is_eva'] == 1){
			echo json_encode(array('code'=>300,'msg'=>'您已评论'));exit();
		}
		$uid = $orderInfo['user_id'];
		M('orders')->where(['id'=>$id])->save(['is_eva'=>1]);
		$userInfo = M('member')->find($uid);
		$data['username'] = $userInfo['username'];
		$data['phone'] = $userInfo['phone'];
		$data['goods_id'] = $orderInfo['good_id'];
		$data['level'] = I('level');
		$data['text'] = I('text');
		$data['create_at'] = date('Y-m-d H:i:s',time());
		$data['update_at'] = date('Y-m-d H:i:s',time());
		$data['statue'] = 1;
		$imgList = explode(',', I('img_url'));
		foreach($imgList as $k=>$v){
			$data['img'.($k+1)] = $v;
		}
		$res = M('evaluate')->add($data);
		if($res){
			echo json_encode(array('code'=>200,'msg'=>'评论成功'));
		}else{
			echo json_encode(array('code'=>400,'msg'=>'评论失败'));
		}
	}

	function dosearch(){
		$phone = I('phone');
		$model = I('model');
		if(!$phone){
		    exit;
        }
		# $userInfo  = M('member')->where('phone=%s',$phone)->field('id')->find();
		# 改成查询多条。
		$userInfo  = M('member')->where('phone=%s',$phone)->field('id,phone,username,address,email,code,create_at')->select();

        $user_ids = array();
        $userInfoNew = array();
		if(!$userInfo){
            echo json_encode(array('code'=>-1,'msg'=>'Telephone number does not exist'));exit;
        }else{
		    foreach($userInfo as $val){
                $user_ids[] = $val['id'];
                $userInfoNew[$val['id']] = $val;
            }
        }

        # thinkphp的in查询
        $where = array();
        $where['o.user_id'] = array('in', $user_ids);

		$fields = 'o.id,o.order_id,o.good_id,o.user_id,o.money,o.pay_type,o.size_id,o.good_count,o.wl_info,o.statue,g.goods_title,g.goods_istuan,g.goods_country';
		//$ordersList = M('orders as o')->join('left join pt_goods as g on o.good_id=g.id')->field($fields)->where('o.user_id=%d',$userInfo['id'])->select();
		#改成直接查电话号码，因为用户信息可能存在多个电话号码，但是不同id。
        $ordersList = M('orders as o')->order('o.id desc')->join('left join pt_goods as g on o.good_id=g.id')->field($fields)->where($where)->select();

		if($ordersList){
			foreach($ordersList as $k=>$v){
				$sizeInfo = array();
				$sizeInfo = M('goods_size')->field('color,size,weight')->where('id=%d',$v['size_id'])->find();
				$ordersList[$k]['color'] = $sizeInfo['color'];
				$ordersList[$k]['size'] = $sizeInfo['size'];
				$ordersList[$k]['weight'] = $sizeInfo['weight'];

				if($v['statue'] == 1){
                    $ordersList[$k]['statue'] = 'Unpaid';
                }else if($v['statue'] == 2){
                    $ordersList[$k]['statue'] = 'Already';
                }else if($v['statue'] == 3){
                    $ordersList[$k]['statue'] = 'Distribution';
                }else if($v['statue'] == 4){
                    $ordersList[$k]['statue'] = 'Have been delivered';
                }else if($v['statue'] == 5){
                    $ordersList[$k]['statue'] = 'Not appraised';
                }else if($v['statue'] == 6){
                    $ordersList[$k]['statue'] = 'Have been evaluated';
                }else if($v['statue'] == 7){
                    $ordersList[$k]['statue'] = 'Return to return';
                }else if($v['statue'] == 8){
                    $ordersList[$k]['statue'] = 'Returned goods';
                }else if($v['statue'] == 9){
                    $ordersList[$k]['statue'] = 'Completed';
                }else if($v['statue'] == 10){
                    $ordersList[$k]['statue'] = 'Stocking';
                }

                if($v['pay_type'] == 3){
                    $ordersList[$k]['pay_type_name'] = 'Credit card payment';
                }else if($v['pay_type'] == 4){
                    $ordersList[$k]['pay_type_name'] = 'PayPal payment';
                }else if($v['pay_type'] == 5){
                    $ordersList[$k]['pay_type_name'] = 'Cash On Delivery';
                }

			}
        }else{
		    # 没有订单数据。--todo？
        }
        /*if($model == 'CN'){
            $html = 'search';
        }else{
            $html = 'search-en';
        }
        $this->model = $model;
        $this->list = $ordersList;
        $this->display($html);*/

        $ordersListNew = array();
        foreach($ordersList as $k=>$v){
            $ordersListNew[] = $v;
            $ordersListNew[$k]['user_data'] = $userInfoNew[$v['user_id']];
        }
        $res = array('code'=>0,'data'=>$ordersListNew, 'msg'=>'search success', 'model'=>$model);
        echo json_encode($res);
	}

	function evaOrder(){
		//goods.php?type=1&f=1  订单6条
//goods.php?type=1&f=2  订单1条
//goods.php?type=2&f=1  评论6条
//goods.php?type=2&f=2  评论1条

		$type = $_GET['type']; //1订单 2评论
		$f = $_GET['f'];  //1：6条  2：一条
		$size = 6;
		if($f == 2){
			$size = 1;
		}
		if($type == 1){
			$count = M('member')->where(['utype'=>2])->count();
			$start = rand(0,$count-7);
			if($f == 2){
				$start = rand(0,$count-1);
			}
			$orderList = M('member')->where(['utype'=>2])->field('username,phone')->order('id ASC')->limit($start,$size)->select();
			foreach($orderList as $k=>$v){
				$orderList[$k]['country'] = '中国';
				$orderList[$k]['time'] = '30';
			}
			$return = $orderList;
		}else{
			$gid = isset($_GET['g']) ? $_GET['g'] : 0;
			$count = M('evaluate')->where(['goods_id'=>$gid,'statue'=>2])->count();
			$p = isset($_GET['p']) ? $_GET['p'] : 0;

			if($p){
				if( $count <= ($p+5) ){
					$bei = floor(($p+5)/$count);
					$start = $p+5-$bei*$count;
				}else{
					$start = $p+5;
				}
			}else{
				$start = 0;
			}
			$evaList = M('evaluate')->where(['goods_id'=>$gid,'statue'=>2])->order('create_at DESC')->limit($start,$size)->select();
			foreach($evaList as $k=>$v){
				$evaList[$k]['img1'] = trim($v['img1'],'.');
				$evaList[$k]['img2'] = trim($v['img2'],'.');
				$evaList[$k]['img3'] = trim($v['img3'],'.');
				$evaList[$k]['img4'] = trim($v['img4'],'.');
			}
//			echo $count;dump($evaList);die;
			$return = $evaList;
		}
		echo json_encode($return);
	}
	function getImg(){
		$id = I('get.id');
		$weight = I('get.weight');
		$color = I('get.color');
		$size = I('get.size');
		$where['good_id'] = $id;

		# 改成只切换图片就可以了。
		/*if($weight){
			$where['weight'] = $weight;
		}*/
		if($color){
			$where['color'] = $color;
		}
		/*if($size){
			$where['size'] = $size;
		}*/

		$return = array('code'=>400,'msg'=>'fail');
		$res = M('goods_size')->where($where)->select();
		if($res){
			$size = array();
			foreach($res as $k=>$v){
				if(!in_array($v['color'],$size['color'])){
					$size['color'][] = $v['color'];
				}
				if(!in_array($v['size'],$size['size'])){
					$size['size'][] = $v['size'];
				}
				if(!in_array($v['weight'],$size['weight'])) {
					$size['weight'][] = $v['weight'];
				}
			}
			$imgInfo = M('goods_image')->where('sid=%d and stype=0',$res[0]['id'])->find();
			if($imgInfo['image']){
				$return['img'] = trim($imgInfo['image'],'.');
			}
			$return['code'] = 200;
			$return['msg'] = 'success';
			$return['data'] = $size;
			$return['count'] = count($res);
			$return['id'] = $res[0]['id'];
		}
		echo json_encode($return);
	}

	function getadd(){
		$phone = I('get.phone');
		$address = M('member')->where('phone=%s',$phone)->select();
		if($address){
			echo json_encode($address);
		}else{
			echo false;
		}
	}
}