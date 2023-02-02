(function($) {

/* showAlertBlank = function(vType,vTitle,vText,vURL){
	Swal.fire({
		icon: vType,
		title: vTitle,
		text: vText,
		showCancelButton: false
	},
	function(){
		window.location.href = vURL;
	});
};	 */
showAlertBlank = function(valid,valtext,valtextfieldid,type){
	/* valtextfieldid.parent().addClass('has-danger');
	valtextfieldid.after('<label id="' + valid + '" class="error mt-2 text-danger">'+valtext+'</label>'); */
	
	if(type=="blank"){
		alert("k");
	}
	if(type=="IsEmail"){
		alert("k1");
	}
	else{
		alert("else");
	}
	
function IsEmail(email) {
	var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	return regex.test(email);
}
};	
	
})(jQuery);