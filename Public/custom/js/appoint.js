// 预约
$(document).ready(function () {

  initialize();


  /**
   * 页面初始化
   */
  function initialize() {
    appoint_time_inilialize();
    click_to_appoint();
    appoint();
  }


  /**
   * 预约时间表初始化
   */
  function appoint_time_inilialize() {

    var freeTime = $('#time-free-time').text();
    var freeTimeArray = freeTime.split(',');
    $caTdTime = $('td.ca-td-time');
    //$('#time-free-time');
    $caTdTime.each(function () {
      for (var i in freeTimeArray) {
        if ($(this).attr('value') == freeTimeArray[i]) {
          $(this).removeClass('ca-no-free-time').addClass('ca-can-appoint');
          $(this).text('可预约');
        }
      }
    });

    var $noFreeTime = $('.ca-no-free-time');
    $noFreeTime.each(function () {
      $(this).removeClass('ca-no-free-time').addClass('ca-cannot-appoint');
    });
  }


  /**
   * 选择预约时间
   */
  function click_to_appoint() {
    $('.ca-can-appoint').click(function () {
      // $(this).addClass('ca-appoint');
      //$('.ca-can-appoint').not(this).each(function () {
      //  if ($(this).hasClass('ca-appoint')) {
      //    $(this).removeClass('ca-appoint');
      //  }
      //});
      if ($(this).hasClass('ca-appoint')) {
        $(this).removeClass('ca-appoint');
      } else {
        var length = $('.ca-appoint').length;
        console.log(length);
        if (length >= 3) {
          alert('总预约次数超过3次需要缴纳预约费用');
        } else {
          $(this).addClass('ca-appoint');
        }
      }

    });
  }


  /**
   * 预约
   */
  function appoint() {
    $('#home-btn-appoint').click(function () {
      //console.log('a');
      var type = $('#nav-user').attr('data-type');
      //console.log(type);
      if (type == 1) {
        var $timeElemt = $('.ca-appoint');
        var time = '';
        $timeElemt.each(function () {
          var val = $(this).attr('value');
          time += val + ',';
        });
        //console.log(time);
        //var time = $('.ca-appoint').attr('value');

        if (time.length > 0) {
          time = time.substring(0, time.length -1);
          var url = $(this).attr('data-url');
          var teacher_id = $('#appoint-teacher-name').attr('data-id');
          var user_id = $('#nav-user').attr('data-id');
          var data = {
            'teacher_id': teacher_id,
            'user_id': user_id,
            'time': time
          };
          console.log(data);

          //console.log(user_id);
          //console.log(url);
          $.post(url, data, function (result) {
            console.log(result);
            if (result.status == 0) {
              alert('预约成功');
              //location.reload();
              location.href = result.url;
            } else {
              alert('预约失败，请重试');
              //location.reload();
            }
          });

        } else {
          alert('请选择预约时间');
        }
      } else {
        alert('请您先登录后再预约');
      }
    });
  }

});