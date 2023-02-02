(function($) {
	showAlert = function(vType,vTitle,vText){
	Swal.fire({
		icon: vType,
		title: vTitle,
		text: vText,
		showCancelButton: false
	},
	function(){
		window.location.href = vURL;
	});
};	
 
	showAlertRedirect = function(vTitle,vText,vType,vURL){
	Swal.fire({
		title: vTitle,
		text: vText,
		type: vType,
		showCancelButton: false
	},
	function(){
		window.location.href = vURL;
	});
};
    showConfirmAlert = function(vTitle,vText,vIcon,vCallback){
	Swal.fire({
		title: vTitle,
		text: vText,
		icon: vIcon,
		showCancelButton: true
	}).then((confirmed) => {
        vCallback(confirmed && confirmed.value == true);
    });
};
	
})(jQuery);