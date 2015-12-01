<?php
// +----------------------------------------------------------------------
// | Teacher后台
// +----------------------------------------------------------------------

namespace Admin\Controller;
use Think\Controller;

/**
 * Class TeacherController
 * @package Admin\Controller
 */
class TeacherController extends BaseController {

    /**
     * Teacher后台首页
     */
    public function index(){
        $this->is_teacher();
        //var_dump($this->_data['teacher']);
        //if ($_POST) {
        //    // 上传图片
        //    $upload = new \Think\Upload();
        //    $upload->maxSize = 3145728 ;// 设置附件上传大小
        //    $upload->exts = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        //    $upload->rootPath = './Uploads/'; // 设置附件上传根目录
        //    $upload->savePath = 'avatar/';
        //
        //    // TODO 这里每post交一次，都要上传一张图片；应改为如果没有更新图片，则不上传
        //    $info = $upload->uploadOne($_FILES['avatar']);
        //    if (!$info) {
        //        $this->_data['error'] = $upload->getError();
        //        $this->assign($this->_data);
        //        $this->display();
        //
        //    } else {
        //        $post['avatar'] = $info['savepath'].$info['savename'];
        //        $post['name'] = I('post.name', 0);
        //        $post['gender'] = I('post.gender', 0);
        //        $post['email'] = I('post.email', 0);
        //        $post['city'] = I('post.city', 0);
        //        $post['service_type_a'] = I('post.service_type_a', 0);
        //        $post['service_type_b'] = I('post.service_type_b', 0);
        //        $post['service_type_c'] = I('post.service_type_c', 0);
        //        $post['service_type_d'] = I('post.service_type_d', 0);
        //        $post['service_type_e'] = I('post.service_type_e', 0);
        //        $post['introduction'] = I('post.introduction', 0);
        //        if ($post['service_type_a'] || $post['service_type_b'] || $post['service_type_c'] || $post['service_type_d'] || $post['service_type_e']) {
        //            $post['service_type'] = '';
        //            if ($post['service_type_a']) {
        //                $post['service_type'] .= $post['service_type_a'] . ',';
        //            }
        //            if ($post['service_type_b']) {
        //                $post['service_type'] .= $post['service_type_b'] . ',';
        //            }
        //            if ($post['service_type_c']) {
        //                $post['service_type'] .= $post['service_type_c'] . ',';
        //            }
        //            if ($post['service_type_d']) {
        //                $post['service_type'] .= $post['service_type_d'] . ',';
        //            }
        //            if ($post['service_type_e']) {
        //                $post['service_type'] .= $post['service_type_e'] . ',';
        //            }
        //            $post['service_type'] = rtrim($post['service_type'], ',');
        //        }
        //
        //        // 将信息写入数据库
        //        $Teacher = M('teacher');
        //        $where_update['account_id'] = $_SESSION['id'];
        //
        //        $data = [];
        //        if ($post['avatar']) {
        //            $data['avatar'] = $post['avatar'];
        //        }
        //        if ($post['name']) {
        //            $data['name'] = $post['name'];
        //        }
        //        if ($post['gender']) {
        //            $data['gender'] = $post['gender'];
        //        }
        //        if ($post['email']) {
        //            $data['email'] = $post['email'];
        //        }
        //        if ($post['city']) {
        //            $data['city'] = $post['city'];
        //        }
        //        if ($post['introduction']) {
        //            $data['introduction'] = $post['introduction'];
        //        }
        //        if ($post['service_type']) {
        //            $data['service_type'] = $post['service_type'];
        //        }
        //        if (count($data) > 0) {
        //            $row_teacher = $Teacher->data($data)->where($where_update)->save();
        //            if ($row_teacher !== false) {
        //                $this->_data['title'] = '修改个人资料';
        //                $this->_data['message'] = '修改个人资料修成功,请<a href="'.U('index').'">刷新</a>查看';
        //                $this->assign($this->_data);
        //                $this->display();
        //            } else {
        //                $this->_data['error'] = '修改个人资料修失败，请重试';
        //                $this->assign($this->_data);
        //                $this->display();
        //            }
        //        } else {
        //            $this->_data['title'] = '修改个人资料';
        //            $this->_data['message'] = '头像修改成功,请<a href="'.U('index').'">刷新</a>查看';
        //            $this->assign($this->_data);
        //            $this->display();
        //        }
        //    }
        //
        //} else {
        //    $this->_data['title'] = '个人中心';
        //    $this->assign($this->_data);
        //    $this->display();
        //}

        $this->_data['title'] = '个人中心';
        $this->assign($this->_data);
        $this->display();
    }


    /**
     * 个人资料
     */
    public function profile() {
        $this->is_teacher();
        $this->_data['title'] = '个人资料';
        $this->_data['teacher'] = M('teacher')->where(array('account_id'=>$_SESSION['id']))->find();

        if ($this->_data['teacher']['certificate'] != null) {
            $certificate_array = explode(',', $this->_data['teacher']['certificate']);
        }
        $Certificate = M('certificate');
        foreach($certificate_array as $k => $v) {
            $where_certificate['id'] = $v;
            $this->_data['teacher']['certificate_list'][$k] = $Certificate->where($where_certificate)->find();
        }

        $this->assign($this->_data);
        $this->display();
    }

    /**
     * 修改个人资料
     */
    public function edit() {
        $this->is_teacher();
        $this->_data['title'] = '修改个人资料';
        // 查询咨询师信息
        $this->_data['teacher'] = M('teacher')->where(array('account_id'=>$_SESSION['id']))->find();
        if ($_POST) {
            //var_dump($_POST);
            $data['name'] = $_POST['name'];
            $data['gender'] = $_POST['gender'];
            $data['email'] = $_POST['email'];
            $data['city'] = $_POST['city'];
            $data['service_type'] = '';
            if ($_POST['service_type_a']) {
                $data['service_type'] .= $_POST['service_type_a'] . ',';
            }
            if ($_POST['service_type_b']) {
                $data['service_type'] .= $_POST['service_type_b'] . ',';
            }
            if ($_POST['service_type_c']) {
                $data['service_type'] .= $_POST['service_type_c'] . ',';
            }
            if ($_POST['service_type_d']) {
                $data['service_type'] .= $_POST['service_type_d'] . ',';
            }
            if ($_POST['service_type_e']) {
                $data['service_type'] .= $_POST['service_type_e'] . ',';
            }
            if ($_POST['service_type_f']) {
                $data['service_type'] .= $_POST['service_type_f'] . ',';
            }
            if ($_POST['service_type_g']) {
                $data['service_type'] .= $_POST['service_type_g'] . ',';
            }
            if ($data['service_type'] != '') {
                $data['service_type'] = rtrim($data['service_type'], ',');
            }
            $data['service_object'] = '';
            if ($_POST['service_object_a']) {
                $data['service_object'] .= $_POST['service_object_a'] . ',';
            }
            if ($_POST['service_object_b']) {
                $data['service_object'] .= $_POST['service_object_b'] . ',';
            }
            if ($_POST['service_object_c']) {
                $data['service_object'] .= $_POST['service_object_c'] . ',';
            }
            if ($_POST['service_object_d']) {
                $data['service_object'] .= $_POST['service_object_d'] . ',';
            }
            if ($_POST['service_object_e']) {
                $data['service_object'] .= $_POST['service_object_e'] . ',';
            }
            if ($data['service_object'] != '') {
                $data['service_object'] = rtrim($data['service_object'], ',');
            }

            $data['introduction'] = $_POST['introduction'];
            $where['account_id'] = $_SESSION['id'];
            $update = M('teacher')->where($where)->data($data)->save();
            //var_dump($update);
            //die();
            if ($update) {
                $this->_data['message'] = '修改资料成功！跳转到<a href="'.U('profile').'">基本资料</a>查看';
            } else {
                $this->_data['error'] = '修改资料失败，请重试！';
            }
        }

        $this->assign($this->_data);
        $this->display();
    }


    /**
     * 修改头像
     */
    public function avatar() {
        $this->is_teacher();
        $this->_data['title'] = '修改头像';

        if ($_FILES['avatar']) {
            // 上传图片
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
                $where_update['account_id'] = $_SESSION['id'];
                $data['avatar'] = $post['avatar'];
                $row_teacher = $Teacher->data($data)->where($where_update)->save();
                if ($row_teacher !== false) {
                    $this->_data['title'] = '修改个人资料';
                    $this->_data['message'] = '修改个人资料修成功！跳转到<a href="'.U('profile').'">基本资料</a>查看';
                    $this->assign($this->_data);
                    $this->display();
                } else {
                    $this->_data['error'] = '修改个人资料修失败，请重试';
                    $this->assign($this->_data);
                    $this->display();
                }
            }
        } else {
            $this->assign($this->_data);
            $this->display();
        }
    }


    /**
     * 修改密码
     */
    public function password() {
        $this->is_teacher();

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
     * 我的空闲时间
     */
     public function time() {
         $this->is_teacher();
         //var_dump($this->_data['teacher']);
         // TODO 预约者姓名(把这部分写在 base)
         $this->_data['teacher']['appoint_name'] = 'appoint_name';
         $this->_data['title'] = '我的空闲时间';
         $today_week = date('N', time());

         switch ($today_week) {
             case '1':
                 $this->_data['html'] = $this->set_week_1();
                 break;
             case '2':
                 $this->_data['html'] = $this->set_month_by_2();
                 break;
             case '3':
                 $this->_data['html'] = $this->set_week_3();
                 break;
             case '4':
                 $this->_data['html'] = $this->set_week_4();
                 break;
             case '5':
                 $this->_data['html'] = $this->set_week_5();
                 break;
             case '6':
                 $this->_data['html'] = $this->set_week_6();
                 break;
             case '7':
                 $this->_data['html'] = $this->set_week_7();
                 break;
         }

         $this->_data['save_time_url'] = U('save_time');
         $this->assign($this->_data);
         $this->display();
     }


    /**
     * 保存空闲时间
     */
    public function save_time() {
        if ($_POST && isset($_POST['time'])) {

            $Teacher = M('teacher');
            $where['account_id'] = $_SESSION['id'];
            $data['free_time'] = I('post.time', null);

            $update_result = $Teacher->where($where)->save($data);
            if ($update_result || $update_result === 0) {
                $result['status'] = 0;
                $this->ajaxReturn($result);
            } else {
                $result['status'] = 1001;
                $result['message'] = '更新失败';
                $this->ajaxReturn($result);
            }

        } else {
            $result['status'] = 1000;
            $result['message'] = '非法请求';
            $this->ajaxReturn($result);
        }
    }


    /**
     * 我的预约表
     */
    public function appoint() {
        $this->is_teacher();
        $this->_data['title'] = '我的预约表';

        $User = M('user');
        foreach ($this->_data['teacher']['appoint_list'] as $k => $v) {
            $user_where['account_id'] = $v['user_id'];
            $user_result = $User->where($user_where)->find();
            $appoint_result[$k]['user_account_id'] = $user_result['account_id'];
            $appoint_result[$k]['name'] = $user_result['name'];
            $appoint_result[$k]['email'] = $user_result['email'];
            $appoint_result[$k]['gender'] = $user_result['gender'];
            $appoint_result[$k]['status'] = $user_result['status'];
            $appoint_result[$k]['school'] = $user_result['school'];
            $appoint_result[$k]['college'] = $user_result['college'];
            $appoint_result[$k]['student_type'] = $user_result['student_type'];
            $appoint_result[$k]['city'] = $user_result['city'];
            $appoint_result[$k]['save_time'] = $this->_data['teacher']['appoint_list'][$k]['save_time'];
            $appoint_result[$k]['appoint_id'] = $this->_data['teacher']['appoint_list'][$k]['appoint_id'];
            $appoint_result[$k]['status'] = $this->_data['teacher']['appoint_list'][$k]['status'];
        }

        $this->_data['appoint_info'] = $appoint_result;
        //var_dump($this->_data);
        //die();
        $this->assign($this->_data);
        $this->display();
    }

    //
    ///**
    // * 确认预约
    // */
    //public function appoint_ok() {
    //    $this->is_teacher();
    //    if($_GET && $_GET['id']) {
    //        $id = I('get.id', 0);
    //
    //        if ($id) {
    //            $Appoint = M('appoint');
    //            $appoint_where['id'] = $id;
    //            $appoint_data['status'] = 1;
    //            $appoint_result = $Appoint->where($appoint_where)->data($appoint_data)->save();
    //
    //            if ($appoint_result) {
    //                $this->_data['title'] = '我的预约表';
    //
    //                $this->assign($this->_data);
    //                $this->redirect('appoint', '', 0);
    //
    //            } else {
    //
    //                $this->_data['title'] = '我的预约表';
    //                $this->assign($this->_data);
    //                $this->redirect('appoint', '', 0);
    //            }
    //        }
    //    }
    //}


    /**
     * 确认预约时间
     */
    public function appoint_confirm() {
        $this->is_teacher();
        $id = I('post.id', 0);
        $time = I('post.time', 0);
        //var_dump($id);
        //var_dump($time);
        if($id && $time) {
            $Appoint = M('appoint');
            $appoint_where['appoint_id'] = $id;
            $appoint_data['status'] = 1;
            $appoint_data['teacher_confirm_date'] = $time;
            $appoint_data['teacher_confirm_time'] = time();
            $appoint_result = $Appoint->where($appoint_where)->data($appoint_data)->save();
            //var_dump($appoint_result);
            if ($appoint_result) {

                $result['status'] = 0;
                $result['message'] = 's';
                $this->ajaxReturn($result);

            } else {
                $result['status'] = -1;
                $result['message'] = 'fail';
                $this->ajaxReturn($result);
            }
        }
    }


    /**
     * 确认完成预约
     */
    public function appoint_finish() {
        $this->is_teacher();

        $id = I('post.id', 0);

        if ($id) {
            $Appoint = M('appoint');
            $appoint_where['appoint_id'] = $id;
            $appoint_data['status'] = 3;
            $appoint_data['finish_time'] = time();
            $appoint_result = $Appoint->where($appoint_where)->data($appoint_data)->save();

            if ($appoint_result !== false) {
                $res['status'] = 0;
                $res['message'] = 'success';
                $this->ajaxReturn($res);

            } else {
                $res['status'] = -1;
                $res['message'] = 'fail';
                $this->ajaxReturn($res);
            }
        }
    }


    /**
     * 预约详情
     */
    function appoint_details() {
        $this->is_teacher();
        $appoint_id = I('get.appoint_id', 0);
        if ($appoint_id) {

            $result = [];
            foreach($this->_data['teacher']['appoint_list'] as $k => $v) {
                if ($v['appoint_id'] == $appoint_id) {
                    $User = M('user');
                    $where['account_id'] = $v['user_id'];
                    $user_info = $User->where($where)->find();
                    $result['appoint']['appoint_id'] = $appoint_id;
                    $result['appoint']['user_id'] = $v['user_id'];
                    $result['appoint']['teacher_id'] = $v['teacher_id'];
                    $result['appoint']['user_select_date'] = $v['user_select_date'];
                    $result['appoint']['teacher_confirm_date'] = $v['teacher_confirm_date'];
                    $result['appoint']['user_confirm_date'] = $v['user_confirm_date'];
                    $result['appoint']['status'] = $v['status'];
                    $result['appoint']['save_time'] = $v['save_time'];
                    $result['appoint']['status'] = $v['status'];
                    $result['user']['name'] = $user_info['name'];
                    $result['user']['gender'] = $user_info['gender'];
                    $result['user']['email'] = $user_info['email'];
                    $result['user']['city'] = $user_info['city'];
                    $result['user']['status'] = $user_info['status'];
                    $result['user']['school'] = $user_info['school'];
                    $result['user']['college'] = $user_info['college'];
                    $result['user']['student_type'] = $user_info['student_type'];
                    break;
                }
            }

            $this->_data['appoint_info'] = $result;
            $this->_data['html'] = set_week();
            $this->_data['appoint_confirm_url'] = U('appoint_confirm');
            $this->_data['appoint_finish_url'] = U('appoint_finish');

            $this->assign($this->_data);
            $this->display();

        } else {
            $this->redirect('appoint', '', 0);
        }
    }


    /**
     * 我的课程
     */
    public function course() {
        $this->is_teacher();
        $this->_data['title'] = '我的课程';

        $this->assign($this->_data);
        $this->display();
    }


    /**
     * 我的钱包
     */
    public function pay() {
        $this->is_teacher();
        $this->_data['title'] = '我的钱包';
        $where['account_id'] = $_SESSION['id'];
        $this->_data['teacher'] = M('teacher')->where($where)->find();
        //var_dump($this->_data['teacher']);
        $this->assign($this->_data);
        $this->display();
    }


    /**
     * 添加证书
     */
    public function certificate() {
        $this->is_teacher();
        $this->_data['title'] = '我的证书';

        if ($_POST['name'] && $_FILES['picture']) {

            $post['name'] = $_POST['name'];


            $upload = new \Think\Upload();
            $upload->maxSize = 3145728 ;// 设置附件上传大小
            $upload->exts = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $upload->rootPath = './Uploads/'; // 设置附件上传根目录
            $upload->savePath = 'certificate/';

            $info = $upload->uploadOne($_FILES['picture']);
            if (!$info) {
                $this->_data['error'] = $upload->getError();
                $this->assign($this->_data);
                $this->display();

            } else {
                $post['picture'] = $info['savepath'].$info['savename'];
                $Certificate = M('certificate');
                $data['picture'] = $post['picture'];
                $data['name'] = $post['name'];

                $row_certificate = $Certificate->data($data)->add();
                if ($row_certificate) {
                    $Teacher = M('teacher');
                    $where_teacher['account_id'] = $_SESSION['id'];
                    $data_old_certificate['picture'] = $Teacher->where($where_teacher)->getField('certificate');
                    if ($data_old_certificate['picture'] == null) {
                        $data_new_certificate['certificate'] = $row_certificate;
                    } else {
                        $data_new_certificate['certificate'] = $data_old_certificate['picture'] . ',' . $row_certificate;
                    }
                    $update_certificate = $Teacher->where($where_teacher)->data($data_new_certificate)->save();
                    if ($update_certificate) {
                        $this->_data['message'] = '添加证书成功！跳转到<a href="'.U('profile').'">基本资料</a>查看';
                        $this->assign($this->_data);
                        $this->display();
                    } else {
                        $this->_data['error'] = '添加证书失败，请重试';
                        $this->assign($this->_data);
                        $this->display();
                    }
                } else {
                    $this->_data['error'] = '添加证书失败，请重试';
                    $this->assign($this->_data);
                    $this->display();
                }
            }
        }

        $this->assign($this->_data);
        $this->display();
    }


    /**
     * 我的评分
     */
    public function comment() {
        $this->is_teacher();
        $this->_data['title'] = '我的评分';

        $Comment = M('comment');
        $where['teacher_id'] = $_SESSION['id'];
        $comment_list = $Comment->where($where)->order('comment_id desc')->select();


        $User = M('user');

        if ($comment_list) {
            foreach ($comment_list as $k => $v) {
                $where_user['account_id'] = $v['user_id'];
                // TODO 只查询 name 一个字段
                $user = $User->where($where_user)->find();
                $comment_list[$k]['name'] = $user['name'];
            }

            $this->_data['comment'] = $comment_list;

        } else {
            $this->_data['comment'] = '';
        }

        $this->assign($this->_data);
        $this->display();
    }


    /**
     * 判断 type 是否为 teacher
     * 如果不是，则跳转到相应 type
     */
    private function is_teacher() {
        $type = $this->login_type();

        if ($type == 2) {

        } else if($type == 1) {
            $this->redirect('User/index', '', 0);
        } else if ($type == 3) {
            $this->redirect('Admin/index', '', 0);
        } else {
            logout();
            $this->redirect('Home/Login/index', '', 0);
        }
    }


    /**
     * 预约信息
     */
    //private function get_appoint_info() {
    //    $Appoint = M('appoint');
    //    $appoint_where['accound_id'] = $_SESSION['id'];
    //    $appoint_result = $Appoint->where($appoint_where)->select();
    //
    //    $User = M('user');
    //    foreach ($appoint_result as $k => $v) {
    //        $user_where['account_id'] = $v['user_id'];
    //        $user_result = $User->where($user_where)->find();
    //        $appoint_result[$k]['name'] = $user_result['name'];
    //        $appoint_result[$k]['email'] = $user_result['email'];
    //        $appoint_result[$k]['gender'] = $user_result['gender'];
    //        $appoint_result[$k]['status'] = $user_result['status'];
    //        $appoint_result[$k]['school'] = $user_result['school'];
    //        $appoint_result[$k]['college'] = $user_result['college'];
    //        $appoint_result[$k]['student_type'] = $user_result['student_type'];
    //        $appoint_result[$k]['city'] = $user_result['city'];
    //    }
    //
    //    return $appoint_result;
    //}


    /**
     * @return string
     */
    private function set_week_1() {
        $html = '';
        $date_1 = date('Y-m-d', time());
        $date_2 = date('Y-m-d', strtotime('+1 day'));
        $date_3 = date('Y-m-d', strtotime('+2 day'));
        $date_4 = date('Y-m-d', strtotime('+3 day'));
        $date_5 = date('Y-m-d', strtotime('+4 day'));
        $date_6 = date('Y-m-d', strtotime('+5 day'));
        $date_7 = date('Y-m-d', strtotime('+6 day'));

        $date_8 = date('Y-m-d', strtotime('+7 day'));
        $date_9 = date('Y-m-d', strtotime('+8 day'));
        $date_10 = date('Y-m-d', strtotime('+9 day'));
        $date_11= date('Y-m-d', strtotime('+10 day'));
        $date_12 = date('Y-m-d', strtotime('+11 day'));
        $date_13 = date('Y-m-d', strtotime('+12 day'));
        $date_14 = date('Y-m-d', strtotime('+13 day'));

        $date_15 = date('Y-m-d', strtotime('+14 day'));
        $date_16 = date('Y-m-d', strtotime('+15 day'));
        $date_17 = date('Y-m-d', strtotime('+16 day'));
        $date_18= date('Y-m-d', strtotime('+17 day'));
        $date_19 = date('Y-m-d', strtotime('+18 day'));
        $date_20 = date('Y-m-d', strtotime('+19 day'));
        $date_21 = date('Y-m-d', strtotime('+20 day'));

        $date_22 = date('Y-m-d', strtotime('+21 day'));
        $date_23 = date('Y-m-d', strtotime('+22 day'));
        $date_24 = date('Y-m-d', strtotime('+23 day'));
        $date_25= date('Y-m-d', strtotime('+24 day'));
        $date_26 = date('Y-m-d', strtotime('+25 day'));
        $date_27 = date('Y-m-d', strtotime('+26 day'));
        $date_28 = date('Y-m-d', strtotime('+27 day'));

        $date_29 = date('Y-m-d', strtotime('+28 day'));
        $date_30 = date('Y-m-d', strtotime('+29 day'));

        $html .= '<table class="table table-bordered ca-table-week" id="week_1">' .
            '<thead>'.
            '<tr>'.
            '<th>时间</th>'.
            '<th>星期一<br>('.$date_1.')</th>'.
            '<th>星期二<br>('.$date_2.')</th>'.
            '<th>星期三<br>('.$date_3.')</th>'.
            '<th>星期四<br>('.$date_4.')</th>'.
            '<th>星期五<br>('.$date_5.')</th>'.
            '<th>星期六<br>('.$date_6.')</th>'.
            '<th>星期日<br>('.$date_7.')</th>'.
            '</tr>'.
            '</thead>'.
            '<tbody>'.
            '<tr>'.
            '<th scope="row">9:00-10:30</th>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_1.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_2.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_3.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">10:30-12:00</th>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_1.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_2.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_3.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">14:30-16:00</th>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_1.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_2.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_3.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">16:00-17:30</th>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_1.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_2.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_3.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">19:00-20:30</th>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_1.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_2.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_3.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">20:30-22:00</th>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_1.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_2.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_3.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '</tbody>'.
            '</table>';
        return $html;
    }


    /**
     * @return string
     */
    private function set_week_2() {
        $html = '';
        $date_1 = date('Y-m-d', strtotime('-1 day'));
        $date_2 = date('Y-m-d', time());
        $date_3 = date('Y-m-d', strtotime('+1 day'));
        $date_4 = date('Y-m-d', strtotime('+2 day'));
        $date_5 = date('Y-m-d', strtotime('+3 day'));
        $date_6 = date('Y-m-d', strtotime('+4 day'));
        $date_7 = date('Y-m-d', strtotime('+5 day'));
        $html .= '<table class="table table-bordered ca-table-week" id="week_1">' .
            '<thead>'.
            '<tr>'.
            '<th>时间</th>'.
            '<th class="ca-time-ago">星期一<br>('.$date_1.')</th>'.
            '<th>星期二<br>('.$date_2.')</th>'.
            '<th>星期三<br>('.$date_3.')</th>'.
            '<th>星期四<br>('.$date_4.')</th>'.
            '<th>星期五<br>('.$date_5.')</th>'.
            '<th>星期六<br>('.$date_6.')</th>'.
            '<th>星期日<br>('.$date_7.')</th>'.
            '</tr>'.
            '</thead>'.
            '<tbody>'.
            '<tr>'.
            '<th scope="row">9:00-10:30</th>'.
            '<td class="ca-time-ago" value="a-'.$date_1.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_2.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_3.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">10:30-12:00</th>'.
            '<td class="ca-time-ago" value="b-'.$date_1.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_2.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_3.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">14:30-16:00</th>'.
            '<td class="ca-time-ago" value="c-'.$date_1.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_2.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_3.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">16:00-17:30</th>'.
            '<td class="ca-time-ago" value="d-'.$date_1.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_2.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_3.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">19:00-20:30</th>'.
            '<td class="ca-time-ago" value="e-'.$date_1.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_2.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_3.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">20:30-22:00</th>'.
            '<td class="ca-time-ago" value="f-'.$date_1.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_2.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_3.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '</tbody>'.
            '</table>';
        return $html;
    }


    /**
     * @return string
     */
    private function set_week_3() {
        $html = '';
        $date_1 = date('Y-m-d', strtotime('-2 day'));
        $date_2 = date('Y-m-d', strtotime('-1 day'));
        $date_3 = date('Y-m-d', time());
        $date_4 = date('Y-m-d', strtotime('+1 day'));
        $date_5 = date('Y-m-d', strtotime('+2 day'));
        $date_6 = date('Y-m-d', strtotime('+3 day'));
        $date_7 = date('Y-m-d', strtotime('+4 day'));
        $html .= '<table class="table table-bordered ca-table-week" id="week_1">' .
            '<thead>'.
            '<tr>'.
            '<th>时间</th>'.
            '<th class="ca-time-ago">星期一<br>('.$date_1.')</th>'.
            '<th class="ca-time-ago">星期二<br>('.$date_2.')</th>'.
            '<th>星期三<br>('.$date_3.')</th>'.
            '<th>星期四<br>('.$date_4.')</th>'.
            '<th>星期五<br>('.$date_5.')</th>'.
            '<th>星期六<br>('.$date_6.')</th>'.
            '<th>星期日<br>('.$date_7.')</th>'.
            '</tr>'.
            '</thead>'.
            '<tbody>'.
            '<tr>'.
            '<th scope="row">9:00-10:30</th>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_3.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">10:30-12:00</th>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_3.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">14:30-16:00</th>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_3.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">16:00-17:30</th>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_3.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">19:00-20:30</th>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_3.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">20:30-22:00</th>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_3.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '</tbody>'.
        '</table>';
        return $html;
    }


    /**
     * @return string
     */
    private function set_week_4() {
        $html = '';
        $date_1 = date('Y-m-d', strtotime('-3 day'));
        $date_2 = date('Y-m-d', strtotime('-2 day'));
        $date_3 = date('Y-m-d', strtotime('-1 day'));
        $date_4 = date('Y-m-d', time());
        $date_5 = date('Y-m-d', strtotime('+1 day'));
        $date_6 = date('Y-m-d', strtotime('+2 day'));
        $date_7 = date('Y-m-d', strtotime('+3 day'));
        $html .= '<table class="table table-bordered ca-table-week" id="week_1">' .
            '<thead>'.
            '<tr>'.
            '<th>时间</th>'.
            '<th class="ca-time-ago">星期一<br>('.$date_1.')</th>'.
            '<th class="ca-time-ago">星期二<br>('.$date_2.')</th>'.
            '<th class="ca-time-ago">星期三<br>('.$date_3.')</th>'.
            '<th>星期四<br>('.$date_4.')</th>'.
            '<th>星期五<br>('.$date_5.')</th>'.
            '<th>星期六<br>('.$date_6.')</th>'.
            '<th>星期日<br>('.$date_7.')</th>'.
            '</tr>'.
            '</thead>'.
            '<tbody>'.
            '<tr>'.
            '<th scope="row">9:00-10:30</th>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">10:30-12:00</th>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">14:30-16:00</th>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">16:00-17:30</th>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">19:00-20:30</th>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">20:30-22:00</th>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '</tbody>'.
            '</table>';
        return $html;
    }


    /**
     * @return string
     */
    private function set_week_5() {
        $html = '';
        $date_1 = date('Y-m-d', strtotime('-4 day'));
        $date_2 = date('Y-m-d', strtotime('-3 day'));
        $date_3 = date('Y-m-d', strtotime('-2 day'));
        $date_4 = date('Y-m-d', strtotime('-1 day'));
        $date_5 = date('Y-m-d', time());
        $date_6 = date('Y-m-d', strtotime('+1 day'));
        $date_7 = date('Y-m-d', strtotime('+2 day'));
        $html .= '<table class="table table-bordered ca-table-week" id="week_1">' .
            '<thead>'.
            '<tr>'.
            '<th>时间</th>'.
            '<th class="ca-time-ago">星期一<br>('.$date_1.')</th>'.
            '<th class="ca-time-ago">星期二<br>('.$date_2.')</th>'.
            '<th class="ca-time-ago">星期三<br>('.$date_3.')</th>'.
            '<th class="ca-time-ago">星期四<br>('.$date_4.')</th>'.
            '<th>星期五<br>('.$date_5.')</th>'.
            '<th>星期六<br>('.$date_6.')</th>'.
            '<th>星期日<br>('.$date_7.')</th>'.
            '</tr>'.
            '</thead>'.
            '<tbody>'.
            '<tr>'.
            '<th scope="row">9:00-10:30</th>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">10:30-12:00</th>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">14:30-16:00</th>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">16:00-17:30</th>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">19:00-20:30</th>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">20:30-22:00</th>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '</tbody>'.
            '</table>';
        return $html;
    }


    /**
     * @return string
     */
    private function set_week_6() {
        $html = '';
        $date_1 = date('Y-m-d', strtotime('-4 day'));
        $date_2 = date('Y-m-d', strtotime('-4 day'));
        $date_3 = date('Y-m-d', strtotime('-3 day'));
        $date_4 = date('Y-m-d', strtotime('-2 day'));
        $date_5 = date('Y-m-d', strtotime('-1 day'));
        $date_6 = date('Y-m-d', time());
        $date_7 = date('Y-m-d', strtotime('+1 day'));
        $html .= '<table class="table table-bordered ca-table-week" id="week_1">' .
            '<thead>'.
            '<tr>'.
            '<th>时间</th>'.
            '<th class="ca-time-ago">星期一<br>('.$date_1.')</th>'.
            '<th class="ca-time-ago">星期二<br>('.$date_2.')</th>'.
            '<th class="ca-time-ago">星期三<br>('.$date_3.')</th>'.
            '<th class="ca-time-ago">星期四<br>('.$date_4.')</th>'.
            '<th class="ca-time-ago">星期五<br>('.$date_5.')</th>'.
            '<th>星期六<br>('.$date_6.')</th>'.
            '<th>星期日<br>('.$date_7.')</th>'.
            '</tr>'.
            '</thead>'.
            '<tbody>'.
            '<tr>'.
            '<th scope="row">9:00-10:30</th>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">10:30-12:00</th>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">14:30-16:00</th>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">16:00-17:30</th>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">19:00-20:30</th>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">20:30-22:00</th>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '</tbody>'.
            '</table>';
        return $html;
    }


    /**
     * @return string
     */
    private function set_week_7() {
        $html = '';
        $date_1 = date('Y-m-d', strtotime('-5 day'));
        $date_2 = date('Y-m-d', strtotime('-4 day'));
        $date_3 = date('Y-m-d', strtotime('-4 day'));
        $date_4 = date('Y-m-d', strtotime('-3 day'));
        $date_5 = date('Y-m-d', strtotime('-2 day'));
        $date_6 = date('Y-m-d', strtotime('-1 day'));
        $date_7 = date('Y-m-d', time());
        $html .= '<table class="table table-bordered ca-table-week" id="week_1">' .
            '<thead>'.
            '<tr>'.
            '<th>时间</th>'.
            '<th class="ca-time-ago">星期一<br>('.$date_1.')</th>'.
            '<th class="ca-time-ago">星期二<br>('.$date_2.')</th>'.
            '<th class="ca-time-ago">星期三<br>('.$date_3.')</th>'.
            '<th class="ca-time-ago">星期四<br>('.$date_4.')</th>'.
            '<th class="ca-time-ago">星期五<br>('.$date_5.')</th>'.
            '<th class="ca-time-ago">星期六<br>('.$date_6.')</th>'.
            '<th>星期日<br>('.$date_7.')</th>'.
            '</tr>'.
            '</thead>'.
            '<tbody>'.
            '<tr>'.
            '<th scope="row">9:00-10:30</th>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">10:30-12:00</th>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">14:30-16:00</th>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">16:00-17:30</th>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">19:00-20:30</th>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">20:30-22:00</th>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '</tbody>'.
            '</table>';
        return $html;
    }


    //从周二开始的一个月时间
    private function set_month_by_2() {
        $html = '';
        $date_1 = date('Y-m-d', strtotime('-1 day'));
        $date_2 = date('Y-m-d', time());
        $date_3 = date('Y-m-d', strtotime('+1 day'));
        $date_4 = date('Y-m-d', strtotime('+2 day'));
        $date_5 = date('Y-m-d', strtotime('+3 day'));
        $date_6 = date('Y-m-d', strtotime('+4 day'));
        $date_7 = date('Y-m-d', strtotime('+5 day'));

        $date_8 = date('Y-m-d', strtotime('+6 day'));
        $date_9 = date('Y-m-d', strtotime('+7 day'));
        $date_10 = date('Y-m-d', strtotime('+8 day'));
        $date_11 = date('Y-m-d', strtotime('+9 day'));
        $date_12 = date('Y-m-d', strtotime('+10 day'));
        $date_13 = date('Y-m-d', strtotime('+11 day'));
        $date_14 = date('Y-m-d', strtotime('+12 day'));

        $date_15 = date('Y-m-d', strtotime('+13 day'));
        $date_16 = date('Y-m-d', strtotime('+14 day'));
        $date_17 = date('Y-m-d', strtotime('+15 day'));
        $date_18 = date('Y-m-d', strtotime('+16 day'));
        $date_19 = date('Y-m-d', strtotime('+17 day'));
        $date_20 = date('Y-m-d', strtotime('+18 day'));
        $date_21 = date('Y-m-d', strtotime('+19 day'));

        $date_22 = date('Y-m-d', strtotime('+20 day'));
        $date_23 = date('Y-m-d', strtotime('+21 day'));
        $date_24 = date('Y-m-d', strtotime('+22 day'));
        $date_25 = date('Y-m-d', strtotime('+23 day'));
        $date_26 = date('Y-m-d', strtotime('+24 day'));
        $date_27 = date('Y-m-d', strtotime('+25 day'));
        $date_28 = date('Y-m-d', strtotime('+26 day'));

        $date_29 = date('Y-m-d', strtotime('+27 day'));
        $date_30 = date('Y-m-d', strtotime('+28 day'));

        $html .= '<table class="table table-bordered ca-table-week" id="week_1">' .
            '<thead>'.
            '<tr>'.
            '<th>时间</th>'.
            '<th class="ca-time-ago">星期一<br>('.$date_1.')</th>'.
            '<th>星期二<br>('.$date_2.')</th>'.
            '<th>星期三<br>('.$date_3.')</th>'.
            '<th>星期四<br>('.$date_4.')</th>'.
            '<th>星期五<br>('.$date_5.')</th>'.
            '<th>星期六<br>('.$date_6.')</th>'.
            '<th>星期日<br>('.$date_7.')</th>'.
            '</tr>'.
            '</thead>'.
            '<tbody>'.
            '<tr>'.
            '<th scope="row">9:00-10:30</th>'.
            '<td class="ca-time-ago" value="a-'.$date_1.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_2.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_3.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">10:30-12:00</th>'.
            '<td class="ca-time-ago" value="b-'.$date_1.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_2.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_3.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">14:30-16:00</th>'.
            '<td class="ca-time-ago" value="c-'.$date_1.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_2.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_3.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">16:00-17:30</th>'.
            '<td class="ca-time-ago" value="d-'.$date_1.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_2.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_3.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">19:00-20:30</th>'.
            '<td class="ca-time-ago" value="e-'.$date_1.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_2.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_3.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">20:30-22:00</th>'.
            '<td class="ca-time-ago" value="f-'.$date_1.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_2.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_3.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '</tbody>'.
            '</table>';

        $html .= '<table class="table table-bordered ca-table-week" id="week_2">' .
            '<thead>'.
            '<tr>'.
            '<th>时间</th>'.
            '<th>星期一<br>('.$date_8.')</th>'.
            '<th>星期二<br>('.$date_9.')</th>'.
            '<th>星期三<br>('.$date_10.')</th>'.
            '<th>星期四<br>('.$date_11.')</th>'.
            '<th>星期五<br>('.$date_12.')</th>'.
            '<th>星期六<br>('.$date_13.')</th>'.
            '<th>星期日<br>('.$date_14.')</th>'.
            '</tr>'.
            '</thead>'.
            '<tbody>'.
            '<tr>'.
            '<th scope="row">9:00-10:30</th>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_8.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_9.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_10.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_11.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_12.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_13.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_14.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">10:30-12:00</th>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_8.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_9.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_10.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_11.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_12.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_13.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_14.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">14:30-16:00</th>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_8.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_9.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_10.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_11.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_12.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_13.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_14.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">16:00-17:30</th>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_8.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_9.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_10.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_11.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_12.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_13.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_14.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">19:00-20:30</th>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_8.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_9.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_10.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_11.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_12.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_13.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_14.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">20:30-22:00</th>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_8.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_9.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_10.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_11.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_12.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_13.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_14.'">不可预约</td>'.
            '</tr>'.
            '</tbody>'.
            '</table>';

        $html .= '<table class="table table-bordered ca-table-week" id="week_3">' .
            '<thead>'.
            '<tr>'.
            '<th>时间</th>'.
            '<th>星期一<br>('.$date_15.')</th>'.
            '<th>星期二<br>('.$date_16.')</th>'.
            '<th>星期三<br>('.$date_17.')</th>'.
            '<th>星期四<br>('.$date_18.')</th>'.
            '<th>星期五<br>('.$date_19.')</th>'.
            '<th>星期六<br>('.$date_20.')</th>'.
            '<th>星期日<br>('.$date_21.')</th>'.
            '</tr>'.
            '</thead>'.
            '<tbody>'.
            '<tr>'.
            '<th scope="row">9:00-10:30</th>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_15.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_16.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_17.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_18.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_19.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_20.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_21.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">10:30-12:00</th>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_15.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_16.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_17.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_18.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_19.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_20.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_21.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">14:30-16:00</th>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_15.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_16.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_17.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_18.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_19.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_20.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_21.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">16:00-17:30</th>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_15.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_16.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_17.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_18.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_19.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_20.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_21.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">19:00-20:30</th>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_15.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_16.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_17.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_18.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_19.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_20.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_21.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">20:30-22:00</th>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_15.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_16.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_17.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_18.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_19.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_20.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_21.'">不可预约</td>'.
            '</tr>'.
            '</tbody>'.
            '</table>';

        $html .= '<table class="table table-bordered ca-table-week" id="week_4">' .
            '<thead>'.
            '<tr>'.
            '<th>时间</th>'.
            '<th>星期一<br>('.$date_22.')</th>'.
            '<th>星期二<br>('.$date_23.')</th>'.
            '<th>星期三<br>('.$date_24.')</th>'.
            '<th>星期四<br>('.$date_25.')</th>'.
            '<th>星期五<br>('.$date_26.')</th>'.
            '<th>星期六<br>('.$date_27.')</th>'.
            '<th>星期日<br>('.$date_28.')</th>'.
            '</tr>'.
            '</thead>'.
            '<tbody>'.
            '<tr>'.
            '<th scope="row">9:00-10:30</th>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_22.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_23.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_24.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_25.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_26.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_27.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_28.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">10:30-12:00</th>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_22.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_23.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_24.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_25.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_26.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_27.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_28.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">14:30-16:00</th>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_22.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_23.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_24.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_25.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_26.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_27.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_28.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">16:00-17:30</th>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_22.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_23.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_24.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_25.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_26.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_27.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_28.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">19:00-20:30</th>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_22.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_23.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_24.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_25.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_26.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_27.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_28.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">20:30-22:00</th>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_22.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_23.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_24.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_25.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_26.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_27.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_28.'">不可预约</td>'.
            '</tr>'.
            '</tbody>'.
            '</table>';

        $html .= '<table class="table table-bordered ca-table-week" id="week_5">' .
            '<thead>'.
            '<tr>'.
            '<th>时间</th>'.
            '<th>星期一<br>('.$date_29.')</th>'.
            '<th>星期二<br>('.$date_30.')</th>'.
            //'<th>星期三<br>('.$date_10.')</th>'.
            //'<th>星期四<br>('.$date_11.')</th>'.
            //'<th>星期五<br>('.$date_12.')</th>'.
            //'<th>星期六<br>('.$date_13.')</th>'.
            //'<th>星期日<br>('.$date_14.')</th>'.
            '</tr>'.
            '</thead>'.
            '<tbody>'.
            '<tr>'.
            '<th scope="row">9:00-10:30</th>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_29.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_30.'">不可预约</td>'.
            //'<td class="ca-td-time ca-no-free-time" value="a-'.$date_10.'">不可预约</td>'.
            //'<td class="ca-td-time ca-no-free-time" value="a-'.$date_11.'">不可预约</td>'.
            //'<td class="ca-td-time ca-no-free-time" value="a-'.$date_12.'">不可预约</td>'.
            //'<td class="ca-td-time ca-no-free-time" value="a-'.$date_13.'">不可预约</td>'.
            //'<td class="ca-td-time ca-no-free-time" value="a-'.$date_14.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">10:30-12:00</th>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_29.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_30.'">不可预约</td>'.
            //'<td class="ca-td-time ca-no-free-time" value="b-'.$date_10.'">不可预约</td>'.
            //'<td class="ca-td-time ca-no-free-time" value="b-'.$date_11.'">不可预约</td>'.
            //'<td class="ca-td-time ca-no-free-time" value="b-'.$date_12.'">不可预约</td>'.
            //'<td class="ca-td-time ca-no-free-time" value="b-'.$date_13.'">不可预约</td>'.
            //'<td class="ca-td-time ca-no-free-time" value="b-'.$date_14.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">14:30-16:00</th>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_29.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_30.'">不可预约</td>'.
            //'<td class="ca-td-time ca-no-free-time" value="c-'.$date_10.'">不可预约</td>'.
            //'<td class="ca-td-time ca-no-free-time" value="c-'.$date_11.'">不可预约</td>'.
            //'<td class="ca-td-time ca-no-free-time" value="c-'.$date_12.'">不可预约</td>'.
            //'<td class="ca-td-time ca-no-free-time" value="c-'.$date_13.'">不可预约</td>'.
            //'<td class="ca-td-time ca-no-free-time" value="c-'.$date_14.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">16:00-17:30</th>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_29.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_30.'">不可预约</td>'.
            //'<td class="ca-td-time ca-no-free-time" value="d-'.$date_10.'">不可预约</td>'.
            //'<td class="ca-td-time ca-no-free-time" value="d-'.$date_11.'">不可预约</td>'.
            //'<td class="ca-td-time ca-no-free-time" value="d-'.$date_12.'">不可预约</td>'.
            //'<td class="ca-td-time ca-no-free-time" value="d-'.$date_13.'">不可预约</td>'.
            //'<td class="ca-td-time ca-no-free-time" value="d-'.$date_14.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">19:00-20:30</th>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_29.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_30.'">不可预约</td>'.
            //'<td class="ca-td-time ca-no-free-time" value="e-'.$date_10.'">不可预约</td>'.
            //'<td class="ca-td-time ca-no-free-time" value="e-'.$date_11.'">不可预约</td>'.
            //'<td class="ca-td-time ca-no-free-time" value="e-'.$date_12.'">不可预约</td>'.
            //'<td class="ca-td-time ca-no-free-time" value="e-'.$date_13.'">不可预约</td>'.
            //'<td class="ca-td-time ca-no-free-time" value="e-'.$date_14.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">20:30-22:00</th>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_29.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_30.'">不可预约</td>'.
            //'<td class="ca-td-time ca-no-free-time" value="f-'.$date_10.'">不可预约</td>'.
            //'<td class="ca-td-time ca-no-free-time" value="f-'.$date_11.'">不可预约</td>'.
            //'<td class="ca-td-time ca-no-free-time" value="f-'.$date_12.'">不可预约</td>'.
            //'<td class="ca-td-time ca-no-free-time" value="f-'.$date_13.'">不可预约</td>'.
            //'<td class="ca-td-time ca-no-free-time" value="f-'.$date_14.'">不可预约</td>'.
            '</tr>'.
            '</tbody>'.
            '</table>';
        return $html;
    }

}