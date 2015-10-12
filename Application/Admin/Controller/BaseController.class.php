<?php
// +----------------------------------------------------------------------
// | 管理员后台初始化
// +----------------------------------------------------------------------

namespace Admin\Controller;
use Think\Controller;

/**
 * 登录验证
 * @package Admin\Controller
 */
class BaseController extends Controller {


    public $_data;

    /**
     * initialize redirect url
     */

    public function __construct() {

        parent::__construct();
        if (!($this->is_login() && $this->login_type())) {
            // not login. redirect to login page
            $this->redirect('Home/Login/index', '', 0);

        } else {

            //if (isset($_SESSION['f'])) {
            //    // first time to login. suggest to change the password
            //    $this->_data['first'] = '1';
            //}

            switch ($this->login_type()) {
                case 1:
                    // login type of user
                    $data = $this->get_user_info($_SESSION['id']);
                    //var_dump($data);
                    if ($data) {

                        //TODO 当未填写哪些信息的时候，显示 “请完善个人资料”
                        if (
                            //is_null($data['phone']) ||
                            is_null($data['name'])
                            //is_null($data['email'])
                            //is_null($data['gender']) ||
                            //is_null($data['status']) ||
                            //is_null($data['city'])
                        ) {

                            $this->_data['nav']['profile'] = 1; // 未完善信息，需要完善信息
                        } else {
                            $this->_data['nav']['profile'] = 0;
                        }
                        // 咨询师已确认某预约，消息提醒
                        $appoint_confirm_number = 0;
                        foreach ($data['appoint_list'] as $v) {
                            if ($v['status'] == 1) {
                                $appoint_confirm_number += 1;
                            }
                        }
                        $this->_data['nav']['appoint'] = $appoint_confirm_number;
                        $this->_data['user'] = $data;
                    } else {
                        $this->_data['user'] = 0;
                    }
                    break;
                case 2:
                    // login type of Teacher
                    $data = $this->get_teacher_info($_SESSION['id']);
                    if ($data) {

                        if (
                            //is_null($data['phone']) ||
                            is_null($data['name']) ||
                            //is_null($data['email'])
                            //is_null($data['gender']) ||
                            is_null($data['avatar'])
                            //is_null($data['city'])
                        ) {
                            $this->_data['nav']['profile'] = 1; // 未完善信息，需要完善信息
                        } else {
                            $this->_data['nav']['profile'] = 0;
                        }
                        if (!$data['free_time']) {
                            $this->_data['nav']['free_time'] = 1;
                        } else {
                            $this->_data['nav']['free_time'] = 0;
                        }
                        // 有新来访者预约预约，消息提醒，显示所有未确定的预约总数
                        $appoint_number = 0;
                        foreach ($data['appoint_list'] as $v) {
                            if ($v['status'] == 0) {
                                $appoint_number += 1;
                            }
                        }
                        $this->_data['nav']['appoint'] = $appoint_number;
                        $this->_data['teacher'] = $data;

                    } else {
                        $this->_data['teacher'] = 0;
                    }
                    break;
                case 3:
                    // login type of admin
                    break;
            }
        }
    }


    /**
     * 是否登录
     * @return boolean true:yes; false:no
     */
    public function is_login() {

        if (isset($_SESSION['id'])) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * the type of logged in user
     * @return number 0:login failed; 1 user; 2 teacher; 3 administrator
     */
    public function login_type() {

        if ($this->is_login()) {
            if (isset($_SESSION['type'])) {
                return $_SESSION['type'];
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    /**
     * get user info
     * @param number:id user's account_id
     * @return array user info; 0 failed
     */
    protected function get_user_info ($id) {
        $Account = M('account');
        $User = M('user');
        $where['account_id'] = ':account_id';
        $data['account'] = $Account->where($where)->bind(':account_id',$id)->find();
        $data['user'] = $User->where($where)->bind(':account_id', $id)->find();

        $Appoint = M('appoint');
        $appoint_where['user_id'] = $id;
        $data['appoint'] = $Appoint->where($appoint_where)->select();
        //var_dump($data['appoint']);
        //die();

        if ($data['account']) {

            $result['account_id'] = $data['account']['account_id'];
            $result['phone'] = $data['account']['phone'];
            $result['card_id'] = $data['account']['card_id'];
            $result['account_id'] = $data['account']['account_id'];

            if ($data['user']) {

                // 判断是否xx考生类型，如果是，则返回给前台checked
                $data['user']['type_1'] = '';
                $data['user']['type_2'] = '';
                $data['user']['type_3'] = '';
                $data['user']['type_4'] = '';

                if ($data['user']['student_type'] != NULL) {

                    $data['user']['type_temp'] = explode(',', $data['user']['student_type']);
                    foreach ($data['user']['type_temp'] as $temp) {
                        if ($temp == '艺体生') {
                            $data['user']['type_1'] = 'checked';
                        } else if ($temp == '少数名族考生') {
                            $data['user']['type_2'] = 'checked';
                        } else if ($temp == '国家专项计划') {
                            $data['user']['type_3'] = 'checked';
                        } else if ($temp == '军校或国防生') {
                            $data['user']['type_4'] = 'checked';
                        }
                    }

                }
            } else {
                $data['user'] = [];
            }

            $result['account_id'] = $data['account']['account_id'];
            $result['phone'] = $data['account']['phone'];
            $result['password'] = $data['account']['password'];
            $result['card_id'] = $data['account']['card_id'];
            $result['register_time'] = $data['account']['register_time'];
            $result['name'] = $data['user']['name'];
            $result['email'] = $data['user']['email'];
            $result['gender'] = $data['user']['gender'];
            $result['status'] = $data['user']['status'];
            $result['school'] = $data['user']['school'];
            $result['college'] = $data['user']['college'];
            $result['student_type'] = $data['user']['student_type'];
            $result['city'] = $data['user']['city'];
            $result['type_1'] = $data['user']['type_1'];
            $result['type_2'] = $data['user']['type_2'];
            $result['type_3'] = $data['user']['type_3'];
            $result['type_4'] = $data['user']['type_4'];
            $result['appoint_list'] = $data['appoint']; //所有预约列表

            return $result;
        } else {
            return 0;
        }
    }


    /**
     * get teacher info
     * @param number:id teacher's account_id
     * @return array teacher info; 0 failed
     */
    protected function get_teacher_info($id) {
        // teacer info
        $Account = M('account');
        $Teacher = M('teacher');
        $where['account_id'] = ':account_id';
        $data['account'] = $Account->where($where)->bind(':account_id', $id)->find();
        $data['teacher'] = $Teacher->where($where)->bind(':account_id', $id)->find();

        // appoint info
        $Appoint = M('appoint');
        $appoint_where['teacher_id'] = $id;
        $data['appoint'] = $Appoint->where($appoint_where)->select();

        if ($data['account'] || $data['teacher']) {

            $data['teacher']['service_type_a'] = '';
            $data['teacher']['service_type_b'] = '';
            $data['teacher']['service_type_c'] = '';
            $data['teacher']['service_type_d'] = '';
            $data['teacher']['service_type_e'] = '';

            if ($data['teacher']['service_type'] != null) {
                $service_array = explode(',', $data['teacher']['service_type']);
            }
            foreach($service_array as $service) {
                if ($service == '小学') {
                    $data['teacher']['service_type_a'] = 'checked';
                }
                if ($service == '初中') {
                    $data['teacher']['service_type_b'] = 'checked';
                }
                if ($service == '高中') {
                    $data['teacher']['service_type_c'] = 'checked';
                }
                if ($service == '大学及以上') {
                    $data['teacher']['service_type_d'] = 'checked';
                }
                if ($service == '工作') {
                    $data['teacher']['service_type_e'] = 'checked';
                }
            }

            $result['account_id'] = $data['account']['account_id'];
            $result['phone'] = $data['account']['phone'];
            $result['password'] = $data['account']['password'];
            $result['card_id'] = $data['account']['card_id'];
            $result['register_time'] = $data['account']['register_time'];
            $result['name'] = $data['teacher']['name'];
            $result['email'] = $data['teacher']['email'];
            $result['gender'] = $data['teacher']['gender'];
            $result['service_type'] = $data['teacher']['service_type'];
            $result['certificate'] = $data['teacher']['certificate'];
            $result['avatar'] = $data['teacher']['avatar'];
            $result['recommendation'] = $data['teacher']['recommendation'];
            $result['free_time'] = $data['teacher']['free_time'];
            $result['introduction'] = $data['teacher']['introduction'];
            $result['city'] = $data['teacher']['city'];
            $result['service_type_a'] = $data['teacher']['service_type_a'];
            $result['service_type_b'] = $data['teacher']['service_type_b'];
            $result['service_type_c'] = $data['teacher']['service_type_c'];
            $result['service_type_d'] = $data['teacher']['service_type_d'];
            $result['service_type_e'] = $data['teacher']['service_type_e'];
            $result['appoint_list'] = $data['appoint']; //所有预约列表

            return $result;

        } else {
            return 0;
        }
    }



}