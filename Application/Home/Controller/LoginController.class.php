<?php
namespace Home\Controller;
use Think\Controller;
class LoginController extends Controller {

    // 登录首页
    public function index() {
        $data['title'] = '登录－蓝鲸教育咨询';
        $this->assign($data);
        $this->display();
    }


    // 完善来访者信息
    public function user() {
        $data['title'] = '完善基本信息';
        $this->assign($data);
        $this->display();
    }


    // 完善咨询师信息
    public function teacher() {
        $data['title'] = '完善基本信息';
        $this->assign($data);
        $this->display();
    }


    // 会员卡登录
    public function login_card() {
        $data['status'] = 1000;
        $data['message'] = '非法操作';
        if ($_POST) {
            $number = $_POST['number'];
            $password = $_POST['password'];

            $user_first_number = function_user_number();
            $teacher_first_number = function_teacher_number();

            $data['status'] = 1001;
            $data['message'] = '会员卡卡号或密码错误';

            if (reg_exp_number($number) && reg_exp_password($password)) {
                $first_number = substr($number, 0 ,1);
                if($first_number == $user_first_number) {
                    // 来访者
                    $Ucard = M('ucard');
                    $result_ucard = $Ucard->where('number=' . $number)->find();

                    if ($result_ucard && $result_ucard['password']) {
                        if (function_encrypt($password) == $result_ucard['password']) {
                            // 会员卡卡号密码正确
                            $data['status'] = 1;
                            $data['message'] = '尚未完善基本信息';
                            $data['url'] = U('Login/user');
                            $Account = M('account');
//                            $card_id = $result_ucard['ucard_id'];
                            $where['card_id'] = $result_ucard['ucard_id'];
                            $where['type'] = $user_first_number;
                            $result_account = $Account->where($where)->find();
                            if ($result_account) {
                                function_set_login_in($result_account['account_id'], $user_first_number);
                                $data['status'] = 0;
                                $data['message'] = '登录成功';
                                $data['url'] = U('Admin/User/index');
                            }
                        }
                    }

                } elseif($first_number == $teacher_first_number) {
                   // 咨询师
                    $Tcard = M('tcard');
                    $result_tcard = $Tcard->where('number=' . $number)->find();

                    if ($result_tcard && $result_tcard['password']) {
                        if (function_encrypt($password) == $result_tcard['password']) {
                            // 会员卡卡号密码正确
                            $data['status'] = 1001;
                            $data['message'] = '尚未完善基本信息';
                            $data['url'] = U('Login/teacher');
                            $Account = M('account');
                            $result_account = $Account->where('card_id=' . $result_tcard['tcard_id'] . 'AND type=' . $teacher_first_number)->find();
                            if ($result_account) {
                                function_set_login_in($result_account['account_id'], $teacher_first_number);
                                $data['status'] = 0;
                                $data['message'] = '登录成功';
                                $data['url'] = U('Admin/Teacher/index');
                            }
                        }
                    }
                }
            }
        }

        $this->ajaxReturn($data);
    }

    // 手机号登录
    public function login_phone() {

    }

}