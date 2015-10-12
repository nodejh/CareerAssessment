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
                    var_dump($data);
                    if ($data) {
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
                        $this->_data['user'] = $data;
                    } else {
                        $this->_data['user'] = 0;
                    }
                    break;
                case 2:
                    // login type of Teacher
                    $data = $this->get_teacher_info($_SESSION['id']);
                    if ($data) {
                        $this->_data['teacher']['account_id'] = $data['account']['account_id'];
                        $this->_data['teacher']['phone'] = $data['account']['phone'];
                        $this->_data['teacher']['password'] = $data['account']['password'];
                        $this->_data['teacher']['card_id'] = $data['account']['card_id'];
                        $this->_data['teacher']['date'] = $data['account']['date'];
                        $this->_data['teacher']['name'] = $data['teacher']['name'];
                        $this->_data['teacher']['email'] = $data['teacher']['email'];
                        $this->_data['teacher']['avatar'] = $data['teacher']['avatar'];
                        $this->_data['teacher']['gender'] = $data['teacher']['gender'];
                        $this->_data['teacher']['service_type'] = $data['teacher']['service_type'];
                        $this->_data['teacher']['free_time'] = $data['teacher']['free_time'];
                        $this->_data['teacher']['introduction'] = $data['teacher']['introduction'];
                        $this->_data['teacher']['city'] = $data['teacher']['city'];
                        $this->_data['teacher']['certificate_a'] = $data['teacher']['certificate_a'];
                        $this->_data['teacher']['certificate_b'] = $data['teacher']['certificate_b'];
                        $this->_data['teacher']['service_type_a'] = $data['teacher']['service_type_a'];
                        $this->_data['teacher']['service_type_b'] = $data['teacher']['service_type_b'];
                        $this->_data['teacher']['service_type_c'] = $data['teacher']['service_type_c'];
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

        if ($data['account'] || $data['teacher']) {

            if ($data['teacher']) {

                switch ($data['teacher']['gender']) {
                    case 1:
                        $data['teacher']['gender'] = '男';
                        break;
                    case 2:
                        $data['teacher']['gender'] = '女';
                        break;
                    default:
                        $data['teacher']['gender'] = '未知';
                }
            }
            $data['teacher']['service_type_a'] = '';
            $data['teacher']['service_type_b'] = '';
            $data['teacher']['service_type_c'] = '';

            if ($data['teacher']['service_type'] != null) {
                $service_array = explode(',', $data['teacher']['service_type']);
            }

            foreach($service_array as $service) {
                if ($service == '高中') {
                    $data['teacher']['service_type_a'] = 'checked';
                }
                if ($service == '大学') {
                    $data['teacher']['service_type_b'] = 'checked';
                }
                if ($service == '工作') {
                    $data['teacher']['service_type_c'] = 'checked';
                }
            }


            return $data;

        } else {
            return 0;
        }
    }



}