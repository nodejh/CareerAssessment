<?php
namespace Home\Controller;
use Think\Controller;
class SignupController extends Controller {


    // 注册页面
    public function index(){
        if (!function_is_login()) {
            $data['title'] = '注册－蓝鲸教育咨询';
            $this->assign($data);
            $this->display();
        } else if (function_login_type() == function_user_number()) {
            $this->redirect('Admin/User/index', '', 0);
        } else if (function_login_type() == function_teacher_number()) {
            $this->redirect('Admin/Teacher/index', '', 0);
        } else {
            function_set_logout();
            $data['title'] = '注册－蓝鲸教育咨询';
            $this->assign($data);
            $this->display();
        }
    }

    // 完善来访者信息页面
    public function user() {
        $uid = function_is_login();
        if ($uid) {
            $Userinfo = M('userinfo');
            $where['accout_id'] = $uid;
            $exit_userinfo = $Userinfo->where($where)->find();
            if (!$exit_userinfo) {
                $data['title'] = '完善个人信息';
                $this->assign($data);
                $this->display();
            } else {
                $this->redirect('Admin/User/index', '', 0);
            }
        } else {
            $this->redirect('Login/index', '', 0);
        }
    }


    // 完善咨询师信息页面
    public function teacher() {
        $uid = function_is_login();
        if ($uid) {
            $Teacherinfo = M('teacherinfo');
            $where['accout_id'] = $uid;
            $exit_teacherinfo = $Userinfo->where($where)->find();
            if (!$exit_teacherinfo) {
                $data['title'] = '完善个人信息';
                $this->assign($data);
                $this->display();
            } else {
                $this->redirect('Admin/Teacher/index', '', 0);
            }
        } else {
            $this->redirect('Login/index', '', 0);
        }
    }


    // 来访者注册
    public function signup_user() {
        $data['status']  = 1000;
        $data['message'] = '非法操作';

        if ($_POST) {
            $phone = $_POST['phone'];
            $password = $_POST['password'];
            $password_confirm = $_POST['password_confirm'];
            $data['status']  = 2001;
            $data['message'] = '手机号格式错误';
            if (reg_exp_phone($phone)) {
                $data['status']  = 2002;
                $data['message'] = '密码格式错误';
                if (reg_exp_password($password)) {
                    $data['status']  = 2003;
                    $data['message'] = '两次密码不一致';
                    if ($password == $password_confirm) {
                        $Account = M('account');
                        $where_exit_phone['phone'] = $phone;
                        $exit_phone = $Account->where($where_exit_phone)->find();
                        $data['status']  = 2004;
                        $data['message'] = '手机号已存在';
                        if (!$exit_phone) {
                            $insert_data['phone'] = $phone;
                            $insert_data['password'] = function_encrypt($password);
                            $insert_data['type'] = function_user_number();
                            $insert_data['date'] = time();
                            $insert_account = $Account->data($insert_data)->add();
                            $data['status']  = 2005;
                            $data['message'] = '注册失败';
                            if ($insert_account) {
                                $data['status']  = 0;
                                $data['message'] = '注册成功';
                                $data['url'] = U('Signup/user');
                                function_set_login_in($insert_account, function_user_number());

                            }
                        }
                    }
                }
            }
        }
        $this->ajaxReturn($data);
    }


    // 咨询师注册
    public function signup_teacher() {
        $data['status']  = 1000;
        $data['message'] = '非法操作';
        if ($_POST) {
            $phone = $_POST['phone'];
            $password = $_POST['password'];
            $password_confirm = $_POST['password_confirm'];
            $data['status']  = 2001;
            $data['message'] = '手机号格式错误';
            if (reg_exp_phone($phone)) {
                $data['status']  = 2002;
                $data['message'] = '密码格式错误';
                if (reg_exp_password($password)) {
                    $data['status']  = 2003;
                    $data['message'] = '两次密码不一致';
                    if ($password == $password_confirm) {
                        $Account = M('account');
                        $where_exit_phone['phone'] = $phone;
                        $exit_phone = $Account->where($where_exit_phone)->find();
                        $data['status']  = 2004;
                        $data['message'] = '手机号已存在';
                        if (!$exit_phone) {
                            $insert_data['phone'] = $phone;
                            $insert_data['password'] = function_encrypt($password);
                            $insert_data['type'] = function_teacher_number();
                            $insert_data['date'] = time();
                            $insert_account = $Account->data($insert_data)->add();
                            $data['status']  = 2005;
                            $data['message'] = '注册失败';
                            if ($insert_account) {
                                $data['status']  = 0;
                                $data['message'] = '注册成功';
                                $data['url'] = U('Signup/teacher');
                                function_set_login_in($insert_account, function_teacher_number());
                            }
                        }
                    }
                }
            }
        }
        $this->ajaxReturn($data);
    }



    // 注册操作－完善会员信息
    public function signup_userinfo() {
        $data['status']  = 1000;
        $data['message'] = '非法操作';
        if ($_POST) {
            $data['status'] = 0;
            $data['url'] = U('Signup/card');
        }

        $this->ajaxReturn($data);
    }

    // 注册操作－完善咨询师信息
    public function signup_teacherinfo() {
        $data['status']  = 1000;
        $data['message'] = '非法操作';
        if ($_POST) {
            $data['status'] = 0;
            $data['url'] = U('Signup/add_card');
        }

        $this->ajaxReturn($data);
    }




}