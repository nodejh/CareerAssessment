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


// 数据加密
function function_encrypt($string) {
    // TODO 加密方法
    $encrypt = md5($string);
    return $encrypt;
}


function test() {
    echo 'a';
}
