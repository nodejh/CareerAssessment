<?php
// +----------------------------------------------------------------------
// | Teacher后台
// +----------------------------------------------------------------------

namespace Admin\Controller;
use Think\Controller;

/**
 * Class TeacherController
 * @package Admin\Controller
 */
class TeacherController extends BaseController {

    /**
     * Teacher后台首页-修改个人资料
     */
    public function index(){
        $this->is_teacher();
        var_dump($this->_data['teacher']);
        if ($_POST) {

        } else {
            $this->_data['title'] = '个人中心';
            $this->assign($this->_data);
            $this->display();
        }
    }


    /**
     * 修改密码
     */
    public function password() {
        $this->is_teacher();

        $this->_data['title'] = '修改密码';
        if ($_POST) {

            if (isset($_POST['password']) && strlen($_POST['password']) > 5) {
                $post['password'] = I('post.password', 0);
                $post['password_confirm'] = I('password_confirm', 0);

                if ($post['password'] == $post['password_confirm']) {

                    $Account = M('account');
                    $account_where['account_id'] = $_SESSION['id'];
                    $account_data['password'] = $post['password'];
                    $account_result = $Account->where($account_where)->data($account_data)->save();

                    if ($account_result) {

                        $this->_data['message'] = '修改密码成功！';
                        unset($_SESSION['f']);
                        unset($this->_data['first']);
                        $this->assign($this->_data);
                        $this->display();

                    } else {
                        $this->_data['error'] = '修改失败，请重试';
                        $this->assign($this->_data);
                        $this->display();
                    }

                } else {
                    $this->_data['error'] = '两次密码不一致';
                    $this->assign($this->_data);
                    $this->display();
                }
            } else {
                $this->_data['error'] = '密码长度不能小于6位';
                $this->assign($this->_data);
                $this->display();
            }

        } else {
            $this->assign($this->_data);
            $this->display();
         }
    }


    /**
     * 我的空闲时间
     */
     public function time() {
         $this->is_teacher();
         $this->_data['title'] = '我的空闲时间';
         $today_week = date('N', time());

         switch ($today_week) {
             case '1':
                 $this->_data['html'] = $this->set_week_1();
                 break;
             case '2':
                 $this->_data['html'] = $this->set_week_2();
                 break;
             case '3':
                 $this->_data['html'] = $this->set_week_3();
                 break;
             case '4':
                 $this->_data['html'] = $this->set_week_4();
                 break;
             case '5':
                 $this->_data['html'] = $this->set_week_5();
                 break;
             case '6':
                 $this->_data['html'] = $this->set_week_6();
                 break;
             case '7':
                 $this->_data['html'] = $this->set_week_7();
                 break;
         }

         $this->assign($this->_data);
         $this->display();
     }


    /**
     * 我的预约表
     */
    public function appoint() {
        $this->is_teacher();
        $this->_data['title'] = '我的预约表';

        $this->assign($this->_data);
        $this->display();
    }


    /**
     * 我的课程
     */
    public function course() {
        $this->is_teacher();
        $this->_data['title'] = '我的课程';

        $this->assign($this->_data);
        $this->display();
    }


    /**
     * 我的评分
     */
    public function comment() {
        $this->is_teacher();
        $this->_data['title'] = '我的评分';

        $this->assign($this->_data);
        $this->display();
    }


    /**
     * 判断 type 是否为 teacher
     * 如果不是，则跳转到相应 type
     */
    private function is_teacher() {
        $type = $this->login_type();

        if ($type == 2) {

        } else if($type == 1) {
            $this->redirect('User/index', '', 0);
        } else if ($type == 3) {
            $this->redirect('Admin/index', '', 0);
        } else {
            logout();
            $this->redirect('Home/Login/index', '', 0);
        }
    }


    /**
     * @return string
     */
    private function set_week_1() {
        $html = '';
        return $html;
    }


    /**
     * @return string
     */
    private function set_week_2() {
        $html = '';
        return $html;
    }


    /**
     * @return string
     */
    private function set_week_3() {
        $html = '';
        $date_1 = date('Y-m-d', strtotime('-2 day'));
        $date_2 = date('Y-m-d', strtotime('-1 day'));
        $date_3 = date('Y-m-d', time());
        $date_4 = date('Y-m-d', strtotime('+1 day'));
        $date_5 = date('Y-m-d', strtotime('+2 day'));
        $date_6 = date('Y-m-d', strtotime('+3 day'));
        $date_7 = date('Y-m-d', strtotime('+4 day'));
        $html .= '<table class="table table-bordered ca-table-week" id="week_1">' .
            '<thead>'.
            '<tr>'.
            '<th>时间</th>'.
            '<th class="ca-time-ago">星期一<br>('.$date_1.')</th>'.
            '<th class="ca-time-ago">星期二<br>('.$date_2.')</th>'.
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
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_3.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="a-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">10:30-12:00</th>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
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
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_3.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="c-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">16:00-17:30</th>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
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
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_3.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="e-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '<tr>'.
            '<th scope="row">20:30-22:00</th>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-time-ago">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_3.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_4.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_5.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_6.'">不可预约</td>'.
            '<td class="ca-td-time ca-no-free-time" value="f-'.$date_7.'">不可预约</td>'.
            '</tr>'.
            '</tbody>'.
        '</table>';
        return $html;
    }


    /**
     * @return string
     */
    private function set_week_4() {
        $html = '';
        return $html;
    }


    /**
     * @return string
     */
    private function set_week_5() {
        $html = '';
        return $html;
    }


    /**
     * @return string
     */
    private function set_week_6() {
        $html = '';
        return $html;
    }


    /**
     * @return string
     */
    private function set_week_7() {
        $html = '';
        return $html;
    }
}