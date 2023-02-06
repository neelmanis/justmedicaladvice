$(document).ready(function(){$('form').attr('autocomplete','off');$(function(){$("#loginForm").validate({rules:{mobile:{required:!0},pass:{required:!0}},messages:{mobile:"Please enter mobile number",pass:"Please enter your password"},submitHandler:function(form){var formdata=$("#loginForm").serialize();$.ajax({type:"POST",data:formdata,url:CI_ROOT+"login/submitLogin",beforeSend:function(){$("#pageLoader").show()},success:function(result){if(result==1){swal({title:"User Not Found!",text:"Entered mobile number not exist !! \nIf you are new user Signup Now",icon:"error",});$("#pageLoader").hide()}else if(result==2){$("#pageLoader").hide();$("#formError").css("display","block");$("#formError").html('<b>Username and Password does not match.</b>').delay(5000).fadeOut(2000)}else if(result==3){window.location.href=CI_ROOT+"doctor/home"}else if(result==4){window.location.href=CI_ROOT+"member/home"}else if(result==5){window.location.href=CI_ROOT+"registration/verifyotp"}else if(result==6){swal({title:"Account Deactivated",text:"You failed to verify otp in 3 attempts !! \nPlease try again after some time. !!",icon:"info",});$("#pageLoader").hide()}else if(result==7){swal({title:"Message",text:"Your account will be activated after document verification. \nThank You !!",icon:"info",});$("#pageLoader").hide()}else if(result==8){swal({title:"Account Deactivated",text:"Your account is deactivated by Just Medical Advice. Contact us for more details.",icon:"info",});$("#pageLoader").hide()}else if(result==9){window.location.href=CI_ROOT+"registration/verifyotp"}else if(result==10){window.location.href=CI_ROOT+"doctor/doctorProfile"}else if(result==11){window.location.href=CI_ROOT+"member/memberProfile"}else if(result==12){window.location.href=CI_ROOT+"doctor/doctorDocumentVerification"}else if(result==13){swal({title:"Something Went Wrong",text:"There is some problem while sending OTP !!\nPlease try again.",icon:"error",});$("#pageLoader").hide()}else if(result==14){window.location.href=CI_ROOT+"registration/set-password"}else if(result.search("blog")>0||result.search("media")>0||result.search("forum")>0||result.search("doctor")>0){window.location.href=result}else{$("#formError").css("display","block");$("#formError").html(result).delay(5000).fadeOut(2000);$("#pageLoader").hide()}}})}})});$(function(){$("#forgotPassword").validate({rules:{mobile:{required:!0}},messages:{mobile:"Please enter mobile number"},submitHandler:function(form){var formdata=$("#forgotPassword").serialize();$.ajax({type:"POST",data:formdata,url:CI_ROOT+"login/forgotPasswordAction",beforeSend:function(){$("#pageLoader").show()},success:function(result){if(result==1){$(".formerror").css("display","block");$(".errorcode").html('Some Error Occured While sending OTP');$("#pageLoader").hide()}else if(result==2){$(".formerror").css("display","block");$(".errorcode").html('Some Error Occured While sending OTP on given number. Please try again !');$("#pageLoader").hide()}else if(result==3){window.location.href=CI_ROOT+"login/verifyotp"}else if(result==4){$(".formerror").css("display","block");$(".errorcode").html('Entered Mobile Number does not exist.');$("#pageLoader").hide()}else{$(".formerror").css("display","block");$(".errorcode").html(result);$("#pageLoader").hide()}}})}})});$(function(){$("#verifyotp").validate({rules:{otp:{required:!0,number:!0}},messages:{required:"Please enter OTP",number:"Should contains numbers only."},submitHandler:function(form){var formdata=$("#verifyotp").serialize();$.ajax({type:"POST",data:formdata,url:CI_ROOT+"login/submitotp",beforeSend:function(){$("#pageLoader").show()},success:function(result){if(result==1){$(".formerror").css("display","block");$(".errorcode").html('You Have Entered wrong OTP.');$("#pageLoader").hide()}else if(result==2){window.location.href=CI_ROOT+"login/changePassword"}else{$(".formerror").css("display","block");$(".errorcode").html(result);$("#pageLoader").hide()}}})}})});$("#changePassword").validate({rules:{pass:{required:!0,},cnfPass:{required:!0,equalTo:"#pass"}},messages:{pass:{required:"Please Enter Your New Password"},cnfPass:{required:"Please Confirm Your New Password"}},submitHandler:function(form){var formdata=$("#changePassword").serialize();$.ajax({type:"POST",data:formdata,url:CI_ROOT+"login/changePasswordAction",beforeSend:function(){$("#pageLoader").show()},success:function(result){if(result==1){window.location.href=CI_ROOT+"login"}else if(result==2){window.location.href=CI_ROOT+"member/home"}else if(result==3){window.location.href=CI_ROOT+"doctor/home"}else{$(".formerror").css("display","block");$(".errorcode").html(result);$("#pageLoader").hide()}}})}})})