<?php
// +----------------------------------------------------------------------
// | Home Page
// +----------------------------------------------------------------------

namespace Home\Controller;
use Think\Controller;

/**
 * Class IndexController
 * @package Home\Controller
 */
class IndexController extends BaseController {


    /**
     * Home Page
     */
    public function index(){
        $this->_data['title'] = '个人中心';
        
        $Teacher = M('teacher');
        $list = $Teacher->order('teacher_id desc')->limit(16)->select();
        $this->_data['list'] = $list;

        $this->assign($this->_data);
        $this->display();
    }
}