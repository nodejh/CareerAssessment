<?php
// +----------------------------------------------------------------------
// | Teacher 预约后台
// +----------------------------------------------------------------------

namespace Admin\Controller;
use Think\Controller;

/**
 * Class UserAppointController
 * @package Admin\Controller
 */
class UserAppointController extends BaseController {

    /**
     * 预约操作
     */
    public function save_appoint() {
        $this->is_user();

        if ($_POST && isset($_POST['time'])) {
            $post['user_id'] = I('post.user_id', 0);
            $post['teacher_id'] = I('post.teacher_id', 0);
            $post['user_select_date'] = I('post.time', 0);

            // TODO 判断是否已经预约
            if ($post['user_id'] && $post['teacher_id'] && $post['user_select_date']) {
                $Appoint = M('appoint');
                $data['user_id'] = $post['user_id'];
                $data['teacher_id'] = $post['teacher_id'];
                $data['user_select_date'] = $post['user_select_date'];
                $data['save_time'] = time();
                $data['status'] = 10000;
                $appoint_result = $Appoint->data($data)->add();

                if ($appoint_result) {
                    $result['status'] = '0';
                    $result['message'] = '预约成功';
                    $result['url'] = U('Home/Index/success');
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
     * 预约表
     */
    public function appoint_all() {
        $this->is_user();

        $Teacher = M('teacher');
        foreach ($this->_data['user']['appoint_list'] as $k => $v) {
            $teacher_where['account_id'] = $v['teacher_id'];
            $teacher_result = $Teacher->where($teacher_where)->find();
            $appoint_info[$k] = $teacher_result;
            $appoint_info[$k]['status'] = $v['status'];
            $appoint_info[$k]['appoint_id'] = $v['appoint_id'];

        }

        $this->_data['appoint_info'] = $appoint_info;
        $this->_data['title'] = '我的预约表';
        $this->assign($this->_data);
        $this->display();
    }


    public function appoint_incomplete() {
        $this->is_user();
        $Appoint = M('appoint');
        $where_appoint['user_id'] = $_SESSION['id'];
        $where_appoint['_string'] = 'status=10000 OR status=10001 OR status=10002';
        $this->_data['user']['appoint_list'] = $Appoint->where($where_appoint)->order('save_time desc')->select();

        $Teacher = M('teacher');
        foreach ($this->_data['user']['appoint_list'] as $k => $v) {
            $teacher_where['account_id'] = $v['teacher_id'];
            $teacher_result = $Teacher->where($teacher_where)->find();
            $appoint_info[$k] = $teacher_result;
            $appoint_info[$k]['status'] = $v['status'];
            $appoint_info[$k]['appoint_id'] = $v['appoint_id'];

        }
        $this->_data['appoint_info'] = $appoint_info;
        $this->_data['title'] = '未完成预约';
        $this->assign($this->_data);
        $this->display();
    }


    public function appoint_complete() {
        $this->is_user();
        $Appoint = M('appoint');
        $where_appoint['user_id'] = $_SESSION['id'];
        $where_appoint['_string'] = 'status=20 OR status=21';
        $this->_data['user']['appoint_list'] = $Appoint->where($where_appoint)->order('save_time desc')->select();

        $Teacher = M('teacher');
        foreach ($this->_data['user']['appoint_list'] as $k => $v) {
            $teacher_where['account_id'] = $v['teacher_id'];
            $teacher_result = $Teacher->where($teacher_where)->find();
            $appoint_info[$k] = $teacher_result;
            $appoint_info[$k]['status'] = $v['status'];
            $appoint_info[$k]['appoint_id'] = $v['appoint_id'];

        }
        $this->_data['appoint_info'] = $appoint_info;
        $this->_data['title'] = '已完成预约';
        $this->assign($this->_data);
        $this->display();
    }


    /**
     * 付款
     */
    public function appoint_pay() {
        $this->is_user();
        // TODO 咨询师未确认，不能付款
        if ($_GET) {
            $appoint_id = $_GET['appoint_id'];
            // TODO 付款逻辑
            $Appoint = M('appoint');
            $where['appoint_id'] = $appoint_id;
            $data['status'] = 10002;
            $update = $Appoint->where($where)->data($data)->save();
            if ($update) {
                $this->assign($this->_data);
                $this->display('pay_success');
            } else {
                $this->assign($this->_data);
                $this->display('pay_error');
            }
        } else {
            $this->redirect('Admin/UserAppoint/appoint_incomplete', '', 0);
        }
    }


}