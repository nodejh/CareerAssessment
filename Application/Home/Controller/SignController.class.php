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
     * 来访者会员注册
     */
    public function index(){
        $this->_data['title'] = '登录';

        if ($this->is_login()) {
            $login_type = $this->login_type();
            if ($login_type) {

                switch ($login_type) {
                    case 1:
                        $this->redirect('User/index', '', 0);
                        break;
                    case 2:
                        $this->redirect('Teacher/index', '', 0);
                        break;
                    case 3:
                        $this->redirect('Index/index', '', 0);
                        break;
                }
            } else {
                logout();
                $this->display('index');
            }

        } else {
            logout();
            $this->assign($this->_data);
            $this->display();
        }
    }


    /**
     * 咨询师/讲师会员注册
     */
    public function tindex() {

    }


    /**
     * card login
     */
    public function card() {

        $card = I('post.card', 0);
        $password = I('post.password', 0);
        if ($card && $password) {

            $Card = M('card');
            $where_card['card'] = ':card';
            $data_card = $Card->where($where_card)->bind(':card', $card)->find();
            if (encrypt($password) == $data_card['password']) {

                $Account = M('account');
                $where_account['card_id'] = ':card_id';
                $data_account = $Account->where($where_account)->bind(':card_id', $data_card['card_id'])->find();
                if ($data_account) {

                } else {
                    $this->_data['error'] = '您尚未注册，请<a href="'.U('Sign/index').'">注册</a>后登录';
                    $this->_data['title'] = '登录';
                    $this->display('index');
                }

            } else {
                $this->_data['error'] = '会员卡卡号或密码错误';
                $this->_data['title'] = '登录';
                $this->display('index');
            }

        } else {
            $this->_data['title'] = '登录';
            $this->display('index');
        }
    }


    /**
     * phone login
     */
    public function phone() {
        echo 'd';
    }
}