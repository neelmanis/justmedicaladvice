$(document).ready(function(){var hashcode=window.location.hash;if(hashcode=='#comment'){$('html, body').animate({scrollTop:$(hashcode).offset().top-200},200);$('#comment').focus();$('#commentTrigger').addClass('comment_trigger')}else if(hashcode=='#share'){$('html, body').animate({scrollTop:$(hashcode).offset().top-200},200)}
$("#searchText").keyup(function(){$('body').click(function(){$(".suggestion_dd").css("display","none")});var value=$("#searchText").val().trim();var length=value.length;var searchKey=value.slice(0);if(length>0){$.ajax({type:"POST",data:{searchKey:searchKey},url:CI_ROOT+"blog/search",success:function(result){if(result==1){$(".suggestion_dd").css("display","none")}else{$(".suggestion_dd").css("display","block");$(".suggestion_dd").html(result)}}})}else{$(".suggestion_dd").css("display","none")}});$("#like_blog").click(function(e){e.preventDefault();var bid=$('#bid').val();var utype=$('#utype').val();var uid=$('#uid').val();$.ajax({type:"POST",url:CI_ROOT+"blog/addLike",data:{blogId:bid,type:utype,user:uid},beforeSend:function(){$("#pageLoader").show()},success:function(result){if(result==1){window.location.reload()}else{swal({title:"Error !",icon:"error",text:result}).then(function(){$("#pageLoader").hide()})}}})});$("#report_blog").click(function(){var bid=$('#bid').val();var utype=$('#utype').val();var uid=$('#uid').val();$.ajax({type:"POST",url:CI_ROOT+"blog/blogReport",data:{blogId:bid,type:utype,user:uid},beforeSend:function(){$("#pageLoader").show()},success:function(result){if(result==1){window.location.reload(!0)}else{swal({title:"Error !",icon:"error",text:result}).then(function(){$("#pageLoader").hide()})}}})});$("#postComment").click(function(e){e.preventDefault();var formdata=$("#comment_form").serialize();$.ajax({type:"POST",url:CI_ROOT+"blog/addComment",data:formdata,beforeSend:function(){$("#pageLoader").show()},success:function(result){if(result==1){swal({title:"Successful !",icon:"success",text:"Answer is posted."}).then(function(){location.reload(!0)})}else{swal({title:"Error !",icon:"error",text:result}).then(function(){location.reload(!0)})}}})});$("a.reply").click(function(e){e.preventDefault();var id=$(this).attr("id");$("#pid").attr("value",id);$('html, body').animate({scrollTop:$("#commentToTop").offset().top-120},200);$('#comment').focus();$('#commentTrigger').addClass('comment_trigger')});$('#comment').keypress(function(){$('#commentTrigger').removeClass('comment_trigger')});$("a.report").click(function(e){e.preventDefault();var id=$(this).attr("id");$.ajax({type:"POST",url:CI_ROOT+"blog/addReport",data:{cid:id},beforeSend:function(){$("#pageLoader").show()},success:function(result){if(result==1){window.location.reload(!0)}else{swal({title:"Error !",icon:"error",text:result}).then(function(){$("#pageLoader").hide()})}}})});$("#postForum").click(function(e){e.preventDefault();var formdata=$("#ask_question").serialize();$.ajax({type:"POST",url:CI_ROOT+"forum/addMemberForumAction",data:formdata,beforeSend:function(){$("#pageLoader").show()},success:function(result){if(result==1){swal({title:"Successful !",icon:"success",text:"Forum is created."}).then(function(){location.href=CI_ROOT+"forum/listall"})}else if(result==2){swal({title:"Error !",icon:"error",text:"Forum Already Exist."}).then(function(){$("#pageLoader").hide()})}else{$("#formError").css("display","block");$('html, body').animate({scrollTop:$("#formError").offset().top-170},200);$("#formError").html(result).delay(5000).fadeOut();$("#pageLoader").hide()}}})})})