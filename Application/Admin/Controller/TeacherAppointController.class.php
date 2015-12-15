<?php
// +----------------------------------------------------------------------
// | Teacher后台
// +----------------------------------------------------------------------

namespace Admin\Controller;
use Think\Controller;

/**
 * Class TeacherAppointController
 * @package Admin\Controller
 */
class TeacherAppointController extends BaseController {

    public function appoint_table () {
        $id = $_SESSION['id'];

        $Appoint = M('appoint');
        $where['teacher_id'] = $id;
        $where['status'] = 2;
        $appoint_list = $Appoint->where($where)->select();

        $User = M('user');
        if ($appoint_list) {
            foreach($appoint_list as $k => $v) {
                $where_user['account_id'] = $v['user_id'];
                $user_info = $User->where($where_user)->find();
                $appoint_list[$k]['user'] = $user_info;
            }
        }
        $res['data'] = $appoint_list;
        echo json_encode($res);
    }



}