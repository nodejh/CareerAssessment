<?php
// +----------------------------------------------------------------------
// | Home Page
// +----------------------------------------------------------------------

namespace Home\Controller;
use Think\Controller;

/**
 * Class CourseController
 * @package Home\Controller
 */
class CourseController extends BaseController {


    /**
     * Course Page
     */
    public function index(){
        $this->_data['title'] = '课程中心';
        $this->assign($this->_data);
        $this->display();
    }


    /**
     *  某个老师的课程
     */
    public function teacher() {
        $this->_data['title'] = '课程列表';
        $this->assign($this->_data);
        $this->display();
    }



}