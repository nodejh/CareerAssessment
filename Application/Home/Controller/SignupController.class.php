<?php
namespace Home\Controller;
use Think\Controller;
class SignupController extends Controller {


    // 注册页面
    public function index(){
        $data = [];
        $data['active_signup'] = 'active';

        $this->assign($data);
        $this->display();
    }

    // 完善会员信息页面
    public function user() {
        $this->display();
    }


    //完善咨询师信息页面
    public function teacher() {
        $this->display();
    }

    // 添加会员卡页码
    public function card() {
        $this->display();
    }


    // 注册操作－手机号
    public function signup_phone() {
        $data['status']  = 1000;
        $data['message'] = '非法操作';
        if ($_POST && $_POST['type']) {
            $type = $_POST['type'];
            $phone = $_POST['phone'];
            $password = $_POST['password'];
            $password_confirm = $_POST['password_confirm'];
            // TODO 数据验证
            if ($password != '' && $password_confirm != '' && $password == $password_confirm) {
                $password = function_encrypt($password);
                if ($type == 1) {
                    // 会员注册
                    $User = M('user');
                    $db_user['phone'] = $phone;
                    $db_user['password'] = $password;
                    $db_result = $User->data($db_user)->add();
                    if ($db_result) {
                        function_set_login_in($db_result, 1);
                        $data['status'] = 0;
                        $data['url'] = U('Signup/user');
                        $data['message'] = 'success';
                    } else {
                        $data['status'] = 1001; // 写入数据库失败
                    }
                } elseif ($type == 2) {
                    // 咨询师注册
                    $Teacher = M('Teacher');
                    $db_teacher['phone'] = $phone;
                    $db_teacher['password'] = $password;
                    $db_result = $Teacher->add($db_teacher);
                    if ($db_result) {
                        $data['status'] = 0;
                        $data['url'] = U('Signup/teacher');
                        $data['message'] = 'success';
                    } else {
                        $data['status'] = 1001; // 写入数据库失败
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


    // 添加会员卡操作
    public function add_card() {

    }


}