(function ($) {

  var imgWidth =  $('.thumbnail').width();
  var imgHeight = parseInt(imgWidth) * 0.618;
  $('.thumbnail img').height(imgHeight);


})(jQuery);