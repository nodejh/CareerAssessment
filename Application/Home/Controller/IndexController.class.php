<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {

    public function index(){
        //var_dump($_SESSION);
        //function_set_logout();
        $uid = function_is_login();
        if ($uid) {
            $utype = function_login_type();
            if ($utype == function_user_number()) {
                $data['utype'] = 'user';
                $account = M('account');

            } elseif ($utype == function_teacher_number()) {
                $data['utype'] = 'teacher';

            } elseif ($utype == function_admin_number()) {
                $data['utype'] = 'admin';

            } else {
                function_set_logout();
            }
            $data['name'] = 'test';
        }
        $data['title'] = '蓝鲸教育咨询';
        $Teacher = M('teacherinfo');
        $list = $Teacher->order('teacherinfo_id desc')->limit(16)->select();
        if ($list) {

            $data['list'] = $list;
           // var_dump($data['list']);
            $this->assign('list', $data['list']);
            $this->assign($data);
            $this->display();
        }

    }


    // 退出登录
    public function logout() {
        function_set_logout();
        $this->redirect('Index/index', '', 0);
    }
}