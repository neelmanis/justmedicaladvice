$(document).ready(function(){equalheight=function(t){var i,e=0,h=0,r=new Array;$(t).each(function(){if(i=$(this),$(i).height("auto"),topPostion=i.position().top,h!=topPostion){for(currentDiv=0;currentDiv<r.length;currentDiv++)r[currentDiv].height(e);r.length=0,h=topPostion,e=i.height(),r.push(i)}else r.push(i),e=e<i.height()?i.height():e;for(currentDiv=0;currentDiv<r.length;currentDiv++)r[currentDiv].height(e)})};$(window).load(function(){equalheight('.topicBox')});$(window).resize(function(){equalheight('.topicBox')});$(".catCheck").click(function(){var count=$(".catCheck:checked").length;if(count==0){$("#submit").prop('disabled',!0);$(window).scrollTop(0);$("#formError").css("display","block");$("#formError").html("<b>Please Select Atleast One Option<b>")}else{$("#submit").prop('disabled',!1);$("#formError").css("display","none")}});$("#catSelect").on("submit",function(e){e.preventDefault();var formdata=new FormData(this);$.ajax({type:"POST",url:CI_ROOT+"member/updateInterest",data:formdata,processData:!1,contentType:!1,mimeType:'multipart/form-data',beforeSend:function(){$("#preloader").show()},success:function(result){$("#preloader").hide();if(result==1){$(window).scrollTop(0);$("#formSuccess").css("display","block");$("#formSuccess").html("<b>Please Select Atleast One Action.</b>").delay(5000).fadeOut()}else if(result==2){$(window).scrollTop(0);$("#formSuccess").css("display","block");$("#formSuccess").html("<b>Your Profile is Successfully Updated.</b>").delay(5000).fadeOut();window.location.href=CI_ROOT+"member/home"}}})})})