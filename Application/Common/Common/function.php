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
 * encrypt
 * @param  [string] encrypt
 * @return [string] cipher text
 */
function encrypt($string) {
    $cipher = $string;
    return $cipher;
}