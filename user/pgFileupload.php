<?php
include_once "commonFunctions.php";
$commonfunction=new Common();
$settingValueUserImagePathOther =$commonfunction->getSettingValue("UserImagePathOther"); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
	<link rel="stylesheet" href="../assets/css/custom/sweetalert2.min.css">
</head>
<body>
<div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left py-5 px-4 px-sm-5">
              <div class="brand-logo">
               <img src="<?php //echo $settingValueUserImagePathOther."logo.png";?>" alt="logo" style="width:50%;">
              </div>
               <div>
				<div align="center">
					<label for="exampleInputFile">File Upload</label>
					<input type="file" name="file" id="file"  size="150">
					<p class="help-block">Only Excel/CSV File Import.</p>
					<button  type="submit"  class="btn btn-default"  onclick="uploadFile();">Upload</button>
				
				</div>
				
			</div>
                </div>  
               </div>
              </div>
             </div>
            </div>
          </div>
         </div>		  

<script src="../assets/css/jquery/jquery.min.js"></script>
<script src="../assets/js/custom/twCommonValidation.js"></script>
<script type='text/javascript'>
	// Upload file
function uploadFile() {

   var files = document.getElementById("file").files;
   if(files.length > 0 ){

      var formData = new FormData();
      formData.append("file", files[0]);

      var xhttp = new XMLHttpRequest();
      // Set POST method and ajax file path
      xhttp.open("POST", "apiFileUpload.php", true);

      // call on request changes state
      xhttp.onreadystatechange = function() {
         if (this.readyState == 4 && this.status == 200) {

           var response = this.responseText;
		   //console.log(response);
           if(response == 1){
              alert("Upload successfully.");
			  
           }else{
              alert("File not uploaded.");
           }
         }
      };
      // Send request with data
      xhttp.send(formData);

   }else{
      alert("Please select a file");
   }

} 
</script>	
</body>
</html>
