<?php
// +----------------------------------------------------------------------
// | Teacher后台
// +----------------------------------------------------------------------

namespace Admin\Controller;
use Think\Controller;

/**
 * Class TeacherController
 * @package Admin\Controller
 */
class TeacherController extends BaseController {

    /**
     * Teacher后台首页
     */
    public function index(){
        $this->_data['title'] = '个人中心';
        $this->assign($this->_data);
        $this->display();
    }


    /**
     * 修改个人资料
     */
    public function profile() {
        $this->_data['title'] = '修改个人资料';
//        var_dump($this->_data);
        $this->assign($this->_data);
        $this->display();
    }


    /**
     * 修改密码
     */
    public function password() {
        $this->_data['title'] = '修改密码';
        $this->assign($this->_data);
        $this->display();
    }
}