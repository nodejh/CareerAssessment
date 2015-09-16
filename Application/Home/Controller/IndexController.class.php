<?php
// +----------------------------------------------------------------------
// | Home Page
// +----------------------------------------------------------------------

namespace Home\Controller;
use Think\Controller;

/**
 * Class IndexController
 * @package Home\Controller
 */
class IndexController extends BaseController {


    /**
     * Home Page
     */
    public function index(){
        $this->_data['title'] = '个人中心';
        
        $Teacher = M('teacher');
        $list = $Teacher->order('teacher_id desc')->limit(16)->select();
        $this->_data['list'] = $list;

        $this->assign($this->_data);
        $this->display();
    }


    public function select() {

        if ($_POST) {
            $city = I('post.city', 0);
            $gender = I('post.gender', 0);
            $certificate_a = I('post.certificate_type_a', 0);
            $certificate_b = I('post.certificate_type_b', 0);
            $service_a = I('post.service_type_a', 0);
            $service_b = I('post.service_type_b', 0);
            $service_c = I('post.service_type_c', 0);
            $time = I('post.time', 0);

            if ($city) {
                $teacher_where['city'] = array('like',array('%'.$city.'%'));
            }
            if ($gender) {
                $teacher_where['gender'] = $gender;
            }
            if ($certificate_a) {
                $teacher_where['certificate_a'] = array('neq', 'NULL');
            }
            if ($certificate_b) {
                $teacher_where['certificate_b'] = array('neq', 'NULL');
            }
            if ($time) {
                $teacher_where['time'] = $time;
            }

            $teacher_where_service = '';
            if ($service_a) {
                $teacher_where_service .= $service_a.',';
            }
            if ($service_b) {
                $teacher_where_service .= $service_b.',';
            }
            if ($service_c) {
                $teacher_where_service .= $service_c.',';
            }
            if ($teacher_where_service != '') {
                $teacher_where_service = rtrim($teacher_where_service, ',');
                $teacher_where['service_type'] = array('like',array('%'.$teacher_where_service.'%'));
            }

            $Teacher = M('teacher');

            if (count($teacher_where) > 0) {
                $teacher_result = $Teacher->where($teacher_where)->order('teacher_id desc')->limit(16)->select();

                if ($teacher_result !== false) {
                    $result['status'] = 0;
                    $result['result'] = $teacher_result;
                } else {
                    $result['status'] = 1; // 查询数据失败
                }
            } else {
                $result['status'] = 2; // 没有查询数据
            }

            $this->ajaxReturn($result);
        }
    }


    /**
     * 咨询师详情
     */
    public function teacher() {
        if ($_GET) {
            $id = $_GET['id'];
        }
    }


    public function logout() {
        logout();
        $this->redirect('index');
    }


}