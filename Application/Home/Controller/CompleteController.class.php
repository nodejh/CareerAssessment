<?php
// +----------------------------------------------------------------------
// | 完善用户信息。会员与非会员的界面一样
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

        if ($this->login_type() == 1) {
            $this->_data['title'] = '完善会员信息';

            if (! $this->_data['user']) {
                // 没有完善过信息

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

            } else {
                // user is compelte
                $this->redirect('Admin/User/index', '', 0);
            }
        } else if($this->login_type() == 2) {

            $this->redirect('teacher', '', 0);

        } else {
            $this->redirect('Home/Index/index', '', 0);
        }

    }


    /**
     * 完善咨询师/讲师会员信息
     * 姓名，
     */
    public function teacher() {

        if ($this->login_type() == 2) {

            $this->_data['title'] = '完善会员信息';

            if (! $this->_data['teacher']) {
                // 没有完善过信息

                if ($_POST) {

                    $post['name'] = I('post.name', null);
                    $post['gender'] = I('post.gender', null);
                    $post['email'] = I('post.email', null);
                    $post['city'] = I('post.city', null);
                    $post['service_type'] = '';
                    $post_temp['type_1'] = I('post.type_1', null);
                    $post_temp['type_2'] = I('post.type_2', null);
                    $post_temp['type_3'] = I('post.type_3', null);
                    if ($post_temp['type_1']) $post['service_type'] .= $post_temp['type_1'] . ',';
                    if ($post_temp['type_2']) $post['service_type'] .= $post_temp['type_2'] . ',';
                    if ($post_temp['type_3']) $post['service_type'] .= $post_temp['type_3'] . ',';
                    if ($post_temp['type_4']) $post['service_type'] .= $post_temp['type_4'] . ',';
                    if ($post['service_type']) $post['service_type'] = rtrim($post['service_type'], ',');

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
                    if (!$post['service_type']) {
                        $this->_data['error'] = '至少选择一项服务类型';
                        $this->assign($this->_data);
                        $this->display();
                        exit();
                    }
                    if (!$_FILES) {
                        $this->_data['error'] = '请上传您的个人照片';
                        $this->assign($this->_data);
                        $this->display();
                        exit();
                    }

                    $upload = new \Think\Upload();
                    $upload->maxSize = 3145728 ;// 设置附件上传大小
                    $upload->exts = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
                    $upload->rootPath = './Uploads/'; // 设置附件上传根目录
                    $upload->savePath = 'avatar/';

                    $info = $upload->uploadOne($_FILES['avatar']);
                    if (!$info) {

                        $this->_data['error'] = $upload->getError();
                        $this->assign($this->_data);
                        $this->display();

                    } else {

                        $post['avatar'] = $info['savepath'].$info['savename'];

                        // 将信息写入数据库
                        $Teacher = M('teacher');
                        $Teacher->account_id = ':account_id';
                        $Teacher->name = ':name';
                        $Teacher->gender = ':gender';
                        $Teacher->email = ':email';
                        $Teacher->city = ':city';
                        $Teacher->service_type = ':service_type';
                        $Teacher->avatar = ':avatar';
                        $teacher_bind[':account_id'] = $_SESSION['id'];
                        $teacher_bind[':name'] = $post['name'];
                        $teacher_bind[':email'] = $post['email'];
                        $teacher_bind[':gender'] = $post['gender'];
                        $teacher_bind[':city'] = $post['city'];
                        $teacher_bind[':service_type'] = $post['service_type'];
                        $teacher_bind[':avatar'] = $post['avatar'];

                        $row_teacher = $Teacher->bind($teacher_bind)->add();
                        if ($row_teacher) {
                            $this->redirect('teacher3', '', 0);

                        } else {
                            $this->_data['error'] = '添加失败，请重试';
                            $this->assign($this->_data);
                            $this->display();
                        }

                    }

                } else {
                    $this->assign($this->_data);
                    $this->display();
                }

            } else {
                $this->redirect('Admin/Teacher/index', '', 0);
            }


        } else if ($this->login_type() == 1) {
            $this->redirect('index', '', 0);

        } else {
            $this->redirect('Home/Index/index', '', 0);
        }

    }


    /**
     * 咨询师信息
     */
    public function teacher3() {

        $this->_data['title'] = '咨询师信息';

        if ($this->login_type() == 2) {

            //var_dump($_POST);
            if ($_POST) {
                $post['introduction'] = I('post.introduction', null);
                $post['c_a'] = I('c_a', null);
                $post['c_b'] = I('c_b', null);
                $post['time_a'] = I('post.time_a', null) == '心理咨询师' ? null : I('post.time_a', null);
                $post['time_b'] = I('post.time_b', null) == '职业生涯规划师' ? null : I('post.time_b', null);

                if ($post['c_a']) {
                    if (!($post['time_a'] && $_FILES['img_a'])) {
                        $this->_data['error'] = '请选择心理咨询师从业时间，并请上传心理咨询师证书';
                        $this->_data['msg']['a'] = '1';
                        $this->assign($this->_data);
                        $this->display();
                        exit();
                    }
                }
                if ($post['c_b']) {
                    if (!($post['time_b'] && $_FILES['img_b'])) {
                        $this->_data['error'] = '请选择职业生涯规划师从业时间，并请上传职业生涯规划师证书';
                        $this->_data['msg']['b'] = '1';
                        $this->assign($this->_data);
                        $this->display();
                        exit();
                    }
                }
                $upload = new \Think\Upload();
                $upload->maxSize = 3145728 ;
                $upload->exts = array('jpg', 'gif', 'png', 'jpeg');
                $upload->rootPath = './Uploads/';
                $upload->savePath = 'certificate/';

                $info = $upload->upload();
                if((!$info) && ($upload->getError() != '没有文件被上传！')) {
                    $this->_data['error'] = $upload->getError();
                    $this->assign($this->_data);
                    $this->display();

                } else {

                    if ($info['img_a']) {
                        $post['certificate_a'] = $info['img_a']['savepath'].$info['img_a']['savename'];
                    }
                    if ($info['img_b']) {
                        $post['certificate_b'] = $info['img_b']['savepath'].$info['img_b']['savename'];
                    }

                    var_dump($post);
                    echo PHP.EOL;
//                    die();
                    if ($post['introduction'] || $post['certificate_a'] || $post['certificate_b'] || $post['time_a'] || $post['time_b']) {
                        // 有更新的数据
                        $Teacher = M('teacher');
                        if ($post['introduction']) {
                            $teacher_data['introduction'] = $post['introduction'];
                        }
                        if ($post['certificate_a']) {
                            $teacher_data['certificate_a'] = $post['certificate_a'];
                        }
                        if ($post['time_a']) {
                            $teacher_data['time_a'] = $post['time_a'];
                        }
                        if ($post['certificate_b']) {
                            $teacher_data['certificate_b'] = $post['certificate_b'];
                        }
                        if ($post['time_b']) {
                            $teacher_data['time_b'] = $post['time_b'];
                        }

                        $where['account_id'] = $_SESSION['id'];
                        $row_teacher = $Teacher->where($where)->data($teacher_data)->save();

                        if ($row_teacher) {

                            $this->redirect('teacher4', '', 0);

                        } else {
                            $this->_data['error'] = '添加失败，请重试';
                            $this->assign($this->_data);
                            $this->display();
                        }

                    } else {
                        // 没有更新的数据，直接跳转
                        $this->redirect('teacher4', '', 0);

                    }

                }

            } else {
                $this->assign($this->_data);
                $this->display();
            }

        } else if ($this->login_type() == 1) {
            $this->redirect('index', '', 0);

        } else {
            $this->redirect('Home/Index/index', '', 0);

        }
    }


    /**
     * 讲师信息
     */
    public function teacher4() {

        $this->_data['title'] = '讲师信息';

        if ($this->login_type() == 2) {

            if ($_POST) {
                $this->redirect('Admin/Teacher/index', '', 0);

            } else {

                $this->assign($this->_data);
                $this->display();
            }

        } else if ($this->login_type() == 1) {
            $this->redirect('index', '', 0);

        } else {
            $this->redirect('Home/Index/index', '', 0);

        }
    }


}