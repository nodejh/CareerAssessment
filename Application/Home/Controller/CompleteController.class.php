<?php
// +----------------------------------------------------------------------
// | 完善会员信息
// +----------------------------------------------------------------------

namespace Home\Controller;
use Think\Controller;

/**
 * Class CompleteController
 * @package Home\Controller
 */
class CompleteController extends BaseController {


    /**
     * 初始化完善来访者信息页面
     * 1. 如果没有登录，则跳转到登录页面
     * 2. 如果用户已经完善过信息，则跳转到个人中心
     */
    public function __construct() {
        parent::__construct();
        if (! ($this->is_login() && $this->login_type()) ) {
            // not login
            $this->redirect('Login/index', '', 0);
        }
    }


    /**
     * 完善来访者会员信息
     */
    public function index() {
        $this->_data['title'] = '完善会员信息';

        if ($this->_data['user']) {
            // user is compelte
            $this->redirect('Admin/User/index', '', 0);

        } else {

            if ($_POST) {

                $post['name'] = I('post.name', null);
                $post['gender'] = I('post.gender', null);
                $post['email'] = I('post.email', null);
                $post['city'] = I('post.city', null);
                $post['status'] = I('post.status', null) == 30 ? I('post.worktime', null) : I('post.status', null);
                $post['school'] = I('post.school', null);
                $post['college'] = I('post.college', null);
                $post['student_type'] = '';
                $post_temp['type_1'] = I('post.type_1', null);
                $post_temp['type_2'] = I('post.type_2', null);
                $post_temp['type_3'] = I('post.type_3', null);
                $post_temp['type_4'] = I('post.type_4', null);
                if ($post_temp['type_1']) $post['student_type'] .= $post_temp['type_1'] . ',';
                if ($post_temp['type_2']) $post['student_type'] .= $post_temp['type_2'] . ',';
                if ($post_temp['type_3']) $post['student_type'] .= $post_temp['type_3'] . ',';
                if ($post_temp['type_4']) $post['student_type'] .= $post_temp['type_4'] . ',';
                if ($post['student_type']) $post['student_type'] = rtrim($post['student_type'], ',');

                $this->_data['msg']['name'] = $post['name'];
                $this->_data['msg']['gender'] = $post['gender'] == 2 ? 2 : 1;
                $this->_data['msg']['city'] = $post['city'];
                $this->_data['msg']['email'] = $post['email'];

                if (!$post['name']) {
                    $this->_data['error'] = '请填写您的姓名';
                    $this->assign($this->_data);
                    $this->display();
                    exit();
                }
                if (!$post['gender']) {
                    $this->_data['error'] = '请选择您的性别';
                    $this->assign($this->_data);
                    $this->display();
                    exit();
                }
                if (! ($post['email'] && reg_exp_email($post['email'])) ) {
                    $this->_data['error'] = '邮箱格式错误';
                    $this->assign($this->_data);
                    $this->display();
                    exit();
                }
                if (!$post['city']) {
                    $this->_data['error'] = '请选择您的城市';
                    $this->assign($this->_data);
                    $this->display();
                    exit();
                }
                if (!$post['status']) {
                    $this->_data['error'] = '请选择您的当前学习或状态';
                    $this->assign($this->_data);
                    $this->display();
                    exit();
                }

                // 未完善过个人信息
                $User = M('user');
                $User->account_id = ':account_id';
                $User->name = ':name';
                $User->gender = ':gender';
                $User->email = ':email';
                $User->city = ':city';
                $User->status = ':status';
                $User->school = ':school';
                $User->college = ':college';
                $User->student_type = ':student_type';
                $user_bind[':account_id'] = $_SESSION['id'];
                $user_bind[':name'] = $post['name'];
                $user_bind[':gender'] = $post['gender'];
                $user_bind[':email'] = $post['email'];
                $user_bind[':city'] = $post['city'];
                $user_bind[':status'] = $post['status'];
                $user_bind[':school'] = $post['school'];
                $user_bind[':college'] = $post['college'];
                $user_bind[':student_type'] = $post['student_type'];
                $row_user = $User->bind($user_bind)->add();

                if ($row_user) {
                    // 添加成功
                    $this->redirect('Admin/User/index', '', 0);

                } else {
                    $this->_data['error'] = '添加失败，请重试';
                    $this->assign($this->_data);
                    $this->display();
                }

            } else {
                $this->assign($this->_data);
                $this->display();
            }
        }

    }


    /**
     * 完善咨询师/讲师会员信息
     */
    public function teacher() {
        $this->_data['title'] = '完善会员信息';
        var_dump($_SESSION);
        var_dump($this->_data);
        $this->assign($this->_data);
        $this->display();
    }
}