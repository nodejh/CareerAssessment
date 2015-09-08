<?php
// +----------------------------------------------------------------------
// | 会员注册
// +----------------------------------------------------------------------

namespace Home\Controller;
use Think\Controller;

/**
 * Class SignController
 * @package Home\Controller
 */
class SignController extends BaseController {


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
     * 来访者会员注册
     */
    public function index(){
        $this->_data['title'] = '来访者会员注册';
        $card = I('post.card', 0);
        $password = I('post.password', 0);
        $phone = I('post.phone', 0);

        $this->_data['msg']['card'] = $card;
        $this->_data['msg']['password'] = $password;
        $this->_data['msg']['phone'] = $phone;

        if ($card && $password && $phone) {

            $Card = M('card');
            $where_card['card'] = ':card';
            $data_card = $Card->where($where_card)->bind(':card', $card)->find();
            if ($data_card) {

                // 判断会员卡是否注册过
                $Account = M('account');
                $where_account_card['card_id'] = ':card_id';
                $is_sign = $Account->where($where_account_card)->bind(':card_id', $data_card['card_id'])->find();

                if (!$is_sign) {
                    if ($data_card['type'] == 1) {

                        if (encrypt($password) == $data_card['password']) {
                            // correct card and password
                            $Account->phone = ':phone';
                            $Account->card_id = ':card_id';
                            $Account->type = ':type';
                            $Account->date = ':date';
                            $account_bind[':phone'] = $phone;
                            $account_bind[':card_id'] = $data_card['card_id'];
                            $account_bind[':type'] = 1;
                            $account_bind[':date'] = time();
                            $row_account = $Account->bind($account_bind)->add();
                            if ($row_account) {
                                login($row_account, 1);
                                $this->redirect('Complete/index', '', 0);
                            } else {
                                $this->_data['error'] = '注册失败，请重试';
                                $this->assign($this->_data);
                                $this->display();
                            }

                        } else {
                            $this->_data['error'] = '会员卡密码错误';
                            $this->assign($this->_data);
                            $this->display();
                        }

                    } elseif ($data_card['type'] == 2) {
                        $this->_data['error'] = '您的会员卡是咨询师/讲师会员卡，请点击<a href="'.U('teacher').'">咨询师/讲师会员注册</a>';
                        $this->assign($this->_data);
                        $this->display();
                    } else {
                        $this->_data['error'] = '会员卡无效';
                        $this->assign($this->_data);
                        $this->display();
                    }
                } else {
                    // card is signed
                    $this->_data['error'] = '您已注册，您可以直接<a href="'.U('Login/index').'">登录</a>';
                    $this->assign($this->_data);
                    $this->display();
                }

            } else {
                $this->_data['error'] = '会员卡不存在';
                $this->assign($this->_data);
                $this->display();
            }

        } else {
            $this->assign($this->_data);
            $this->display();
        }
    }


    /**
     * 咨询师/讲师会员注册
     */
    public function teacher() {
        $this->_data['title'] = '咨询师/讲师会员注册';
        $card = I('post.card', 0);
        $password = I('post.password', 0);
        $phone = I('post.phone', 0);

        $this->_data['msg']['card'] = $card;
        $this->_data['msg']['password'] = $password;
        $this->_data['msg']['phone'] = $phone;

        if ($card && $password && $phone) {

            $Card = M('card');
            $where_card['card'] = ':card';
            $data_card = $Card->where($where_card)->bind(':card', $card)->find();
            if ($data_card) {

                // 判断会员卡是否注册过
                $Account = M('account');
                $where_account_card['card_id'] = ':card_id';
                $is_sign = $Account->where($where_account_card)->bind(':card_id', $data_card['card_id'])->find();

                if (!$is_sign) {

                    // card not sign
                    if ($data_card['type'] == 2) {

                        if (encrypt($password) == $data_card['password']) {
                            // correct card and password
                            $Account->phone = ':phone';
                            $Account->card_id = ':card_id';
                            $Account->type = ':type';
                            $Account->date = ':date';
                            $account_bind[':phone'] = $phone;
                            $account_bind[':card_id'] = $data_card['card_id'];
                            $account_bind[':type'] = 2;
                            $account_bind[':date'] = time();
                            $row_account = $Account->bind($account_bind)->add();
                            if ($row_account) {
                                login($row_account, 2);
                                $this->redirect('Complete/teacher', '', 0);

                            } else {
                                $this->_data['error'] = '注册失败，请重试';
                                $this->assign($this->_data);
                                $this->display();
                            }

                        } else {
                            $this->_data['error'] = '会员卡密码错误';
                            $this->assign($this->_data);
                            $this->display();
                        }

                    } elseif ($data_card['type'] == 1){
                        $this->_data['error'] = '您的会员卡是咨询师/讲师会员卡，请点击<a href="'.U('index').'">来访者会员注册</a>';
                        $this->assign($this->_data);
                        $this->display();
                    } else {
                        $this->_data['error'] = '会员卡无效';
                        $this->assign($this->_data);
                        $this->display();
                    }

                } else {
                    // card is signed
                    $this->_data['error'] = '您已注册，您可以直接<a href="'.U('Login/index').'">登录</a>';
                    $this->assign($this->_data);
                    $this->display();
                }

            } else {
                $this->_data['error'] = '会员卡不存在';
                $this->assign($this->_data);
                $this->display();
            }


        } else {
            $this->assign($this->_data);
            $this->display();
        }
    }







}