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
        $this->_data['title'] = '首页－蓝鲸教育咨询';
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
                    $this->_data['html'] = $this->set_month_by_2();
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


    public function appoint2() {
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

    public function appoint3() {
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
            $post['user_select_date'] = I('post.time', 0);

            // TODO 判断是否已经预约
            if ($post['user_id'] && $post['teacher_id'] && $post['user_select_date']) {
                $Appoint = M('appoint');
                $data['user_id'] = $post['user_id'];
                $data['teacher_id'] = $post['teacher_id'];
                $data['user_select_date'] = $post['user_select_date'];
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





    //从周二开始的一个月时间
    private function set_month_by_2() {
        $html = array();
        $date_1 = date('Y-m-d', strtotime('-1 day'));
        $date_2 = date('Y-m-d', time());
        $date_3 = date('Y-m-d', strtotime('+1 day'));
        $date_4 = date('Y-m-d', strtotime('+2 day'));
        $date_5 = date('Y-m-d', strtotime('+3 day'));
        $date_6 = date('Y-m-d', strtotime('+4 day'));
        $date_7 = date('Y-m-d', strtotime('+5 day'));

        $date_8 = date('Y-m-d', strtotime('+6 day'));
        $date_9 = date('Y-m-d', strtotime('+7 day'));
        $date_10 = date('Y-m-d', strtotime('+8 day'));
        $date_11 = date('Y-m-d', strtotime('+9 day'));
        $date_12 = date('Y-m-d', strtotime('+10 day'));
        $date_13 = date('Y-m-d', strtotime('+11 day'));
        $date_14 = date('Y-m-d', strtotime('+12 day'));

        $date_15 = date('Y-m-d', strtotime('+13 day'));
        $date_16 = date('Y-m-d', strtotime('+14 day'));
        $date_17 = date('Y-m-d', strtotime('+15 day'));
        $date_18 = date('Y-m-d', strtotime('+16 day'));
        $date_19 = date('Y-m-d', strtotime('+17 day'));
        $date_20 = date('Y-m-d', strtotime('+18 day'));
        $date_21 = date('Y-m-d', strtotime('+19 day'));

        $date_22 = date('Y-m-d', strtotime('+20 day'));
        $date_23 = date('Y-m-d', strtotime('+21 day'));
        $date_24 = date('Y-m-d', strtotime('+22 day'));
        $date_25 = date('Y-m-d', strtotime('+23 day'));
        $date_26 = date('Y-m-d', strtotime('+24 day'));
        $date_27 = date('Y-m-d', strtotime('+25 day'));
        $date_28 = date('Y-m-d', strtotime('+26 day'));

        $date_29 = date('Y-m-d', strtotime('+27 day'));
        $date_30 = date('Y-m-d', strtotime('+28 day'));

        $html[0] = '<table class="table table-bordered ca-table-week" id="week_1">' .
            '<thead>'.
            '<tr>'.
            '<th>时间</th>'.
            '<th class="ca-time-ago">星期一<br>('.$date_1.')</th>'.
            '<th>星期二<br>('.$date_2.')</th>'.
            '<th>星期三<br>('.$date_3.')</th>'.
            '<th>星期四<br>('.$date_4.')</th>'.
            '<th>星期五<br>('.$date_5.')</th>'.
            '<th>星期六<br>('.$date_6.')</th>'.
            '<th>星期日<br>('.$date_7.')</th>'.
            '</tr>'.
            '</thead>'.
            '<tbody>'.
            '<tr>'.
            '<th scope="row">9:00-10:30</th>'.
            '<td class="ca-time-ago" value="a-'.$date_1.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_2.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_3.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">10:30-12:00</th>'.
            '<td class="ca-time-ago" value="b-'.$date_1.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_2.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_3.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">14:30-16:00</th>'.
            '<td class="ca-time-ago" value="c-'.$date_1.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_2.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_3.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">16:00-17:30</th>'.
            '<td class="ca-time-ago" value="d-'.$date_1.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_2.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_3.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">19:00-20:30</th>'.
            '<td class="ca-time-ago" value="e-'.$date_1.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_2.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_3.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">20:30-22:00</th>'.
            '<td class="ca-time-ago" value="f-'.$date_1.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_2.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_3.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '</tbody>'.
            '</table>';

        $html[1] = '<table class="table table-bordered ca-table-week" id="week_2">' .
            '<thead>'.
            '<tr>'.
            '<th>时间</th>'.
            '<th>星期一<br>('.$date_8.')</th>'.
            '<th>星期二<br>('.$date_9.')</th>'.
            '<th>星期三<br>('.$date_10.')</th>'.
            '<th>星期四<br>('.$date_11.')</th>'.
            '<th>星期五<br>('.$date_12.')</th>'.
            '<th>星期六<br>('.$date_13.')</th>'.
            '<th>星期日<br>('.$date_14.')</th>'.
            '</tr>'.
            '</thead>'.
            '<tbody>'.
            '<tr>'.
            '<th scope="row">9:00-10:30</th>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_8.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_9.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_10.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_11.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_12.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_13.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_14.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">10:30-12:00</th>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_8.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_9.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_10.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_11.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_12.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_13.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_14.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">14:30-16:00</th>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_8.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_9.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_10.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_11.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_12.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_13.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_14.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">16:00-17:30</th>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_8.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_9.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_10.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_11.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_12.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_13.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_14.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">19:00-20:30</th>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_8.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_9.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_10.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_11.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_12.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_13.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_14.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">20:30-22:00</th>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_8.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_9.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_10.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_11.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_12.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_13.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_14.'">不可预约</td>'.
            '</tr>'.
            '</tbody>'.
            '</table>';

        $html[2] = '<table class="table table-bordered ca-table-week" id="week_3">' .
            '<thead>'.
            '<tr>'.
            '<th>时间</th>'.
            '<th>星期一<br>('.$date_15.')</th>'.
            '<th>星期二<br>('.$date_16.')</th>'.
            '<th>星期三<br>('.$date_17.')</th>'.
            '<th>星期四<br>('.$date_18.')</th>'.
            '<th>星期五<br>('.$date_19.')</th>'.
            '<th>星期六<br>('.$date_20.')</th>'.
            '<th>星期日<br>('.$date_21.')</th>'.
            '</tr>'.
            '</thead>'.
            '<tbody>'.
            '<tr>'.
            '<th scope="row">9:00-10:30</th>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_15.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_16.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_17.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_18.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_19.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_20.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_21.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">10:30-12:00</th>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_15.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_16.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_17.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_18.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_19.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_20.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_21.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">14:30-16:00</th>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_15.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_16.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_17.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_18.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_19.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_20.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_21.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">16:00-17:30</th>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_15.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_16.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_17.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_18.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_19.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_20.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_21.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">19:00-20:30</th>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_15.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_16.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_17.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_18.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_19.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_20.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_21.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">20:30-22:00</th>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_15.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_16.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_17.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_18.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_19.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_20.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_21.'">不可预约</td>'.
            '</tr>'.
            '</tbody>'.
            '</table>';

        $html[3] = '<table class="table table-bordered ca-table-week" id="week_4">' .
            '<thead>'.
            '<tr>'.
            '<th>时间</th>'.
            '<th>星期一<br>('.$date_22.')</th>'.
            '<th>星期二<br>('.$date_23.')</th>'.
            '<th>星期三<br>('.$date_24.')</th>'.
            '<th>星期四<br>('.$date_25.')</th>'.
            '<th>星期五<br>('.$date_26.')</th>'.
            '<th>星期六<br>('.$date_27.')</th>'.
            '<th>星期日<br>('.$date_28.')</th>'.
            '</tr>'.
            '</thead>'.
            '<tbody>'.
            '<tr>'.
            '<th scope="row">9:00-10:30</th>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_22.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_23.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_24.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_25.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_26.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_27.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_28.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">10:30-12:00</th>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_22.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_23.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_24.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_25.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_26.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_27.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_28.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">14:30-16:00</th>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_22.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_23.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_24.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_25.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_26.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_27.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_28.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">16:00-17:30</th>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_22.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_23.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_24.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_25.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_26.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_27.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_28.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">19:00-20:30</th>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_22.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_23.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_24.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_25.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_26.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_27.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_28.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">20:30-22:00</th>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_22.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_23.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_24.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_25.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_26.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_27.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_28.'">不可预约</td>'.
            '</tr>'.
            '</tbody>'.
            '</table>';

        $html[4] = '<table class="table table-bordered ca-table-week" id="week_5">' .
            '<thead>'.
            '<tr>'.
            '<th>时间</th>'.
            '<th>星期一<br>('.$date_29.')</th>'.
            '<th>星期二<br>('.$date_30.')</th>'.
            //'<th>星期三<br>('.$date_10.')</th>'.
            //'<th>星期四<br>('.$date_11.')</th>'.
            //'<th>星期五<br>('.$date_12.')</th>'.
            //'<th>星期六<br>('.$date_13.')</th>'.
            //'<th>星期日<br>('.$date_14.')</th>'.
            '</tr>'.
            '</thead>'.
            '<tbody>'.
            '<tr>'.
            '<th scope="row">9:00-10:30</th>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_29.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_30.'">不可预约</td>'.
            //'<td class="ca-td-time ca-no-free-time" value="a-'.$date_10.'">不可预约</td>'.
            //'<td class="ca-td-time ca-no-free-time" value="a-'.$date_11.'">不可预约</td>'.
            //'<td class="ca-td-time ca-no-free-time" value="a-'.$date_12.'">不可预约</td>'.
            //'<td class="ca-td-time ca-no-free-time" value="a-'.$date_13.'">不可预约</td>'.
            //'<td class="ca-td-time ca-no-free-time" value="a-'.$date_14.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">10:30-12:00</th>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_29.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="b-'.$date_30.'">不可预约</td>'.
            //'<td class="ca-td-time ca-no-free-time" value="b-'.$date_10.'">不可预约</td>'.
            //'<td class="ca-td-time ca-no-free-time" value="b-'.$date_11.'">不可预约</td>'.
            //'<td class="ca-td-time ca-no-free-time" value="b-'.$date_12.'">不可预约</td>'.
            //'<td class="ca-td-time ca-no-free-time" value="b-'.$date_13.'">不可预约</td>'.
            //'<td class="ca-td-time ca-no-free-time" value="b-'.$date_14.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">14:30-16:00</th>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_29.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_30.'">不可预约</td>'.
            //'<td class="ca-td-time ca-no-free-time" value="c-'.$date_10.'">不可预约</td>'.
            //'<td class="ca-td-time ca-no-free-time" value="c-'.$date_11.'">不可预约</td>'.
            //'<td class="ca-td-time ca-no-free-time" value="c-'.$date_12.'">不可预约</td>'.
            //'<td class="ca-td-time ca-no-free-time" value="c-'.$date_13.'">不可预约</td>'.
            //'<td class="ca-td-time ca-no-free-time" value="c-'.$date_14.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">16:00-17:30</th>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_29.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="d-'.$date_30.'">不可预约</td>'.
            //'<td class="ca-td-time ca-no-free-time" value="d-'.$date_10.'">不可预约</td>'.
            //'<td class="ca-td-time ca-no-free-time" value="d-'.$date_11.'">不可预约</td>'.
            //'<td class="ca-td-time ca-no-free-time" value="d-'.$date_12.'">不可预约</td>'.
            //'<td class="ca-td-time ca-no-free-time" value="d-'.$date_13.'">不可预约</td>'.
            //'<td class="ca-td-time ca-no-free-time" value="d-'.$date_14.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<td colspan="7"></td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">19:00-20:30</th>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_29.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_30.'">不可预约</td>'.
            //'<td class="ca-td-time ca-no-free-time" value="e-'.$date_10.'">不可预约</td>'.
            //'<td class="ca-td-time ca-no-free-time" value="e-'.$date_11.'">不可预约</td>'.
            //'<td class="ca-td-time ca-no-free-time" value="e-'.$date_12.'">不可预约</td>'.
            //'<td class="ca-td-time ca-no-free-time" value="e-'.$date_13.'">不可预约</td>'.
            //'<td class="ca-td-time ca-no-free-time" value="e-'.$date_14.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">20:30-22:00</th>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_29.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_30.'">不可预约</td>'.
            //'<td class="ca-td-time ca-no-free-time" value="f-'.$date_10.'">不可预约</td>'.
            //'<td class="ca-td-time ca-no-free-time" value="f-'.$date_11.'">不可预约</td>'.
            //'<td class="ca-td-time ca-no-free-time" value="f-'.$date_12.'">不可预约</td>'.
            //'<td class="ca-td-time ca-no-free-time" value="f-'.$date_13.'">不可预约</td>'.
            //'<td class="ca-td-time ca-no-free-time" value="f-'.$date_14.'">不可预约</td>'.
            '</tr>'.
            '</tbody>'.
            '</table>';
        return $html;
    }

}