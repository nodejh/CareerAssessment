(function ($) {

  initialize();


  /**
   * 初始化页面
   */
  function initialize() {
    set_img_height();
    select_time();
    save_time();
  }


  /**
   * 图片高度
   */
  function set_img_height() {
    // 图片高度
    var imgWidth =  $('.thumbnail').width();
    var imgHeight = parseInt(imgWidth) * 0.618;
    $('.thumbnail img').height(imgHeight);
  }


  /**
   * 选择/取消选择空闲时间
   */
   function select_time() {
    // 选择空闲时间
    $('.ca-table-week td.ca-td-time').click(function () {
      var text = $(this).text();
      if (text == '可预约') {
        $(this).text('不可预约');
        $(this).removeClass('ca-free-time').addClass('ca-no-free-time');
      } else if (text == '不可预约') {
        $(this).text('可预约');
        $(this).removeClass('ca-no-free-time').addClass('ca-free-time');
      }
    });
  }


  /**
   * 覆盖页面
   */
  function set_wrap_page() {
    var bodyWidth = document.documentElement.clientWidth;
    var bodyHeight =Math.max(document.documentElement.clientHeight,document.body.scrollHeight);
    $("<div class='ca-wrap'</div>").appendTo("body");
    $(".ca-wrap").width(bodyWidth);
    $(".wrap").height(bodyHeight);
  }


  /**
   *
   */
  function delete_wrap_page() {
    $(".ca-wrap").remove();
  }


  /**
   * 保存空闲时间表
   */
  function save_time() {
    $('#time-button').click(function () {
      set_wrap_page();



      delete_wrap_page();
    });
  }



})(jQuery);