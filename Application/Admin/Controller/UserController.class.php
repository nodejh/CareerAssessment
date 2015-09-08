<?php
// +----------------------------------------------------------------------
// | User 后台
// +----------------------------------------------------------------------

namespace Admin\Controller;
use Think\Controller;

/**
 * Class IndexController
 * @package Admin\Controller
 */
class UserController extends BaseController {

    /**
     * User后台首页
     */
    public function index(){
        var_dump($_SESSION);

                logout();

    }

}