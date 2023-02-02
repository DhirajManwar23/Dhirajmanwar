function validateEmail(inputText) 
{
	if (inputText.trim()=="")
	{
		return false;
	}
	else
	{
		var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		return regex.test(inputText);
	}
}
function validateBlank(inputText) 
{
	
	if (inputText.trim()=="")
	{
		return false;
	}
	else
	{
		return true;
	}
}
function isNumber(inputText) {
	
	let isnum = /^\d+$/.test(inputText);
	if (isnum) {
	  return true;
	}
	else{
		return false;
	}
		
}
function isFax(inputText) {
	
	let isnum = /^\d+$/.test(inputText);
	if (isnum) {
	  return true;
	}
	else{
		return false;
	}
		
}
function isCharacter(inputText) {
	if (/^[a-zA-Z]/.test(inputText)) {
	   return true;
	}
	else{
		return false;
	}
}
function isMobile(inputText) {
	var validateMobNum= /^\d*(?:\.\d{1,2})?$/;
	if (validateMobNum.test(inputText) && inputText.length == 10) {	
		return true;
	}
	else{
		return false;
	}
}
function isIfsc(inputText) {      
	var reg = /[A-Z|a-z]{4}[0][a-zA-Z0-9]{6}$/;    
	if (inputText.match(reg)) {    
		return true;    
	}    
	else {       
		return false;    
	}    
}
function isPan(inputText) {      
	var PAN_Card_No = inputText.toUpperCase();
    var regex = /([A-Z]){5}([0-9]){4}([A-Z]){1}$/;
    if (PAN_Card_No.length == 10) {
        if (PAN_Card_No.match(regex)) {
            return true;
			// alert("Valid Pan Number");
        } else {
			return false;    
            //alert("InValid Pan Number");
        }
    } else {
		return false;    
        //alert("Enter Valid Pan Number");
    }
}
function adminLogs(valpgName,valaction,valdata,valresult,valstatus) {
	
}
function userLogs(valpgName,valaction,valdata,valresult,valstatus) {
	
}
function employeeLogs(valpgName,valaction,valdata,valresult,valstatus) {
	
}

function disableButton(button_id,button_text)
{
	$(button_id).attr("disabled","true");
	$(button_id).removeClass('btn-success');
	$(button_id).addClass('btn-secondary');//secondary;
	$(button_id).css('cursor', 'no-drop');
	$(button_id).html(button_text);
}

function enableButton(button_id,button_text)
{
	$(button_id).removeAttr('disabled');
	$(button_id).removeClass('btn-warning');
	$(button_id).addClass('btn-success');
	$(button_id).css('cursor', 'pointer');
	$(button_id).html(button_text);
}

function viewPassword(password_field) {
	var type = $(password_field).attr('type');
	type=type.toLowerCase();
	if (type == "password") {
		$(password_field).attr('type', 'text');
	} else {
		$(password_field).attr('type', 'password');
	}
}


function checkPasswordStrength(indicator,input,weak,medium,strong,text){
let regExpWeak = /[a-z]/;
let regExpMedium = /\d+/;
let regExpStrong = /.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/;
  if(input.value != ""){
    indicator.style.display = "block";
    indicator.style.display = "flex";
    if(input.value.length <= 3 && (input.value.match(regExpWeak) || input.value.match(regExpMedium) || input.value.match(regExpStrong)))no=1;
    if(input.value.length >= 6 && ((input.value.match(regExpWeak) && input.value.match(regExpMedium)) || (input.value.match(regExpMedium) && input.value.match(regExpStrong)) || (input.value.match(regExpWeak) && input.value.match(regExpStrong))))no=2;
    if(input.value.length >= 6 && input.value.match(regExpWeak) && input.value.match(regExpMedium) && input.value.match(regExpStrong))no=3;
    if(no==1){
      weak.classList.add("active");
      text.style.display = "block";
      text.textContent = "Your password is too weak";
      text.classList.add("weak");
    }
    if(no==2){
      medium.classList.add("active");
      text.textContent = "Your password is medium";
      text.classList.add("medium");
    }else{
      medium.classList.remove("active");
      text.classList.remove("medium");
    }
    if(no==3){
      weak.classList.add("active");
      medium.classList.add("active");
      strong.classList.add("active");
      text.textContent = "Your password is strong";
      text.classList.add("strong");
    }else{
      strong.classList.remove("active");
      text.classList.remove("strong");
    }
  }else{
    indicator.style.display = "none";
    text.style.display = "none";
  }
}

function passwordLength(inputText)
{
	if (inputText.length>=6) {	
		return true;
	}
	else{
		return false;
	}
}