<?php
/*
 * 预约咨询师
 */
namespace Home\Controller;
use Think\Controller;
class AppointmentController extends Controller {


    // 咨询师列表
//    public function index() {
//
//        $data = [1,2,3,4,5,6,7,8,9,0,1];
//
//        $this->assign('data', $data);
//        $this->display();
//    }


    public function  _before_index() {
        $uid = function_is_login();
        if (!$uid) {
            $this->error('请您先登录后再预约', '/Login/type');
        }
    }


    // 预约咨询师
    public function index() {
        if ($_GET && isset($_GET['tid'])) {
            $Teacherinfo = M('teacherinfo');
            $where_teacherinfo['account_id'] = $_GET['tid'];
            $teacherinfo = $Teacherinfo->where($where_teacherinfo)->find();
            $data['title'] = $teacherinfo['name'];
            $data['teacherinfo'] = $teacherinfo;
//            var_dump($data['teacherinfo']);
//            die();
            $this->assign('data', $data);
            $this->display();
        }
    }

}