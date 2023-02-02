(function($) {
	showAlert = function(vTitle,vText,vType){
	Swal.fire({
		icon: vType,
		allowOutsideClick: false, 
		title: vTitle,
		text: vText,
		showCancelButton: false,
		padding: "2rem"
	});
};	
 
	showAlertRedirect = function(vTitle,vText,vType,vURL){
	Swal.fire({
		title: vTitle,
		allowOutsideClick: false, 
		text: vText,
		icon: vType,
		showCancelButton: false,
		padding: "2rem"
	}).then(function (result) {
		if (result.value) {
			window.location = vURL;
		}
	});
};
    showConfirmAlert = function(vTitle,vText,vIcon,vCallback){
	Swal.fire({
		title: vTitle,
		allowOutsideClick: false, 
		text: vText,
		icon: vIcon,
		showCancelButton: true,
		padding: "2rem"
	}).then((confirmed) => {
        vCallback(confirmed && confirmed.value == true);
    });
};
	
})(jQuery);