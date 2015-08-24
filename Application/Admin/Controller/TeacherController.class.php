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

                if ($exit_teacherinfo['picture'] == NULL ||
                    $exit_teacherinfo['free_time'] == NULL ||
                    $exit_teacherinfo['introduction'] == NULL) {

                    $this->redirect('Home/Signup/complete', '', 0);

                } else {

                    $data['title'] = '个人中心';
                    $data['teacherinfo'] = $exit_teacherinfo;
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



}