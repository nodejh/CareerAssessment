<?php
namespace Admin\Controller;
use Think\Controller;
class TeacherController extends Controller {


    // 咨询师个人中心
    public function index(){
        $uid = function_is_login();

        if ($uid) {
            $Teacherinfo = M('teacherinfo');
            $where['account_id'] = $uid;
            $exit_teacherinfo = $Teacherinfo->where($where)->find();

            if ($exit_teacherinfo) {

                if ($exit_teacherinfo['avatar'] == NULL ||
                    $exit_teacherinfo['introduction'] == NULL) {
                    $this->redirect('Home/Signup/complete', '', 0);

                } else {

                    $data['title'] = '个人中心';
                    $data['teacherinfo'] = $exit_teacherinfo;

                    $Account = M('account');
                    $account_info = $Account->where($where)->find();

                    if ($account_info['password'] == null) {
                        $data['password'] = get_default_password();
                        $data['phone'] = $account_info['phone'];
                        $update_data['password'] = $data['password'];
                        $update_result = $Account->where($where)->data($update_data)->save();
                        if (!$update_result) {
                            $this->error('设置默认密码失败，请手动设置登录密码', '/Teacher/password');
                        }
                    }
                    $this->assign($data);
                    $this->display();
                }

            } else {
                $this->redirect('Home/Signup/teacher', '', 0);
            }

        } else {
            function_set_logout();
            $this->redirect('Home/Login/index', '', 0);
        }
    }


    public function _before_password(){
        $uid = function_is_login();

        if ($uid) {
            $Teacherinfo = M('teacherinfo');
            $where['account_id'] = $uid;
            $exit_teacherinfo = $Teacherinfo->where($where)->find();

            if ($exit_teacherinfo) {

                if ($exit_teacherinfo['avatar'] == NULL ||
                    $exit_teacherinfo['introduction'] == NULL) {
                    $this->redirect('Home/Signup/complete', '', 0);

                }
            } else {
                $this->redirect('Home/Sign/teacher', '', 0);
            }

        } else {
            function_set_logout();
            $this->redirect('Home/Login/index', '', 0);
        }
    }


    // 修改密码
    public function password() {
        $data['title'] = '修改密码';
        $this->assign($data);
        $this->display();
    }


    // 时间表
    public function time() {
        $data['title'] = '时间表';

        $data['date']['week'] = date('l');
        $data['date']['year'] = date('Y', time());
        $data['date']['month'] = date('m', time());
        $data['date']['day'] = date('d', time());

        $this->assign($data);
        $this->display();
    }


    // 个人资料
    public function profile() {
        $uid = function_teacher_number();

        $data['title'] = '个人资料';
        $Account = M('account');
        $where_account['account_id'] = $uid;
        $account_info = $Account->where($where_account)->find();
        if ($account_info) {
            $data['account'] = $account_info;
        }

        $Teacherinfo = M('teacherinfo');
        $where_teacher['account_id'] = $uid;
        $teacherinfo_info = $Account->where($where_teacher)->find();
        if ($teacherinfo_info) {
            $data['teacherinfo'] = $teacherinfo_info;
        }

        if (isset($data['account']) && isset($data['teacherinfo'])) {
            $this->assign($data);
            $this->display();
        }
    }


}