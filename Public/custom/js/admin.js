(function ($) {

  initialize();


  /**
   * 初始化页面
   */
  function initialize() {
    // 主页
    set_img_height();

    // 咨询师页面
    teacher_time_inilialize();
    select_time();
    select_all_time();
    save_time();
  }


  /**
   * user 个人中心咨询师列表的咨询师头像图片高度
   */
  function set_img_height() {
    // 图片高度
    var imgWidth =  $('.thumbnail').width();
    var imgHeight = parseInt(imgWidth) * 0.618;
    $('.thumbnail img').height(imgHeight);
  }


  /**
   * time 咨询师时间表初始化：设置空闲时间和预约者姓名
   */
  function teacher_time_inilialize() {
    var freeTime = $('#time-free-time').text();
    var freeTimeArray = freeTime.split(',');

    var $caTdTime = $('td.ca-td-time');
    var $appointUser = $('#time-appoint-name').find('span');

    $caTdTime.each(function () {
      for (var i in freeTimeArray) {
        //var time = $(this).attr('value');
        if ($(this).attr('value') == freeTimeArray[i]) {
          $(this).removeClass('ca-no-free-time').addClass('ca-free-time');
          $(this).text('可预约');
        }
      }
    });
  }

  /**
   * 选择/取消选择空闲时间
   */
   function select_time() {
    // 选择空闲时间
    $('.ca-table-week td.ca-td-time').click(function () {
      var text = $(this).text();
      if (text == '可预约') {
        //$(this).text('不可预约');
        $(this)
          .removeClass('ca-free-time')
          .addClass('ca-no-free-time')
          .text('不可预约');
      } else if (text == '不可预约') {
        //$(this).text('可预约');
        $(this)
          .removeClass('ca-no-free-time')
          .addClass('ca-free-time')
          .text('可预约');
      }
    });
  }

  /**
   * 全选/取消全选 预约时间
   */
  function select_all_time() {
    $('#time-select-all').click(function () {
      //console.log($(this).is(':checked'));
      if ($(this).is(':checked')) {
        $('.ca-table-week td.ca-td-time')
          .removeClass('ca-no-free-time')
          .addClass('ca-free-time')
          .text('可预约');
      } else {
        $('.ca-table-week td.ca-td-time')
          .removeClass('ca-free-time')
          .addClass('ca-no-free-time')
          .text('不可预约');
      }
    });
  }

  /**
   * 获取所有空闲时间
   * @returns {Array} ["e-2015-09-21", "e-2015-09-22", "f-2015-09-21"]
   */
  function get_all_free_time() {
    var $caFreeTime = $('.ca-free-time');
    var allFreeTime = '';
    if ($caFreeTime.length > 0) {
      $caFreeTime.each(function () {
        allFreeTime += $(this).attr('value') + ',';
      });
    } else {
      allFreeTime = null;
    }
    if (allFreeTime != null) {
      allFreeTime = allFreeTime.substring(0, allFreeTime.length-1);
    }
    return allFreeTime;
  }


  /**
   * 保存空闲时间表
   */
  function save_time() {
    $('#time-button').click(function () {
      var allFreeTime = get_all_free_time();
      var url = $(this).attr('url');

      var data = {'time': allFreeTime};
      $.post(url, data, function (result) {
        if (result.status == 0) {
          $('#body-content').text('更新时间表成功!');
          $('#message').modal('show').on('hidden.bs.modal', function () {
            location.reload();
          });
        } else {
          $('#body-content').text('更新时间表失败，请重试!');
          $('#message').on('hidden.bs.modal', function () {
            location.reload();
          });
        }
      });
    });
  }



})(jQuery);