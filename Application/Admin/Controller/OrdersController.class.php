<?php
namespace Admin\Controller;
use Think\Controller;
//订单管理
class OrdersController extends CommonController {
    function __construct()
    {
        parent::__construct();
        /*if($_SESSION['admin_name'] != 'admim'){

            print_r('没有权限');exit;
        }*/
    }

	// 订单列表
	public function index() {
		$db = M('orders');
		$keyword = I('get.keyword') ? I('get.keyword') : '';
		$keyword_num = I('get.keyword_num') ? I('get.keyword_num') : '';
		if ($keyword) {
			$where['g.goods_title'] = array('like','%' . $keyword . '%');
		}

		# 查询商品编号
        if ($keyword_num) {
            $where['g.goods_number'] = array('like','%' . $keyword_num . '%');
        }

		$tuanTime = date('Y-m-d H:i:s',time()-300);
		$statue = I('get.statue') ? I('get.statue') : '';
		if ($statue) {
			/*if($statue == 10){*/
            # 可能之前团购的状态是10，现在改成11，因为货到付款是10了。
			if($statue == 11){
				$where['o.create_at'] = array('gt',$tuanTime);
			}else{
				$where['o.statue'] = $statue;
			}
		}
		$area = I('get.time_area');
		if($area){
			$times = explode('~',$area);
			$start_at = $times[0];
			$end_at = $times[1];
			$where['o.create_at'] = array('between',"{$start_at},{$end_at}");
		}

		$count 	= M('orders o')->join('pt_goods g on o.good_id=g.id')->where($where)->count();
		$page 	= show_page($count,10);
		$limit 	= $page->firstRow.','.$page->listRows;		
		$table 	= 'pt_orders o';
		$join 	= array('LEFT JOIN pt_goods g on o.good_id=g.id');
		$field 	= 'o.id,o.pw_info,o.wl_info,o.from,o.statue,o.create_at,o.user_id,g.goods_title,o.create_at,o.remark,g.admin_id,g.goods_number';
		$order 	= 'o.id desc';
		$list 	= M()->table($table)->join($join)->where($where)->field($field)->limit($limit)->order($order)->select();

		# 查询投放人名称
        $admin_data = M('admin')->field('admin_id, admin_name')->select();
		foreach($list as $k=>$v){
			$userInfo = M('member')->field('phone,username,address')->find($v['user_id']);
			$list[$k]['phone'] = $userInfo['phone'];
			$list[$k]['username'] = $userInfo['username'];
			$list[$k]['address'] = $userInfo['address'];
            //合并管理员名称
            foreach($admin_data as $admin_val){
                if($v['admin_id'] == $admin_val['admin_id']){
                    $list[$k]['admin_name'] = $admin_val['admin_name'];
                }
            }
		}
		$this->assign('time_area',$area);
		$this->assign('statue',$statue);
		$this->assign('keyword',$keyword);
		$this->assign('keyword_num',$keyword_num);
		$this->assign('page',$page->show());
		$this->assign('list',$list);
		$this->display(); 
	}

	public function edit(){
		$id = I('get.id');
		$r = I('get.r');
		$info = M('orders')->find($id);
		$userInfo = M('member')->where('id=%d',$info['user_id'])->field('username,phone,address')->find();
		$goodsInfo = M('goods')->where('id=%d',$info['good_id'])->field('goods_title')->find();
		$info['username'] = $userInfo['username'];
		$info['phone'] = $userInfo['phone'];
		$info['address'] = $userInfo['address'];
		$info['goods_title'] = $goodsInfo['goods_title'];
		$this->r = $r;
		$this->info = $info;
		$this->display();
	}

	public function doedit(){
		$db = M('orders');
		$id = I('get.id');
		$data['pw_info'] = I('pw_info');
		$data['wl_info'] = I('wl_info');
		$data['statue'] = I('statue');
		$r = I('r');
		$res = $db->where('id=%d',$id)->save($data);
		if($res){
			$this->success('订单修改成功',$r);
		}else{
			$this->error('订单修改失败',$r);
		}
	}
	//最近订单
	public function last(){
		$keyword = I('get.keyword');
		$lastMin = date( 'Y-m-d H:i:s', strtotime('-10 day',time()) );
		$where['o.create_at'] = array('gt',$lastMin);
		if ($keyword) {
			$where['g.goods_name'] = array('like','%' . $keyword . '%');
		}
		$statue = I('get.statue') ? I('get.statue') : 0;
		if ($statue) {
			$where['o.statue'] = $statue;
		}
		$area = I('get.time_area');
		if($area){
			$times = explode('~',$area);
			$start_at = $times[0];
			$end_at = $times[1];
			$where['o.create_at'] = array('between',"{$start_at},{$end_at}");
		}
		$count 	= M('orders o')->join('pt_goods g on o.good_id=g.id')->where($where)->count();
		$page 	= show_page($count,10);
		$limit 	= $page->firstRow.','.$page->listRows;
		$table 	= 'pt_orders o';
		$join 	= array('LEFT JOIN pt_goods g on o.good_id=g.id');
		$field 	= 'o.id,o.pw_info,o.wl_info,o.from,o.statue,o.create_at,o.user_id,g.goods_title,o.remark,g.admin_id';
		$order 	= 'o.id desc';
		$list 	= M()->table($table)->join($join)->where($where)->field($field)->limit($limit)->order($order)->select();

		# 查询投放人名称
        $admin_data = M('admin')->field('admin_id, admin_name')->select();

		foreach($list as $k=>$v){
			$userInfo = M('member')->field('phone,username,address')->find($v['user_id']);
			$list[$k]['phone'] = $userInfo['phone'];
			$list[$k]['username'] = $userInfo['username'];
			$list[$k]['address'] = $userInfo['address'];

            //合并管理员名称
            foreach($admin_data as $admin_val){
                if($v['admin_id'] == $admin_val['admin_id']){
                    $list[$k]['admin_name'] = $admin_val['admin_name'];
                }
            }
		}

		$this->assign('time_area',$area);
		$this->assign('statue',$statue);
		$this->assign('keyword',$keyword);
		$this->assign('page',$page->show());
		$this->assign('list',$list);
		$this->display();
	}
	//待付款订单
	public function nopay(){
		$keyword = I('get.keyword');
		$where['statue'] = 1;
		if ($keyword) {
			$where['g.goods_name'] = array('like','%' . $keyword . '%');
		}
		$area = I('get.time_area');
		if($area){
			$times = explode('~',$area);
			$start_at = $times[0];
			$end_at = $times[1];
			$where['o.create_at'] = array('between',"{$start_at},{$end_at}");
		}
		$count 	= M('orders o')->join('pt_goods g on o.good_id=g.id')->where($where)->count();
		$page 	= show_page($count,10);
		$limit 	= $page->firstRow.','.$page->listRows;
		$table 	= 'pt_orders o';
		$join 	= array('LEFT JOIN pt_goods g on o.good_id=g.id');
		$field 	= 'o.id,o.pw_info,o.wl_info,o.from,o.statue,o.create_at,o.user_id,g.goods_name';
		$order 	= 'o.id desc';
		$list 	= M()->table($table)->join($join)->where($where)->field($field)->limit($limit)->order($order)->select();
		foreach($list as $k=>$v){
			$userInfo = M('member')->field('phone,username,address')->find($v['user_id']);
			$list[$k]['phone'] = $userInfo['phone'];
			$list[$k]['username'] = $userInfo['username'];
			$list[$k]['address'] = $userInfo['address'];
		}
		$this->assign('time_area',$area);
		$this->assign('statue',$where['statue']);
		$this->assign('keyword',$keyword);
		$this->assign('page',$page->show());
		$this->assign('list',$list);
		$this->display();
	}
   	//待发货订单
	public function nosend(){
		$keyword = I('get.keyword');
		$where['statue'] = 2;
		if ($keyword) {
			$where['g.goods_name'] = array('like','%' . $keyword . '%');
		}
		$area = I('get.time_area');
		if($area){
			$times = explode('~',$area);
			$start_at = $times[0];
			$end_at = $times[1];
			$where['o.create_at'] = array('between',"{$start_at},{$end_at}");
		}
		$count 	= M('orders o')->join('pt_goods g on o.good_id=g.id')->where($where)->count();
		$page 	= show_page($count,10);
		$limit 	= $page->firstRow.','.$page->listRows;
		$table 	= 'pt_orders o';
		$join 	= array('LEFT JOIN pt_goods g on o.good_id=g.id');
		$field 	= 'o.id,o.pw_info,o.wl_info,o.from,o.statue,o.create_at,o.user_id,g.goods_name';
		$order 	= 'o.id desc';
		$list 	= M()->table($table)->join($join)->where($where)->field($field)->limit($limit)->order($order)->select();
		foreach($list as $k=>$v){
			$userInfo = M('member')->field('phone,username,address')->find($v['user_id']);
			$list[$k]['phone'] = $userInfo['phone'];
			$list[$k]['username'] = $userInfo['username'];
			$list[$k]['address'] = $userInfo['address'];
		}
		$this->assign('time_area',$area);
		$this->assign('statue',$where['statue']);
		$this->assign('keyword',$keyword);
		$this->assign('page',$page->show());
		$this->assign('list',$list);
		$this->display();
	}
	//待退货退款订单
	public function norefund(){
		$keyword = I('get.keyword');
		$where['statue'] = 7;
		if ($keyword) {
			$where['g.goods_name'] = array('like','%' . $keyword . '%');
		}
		$area = I('get.time_area');
		if($area){
			$times = explode('~',$area);
			$start_at = $times[0];
			$end_at = $times[1];
			$where['o.create_at'] = array('between',"{$start_at},{$end_at}");
		}
		$count 	= M('orders o')->join('pt_goods g on o.good_id=g.id')->where($where)->count();
		$page 	= show_page($count,10);
		$limit 	= $page->firstRow.','.$page->listRows;
		$table 	= 'pt_orders o';
		$join 	= array('LEFT JOIN pt_goods g on o.good_id=g.id');
		$field 	= 'o.id,o.pw_info,o.wl_info,o.from,o.statue,o.create_at,o.user_id,g.goods_name';
		$order 	= 'o.id desc';
		$list 	= M()->table($table)->join($join)->where($where)->field($field)->limit($limit)->order($order)->select();
		foreach($list as $k=>$v){
			$userInfo = M('member')->field('phone,username,address')->find($v['user_id']);
			$list[$k]['phone'] = $userInfo['phone'];
			$list[$k]['username'] = $userInfo['username'];
			$list[$k]['address'] = $userInfo['address'];
		}
		$this->assign('time_area',$area);
		$this->assign('statue',$where['statue']);
		$this->assign('keyword',$keyword);
		$this->assign('page',$page->show());
		$this->assign('list',$list);
		$this->display();
	}
	//最近收入
	public function income(){
		$lastMin = date( 'Y-m-d H:i:s', strtotime('-7 day',time()) );
		$where['o.statue'] = 9;
		$where['o.statue'] = array('in','2,3,5,6,9');
		$where['o.create_at'] = array('gt',$lastMin);
		$table 	= 'pt_orders o';
		$join 	= array('LEFT JOIN pt_goods g on o.good_id=g.id');
		$field 	= 'o.id,o.statue,o.create_at,g.goods_trprice,g.goods_twprice,g.goods_price,g.goods_istuan,g.goods_country';
		$order 	= 'o.id desc';
		$list 	= M()->table($table)->join($join)->where($where)->field($field)->order($order)->select();
//		echo M()->getLastSql();var_dump($list);die;
		$time = $data = array();
		$j = 0;
		for($i=7;$i>=0;$i--){
			$time[$j] = date( 'Y-m-d H:i:s', strtotime('-'.$i.' day',time()) );
			$j++;
		}
		$cn = array(0,0,0,0,0,0,0);
		$uk = array(0,0,0,0,0,0,0);
		foreach($list as $k=>$v){
			for($w=0;$w<7;$w++){
				if($v['create_at'] > $time[$w] && $v['create_at'] < $time[$w+1]){
					if($v['goods_country'] == 'CN'){
						if($v['goods_istuan']){
							$cn[$w] += $v['goods_twprice'] + $v['goods_price'];
						}else{
							$cn[$w] += $v['goods_trprice'] + $v['goods_price'];
						}
					}
					if($v['goods_country'] == 'UK'){
						if($v['goods_istuan']){
							$uk[$w] += $v['goods_twprice'] + $v['goods_price'];
						}else{
							$uk[$w] += $v['goods_trprice'] + $v['goods_price'];
						}
					}
				}
			}
		}
		array_pop($time);
		foreach($time as $kkk=>$vvv){
			$key[] = substr($vvv,5,5);
		}
		$key = json_encode($key);
		$cn = json_encode($cn);
		$uk = json_encode($uk);
		$this->assign('key',$key);
		$this->assign('cn',$cn);
		$this->assign('uk',$uk);
		$this->assign('list',$list);
		$this->display();
	}
	//最近收入
	public function income_bak(){
		$keyword = I('get.keyword');
		$lastMin = date( 'Y-m-d H:i:s', strtotime('-7 day',time()) );
		$where['o.statue'] = 9;
		$where['o.create_at'] = array('gt',$lastMin);
		if ($keyword) {
			$where['g.goods_name'] = array('like','%' . $keyword . '%');
		}
		$count 	= M('orders o')->join('pt_goods g on o.good_id=g.id')->where($where)->count();
		$page 	= show_page($count,10);
		$limit 	= $page->firstRow.','.$page->listRows;
		$table 	= 'pt_orders o';
		$join 	= array('LEFT JOIN pt_goods g on o.good_id=g.id');
		$field 	= 'o.id,o.pw_info,o.wl_info,o.from,o.statue,o.create_at,o.user_id,g.goods_name';
		$order 	= 'o.id desc';
		$list 	= M()->table($table)->join($join)->where($where)->field($field)->limit($limit)->order($order)->select();
		foreach($list as $k=>$v){
			$userInfo = M('member')->field('phone,username,address')->find($v['user_id']);
			$list[$k]['phone'] = $userInfo['phone'];
			$list[$k]['username'] = $userInfo['username'];
			$list[$k]['address'] = $userInfo['address'];
		}
		$this->assign('keyword',$keyword);
		$this->assign('page',$page->show());
		$this->assign('list',$list);
		$this->display();
	}
	//订单趋势
	public function trend(){
		$where['o.statue'] = 9;
		$area = I('get.time_area');
		if($area){
			$times = explode('~',$area);
			$start_at = $times[0];
			$end_at = $times[1];
			$where['o.create_at'] = array('between',"{$start_at},{$end_at}");
		}
		$table 	= 'pt_orders o';
		$order 	= 'o.create_at asc,o.id asc';
		$list 	= M()->table($table)->where($where)->order($order)->select();
		$data = array();
		foreach($list as $k=>$v){
			$item = substr($v['create_at'],0,10);
			if($data[$item]){
				$data[$item] += 1;
			}else{
				$data[$item] = 1;
			}
		}
		$key = json_encode(array_keys($data));
		$val = json_encode(array_values($data));
		$this->key = $key;
		$this->val = $val;
		$this->assign('time_area',$area);
		$this->assign('list',$list);
		$this->display();
	}
	//订单趋势
	public function trend_bak(){
		$keyword = I('get.keyword');
		$lastMin = date( 'Y-m-d H:i:s', strtotime('-30 day',time()) );
		$where['o.statue'] = 9;
		$where['o.create_at'] = array('gt',$lastMin);
		if ($keyword) {
			$where['g.goods_name'] = array('like','%' . $keyword . '%');
		}
		$count 	= M('orders o')->join('pt_goods g on o.good_id=g.id')->where($where)->count();
		$page 	= show_page($count,10);
		$limit 	= $page->firstRow.','.$page->listRows;
		$table 	= 'pt_orders o';
		$join 	= array('LEFT JOIN pt_goods g on o.good_id=g.id');
		$field 	= 'o.id,o.pw_info,o.wl_info,o.from,o.statue,o.create_at,o.user_id,g.goods_name';
		$order 	= 'o.id desc';
		$list 	= M()->table($table)->join($join)->where($where)->field($field)->limit($limit)->order($order)->select();
		foreach($list as $k=>$v){
			$userInfo = M('member')->field('phone,username,address')->find($v['user_id']);
			$list[$k]['phone'] = $userInfo['phone'];
			$list[$k]['username'] = $userInfo['username'];
			$list[$k]['address'] = $userInfo['address'];
		}
		$this->assign('keyword',$keyword);
		$this->assign('page',$page->show());
		$this->assign('list',$list);
		$this->display();
	}
	//订单来源
    public function source(){
		$keyword = I('get.keyword');
		$lastMin = date( 'Y-m-d H:i:s', strtotime('-30 day',time()) );
		$where['o.statue'] = 9;
		$where['o.create_at'] = array('gt',$lastMin);
		if ($keyword) {
			$where['g.goods_name'] = array('like','%' . $keyword . '%');
		}
		$area = I('get.time_area');
		if($area){
			$times = explode('~',$area);
			$start_at = $times[0];
			$end_at = $times[1];
			$where['o.create_at'] = array('between',"{$start_at},{$end_at}");
		}
		$count 	= M('orders o')->join('pt_goods g on o.good_id=g.id')->where($where)->count();
		$page 	= show_page($count,10);
		$limit 	= $page->firstRow.','.$page->listRows;
		$table 	= 'pt_orders o';
		$join 	= array('LEFT JOIN pt_goods g on o.good_id=g.id');
		$field 	= 'o.id,o.pw_info,o.wl_info,o.from,o.statue,o.create_at,o.user_id,g.goods_name';
		$order 	= 'o.id desc';
		$list 	= M()->table($table)->join($join)->where($where)->field($field)->limit($limit)->order($order)->select();
		foreach($list as $k=>$v){
			$userInfo = M('member')->field('phone,username,address')->find($v['user_id']);
			$list[$k]['phone'] = $userInfo['phone'];
			$list[$k]['username'] = $userInfo['username'];
			$list[$k]['address'] = $userInfo['address'];
		}
		$this->assign('time_area',$area);
		$this->assign('keyword',$keyword);
		$this->assign('page',$page->show());
		$this->assign('list',$list);
		$this->display();
	}




	public function del(){
	//编辑订单状态
		$id = I('get.id');
		$r = I('r');
		$res = M('orders')->delete($id);
		if($res){
			$this->success('订单删除成功',$r);
		}else{
			$this->error('订单删除失败',$r);
		}

	}
}
