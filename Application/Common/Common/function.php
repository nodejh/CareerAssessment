<?php

// 判断是否登录
function function_is_login() {
    if($_SESSION['uid'] && $_SESSION['uid'] != '') {
        // Account 表的 id
        return intval($_SESSION['uid']);
    } else {
        return false;
    }
}


// 用户类型
function function_login_type() {
    if ($_SESSION['utype'] && $_SESSION['utype'] != '') {
        return intval($_SESSION['utype']);
    } else {
        return false;
    }
}


// 来访者类型
function function_user_number() {
    return 1;
}


// 咨询师类型
function function_teacher_number() {
    return 9;
}


// 管理员type
function function_admin_number() {
    return 11;
}


// 设置用户登录状态
function function_set_login_in($uid, $utype) {
    $_SESSION['uid'] = $uid;
    $_SESSION['utype'] = $utype;
    return true;
}


// 退出登录
function function_set_logout() {
    session_unset();
    session_destroy();
}


// 数据加密
function function_encrypt($string) {
    // TODO 加密方法
    $encrypt = md5($string);
    $encrypt = $string;
    return $encrypt;
}


// 生成默认密码
function get_default_password() {
    $number = '0123456789';
    $password = '';
    for ($i=0; $i<8; $i++) {
        $rand = rand(0, 9);
        $password .= substr($number, $rand, 1);
    }
    return $password;
}


// 验证手机号
function reg_exp_phone($string) {
    $pattern =  '/^(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/';
    if (preg_match($pattern, $string)) {
        return true;
    } else {
        return false;
    }
}


// 验证邮箱
function reg_exp_email($string) {
    $pattern = '/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$/';
    if(preg_match($pattern, $string)) {
        return true;
    } else {
        return false;
    }
}


// 验证密码，6-20位不含引号字符串
function reg_exp_password($string) {
    $pattern = '/^[^\'\"]{6,20}$/';
    if (preg_match($pattern, $string)) {
      return true;
    } else {
      return false;
    }
}


// 验证6-20位纯数字
function reg_exp_number($string) {
    $pattern = '/^[0-9 ]{6,20}$/';
    if (preg_match($pattern, $string)) {
        return true;
    } else {
        return false;
    }
}


// 验证2-20位不含引号字符串
function reg_exp_nomarks($string) {
    $pattern = '/^[^\'\"]{2,20}$/';
    if (preg_match($pattern, $string)) {
        return true;
    } else {
        return false;
    }
}

