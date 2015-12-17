<?php
// +----------------------------------------------------------------------
// | Teacher后台
// +----------------------------------------------------------------------

namespace Admin\Controller;
use Think\Controller;

/**
 * Class TeacherAppointController
 * @package Admin\Controller
 */
class TeacherApplyController extends BaseController {

    public function apply_one () {
        $this->is_teacher();
        $this->_data['title'] = '填写基本资料';
        $this->assign($this->_data);
        $this->display();
    }

    public function apply_two () {
        $this->is_teacher();
        $this->_data['title'] = '填写个人简介';
        $this->assign($this->_data);
        $this->display();
    }

    public function apply_three () {
        $this->is_teacher();
        $this->_data['title'] = '上传头像和证书';
        $this->assign($this->_data);
        $this->display();
    }

    public function apply_four () {
        $this->is_teacher();
        $this->_data['title'] = '添加话题';
        $this->assign($this->_data);
        $this->display();
    }



    /**
     * 判断 type 是否为 teacher
     * 如果不是，则跳转到相应 type
     */
    private function is_teacher() {
        $type = $this->login_type();

        if ($type == 2) {

        } else if($type == 1) {
            $this->redirect('User/index', '', 0);
        } else if ($type == 3) {
            $this->redirect('Admin/index', '', 0);
        } else {
            logout();
            $this->redirect('Home/Login/index', '', 0);
        }
    }
}