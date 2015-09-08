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


    /**
     * initialize redirect url
     */

    public function _initialize() {

        if ($this->is_login()) {
            $login_type = $this->login_type();
            if ($login_type) {

                switch ($login_type) {
                    case 1:
                        $this->redirect('User/index', '', 0);
                        break;
                    case 2:
                        $this->redirect('Teacher/index', '', 0);
                        break;
                    case 3:
                        $this->redirect('Index/index', '', 0);
                        break;
                }
            } else {
                logout();
                $this->redirect('Home/Login/index', '', 0);
            }
        } else {
            logout();
            $this->redirect('Home/Login/index', '', 0);
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


}