(function ($) {

  /*
   * 登录部分
   **/

  // 会员卡登录
  $('#login-card-btn').click(function () {
    var $button = $(this);
    disable_button($button);
    var number = $('#login-card-number').val();
    var password = $('#login-card-password').val();
    if (!reg_exp_number(number)) {
      var $error_message = $('#login-card .ca-wrong-message');
      $error_message.text('会员卡卡号格式错误');
      $error_message.show();
      able_button($button);
      return;
    }
    if (!reg_exp_password(password)) {
      var $error_message = $('#login-card .ca-wrong-message');
      $error_message.text('会员卡密码格式错误');
      $error_message.show();
      able_button($button);
      return;
    }
    var url = $button.attr('url');
    $.post(url, {number:number, password:password}, function(data) {
      console.log(data);
      if (data.status == 0 || data.status == 1) {
        $button.text('登录成功，跳转中...');
        window.location.href = data.url;
      } else if(data.status == 1001) {
        var $error_message = $('#login-card .ca-wrong-message');
        $error_message.text('会员卡卡号或密码错误，请重试');
        $error_message.show();
        able_button($button);
      } else {
        var $error_message = $('#login-card .ca-wrong-message');
        $error_message.text('登录失败，请重试');
        $error_message.show();
        able_button($button);
      }
    });
  });


  // 手机号登录
  $('#login-phone-btn').click(function () {
    var $button = $(this);
    disable_button($button);
    var number = $('#login-phone-number').val();
    var password = $('#login-phone-password').val();
    if (!reg_exp_phone(number)) {
      var $error_message = $('#login-phone .ca-wrong-message');
      $error_message.text('手机号格式错误');
      $error_message.show();
      able_button($button);
      return;
    }
    if (!reg_exp_password(password)) {
      var $error_message = $('#login-phone .ca-wrong-message');
      $error_message.text('密码格式错误');
      $error_message.show();
      able_button($button);
      return;
    }
    var url = $button.attr('url');
    $.post(url, {number:number, password:password}, function() {

    });
  });

  /*
   * 注册部分
   **/

  // 来访者注册
  $('#signup-user-btn').click(function () {
    var $button = $(this);
    disable_button($button);
    var $error_message = $('#signup-user-panel .ca-wrong-message');
    var phone = $('#signup-user-phone').val();
    var password = $('#signup-user-password').val();
    var password_confirm = $('#signup-user-password-confirm').val();
    if (!reg_exp_phone(phone)) {
      $error_message.text('手机号格式错误');
      $error_message.show();
      able_button($button);
      return;
    }
    if (!reg_exp_password(password)) {
      $error_message.text('密码格式错误');
      $error_message.show();
      able_button($button);
      return;
    }
    if (password != password_confirm) {
      $error_message.text('两次密码不一致');
      $error_message.show();
      able_button($button);
      return;
    }
    var url = $button.attr('url');
    var form_data = {
      'phone': phone,
      'password': password,
      'password_confirm': password_confirm
    };
    $.post(url, form_data, function(data) {
      if (data.status == 0) {
        $button.text('注册成功，跳转中...');
        window.location.href = data.url;
      } else if (data.status == 2001) {
        $error_message.text('手机号格式错误');
        $error_message.show();
        able_button($button);
      } else if (data.status == 2002) {
        $error_message.text('密码格式错误');
        $error_message.show();
        able_button($button);
      } else if (data.status == 2003) {
        $error_message.text('两次密码不一致');
        $error_message.show();
        able_button($button);
      } else if (data.status == 2004) {
        var login_url = $('#signup-login-p a').attr('href');
        $error_message.html('手机号已存在，您可以直接<a href="'+login_url+'">登录</a>');
        $error_message.show();
        able_button($button);
      } else if (data.status == 2005) {
        $error_message.text('注册失败，请重试');
        $error_message.show();
        able_button($button);
      }
    });
  });


  // 咨询师注册
  $('#signup-teacher-btn').click(function () {
    var $button = $(this);
    disable_button($button);
    var $error_message = $('#signup-teacher-panel .ca-wrong-message');
    var phone = $('#signup-teacher-phone').val();
    var password = $('#signup-teacher-password').val();
    var password_confirm = $('#signup-teacher-password-confirm').val();
    if (!reg_exp_phone(phone)) {
      $error_message.text('手机号格式错误');
      $error_message.show();
      able_button($button);
      return;
    }
    if (!reg_exp_password(password)) {
      $error_message.text('密码格式错误');
      $error_message.show();
      able_button($button);
      return;
    }
    if (password != password_confirm) {
      $error_message.text('两次密码不一致');
      $error_message.show();
      able_button($button);
      return;
    }
    var url = $button.attr('url');
    var form_data = {
      'phone': phone,
      'password': password,
      'password_confirm': password_confirm
    };
    $.post(url, form_data, function(data) {
      if (data.status == 0) {
        $button.text('登录成功，跳转中...');
        window.location.href = data.url;
      } else if (data.status == 2001) {
        $error_message.text('手机号格式错误');
        $error_message.show();
        able_button($button);
      } else if (data.status == 2002) {
        $error_message.text('密码格式错误');
        $error_message.show();
        able_button($button);
      } else if (data.status == 2003) {
        $error_message.text('两次密码不一致');
        $error_message.show();
        able_button($button);
      } else if (data.status == 2004) {
        var login_url = $('#signup-login-p a').attr('href');
        $error_message.html('手机号已存在，您可以直接<a href="'+login_url+'">登录</a>');
        $error_message.show();
        able_button($button);
      } else if (data.status == 2005) {
        $error_message.text('注册失败，请重试');
        $error_message.show();
        able_button($button);
      }
    });
  });


  /*
   * 完善信息
   **/
  var signup_user_type = 0;
  // 显示更多信息-高中
  $('#signup-userinfo-senior').click(function () {
    $('.ca-signup-choose-form').addClass('ca-signup-hide');
    $('#singnup-userinfo-form-school').removeClass('ca-signup-hide');
    $('#singnup-userinfo-form-student-type').removeClass('ca-signup-hide');
    signup_user_type = 1;
    able_button($('#singnup-userinfo-btn'));
  });


  // 显示更多信息-大学
  $('#signup-userinfo-university').click(function () {
    $('.ca-signup-choose-form').addClass('ca-signup-hide');
    $('#singnup-userinfo-form-school').removeClass('ca-signup-hide');
    $('#singnup-userinfo-form-college').removeClass('ca-signup-hide');
    $('#singnup-userinfo-form-student-type').removeClass('ca-signup-hide');
    signup_user_type = 2;
    able_button($('#singnup-userinfo-btn'));
  });


  // 显示更多信息-工作
  $('#signup-userinfo-work').click(function () {
    $('.ca-signup-choose-form').addClass('ca-signup-hide');
    $('#singnup-userinfo-form-work').removeClass('ca-signup-hide');
    signup_user_type = 3;
    able_button($('#singnup-userinfo-btn'));
  });


  // 完善来访者信息
  $('#singnup-userinfo-btn').click(function () {
    var $button = $(this);
    disable_button($button);
    var $error_message = $('#signup-userinfo-panel .ca-wrong-message');
    $error_message.hide();
    var post_data = {};
    var name = $('#signup-userinfo-name').val();
    if (!name.length > 0) {
      $error_message.text('请填写您的姓名');
      $error_message.show();
      able_button($button);
      return;
    }
    post_data.name = name;
    //console.log(post_data);

    var gender = $('#signup-userinfo-gender input[type="radio"]:checked').val();
    if (gender != 1 && gender != 2) {
      $error_message.text('请选择您的性别');
      $error_message.show();
      able_button($button);
      return;
    }
    post_data.gender = gender;
    //console.log(post_data);

    var email = $('#signup-userinfo-email').val();
    if (!reg_exp_email(email)) {
      $error_message.text('邮箱格式错误');
      $error_message.show();
      able_button($button);
      return;
    }
    post_data.email = email;
    //console.log(post_data);

    var city = $('#signup-userinfo-city').val();
    if (city == '请选择您所在城市' || city == '') {
      $error_message.text('请选择您所在城市');
      $error_message.show();
      able_button($button);
      return;
    }
    post_data.city = city;
    //console.log(post_data);

    var status = $('#signup-userinfo-status input[type="radio"]:checked').val();
    if (status != 1 && status != 2 && status != 30) {
      $error_message.text('请选择您当前学习或工作状态');
      $error_message.show();
      able_button($button);
      return;
    }
    post_data.status = status;
    //console.log(post_data);

    if (signup_user_type == 1) {
      var school =  $('#signup-userinfo-school').val();
      if (!school.length > 0) {
        $error_message.text('请填写您当前学校名称');
        $error_message.show();
        able_button($button);
        return;
      }
      post_data.school = school;
      if($('#signup-userinfo-student-type-1').is(':checked') == true) {
        var student_type_1 = '1';
      } else {
        var student_type_1 = '0';
      }
      if($('#signup-userinfo-student-type-2').is(':checked') == true) {
        var student_type_2 = '1';
      } else {
        var student_type_2 = '0';
      }
      if($('#signup-userinfo-student-type-3').is(':checked') == true) {
        var student_type_3 = '1';
      } else {
        var student_type_3 = '0';
      }
      if($('#signup-userinfo-student-type-4').is(':checked') == true) {
        var student_type_4 = '1';
      } else {
        var student_type_4 = '0';
      }
      var student_type = student_type_1 + student_type_2 + student_type_3 + student_type_4;
      post_data.student_type = student_type;
    } else if (signup_user_type == 2) {
      var school =  $('#signup-userinfo-school').val();
      if (!school.length > 0) {
        $error_message.text('请填写您当前学校名称');
        $error_message.show();
        able_button($button);
        return;
      }
      post_data.school = school;

      var college = $('#signup-userinfo-college').val();
      //console.log(college);
      if (!college.length > 0) {
        $error_message.text('请填写您的专业');
        $error_message.show();
        able_button($button);
        return;
      }
      post_data.college = college;

      if($('#signup-userinfo-student-type-1').is(':checked') == true) {
        var student_type_1 = '1';
      } else {
        var student_type_1 = '0';
      }
      if($('#signup-userinfo-student-type-2').is(':checked') == true) {
        var student_type_2 = '1';
      } else {
        var student_type_2 = '0';
      }
      if($('#signup-userinfo-student-type-3').is(':checked') == true) {
        var student_type_3 = '1';
      } else {
        var student_type_3 = '0';
      }
      if($('#signup-userinfo-student-type-4').is(':checked') == true) {
        var student_type_4 = '1';
      } else {
        var student_type_4 = '0';
      }
      var student_type = student_type_1 + student_type_2 + student_type_3 + student_type_4;
      post_data.student_type = student_type;
    } else if (signup_user_type == 3) {
      status = $('#singnup-userinfo-worktime input[type="radio"]:checked').val();
      if (status != 3 && status != 4 && status != 5 && status != 6) {
        $error_message.text('请选择您的工作时间');
        $error_message.show();
        able_button($button);
        return;
      }
      post_data.status = status;
    }
    var url = $button.attr('url');
    $.post(url, post_data, function(data) {
      // console.log(data);
      if (data.status == 0) {
        $button.text('成功，跳转中...');
        window.location.href = data.url;
      } else if (data.status == 3000) {
        $error_message.text('注册失败，请重试');
        $error_message.show();
        able_button($button);
      } else if (data.status == 3001) {
        $error_message.text('注册失败，请重试');
        $error_message.show();
        able_button($button);
      } else if (data.status == 3002) {
        $error_message.text('姓名格式错误，请检查后重新提交');
        $error_message.show();
        able_button($button);
      } else if (data.status == 3003) {
        $error_message.text('性别格式错误，请检查后重新提交');
        $error_message.show();
        able_button($button);
      } else if (data.status == 3004) {
        $error_message.text('邮箱格式错误，请检查后重新提交');
        $error_message.show();
        able_button($button);
      } else if (data.status == 3005) {
        $error_message.text('地点格式错误，请检查后重新提交');
        $error_message.show();
        able_button($button);
      } else if (data.status == 3006) {
        $error_message.text('学习或工作状态格式错误，请检查后重新提交');
        $error_message.show();
        able_button($button);
      } else if (data.status == 3007) {
        $error_message.text('学校格式错误，请检查后重新提交');
        $error_message.show();
        able_button($button);
      }else if (data.status == 3008) {
        $error_message.text('学生类别格式错误，请检查后重新提交');
        $error_message.show();
        able_button($button);
      }else if (data.status == 3009) {
        $error_message.text('专业格式错误，请检查后重新提交');
        $error_message.show();
        able_button($button);
      } else if (data.status == 3011) {
        $error_message.text('注册失败，请重试');
        $error_message.show();
        able_button($button);
      } else {
        $error_message.text('注册失败，请重试');
        $error_message.show();
        able_button($button);
      }
    });
  });


  // 完善咨询师信息
  $('#singnup-teacherinfo-btn').click(function () {
    var $button = $(this);
    disable_button($button);
    var $error_message = $('#signup-teacherinfo-panel .ca-wrong-message');
    $error_message.hide();
    var post_data = {};
    var name = $('#signup-teacherinfo-name').val();
    if (!name.length > 0) {
      $error_message.text('请填写您的姓名');
      $error_message.show();
      able_button($button);
      return;
    }
    post_data.name = name;
    //console.log(post_data);

    var gender = $('#signup-teacherinfo-gender input[type="radio"]:checked').val();
    if (gender != 1 && gender != 2) {
      $error_message.text('请选择您的性别');
      $error_message.show();
      able_button($button);
      return;
    }
    post_data.gender = gender;
    //console.log(post_data);

    var email = $('#signup-teacherinfo-email').val();
    if (!reg_exp_email(email)) {
      $error_message.text('邮箱格式错误');
      $error_message.show();
      able_button($button);
      return;
    }
    post_data.email = email;
    //console.log(post_data);

    var city = $('#signup-teacherinfo-city').val();
    if (city == '请选择您所在城市' || city == '') {
      $error_message.text('请选择您所在城市');
      $error_message.show();
      able_button($button);
      return;
    }
    post_data.city = city;
    // console.log(post_data);

    if($('#signup-teacherinfo-service-type-1').is(':checked') == true) {
      var service_type_1 = '1';
    } else {
      var service_type_1 = '0';
    }
    if($('#signup-teacherinfo-service-type-2').is(':checked') == true) {
      var service_type_2 = '2';
    } else {
      var service_type_2 = '0';
    }
    if($('#signup-teacherinfo-service-type-3').is(':checked') == true) {
      var service_type_3 = '3';
    } else {
      var service_type_3 = '0';
    }
    if($('#signup-teacherinfo-service-type-4').is(':checked') == true) {
      var service_type_4 = '4';
    } else {
      var service_type_4 = '0';
    }
    if($('#signup-teacherinfo-service-type-5').is(':checked') == true) {
      var service_type_5 = '5';
    } else {
      var service_type_5 = '0';
    }
    if($('#signup-teacherinfo-service-type-6').is(':checked') == true) {
      var service_type_6 = '6';
    } else {
      var service_type_6 = '0';
    }
    var service_type = service_type_1 + service_type_2 + service_type_3 + service_type_4 + service_type_5 + service_type_6;
    if (service_type == '000000') {
      $error_message.text('至少选择一项服务类型');
      $error_message.show();
      able_button($button);
      return;
    }
    post_data.service_type = service_type;
   // console.log(post_data);

    if($('#signup-teacherinfo-certificate-1').is(':checked') == true) {
      var certificate_1 = '1';
    } else {
      var certificate_1 = '0';
    }
    if($('#signup-teacherinfo-certificate-2').is(':checked') == true) {
      var certificate_2 = '2';
    } else {
      var certificate_2 = '0';
    }
    var certificate = certificate_1 + certificate_2;
    post_data.certificate = certificate;
    // console.log(post_data);

    var url = $button.attr('url');
    //able_button($button);
    $.post(url, post_data, function(data) {
      //console.log(data);
      if (data.status == 0) {
        //console.log('scuess');
        able_button($button);
        $button.text('提交成功，跳转中...');
        window.location.href = data.url;
      } else if (data.status == 3100) {
        $error_message.text('提交失败，请重试');
        $error_message.show();
        able_button($button);
      } else if (data.status == 3101) {
        $error_message.text('提交失败，请重试');
        $error_message.show();
        able_button($button);
      } else if (data.status == 3102) {
        $error_message.text('姓名格式错误，请检查后重新提交');
        $error_message.show();
        able_button($button);
      } else if (data.status == 3103) {
        $error_message.text('性别格式错误，请检查后重新提交');
        $error_message.show();
        able_button($button);
      } else if (data.status == 3104) {
        $error_message.text('邮箱格式错误，请检查后重新提交');
        $error_message.show();
        able_button($button);
      } else if (data.status == 3105) {
        $error_message.text('地点格式错误，请检查后重新提交');
        $error_message.show();
        able_button($button);
      } else if (data.status == 3106) {
        $error_message.text('至少选择一项服务类型，请检查后重新提交');
        $error_message.show();
        able_button($button);
      } else if (data.status == 3107) {
        $error_message.text('提交失败，请重试');
        $error_message.show();
        able_button($button);
      } else {
        $error_message.text('提交失败，请重试');
        $error_message.show();
        able_button($button);
      }
    });

  });


  // 点击按钮后，禁用按钮
  function disable_button($element) {
    $element.attr('disabled', true);
    $element.append('<i class="fa fa-cog fa-spin"></i>');
    $element.addClass('disabled');
  }


  // 后台返回了结果，激活按钮
  function able_button($element) {
    $element.removeClass('disabled');
    $element.find('i').remove();
    $element.attr('disabled', false);
  }



  // 验证手机号
  function reg_exp_phone(string) {
    var pattern =  /^(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/;
    if (pattern.test(string)) {
      return true;
    } else {
      return false;
    }
  }


  // 验证邮箱
  function reg_exp_email(string) {
    var pattern = /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$/;
    if(string != undefined && pattern.test(string)) {
      return true;
    } else {
      return false;
    }
  }


  // 验证密码，6-20为不含引号字符串
  function reg_exp_password(string) {
    var pattern = /^[^\'\"]{6,20}$/;
    if (pattern.test(string)) {
      return true;
    } else {
      return false;
    }
  }


  // 验证6-20位纯数字
  function reg_exp_number(string) {
    var pattern = /^[0-9 ]{6,20}$/;
    if (pattern.test(string)) {
      return true;
    } else {
      return false;
    }
  }




})(jQuery);