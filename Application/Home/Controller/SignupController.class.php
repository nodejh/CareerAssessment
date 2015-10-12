<?php
// +----------------------------------------------------------------------
// | 一般用户注册
// +----------------------------------------------------------------------

namespace Home\Controller;
use Think\Controller;

/**
 * Class SignupController
 * @package Home\Controller
 */
class SignupController extends BaseController {


    /**
     * 初始化注册页面
     * 如果已经登录，则跳转到相应个人中心
     */
    public function __construct() {
        parent::__construct();
        if ($this->is_login()) {
            $login_type = $this->login_type();
            if ($login_type) {

                switch ($login_type) {
                    case 1:
                        $this->redirect('Admin/User/index', '', 0);
                        break;
                    case 2:
                        $this->redirect('Admin/Teacher/index', '', 0);
                        break;
                    case 3:
                        $this->redirect('Admin/Index/index', '', 0);
                        break;
                }
            } else {
                session_unset();
            }
        } else {
            session_unset();
        }
    }


    /**
     * 一般来访者用户注册
     */
    public function index(){
        $this->_data['title'] = '一般来访者用户注册';
        $phone = I('post.phone', 0);
        $password = I('post.password', 0);
        $password_confirm = I('post.password_confirm', 0);

        $this->_data['msg']['phone'] = $phone ? $phone : '';

        if ($phone) {
            if ($password == $password_confirm) {
                if (reg_exp_phone($phone)) {
                    $Account = M('account');
                    $where_account['phone'] = ':phone';
                    $row_account = $Account->where($where_account)->bind(':phone', $phone)->find();

                    if (!$row_account) {
                        // 该手机号未注册
                        $Account->phone = ':phone';
                        $Account->password = ':password';
                        $Account->register_time = ':register_time';
                        $Account->type = ':type';
                        $account_bind[':phone'] = $phone;
                        $account_bind[':password'] = encrypt($password);
                        $account_bind[':type'] = 1;
                        $account_bind[':register_time'] = time();

                        $row_account = $Account->bind($account_bind)->add();

                        if ($row_account) {
                            $row_user = M('user')->data(array('account_id'=>$row_account))->add();
                            if ($row_user) {
                                login($row_account, 1);
                                $this->redirect('Admin/User/index', '', 0);
                            } else {
                                $this->_data['error'] = '注册失败，请重试';
                                $this->assign($this->_data);
                                $this->display();
                            }
                        } else {
                            $this->_data['error'] = '注册失败，请重试';
                            $this->assign($this->_data);
                            $this->display();
                        }

                    } else {
                        // 该手机号已注册
                        $this->_data['error'] = '该手机号已注册，您可以直接<a href="'.U('Login/index').'">登录</a>';
                        $this->assign($this->_data);
                        $this->display();
                    }

                } else {
                    $this->_data['error'] = '手机号格式错误';
                    $this->assign($this->_data);
                    $this->display();
                }
            } else {
                $this->_data['error'] = '两次密码不一致';
                $this->assign($this->_data);
                $this->display();
            }
        } else {
            $this->assign($this->_data);
            $this->display();
        }

    }


    /**
     * 一般咨询师/讲师注册
     */
    public function teacher(){
        $this->_data['title'] = '一般咨询师/讲师注册';
        $phone = I('post.phone', 0);
        $password = I('post.password', 0);
        $password_confirm = I('post.password_confirm', 0);

        $this->_data['msg']['phone'] = $phone ? $phone : 0;

        if ($phone) {
            if ($password == $password_confirm) {
                if (reg_exp_phone($phone)) {
                $Account = M('account');
                $where_account['phone'] = ':phone';
                $row_account = $Account->where($where_account)->bind(':phone', $phone)->find();

                    if (!$row_account) {
                        // 该手机号未注册
                        $Account->phone = ':phone';
                        $Account->password = ':password';
                        $Account->register_time = ':register_time';
                        $Account->type = ':type';
                        $account_bind[':phone'] = $phone;
                        $account_bind[':password'] = encrypt($password);
                        $account_bind[':type'] = 2;
                        $account_bind[':register_time'] = time();

                        $row_account = $Account->bind($account_bind)->add();

                        if ($row_account) {
                            $row_teacher = M('teacher')->data(array('account_id'=>$row_account))->add();
                            if ($row_teacher) {
                                login($row_account, 2);
                                $this->redirect('Admin/Teacher/index', '', 0);
                            } else {
                                $this->_data['error'] = '注册失败，请重试';
                                $this->assign($this->_data);
                                $this->display();
                            }

                        } else {
                            $this->_data['error'] = '注册失败，请重试';
                            $this->assign($this->_data);
                            $this->display();
                        }


                    } else {
                        // 该手机号已注册
                        $this->_data['error'] = '该手机号已注册，您可以直接<a href="'.U('Login/index').'">登录</a>';
                        $this->assign($this->_data);
                        $this->display();
                    }

                } else {
                    $this->_data['error'] = '手机号格式错误';
                    $this->assign($this->_data);
                    $this->display();
                }
            } else {
                $this->_data['error'] = '两次密码不一致';
                $this->assign($this->_data);
                $this->display();
            }

        } else {
            $this->assign($this->_data);
            $this->display();
        }

    }

}