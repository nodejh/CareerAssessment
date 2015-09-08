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
 * 注销登录
 * @return void
 */
function logout () {
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
    return $password;
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