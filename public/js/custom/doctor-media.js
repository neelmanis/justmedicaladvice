$(document).ready(function(){var hashcode=window.location.hash;if(hashcode=='#comment'){$('html, body').animate({scrollTop:$(hashcode).offset().top-200},200);$('#comment').focus();$('#commentTrigger').addClass('comment_trigger')}else if(hashcode=='#share'){$('html, body').animate({scrollTop:$(hashcode).offset().top-200},200)}
$("#like_media").click(function(e){e.preventDefault();var mid=$('#mid').val();var utype=$('#utype').val();var uid=$('#uid').val();$.ajax({type:"POST",url:CI_ROOT+"media/addLike",data:{mediaId:mid,type:utype,user:uid},beforeSend:function(){$("#pageLoader").show()},success:function(result){if(result==1){window.location.reload()}else{swal({title:"Error !",icon:"error",text:result}).then(function(){$("#pageLoader").hide()})}}})});$("#report_media").click(function(){var mid=$('#mid').val();var utype=$('#utype').val();var uid=$('#uid').val();$.ajax({type:"POST",url:CI_ROOT+"media/blogReport",data:{mediaId:mid,type:utype,user:uid},beforeSend:function(){$("#pageLoader").show()},success:function(result){if(result==1){window.location.reload(!0)}else{swal({title:"Error !",icon:"error",text:result}).then(function(){$("#pageLoader").hide()})}}})});$("#postComment").click(function(e){e.preventDefault();var formdata=$("#comment_form").serialize();$.ajax({type:"POST",url:CI_ROOT+"media/addComment",data:formdata,beforeSend:function(){$("#pageLoader").show()},success:function(result){if(result==1){swal({title:"Successful !",icon:"success",text:"Comment is posted."}).then(function(){location.reload(!0)})}else{swal({title:"Error !",icon:"error",text:result}).then(function(){location.reload(!0)})}}})});$("a.reply").click(function(e){e.preventDefault();var id=$(this).attr("id");$("#pid").attr("value",id);$('html, body').animate({scrollTop:$("#commentToTop").offset().top-80},200);$('#comment').focus()});$('#comment').keypress(function(){$('#commentTrigger').removeClass('comment_trigger')});$("a.report").click(function(e){e.preventDefault();var id=$(this).attr("id");$.ajax({type:"POST",url:CI_ROOT+"media/addReport",data:{cid:id},beforeSend:function(){$("#pageLoader").show()},success:function(result){if(result==1){window.location.reload(!0)}else{swal({title:"Error !",icon:"error",text:result}).then(function(){$("#pageLoader").hide()})}}})});$("#postForum").click(function(e){e.preventDefault();var formdata=$("#ask_question").serialize();$.ajax({type:"POST",url:CI_ROOT+"forum/addDoctorForumAction",data:formdata,beforeSend:function(){$("#pageLoader").show()},success:function(result){if(result==1){swal({title:"Successful !",icon:"success",text:"Forum is created. You can check the same in your profile."}).then(function(){location.href=CI_ROOT+"doctor/myProfile"})}else if(result==2){swal({title:"Error !",icon:"error",text:"Forum Already Exist."}).then(function(){$("#pageLoader").hide()})}else{$("#formError").css("display","block");$('html, body').animate({scrollTop:$("#formError").offset().top-170},200);$("#formError").html(result).delay(5000).fadeOut();$("#pageLoader").hide()}}})})})