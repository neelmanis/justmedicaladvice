$(document).ready(function(){$("#searchText").keyup(function(){$('body').click(function(){$(".suggestion_dd").css("display","none")});var value=$("#searchText").val().trim();var length=value.length;var searchKey=value.slice(0);if(length>0){$.ajax({type:"POST",data:{searchKey:searchKey},url:CI_ROOT+"forum/search",success:function(result){if(result==1){$(".suggestion_dd").css("display","none")}else{$(".suggestion_dd").css("display","block");$(".suggestion_dd").html(result)}}})}else{$(".suggestion_dd").css("display","none")}});$("#postForum").click(function(e){e.preventDefault();var formdata=$("#ask_question").serialize();$.ajax({type:"POST",url:CI_ROOT+"forum/addMemberForumAction",data:formdata,beforeSend:function(){$("#pageLoader").show()},success:function(result){if(result==1){swal({title:"Successful !",icon:"success",text:"Forum is created."}).then(function(){location.href=CI_ROOT+"forum/listall"})}else if(result==2){swal({title:"Error !",icon:"error",text:"Forum Already Exist."}).then(function(){$("#pageLoader").hide()})}else{$(window).scrollTop(0);$("#formError").css("display","block");$("#formError").html(result).delay(5000).fadeOut();$("#pageLoader").hide()}}})})})