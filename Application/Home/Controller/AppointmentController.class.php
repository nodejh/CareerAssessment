<?php
/*
 * 预约咨询师
 */
namespace Home\Controller;
use Think\Controller;
class AppointmentController extends Controller {


    // 咨询师列表
    public function index() {

        $data = [1,2,3,4,5,6,7,8,9,0,1];

        $this->assign('data', $data);
        $this->display();
    }


}