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
      var $error_message = $('#card .ca-wrong-message');
      $error_message.text('会员卡卡号格式错误');
      $error_message.show();
      able_button($button);
      return;
    }
    if (!reg_exp_password(password)) {
      var $error_message = $('#card .ca-wrong-message');
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
        var $error_message = $('#card .ca-wrong-message');
        $error_message.text('会员卡卡号或密码错误，请重试');
        $error_message.show();
        able_button($button);
      } else {
        var $error_message = $('#card .ca-wrong-message');
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
      var $error_message = $('#phone .ca-wrong-message');
      $error_message.text('手机号格式错误');
      $error_message.show();
      able_button($button);
      return;
    }
    if (!reg_exp_password(password)) {
      var $error_message = $('#phone .ca-wrong-message');
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

  // 会员注册－手机号
  $('#user-btn-signup').click(function () {
    var $button = $(this);
    disable_button($button);
    var password = $('#user-password').val();
    var password_confirm = $('#user-password-confirm').val();
    if (password == password_confirm) {
      var phone = $('#user-phone').val();
      var signup_url = $button.attr('url');
      $.post(signup_url, {phone: phone, password: password, password_confirm: password_confirm, type: 1}, function(data) {
        console.log(data);
        if (data.status == 0 ) {
          able_button($button);
          window.location.href = data.url;
        } else {
          able_button($button);
          alert('注册失败，请重试');
        }
      });
    } else {
      alert('两次密码不一致！');
      able_button($button);
    }
  });


  // 咨询师注册－手机号
  $('#teacher-btn-signup').click(function () {
    var $button = $(this);
    disable_button($button);
    var password = $('#teacher-password').val();
    var password_confirm = $('#teacher-password-confirm').val();
    if (password == password_confirm) {
      var phone = $('#teacher-phone').val();
      var signup_url = $button.attr('url');
      $.post(signup_url, {phone: phone, password: password, password_confirm: password_confirm, type: 2}, function(data) {
        console.log(data);
        if (data.status == 0 ) {
          able_button($button);
          window.location.href = data.url;
        } else {
          able_button($button);
          alert('注册失败，请重试');
        }
      });
    } else {
      alert('两次密码不一致！');
      able_button($button);
    }
  });


  // 完善信息－会员
  $('#info-user-btn').click(function () {
    var $button = $(this);
    disable_button($button);

    var real_name = $('#info-user-real-name').val();
    var signup_userinfo_url = $button.attr('url');

    $.post(signup_userinfo_url, {real_name:real_name}, function (data) {
      if (data.status == 0 ) {
        able_button($button);
        window.location.href = data.url;
      } else {
        able_button($button);
        alert('失败，请重试');
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
    if(pattern.text(string)) {
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