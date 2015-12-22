<?php
// +----------------------------------------------------------------------
// | Api
// +----------------------------------------------------------------------

namespace Api\Controller;
use Think\Controller;

/**
 * Class IndexController
 * @package Api\Controller
 */
class IndexController extends Controller {

    public function index(){

    }


    // 获取咨询师的当前已确认预约列表
    public function get_appoint_list_confirm() {
        if ($_POST) {
            $teacher_id = $_POST['teacher_id'];
            $Appoint = M('appoint');
            $where['teacher_id'] = $teacher_id;
            $where['status'] = array('gt',10000);
            $field = array('teacher_confirm_date');
            $appoint_list = $Appoint->field($field)->where($where)->select();
            $this->ajaxReturn($appoint_list);
        } else {
            $data['code'] = 1;
            $data['msg'] = 'error';
            $this->ajaxReturn($data);
        }
    }


    // 获取某个咨询师不能再被预约的时间
    public function get_teacher_can_not_appoint_time() {
        if ($_POST['teacher_id']) {
            $teacher_id = $_POST['teacher_id'];
            $Appoint = M('appoint');
            $where['teacher_id'] = $teacher_id;
            //$where['status'] = array('gt',10000);
            $where['_string'] = 'status=10001 OR status=10002';
            $field = array('teacher_confirm_date');
            $appoint_list = $Appoint->field($field)->where($where)->select();
            $data['code'] = 0;
            $data['msg'] = $appoint_list;
            $this->ajaxReturn($data);
        } else {
            $data['code'] = 1;
            $data['msg'] = 'error';
            $this->ajaxReturn($data);
        }
    }

}