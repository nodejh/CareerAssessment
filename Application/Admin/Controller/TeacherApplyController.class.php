<?php
// +----------------------------------------------------------------------
// | Teacher后台 完善教师信息
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
        $Teacher = M('teacher');
        $where['account_id'] = $_SESSION['id'];
        $field[0] = 'name';
        $field[1] = 'gender';
        $field[2] = 'email';
        $field[3] = 'occupation';
        $field[4] = 'city';
        $field[5] = 'service_type';
        $field[6] = 'service_object';
        $field[7] = 'price';
        $field[8] = 'appoint_location';
        $this->_data['teacher'] = $Teacher->field($field)->where($where)->find();
        if (IS_POST) {
            var_dump($_POST);
            $post_data['name'] = $_POST['name']; 
            $post_data['gender'] = $_POST['gender']; 
            $post_data['email'] = $_POST['email']; 
            $post_data['occupation'] = $_POST['occupation']; 
            $post_data['city'] = $_POST['city']; 
            $post_data['service_type'] = $_POST['service_type']; 
            $post_data['service_object'] = $_POST['service_object']; 
            $post_data['price'] = $_POST['price']; 
            $post_data['appoint_location'] = $_POST['appoint_location']; 
            $post_data['service_object'] = $_POST['service_object']; 
            $update = $Teacher->where($where)->data($post_data)->save();
            if ($update) {
                $this->redirect('apply_two', '', 0);
                exit();
            } else {
                $this->_data['error'] = '更新资料失败，请重试';
            }
        } 
        $this->assign($this->_data);
        $this->display();


    }

    public function apply_two () {
        $this->is_teacher();
        $this->_data['title'] = '填写个人简介';
        $Teacher = M('teacher');
        $where['account_id'] = $_SESSION['id'];
        $field[0] = 'introduction';
        $this->_data['teacher'] = $Teacher->field($field)->where($where)->find();
        if (IS_POST) {
            var_dump($_POST);
            $post_data['introduction'] = $_POST['introduction']; 
            $update = $Teacher->where($where)->data($post_data)->save();
            if ($update) {
                $this->redirect('apply_three', '', 0);
                exit();
            } else {
                $this->_data['error'] = '更新资料失败，请重试';
            }
        }
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