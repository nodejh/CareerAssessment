<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {

    public function index(){
        $data = [];
        $is_login = function_is_login();
        if ($is_login) {
            $User = M('user');
            $user_info = $User->where('user_id = ' . $is_login);
            $data['user_info'] = $user_info;
        } else {
            $data['user_info'] = '';
        }

        $this->assign($data);
        $this->display();
    }
}