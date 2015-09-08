<?php
// +----------------------------------------------------------------------
// | 完善一般用户信息
// +----------------------------------------------------------------------

namespace Home\Controller;
use Think\Controller;

/**
 * Class CompletelyController
 * @package Home\Controller
 */
class CompletelyController extends BaseController {


    /**
     *
     */
    public function index() {
        $this->_data['title'] = '完善会员信息';
        var_dump($_SESSION);
        $this->assign($this->_data);
        $this->display();
    }

}