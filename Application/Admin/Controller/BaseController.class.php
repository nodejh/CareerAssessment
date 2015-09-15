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

            if (isset($_SESSION['f'])) {
                // first time to login. suggest to change the password
                $this->_data['first'] = '1';
            }

            switch ($this->login_type()) {
                case 1:
                    // login type of user
                    $data = $this->get_user_info($_SESSION['id']);
                    if ($data) {
                        $this->_data['user']['account_id'] = $data['account']['account_id'];
                        $this->_data['user']['phone'] = $data['account']['phone'];
                        $this->_data['user']['password'] = $data['account']['password'];
                        $this->_data['user']['card_id'] = $data['account']['card_id'];
                        $this->_data['user']['date'] = $data['account']['date'];
                        $this->_data['user']['name'] = $data['user']['name'];
                        $this->_data['user']['email'] = $data['user']['email'];
                        $this->_data['user']['gender'] = $data['user']['gender'];
                        $this->_data['user']['status'] = $data['user']['status'];
                        $this->_data['user']['school'] = $data['user']['school'];
                        $this->_data['user']['college'] = $data['user']['college'];
                        $this->_data['user']['student_type'] = $data['user']['student_type'];
                        $this->_data['user']['type_1'] = $data['user']['type_1'];
                        $this->_data['user']['type_2'] = $data['user']['type_2'];
                        $this->_data['user']['type_3'] = $data['user']['type_3'];
                        $this->_data['user']['type_4'] = $data['user']['type_4'];
                        $this->_data['user']['city'] = $data['user']['city'];
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
                        $this->_data['teacher']['avatar'] = $data['teacher']['avatar'];
                        $this->_data['teacher']['gender'] = $data['teacher']['gender'];
                        $this->_data['teacher']['service_type'] = $data['teacher']['service_type'];
                        $this->_data['teacher']['free_time'] = $data['teacher']['free_time'];
                        $this->_data['teacher']['introduction'] = $data['teacher']['introduction'];
                        $this->_data['teacher']['city'] = $data['teacher']['city'];
                        $this->_data['teacher']['certificate_a'] = $data['teacher']['certificate_a'];
                        $this->_data['teacher']['certificate_b'] = $data['teacher']['certificate_b'];
                        $this->_data['teacher']['time_a'] = $data['teacher']['time_a'];
                        $this->_data['teacher']['time_b'] = $data['teacher']['time_b'];
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
    private function get_user_info ($id) {
        $Account = M('account');
        $User = M('user');
        $where['account_id'] = ':account_id';
        $data['account'] = $Account->where($where)->bind(':account_id',$id)->find();
        $data['user'] = $User->where($where)->bind(':account_id', $id)->find();

        if ($data['account'] || $data['user']) {

            if ($data['user']) {
                switch ($data['user']['gender']) {
                    case 1:
                        $data['user']['gender'] = '男';
                        break;
                    case 2:
                        $data['user']['gender'] = '女';
                        break;
                    default:
                        $data['user']['gender'] = '未知';
                }

                switch ($data['user']['status']) {
                    case 1:
                        $data['user']['status'] = '高中';
                        break;
                    case 2:
                        $data['user']['status'] = '大学';
                        break;
                    case 3:
                        $data['user']['status'] = '工作未满1年';
                        break;
                    case 4:
                        $data['user']['status'] = '工作1-3年';
                        break;
                    case 5:
                        $data['user']['status'] = '工作3-5年';
                        break;
                    case 6:
                        $data['user']['status'] = '工作5年以上';
                        break;
                    default:
                        $data['user']['status'] = '未知';
                }

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
            }

            return $data;
        } else {
            return 0;
        }
    }


    /**
     * get teacher info
     * @param number:id teacher's account_id
     * @return array teacher info; 0 failed
     */
    private function get_teacher_info($id) {
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

            return $data;

        } else {
            return 0;
        }
    }



}