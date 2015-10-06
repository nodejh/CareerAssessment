<?php
// +----------------------------------------------------------------------
// | 登录
// +----------------------------------------------------------------------

namespace Home\Controller;
use Think\Controller;

/**
 * Class LoginController
 * @package Home\Controller
 */
class LoginController extends BaseController {


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
     * 登录页面
     */
    public function index(){

        $this->_data['title'] = '登录';
        $this->assign($this->_data);
        $this->display();

    }


    /**
     * card login
     */
    public function card() {

        $card = I('post.card', 0);
        $password = I('post.password', 0);

        $this->_data['msg']['card'] = $card;
        $this->_data['msg']['password'] = $password;

        if ($card && $password) {

            $Card = M('card');
            $where_card['card'] = ':card';
            $data_card = $Card->where($where_card)->bind(':card', $card)->find();
            if (encrypt($password) == $data_card['password']) {

                // 会员卡密码正确，查看是否已经将信息写入到 account 表。如果没有，则写入。
                $Account = M('account');
                $where_account['card_id'] = ':card_id';

                $data_account = $Account->where($where_account)->bind(':card_id', $data_card['card_id'])->find();

                if ($data_account) {
                    //account 表中已经写入该会员信息

                    login($data_account['account_id'], $data_card['type']);

                    switch ($data_account['type']) {
                        case 1:
                            $this->redirect('Admin/User/index', '', 0);
                            break;
                        case 2:
                            $this->redirect('Admin/Teacher/index', '', 0);
                    }

                } else {
                    //account 表中没有写入该会员信息

                    $data['card'] = $card;
                    $data['password'] = $password;
                    $data['type'] = $data_card['type'];
                    $data['register_time'] = time();
                    $insert_account = $Account->add($data);

                    if ($insert_account) {

                        login($insert_account, $data_card['type']);

                        switch ($data_card['type']) {
                            case 1:
                                $this->redirect('Admin/User/index', '', 0);
                                break;
                            case 2:
                                $this->redirect('Admin/Teacher/index', '', 0);
                        }

                    } else {
                        $this->_data['error'] = '登陆失败，请重试';
                        $this->_data['title'] = '登录';
                        $this->assign($this->_data);
                        $this->display('index');
                    }


                }

            } else {
                $this->_data['error'] = '会员卡卡号或密码错误';
                $this->_data['title'] = '登录';
                $this->assign($this->_data);
                $this->display('index');
            }

        } else {
            $this->_data['title'] = '登录';
            $this->assign($this->_data);
            $this->display('index');
        }
    }


    /**
     * phone login
     */
    public function phone() {
        $phone = I('post.phone', 0);
        $password = I('post.password', 0);

        $this->_data['msg']['phone'] = $phone;
        $this->_data['msg']['password'] = $password;

        if ($phone && $password) {

            $Account = M('account');
            $where_account['phone'] = ':phone';
            $data_account = $Account->where($where_account)->bind(':phone', $phone)->find();

            if ($data_account) {

                if (encrypt($password) == $data_account['password']) {
                    login($data_account['account_id'], $data_account['type']);

                    switch ($data_account['type']) {
                        case 1:
                            $this->redirect('Admin/User/index', '', 0);
                            break;
                        case 2:
                            $this->redirect('Admin/Teacher/index', '', 0);
                    }

                } else {
                    $this->_data['error'] = '用户号或密码错误';
                    $this->_data['phone'] = 'phone';
                    $this->_data['title'] = '登录';
                    $this->assign($this->_data);
                    $this->display('index');
                }

            } else {
                $this->_data['error'] = '用户号或密码错误';
                $this->_data['phone'] = 'phone';
                $this->_data['title'] = '登录';
                $this->assign($this->_data);
                $this->display('index');
            }

        } else {
            $this->_data['phone'] = 'phone'; // 用于前台判断是会员登录还是一般用户登录；如果 phone 被赋值，则为一般用户登录
            $this->_data['title'] = '登录';
            $this->assign($this->_data);
            $this->display('index');
        }
    }


    /**
     * sign type
     */
    public function type() {
        $this->_data['title'] = '请选择注册类型';

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
                logout();
                $this->assign($this->_data);
                $this->display();
            }

        } else {
            logout();
            $this->assign($this->_data);
            $this->display();
        }
    }


}