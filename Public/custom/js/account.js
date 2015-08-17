(function ($) {

  // TODO 表单验证
  // 会员注册－手机号
  $('#user-btn-signup').click(function () {
    var password = $('#user-password').val();
    var password_confirm = $('#user-password-confirm').val();
    if (password == password_confirm) {
      var phone = $('#user-phone').val();
      var signup_url = $(this).attr('url');
      $.post(signup_url, {phone: phone, password: password, password_confirm: password_confirm, type: 1}, function(data) {
        console.log(data);
      });
    } else {
      alert('两次密码不一致！');
    }
  });


  // 咨询师注册－手机号
  $('#teacher-btn-signup').click(function () {
    var password = $('#teacher-password').val();
    var password_confirm = $('#teacher-password-confirm').val();
    if (password == password_confirm) {
      var phone = $('#teacher-phone').val();
      var signup_url = $(this).attr('url');
      $.post(signup_url, {phone: phone, password: password, password_confirm: password_confirm, type: 2}, function(data) {
        console.log(data);
      });
    } else {
      alert('两次密码不一致！');
    }
  });



})(jQuery);