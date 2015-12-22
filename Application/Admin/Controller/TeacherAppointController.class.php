<?php
// +----------------------------------------------------------------------
// | Teacher 预约后台
// +----------------------------------------------------------------------

namespace Admin\Controller;
use Think\Controller;

/**
 * Class TeacherAppointController
 * @package Admin\Controller
 */
class TeacherAppointController extends BaseController {

    public function appoint_table () {
        $id = $_SESSION['id'];

        $Appoint = M('appoint');
        $where['teacher_id'] = $id;
        $where['status'] = 2;
        $appoint_list = $Appoint->where($where)->select();

        $User = M('user');
        if ($appoint_list) {
            foreach($appoint_list as $k => $v) {
                $where_user['account_id'] = $v['user_id'];
                $user_info = $User->where($where_user)->find();
                $appoint_list[$k]['user'] = $user_info;
            }
        }
        $res['data'] = $appoint_list;
        echo json_encode($res);
    }


    /**
     * 未完成预约
     */
    public function appoint_incomplete() {
        $this->is_teacher();
        // appoint info
        $Appoint = M('appoint');
        $appoint_where['teacher_id'] = $_SESSION['id'];
        $appoint_where['_string'] = 'status=10000 OR status=10001 OR status=10002';
        $this->_data['teacher']['appoint_list'] = $Appoint->where($appoint_where)->order('save_time desc')->select();
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
        $this->assign($this->_data);
        $this->display();
    }



    /**
     * 已完成预约
     */
    public function appoint_complete() {
        $this->is_teacher();
        // appoint info
        $Appoint = M('appoint');
        $appoint_where['teacher_id'] = $_SESSION['id'];
        $appoint_where['_string'] = 'status=20 OR status=21';
        $this->_data['teacher']['appoint_list'] = $Appoint->where($appoint_where)->order('save_time desc')->select();
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
        $this->assign($this->_data);
        $this->display();
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
            $today_week = date('N', time());
            switch ($today_week) {
                case '1':
                    $this->_data['html'] = $this->set_month_by_1();
                    break;
                case '2':
                    $this->_data['html'] = $this->set_month_by_2();
                    break;
                case '3':
                    $this->_data['html'] = $this->set_month_by_3();
                    break;
                case '4':
                    $this->_data['html'] = $this->set_month_by_4();
                    break;
                case '5':
                    $this->_data['html'] = $this->set_month_by_5();
                    break;
                case '6':
                    $this->_data['html'] = $this->set_month_by_6();
                    break;
                case '7':
                    $this->_data['html'] = $this->set_month_by_7();
                    break;
            }

            $this->_data['appoint_confirm_url'] = U('appoint_confirm');
            $this->_data['api']['get_teacher_can_not_appoint_time'] = U('Api/Index/get_teacher_can_not_appoint_time');
            $this->_data['api']['data']['teacher_id'] = $_SESSION['id'];
            $this->assign($this->_data);
            $this->display();

        } else {
            $this->redirect('appoint', '', 0);
        }
    }



    public function appoint_details_view() {
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
            $today_week = date('N', time());
            switch ($today_week) {
                case '1':
                    $this->_data['html'] = $this->set_month_by_1();
                    break;
                case '2':
                    $this->_data['html'] = $this->set_month_by_2();
                    break;
                case '3':
                    $this->_data['html'] = $this->set_month_by_3();
                    break;
                case '4':
                    $this->_data['html'] = $this->set_month_by_4();
                    break;
                case '5':
                    $this->_data['html'] = $this->set_month_by_5();
                    break;
                case '6':
                    $this->_data['html'] = $this->set_month_by_6();
                    break;
                case '7':
                    $this->_data['html'] = $this->set_month_by_7();
                    break;
            }

            $this->_data['appoint_confirm_url'] = U('appoint_confirm');
            $this->_data['api']['get_teacher_can_not_appoint_time'] = U('Api/Index/get_teacher_can_not_appoint_time');
            $this->_data['api']['data']['teacher_id'] = $_SESSION['id'];
            $this->assign($this->_data);
            $this->display();

        } else {
            $this->redirect('appoint', '', 0);
        }
    }


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
            $appoint_data['status'] = 10001;
            $appoint_data['teacher_confirm_date'] = $time;
            $appoint_data['teacher_confirm_time'] = time();
            $appoint_result = $Appoint->where($appoint_where)->data($appoint_data)->save();
            //var_dump($appoint_result);
            if ($appoint_result) {

                $result['status'] = 0;
                $result['message'] = 'success';
                $this->ajaxReturn($result);

            } else {
                $result['status'] = -1;
                $result['message'] = 'fail';
                $this->ajaxReturn($result);
            }
        }
    }




}