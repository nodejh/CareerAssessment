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

                    //var_dump($user_update);

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
                    $account_data['password'] = encrypt($post['password']);
                    $account_result = $Account->where($account_where)->data($account_data)->save();

                    if ($account_result) {

                        $this->_data['message'] = '修改密码成功！';
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

        $Teacher = M('teacher');
        foreach ($this->_data['user']['appoint_list'] as $k => $v) {
            $teacher_where['account_id'] = $v['teacher_id'];
            $teacher_result = $Teacher->where($teacher_where)->find();
            //$appoint_result[$k]['teacher_name'] = $teacher_result['name'];
            //$appoint_result[$k]['teacher_email'] = $teacher_result['email'];
            $appoint_info[$k] = $teacher_result;
            $appoint_info[$k]['status'] = $v['status'];
            $appoint_info[$k]['appoint_id'] = $v['appoint_id'];
            //switch ($v['status']) {
            //    case 0:
            //        $appoint_result[$k]['status'] = '待咨询师确认';
            //        break;
            //    case 1:
            //        $appoint_result[$k]['status'] = '咨询师已确认';
            //        break;
            //    case 2:
            //        $appoint_result[$k]['status'] = '已完成';
            //        break;
            //}

            //$timeArr = explode(',', $v['time']);
            //$appoint_result[$k]['time'] = '';
            //foreach($timeArr as $kk => $vv) {
            //    $tempArr = explode('-', $vv);
            //    var_dump($tempArr);
            //
            //    switch ($tempArr[0]) {
            //        case 'a':
            //            $t = '9:00-10:30';
            //            break;
            //        case 'b':
            //            $t = '10:30-12:00';
            //            break;
            //        case 'c':
            //            $t = '14:30-16:00';
            //            break;
            //        case 'd':
            //            $t = '16:00-17:30';
            //            break;
            //        case 'e':
            //            $t = '19:00-20:30';
            //            break;
            //        case 'f':
            //            $t = '20:30-22:00';
            //            break;
            //    }
            //
            //    $appoint_result[$k]['time'] .= $tempArr[1] . '-' . $tempArr[2] . '-' . $tempArr[3] . $t . '/';
            //}
            //$appoint_result[$k]['time'] = rtrim($appoint_result[$k]['time'], '/');
        }

        //var_dump($appoint_teacher_info);
        //die();

        $this->_data['appoint_info'] = $appoint_info;
        $this->_data['title'] = '我的预约表';
        //$appoint_confirm_count_where['user_id'] = $_SESSION['id'];
        //$appoint_confirm_count_where['status'] = 1;
        //$this->_data['appoint_confirm_count'] = $Appoint->where($appoint_confirm_count_where)->count('id');
        $this->assign($this->_data);
        $this->display();
    }


    /**
     * 删除预约
     */
    public function appoint_delete() {
        $this->is_user();
        $id = I('get.appoint_id', 0);
        if ($id) {
            $Appoint = M('appoint');
            $where['appoint_id'] = $id;
            $delete_result = $Appoint->where($where)->delete();
            if ($delete_result) {
                $this->redirect('appoint', '', 0);
            } else {
                $this->redirect('appoint', '', 0);
            }
        } else {
            $this->redirect('appoint', '', 0);
        }
    }


    /**
     * 预约详情
     */
    public function appoint_details() {
        $this->is_user();
        //var_dump($this->_data['user']['appoint_list']);
        //die();
        $appoint_id = I('get.appoint_id', 0);
        $result = [];
        foreach($this->_data['user']['appoint_list'] as $k => $v) {
            if ($v['appoint_id'] == $appoint_id) {
                $Teacher = M('teacher');
                $where['account_id'] = $v['teacher_id'];
                $teacher_info = $Teacher->where($where)->find();

                $result['appoint']['appoint_id'] = $appoint_id;
                $result['appoint']['user_id'] = $v['user_id'];
                $result['appoint']['teacher_id'] = $v['teacher_id'];
                $result['appoint']['user_select_date'] = $v['user_select_date'];
                $result['appoint']['teacher_confirm_date'] = $v['teacher_confirm_date'];
                $result['appoint']['user_confirm_date'] = $v['user_confirm_date'];
                $result['appoint']['save_time'] = $v['save_time'];
                $result['appoint']['status'] = $v['status'];
                $result['teacher']['avatar'] = $teacher_info['avatar'];
                $result['teacher']['gender'] = $teacher_info['gender'];
                $result['teacher']['email'] = $teacher_info['email'];
                $result['teacher']['city'] = $teacher_info['city'];
                $result['teacher']['name'] = $teacher_info['name'];
                $result['teacher']['service_type'] = $teacher_info['service_type'];
                $result['teacher']['free_time'] = $teacher_info['free_time'];
                $result['teacher']['certificate'] = $teacher_info['certificate'];
                $result['teacher']['introduction'] = $teacher_info['introduction'];
                break;
            }
        }
        //var_dump($result);
        //die();
        $this->_data['appoint_info'] = $result;
        $this->_data['html'] = set_week();
        $this->_data['appoint_confirm_url'] = U('appoint_confirm');
        $this->assign($this->_data);
        $this->display();
    }


    /**
     * 修改预约
     */
    public function appoint_change() {
        $this->is_user();
        $this->_data['title'] = '修改预约';
        if ($_POST && $_POST['appoint_id']) {

            $where['appoint_id'] = $_POST['appoint_id'];
            $data['time'] = $_POST['time'];
            $Appoint = M('appoint');
            $appoint_result = $Appoint->data($data)->where($where)->save();
            if ($appoint_result !== false ){
                $res['status']  = 0;
                $res['content'] = 'success';
                $this->ajaxReturn($res);
            } else {
                $res['status']  = -1;
                $res['content'] = 'fail';
                $this->ajaxReturn($res);
            }

        } else {
            $appoint_id = I('get.appoint_id', 0);
            $result = [];
            foreach($this->_data['user']['appoint_list'] as $k => $v) {
                if ($v['appoint_id'] == $appoint_id) {
                    $Teacher = M('teacher');
                    $where['account_id'] = $v['teacher_id'];
                    $teacher_info = $Teacher->where($where)->find();

                    $result['appoint']['appoint_id'] = $appoint_id;
                    $result['appoint']['user_id'] = $v['user_id'];
                    $result['appoint']['teacher_id'] = $v['teacher_id'];
                    $result['appoint']['time'] = $v['time'];
                    $result['appoint']['save_time'] = $v['save_time'];
                    $result['appoint']['status'] = $v['status'];
                    $result['teacher']['avatar'] = $teacher_info['avatar'];
                    $result['teacher']['gender'] = $teacher_info['gender'];
                    $result['teacher']['email'] = $teacher_info['email'];
                    $result['teacher']['city'] = $teacher_info['city'];
                    $result['teacher']['name'] = $teacher_info['name'];
                    $result['teacher']['service_type'] = $teacher_info['service_type'];
                    $result['teacher']['free_time'] = $teacher_info['free_time'];
                    $result['teacher']['certificate'] = $teacher_info['certificate'];
                    $result['teacher']['introduction'] = $teacher_info['introduction'];
                    break;
                }
            }

            $this->_data['appoint_info'] = $result;
            $this->_data['html'] = set_week();
            $this->_data['url'] = U('appoint_change');
            $this->assign($this->_data);
            $this->display();
        }
    }


    /**
     * 来访者确认预约时间
     */
    public function appoint_confirm() {
        $this->is_user();
        if ($_POST && isset($_POST['appoint_id'])) {

            $where['appoint_id'] = $_POST['appoint_id'];
            $data['user_confirm_date'] = $_POST['time'];
            $appoint_data['user_confirm_time'] = time();
            $data['status'] = 2;
            $Appoint = M('appoint');
            $appoint_result = $Appoint->data($data)->where($where)->save();
            if ($appoint_result !== false) {
                $res['status'] = 0;
                $res['content'] = 'success';
                $this->ajaxReturn($res);
            } else {
                $res['status'] = -1;
                $res['content'] = 'fail';
                $this->ajaxReturn($res);
            }
        } else {
            $res['status'] = -1;
            $res['content'] = '非法操作';
            $this->ajaxReturn($res);
        }
    }

    /**
     * 对预约进行支付宝付款
     */
    public function appoint_pay() {
        $this->is_user();
        $this->_data['title'] = '付款';
        $this->assign($this->_data);
        $this->display();
    }


    /**
     *  评价
     */
    public function comment() {
        $this->is_user();

        if ($_POST && isset($_POST['appoint_id']) && isset($_POST['user_id']) && isset($_POST['teacher_id'])) {
            $data['appoint_id'] = $_POST['appoint_id'];
            $data['user_id'] = $_POST['user_id'];
            $data['teacher_id'] = $_POST['teacher_id'];
            $data['attitude_score'] = $_POST['attitude_score'] ? $_POST['attitude_score'] : 0 ;
            $data['professional_score'] = $_POST['professional_score'] ? $_POST['professional_score'] : 0;
            $data['content'] = $_POST['content'] ? $_POST['content'] : null;
            $data['time'] = time();

            $Comment = M('comment');
            $insert_comment = $Comment->data($data)->add();

            // 计算改咨询师的平均分，并存入 teacher 表
            $where_comment['teacher_id'] = $_POST['teacher_id'];
            $comment_list = $Comment->where($where_comment)->select();

            $comment_list_length = count($comment_list);
            $all_attitude_score = 0;
            $all_professional_score = 0;
            for ($i = 0; $i < $comment_list_length; $i++) {
                $all_attitude_score += $comment_list[$i]['attitude_score'];
                $all_professional_score += $comment_list[$i]['professional_score'];
            }
            $data_teacher['attitude_score'] = round($all_attitude_score/$comment_list_length, 2);
            $data_teacher['professional_score'] = round($all_professional_score/$comment_list_length, 2);
            $Teacher = M('teacher');
            $where_teacher['account_id'] = $_POST['teacher_id'];
            $update_teacher = $Teacher->where($where_teacher)->data($data_teacher)->save();
            //var_dump($insert_comment);
            //var_dump($update_teacher);
            if ($insert_comment && ($update_teacher !== false)) {
                $res['status'] = 0;
                $res['content'] = 'success';
                $this->ajaxReturn($res);
            } else {
                $res['status'] = -1;
                $res['content'] = 'fail';
                $this->ajaxReturn($res);
            }
        } else {
            //$res['status'] = -1;
            //$res['content'] = '非法操作';
            //$this->ajaxReturn($res);
            $teacher_account_id = $_GET['teacher_id'];
            $Teacher = M('teacher');
            $where['account_id'] = $teacher_account_id;
            $teacher = $Teacher->where($where)->find();
            $this->_data['teacher'] = $teacher;
            $this->_data['comment_url'] = U('comment');
            $this->_data['appoint_id'] = $_GET['appoint_id'];
            $this->assign($this->_data);
            $this->display();
        }
    }


    public function pay() {
        $this->is_user();

        $this->_data['title'] = '我的钱包';
        $where['id'] = $_SESSION['id'];
        $this->_data['user'] = M('user')->where($where)->find();
        $this->assign($this->_data);
        $this->display();
    }


    /**
     * 评论列表
     */
    public function comment_list() {
        $this->is_user();

        $this->_data['title'] = '我的评分';

        $Comment = M('comment');
        $where['user_id'] = $_SESSION['id'];
        $comment_list = $Comment->where($where)->order('comment_id desc')->select();


        $User = M('teacher');

        if ($comment_list) {
            foreach ($comment_list as $k => $v) {
                $where_teacher['account_id'] = $v['teacher_id'];
                // TODO 只查询 name 一个字段
                $teacher = $User->where($where_teacher)->find();
                $comment_list[$k]['name'] = $teacher['name'];
            }

            $this->_data['comment'] = $comment_list;

        } else {
            $this->_data['comment'] = '';
        }

        $this->assign($this->_data);
        $this->display();


    }


    /**
     * 评测
     */
    public function test() {
        $this->is_user();
        $this->_data['title'] = '我的评分';
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