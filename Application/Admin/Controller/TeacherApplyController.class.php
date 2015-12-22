<?php
// +----------------------------------------------------------------------
// | Teacher后台 完善教师信息／申请成为咨询师
// +----------------------------------------------------------------------

namespace Admin\Controller;
use Think\Controller;

/**
 * Class TeacherAppointController
 * @package Admin\Controller
 */
class TeacherApplyController extends BaseController {

    public function apply_one () {
        $this->is_teacher();
        $this->_data['title'] = '填写基本资料';
        if (IS_POST) {
            $post_data['name'] = $_POST['name']; 
            $post_data['gender'] = $_POST['gender']; 
            $post_data['email'] = $_POST['email']; 
            $post_data['occupation'] = $_POST['occupation']; 
            $post_data['city'] = $_POST['city']; 
            $post_data['price'] = $_POST['price']; 
            $post_data['appoint_location'] = $_POST['appoint_location']; 
            $post_data['service_object'] = $_POST['service_object']; 
            $post_data['service_type'] = '';
            if ($_POST['service_type_a']) {
                $post_data['service_type'] .= $_POST['service_type_a'] . ',';
            }
            if ($_POST['service_type_b']) {
                $post_data['service_type'] .= $_POST['service_type_b'] . ',';
            }
            if ($_POST['service_type_c']) {
                $post_data['service_type'] .= $_POST['service_type_c'] . ',';
            }
            if ($_POST['service_type_d']) {
                $post_data['service_type'] .= $_POST['service_type_d'] . ',';
            }
            if ($_POST['service_type_e']) {
                $post_data['service_type'] .= $_POST['service_type_e'] . ',';
            }
            if ($_POST['service_type_f']) {
                $post_data['service_type'] .= $_POST['service_type_f'] . ',';
            }
            if ($_POST['service_type_g']) {
                $post_data['service_type'] .= $_POST['service_type_g'] . ',';
            }
            if ($post_data['service_type'] != '') {
                $post_data['service_type'] = rtrim($post_data['service_type'], ',');
            }
            $post_data['service_object'] = '';
            if ($_POST['service_object_a']) {
                $post_data['service_object'] .= $_POST['service_object_a'] . ',';
            }
            if ($_POST['service_object_b']) {
                $post_data['service_object'] .= $_POST['service_object_b'] . ',';
            }
            if ($_POST['service_object_c']) {
                $post_data['service_object'] .= $_POST['service_object_c'] . ',';
            }
            if ($_POST['service_object_d']) {
                $post_data['service_object'] .= $_POST['service_object_d'] . ',';
            }
            if ($_POST['service_object_e']) {
                $post_data['service_object'] .= $_POST['service_object_e'] . ',';
            }
            if ($post_data['service_object'] != '') {
                $post_data['service_object'] = rtrim($post_data['service_object'], ',');
            }
            $Teacher = M('teacher');
            $where['account_id'] = $_SESSION['id'];
            $update = $Teacher->where($where)->data($post_data)->save();
            if ($update) {
                $this->redirect('apply_two', '', 0);
            } else {
                $Teacher = M('teacher');
                $where['account_id'] = $_SESSION['id'];
                $field[0] = 'name';
                $field[1] = 'gender';
                $field[2] = 'email';
                $field[3] = 'occupation';
                $field[4] = 'city';
                $field[5] = 'service_type';
                $field[6] = 'service_object';
                $field[7] = 'price';
                $field[8] = 'appoint_location';
                $this->_data['teacher'] = $Teacher->field($field)->where($where)->find();
                $this->_data['error'] = '更新资料失败，请重试';
                $this->assign($this->_data);
                $this->display();
            }
        } else {
            $Teacher = M('teacher');
            $where['account_id'] = $_SESSION['id'];
            $field[0] = 'name';
            $field[1] = 'gender';
            $field[2] = 'email';
            $field[3] = 'occupation';
            $field[4] = 'city';
            $field[5] = 'service_type';
            $field[6] = 'service_object';
            $field[7] = 'price';
            $field[8] = 'appoint_location';
            $this->_data['teacher'] = $Teacher->field($field)->where($where)->find();
            $this->assign($this->_data);
            $this->display();
        }
    }

    public function apply_two () {
        $this->is_teacher();
        $this->_data['title'] = '填写个人简介';
        if (IS_POST) {
            if ($_FILES['avatar']) {
                // 上传图片
                $upload = new \Think\Upload();
                $upload->maxSize = 3145728 ;// 设置附件上传大小
                $upload->exts = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
                $upload->rootPath = './Uploads/'; // 设置附件上传根目录
                $upload->savePath = 'avatar/';

                $info = $upload->uploadOne($_FILES['avatar']);
                if (!$info) {
                    $Teacher = M('teacher');
                    $where['account_id'] = $_SESSION['id'];
                    $field[0] = 'introduction';
                    $this->_data['teacher'] = $Teacher->field($field)->where($where)->find();
                    $this->_data['error'] = $upload->getError();
                    $this->assign($this->_data);
                    $this->display();
                    exit();
                } else {
                    $post['avatar'] = $info['savepath'].$info['savename'];
                    // 将信息写入数据库
                    $Teacher = M('teacher');
                    $where_update['account_id'] = $_SESSION['id'];
                    $data['avatar'] = $post['avatar'];
                    $data['introduction'] = $_POST['introduction'];
                    $row_teacher = $Teacher->data($data)->where($where_update)->save();
                    if ($row_teacher !== false) {
                        $this->redirect('apply_three', '', 0);
                        exit();
                    } else {
                        $Teacher = M('teacher');
                        $where['account_id'] = $_SESSION['id'];
                        $field[0] = 'introduction';
                        $this->_data['teacher'] = $Teacher->field($field)->where($where)->find();
                        $this->_data['error'] = '修改个人资料修失败，请重试';
                        $this->assign($this->_data);
                        $this->display();
                    }
                }
            } else {
                $this->_data['error'] = '头像不能为空';
                $this->assign($this->_data);
                $this->display();
            }
        } else {
            $Teacher = M('teacher');
            $where['account_id'] = $_SESSION['id'];
            $field[0] = 'introduction';
            $this->_data['teacher'] = $Teacher->field($field)->where($where)->find();
            $this->assign($this->_data);
            $this->display();
        }
    }

    public function apply_three () {
        $this->is_teacher();
        $this->_data['title'] = '上传证书';
        if (IS_POST) {
            if ($_FILES['picture']) {
                $post['name'] = $_POST['name'];

                $upload = new \Think\Upload();
                $upload->maxSize = 3145728 ;// 设置附件上传大小
                $upload->exts = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
                $upload->rootPath = './Uploads/'; // 设置附件上传根目录
                $upload->savePath = 'certificate/';

                $info = $upload->uploadOne($_FILES['picture']);
                if (!$info) {
                    $this->_data['error'] = $upload->getError();
                    $this->assign($this->_data);
                    $this->display();

                } else {
                    $post['picture'] = $info['savepath'].$info['savename'];
                    $Certificate = M('certificate');
                    $data['picture'] = $post['picture'];
                    $data['name'] = $post['name'];

                    $row_certificate = $Certificate->data($data)->add();
                    if ($row_certificate) {
                        $Teacher = M('teacher');
                        $where_teacher['account_id'] = $_SESSION['id'];
                        $data_old_certificate['picture'] = $Teacher->where($where_teacher)->getField('certificate');
                        if ($data_old_certificate['picture'] == null) {
                            $data_new_certificate['certificate'] = $row_certificate;
                        } else {
                            $data_new_certificate['certificate'] = $data_old_certificate['picture'] . ',' . $row_certificate;
                        }
                        $update_certificate = $Teacher->where($where_teacher)->data($data_new_certificate)->save();
                        if ($update_certificate) {
                            $this->redirect('apply_four', '', 0);
                        } else {
                            $this->_data['error'] = '添加证书失败，请重试';
                            $this->assign($this->_data);
                            $this->display();
                        }
                    } else {
                        $this->_data['error'] = '添加证书失败，请重试';
                        $this->assign($this->_data);
                        $this->display();
                    }
                }
            } else {
                $this->_data['error'] = '证书图片不能为空';
                $this->assign($this->_data);
                $this->display();
            }
        } else {
            $this->assign($this->_data);
            $this->display();
        }
    }

    public function apply_four () {
        $this->is_teacher();
        $this->_data['title'] = '添加话题';
        if ($_POST) {
            $data['name'] = $_POST['name'];
            $data['content'] = $_POST['content'];
            $Topic = M('topic');
            $insert = $Topic->data($data)->add();
            if ($insert) {
                $id = $_SESSION['id'];
                $Teacher = M('teacher');
                $topic_old =  $Teacher->where('account_id='.$id)->getField('topic');
                if ($topic_old) {
                    $topic_now = $topic_old . ',' . $insert;
                } else {
                    $topic_now = $insert;
                }
                $data_topic['topic'] = $topic_now;
                $data_topic['apply_status'] = 1;
                $update_topic = $Teacher->where('account_id='.$id)->data($data_topic)->save();
                if ($update_topic) {
                    $this->redirect('apply_success', '', 0);
                } else {
                    $this->_data['error'] = '更新话题失败，请重试';
                    $this->assign($this->_data);
                    $this->display();
                }
            } else {
                $this->_data['error'] = '添加话题失败，请重试';
                $this->assign($this->_data);
                $this->display();
            }
        }
        $this->assign($this->_data);
        $this->display();
    }


    public function apply_success() {
        $this->is_teacher();
        $this->_data['title'] = '申请成功';
        $this->assign($this->_data);
        $this->display();
    }







}