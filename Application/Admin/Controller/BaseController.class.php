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
        $data['appoint'] = $Appoint->where($appoint_where)->order('save_time desc')->select();
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
        $data['appoint'] = $Appoint->where($appoint_where)->order('save_time desc')->select();

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
            $result['apply_status'] = $data['teacher']['apply_status'];
            return $result;

        } else {
            return 0;
        }
    }


    /**
     * 判断 type 是否为 teacher
     * 如果不是，则跳转到相应 type
     */
    protected function is_teacher() {
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
     * @description 从周开一始的28天时间
     * @return string
     */
    protected function set_month_by_1() {
        $html = array();
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
        $date_11 = date('Y-m-d', strtotime('+10 day'));
        $date_12 = date('Y-m-d', strtotime('+11 day'));
        $date_13 = date('Y-m-d', strtotime('+12 day'));
        $date_14 = date('Y-m-d', strtotime('+13 day'));

        $date_15 = date('Y-m-d', strtotime('+14 day'));
        $date_16 = date('Y-m-d', strtotime('+15 day'));
        $date_17 = date('Y-m-d', strtotime('+16 day'));
        $date_18 = date('Y-m-d', strtotime('+17 day'));
        $date_19 = date('Y-m-d', strtotime('+18 day'));
        $date_20 = date('Y-m-d', strtotime('+19 day'));
        $date_21 = date('Y-m-d', strtotime('+20 day'));

        $date_22 = date('Y-m-d', strtotime('+21 day'));
        $date_23 = date('Y-m-d', strtotime('+22 day'));
        $date_24 = date('Y-m-d', strtotime('+23 day'));
        $date_25 = date('Y-m-d', strtotime('+24 day'));
        $date_26 = date('Y-m-d', strtotime('+25 day'));
        $date_27 = date('Y-m-d', strtotime('+26 day'));
        $date_28 = date('Y-m-d', strtotime('+27 day'));

        $html[0] = '<table class="table table-bordered ca-table-week" id="week_1_1">' .
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
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_1.'" value="a-'.$date_1.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_2.'" value="a-'.$date_2.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_3.'" value="a-'.$date_3.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_4.'" value="a-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_5.'" value="a-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_6.'" value="a-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_7.'" value="a-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">10:30-12:00</th>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_1.'" value="b-'.$date_1.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_2.'" value="b-'.$date_2.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_3.'" value="b-'.$date_3.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_4.'" value="b-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_5.'" value="b-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_6.'" value="b-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_7.'" value="b-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">14:30-16:00</th>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_1.'" value="c-'.$date_1.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_2.'" value="c-'.$date_2.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_3.'" value="c-'.$date_3.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_4.'" value="c-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_5.'" value="c-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_6.'" value="c-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_7.'" value="c-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">16:00-17:30</th>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_1.'" value="d-'.$date_1.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_2.'" value="d-'.$date_2.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_3.'" value="d-'.$date_3.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_4.'" value="d-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_5.'" value="d-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_6.'" value="d-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_7.'" value="d-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">19:00-20:30</th>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_1.'" value="e-'.$date_1.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_2.'" value="e-'.$date_2.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_3.'" value="e-'.$date_3.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_4.'" value="e-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_5.'" value="e-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_6.'" value="e-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_7.'" value="e-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">20:30-22:00</th>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_1.'" value="f-'.$date_1.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_2.'" value="f-'.$date_2.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_3.'" value="f-'.$date_3.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_4.'" value="f-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_5.'" value="f-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_6.'" value="f-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_7.'" value="f-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '</tbody>'.
            '</table>';

        $html[1] = '<table class="table table-bordered ca-table-week" id="week_1_2">' .
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
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_8.'" value="a-'.$date_8.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_9.'" value="a-'.$date_9.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_10.'" value="a-'.$date_10.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_11.'" value="a-'.$date_11.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_12.'" value="a-'.$date_12.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_13.'" value="a-'.$date_13.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_14.'" value="a-'.$date_14.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">10:30-12:00</th>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_8.'" value="b-'.$date_8.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_9.'" value="b-'.$date_9.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_10.'" value="b-'.$date_10.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_11.'" value="b-'.$date_11.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_12.'" value="b-'.$date_12.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_13.'" value="b-'.$date_13.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_14.'" value="b-'.$date_14.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">14:30-16:00</th>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_8.'" value="c-'.$date_8.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_9.'" value="c-'.$date_9.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_10.'" value="c-'.$date_10.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_11.'" value="c-'.$date_11.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_12.'" value="c-'.$date_12.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_13.'" value="c-'.$date_13.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_14.'" value="c-'.$date_14.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">16:00-17:30</th>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_8.'" value="d-'.$date_8.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_9.'" value="d-'.$date_9.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_10.'" value="d-'.$date_10.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_11.'" value="d-'.$date_11.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_12.'" value="d-'.$date_12.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_13.'" value="d-'.$date_13.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_14.'" value="d-'.$date_14.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">19:00-20:30</th>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_8.'" value="e-'.$date_8.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_9.'" value="e-'.$date_9.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_10.'" value="e-'.$date_10.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_11.'" value="e-'.$date_11.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_12.'" value="e-'.$date_12.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_13.'" value="e-'.$date_13.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_14.'" value="e-'.$date_14.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">20:30-22:00</th>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_8.'" value="f-'.$date_8.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_9.'" value="f-'.$date_9.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_10.'" value="f-'.$date_10.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_11.'" value="f-'.$date_11.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_12.'" value="f-'.$date_12.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_13.'" value="f-'.$date_13.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_14.'" value="f-'.$date_14.'">不可预约</td>'.
            '</tr>'.
            '</tbody>'.
            '</table>';

        $html[2] = '<table class="table table-bordered ca-table-week" id="week_1_3">' .
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
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_15.'" value="a-'.$date_15.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_16.'" value="a-'.$date_16.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_17.'" value="a-'.$date_17.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_18.'" value="a-'.$date_18.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_19.'" value="a-'.$date_19.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_20.'" value="a-'.$date_20.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_21.'" value="a-'.$date_21.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">10:30-12:00</th>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_15.'" value="b-'.$date_15.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_16.'" value="b-'.$date_16.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_17.'" value="b-'.$date_17.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_18.'" value="b-'.$date_18.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_19.'" value="b-'.$date_19.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_20.'" value="b-'.$date_20.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_21.'" value="b-'.$date_21.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">14:30-16:00</th>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_15.'" idvalue="c-'.$date_15.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_16.'" idvalue="c-'.$date_16.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_17.'" idvalue="c-'.$date_17.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_18.'" idvalue="c-'.$date_18.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_19.'" idvalue="c-'.$date_19.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_20.'" idvalue="c-'.$date_20.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_21.'" idvalue="c-'.$date_21.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">16:00-17:30</th>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_15.'" value="d-'.$date_15.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_16.'" value="d-'.$date_16.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_17.'" value="d-'.$date_17.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_18.'" value="d-'.$date_18.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_19.'" value="d-'.$date_19.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_20.'" value="d-'.$date_20.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_21.'" value="d-'.$date_21.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">19:00-20:30</th>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_15.'" value="e-'.$date_15.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_16.'" value="e-'.$date_16.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_17.'" value="e-'.$date_17.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_18.'" value="e-'.$date_18.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_19.'" value="e-'.$date_19.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_20.'" value="e-'.$date_20.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_21.'" value="e-'.$date_21.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">20:30-22:00</th>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_15.'" value="f-'.$date_15.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_16.'" value="f-'.$date_16.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_17.'" value="f-'.$date_17.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_18.'" value="f-'.$date_18.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_19.'" value="f-'.$date_19.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_20.'" value="f-'.$date_20.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_21.'" value="f-'.$date_21.'">不可预约</td>'.
            '</tr>'.
            '</tbody>'.
            '</table>';

        $html[3] = '<table class="table table-bordered ca-table-week" id="week_1_4">' .
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
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_22.'" value="a-'.$date_22.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_23.'" value="a-'.$date_23.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_24.'" value="a-'.$date_24.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_25.'" value="a-'.$date_25.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_26.'" value="a-'.$date_26.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_27.'" value="a-'.$date_27.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_28.'" value="a-'.$date_28.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">10:30-12:00</th>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_22.'" value="b-'.$date_22.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_23.'" value="b-'.$date_23.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_24.'" value="b-'.$date_24.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_25.'" value="b-'.$date_25.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_26.'" value="b-'.$date_26.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_27.'" value="b-'.$date_27.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_28.'" value="b-'.$date_28.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">14:30-16:00</th>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_22.'" value="c-'.$date_22.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_23.'" value="c-'.$date_23.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_24.'" value="c-'.$date_24.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_25.'" value="c-'.$date_25.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_26.'" value="c-'.$date_26.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_27.'" value="c-'.$date_27.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_28.'" value="c-'.$date_28.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">16:00-17:30</th>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_22.'" value="d-'.$date_22.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_23.'" value="d-'.$date_23.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_24.'" value="d-'.$date_24.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_25.'" value="d-'.$date_25.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_26.'" value="d-'.$date_26.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_27.'" value="d-'.$date_27.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_28.'" value="d-'.$date_28.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">19:00-20:30</th>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_22.'" value="e-'.$date_22.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_23.'" value="e-'.$date_23.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_24.'" value="e-'.$date_24.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_25.'" value="e-'.$date_25.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_26.'" value="e-'.$date_26.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_27.'" value="e-'.$date_27.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_28.'" value="e-'.$date_28.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">20:30-22:00</th>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_22.'" value="f-'.$date_22.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_23.'" value="f-'.$date_23.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_24.'" value="f-'.$date_24.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_25.'" value="f-'.$date_25.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_26.'" value="f-'.$date_26.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_27.'" value="f-'.$date_27.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_28.'" value="f-'.$date_28.'">不可预约</td>'.
            '</tr>'.
            '</tbody>'.
            '</table>';
        return $html;
    }


    /**
     * @description 从周二开始的28天时间
     * @return string
     */
    protected function set_month_by_2() {
        $html = array();
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


        $html[0] = '<table class="table table-bordered ca-table-week" id="week_2_1">' .
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
            '<td class="ca-time-ago" id="a-'.$date_1.'" value="a-'.$date_1.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_2.'" value="a-'.$date_2.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_3.'" value="a-'.$date_3.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_4.'" value="a-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_5.'" value="a-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_6.'" value="a-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_7.'" value="a-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">10:30-12:00</th>'.
            '<td class="ca-time-ago" id="b-'.$date_1.'" value="b-'.$date_1.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_2.'" value="b-'.$date_2.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_3.'" value="b-'.$date_3.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_4.'" value="b-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_5.'" value="b-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_6.'" value="b-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_7.'" value="b-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">14:30-16:00</th>'.
            '<td class="ca-time-ago" id="c-'.$date_1.'" value="c-'.$date_1.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_2.'" value="c-'.$date_2.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_3.'" value="c-'.$date_3.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_4.'" value="c-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_5.'" value="c-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_6.'" value="c-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_7.'" value="c-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">16:00-17:30</th>'.
            '<td class="ca-time-ago" id="d-'.$date_1.'" value="d-'.$date_1.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_1.'"  value="d-'.$date_2.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_3.'" value="d-'.$date_3.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_4.'" value="d-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_5.'" value="d-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_6.'" value="d-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_7.'" value="d-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">19:00-20:30</th>'.
            '<td class="ca-time-ago" id="e-'.$date_1.'" value="e-'.$date_1.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_2.'" value="e-'.$date_2.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_3.'" value="e-'.$date_3.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_4.'" value="e-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_5.'" value="e-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_6.'" value="e-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_7.'" value="e-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">20:30-22:00</th>'.
            '<td class="ca-time-ago" id="f-'.$date_1.'" value="f-'.$date_1.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_2.'" value="f-'.$date_2.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_3.'" value="f-'.$date_3.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_4.'" value="f-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_5.'" value="f-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_6.'" value="f-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_7.'" value="f-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '</tbody>'.
            '</table>';

        $html[1] = '<table class="table table-bordered ca-table-week" id="week_2_2">' .
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
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_8.'" value="a-'.$date_8.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_9.'" value="a-'.$date_9.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_10.'" value="a-'.$date_10.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_11.'" value="a-'.$date_11.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_12.'" value="a-'.$date_12.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_13.'" value="a-'.$date_13.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_14.'" value="a-'.$date_14.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">10:30-12:00</th>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_8.'" value="b-'.$date_8.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_9.'" value="b-'.$date_9.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_10.'" value="b-'.$date_10.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_11.'" value="b-'.$date_11.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_12.'" value="b-'.$date_12.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_13.'" value="b-'.$date_13.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_14.'" value="b-'.$date_14.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">14:30-16:00</th>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_8.'" value="c-'.$date_8.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_9.'" value="c-'.$date_9.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_10.'" value="c-'.$date_10.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_11.'" value="c-'.$date_11.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_12.'" value="c-'.$date_12.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_13.'" value="c-'.$date_13.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_14.'" value="c-'.$date_14.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">16:00-17:30</th>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_8.'" value="d-'.$date_8.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_9.'" value="d-'.$date_9.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_10.'" value="d-'.$date_10.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_11.'" value="d-'.$date_11.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_12.'" value="d-'.$date_12.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_13.'" value="d-'.$date_13.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_14.'" value="d-'.$date_14.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">19:00-20:30</th>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_8.'" value="e-'.$date_8.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_9.'" value="e-'.$date_9.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_10.'" value="e-'.$date_10.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_11.'" value="e-'.$date_11.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_12.'" value="e-'.$date_12.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_13.'" value="e-'.$date_13.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_14.'" value="e-'.$date_14.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">20:30-22:00</th>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_8.'" value="f-'.$date_8.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_9.'" value="f-'.$date_9.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_10.'" value="f-'.$date_10.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_11.'" value="f-'.$date_11.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_12.'" value="f-'.$date_12.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_13.'" value="f-'.$date_13.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_14.'" value="f-'.$date_14.'">不可预约</td>'.
            '</tr>'.
            '</tbody>'.
            '</table>';

        $html[2] = '<table class="table table-bordered ca-table-week" id="week_2_3">' .
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
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_15.'" value="a-'.$date_15.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_16.'" value="a-'.$date_16.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_17.'" value="a-'.$date_17.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_18.'" value="a-'.$date_18.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_19.'" value="a-'.$date_19.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_20.'" value="a-'.$date_20.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_21.'" value="a-'.$date_21.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">10:30-12:00</th>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_15.'" value="b-'.$date_15.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_16.'" value="b-'.$date_16.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_17.'" value="b-'.$date_17.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_18.'" value="b-'.$date_18.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_19.'" value="b-'.$date_19.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_20.'" value="b-'.$date_20.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_21.'" value="b-'.$date_21.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">14:30-16:00</th>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_15.'" value="c-'.$date_15.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_16.'" value="c-'.$date_16.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_17.'" value="c-'.$date_17.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_18.'" value="c-'.$date_18.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_19.'" value="c-'.$date_19.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_20.'" value="c-'.$date_20.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_21.'" value="c-'.$date_21.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">16:00-17:30</th>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_15.'" value="d-'.$date_15.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_16.'" value="d-'.$date_16.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_17.'" value="d-'.$date_17.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_18.'" value="d-'.$date_18.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_19.'" value="d-'.$date_19.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_20.'" value="d-'.$date_20.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_21.'" value="d-'.$date_21.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">19:00-20:30</th>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_15.'" value="e-'.$date_15.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_16.'" value="e-'.$date_16.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_17.'" value="e-'.$date_17.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_18.'" value="e-'.$date_18.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_19.'" value="e-'.$date_19.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_20.'" value="e-'.$date_20.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_21.'" value="e-'.$date_21.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">20:30-22:00</th>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_15.'" value="f-'.$date_15.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_16.'" value="f-'.$date_16.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_17.'" value="f-'.$date_17.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_18.'" value="f-'.$date_18.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_19.'" value="f-'.$date_19.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_20.'" value="f-'.$date_20.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_21.'" value="f-'.$date_21.'">不可预约</td>'.
            '</tr>'.
            '</tbody>'.
            '</table>';

        $html[3] = '<table class="table table-bordered ca-table-week" id="week_2_4">' .
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
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_22.'" value="a-'.$date_22.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_23.'" value="a-'.$date_23.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_24.'" value="a-'.$date_24.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_25.'" value="a-'.$date_25.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_26.'" value="a-'.$date_26.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_27.'" value="a-'.$date_27.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_28.'" value="a-'.$date_28.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">10:30-12:00</th>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_22.'" value="b-'.$date_22.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_23.'" value="b-'.$date_23.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_24.'" value="b-'.$date_24.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_25.'" value="b-'.$date_25.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_26.'" value="b-'.$date_26.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_27.'" value="b-'.$date_27.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_28.'"id value="b-'.$date_28.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">14:30-16:00</th>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_22.'" value="c-'.$date_22.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_23.'" value="c-'.$date_23.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_24.'" value="c-'.$date_24.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_25.'" value="c-'.$date_25.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_26.'" value="c-'.$date_26.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_27.'" value="c-'.$date_27.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_28.'" value="c-'.$date_28.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">16:00-17:30</th>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_22.'" value="d-'.$date_22.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_23.'" value="d-'.$date_23.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_24.'" value="d-'.$date_24.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_25.'" value="d-'.$date_25.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_26.'" value="d-'.$date_26.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_27.'" value="d-'.$date_27.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_28.'" value="d-'.$date_28.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">19:00-20:30</th>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_22.'" value="e-'.$date_22.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_23.'" value="e-'.$date_23.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_24.'" value="e-'.$date_24.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_25.'" value="e-'.$date_25.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_26.'" value="e-'.$date_26.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_27.'" value="e-'.$date_27.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_28.'" value="e-'.$date_28.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">20:30-22:00</th>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_22.'" value="f-'.$date_22.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_23.'" value="f-'.$date_23.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_24.'" value="f-'.$date_24.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_25.'" value="f-'.$date_25.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_26.'" value="f-'.$date_26.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_27.'" value="f-'.$date_27.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_28.'" value="f-'.$date_28.'">不可预约</td>'.
            '</tr>'.
            '</tbody>'.
            '</table>';
        return $html;
    }


    /**
     * @description 从周三开始的28天时间
     * @return string
     */
    protected function set_month_by_3() {
        $html = array();
        $date_1 = date('Y-m-d', strtotime('-2 day'));
        $date_2 = date('Y-m-d', strtotime('-1 day'));
        $date_3 = date('Y-m-d', time());
        $date_4 = date('Y-m-d', strtotime('+1 day'));
        $date_5 = date('Y-m-d', strtotime('+2 day'));
        $date_6 = date('Y-m-d', strtotime('+3 day'));
        $date_7 = date('Y-m-d', strtotime('+4 day'));

        $date_8 = date('Y-m-d', strtotime('+5 day'));
        $date_9 = date('Y-m-d', strtotime('+6 day'));
        $date_10 = date('Y-m-d', strtotime('+7 day'));
        $date_11 = date('Y-m-d', strtotime('+8 day'));
        $date_12 = date('Y-m-d', strtotime('+9 day'));
        $date_13 = date('Y-m-d', strtotime('+10 day'));
        $date_14 = date('Y-m-d', strtotime('+11 day'));

        $date_15 = date('Y-m-d', strtotime('+12 day'));
        $date_16 = date('Y-m-d', strtotime('+13 day'));
        $date_17 = date('Y-m-d', strtotime('+14 day'));
        $date_18 = date('Y-m-d', strtotime('+15 day'));
        $date_19 = date('Y-m-d', strtotime('+16 day'));
        $date_20 = date('Y-m-d', strtotime('+17 day'));
        $date_21 = date('Y-m-d', strtotime('+18 day'));

        $date_22 = date('Y-m-d', strtotime('+19 day'));
        $date_23 = date('Y-m-d', strtotime('+20 day'));
        $date_24 = date('Y-m-d', strtotime('+21 day'));
        $date_25 = date('Y-m-d', strtotime('+22 day'));
        $date_26 = date('Y-m-d', strtotime('+23 day'));
        $date_27 = date('Y-m-d', strtotime('+24 day'));
        $date_28 = date('Y-m-d', strtotime('+25 day'));


        $html[0] = '<table class="table table-bordered ca-table-week" id="week_3_1">' .
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
            '<td class="ca-time-ago" id="a-'.$date_1.'" value="a-'.$date_1.'">不可预约</td>'.
            '<td class="ca-time-ago" id="a-'.$date_2.'" value="a-'.$date_2.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_3.'" value="a-'.$date_3.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_4.'" value="a-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_5.'" value="a-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_6.'" value="a-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_7.'" value="a-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">10:30-12:00</th>'.
            '<td class="ca-time-ago" id="b-'.$date_1.'" value="b-'.$date_1.'">不可预约</td>'.
            '<td class="ca-time-ago" id="b-'.$date_2.'" value="b-'.$date_2.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_3.'" value="b-'.$date_3.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_4.'" value="b-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_5.'" value="b-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_6.'" value="b-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_7.'" value="b-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">14:30-16:00</th>'.
            '<td class="ca-time-ago" id="c-'.$date_1.'" value="c-'.$date_1.'">不可预约</td>'.
            '<td class="ca-time-ago" id="c-'.$date_2.'" value="c-'.$date_2.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_3.'" value="c-'.$date_3.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_4.'" value="c-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_5.'" value="c-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_6.'" value="c-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_7.'" value="c-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">16:00-17:30</th>'.
            '<td class="ca-time-ago" id="d-'.$date_1.'" value="d-'.$date_1.'">不可预约</td>'.
            '<td class="ca-time-ago" id="d-'.$date_2.'" value="d-'.$date_2.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_3.'" value="d-'.$date_3.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_4.'" value="d-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_5.'" value="d-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_6.'" value="d-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_7.'" value="d-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">19:00-20:30</th>'.
            '<td class="ca-time-ago" id="e-'.$date_1.'" value="e-'.$date_1.'">不可预约</td>'.
            '<td class="ca-time-ago" id="e-'.$date_2.'" value="e-'.$date_2.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_3.'" value="e-'.$date_3.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_4.'" value="e-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_5.'" value="e-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_6.'" value="e-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_7.'" value="e-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">20:30-22:00</th>'.
            '<td class="ca-time-ago" id="f-'.$date_1.'" value="f-'.$date_1.'">不可预约</td>'.
            '<td class="ca-time-ago" id="f-'.$date_2.'" value="f-'.$date_2.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_3.'" value="f-'.$date_3.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_4.'" value="f-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_5.'" value="f-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_6.'" value="f-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_7.'" value="f-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '</tbody>'.
            '</table>';

        $html[1] = '<table class="table table-bordered ca-table-week" id="week_3_2">' .
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
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_8.'" value="a-'.$date_8.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_9.'" value="a-'.$date_9.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_10.'" value="a-'.$date_10.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_11.'" value="a-'.$date_11.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_12.'" value="a-'.$date_12.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_13.'" value="a-'.$date_13.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_14.'" value="a-'.$date_14.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">10:30-12:00</th>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_8.'" value="b-'.$date_8.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_9.'" value="b-'.$date_9.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_10.'" value="b-'.$date_10.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_11.'" value="b-'.$date_11.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_12.'" value="b-'.$date_12.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_13.'" value="b-'.$date_13.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_14.'" value="b-'.$date_14.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">14:30-16:00</th>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_8.'" value="c-'.$date_8.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_9.'" value="c-'.$date_9.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_10.'" value="c-'.$date_10.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_11.'" value="c-'.$date_11.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_12.'" value="c-'.$date_12.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_13.'" value="c-'.$date_13.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_14.'" value="c-'.$date_14.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">16:00-17:30</th>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_8.'" value="d-'.$date_8.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_9.'" value="d-'.$date_9.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_10.'" value="d-'.$date_10.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_11.'" value="d-'.$date_11.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_12.'" value="d-'.$date_12.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_13.'" value="d-'.$date_13.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_14.'" value="d-'.$date_14.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">19:00-20:30</th>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_8.'" value="e-'.$date_8.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_9.'" value="e-'.$date_9.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_10.'" value="e-'.$date_10.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_11.'" value="e-'.$date_11.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_12.'" value="e-'.$date_12.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_13.'" value="e-'.$date_13.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_14.'" value="e-'.$date_14.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">20:30-22:00</th>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_8.'" value="f-'.$date_8.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_9.'" value="f-'.$date_9.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_10.'" value="f-'.$date_10.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_11.'" value="f-'.$date_11.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_12.'" value="f-'.$date_12.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_13.'" value="f-'.$date_13.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_14.'" value="f-'.$date_14.'">不可预约</td>'.
            '</tr>'.
            '</tbody>'.
            '</table>';

        $html[2] = '<table class="table table-bordered ca-table-week" id="week_3_3">' .
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
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_15.'" value="a-'.$date_15.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_16.'" value="a-'.$date_16.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_17.'" value="a-'.$date_17.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_18.'" value="a-'.$date_18.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_19.'" value="a-'.$date_19.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_20.'" value="a-'.$date_20.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_21.'" value="a-'.$date_21.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">10:30-12:00</th>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_15.'" value="b-'.$date_15.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_16.'" value="b-'.$date_16.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_17.'" value="b-'.$date_17.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_18.'" value="b-'.$date_18.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_19.'" value="b-'.$date_19.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_20.'" value="b-'.$date_20.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_21.'" value="b-'.$date_21.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">14:30-16:00</th>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_15.'" value="c-'.$date_15.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_16.'" value="c-'.$date_16.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_17.'" value="c-'.$date_17.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_18.'" value="c-'.$date_18.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_19.'" value="c-'.$date_19.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_20.'" value="c-'.$date_20.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_21.'" value="c-'.$date_21.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">16:00-17:30</th>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_15.'" value="d-'.$date_15.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_16.'" value="d-'.$date_16.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_17.'" value="d-'.$date_17.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_18.'" value="d-'.$date_18.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_19.'" value="d-'.$date_19.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_20.'" value="d-'.$date_20.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_21.'" value="d-'.$date_21.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">19:00-20:30</th>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_15.'" value="e-'.$date_15.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_16.'" value="e-'.$date_16.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_17.'" value="e-'.$date_17.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_18.'" value="e-'.$date_18.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_19.'" value="e-'.$date_19.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_20.'" value="e-'.$date_20.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_21.'" value="e-'.$date_21.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">20:30-22:00</th>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_15.'" value="f-'.$date_15.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_16.'" value="f-'.$date_16.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_17.'" value="f-'.$date_17.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_18.'" value="f-'.$date_18.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_19.'" value="f-'.$date_19.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_20.'" value="f-'.$date_20.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_21.'" value="f-'.$date_21.'">不可预约</td>'.
            '</tr>'.
            '</tbody>'.
            '</table>';

        $html[3] = '<table class="table table-bordered ca-table-week" id="week_3_4">' .
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
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_22.'" value="a-'.$date_22.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_23.'" value="a-'.$date_23.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_24.'" value="a-'.$date_24.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_25.'" value="a-'.$date_25.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_26.'" value="a-'.$date_26.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_27.'" value="a-'.$date_27.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_28.'" value="a-'.$date_28.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">10:30-12:00</th>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_22.'" value="b-'.$date_22.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_23.'" value="b-'.$date_23.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_24.'" value="b-'.$date_24.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_25.'" value="b-'.$date_25.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_26.'" value="b-'.$date_26.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_27.'" value="b-'.$date_27.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_28.'" value="b-'.$date_28.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">14:30-16:00</th>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_22.'" value="c-'.$date_22.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_23.'" value="c-'.$date_23.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_24.'" value="c-'.$date_24.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_25.'" value="c-'.$date_25.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_26.'" value="c-'.$date_26.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_27.'" value="c-'.$date_27.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_28.'" value="c-'.$date_28.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">16:00-17:30</th>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_22.'" value="d-'.$date_22.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_23.'" value="d-'.$date_23.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_24.'" value="d-'.$date_24.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_25.'" value="d-'.$date_25.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_26.'" value="d-'.$date_26.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_27.'" value="d-'.$date_27.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_28.'" value="d-'.$date_28.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">19:00-20:30</th>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_22.'" value="e-'.$date_22.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_23.'" value="e-'.$date_23.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_24.'" value="e-'.$date_24.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_25.'" value="e-'.$date_25.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_26.'" value="e-'.$date_26.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_27.'" value="e-'.$date_27.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_28.'" value="e-'.$date_28.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">20:30-22:00</th>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_22.'" value="f-'.$date_22.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_23.'" value="f-'.$date_23.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_24.'" value="f-'.$date_24.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_25.'" value="f-'.$date_25.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_26.'" value="f-'.$date_26.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_27.'" value="f-'.$date_27.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_28.'" value="f-'.$date_28.'">不可预约</td>'.
            '</tr>'.
            '</tbody>'.
            '</table>';
        return $html;
    }


    /**
     * @description 从周四开始的28天时间
     * @return string
     */
    protected function set_month_by_4() {
        $html = array();
        $date_1 = date('Y-m-d', strtotime('-3 day'));
        $date_2 = date('Y-m-d', strtotime('-2 day'));
        $date_3 = date('Y-m-d', strtotime('-1 day'));
        $date_4 = date('Y-m-d', time());
        $date_5 = date('Y-m-d', strtotime('+1 day'));
        $date_6 = date('Y-m-d', strtotime('+2 day'));
        $date_7 = date('Y-m-d', strtotime('+3 day'));

        $date_8 = date('Y-m-d', strtotime('+4 day'));
        $date_9 = date('Y-m-d', strtotime('+5 day'));
        $date_10 = date('Y-m-d', strtotime('+6 day'));
        $date_11 = date('Y-m-d', strtotime('+7 day'));
        $date_12 = date('Y-m-d', strtotime('+8 day'));
        $date_13 = date('Y-m-d', strtotime('+9 day'));
        $date_14 = date('Y-m-d', strtotime('+10 day'));

        $date_15 = date('Y-m-d', strtotime('+11 day'));
        $date_16 = date('Y-m-d', strtotime('+12 day'));
        $date_17 = date('Y-m-d', strtotime('+13 day'));
        $date_18 = date('Y-m-d', strtotime('+14 day'));
        $date_19 = date('Y-m-d', strtotime('+15 day'));
        $date_20 = date('Y-m-d', strtotime('+16 day'));
        $date_21 = date('Y-m-d', strtotime('+17 day'));

        $date_22 = date('Y-m-d', strtotime('+18 day'));
        $date_23 = date('Y-m-d', strtotime('+19 day'));
        $date_24 = date('Y-m-d', strtotime('+20 day'));
        $date_25 = date('Y-m-d', strtotime('+21 day'));
        $date_26 = date('Y-m-d', strtotime('+22 day'));
        $date_27 = date('Y-m-d', strtotime('+23 day'));
        $date_28 = date('Y-m-d', strtotime('+24 day'));

        $html[0] = '<table class="table table-bordered ca-table-week" id="week_4_1">' .
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
            '<td class="ca-time-ago" id="a-'.$date_1.'" value="a-'.$date_1.'">不可预约</td>'.
            '<td class="ca-time-ago" id="a-'.$date_2.'" value="a-'.$date_2.'">不可预约</td>'.
            '<td class="ca-time-ago" id="a-'.$date_3.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_4.'" value="a-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_5.'" value="a-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_6.'" value="a-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_7.'" value="a-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">10:30-12:00</th>'.
            '<td class="ca-time-ago" id="b-'.$date_1.'" value="b-'.$date_1.'">不可预约</td>'.
            '<td class="ca-time-ago" id="b-'.$date_2.'" value="b-'.$date_2.'">不可预约</td>'.
            '<td class="ca-time-ago" id="b-'.$date_3.'" value="b-'.$date_3.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_4.'" value="b-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_5.'" value="b-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_6.'" value="b-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_7.'" value="b-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">14:30-16:00</th>'.
            '<td class="ca-time-ago" id="c-'.$date_1.'" value="c-'.$date_1.'">不可预约</td>'.
            '<td class="ca-time-ago" id="c-'.$date_2.'" value="c-'.$date_2.'">不可预约</td>'.
            '<td class="ca-time-ago" id="c-'.$date_3.'" value="c-'.$date_3.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_4.'" value="c-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_5.'" value="c-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_6.'" value="c-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_7.'" value="c-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">16:00-17:30</th>'.
            '<td class="ca-time-ago" id="d-'.$date_1.'" value="d-'.$date_1.'">不可预约</td>'.
            '<td class="ca-time-ago" id="d-'.$date_2.'" value="d-'.$date_2.'">不可预约</td>'.
            '<td class="ca-time-ago" id="d-'.$date_3.'" value="d-'.$date_3.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_4.'" value="d-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_5.'" value="d-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_6.'" value="d-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_7.'" value="d-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">19:00-20:30</th>'.
            '<td class="ca-time-ago" id="e-'.$date_1.'" value="e-'.$date_1.'">不可预约</td>'.
            '<td class="ca-time-ago" id="e-'.$date_2.'" value="e-'.$date_2.'">不可预约</td>'.
            '<td class="ca-time-ago" id="e-'.$date_3.'" value="e-'.$date_3.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_4.'" value="e-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_5.'" value="e-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_6.'" value="e-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_7.'" value="e-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">20:30-22:00</th>'.
            '<td class="ca-time-ago" id="f-'.$date_1.'" value="f-'.$date_1.'">不可预约</td>'.
            '<td class="ca-time-ago" id="f-'.$date_2.'" value="f-'.$date_2.'">不可预约</td>'.
            '<td class="ca-time-ago" id="f-'.$date_3.'" value="f-'.$date_3.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_4.'" value="f-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_5.'" value="f-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_6.'" value="f-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_7.'" value="f-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '</tbody>'.
            '</table>';

        $html[1] = '<table class="table table-bordered ca-table-week" id="week_4_2">' .
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
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_8.'" value="a-'.$date_8.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_9.'" value="a-'.$date_9.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_10.'" value="a-'.$date_10.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_11.'" value="a-'.$date_11.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_12.'" value="a-'.$date_12.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_13.'" value="a-'.$date_13.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_14.'" value="a-'.$date_14.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">10:30-12:00</th>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_8.'" value="b-'.$date_8.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_9.'" value="b-'.$date_9.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_10.'" value="b-'.$date_10.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_11.'" value="b-'.$date_11.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_12.'" value="b-'.$date_12.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_13.'" value="b-'.$date_13.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_14.'" value="b-'.$date_14.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">14:30-16:00</th>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_8.'" value="c-'.$date_8.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_9.'" value="c-'.$date_9.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_10.'" value="c-'.$date_10.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_11.'" value="c-'.$date_11.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_12.'" value="c-'.$date_12.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_13.'" value="c-'.$date_13.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_14.'" value="c-'.$date_14.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">16:00-17:30</th>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_8.'" value="d-'.$date_8.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_9.'" value="d-'.$date_9.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_10.'" value="d-'.$date_10.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_11.'" value="d-'.$date_11.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_12.'" value="d-'.$date_12.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_13.'" value="d-'.$date_13.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_14.'" value="d-'.$date_14.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">19:00-20:30</th>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_8.'" value="e-'.$date_8.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_9.'" value="e-'.$date_9.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_10.'" value="e-'.$date_10.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_11.'" value="e-'.$date_11.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_12.'" value="e-'.$date_12.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_13.'" value="e-'.$date_13.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_14.'" value="e-'.$date_14.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">20:30-22:00</th>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_8.'" value="f-'.$date_8.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_9.'" value="f-'.$date_9.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_10.'" value="f-'.$date_10.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_11.'" value="f-'.$date_11.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_12.'" value="f-'.$date_12.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_13.'" value="f-'.$date_13.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_14.'" value="f-'.$date_14.'">不可预约</td>'.
            '</tr>'.
            '</tbody>'.
            '</table>';

        $html[2] = '<table class="table table-bordered ca-table-week" id="week_4_3">' .
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
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_15.'" value="a-'.$date_15.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_16.'" value="a-'.$date_16.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_17.'" value="a-'.$date_17.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_18.'" value="a-'.$date_18.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_19.'" value="a-'.$date_19.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_20.'" value="a-'.$date_20.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_21.'" value="a-'.$date_21.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">10:30-12:00</th>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_15.'" value="b-'.$date_15.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_16.'" value="b-'.$date_16.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_17.'" value="b-'.$date_17.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_18.'" value="b-'.$date_18.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_19.'" value="b-'.$date_19.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_20.'" value="b-'.$date_20.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_21.'" value="b-'.$date_21.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">14:30-16:00</th>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_15.'" value="c-'.$date_15.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_16.'" value="c-'.$date_16.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_17.'" value="c-'.$date_17.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_18.'" value="c-'.$date_18.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_19.'" value="c-'.$date_19.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_20.'" value="c-'.$date_20.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_21.'" value="c-'.$date_21.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">16:00-17:30</th>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_15.'" value="d-'.$date_15.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_16.'" value="d-'.$date_16.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_17.'" value="d-'.$date_17.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_18.'" value="d-'.$date_18.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_19.'" value="d-'.$date_19.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_20.'" value="d-'.$date_20.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_21.'" value="d-'.$date_21.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">19:00-20:30</th>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_15.'" value="e-'.$date_15.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_16.'" value="e-'.$date_16.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_17.'" value="e-'.$date_17.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_18.'" value="e-'.$date_18.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_19.'" value="e-'.$date_19.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_20.'" value="e-'.$date_20.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_21.'" value="e-'.$date_21.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">20:30-22:00</th>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_15.'" value="f-'.$date_15.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_16.'" value="f-'.$date_16.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_17.'" value="f-'.$date_17.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_18.'" value="f-'.$date_18.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_19.'" value="f-'.$date_19.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_20.'" value="f-'.$date_20.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_21.'" value="f-'.$date_21.'">不可预约</td>'.
            '</tr>'.
            '</tbody>'.
            '</table>';

        $html[3] = '<table class="table table-bordered ca-table-week" id="week_4_4">' .
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
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_22.'" value="a-'.$date_22.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_23.'" value="a-'.$date_23.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_24.'" value="a-'.$date_24.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_25.'" value="a-'.$date_25.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_26.'" value="a-'.$date_26.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_27.'" value="a-'.$date_27.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_28.'" value="a-'.$date_28.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">10:30-12:00</th>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_22.'" value="b-'.$date_22.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_23.'" value="b-'.$date_23.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_24.'" value="b-'.$date_24.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_25.'" value="b-'.$date_25.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_26.'" value="b-'.$date_26.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_27.'" value="b-'.$date_27.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_28.'" value="b-'.$date_28.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">14:30-16:00</th>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_22.'" value="c-'.$date_22.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_23.'" value="c-'.$date_23.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_24.'" value="c-'.$date_24.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_25.'" value="c-'.$date_25.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_26.'" value="c-'.$date_26.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_27.'" value="c-'.$date_27.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_28.'" value="c-'.$date_28.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">16:00-17:30</th>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_22.'" value="d-'.$date_22.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_23.'" value="d-'.$date_23.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_24.'" value="d-'.$date_24.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_25.'" value="d-'.$date_25.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_26.'" value="d-'.$date_26.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_27.'" value="d-'.$date_27.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_28.'" value="d-'.$date_28.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">19:00-20:30</th>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_22.'" value="e-'.$date_22.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_23.'" value="e-'.$date_23.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_24.'" value="e-'.$date_24.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_25.'" value="e-'.$date_25.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_26.'" value="e-'.$date_26.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_27.'" value="e-'.$date_27.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_28.'" value="e-'.$date_28.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">20:30-22:00</th>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_22.'" value="f-'.$date_22.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_23.'" value="f-'.$date_23.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_24.'" value="f-'.$date_24.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_25.'" value="f-'.$date_25.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_26.'" value="f-'.$date_26.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_27.'" value="f-'.$date_27.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_28.'" value="f-'.$date_28.'">不可预约</td>'.
            '</tr>'.
            '</tbody>'.
            '</table>';
        return $html;
    }


    /**
     * @description 从周五开始的28天时间
     * @return string
     */
    protected function set_month_by_5() {
        $html = array();
        $date_1 = date('Y-m-d', strtotime('-4 day'));
        $date_2 = date('Y-m-d', strtotime('-3 day'));
        $date_3 = date('Y-m-d', strtotime('-2 day'));
        $date_4 = date('Y-m-d', strtotime('-1 day'));
        $date_5 = date('Y-m-d', time());
        $date_6 = date('Y-m-d', strtotime('+1 day'));
        $date_7 = date('Y-m-d', strtotime('+2 day'));

        $date_8 = date('Y-m-d', strtotime('+3 day'));
        $date_9 = date('Y-m-d', strtotime('+4 day'));
        $date_10 = date('Y-m-d', strtotime('+5 day'));
        $date_11 = date('Y-m-d', strtotime('+6 day'));
        $date_12 = date('Y-m-d', strtotime('+7 day'));
        $date_13 = date('Y-m-d', strtotime('+8 day'));
        $date_14 = date('Y-m-d', strtotime('+9 day'));

        $date_15 = date('Y-m-d', strtotime('+10 day'));
        $date_16 = date('Y-m-d', strtotime('+11 day'));
        $date_17 = date('Y-m-d', strtotime('+12 day'));
        $date_18 = date('Y-m-d', strtotime('+13 day'));
        $date_19 = date('Y-m-d', strtotime('+14 day'));
        $date_20 = date('Y-m-d', strtotime('+15 day'));
        $date_21 = date('Y-m-d', strtotime('+16 day'));

        $date_22 = date('Y-m-d', strtotime('+17 day'));
        $date_23 = date('Y-m-d', strtotime('+18 day'));
        $date_24 = date('Y-m-d', strtotime('+19 day'));
        $date_25 = date('Y-m-d', strtotime('+20 day'));
        $date_26 = date('Y-m-d', strtotime('+21 day'));
        $date_27 = date('Y-m-d', strtotime('+22 day'));
        $date_28 = date('Y-m-d', strtotime('+23 day'));

        $html[0] = '<table class="table table-bordered ca-table-week" id="week_5_1">' .
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
            '<td class="ca-time-ago" id="a-'.$date_1.'" value="a-'.$date_1.'">不可预约</td>'.
            '<td class="ca-time-ago" id="a-'.$date_2.'" value="a-'.$date_2.'">不可预约</td>'.
            '<td class="ca-time-ago" id="a-'.$date_3.'" value="a-'.$date_3.'">不可预约</td>'.
            '<td class="ca-time-ago" id="a-'.$date_4.'" value="a-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_5.'" value="a-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_6.'" value="a-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_7.'" value="a-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">10:30-12:00</th>'.
            '<td class="ca-time-ago" id="b-'.$date_1.'" value="b-'.$date_1.'">不可预约</td>'.
            '<td class="ca-time-ago" id="b-'.$date_2.'" value="b-'.$date_2.'">不可预约</td>'.
            '<td class="ca-time-ago" id="b-'.$date_3.'" value="b-'.$date_3.'">不可预约</td>'.
            '<td class="ca-time-ago" id="b-'.$date_4.'" value="b-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_5.'" value="b-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_6.'" value="b-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_7.'" value="b-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">14:30-16:00</th>'.
            '<td class="ca-time-ago" id="c-'.$date_1.'" value="c-'.$date_1.'">不可预约</td>'.
            '<td class="ca-time-ago" id="c-'.$date_2.'" value="c-'.$date_2.'">不可预约</td>'.
            '<td class="ca-time-ago" id="c-'.$date_3.'" value="c-'.$date_3.'">不可预约</td>'.
            '<td class="ca-time-ago" id="c-'.$date_4.'" value="c-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_5.'" value="c-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_6.'" value="c-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_7.'" value="c-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">16:00-17:30</th>'.
            '<td class="ca-time-ago" id="d-'.$date_1.'" value="d-'.$date_1.'">不可预约</td>'.
            '<td class="ca-time-ago" id="d-'.$date_2.'" value="d-'.$date_2.'">不可预约</td>'.
            '<td class="ca-time-ago" id="d-'.$date_3.'" value="d-'.$date_3.'">不可预约</td>'.
            '<td class="ca-time-ago" id="d-'.$date_4.'" value="d-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_5.'" value="d-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_6.'" value="d-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_7.'" value="d-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">19:00-20:30</th>'.
            '<td class="ca-time-ago" id="e-'.$date_1.'" value="e-'.$date_1.'">不可预约</td>'.
            '<td class="ca-time-ago" id="e-'.$date_2.'" value="e-'.$date_2.'">不可预约</td>'.
            '<td class="ca-time-ago" id="e-'.$date_3.'" value="e-'.$date_3.'">不可预约</td>'.
            '<td class="ca-time-ago" id="e-'.$date_4.'" value="e-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_5.'" value="e-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_6.'" value="e-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_7.'" value="e-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">20:30-22:00</th>'.
            '<td class="ca-time-ago" id="f-'.$date_1.'" value="f-'.$date_1.'">不可预约</td>'.
            '<td class="ca-time-ago" id="f-'.$date_2.'" value="f-'.$date_2.'">不可预约</td>'.
            '<td class="ca-time-ago" id="f-'.$date_3.'" value="f-'.$date_3.'">不可预约</td>'.
            '<td class="ca-time-ago" id="f-'.$date_4.'" value="f-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_5.'" value="f-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_6.'" value="f-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_7.'" value="f-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '</tbody>'.
            '</table>';

        $html[1] = '<table class="table table-bordered ca-table-week" id="week_5_2">' .
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
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_8.'" value="a-'.$date_8.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_9.'" value="a-'.$date_9.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_10.'" value="a-'.$date_10.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_11.'" value="a-'.$date_11.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_12.'" value="a-'.$date_12.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_13.'" value="a-'.$date_13.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_14.'" value="a-'.$date_14.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">10:30-12:00</th>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_8.'" value="b-'.$date_8.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_9.'" value="b-'.$date_9.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_10.'" value="b-'.$date_10.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_11.'" value="b-'.$date_11.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_12.'" value="b-'.$date_12.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_13.'" value="b-'.$date_13.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_14.'" value="b-'.$date_14.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">14:30-16:00</th>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_8.'" value="c-'.$date_8.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_9.'" value="c-'.$date_9.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_10.'" value="c-'.$date_10.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_11.'" value="c-'.$date_11.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_12.'" value="c-'.$date_12.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_13.'" value="c-'.$date_13.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_14.'" value="c-'.$date_14.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">16:00-17:30</th>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_8.'" value="d-'.$date_8.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_9.'" value="d-'.$date_9.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_10.'" value="d-'.$date_10.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_11.'" value="d-'.$date_11.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_12.'" value="d-'.$date_12.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_13.'" value="d-'.$date_13.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_14.'" value="d-'.$date_14.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">19:00-20:30</th>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_8.'" value="e-'.$date_8.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_9.'" value="e-'.$date_9.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_10.'" value="e-'.$date_10.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_11.'" value="e-'.$date_11.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_12.'" value="e-'.$date_12.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_13.'" value="e-'.$date_13.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_14.'" value="e-'.$date_14.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">20:30-22:00</th>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_8.'" value="f-'.$date_8.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_9.'" value="f-'.$date_9.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_10.'" value="f-'.$date_10.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_11.'" value="f-'.$date_11.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_12.'" value="f-'.$date_12.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_13.'" value="f-'.$date_13.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_14.'" value="f-'.$date_14.'">不可预约</td>'.
            '</tr>'.
            '</tbody>'.
            '</table>';

        $html[2] = '<table class="table table-bordered ca-table-week" id="week_5_3">' .
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
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_15.'" value="a-'.$date_15.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_16.'" value="a-'.$date_16.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_17.'" value="a-'.$date_17.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_18.'" value="a-'.$date_18.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_19.'" value="a-'.$date_19.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_20.'" value="a-'.$date_20.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_21.'" value="a-'.$date_21.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">10:30-12:00</th>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_15.'" value="b-'.$date_15.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_16.'" value="b-'.$date_16.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_17.'" value="b-'.$date_17.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_18.'" value="b-'.$date_18.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_19.'" value="b-'.$date_19.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_20.'" value="b-'.$date_20.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_21.'" value="b-'.$date_21.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">14:30-16:00</th>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_15.'" value="c-'.$date_15.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_16.'" value="c-'.$date_16.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_17.'" value="c-'.$date_17.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_18.'" value="c-'.$date_18.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_19.'" value="c-'.$date_19.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_20.'" value="c-'.$date_20.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_21.'" value="c-'.$date_21.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">16:00-17:30</th>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_15.'" value="d-'.$date_15.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_16.'" value="d-'.$date_16.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_17.'" value="d-'.$date_17.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_18.'" value="d-'.$date_18.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_19.'" value="d-'.$date_19.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_20.'" value="d-'.$date_20.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_21.'" value="d-'.$date_21.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">19:00-20:30</th>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_15.'" value="e-'.$date_15.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_16.'" value="e-'.$date_16.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_17.'" value="e-'.$date_17.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_18.'" value="e-'.$date_18.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_19.'" value="e-'.$date_19.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_20.'" value="e-'.$date_20.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_21.'" value="e-'.$date_21.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">20:30-22:00</th>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_15.'" value="f-'.$date_15.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_16.'" value="f-'.$date_16.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_17.'" value="f-'.$date_17.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_18.'" value="f-'.$date_18.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_19.'" value="f-'.$date_19.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_20.'" value="f-'.$date_20.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_21.'" value="f-'.$date_21.'">不可预约</td>'.
            '</tr>'.
            '</tbody>'.
            '</table>';

        $html[3] = '<table class="table table-bordered ca-table-week" id="week_5_4">' .
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
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_22.'" value="a-'.$date_22.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_23.'" value="a-'.$date_23.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_24.'" value="a-'.$date_24.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_25.'" value="a-'.$date_25.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_26.'" value="a-'.$date_26.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_27.'" value="a-'.$date_27.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_28.'" value="a-'.$date_28.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">10:30-12:00</th>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_22.'" value="b-'.$date_22.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_23.'" value="b-'.$date_23.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_24.'" value="b-'.$date_24.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_25.'" value="b-'.$date_25.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_26.'" value="b-'.$date_26.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_27.'" value="b-'.$date_27.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_28.'" value="b-'.$date_28.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">14:30-16:00</th>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_22.'" value="c-'.$date_22.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_23.'" value="c-'.$date_23.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_24.'" value="c-'.$date_24.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_25.'" value="c-'.$date_25.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_26.'" value="c-'.$date_26.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_27.'" value="c-'.$date_27.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_28.'" value="c-'.$date_28.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">16:00-17:30</th>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_22.'" value="d-'.$date_22.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_23.'" value="d-'.$date_23.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_24.'" value="d-'.$date_24.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_25.'" value="d-'.$date_25.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_26.'" value="d-'.$date_26.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_27.'" value="d-'.$date_27.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_28.'" value="d-'.$date_28.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">19:00-20:30</th>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_22.'" value="e-'.$date_22.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_23.'" value="e-'.$date_23.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_24.'" value="e-'.$date_24.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_25.'" value="e-'.$date_25.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_26.'" value="e-'.$date_26.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_27.'" value="e-'.$date_27.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_28.'" value="e-'.$date_28.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">20:30-22:00</th>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_22.'" value="f-'.$date_22.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_23.'" value="f-'.$date_23.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_24.'" value="f-'.$date_24.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_25.'" value="f-'.$date_25.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_26.'" value="f-'.$date_26.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_27.'" value="f-'.$date_27.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_28.'" value="f-'.$date_28.'">不可预约</td>'.
            '</tr>'.
            '</tbody>'.
            '</table>';
        return $html;
    }


    /**
     * @description 从周六开始的28天时间
     * @return string
     */
    protected function set_month_by_6() {
        $html = array();
        $date_1 = date('Y-m-d', strtotime('-5 day'));
        $date_2 = date('Y-m-d', strtotime('-4 day'));
        $date_3 = date('Y-m-d', strtotime('-3 day'));
        $date_4 = date('Y-m-d', strtotime('-2 day'));
        $date_5 = date('Y-m-d', strtotime('-1 day'));
        $date_6 = date('Y-m-d', time());
        $date_7 = date('Y-m-d', strtotime('+1 day'));

        $date_8 = date('Y-m-d', strtotime('+2 day'));
        $date_9 = date('Y-m-d', strtotime('+3 day'));
        $date_10 = date('Y-m-d', strtotime('+4 day'));
        $date_11 = date('Y-m-d', strtotime('+5 day'));
        $date_12 = date('Y-m-d', strtotime('+6 day'));
        $date_13 = date('Y-m-d', strtotime('+7 day'));
        $date_14 = date('Y-m-d', strtotime('+8 day'));

        $date_15 = date('Y-m-d', strtotime('+9 day'));
        $date_16 = date('Y-m-d', strtotime('+10 day'));
        $date_17 = date('Y-m-d', strtotime('+11 day'));
        $date_18 = date('Y-m-d', strtotime('+12 day'));
        $date_19 = date('Y-m-d', strtotime('+13 day'));
        $date_20 = date('Y-m-d', strtotime('+14 day'));
        $date_21 = date('Y-m-d', strtotime('+15 day'));

        $date_22 = date('Y-m-d', strtotime('+16 day'));
        $date_23 = date('Y-m-d', strtotime('+17 day'));
        $date_24 = date('Y-m-d', strtotime('+18 day'));
        $date_25 = date('Y-m-d', strtotime('+19 day'));
        $date_26 = date('Y-m-d', strtotime('+20 day'));
        $date_27 = date('Y-m-d', strtotime('+21 day'));
        $date_28 = date('Y-m-d', strtotime('+22 day'));

        $html[0] = '<table class="table table-bordered ca-table-week" id="week_6_1">' .
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
            '<td class="ca-time-ago" id="a-'.$date_1.'" value="a-'.$date_1.'">不可预约</td>'.
            '<td class="ca-time-ago" id="a-'.$date_2.'" value="a-'.$date_2.'">不可预约</td>'.
            '<td class="ca-time-ago" id="a-'.$date_3.'" value="a-'.$date_3.'">不可预约</td>'.
            '<td class="ca-time-ago" id="a-'.$date_4.'" value="a-'.$date_4.'">不可预约</td>'.
            '<td class="ca-time-ago" id="a-'.$date_5.'" value="a-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_6.'" value="a-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_7.'" value="a-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">10:30-12:00</th>'.
            '<td class="ca-time-ago" id="b-'.$date_1.'" value="b-'.$date_1.'">不可预约</td>'.
            '<td class="ca-time-ago" id="b-'.$date_2.'" value="b-'.$date_2.'">不可预约</td>'.
            '<td class="ca-time-ago" id="b-'.$date_3.'" value="b-'.$date_3.'">不可预约</td>'.
            '<td class="ca-time-ago" id="b-'.$date_4.'" value="b-'.$date_4.'">不可预约</td>'.
            '<td class="ca-time-ago" id="b-'.$date_5.'" value="b-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_6.'" value="b-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_7.'" value="b-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">14:30-16:00</th>'.
            '<td class="ca-time-ago" id="c-'.$date_1.'" value="c-'.$date_1.'">不可预约</td>'.
            '<td class="ca-time-ago" id="c-'.$date_2.'" value="c-'.$date_2.'">不可预约</td>'.
            '<td class="ca-time-ago" id="c-'.$date_3.'" value="c-'.$date_3.'">不可预约</td>'.
            '<td class="ca-time-ago" id="c-'.$date_4.'" value="c-'.$date_4.'">不可预约</td>'.
            '<td class="ca-time-ago" id="c-'.$date_5.'" value="c-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_6.'" value="c-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_7.'" value="c-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">16:00-17:30</th>'.
            '<td class="ca-time-ago" id="d-'.$date_1.'" value="d-'.$date_1.'">不可预约</td>'.
            '<td class="ca-time-ago" id="d-'.$date_2.'" value="d-'.$date_2.'">不可预约</td>'.
            '<td class="ca-time-ago" id="d-'.$date_3.'" value="d-'.$date_3.'">不可预约</td>'.
            '<td class="ca-time-ago" id="d-'.$date_4.'" value="d-'.$date_4.'">不可预约</td>'.
            '<td class="ca-time-ago" id="d-'.$date_5.'" value="d-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_6.'" value="d-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_7.'" value="d-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">19:00-20:30</th>'.
            '<td class="ca-time-ago" id="e-'.$date_1.'" value="e-'.$date_1.'">不可预约</td>'.
            '<td class="ca-time-ago" id="e-'.$date_2.'" value="e-'.$date_2.'">不可预约</td>'.
            '<td class="ca-time-ago" id="e-'.$date_3.'" value="e-'.$date_3.'">不可预约</td>'.
            '<td class="ca-time-ago" id="e-'.$date_4.'" value="e-'.$date_4.'">不可预约</td>'.
            '<td class="ca-time-ago" id="e-'.$date_5.'" value="e-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_6.'" value="e-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_7.'" value="e-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">20:30-22:00</th>'.
            '<td class="ca-time-ago" id="f-'.$date_1.'" value="f-'.$date_1.'">不可预约</td>'.
            '<td class="ca-time-ago" id="f-'.$date_2.'" value="f-'.$date_2.'">不可预约</td>'.
            '<td class="ca-time-ago" id="f-'.$date_3.'" value="f-'.$date_3.'">不可预约</td>'.
            '<td class="ca-time-ago" id="f-'.$date_4.'" value="f-'.$date_4.'">不可预约</td>'.
            '<td class="ca-time-ago" id="f-'.$date_5.'" value="f-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_6.'"value="f-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_7.'"value="f-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '</tbody>'.
            '</table>';

        $html[1] = '<table class="table table-bordered ca-table-week" id="week_6_2">' .
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
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_8.'" value="a-'.$date_8.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_9.'" value="a-'.$date_9.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_10.'" value="a-'.$date_10.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_11.'" value="a-'.$date_11.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_12.'" value="a-'.$date_12.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_13.'" value="a-'.$date_13.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_14.'" value="a-'.$date_14.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">10:30-12:00</th>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_8.'" value="b-'.$date_8.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_9.'" value="b-'.$date_9.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_10.'" value="b-'.$date_10.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_11.'" value="b-'.$date_11.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_12.'" value="b-'.$date_12.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_13.'" value="b-'.$date_13.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_14.'" value="b-'.$date_14.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">14:30-16:00</th>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_8.'" value="c-'.$date_8.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_9.'" value="c-'.$date_9.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_10.'" value="c-'.$date_10.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_11.'" value="c-'.$date_11.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_12.'" value="c-'.$date_12.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_13.'" value="c-'.$date_13.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_14.'" value="c-'.$date_14.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">16:00-17:30</th>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_8.'" value="d-'.$date_8.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_9.'" value="d-'.$date_9.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_10.'" value="d-'.$date_10.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_11.'" value="d-'.$date_11.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_12.'" value="d-'.$date_12.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_13.'" value="d-'.$date_13.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_14.'" value="d-'.$date_14.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">19:00-20:30</th>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_8.'" value="e-'.$date_8.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_9.'" value="e-'.$date_9.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_10.'" value="e-'.$date_10.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_11.'" value="e-'.$date_11.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_12.'" value="e-'.$date_12.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_13.'" value="e-'.$date_13.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_14.'" value="e-'.$date_14.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">20:30-22:00</th>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_8.'" value="f-'.$date_8.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_9.'" value="f-'.$date_9.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_10.'" value="f-'.$date_10.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_11.'" value="f-'.$date_11.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_12.'" value="f-'.$date_12.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_13.'" value="f-'.$date_13.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_14.'" value="f-'.$date_14.'">不可预约</td>'.
            '</tr>'.
            '</tbody>'.
            '</table>';

        $html[2] = '<table class="table table-bordered ca-table-week" id="week_6_3">' .
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
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_15.'" value="a-'.$date_15.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_16.'" value="a-'.$date_16.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_17.'" value="a-'.$date_17.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_18.'" value="a-'.$date_18.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_19.'" value="a-'.$date_19.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_20.'" value="a-'.$date_20.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_21.'" value="a-'.$date_21.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">10:30-12:00</th>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_15.'" value="b-'.$date_15.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_16.'" value="b-'.$date_16.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_17.'" value="b-'.$date_17.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_18.'" value="b-'.$date_18.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_19.'" value="b-'.$date_19.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_20.'" value="b-'.$date_20.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_21.'" value="b-'.$date_21.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">14:30-16:00</th>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_15.'" value="c-'.$date_15.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_16.'" value="c-'.$date_16.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_17.'" value="c-'.$date_17.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_18.'" value="c-'.$date_18.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_19.'" value="c-'.$date_19.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_20.'" value="c-'.$date_20.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_21.'" value="c-'.$date_21.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">16:00-17:30</th>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_15.'" value="d-'.$date_15.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_16.'" value="d-'.$date_16.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_17.'" value="d-'.$date_17.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_18.'" value="d-'.$date_18.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_19.'" value="d-'.$date_19.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_20.'" value="d-'.$date_20.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_21.'" value="d-'.$date_21.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">19:00-20:30</th>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_15.'" value="e-'.$date_15.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_16.'" value="e-'.$date_16.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_17.'" value="e-'.$date_17.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_18.'" value="e-'.$date_18.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_19.'" value="e-'.$date_19.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_20.'" value="e-'.$date_20.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_21.'" value="e-'.$date_21.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">20:30-22:00</th>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_15.'" value="f-'.$date_15.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_16.'" value="f-'.$date_16.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_17.'" value="f-'.$date_17.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_18.'" value="f-'.$date_18.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_19.'" value="f-'.$date_19.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_20.'" value="f-'.$date_20.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_21.'" value="f-'.$date_21.'">不可预约</td>'.
            '</tr>'.
            '</tbody>'.
            '</table>';

        $html[3] = '<table class="table table-bordered ca-table-week" id="week_6_4">' .
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
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_22.'" value="a-'.$date_22.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_23.'" value="a-'.$date_23.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_24.'" value="a-'.$date_24.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_25.'" value="a-'.$date_25.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_26.'" value="a-'.$date_26.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_27.'" value="a-'.$date_27.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_28.'" value="a-'.$date_28.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">10:30-12:00</th>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_22.'" value="b-'.$date_22.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_23.'" value="b-'.$date_23.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_24.'" value="b-'.$date_24.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_25.'" value="b-'.$date_25.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_26.'" value="b-'.$date_26.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_27.'" value="b-'.$date_27.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_28.'" value="b-'.$date_28.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">14:30-16:00</th>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_22.'" value="c-'.$date_22.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_23.'" value="c-'.$date_23.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_24.'" value="c-'.$date_24.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_25.'" value="c-'.$date_25.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_26.'" value="c-'.$date_26.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_27.'" value="c-'.$date_27.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_28.'" value="c-'.$date_28.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">16:00-17:30</th>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_22.'" value="d-'.$date_22.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_23.'" value="d-'.$date_23.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_24.'" value="d-'.$date_24.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_25.'" value="d-'.$date_25.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_26.'" value="d-'.$date_26.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_27.'" value="d-'.$date_27.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_28.'" value="d-'.$date_28.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">19:00-20:30</th>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_22.'" value="e-'.$date_22.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_23.'" value="e-'.$date_23.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_24.'" value="e-'.$date_24.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_25.'" value="e-'.$date_25.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_26.'" value="e-'.$date_26.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_27.'" value="e-'.$date_27.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_28.'" value="e-'.$date_28.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">20:30-22:00</th>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_22.'" value="f-'.$date_22.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_23.'" value="f-'.$date_23.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_24.'" value="f-'.$date_24.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_25.'" value="f-'.$date_25.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_26.'" value="f-'.$date_26.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_27.'" value="f-'.$date_27.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_28.'" value="f-'.$date_28.'">不可预约</td>'.
            '</tr>'.
            '</tbody>'.
            '</table>';
        return $html;
    }


    /**
     * @description 从周日开始的28天时间
     * @return string
     */
    protected function set_month_by_7() {
        $html = array();
        $date_1 = date('Y-m-d', strtotime('-6 day'));
        $date_2 = date('Y-m-d', strtotime('-5 day'));
        $date_3 = date('Y-m-d', strtotime('-4 day'));
        $date_4 = date('Y-m-d', strtotime('-3 day'));
        $date_5 = date('Y-m-d', strtotime('-2 day'));
        $date_6 = date('Y-m-d', strtotime('-1 day'));
        $date_7 = date('Y-m-d', time());

        $date_8 = date('Y-m-d', strtotime('+1 day'));
        $date_9 = date('Y-m-d', strtotime('+2 day'));
        $date_10 = date('Y-m-d', strtotime('+3 day'));
        $date_11 = date('Y-m-d', strtotime('+4 day'));
        $date_12 = date('Y-m-d', strtotime('+5 day'));
        $date_13 = date('Y-m-d', strtotime('+6 day'));
        $date_14 = date('Y-m-d', strtotime('+7 day'));

        $date_15 = date('Y-m-d', strtotime('+8 day'));
        $date_16 = date('Y-m-d', strtotime('+9 day'));
        $date_17 = date('Y-m-d', strtotime('+10 day'));
        $date_18 = date('Y-m-d', strtotime('+11 day'));
        $date_19 = date('Y-m-d', strtotime('+12 day'));
        $date_20 = date('Y-m-d', strtotime('+13 day'));
        $date_21 = date('Y-m-d', strtotime('+14 day'));

        $date_22 = date('Y-m-d', strtotime('+15 day'));
        $date_23 = date('Y-m-d', strtotime('+16 day'));
        $date_24 = date('Y-m-d', strtotime('+17 day'));
        $date_25 = date('Y-m-d', strtotime('+18 day'));
        $date_26 = date('Y-m-d', strtotime('+19 day'));
        $date_27 = date('Y-m-d', strtotime('+20 day'));
        $date_28 = date('Y-m-d', strtotime('+21 day'));

        $html[0] = '<table class="table table-bordered ca-table-week" id="week_7_1">' .
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
            '<td class="ca-time-ago" id="a-'.$date_1.'" value="a-'.$date_1.'">不可预约</td>'.
            '<td class="ca-time-ago" id="a-'.$date_2.'" value="a-'.$date_2.'">不可预约</td>'.
            '<td class="ca-time-ago" id="a-'.$date_3.'" value="a-'.$date_3.'">不可预约</td>'.
            '<td class="ca-time-ago" id="a-'.$date_4.'" value="a-'.$date_4.'">不可预约</td>'.
            '<td class="ca-time-ago" id="a-'.$date_5.'" value="a-'.$date_5.'">不可预约</td>'.
            '<td class="ca-time-ago" id="a-'.$date_6.'" value="a-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_7.'" value="a-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">10:30-12:00</th>'.
            '<td class="ca-time-ago" id="b-'.$date_1.'" value="b-'.$date_1.'">不可预约</td>'.
            '<td class="ca-time-ago" id="b-'.$date_2.'" value="b-'.$date_2.'">不可预约</td>'.
            '<td class="ca-time-ago" id="b-'.$date_3.'" value="b-'.$date_3.'">不可预约</td>'.
            '<td class="ca-time-ago" id="b-'.$date_4.'" value="b-'.$date_4.'">不可预约</td>'.
            '<td class="ca-time-ago" id="b-'.$date_5.'" value="b-'.$date_5.'">不可预约</td>'.
            '<td class="ca-time-ago" id="b-'.$date_6.'" value="b-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_7.'" value="b-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">14:30-16:00</th>'.
            '<td class="ca-time-ago" id="c-'.$date_1.'" value="c-'.$date_1.'">不可预约</td>'.
            '<td class="ca-time-ago" id="c-'.$date_2.'" value="c-'.$date_2.'">不可预约</td>'.
            '<td class="ca-time-ago" id="c-'.$date_3.'" value="c-'.$date_3.'">不可预约</td>'.
            '<td class="ca-time-ago" id="c-'.$date_4.'" value="c-'.$date_4.'">不可预约</td>'.
            '<td class="ca-time-ago" id="c-'.$date_5.'" value="c-'.$date_5.'">不可预约</td>'.
            '<td class="ca-time-ago" id="c-'.$date_6.'" value="c-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_7.'" value="c-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">16:00-17:30</th>'.
            '<td class="ca-time-ago" id="d-'.$date_1.'" value="d-'.$date_1.'">不可预约</td>'.
            '<td class="ca-time-ago" id="d-'.$date_2.'" value="d-'.$date_2.'">不可预约</td>'.
            '<td class="ca-time-ago" id="d-'.$date_3.'" value="d-'.$date_3.'">不可预约</td>'.
            '<td class="ca-time-ago" id="d-'.$date_4.'" value="d-'.$date_4.'">不可预约</td>'.
            '<td class="ca-time-ago" id="d-'.$date_5.'" value="d-'.$date_5.'">不可预约</td>'.
            '<td class="ca-time-ago" id="d-'.$date_6.'" value="d-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_7.'" value="d-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">19:00-20:30</th>'.
            '<td class="ca-time-ago" id="e-'.$date_1.'" value="e-'.$date_1.'">不可预约</td>'.
            '<td class="ca-time-ago" id="e-'.$date_2.'" value="e-'.$date_2.'">不可预约</td>'.
            '<td class="ca-time-ago" id="e-'.$date_3.'" value="e-'.$date_3.'">不可预约</td>'.
            '<td class="ca-time-ago" id="e-'.$date_4.'" value="e-'.$date_4.'">不可预约</td>'.
            '<td class="ca-time-ago" id="e-'.$date_5.'" value="e-'.$date_5.'">不可预约</td>'.
            '<td class="ca-time-ago" id="e-'.$date_6.'" value="e-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_7.'" value="e-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">20:30-22:00</th>'.
            '<td class="ca-time-ago" id="f-'.$date_1.'" value="f-'.$date_1.'">不可预约</td>'.
            '<td class="ca-time-ago" id="f-'.$date_2.'" value="f-'.$date_2.'">不可预约</td>'.
            '<td class="ca-time-ago" id="f-'.$date_3.'" value="f-'.$date_3.'">不可预约</td>'.
            '<td class="ca-time-ago" id="f-'.$date_4.'" value="f-'.$date_4.'">不可预约</td>'.
            '<td class="ca-time-ago" id="f-'.$date_5.'" value="f-'.$date_5.'">不可预约</td>'.
            '<td class="ca-time-ago" id="f-'.$date_6.'" value="f-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_7.'" value="f-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '</tbody>'.
            '</table>';

        $html[1] = '<table class="table table-bordered ca-table-week" id="week_7_2">' .
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
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_8.'" value="a-'.$date_8.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_9.'" value="a-'.$date_9.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_10.'" value="a-'.$date_10.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_11.'" value="a-'.$date_11.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_12.'" value="a-'.$date_12.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_13.'" value="a-'.$date_13.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_14.'" value="a-'.$date_14.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">10:30-12:00</th>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_8.'" value="b-'.$date_8.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_9.'" value="b-'.$date_9.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_10.'" value="b-'.$date_10.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_11.'" value="b-'.$date_11.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_12.'" value="b-'.$date_12.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_13.'" value="b-'.$date_13.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_14.'" value="b-'.$date_14.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">14:30-16:00</th>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_8.'" value="c-'.$date_8.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_9.'" value="c-'.$date_9.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_10.'" value="c-'.$date_10.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_11.'" value="c-'.$date_11.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_12.'" value="c-'.$date_12.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_13.'" value="c-'.$date_13.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_14.'" value="c-'.$date_14.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">16:00-17:30</th>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_8.'" value="d-'.$date_8.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_9.'" value="d-'.$date_9.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_10.'" value="d-'.$date_10.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_11.'" value="d-'.$date_11.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_12.'" value="d-'.$date_12.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_13.'" value="d-'.$date_13.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_14.'" value="d-'.$date_14.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">19:00-20:30</th>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_8.'" value="e-'.$date_8.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_9.'" value="e-'.$date_9.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_10.'" value="e-'.$date_10.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_11.'" value="e-'.$date_11.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_12.'" value="e-'.$date_12.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_13.'" value="e-'.$date_13.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_14.'" value="e-'.$date_14.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">20:30-22:00</th>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_8.'" value="f-'.$date_8.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_9.'" value="f-'.$date_9.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_10.'" value="f-'.$date_10.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_11.'" value="f-'.$date_11.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_12.'" value="f-'.$date_12.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_13.'" value="f-'.$date_13.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_14.'" value="f-'.$date_14.'">不可预约</td>'.
            '</tr>'.
            '</tbody>'.
            '</table>';

        $html[2] = '<table class="table table-bordered ca-table-week" id="week_7_3">' .
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
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_15.'" value="a-'.$date_15.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_16.'" value="a-'.$date_16.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_17.'" value="a-'.$date_17.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_18.'" value="a-'.$date_18.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_19.'" value="a-'.$date_19.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_20.'" value="a-'.$date_20.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_21.'" value="a-'.$date_21.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">10:30-12:00</th>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_15.'" value="b-'.$date_15.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_16.'" value="b-'.$date_16.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_17.'" value="b-'.$date_17.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_18.'" value="b-'.$date_18.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_19.'" value="b-'.$date_19.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_20.'" value="b-'.$date_20.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_21.'" value="b-'.$date_21.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">14:30-16:00</th>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_15.'" value="c-'.$date_15.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_16.'" value="c-'.$date_16.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_17.'" value="c-'.$date_17.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_18.'" value="c-'.$date_18.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_19.'" value="c-'.$date_19.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_20.'" value="c-'.$date_20.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_21.'" value="c-'.$date_21.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">16:00-17:30</th>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_15.'" value="d-'.$date_15.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_16.'" value="d-'.$date_16.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_17.'" value="d-'.$date_17.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_18.'" value="d-'.$date_18.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_19.'" value="d-'.$date_19.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_20.'" value="d-'.$date_20.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_21.'" value="d-'.$date_21.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">19:00-20:30</th>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_15.'" value="e-'.$date_15.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_16.'" value="e-'.$date_16.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_17.'" value="e-'.$date_17.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_18.'" value="e-'.$date_18.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_19.'" value="e-'.$date_19.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_20.'" value="e-'.$date_20.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_21.'" value="e-'.$date_21.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">20:30-22:00</th>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_15.'" value="f-'.$date_15.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_16.'" value="f-'.$date_16.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_17.'" value="f-'.$date_17.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_18.'" value="f-'.$date_18.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_19.'" value="f-'.$date_19.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_20.'" value="f-'.$date_20.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_21.'" value="f-'.$date_21.'">不可预约</td>'.
            '</tr>'.
            '</tbody>'.
            '</table>';

        $html[3] = '<table class="table table-bordered ca-table-week" id="week_7_4">' .
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
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_22.'" value="a-'.$date_22.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_23.'" value="a-'.$date_23.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_24.'" value="a-'.$date_24.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_25.'" value="a-'.$date_25.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_26.'" value="a-'.$date_26.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_27.'" value="a-'.$date_27.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="a-'.$date_28.'" value="a-'.$date_28.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">10:30-12:00</th>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_22.'" value="b-'.$date_22.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_23.'" value="b-'.$date_23.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_24.'" value="b-'.$date_24.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_25.'" value="b-'.$date_25.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_26.'" value="b-'.$date_26.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_27.'" value="b-'.$date_27.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="b-'.$date_28.'" value="b-'.$date_28.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">14:30-16:00</th>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_22.'" value="c-'.$date_22.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_23.'" value="c-'.$date_23.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_24.'" value="c-'.$date_24.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_25.'" value="c-'.$date_25.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_26.'" value="c-'.$date_26.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_27.'" value="c-'.$date_27.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="c-'.$date_28.'" value="c-'.$date_28.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">16:00-17:30</th>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_22.'" value="d-'.$date_22.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_23.'" value="d-'.$date_23.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_24.'" value="d-'.$date_24.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_25.'" value="d-'.$date_25.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_26.'" value="d-'.$date_26.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_27.'" value="d-'.$date_27.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="d-'.$date_28.'" value="d-'.$date_28.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">19:00-20:30</th>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_22.'" value="e-'.$date_22.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_23.'" value="e-'.$date_23.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_24.'" value="e-'.$date_24.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_25.'" value="e-'.$date_25.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_26.'" value="e-'.$date_26.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_27.'" value="e-'.$date_27.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="e-'.$date_28.'" value="e-'.$date_28.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">20:30-22:00</th>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_22.'" value="f-'.$date_22.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_23.'" value="f-'.$date_23.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_24.'" value="f-'.$date_24.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_25.'" value="f-'.$date_25.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_26.'" value="f-'.$date_26.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_27.'" value="f-'.$date_27.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" id="f-'.$date_28.'" value="f-'.$date_28.'">不可预约</td>'.
            '</tr>'.
            '</tbody>'.
            '</table>';
        return $html;
    }

}