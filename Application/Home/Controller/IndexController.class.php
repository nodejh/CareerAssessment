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
        //var_dump($this->_data);
        //die();
        $this->_data['title'] = '个人中心';
        // TODO 列出推荐的咨询师
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
     * 预约咨询师
     */
    public function appoint() {
        if ($_GET) {
            $id = I('get.id', 0);
            if ($id) {
                $Teacher = M('teacher');
                $where['account_id'] = $id;
                $teacher_result = $Teacher->where($where)->find();
                $Option = M('option');
                $option_result = $Option->select();

                if ($teacher_result && $option_result) {
                    $this->_data['html'] = set_week();
                    $this->_data['appoint'] = $teacher_result;
                    $this->_data['url'] = U('save_appoint');
                    $this->_data['appoint']['time_per_day'] = $option_result[0]['value']; //每天最多预约次数
                    $this->_data['appoint']['number_per_appoint'] = $option_result[1]['value']; //每次预约最多能选择的时间段
                    $this->_data['appoint']['number_per_month'] = $option_result[2]['value']; //每个月最多预约次数
                    //var_dump($this->_data);
                    //die();
                    $this->assign($this->_data);
                    $this->display();
                } else {
                    $this->redirect('index', '', 0);
                }

            } else {
                $this->redirect('index', '', 0);
            }
        }
    }


    /**
     * 预约操作
     */
    public function save_appoint() {
        if ($_POST && isset($_POST['time'])) {
            $post['user_id'] = I('post.user_id', 0);
            $post['teacher_id'] = I('post.teacher_id', 0);
            $post['time'] = I('post.time', 0);

            // TODO 判断是否已经预约
            if ($post['user_id'] && $post['teacher_id'] && $post['time']) {
                $Appoint = M('appoint');
                $data['user_id'] = $post['user_id'];
                $data['teacher_id'] = $post['teacher_id'];
                $data['time'] = $post['time'];
                $data['save_time'] = time();
                $data['status'] = 0;
                $appoint_result = $Appoint->data($data)->add();

                if ($appoint_result) {
                    $result['status'] = '0';
                    $result['message'] = '预约成功';
                    $result['url'] = U('Admin/User/appoint');
                    $this->ajaxReturn($result);
                } else {
                    $result['status'] = '1001';
                    $result['message'] = '写入数据库失败';
                    $this->ajaxReturn($result);
                }
            } else {
                $result['status'] = '1002';
                $result['message'] = '失败';
                $this->ajaxReturn($result);
            }
        } else {
            $result['status'] = '1003';
            $result['message'] = '失败';
            $this->ajaxReturn($result);
        }
    }


    /**
     * 修改预约
     */
    public function change_appoint() {
        if ($_POST && isset($_POST['time'])) {
            $post['user_id'] = I('post.user_id', 0);
            $post['teacher_id'] = I('post.teacher_id', 0);
            $post['time'] = I('post.time', 0);

            // TODO 判断是否已经预约
            if ($post['user_id'] && $post['teacher_id'] && $post['time']) {
                $Appoint = M('appoint');
                $data['user_id'] = $post['user_id'];
                $data['teacher_id'] = $post['teacher_id'];
                $data['time'] = $post['time'];
                $data['save_time'] = time();
                $data['status'] = 0;
                $appoint_result = $Appoint->data($data)->add();

                if ($appoint_result) {
                    $result['status'] = '0';
                    $result['message'] = '预约成功';
                    $result['url'] = U('Admin/User/appoint');
                    $this->ajaxReturn($result);
                } else {
                    $result['status'] = '1001';
                    $result['message'] = '写入数据库失败';
                    $this->ajaxReturn($result);
                }
            } else {
                $result['status'] = '1002';
                $result['message'] = '失败';
                $this->ajaxReturn($result);
            }
        } else {
            $result['status'] = '1003';
            $result['message'] = '失败';
            $this->ajaxReturn($result);
        }
    }

    /**
     * TODO 预约状态，包括预约次数，是否可继续预约等
     */
    public function appoint_status() {

    }


    public function logout() {
        logout();
        $this->redirect('index');
    }


}