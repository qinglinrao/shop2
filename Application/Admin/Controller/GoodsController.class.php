<?php
namespace Admin\Controller;
use Think\Controller;

class GoodsController extends CommonController {
//商品管理
	function index(){
	//列表页
		$keyword = I('get.keyword');
		$db = M('goods');

		# 管理员数据
        $admin_data = M('admin')->field('admin_id, admin_name')->select();
		if ($keyword) {
			$where['goods_title'] = array('like','%' . $keyword . '%');
			$wherestr['a.goods_title'] = array('like','%' . $keyword . '%');
		}
		$count 	= $db->where($where)->count();
		$page 	= show_page($count,10);
		$limit 	= $page->firstRow.','.$page->listRows;		
		$table 	= 'pt_goods a';
		$join 	= array('LEFT JOIN pt_goods_type b on a.cate_id = b.id');
		$field 	= 'a.*,b.type_name as tname';
		$order 	= 'a.id desc';
		$list 	= M()->table($table)->join($join)->where($wherestr)->field($field)->limit($limit)->order($order)->select();
		foreach($list as $k=>$v) {
			//商品轮播图首图
			$itemImg = M('goods_image')->where('stype=1 and good_id=%d', $v['id'])->order('id asc')->limit(1)->field('image')->select();
			$list[$k]['goods_img'] = $itemImg[0]['image'];
			//普通标签
			if ($v['goods_tag'] != 0){
				$tagList = explode(',', $v['goods_tag']);
				$itemTag = '';
				foreach ($tagList as $vv) {
					$itemTagInfo = M('tag')->find($vv);
					$itemTag .= $itemTagInfo['tag_name'] . ' ';
				}
				$list[$k]['tag_name'] = $itemTag;
			}
			//拼团
			if ($v['goods_istuan'] != 0){
				$itemTuanInfo = M('tourdiy')->find($v['goods_istuan']);
				$list[$k]['tuan_name'] = $itemTuanInfo['pname'];
			}
			//促销
			if ($v['goods_promotion'] != 0){
				$itemTuanInfo = M('promotion')->find($v['goods_promotion']);
				$list[$k]['pro_name'] = $itemTuanInfo['pro_name'];
			}

			//合并管理员名称
            foreach($admin_data as $admin_val){
                if($v['admin_id'] == $admin_val['admin_id']){
                    $list[$k]['admin_name'] = $admin_val['admin_name'];
                }
            }

		}
		$this->assign('keyword',$keyword);
		$this->assign('page',$page->show());
		$this->assign('list',$list);
		$this->display();
	}
		
	function add(){
	//添加页
		if(IS_POST){
			$type = 'images';
			$folder = 'Goods';
			$item = 'upimg5';
			$name = 1;
			$width = 200;
			$height = 200;
			$this->_ajaxupload($type,$folder,$item,$name,$width,$height);
		}
		$list = $slist = '';
		$list = M('goods_type')->where('pid=0 and statue = 1')->select();

		foreach ($list as $k => $v) {
			$slist  = M('goods_type')->where('pid='.$v['id'].' and statue = 1')->select();
			$list[$k]['slist'] = $slist;
		}
		$country = M('country')->order('id desc')->select();
		$this->assign('country',$country);
		$this->assign('list',$list);
		$this->display();
	}

	function doadd(){
	//添加操作
		if(IS_POST){
			$data['goods_country'] = I('goods_country');
			# 投放人（管理员）id
			$data['admin_id'] = $_SESSION['admin_id'];
			$data['goods_title'] = I('goods_title');
			$data['goods_subtitle'] = I('goods_subtitle');
			$data['goods_number'] = I('goods_number');
			$data['video_url'] = I('video_url');
			$data['goods_toprice'] = I('goods_toprice');
			$data['goods_trprice'] = I('goods_trprice');
			$data['goods_twprice'] = I('goods_twprice');
			$data['goods_psprice'] = I('goods_psprice');
			$data['goods_purchase_url'] = I('goods_purchase_url');
			$data['cate_id'] = I('pid');
			$data['cate_id_2'] = I('pid');
			//$data['goods_sort'] = I('goods_sort');
			//$data['goods_gg'] = I('goods_gg');
			$data['goods_price'] = ceil(I('goods_price'));
			$data['goods_introduce'] = I('goods_introduce');
			$data['goods_notice'] = I('goods_notice');
			$imglist = array_filter(I('lunbo'));
			if($imglist){
				//$data['goods_imgs'] = implode(',', $imglist);
			}

			$cateId = I('pid');
            $cateInfo  = M('goods_type')->where('id='.$cateId)->find();
            $data['cate_id_1'] = $cateInfo['pid'];
			/*$data['goods_det'] = I('goods_det');*/
			$goods_det = array_filter(I('goods_det'));
			$data['addtime'] = NowTime();
			$data['edittime'] = NowTime();
			$db = M('goods');
			$db->create($data);
			$goodId = $db -> add();
//            $goodId = $db->insert_id();
            if ($goodId > 0) {
                $imgDb = M('goods_image');
                foreach ($imglist as $k=>$v) {

                    # 保存一下后缀名
                    $name_arr = explode('.', $v);
                    $postfix_name = $name_arr[count($name_arr)-1];

                    $arr = array("gif", "jpg", "jpeg", "bmp", "png");
                    if(in_array(strtolower($postfix_name), $arr)){
                        $is_img = 1;
                    }else{
                        $is_img = 2;
                    }

                    $imageData = array(
                        'good_id' => $goodId,
                        'image' => $v,
                        'is_img' => $is_img,
                        'stype' => 1,
                        'add_time' => time(),
                    );
                    $imgDb->create($imageData);
                    $res = $imgDb -> add();
                }
            }

            # 批量上传描述长图
            if ($goodId > 0) {
                $imgDb = M('goods_image');
                foreach ($goods_det as $k=>$v) {

                    # 保存一下后缀名
                    $name_arr = explode('.', $v);
                    $postfix_name = $name_arr[count($name_arr)-1];

                    $arr = array("gif", "jpg", "jpeg", "bmp", "png");
                    if(in_array(strtolower($postfix_name), $arr)){
                        $is_img = 1;
                    }else{
                        $is_img = 2;
                    }

                    $imageData = array(
                        'good_id' => $goodId,
                        'image' => $v,
                        'is_img' => $is_img,
                        'stype' => 2,  //2是描述长图的类型
                        'add_time' => time(),
                    );
                    $imgDb->create($imageData);
                    $res = $imgDb -> add();
                }
            }


            $this->success('信息添加成功',U('Goods/index'));

		}else{
			$this->error('参数错误',U('Goods/add'));
		}

	}

	function edit(){
	//编辑页
		$id = I('id');
		$info = M('goods')->find($id);
		$info['imgs'] = array();
		if($info['goods_imgs']){
			$info['imgs'] = explode(',',$info['goods_imgs']);
		}

		//dump($info);die;
		$list = $slist = '';
        $list = M('goods_type')->where('pid=0 and statue = 1')->select();

        foreach ($list as $k => $v) {
            $slist  = M('goods_type')->where('pid='.$v['id'].' and statue = 1')->select();
            $list[$k]['slist'] = $slist;
        }

        //获取商品轮播图
        $imgDb = M('goods_image');
        $imgList = $imgDb->where('good_id=%d and sid = 0 and stype = 1',$id)->select();
        foreach ($imgList as $k=>$v) {
            $no = $k+1;
            $this->assign('imgList'.$no,$v['image']);//商品信息
        }
		$country = M('country')->select();
		$this->assign('country',$country);
		$this->assign('list',$list);//分类信息
		$this->assign('info',$info);//商品信息
		$this->display();
	}

	function doedit(){
	//编辑操作
		$id = I('id');
		if($id){
			$where['id'] = $id;
			if(IS_POST){
                $data['goods_country'] = I('goods_country');
                $data['goods_title'] = I('goods_title');
                $data['goods_subtitle'] = I('goods_subtitle');
                $data['goods_toprice'] = I('goods_toprice');
                $data['goods_trprice'] = I('goods_trprice');
                $data['goods_twprice'] = I('goods_twprice');
                $data['goods_number'] = I('goods_number');
                $data['goods_psprice'] = I('goods_psprice');
                $data['goods_purchase_url'] = I('goods_purchase_url');
                $data['video_url'] = I('video_url');
                $data['goods_introduce'] = I('goods_introduce');
                $data['cate_id'] = I('pid');
                $data['cate_id_2'] = I('pid');
                //$data['goods_sort'] = I('goods_sort');
                //$data['goods_gg'] = I('goods_gg');
                $data['goods_price'] = ceil(I('goods_price'));
                $data['goods_notice'] = I('goods_notice');
                $imglist = array_filter(I('lunbo'));
                $cateId = I('pid');
                $cateInfo  = M('goods_type')->where('id='.$cateId)->find();
                $data['cate_id_1'] = $cateInfo['pid'];
                $data['goods_det'] = I('goods_det');
				$data['edittime'] = NowTime();
				$db = M('goods');
				$db->create($data);
				$res = $db -> where($where) ->save();
				/*if ($id > 0) {
                    $imgDb = M('goods_image');
                    $res = $imgDb->where('good_id=%d and sid = 0',$id)->delete();
				    foreach ($imglist as $k=>$v) {
				        $imageData = array(
				            'good_id' => $id,
                            'image' => $v,
                            'stype' => 1,
                            'add_time' => time(),
                        );
                        $imgDb->create($imageData);
                        $res = $imgDb -> add();
                    }
                }*/
				$this->success('信息更新成功',U('Goods/index'));
			}else{
				$this->error('请求参数错误',U('Goods/index'));
			}
		}else{
			$this->error('缺少必要参数',U('Goods/index'));
		}

	}

    /**
     * 批量导入
     */
    function addmore()
    {
        //批量导入
        $this->display();
    }

    /**
     * 规格列表
     */
	function configlist()
    {
        $goodId = I('get.id');
        $join 	= array('LEFT JOIN pt_goods_image img on s.id = img.sid');
        $list 	= M('goods_size s')->join($join)->where('s.good_id='.$goodId)->field('s.id,s.color,s.size,s.weight,img.image,s.good_id')->order('s.id desc')->select();

        $this->assign('list',$list);
        $this->assign('goodId',$goodId);
        $this->display();
    }

    function configadd()
    {
        $goodId = I('get.goodsId');
        $this->assign('goodId',$goodId);
        $this->display();
    }

    /**
     * 执行上传商品
     */
    public function doaddmore()
    {
        //获取上传文件
        if(IS_POST){
            $file = I('goodsFile');
           // $file = './Uploads/Goods/5ad32eccbf218.csv';
            $file = fopen($file,"r");
            $data = array();
            $imageEtx = array('png','gif','jpg','jpeg');
            $i = 0;
            while(! feof($file))
            {
                $i++;
                $info = fgetcsv($file);
                $info = $this->gbktoutf8($info);
                if ($i == 1) {
                    continue;
                }
                $data[$i]['goods'] = array(
                    'goods_title' => $info[0],
                    'goods_subtitle' => $info[1],
                    'goods_toprice' => $info[2],
                    'goods_trprice' => $info[3],
                    'goods_twprice' => $info[4],
                    'goods_det' => $info[5],
                    'goods_price' => $info[6],
                    'goods_country' => $info[7],
                    'goods_istuan' => $info[8],
                    'goods_notice' => $info[9],
                    'goods_promotion' => $info[10],
                    'goods_tag' => $info[11],
                    'cate_id1' => $info[12],
                    'cate_id2' => $info[13],
                    'cate_id' => $info[13],
                    'edittime' => NowTime(),
                );

                $goods_notice_ext = pathinfo($data[$i]['goods']['goods_notice']);
                $goods_det_ext = pathinfo($data[$i]['goods']['goods_det']);

                if (!in_array($goods_det_ext['extension'],$imageEtx) || !in_array($goods_notice_ext['extension'],$imageEtx)) {
                    $num = $i-1;
                    $this->error('第'.$num.'购买须知或长描述图格式不正确',U('Goods/addmore'));
                    die();
                }


                $data[$i]['images'] = explode('@',$info[15]);

                foreach ($data[$i]['images'] as $lunboImg) {
                    $lunboImg_ext = pathinfo($lunboImg);
                    if (!in_array($lunboImg_ext['extension'],$imageEtx)) {
                        $num = $i-1;
                        $this->error('第'.$num.'条轮播图图格式不正确',U('Goods/addmore'));
                        die();
                    }
                }

                $data[$i]['size'] = array();

                $sizeInfo = $info[14];
                $sizeArr = explode('@',$sizeInfo);
                if (!empty($sizeArr)) {
                    foreach ($sizeArr as $k=>$v) {
                        if (empty($v)) continue;
                        $sizeOne = explode('|',$v);
                        if (!empty($sizeOne)) {
                            $sizeImg_ext = pathinfo($sizeOne[3]);
                            if (!in_array($sizeImg_ext['extension'],$imageEtx)) {
                                $num = $i-1;
                                $sizeK = $k+1;
                                $this->error('第'.$num.'条的第'.$sizeK.'个规格图格式不正确',U('Goods/addmore'));
                                die();
                            }

                            if (empty($sizeOne[0]) && empty($sizeOne[1]) && $sizeOne[2]) {
                                $num = $i-1;
                                $sizeK = $k+1;
                                $this->error('第'.$num.'条的第'.$sizeK.'规格数据都为空',U('Goods/addmore'));
                                die();
                            }

                            $data[$i]['size'][] = array(
                                'color' => $sizeOne[0],
                                'size' => $sizeOne[1],
                                'weight' => $sizeOne[2],
                                'add_time' => time(),
                                'image' => $sizeOne[3],
                            );
                        }
                    }
                }
            }
            $j = 0;
            if (!empty($data)) {
                $db = M('goods');
                $imgDb = M('goods_image');
                $dbsize = M('goods_size');
                foreach ($data as $k=>$v) {
                    if (empty($v)) continue;
                    $goodImg = $v['images'];
                    $goodInfo = $v['goods'];
                    $goodSize = $v['size'];

                    //添加商品
                    $db->create($goodInfo);
                    $goodId = $db -> add();
                    if ($goodId > 0) {
                        $j++;
                    } else {
                        continue;
                    }

                    //添加轮播图
                    foreach ($goodImg as $k=>$v) {
                        $v = trim($v);
                        if (empty($v)) continue;
                        $imageData = array(
                            'good_id' => $goodId,
                            'image' => $v,
                            'stype' => 1,
                            'add_time' => time(),
                        );
                        $imgDb->create($imageData);
                        $res = $imgDb -> add();
                    }

                    //添加规格
                    foreach ($goodSize as $size) {
                        if (empty($size)) continue;
                        $sizeImage = $size['image'];
                        unset($size['image']);
                        $size['good_id'] = $goodId;
                        $dbsize->create($size);
                        $sid = $dbsize->add();
                        $imageData = array(
                            'good_id' => $goodId,
                            'image' => $sizeImage,
                            'sid' => $sid,
                            'stype' => 0,
                            'add_time' => time(),
                        );
                        $imgDb->create($imageData);
                        $res = $imgDb->add();
                    }
                }
            }

            fclose($file);
        }

        if ($j > 0) {
            $this->success('成功导入'.$j.'条数据',U('Goods/index'));
        } else {
            $this->error('参数错误',U('Goods/addmore'));
        }
    }

    # 添加商品规格
    function doaddconfig()
    {
		$id = I('get.goodId');
        if(IS_POST){
            $data = array();
            $data['good_id'] = I('good_id');
            $data['color'] = I('color');
            $data['size'] = I('size');
            $data['weight'] = I('weight');
            $data['add_time'] = time();
            $db = M('goods_size');
            $db->create($data);
            $sid = $db -> add();
            $imageData = array(
                'good_id' => $data['good_id'],
                'image' => I('image'),
                'sid' => $sid,
                'stype' => 0,
                'add_time' => time(),
            );
            $imgDb = M('goods_image');
            $imgDb->create($imageData);
            $res = $imgDb -> add();
            $this->success('信息更新成功',U('Goods/configlist',array('id'=>$data['good_id'])));
        }else{
            $this->error('参数错误',U('Goods/configlist',array('id'=>$id)));
        }
    }

    function editconfig()
    {
        $sid = I('get.id');
        $id = I('id');
        $info = M('goods_size')->find($sid);

        //dump($info);die;

        //获取商品轮播图
        $imgDb = M('goods_image');
        $imgList = $imgDb->where('sid=%d',$sid)->select();
        foreach ($imgList as $k=>$v) {
            $no = $k+1;
            $this->assign('imgList'.$no,$v['image']);//商品信息
        }
        $this->assign('info',$info);//商品信息
        $this->display();

    }

    function doeditconfig()
    {
        $id = I('id');
        $good_id = I('good_id');
        if($id){
            $where['id'] = $id;
            if(IS_POST){

                $data['good_id'] = I('good_id');
                $data['color'] = I('color');
                $data['size'] = I('size');
                $data['weight'] = I('weight');
                $data['add_time'] = time();
                $db = M('goods_size');
                $db->create($data);
                $res = $db -> where($where) ->save();
                if ($id > 0) {
                    $imgDb = M('goods_image');
                    $res = $imgDb->where('sid = %d',$id)->delete();
                        $imageData = array(
                            'good_id' => $good_id,
                            'image' => I('image'),
                            'stype' => 0,
                            'sid' => $id,
                            'add_time' => time(),
                        );
                        $imgDb->create($imageData);
                        $res = $imgDb -> add();
                }
                $this->success('信息更新成功',U('Goods/configlist',array('id'=>I('good_id'))));
            }else{
                $this->error('参数错误',U('Goods/configlist',array('id'=>I('good_id'))));
            }
        }else{
            $this->error('参数错误',U('Goods/configlist',array('id'=>I('good_id'))));
        }
    }

    //删除规格
    function delconfig()
    {
        $goodId = I('get.goodId');
        $id = I('get.id');
        if($id){
            $res = M('goods_size')->where('id=%d',$id)->delete();
            if($res){
                $this->success('信息更新成功',U('Goods/configlist',array('id'=>$goodId)));
            }else{
                $this->error('删除失败',U('Goods/configlist',array('id'=>$goodId)));
            }
        }else{
            $this->error('删除失败',U('Goods/configlist',array('id'=>$goodId)));
        }
    }

	function del(){
	//删除操作
		$id = I('get.id');
		if($id){
			$res = M('goods')->where('id=%d',$id)->delete();
			if($res){
				$this->success('信息删除成功',U('Goods/index'));
			}else{
				$this->error('信息删除失败，没有找到该信息',U('Goods/index'));
			}
		}else{
			$this->error('缺少必要参数',U('Goods/index'));
		}
	}

	function sell(){
	//上下架操作
		$id = I('id');
		$stats = I('get.goods_stats');

		if($stats&&$id){
			$data['goods_stats'] = (int)$stats;
			$where['id']  =	 $id;
			$res = M('goods')->where($where)->save($data);
			if($res){
				$this->success('数据编辑成功',U('Goods/index'));
			}else{
				$this->error('商品状态变更失败');
			}  
		}else{
			$this->error('缺少必要参数');
		}	
	}

    public function imgup6() {
        $type = 'images';
        $folder = 'Goods';
        $item = 'upimg6';
        $name = "";
        $width = 3000;
        $height = 3000;
        $isCut = 0;   //1为居中剪裁，0为只等比缩放
        $this->_ajaxupload($type,$folder,$item,$name,$width,$height,$isCut);
    }

	public function imgup5() {
		$type = 'images';
		$folder = 'Goods';
		$item = 'upimg5';
		$name = "";
		$width = 900;
		$height = 900;
		$this->_ajaxupload($type,$folder,$item,$name,$width,$height);
	}

	public function imgup4() {
		$type = 'images';
		$folder = 'Goods';
		$item = 'upimg4';
		$name = "";
		$width = 900;
		$height = 900;
		$this->_ajaxupload($type,$folder,$item,$name,$width,$height);
	}

	public function imgup3() {
		$type = 'images';
		$folder = 'Goods';
		$item = 'upimg3';
		$name = "";
		$width = 900;
		$height = 900;
		$this->_ajaxupload($type,$folder,$item,$name,$width,$height);
	}

	public function imgup2() {
		$type = 'images';
		$folder = 'Goods';
		$item = 'upimg2';
		$name = "";
		$width = 900;
		$height = 900;
		$this->_ajaxupload($type,$folder,$item,$name,$width,$height);
	}

	public function imgup1() {
		$type = 'images';
		$folder = 'Goods';
		$item = 'upimg1';
		$name = "";
		$width = 900;
		$height = 900;
		$this->_ajaxupload($type,$folder,$item,$name,$width,$height);
	}

	public function imgup0() {
		$type = 'images';
		$folder = 'Goods';
		$item = 'upimg0';
		$name = "";
		$width = 900;
		$height = 900;
		$this->_ajaxupload($type,$folder,$item,$name,$width,$height);
	}

    public function imgupcsv() {
        $type = 'file';
        $folder = 'Goods_csv';
        $item = 'upimg0';
        $name = "";
        $width = 900;
        $height = 900;
        $this->_ajaxupload($type,$folder,$item,$name,$width,$height);
    }

	// 设置推荐商品操作
	public function tj() {
		$id = I('get.id');
		if($id){
			$data['tj_sort'] = I('tj_sort');
			$data['is_tj'] = 1;
			$res = M('goods')->where('id=%d',$id)->save($data);
			if($res){
				$this->success('推荐商品成功',U('Goods/index'));
			}else{
				$this->error('推荐商品失败',U('Goods/index'));
			}
		}else{
			$this->error('请选择您要推荐的商品');
		}
	}
	//添加、编辑普通标签页面
	public function goods_tag(){
		$id = I('get.id');
		$type = I('get.type');
		$info = M('goods')->find($id);
		$tagList = explode(',',$info['goods_tag']);
		$tag = M('tag')->where('statue=1')->select();
		$this->type = $type;
		$this->info = $info;
		$this->tagList = $tagList;
		$this->tag = $tag;
		$this->display();
	}
	//添加、编辑普通标签操作
	public function goods_tag_edit(){
		$data['id'] = I('get.id');
		$type = I('get.type');
		$tag = I('tag');
		if($tag == ''){
			$data['goods_tag'] = 0;
		}else{
			$data['goods_tag'] = implode($tag,',');
		}
		$model = M('goods');
		$model->create($data);
		$res = $model->save();
		if($type==1){
			$str = '添加';
		}else{
			$str = '编辑';
		}
		if($res){
			$this->success($str.'商品标签成功',U('Goods/index'));
		}else{
			$this->error($str.'商品标签失败',U('Goods/index'));
		}
	}
	//添加、编辑拼团页面
	public function goods_istuan(){
		$id = I('get.id');
		$type = I('get.type');
		$info = M('goods')->find($id);
		$tag = M('tourdiy')->where('statue=1')->select();
		$this->type = $type;
		$this->info = $info;
		$this->tag = $tag;
		$this->display();
	}
	//添加、编辑拼团操作
	public function goods_istuan_edit(){
		$data['id'] = I('get.id');
		$type = I('get.type');
		$data['goods_istuan'] = I('tag');
		$model = M('goods');
		$model->create($data);
		$res = $model->save();
		if($type==1){
			$str = '添加';
		}else{
			$str = '编辑';
		}
		if($res){
			$this->success('商品'.$str.'拼团成功',U('Goods/index'));
		}else{
			$this->error('商品'.$str.'拼团失败',U('Goods/index'));
		}
	}
	//添加、编辑促销标签页面
	public function goods_promotion(){
		$id = I('get.id');
		$type = I('get.type');
		$info = M('goods')->find($id);
		$tag = M('promotion')->where('statue=1')->select();
		$this->type = $type;
		$this->info = $info;
		$this->tag = $tag;
		$this->display();
	}
	//添加、编辑促销标签操作
	public function goods_promotion_edit(){
		$data['id'] = I('get.id');
		$type = I('get.type');
		$data['goods_promotion'] = I('tag');
		$model = M('goods');
		$model->create($data);
		$res = $model->save();
		if($type==1){
			$str = '添加';
		}else{
			$str = '编辑';
		}
		if($res){
			$this->success('商品'.$str.'促销标签成功',U('Goods/index'));
		}else{
			$this->error('商品'.$str.'促销标签失败',U('Goods/index'));
		}
	}
    public function gbktoutf8($info){
        $data = array();
        foreach($info as $v){
            $encode = mb_detect_encoding($v, array('ASCII','UTF-8','GB2312','GBK','BIG5'));
            $data[] = iconv($encode, 'UTF-8', $v);
        }
        return $data;
    }
    public function downFile($path = ''){
        $path = './Uploads/Goods_csv/demo.csv';
        $this->download($path);
    }

    function download($file_url,$new_name=''){

        if(!isset($file_url)||trim($file_url)==''){
            echo '500';
        }
        if(!file_exists($file_url)){ //检查文件是否存在
            echo '404';
        }
        $file_name=basename($file_url);
        $file_type=explode('.',$file_url);
        $file_type=$file_type[count($file_type)-1];
        $file_name=trim($new_name=='')?$file_name:urlencode($new_name);
        $file_type=fopen($file_url,'r'); //打开文件
        //输入文件标签

        header("Content-type: application/octet-stream");
        header("Accept-Ranges: bytes");
        header("Accept-Length: ".filesize($file_url));
        header("Content-Disposition: attachment; filename=示例表格.csv");
        //输出文件内容
        echo fread($file_type,filesize($file_url));
        fclose($file_url);
        exit();
    }

    public function upload_file_more(){
        #!! 注意
        #!! 此文件只是个示例，不要用于真正的产品之中。
        #!! 不保证代码安全性。

        #!! IMPORTANT:
        #!! this file is just an example, it doesn't incorporate any security checks and
        #!! is not recommended to be used in production environment as it is. Be sure to
        #!! revise it and customize to your needs.


        // Make sure file is not cached (as it happens for example on iOS devices)
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");


        // Support CORS
        // header("Access-Control-Allow-Origin: *");
        // other CORS headers if any...
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            exit; // finish preflight CORS requests here
        }


        if ( !empty($_REQUEST[ 'debug' ]) ) {
            $random = rand(0, intval($_REQUEST[ 'debug' ]) );
            if ( $random === 0 ) {
                header("HTTP/1.0 500 Internal Server Error");
                exit;
            }
        }

        // header("HTTP/1.0 500 Internal Server Error");
        // exit;


        // 5 minutes execution time
                @set_time_limit(5 * 60);

        // Uncomment this one to fake upload time
        // usleep(5000);

        // Settings
        // $targetDir = ini_get("upload_tmp_dir") . DIRECTORY_SEPARATOR . "plupload";


        $cleanupTargetDir = true; // Remove old files
        $maxFileAge = 5 * 3600; // Temp file age in seconds




        // Get a file name
        if (isset($_REQUEST["name"])) {
            $fileName = $_REQUEST["name"];
        } elseif (!empty($_FILES)) {
            $fileName = $_FILES["file"]["name"];
        } else {
            $fileName = uniqid("file_");
        }
        # 不要原来名称，改成文件格式+随机数
        $name_arr = explode('.', $fileName);
        $fileName = $name_arr[count($name_arr)-1];
        # 改成唯一id
        $fileNameNew = time() . uniqid() . '.'.$fileName;
        # 如果是文件上传，没有后缀名或者上传不成功，可能是服务器显示上传大小的问题。
        file_put_contents("./data.txt",'$fileName'.json_encode($fileName).PHP_EOL, FILE_APPEND);
        file_put_contents("./data.txt",'$name_arr'.json_encode($name_arr).PHP_EOL, FILE_APPEND);
        file_put_contents("./data.txt",'$fileNameNew'.json_encode($fileNameNew).PHP_EOL, FILE_APPEND);


        # 批量上传目录
        $targetDir = 'upload_tmp';
        $arr = array("gif", "jpg", "jpeg", "bmp", "png");
        if(in_array(strtolower($fileName), $arr)){
            $uploadDir = 'upload/Goods';
            $path = 'Goods';
        }else{
            $uploadDir = 'upload/Videos';
            $path = 'Videos';
        }
        file_put_contents("./data.txt",'gettype($fileName)'.json_encode(gettype($fileName)).PHP_EOL, FILE_APPEND);

        file_put_contents("./data.txt",'in_array(strtolower($fileName), $arr)'.json_encode(in_array(strtolower($fileName), $arr)).PHP_EOL, FILE_APPEND);

        // Create target dir
        if (!file_exists($targetDir)) {
            @mkdir($targetDir);
        }

        // Create target dir
        if (!file_exists($uploadDir)) {
            @mkdir($uploadDir);
        }

        $filePath = $targetDir . DIRECTORY_SEPARATOR . $fileNameNew;
        $uploadPath = $uploadDir . DIRECTORY_SEPARATOR . $fileNameNew;

        // Chunking might be enabled
        $chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
        $chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 1;


        // Remove old temp files
        if ($cleanupTargetDir) {
            if (!is_dir($targetDir) || !$dir = opendir($targetDir)) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
            }

            while (($file = readdir($dir)) !== false) {
                $tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;

                // If temp file is current file proceed to the next
                if ($tmpfilePath == "{$filePath}_{$chunk}.part" || $tmpfilePath == "{$filePath}_{$chunk}.parttmp") {
                    continue;
                }

                // Remove temp file if it is older than the max age and is not the current file
                if (preg_match('/\.(part|parttmp)$/', $file) && (@filemtime($tmpfilePath) < time() - $maxFileAge)) {
                    @unlink($tmpfilePath);
                }
            }
            closedir($dir);
        }


        // Open temp file
        if (!$out = @fopen("{$filePath}_{$chunk}.parttmp", "wb")) {
            die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
        }

        if (!empty($_FILES)) {
            if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
            }

            // Read binary input stream and append it to temp file
            if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
            }
        } else {
            if (!$in = @fopen("php://input", "rb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
            }
        }

        while ($buff = fread($in, 4096)) {
            fwrite($out, $buff);
        }

        @fclose($out);
        @fclose($in);

        rename("{$filePath}_{$chunk}.parttmp", "{$filePath}_{$chunk}.part");

        $index = 0;
        $done = true;
        for( $index = 0; $index < $chunks; $index++ ) {
            if ( !file_exists("{$filePath}_{$index}.part") ) {
                $done = false;
                break;
            }
        }
        if ( $done ) {
            if (!$out = @fopen($uploadPath, "wb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
            }

            if ( flock($out, LOCK_EX) ) {
                for( $index = 0; $index < $chunks; $index++ ) {
                    if (!$in = @fopen("{$filePath}_{$index}.part", "rb")) {
                        break;
                    }

                    while ($buff = fread($in, 4096)) {
                        fwrite($out, $buff);
                    }

                    @fclose($in);
                    @unlink("{$filePath}_{$index}.part");
                }

                flock($out, LOCK_UN);
            }
            @fclose($out);
        }

        // Return Success JSON-RPC response
        /*die('{"jsonrpc" : "2.0", "url" : "./Goods/'.$fileNameNew.'", "id" : "id"}');*/
        die("./upload/".$path."/$fileNameNew");
    }
}