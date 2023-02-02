function checkPW(){
  var val = $('#pw').val();
  
  var strength = 'weak';
  //pseudo pw strength
  if(val.length > 3) strength = 'medium';
  if(val.length > 6) strength = 'strong';
  
  var width = val.length * 10;
  
  if(width > 100) width = 100;
  if(width < 1) width = 1;
  
  console.log(width);
  $('.input span').removeAttr('class').addClass(strength).css('width', width + '%');
}
$(document).ready(function(){
  $('#pw').on('keyup', checkPW).focus();
  checkPW();
});