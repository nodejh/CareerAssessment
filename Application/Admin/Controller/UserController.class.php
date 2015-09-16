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
        $this->is_user();

        $this->_data['title'] = '个人中心';
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
        $this->is_user();

         if ($_POST) {
             $post['name'] = I('post.name', 0);
             $post['gender'] = I('post.gender', 0);
             $post['email'] = I('post.email', 0);
             $post['city'] = I('post.city', 0) == '请选择您所在城市' ? 0 : I('post.city', 0);
             $post['status'] = I('post.status', 0) == 30 ? I('post.worktime', 0) : I('post.status', 0);
             $post['school'] = I('post.school', 0);
             $post['college'] = I('post.college', 0);
             $post['type_1'] = I('post.type_1', 0);
             $post['type_2'] = I('post.type_2', 0);
             $post['type_3'] = I('post.type_3', 0);
             $post['type_4'] = I('post.type_4', 0);

             if ($post['type_1'] || $post['type_2'] || $post['type_3'] || $post['type_4']) {
                 $post['student_type'] = '';
                 if ($post['type_1']) {
                     $post['student_type'] .= $post['type_1'] . ',';
                 }
                 if ($post['type_2']) {
                     $post['student_type'] .= $post['type_2'] . ',';
                 }
                 if ($post['type_3']) {
                     $post['student_type'] .= $post['type_3'] . ',';
                 }
                 if ($post['type_4']) {
                     $post['student_type'] .= $post['type_4'] . ',';
                 }
                 $post['student_type'] = rtrim($post['student_type'], ',');
             }

             $User = M('user');
             $user_data['name'] = $post['name'];

             if ($post['name']) {
                 $user_update['name'] = $post['name'];
             }
             if ($post['gender']) {
                 $user_update['gender'] = $post['gender'];
             }
             if ($post['email']) {
                 $user_update['email'] = $post['email'];
             }
             if ($post['city']) {
                 $user_update['city'] = $post['city'];
             }
             if ($post['status']) {
                 $user_update['status'] = $post['status'];
             }
             if ($post['school']) {
                 $user_update['school'] = $post['school'];
             }
             if ($post['college']) {
                 $user_update['college'] = $post['college'];
             }
             if ($post['student_type']) {
                 $user_update['student_type'] = $post['student_type'];
             }

             if (count($user_update )) {

                 //判断 user 表中是否有该用户
                 $user_where['account_id'] = $_SESSION['id'];

                 $user_exist = $User->where($user_where)->find();
                 
                 if ($user_exist) {
                     // user 表中有该用户
                     $user_result = $User->where($user_where)->data($user_update)->save();
                 } else {
                     // user 表中没有该用户
                     $user_data['account_id'] = $user_where['account_id'];
                     $user_result = $User->data($user_update)->add();
                 }

                 var_dump($user_result);

                 if ($user_result !== false) {
                     $this->_data['title'] = '修改个人资料';
                     $this->_data['message'] = '修改个人资料修成功,请<a href="'.U('profile').'">刷新</a>查看';
                     $this->assign($this->_data);
                     $this->display();

                 } else {
                     $this->_data['title'] = '修改个人资料';
                     $this->_data['error'] = '修改个人资料失败，请重试';
                     $this->assign($this->_data);
                     $this->display();
                 }

             } else {
                 $this->_data['title'] = '修改个人资料';
                 $this->_data['error'] = '没有任何更新，请重试';
                 $this->assign($this->_data);
                 $this->display();
             }



         } else {
             $this->_data['title'] = '修改个人资料';
             $this->assign($this->_data);
             $this->display();
         }
    }


    /**
     * 修改密码
     */
    public function password() {
        $this->is_user();

        $this->_data['title'] = '修改密码';
        if ($_POST) {
            if (isset($_POST['password']) && strlen($_POST['password']) > 5) {
                $post['password'] = I('post.password', 0);
                $post['password_confirm'] = I('password_confirm', 0);

                if ($post['password'] == $post['password_confirm']) {

                    $Account = M('account');
                    $account_where['account_id'] = $_SESSION['id'];
                    $account_data['password'] = $post['password'];
                    $account_result = $Account->where($account_where)->data($account_data)->save();

                    if ($account_result) {

                        $this->_data['message'] = '修改密码成功！';
                        unset($_SESSION['f']);
                        unset($this->_data['first']);
                        $this->assign($this->_data);
                        $this->display();

                    } else {
                        $this->_data['error'] = '修改失败，请重试';
                        $this->assign($this->_data);
                        $this->display();
                    }

                } else {
                    $this->_data['error'] = '两次密码不一致';
                    $this->assign($this->_data);
                    $this->display();
                }
            } else {
                $this->_data['error'] = '密码长度不能小于6位';
                $this->assign($this->_data);
                $this->display();
            }

        } else {
            $this->assign($this->_data);
            $this->display();
        }
    }


    /**
     * 判断是 type 否为 user
     * 如果不是，则跳转到相应 type
     */
    private function is_user() {
        $type = $this->login_type();

        if ($type == 1) {

        } else if($type == 2) {
            $this->redirect('Teacher/index', '', 0);
        } else if ($type == 3) {
            $this->redirect('Admin/index', '', 0);
        } else {
            logout();
            $this->redirect('Home/Login/index', '', 0);
        }
    }


}