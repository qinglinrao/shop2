<?php
namespace Admin\Controller;
use Think\Controller;

class ConfigController extends Controller {

    public function index(){
        $siteinfo_file = 'Config/siteinfo.config.php';
        $siteinfo_file = $siteinfo_file ? $siteinfo_file : array();
        $this -> assign('siteinfo', $siteinfo_file);
        $this->display();
    }

    public function update_config(){
        $siteinfo_file = 'Config/siteinfo.config.php';
        if(file_exists($siteinfo_file)){
            if(IS_POST){
                // 写入文件，这里是关键
                // I() 方法获取提交的数据
                // var_export() 处理数组
                $result = file_put_contents($siteinfo_file, "<?php\nreturn " . var_export(I('post.'), true).';');
                if($result){
                    $this -> success('保存成功');
                }else{
                    $this -> error('保存失败');
                }
            }else{
                $this -> error('非法操作');
            }
        }else{
            $this -> error('配置文件不存在！');
        }
    }


}
