<?php 

function getPinCodeResi(id){
	$.ajax({
		type:"GET",
		url:"https://api.postalpincode.in/pincode/"+id,
		dataType:"JSON",
		data:{},
		success: function(response){
			if (response["0"]["Message"]!="No records found")
			{
				$("#residentalcity").val(response["0"]["PostOffice"]["0"]["Region"]);
				$("#residentalstate").val(response["0"]["PostOffice"]["0"]["State"]);
				$("#residentalstate").val(response["0"]["PostOffice"]["0"]["Country"]);
				$("#residentalphonestdcode").focus();
			}
			else
			{
				$("#residentalcity").focus();
			}
		}
	});
}
?>