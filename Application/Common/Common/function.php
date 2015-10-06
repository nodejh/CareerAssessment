<?php
// +----------------------------------------------------------------------
// | 公共函数
// +----------------------------------------------------------------------


/**
 * log in
 * @param account_id
 * @param type
 * @return void
 */
function login($id, $type) {
    $_SESSION['id'] = $id;
    $_SESSION['type'] = $type;
}


/**
 * common user first signup
 * set a session, to mark it
 * once the user change the password, unset the session['f']
 */
function first_signup() {
    $_SESSION['f'] = '1';
}


/**
 * 注销登录
 * @return void
 */
function logout() {
    session_unset();
    session_destroy();
}


/**
 * @description encrypt
 * @param $string
 * @return mixed
 */
function encrypt($string) {
    $cipher = $string;
    return $cipher;
}


/**
 * @description 生成默认密码
 * @return string
 */
function get_default_password() {
    $number = '0123456789';
    $password = '';
    for ($i=0; $i<8; $i++) {
        $rand = rand(0, 9);
        $password .= substr($number, $rand, 1);
    }
    return encrypt($password);
}


/**
 * @description 验证手机号
 * @param $string
 * @return bool
 */
function reg_exp_phone($string) {
    $pattern =  '/^(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/';
    if (preg_match($pattern, $string)) {
        return true;
    } else {
        return false;
    }
}


/**
 * @description 验证邮箱
 * @param $string
 * @return bool
 */
function reg_exp_email($string) {
    $pattern = '/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$/';
    if(preg_match($pattern, $string)) {
        return true;
    } else {
        return false;
    }
}


/**
 * @description 验证密码，6-20位不含引号字符串
 * @param $string
 * @return bool
 */
function reg_exp_password($string) {
    $pattern = '/^[^\'\"]{6,20}$/';
    if (preg_match($pattern, $string)) {
        return true;
    } else {
        return false;
    }
}



/**
 * @description 验证6-20位纯数字
 * @param $string
 * @return bool
 */
function reg_exp_number($string) {
    $pattern = '/^[0-9 ]{6,20}$/';
    if (preg_match($pattern, $string)) {
        return true;
    } else {
        return false;
    }
}

/**
 * @description 验证2-20位不含引号字符串
 * @param $string
 * @return bool
 */
function reg_exp_nomarks($string) {
    $pattern = '/^[^\'\"]{2,20}$/';
    if (preg_match($pattern, $string)) {
        return true;
    } else {
        return false;
    }
}


/**
 * @return string
 */
function set_week() {
    $today_week = date('N', time());
    switch ($today_week) {
        case '1':
            $html = set_week_1();
            break;
        case '2':
            $html = set_week_2();
            break;
        case '3':
            $html = set_week_3();
            break;
        case '4':
            $html = set_week_4();
            break;
        case '5':
            $html = set_week_5();
            break;
        case '6':
            $html = set_week_6();
            break;
        case '7':
            $html = set_week_7();
            break;
    }
    return $html;
}


/**
 * @return string
 */
function set_week_1() {
    $html = '';
    $date_1 = date('Y-m-d', time());
    $date_2 = date('Y-m-d', strtotime('+1 day'));
    $date_3 = date('Y-m-d', strtotime('+2 day'));
    $date_4 = date('Y-m-d', strtotime('+3 day'));
    $date_5 = date('Y-m-d', strtotime('+4 day'));
    $date_6 = date('Y-m-d', strtotime('+5 day'));
    $date_7 = date('Y-m-d', strtotime('+6 day'));
    $html .= '<table class="table table-bordered ca-table-week" id="week_1">' .
        '<thead>'.
        '<tr>'.
        '<th>时间</th>'.
        '<th>星期一<br>('.$date_1.')</th>'.
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
        '<td class="ca-td-time ca-no-free-time" value="a-'.$date_1.'">不可预约</td>'.
        '<td class="ca-td-time ca-no-free-time" value="a-'.$date_2.'">不可预约</td>'.
        '<td class="ca-td-time ca-no-free-time" value="a-'.$date_3.'">不可预约</td>'.
        '<td class="ca-td-time ca-no-free-time" value="a-'.$date_4.'">不可预约</td>'.
        '<td class="ca-td-time ca-no-free-time" value="a-'.$date_5.'">不可预约</td>'.
        '<td class="ca-td-time ca-no-free-time" value="a-'.$date_6.'">不可预约</td>'.
        '<td class="ca-td-time ca-no-free-time" value="a-'.$date_7.'">不可预约</td>'.
        '</tr>'.
        '<tr>'.
        '<th scope="row">10:30-12:00</th>'.
        '<td class="ca-td-time ca-no-free-time" value="b-'.$date_1.'">不可预约</td>'.
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
        '<td class="ca-td-time ca-no-free-time" value="c-'.$date_1.'">不可预约</td>'.
        '<td class="ca-td-time ca-no-free-time" value="c-'.$date_2.'">不可预约</td>'.
        '<td class="ca-td-time ca-no-free-time" value="c-'.$date_3.'">不可预约</td>'.
        '<td class="ca-td-time ca-no-free-time" value="c-'.$date_4.'">不可预约</td>'.
        '<td class="ca-td-time ca-no-free-time" value="c-'.$date_5.'">不可预约</td>'.
        '<td class="ca-td-time ca-no-free-time" value="c-'.$date_6.'">不可预约</td>'.
        '<td class="ca-td-time ca-no-free-time" value="c-'.$date_7.'">不可预约</td>'.
        '</tr>'.
        '<tr>'.
        '<th scope="row">16:00-17:30</th>'.
        '<td class="ca-td-time ca-no-free-time" value="d-'.$date_1.'">不可预约</td>'.
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
        '<td class="ca-td-time ca-no-free-time" value="e-'.$date_1.'">不可预约</td>'.
        '<td class="ca-td-time ca-no-free-time" value="e-'.$date_2.'">不可预约</td>'.
        '<td class="ca-td-time ca-no-free-time" value="e-'.$date_3.'">不可预约</td>'.
        '<td class="ca-td-time ca-no-free-time" value="e-'.$date_4.'">不可预约</td>'.
        '<td class="ca-td-time ca-no-free-time" value="e-'.$date_5.'">不可预约</td>'.
        '<td class="ca-td-time ca-no-free-time" value="e-'.$date_6.'">不可预约</td>'.
        '<td class="ca-td-time ca-no-free-time" value="e-'.$date_7.'">不可预约</td>'.
        '</tr>'.
        '<tr>'.
        '<th scope="row">20:30-22:00</th>'.
        '<td class="ca-td-time ca-no-free-time" value="f-'.$date_1.'">不可预约</td>'.
        '<td class="ca-td-time ca-no-free-time" value="f-'.$date_2.'">不可预约</td>'.
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
function set_week_2() {
    $html = '';
    $date_1 = date('Y-m-d', strtotime('-1 day'));
    $date_2 = date('Y-m-d', time());
    $date_3 = date('Y-m-d', strtotime('+1 day'));
    $date_4 = date('Y-m-d', strtotime('+2 day'));
    $date_5 = date('Y-m-d', strtotime('+3 day'));
    $date_6 = date('Y-m-d', strtotime('+4 day'));
    $date_7 = date('Y-m-d', strtotime('+5 day'));
    $html .= '<table class="table table-bordered ca-table-week" id="week_1">' .
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
    return $html;
}


/**
 * @return string
 */
function set_week_3() {
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
function set_week_4() {
    $html = '';
    $date_1 = date('Y-m-d', strtotime('-3 day'));
    $date_2 = date('Y-m-d', strtotime('-2 day'));
    $date_3 = date('Y-m-d', strtotime('-1 day'));
    $date_4 = date('Y-m-d', time());
    $date_5 = date('Y-m-d', strtotime('+1 day'));
    $date_6 = date('Y-m-d', strtotime('+2 day'));
    $date_7 = date('Y-m-d', strtotime('+3 day'));
    $html .= '<table class="table table-bordered ca-table-week" id="week_1">' .
        '<thead>'.
        '<tr>'.
        '<th>时间</th>'.
        '<th class="ca-time-ago">星期一<br>('.$date_1.')</th>'.
        '<th class="ca-time-ago">星期二<br>('.$date_2.')</th>'.
        '<th class="ca-time-ago">星期三<br>('.$date_3.')</th>'.
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
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-td-time ca-no-free-time" value="a-'.$date_4.'">不可预约</td>'.
        '<td class="ca-td-time ca-no-free-time" value="a-'.$date_5.'">不可预约</td>'.
        '<td class="ca-td-time ca-no-free-time" value="a-'.$date_6.'">不可预约</td>'.
        '<td class="ca-td-time ca-no-free-time" value="a-'.$date_7.'">不可预约</td>'.
        '</tr>'.
        '<tr>'.
        '<th scope="row">10:30-12:00</th>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
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
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-td-time ca-no-free-time" value="c-'.$date_4.'">不可预约</td>'.
        '<td class="ca-td-time ca-no-free-time" value="c-'.$date_5.'">不可预约</td>'.
        '<td class="ca-td-time ca-no-free-time" value="c-'.$date_6.'">不可预约</td>'.
        '<td class="ca-td-time ca-no-free-time" value="c-'.$date_7.'">不可预约</td>'.
        '</tr>'.
        '<tr>'.
        '<th scope="row">16:00-17:30</th>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
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
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-td-time ca-no-free-time" value="e-'.$date_4.'">不可预约</td>'.
        '<td class="ca-td-time ca-no-free-time" value="e-'.$date_5.'">不可预约</td>'.
        '<td class="ca-td-time ca-no-free-time" value="e-'.$date_6.'">不可预约</td>'.
        '<td class="ca-td-time ca-no-free-time" value="e-'.$date_7.'">不可预约</td>'.
        '</tr>'.
        '<tr>'.
        '<th scope="row">20:30-22:00</th>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
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
function set_week_5() {
    $html = '';
    $date_1 = date('Y-m-d', strtotime('-4 day'));
    $date_2 = date('Y-m-d', strtotime('-3 day'));
    $date_3 = date('Y-m-d', strtotime('-2 day'));
    $date_4 = date('Y-m-d', strtotime('-1 day'));
    $date_5 = date('Y-m-d', time());
    $date_6 = date('Y-m-d', strtotime('+1 day'));
    $date_7 = date('Y-m-d', strtotime('+2 day'));
    $html .= '<table class="table table-bordered ca-table-week" id="week_1">' .
        '<thead>'.
        '<tr>'.
        '<th>时间</th>'.
        '<th class="ca-time-ago">星期一<br>('.$date_1.')</th>'.
        '<th class="ca-time-ago">星期二<br>('.$date_2.')</th>'.
        '<th class="ca-time-ago">星期三<br>('.$date_3.')</th>'.
        '<th class="ca-time-ago">星期四<br>('.$date_4.')</th>'.
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
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-td-time ca-no-free-time" value="a-'.$date_5.'">不可预约</td>'.
        '<td class="ca-td-time ca-no-free-time" value="a-'.$date_6.'">不可预约</td>'.
        '<td class="ca-td-time ca-no-free-time" value="a-'.$date_7.'">不可预约</td>'.
        '</tr>'.
        '<tr>'.
        '<th scope="row">10:30-12:00</th>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
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
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-td-time ca-no-free-time" value="c-'.$date_5.'">不可预约</td>'.
        '<td class="ca-td-time ca-no-free-time" value="c-'.$date_6.'">不可预约</td>'.
        '<td class="ca-td-time ca-no-free-time" value="c-'.$date_7.'">不可预约</td>'.
        '</tr>'.
        '<tr>'.
        '<th scope="row">16:00-17:30</th>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
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
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-td-time ca-no-free-time" value="e-'.$date_5.'">不可预约</td>'.
        '<td class="ca-td-time ca-no-free-time" value="e-'.$date_6.'">不可预约</td>'.
        '<td class="ca-td-time ca-no-free-time" value="e-'.$date_7.'">不可预约</td>'.
        '</tr>'.
        '<tr>'.
        '<th scope="row">20:30-22:00</th>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
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
function set_week_6() {
    $html = '';
    $date_1 = date('Y-m-d', strtotime('-4 day'));
    $date_2 = date('Y-m-d', strtotime('-4 day'));
    $date_3 = date('Y-m-d', strtotime('-3 day'));
    $date_4 = date('Y-m-d', strtotime('-2 day'));
    $date_5 = date('Y-m-d', strtotime('-1 day'));
    $date_6 = date('Y-m-d', time());
    $date_7 = date('Y-m-d', strtotime('+1 day'));
    $html .= '<table class="table table-bordered ca-table-week" id="week_1">' .
        '<thead>'.
        '<tr>'.
        '<th>时间</th>'.
        '<th class="ca-time-ago">星期一<br>('.$date_1.')</th>'.
        '<th class="ca-time-ago">星期二<br>('.$date_2.')</th>'.
        '<th class="ca-time-ago">星期三<br>('.$date_3.')</th>'.
        '<th class="ca-time-ago">星期四<br>('.$date_4.')</th>'.
        '<th class="ca-time-ago">星期五<br>('.$date_5.')</th>'.
        '<th>星期六<br>('.$date_6.')</th>'.
        '<th>星期日<br>('.$date_7.')</th>'.
        '</tr>'.
        '</thead>'.
        '<tbody>'.
        '<tr>'.
        '<th scope="row">9:00-10:30</th>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-td-time ca-no-free-time" value="a-'.$date_6.'">不可预约</td>'.
        '<td class="ca-td-time ca-no-free-time" value="a-'.$date_7.'">不可预约</td>'.
        '</tr>'.
        '<tr>'.
        '<th scope="row">10:30-12:00</th>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
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
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-td-time ca-no-free-time" value="c-'.$date_6.'">不可预约</td>'.
        '<td class="ca-td-time ca-no-free-time" value="c-'.$date_7.'">不可预约</td>'.
        '</tr>'.
        '<tr>'.
        '<th scope="row">16:00-17:30</th>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
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
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-td-time ca-no-free-time" value="e-'.$date_6.'">不可预约</td>'.
        '<td class="ca-td-time ca-no-free-time" value="e-'.$date_7.'">不可预约</td>'.
        '</tr>'.
        '<tr>'.
        '<th scope="row">20:30-22:00</th>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
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
function set_week_7() {
    $html = '';
    $date_1 = date('Y-m-d', strtotime('-5 day'));
    $date_2 = date('Y-m-d', strtotime('-4 day'));
    $date_3 = date('Y-m-d', strtotime('-4 day'));
    $date_4 = date('Y-m-d', strtotime('-3 day'));
    $date_5 = date('Y-m-d', strtotime('-2 day'));
    $date_6 = date('Y-m-d', strtotime('-1 day'));
    $date_7 = date('Y-m-d', time());
    $html .= '<table class="table table-bordered ca-table-week" id="week_1">' .
        '<thead>'.
        '<tr>'.
        '<th>时间</th>'.
        '<th class="ca-time-ago">星期一<br>('.$date_1.')</th>'.
        '<th class="ca-time-ago">星期二<br>('.$date_2.')</th>'.
        '<th class="ca-time-ago">星期三<br>('.$date_3.')</th>'.
        '<th class="ca-time-ago">星期四<br>('.$date_4.')</th>'.
        '<th class="ca-time-ago">星期五<br>('.$date_5.')</th>'.
        '<th class="ca-time-ago">星期六<br>('.$date_6.')</th>'.
        '<th>星期日<br>('.$date_7.')</th>'.
        '</tr>'.
        '</thead>'.
        '<tbody>'.
        '<tr>'.
        '<th scope="row">9:00-10:30</th>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-td-time ca-no-free-time" value="a-'.$date_7.'">不可预约</td>'.
        '</tr>'.
        '<tr>'.
        '<th scope="row">10:30-12:00</th>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-td-time ca-no-free-time" value="b-'.$date_7.'">不可预约</td>'.
        '</tr>'.
        '<tr>'.
        '<td colspan="7"></td>'.
        '</tr>'.
        '<tr>'.
        '<th scope="row">14:30-16:00</th>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-td-time ca-no-free-time" value="c-'.$date_7.'">不可预约</td>'.
        '</tr>'.
        '<tr>'.
        '<th scope="row">16:00-17:30</th>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-td-time ca-no-free-time" value="d-'.$date_7.'">不可预约</td>'.
        '</tr>'.
        '<tr>'.
        '<td colspan="7"></td>'.
        '</tr>'.
        '<tr>'.
        '<th scope="row">19:00-20:30</th>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-td-time ca-no-free-time" value="e-'.$date_7.'">不可预约</td>'.
        '</tr>'.
        '<tr>'.
        '<th scope="row">20:30-22:00</th>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-time-ago">不可预约</td>'.
        '<td class="ca-td-time ca-no-free-time" value="f-'.$date_7.'">不可预约</td>'.
        '</tr>'.
        '</tbody>'.
        '</table>';
    return $html;
}