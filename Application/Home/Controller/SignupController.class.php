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


    // 注册操作－手机号
    public function signup_phone() {
        $data['status']  = 1000;
        $data['message'] = '非法操作';
        if ($_POST && $_POST['type']) {
            $type = $_POST['type'];
            $phone = $_POST['phone'];
            $password = function_encrypt($_POST['password']);
            $password_confirm = function_encrypt($_POST['password_confirm']);

            if ($password == $password_confirm) {
                if ($type == 1) {
                    // 会员注册
                    $User = M('user');
                    $db_user['phone'] = '1';
                    $db_user['password'] = '1';
                    $db_result = $User->data($db_user)->add();
                    if ($db_result) {
                        $data['status'] = 0;
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
                        $data['message'] = 'success';
                    } else {
                        $data['status'] = 1001; // 写入数据库失败
                    }
                }
            }
        }
        $this->ajaxReturn($data);
    }


    // 注册操作－完善信息
    public function signup_info() {

    }
}