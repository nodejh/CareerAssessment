<?php
// +----------------------------------------------------------------------
// | User 后台
// +----------------------------------------------------------------------

namespace Admin\Controller;
use Think\Controller;

/**
 * Class IndexController
 * @package Admin\Controller
 */
class UserController extends BaseController {

    /**
     * User 个人中心
     */
    public function index(){
//logout();
        $this->_data['title'] = '个人中心';

        if (isset($_SESSION['f'])) {
            // first time to login. suggest to change the password
            $this->_data['first'] = '1';
        }

        $Teacher = M('teacher');
        $list = $Teacher->order('teacher_id desc')->limit(16)->select();
        $this->_data['list'] = $list;

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