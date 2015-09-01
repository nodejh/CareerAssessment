<?php
/*
 * 会员注册
 */
namespace Home\Controller;
use Think\Controller;
class SignController extends Controller {


    // 会员注册页面
    public function index(){
        if (!function_is_login()) {
            $data['title'] = '会员注册－蓝鲸教育咨询';
            $this->assign($data);
            $this->display();
        } else if (function_login_type() == function_user_number()) {
            $this->redirect('Admin/User/index', '', 0);
        } else if (function_login_type() == function_teacher_number()) {
            $this->redirect('Admin/Teacher/index', '', 0);
        } else {
            function_set_logout();
            $data['title'] = '会员注册－蓝鲸教育咨询';
            $this->assign($data);
            $this->display();
        }
    }

    // 完善来访者信息页面
    public function user() {
        $uid = function_is_login();
        if ($uid) {
            $Userinfo = M('userinfo');
            $where['account_id'] = $uid;
            $exit_userinfo = $Userinfo->where($where)->find();

            if ($exit_userinfo) {
                $this->redirect('Admin/User/index', '', 0);
            } else {
                $data['title'] = '完善个人信息';
                $this->assign($data);
                $this->display();
            }
        } else {
            $this->redirect('Login/index', '', 0);
        }
    }


    // 完善咨询师信息页面一 -- 基本信息
    public function teacher() {
        $uid = function_is_login();

        if ($uid) {
            $Teacherinfo = M('teacherinfo');
            $where['account_id'] = $uid;
            $exit_teacherinfo = $Teacherinfo->where($where)->find();

            if ($exit_teacherinfo) {

                if ($exit_teacherinfo['picture'] == NULL ||
                    $exit_teacherinfo['free_time'] == NULL ||
                    $exit_teacherinfo['introduction'] == NULL) {

                    $this->redirect('Signup/complete', '', 0);

                } else {
                    $this->redirect('Admin/Teacher/index', '', 0);
                }

            } else {
                $data['title'] = '完善个人信息';
                $this->assign($data);
                $this->display();
            }

        } else {
            $this->redirect('Login/index', '', 0);
        }
    }


    // 完善咨询师信息页面二 -- 头像、简介、空闲时间
    public function complete() {
        $uid = function_is_login();
        if ($uid) {

            $Teacherinfo = M('teacherinfo');
            $where['account_id'] = $uid;
            $exit_teacherinfo = $Teacherinfo->where($where)->find();

            if ($exit_teacherinfo) {

                if ($exit_teacherinfo['picture'] == NULL ||
                    $exit_teacherinfo['free_time'] == NULL ||
                    $exit_teacherinfo['introduction'] == NULL) {

                    $data['title'] = '添加个人简介';
                    $this->assign($data);
                    $this->display();

                } else {
                    $this->redirect('Admin/Teacher/index', '', 0);
                }

            } else {
                $this->redirect('Signup/teacher', '', 0);
            }

        } else {
            $this->redirect('Login/index', '', 0);
        }
    }


    // 来访者注册
    public function sign_user() {
        $data['status']  = 1000;
        $data['message'] = '非法操作';

        if ($_POST) {
            $number = $_POST['number'];


//            $phone = $_POST['phone'];
//            $password = $_POST['password'];
//            $password_confirm = $_POST['password_confirm'];
//            $data['status']  = 2001;
//            $data['message'] = '手机号格式错误';
//            if (reg_exp_phone($phone)) {
//                $data['status']  = 2002;
//                $data['message'] = '密码格式错误';
//                if (reg_exp_password($password)) {
//                    $data['status']  = 2003;
//                    $data['message'] = '两次密码不一致';
//                    if ($password == $password_confirm) {
//                        $Account = M('account');
//                        $where_exit_phone['phone'] = $phone;
//                        $exit_phone = $Account->where($where_exit_phone)->find();
//                        $data['status']  = 2004;
//                        $data['message'] = '手机号已存在';
//                        if (!$exit_phone) {
//                            $insert_data['phone'] = $phone;
//                            $insert_data['password'] = function_encrypt($password);
//                            $insert_data['type'] = function_user_number();
//                            $insert_data['date'] = time();
//                            $insert_account = $Account->data($insert_data)->add();
//                            $data['status']  = 2005;
//                            $data['message'] = '注册失败';
//                            if ($insert_account) {
//                                $data['status']  = 0;
//                                $data['message'] = '注册成功';
//                                $data['url'] = U('Signup/user');
//                                function_set_login_in($insert_account, function_user_number());
//                            }
//                        }
//                    }
//                }
//            }
        }
        $this->ajaxReturn($data);
    }


    // 咨询师注册
    public function signup_teacher() {
        $data['status']  = 1000;
        $data['message'] = '非法操作';
        if ($_POST) {
            $phone = $_POST['phone'];
            $password = $_POST['password'];
            $password_confirm = $_POST['password_confirm'];
            $data['status']  = 2001;
            $data['message'] = '手机号格式错误';
            if (reg_exp_phone($phone)) {
                $data['status']  = 2002;
                $data['message'] = '密码格式错误';
                if (reg_exp_password($password)) {
                    $data['status']  = 2003;
                    $data['message'] = '两次密码不一致';
                    if ($password == $password_confirm) {
                        $Account = M('account');
                        $where_exit_phone['phone'] = $phone;
                        $exit_phone = $Account->where($where_exit_phone)->find();
                        $data['status']  = 2004;
                        $data['message'] = '手机号已存在';
                        if (!$exit_phone) {
                            $insert_data['phone'] = $phone;
                            $insert_data['password'] = function_encrypt($password);
                            $insert_data['type'] = function_teacher_number();
                            $insert_data['date'] = time();
                            $insert_account = $Account->data($insert_data)->add();
                            $data['status']  = 2005;
                            $data['message'] = '注册失败';
                            if ($insert_account) {
                                $data['status']  = 0;
                                $data['message'] = '注册成功';
                                $data['url'] = U('Signup/teacher');
                                function_set_login_in($insert_account, function_teacher_number());
                            }
                        }
                    }
                }
            }
        }
        $this->ajaxReturn($data);
    }



    // 注册操作－完善会员信息
    public function signup_userinfo() {
        $data['status']  = 3000;
        $data['message'] = '非法操作';
        if (function_is_login() && function_login_type() == function_user_number()) {
            $Userinfo = M('userinfo');
            $where_account['account_id'] = function_is_login();
            $exit_account = $Userinfo->where($where_account)->find();
            if ($exit_account) {
                $this->redirect('Admin/User/index', '', 0);
                exit();
            } else {
                $data['status']  = 3001;
                $data['message'] = '提交方式错误';

                if ($_POST) {
                    $post_name = isset($_POST['name']) ? $_POST['name'] : '';
                    $data['status']  = 3002;
                    $data['message'] = '姓名格式错误';

                    if ($post_name && reg_exp_nomarks($post_name)) {
                        $insert_data['name'] = $post_name;
                        $post_gender = isset($_POST['gender']) ? $_POST['gender'] : '';
                        $data['status']  = 3003;
                        $data['message'] = '性别格式错误';

                        if ($post_gender && ($post_gender ==1 || $post_gender ==2)) {
                            $insert_data['gender'] = intval($post_gender);
                            $post_email = isset($_POST['email']) ? $_POST['email'] : '';
                            $data['status']  = 3004;
                            $data['message'] = '邮箱格式错误';

                            if ($post_email && reg_exp_email($post_email)) {
                                $insert_data['email'] = $post_email;
                                $post_city = isset($_POST['city']) ? $_POST['city'] : '';
                                $data['status']  = 3005;
                                $data['message'] = '地点格式错误';

                                if ($post_city && reg_exp_nomarks($post_city)) {
                                    $insert_data['city'] = $post_city;
                                    $post_status = isset($_POST['status']) ? $_POST['status'] : '';
                                    $data['status']  = 3006;
                                    $data['message'] = '学习或工作状态格式错误';

                                    if ($post_status == 1 || $post_status == 2 || $post_status == 3 || $post_status == 4 || $post_status == 5 || $post_status == 6) {
                                        $insert_data['status'] = $post_status;

                                        if ($post_status == 1) {
                                            $post_school = isset($_POST['school']) ? $_POST['school'] : '';
                                            $data['status']  = 3007;
                                            $data['message'] = '学校格式错误';
                                            if ($post_school && reg_exp_nomarks($post_school)) {
                                                $insert_data['school'] = $post_school;
                                                $data['status']  = 3008;
                                                $data['message'] = '学生类别格式错误';
                                                $post_student_type = isset($_POST['student_type']) ? $_POST['student_type'] : '';

                                                if ($post_student_type && reg_exp_nomarks($post_student_type)) {
                                                    $insert_data['student_type'] = $post_student_type;
                                                    $data['status']  = 3010;
                                                    $data['message'] = '数据正确';
                                                }
                                            }

                                        } elseif ($post_status == 2) {
                                            $post_school = isset($_POST['school']) ? $_POST['school'] : '';
                                            $data['status']  = 3007;
                                            $data['message'] = '学校格式错误';

                                            if ($post_school && reg_exp_nomarks($post_school)) {
                                                $insert_data['school'] = $post_school;
                                                $data['status']  = 3008;
                                                $data['message'] = '学生类别格式错误';
                                                $post_student_type = isset($_POST['student_type']) ? $_POST['student_type'] : '';

                                                if ($post_student_type && reg_exp_nomarks($post_student_type)) {
                                                    $insert_data['student_type'] = $post_student_type;
                                                    $data['status']  = 3009;
                                                    $data['message'] = '专业格式错误';

                                                    $post_college = isset($_POST['college']) ? $_POST['college'] : '';
                                                    if ($post_college && reg_exp_nomarks($post_college)) {
                                                        $insert_data['college'] = $post_college;
                                                        $data['status']  = 3100;
                                                        $data['message'] = '数据正确';
                                                    }
                                                }
                                            }
                                        } else {
                                            $data['status']  = 3100;
                                            $data['message'] = '数据正确';
                                        }

                                        if ($data['status'] == 3100) {
                                            $insert_data['account_id'] = function_is_login();
                                            $data['insert'] = $insert_data;
                                            $insert_result = $Userinfo->data($insert_data)->add();
                                            $data['status']  = 3011;
                                            $data['message'] = '写入数据库失败';
                                            if ($insert_result) {
                                                $data['status']  = 0;
                                                $data['url'] = U('Admin/User/index');
                                                $data['message'] = '注册成功';
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        $this->ajaxReturn($data);
    }

    // 注册操作－完善咨询师信息
    public function signup_teacherinfo() {
        $data['status']  = 3100;
        $data['message'] = '非法操作';

        if (function_is_login() && function_login_type() == function_teacher_number()) {
            $Teacherinfo = M('teacherinfo');
            $where_account['account_id'] = function_is_login();
            $exit_account = $Teacherinfo->where($where_account)->find();
            if ($exit_account) {
                $this->redirect('Admin/Teacher/index', '', 0);
                exit();

            } else {
                $data['status']  = 3101;
                $data['message'] = '提交方式错误';

                if ($_POST) {
                    $post_name = isset($_POST['name']) ? $_POST['name'] : '';
                    $data['status']  = 3102;
                    $data['message'] = '姓名格式错误';

                    if ($post_name && reg_exp_nomarks($post_name)) {
                        $insert_data['name'] = $post_name;
                        $post_gender = isset($_POST['gender']) ? $_POST['gender'] : '';
                        $data['status']  = 3103;
                        $data['message'] = '性别格式错误';

                        if ($post_gender && $post_gender && ($post_gender ==1 || $post_gender ==2)) {
                            $insert_data['gender'] = intval($post_gender);
                            $post_email = isset($_POST['email']) ? $_POST['email'] : '';
                            $data['status']  = 3104;
                            $data['message'] = '邮箱格式错误';

                            if ($post_email && reg_exp_email($post_email)) {
                                $insert_data['email'] = $post_email;
                                $post_city = isset($_POST['city']) ? $_POST['city'] : '';
                                $data['status']  = 3105;
                                $data['message'] = '地点格式错误';

                                if ($post_city && reg_exp_nomarks($post_city)) {
                                    $insert_data['city'] = $post_city;
                                    $post_service_type = isset($_POST['service_type']) ? $_POST['service_type'] : '';
                                    $data['status']  = 3106;
                                    $data['message'] = '服务类型格式错误';

                                    if ($post_service_type && $post_service_type != '000000') {
                                        $insert_data['service_type'] = $post_service_type;
                                        $post_certificate = isset($_POST['certificate']) ? $_POST['certificate'] : '';

                                        if ($post_certificate) {
                                            $insert_data['certificate'] = $post_certificate;
                                        }

                                        $data['status']  = 3110;
                                        $data['message'] = '数据正确';

                                        if ($data['status'] == 3110) {
                                            $insert_data['account_id'] = function_is_login();
                                            $insert_result = $Teacherinfo->data($insert_data)->add();
                                            $data['status']  = 3107;
                                            $data['message'] = '写入数据库失败';
                                            if ($insert_result) {
                                                $data['status']  = 0;
                                                $data['url'] = U('Home/Signup/complete');
                                                $data['message'] = '写入成功';
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        $this->ajaxReturn($data);
    }




}