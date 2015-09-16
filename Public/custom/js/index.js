(function ($) {

  initialize();


  /**
   * 初始化页面
   */
  function initialize() {
    set_img_height();
    set_select();
  }


  /**
   * 选择条件初始化
   */
  function set_select() {
    // 城市输入框失去焦点
    $('#index-city').blur(function () {
      // TODO fix bug
      // 这里是点击选择地点。当点击的时候，input 框就会失去焦点，就会先提交
    });

    // 选择性别
    $('#index-gender').change(function () {
      post_action();
    });

    // 选择从业时间
    $('#index-time').change(function () {
      post_action();
    });

    // 服务类型
    $('#index-service-type-1').click(function () {
      post_action();
    });
    $('#index-service-type-2').click(function () {
      post_action();
    });
    $('#index-service-type-3').click(function () {
      post_action();
    });

    // 咨询师类型
    $('#index-certificate-type-a').click(function () {
      post_action();
    });
    $('#index-certificate-type-b').click(function () {
      post_action();
    });
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
   * @description 获取地点
   * @return {string} city
   */
  function get_city() {
    var city = $('#index-city').val();
    return city;
  }


  /**
   * @description 获取性别
   * @returns {string}
   */
  function get_gender() {
    var gender = $('#index-gender option:selected').val();
    return gender;
  }


  /**
   * 从业时间
   * @returns {string}
   */
  function get_time() {
    var time = $('#index-time option:selected').val();
    if (time == '不限') {
      time =0;
    }
    return time;
  }


  /**
   * 服务类型
   * @returns {object}
   */
  function  get_service_type() {
    var service_type = {};
    if ($('#index-service-type-1').is(':checked') == true) {
      service_type.a = $('#index-service-type-1').val();
    } else {
      service_type.a = '';
    }
    if ($('#index-service-type-2').is(':checked') == true) {
      service_type.b = $('#index-service-type-2').val();
    } else {
      service_type.b = '';
    }
    if ($('#index-service-type-3').is(':checked') == true) {
      service_type.c = $('#index-service-type-3').val();
    } else {
      service_type.c = '';
    }
    return service_type;
  }


  /**
   * 咨询类型
   * @returns {object}
   */
  function get_certificate_type() {
    var certificate_type = {};
    if ($('#index-certificate-type-a').is(':checked') == true) {
      certificate_type.a = $('#index-certificate-type-a').val();
    } else {
      certificate_type.a = '';
    }
    if ($('#index-certificate-type-b').is(':checked') == true) {
      certificate_type.b = $('#index-certificate-type-b').val();
    } else {
      certificate_type.b = '';
    }
    return certificate_type;
  }


  /**
   * 表单url
   * @returns {string}
   */
  function get_url() {
    var url = $('#index-form').attr('action');
    return url;
  }


  /**
   * 表单数据
   * @returns {{city: string, gender: string, time: string, service_type: Object, get_certificate_type: Object}}
   */
  function get_post_data() {
    var service_type = get_service_type();
    var certificate_type = get_certificate_type();
    var post = {
      'city': get_city(),
      'gender': get_gender(),
      'time': get_time(),
      'service_type_a': service_type.a,
      'service_type_b': service_type.b,
      'service_type_c': service_type.c,
      'certificate_type_a': certificate_type.a,
      'certificate_type_b': certificate_type.b
    };
    return post;
  }


  /**
   * post 操作
   */
  function post_action() {
    var url = get_url();
    var post_data = get_post_data();
    console.log(post_data);
    render_empty('index-list');
    render_animation('index-list');

    $.post(url, post_data, function (result) {
      console.log(result);
      if (result.status == 0) {
        render_dom('index-list', result.result);
      } else if (result.status == 1) {
        alert('查询失败，请重试');
      } else {
        location.reload(true);
      }
    });
  }


  /**
   * 清空页面节点
   * @param id
   */
  function render_empty(id) {
    $('#'+id).empty();
  }

  /**
   * 加载动画
   */
  function render_animation(id) {
    var dom = '<div class="ca-index-fa-spin"><i class="fa fa-spinner fa-spin fa-3x" id="index-animation"></i></div>';
    $('#'+id).append(dom);
  }


  /**
   * 渲染 html 节点
   * @param dom
   */
  function render_dom(id, data) {
    var html = '';
    if (data.length > 0) {
      for (var i in data) {
        html += '<div class="col-lg-3 col-md-6">' +
          '<div class="thumbnail">' +
          '<img src="/CareerAssessment/Uploads/'+data[i].avatar+'" alt="'+data[i].name+'">' +
          '<div class="caption">' +
          '<h4>'+ data[i].name +' <small> '+data[i].city+'</small></h4>' +
          '<p>' +
          '<small>专业程度</small>' +
          '<br>' +
          '<small>服务态度</small>' +
          '</p>' +
          '<p>' +
          '<a href="#" class="btn btn-primary" role="button">预约</a>' +
          '<a href="#" class="btn btn-default" role="button">详情</a></p>' +
          '</div>'+
          '</div>'+
          '</div>';
      }
    } else {
      html = '<div class="alert alert-success" role="alert">暂无信息</div>'
    }
    render_empty('index-list');
    $('#'+id).append(html);
    set_img_height();
  }



})(jQuery);