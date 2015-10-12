<?php
// +----------------------------------------------------------------------
// | User 后台
// +----------------------------------------------------------------------

namespace Admin\Controller;
use Think\Controller;

/**
 * Class IndexController
 * @package Admin\Controller
 */
class UserController extends BaseController {

    /**
     * User 个人中心
     */
    public function index(){
        $this->is_user();

        $this->_data['title'] = '个人中心';
        $Teacher = M('teacher');
        $list = $Teacher->order('teacher_id desc')->limit(16)->select();
        $this->_data['list'] = $list;

        $this->assign($this->_data);
        $this->display();
    }



    /**
     * 修改个人资料
     */
    public function profile() {
        $this->is_user();

        //var_dump($this->_data['user']);
        //exit();

         if ($_POST) {
             //var_dump($_POST);
             //die();

             $post['name'] = I('post.name', 0);
             $post['gender'] = I('post.gender', 0);
             $post['email'] = I('post.email', 0);
             $post['city'] = I('post.city', 0) == '请选择您所在城市' ? 0 : I('post.city', 0);
             $post['school_primary'] = I('post.school_primary', 0);
             $post['school_junior'] = I('post.school_junior', 0);
             $post['school_senior'] = I('post.school_senior', 0);
             $post['school_university'] = I('post.school_university', 0);
             $post['college'] = I('post.college', 0);
             $post['test_status'] = I('post.test_status', 0);
             $post['status'] = I('post.status', 0);

             if ($post['type_1'] || $post['type_2'] || $post['type_3'] || $post['type_4']) {
                 $post['student_type'] = '';
                 if ($post['type_1']) {
                     $post['student_type'] .= $post['type_1'] . ',';
                 }
                 if ($post['type_2']) {
                     $post['student_type'] .= $post['type_2'] . ',';
                 }
                 if ($post['type_3']) {
                     $post['student_type'] .= $post['type_3'] . ',';
                 }
                 if ($post['type_4']) {
                     $post['student_type'] .= $post['type_4'] . ',';
                 }
                 $post['student_type'] = rtrim($post['student_type'], ',');
             }

             $User = M('user');
             $user_data['name'] = $post['name'];

             if ($post['name']) {
                 $user_update['name'] = $post['name'];
             }
             if ($post['gender']) {
                 $user_update['gender'] = $post['gender'];
             }
             if ($post['email']) {
                 $user_update['email'] = $post['email'];
             }
             if ($post['city']) {
                 $user_update['city'] = $post['city'];
             }
             if ($post['school']) {
                 $user_update['school'] = $post['school'];
             }
             if ($post['college']) {
                 $user_update['college'] = $post['college'];
             }
             if ($post['student_type']) {
                 $user_update['student_type'] = $post['student_type'];
             }

             switch ($post['test_status']) {
                 case '小学':
                     $user_update['status'] = '小学';
                     $user_update['school'] = $post['school_primary'];
                     if ($user_update['college']) {
                         $user_update['college'] = null;
                     }
                     if ($user_update['student_type']) {
                         $user_data['student_type'] = null;
                     }
                     break;
                 case '初中':
                     $user_update['status'] = '初中';
                     $user_update['school'] = $post['school_junior'];
                     if ($user_update['college']) {
                         $user_update['college'] = null;
                     }
                     if ($user_update['student_type']) {
                         $user_data['student_type'] = null;
                     }
                     break;
                 case '高中':
                     $user_update['status'] = '高中';
                     $user_update['school'] = $post['school_senior'];
                     if ($user_update['college']) {
                         $user_update['college'] = null;
                     }
                     break;
                 case '大学及以上':
                     $user_update['status'] = '大学及以上';
                     $user_update['school'] = $post['school_university'];
                     break;
                 case '工作':
                     $user_update['status'] = $post['status'];
                     if ($user_update['school']) {
                         $user_update['school'] = null;
                     }
                     if ($user_update['college']) {
                         $user_update['college'] = null;
                     }
                     if ($user_update['student_type']) {
                         $user_update['student_type'] = null;
                     }
                     break;
                 default:

             }


             if (count($user_update)) {

                 //判断 user 表中是否有该用户
                 $user_where['account_id'] = $_SESSION['id'];

                 $user_exist = $User->where($user_where)->find();

                    var_dump($user_update);

                 if ($user_exist) {
                     // user 表中有该用户
                     $user_result = $User->where($user_where)->data($user_update)->save();
                 } else {
                     // user 表中没有该用户
                     $user_update['account_id'] = $user_where['account_id'];
                     $user_result = $User->data($user_update)->add();
                 }

                 if ($user_result !== false) {
                     $this->_data['title'] = '修改个人资料';
                     $this->_data['message'] = '修改个人资料修成功,请<a href="'.U('profile').'">刷新</a>查看';
                     $this->assign($this->_data);
                     $this->display();

                 } else {
                     $this->_data['title'] = '修改个人资料';
                     $this->_data['error'] = '修改个人资料失败，请重试';
                     $this->assign($this->_data);
                     $this->display();
                 }

             } else {
                 $this->_data['title'] = '修改个人资料';
                 $this->_data['error'] = '没有任何更新，请重试';
                 $this->assign($this->_data);
                 $this->display();
             }



         } else {
             $this->_data['title'] = '修改个人资料';
             //$this->_data['user'] = $this->get_user_info($_SESSION['id']);
             //var_dump($this->_data);
             $this->assign($this->_data);
             $this->display();
         }
    }


    /**
     * 修改密码
     */
    public function password() {
        $this->is_user();

        $this->_data['title'] = '修改密码';
        if ($_POST) {
            if (isset($_POST['password']) && strlen($_POST['password']) > 5) {
                $post['password'] = I('post.password', 0);
                $post['password_confirm'] = I('password_confirm', 0);

                if ($post['password'] == $post['password_confirm']) {

                    $Account = M('account');
                    $account_where['account_id'] = $_SESSION['id'];
                    $account_data['password'] = $post['password'];
                    $account_result = $Account->where($account_where)->data($account_data)->save();

                    if ($account_result) {

                        $this->_data['message'] = '修改密码成功！';
                        unset($_SESSION['f']);
                        unset($this->_data['first']);
                        $this->assign($this->_data);
                        $this->display();

                    } else {
                        $this->_data['error'] = '修改失败，请重试';
                        $this->assign($this->_data);
                        $this->display();
                    }

                } else {
                    $this->_data['error'] = '两次密码不一致';
                    $this->assign($this->_data);
                    $this->display();
                }
            } else {
                $this->_data['error'] = '密码长度不能小于6位';
                $this->assign($this->_data);
                $this->display();
            }

        } else {
            $this->assign($this->_data);
            $this->display();
        }
    }


    /**
     * 预约表
     */
    public function appoint() {

        $this->is_user();

        $Appoint = M('appoint');
        $appoint_where['user_id'] = $_SESSION['id'];
        $appoint_result = $Appoint->where($appoint_where)->select();


        //var_dump($appoint_result);

        $Teacher = M('teacher');
        foreach ($appoint_result as $k => $v) {
            $teacher_where['account_id'] = $v['teacher_id'];
            $teacher_result = $Teacher->where($teacher_where)->find();
            $appoint_result[$k]['teacher_name'] = $teacher_result['name'];
            $appoint_result[$k]['teacher_email'] = $teacher_result['email'];

            switch ($v['status']) {
                case 0:
                    $appoint_result[$k]['status'] = '待咨询师确认';
                    break;
                case 1:
                    $appoint_result[$k]['status'] = '咨询师已确认';
                    break;
                case 2:
                    $appoint_result[$k]['status'] = '已完成';
                    break;
            }

            $timeArr = explode(',', $v['time']);
            $appoint_result[$k]['time'] = '';
            foreach($timeArr as $kk => $vv) {
                $tempArr = explode('-', $vv);
                var_dump($tempArr);

                switch ($tempArr[0]) {
                    case 'a':
                        $t = '9:00-10:30';
                        break;
                    case 'b':
                        $t = '10:30-12:00';
                        break;
                    case 'c':
                        $t = '14:30-16:00';
                        break;
                    case 'd':
                        $t = '16:00-17:30';
                        break;
                    case 'e':
                        $t = '19:00-20:30';
                        break;
                    case 'f':
                        $t = '20:30-22:00';
                        break;
                }

                $appoint_result[$k]['time'] .= $tempArr[1] . '-' . $tempArr[2] . '-' . $tempArr[3] . $t . '/';
            }
            $appoint_result[$k]['time'] = rtrim($appoint_result[$k]['time'], '/');
        }


        $this->_data['appoint'] = $appoint_result;
        $this->_data['title'] = '我的预约表';
        $appoint_confirm_count_where['user_id'] = $_SESSION['id'];
        $appoint_confirm_count_where['status'] = 1;
        $this->_data['appoint_confirm_count'] = $Appoint->where($appoint_confirm_count_where)->count('id');
        $this->assign($this->_data);
        $this->display();

    }


    /**
     * 判断是 type 否为 user
     * 如果不是，则跳转到相应 type
     */
    private function is_user() {
        $type = $this->login_type();

        if ($type == 1) {

        } else if($type == 2) {
            $this->redirect('Teacher/index', '', 0);
        } else if ($type == 3) {
            $this->redirect('Admin/index', '', 0);
        } else {
            logout();
            $this->redirect('Home/Login/index', '', 0);
        }
    }


}