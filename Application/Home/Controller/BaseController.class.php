<?php
// +----------------------------------------------------------------------
// | 登录
// +----------------------------------------------------------------------

namespace Home\Controller;
use Think\Controller;

/**
 * Class LoginController
 * @package Home\Controller
 */

class BaseController extends Controller {

    public $_data; //渲染到前台的数据


    /**
     * initialize. push account info to $_data
     */
    public function _initialize() {

        if ($this->is_login()) {

            //$login_type = $this->login_type();

            //switch ($login_type) {
            //    case 1:
            //        // login type of user
            //        $data = $this->get_user_info($_SESSION['id']);
            //        if ($data) {
            //            $this->_data['user']['account_id'] = $data['account']['account_id'];
            //            $this->_data['user']['phone'] = $data['account']['phone'];
            //            $this->_data['user']['card_id'] = $data['account']['card_id'];
            //            $this->_data['user']['date'] = $data['account']['date'];
            //            $this->_data['user']['name'] = $data['user']['name'];
            //            $this->_data['user']['email'] = $data['user']['email'];
            //            $this->_data['user']['gender'] = $data['user']['gender'];
            //            $this->_data['user']['status'] = $data['user']['status'];
            //            $this->_data['user']['school'] = $data['user']['school'];
            //            $this->_data['user']['college'] = $data['user']['college'];
            //            $this->_data['user']['student_type'] = $data['user']['student_type'];
            //            $this->_data['user']['city'] = $data['user']['city'];
            //        } else {
            //            $this->_data['user'] = 0;
            //        }
            //        break;
            //    case 2:
            //        // login type of Teacher
            //        $data = $this->get_teacher_info($_SESSION['id']);
            //        if ($data) {
            //            $this->_data['teacher']['account_id'] = $data['account']['account_id'];
            //            $this->_data['teacher']['phone'] = $data['account']['phone'];
            //            $this->_data['teacher']['card_id'] = $data['account']['card_id'];
            //            $this->_data['teacher']['date'] = $data['account']['date'];
            //            $this->_data['teacher']['name'] = $data['teacher']['name'];
            //            $this->_data['teacher']['avatar'] = $data['teacher']['avatar'];
            //            $this->_data['teacher']['gender'] = $data['teacher']['gender'];
            //            $this->_data['teacher']['service_type'] = $data['teacher']['service_type'];
            //            $this->_data['teacher']['free_time'] = $data['teacher']['free_time'];
            //            $this->_data['teacher']['introduction'] = $data['teacher']['introduction'];
            //            $this->_data['teacher']['city'] = $data['teacher']['city'];
            //            $this->_data['teacher']['certificate_a'] = $data['teacher']['certificate_a'];
            //            $this->_data['teacher']['certificate_b'] = $data['teacher']['certificate_b'];
            //            $this->_data['teacher']['time_a'] = $data['teacher']['time_a'];
            //            $this->_data['teacher']['time_b'] = $data['teacher']['time_b'];
            //        } else {
            //            $this->_data['teacher'] = 0;
            //        }
            //        break;
            //    case 3:
            //        // login type of admin
            //        break;
            //}
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

        if ($data['account'] && $data['user']) {

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

            if ($data['user']['student_type'] != NULL) {
                $student_type1 = substr($data['user']['student_type'], 0, 1);
                $student_type2 = substr($data['user']['student_type'], 1, 1);
                $student_type3 = substr($data['user']['student_type'], 2, 1);
                $student_type4 = substr($data['user']['student_type'], 3, 1);
                $data['user']['student_type'] = '';
                if ($student_type1 == '1') {
                    $data['user']['student_type'] .= '艺体生,';
                }
                if ($student_type2 == '1') {
                    $data['user']['student_type'] .= '少数名族考生,';
                }
                if ($student_type3 == '1') {
                    $data['user']['student_type'] .= '国家专项计划,';
                }
                if ($student_type4 == '1') {
                    $data['user']['student_type'] .= '军校或国防生,';
                }
                $data['user']['student_type'] = rtrim($data['user']['student_type'], ',');

            } else {
                $data['user']['student_type'] = '';
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

        if ($data['account'] && $data['teacher']) {

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
            return $data;

        } else {
            return 0;
        }
    }



}