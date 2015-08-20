<?php

// 判断是否登录
function function_is_login() {
    if($_SESSION['uid'] && $_SESSION['uid'] != '') {
        return $_SESSION['uid'];
    } else {
        return false;
    }
}


// 用户类型
function function_login_type() {
    $type = $_SESSION['user_type'];
    return $type;
}


// 来访者会员卡第一个数字编号
function function_user_number() {
    return 1;
}


// 来访者会员卡第一个数字编号
function function_teacher_number() {
    return 9;
}


// 设置用户登录状态
function function_set_login_in($user_id, $type) {
    $_SESSION['uid'] = $user_id;
    $_SESSION['type'] = $type;
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


// 验证密码，6-20为不含引号字符串
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