(function($) {
  resetToastPosition = function() {
    $('.jq-toast-wrap').removeClass('bottom-left bottom-right top-left top-right mid-center'); // to remove previous position class
    $(".jq-toast-wrap").css({
      "top": "",
      "left": "",
      "bottom": "",
      "right": ""
    }); //to remove previous position style
  }
  
  showToast = function(vTitle,vText,vIcon,vBgcolor) {
    'use strict';
    resetToastPosition();
    $.toast({
		title:String(vTitle),
      text: String(vText),
      showHideTransition: 'slide',
      icon: String(vIcon), //success, info, warning, danger
      loaderBg: String(vBgcolor),
      position: 'top-center'
    });
  }
})(jQuery);