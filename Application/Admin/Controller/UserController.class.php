<?php
namespace Admin\Controller;
use Think\Controller;
class UserController extends Controller {

//
//    public function _before_index() {
//        $uid = function_is_login();
//
//        if ($uid) {
//            $Account = M('account');
//            $where['account_id'] = $uid;
//            $account_info = $Account->where($where)->find();
//            var_dump($account_info);
//            die();
//        }
//    }


    // 来访者个人中心
    public function index(){
        $uid = function_is_login();

        if ($uid) {
            $Userinfo = M('userinfo');
            $where['account_id'] = $uid;
            $exit_userinfo = $Userinfo->where($where)->find();

            if ($exit_userinfo) {

                $data['title'] = '个人中心';
                $data['teacherinfo'] = $exit_userinfo;
                $Account = M('account');
                $account_info = $Account->where($where)->find();
                if ($account_info['password'] == null) {
                    $data['password'] = get_default_password();
                    $data['phone'] = $account_info['phone'];
                    $update_data['password'] = $data['password'];
                    $update_result = $Account->where($where)->data($update_data)->save();
                    if (!$update_result) {
                        $this->error('设置默认密码失败，请手动设置登录密码', '/User/password');
                    }
                }
                $this->assign($data);
                $this->display();

            } else {
                $this->redirect('Home/Signup/user', '', 0);
            }

        } else {
            function_set_logout();
            $this->redirect('Home/Login/index', '', 0);
        }
    }


    public function _before_password(){
        $uid = function_is_login();

        if ($uid) {
            $Userinfo = M('userinfo');
            $where['account_id'] = $uid;
            $exit_userinfo = $Userinfo->where($where)->find();

            if ($exit_userinfo) {

            } else {
                $this->redirect('Home/Sign/user', '', 0);
            }

        } else {
            function_set_logout();
            $this->redirect('Home/Login/index', '', 0);
        }
    }


    // 修改密码
    public function password() {
        $data['title'] = '修改密码';
        $this->assign($data);
        $this->display();
    }
}