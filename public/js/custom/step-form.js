$(function(){$('[data-toggle="tooltip"]').tooltip()})
$(".stepform").hide();$(".stepform:first").show();$(".btn_next").click(function(){$(".stepform").hide();var activeTab=$(this).attr("rel");$("#"+activeTab).fadeIn();$(".stepsbox li").removeClass("active");$(".stepsbox li[rel^='"+activeTab+"']").addClass("active")});$(".topic_holder").hide();$(".topic_heading").click(function(){$(this).next(".topic_holder").siblings(".topic_holder").slideUp();$(this).next(".topic_holder").slideToggle();$(".topic_heading").removeClass("active");$(this).addClass("active")});$(function(){$("#image").change(function(){if(typeof(FileReader)!="undefined"){var dvPreview=$("#imgPreview");dvPreview.html("");var regex=/^([a-zA-Z0-9\s_\\.\-:])+(.jpg|.jpeg|.gif|.png|.bmp)$/;$($(this)[0].files).each(function(){var file=$(this);if(regex.test(file[0].name.toLowerCase())){var reader=new FileReader();reader.onload=function(e){var img=$("<img />");img.attr("src",e.target.result);dvPreview.append(img)}
reader.readAsDataURL(file[0])}else{alert(file[0].name+" is not a valid image file.");dvPreview.html("");return!1}})}else{alert("This browser does not support HTML5 FileReader.")}})});$(function(){$("#fileupload").change(function(){if(typeof(FileReader)!="undefined"){var dvPreview=$("#imgPreview");dvPreview.html("");var regex=/^([a-zA-Z0-9\s_\\.\-:])+(.jpg|.jpeg|.gif|.png|.bmp)$/;$($(this)[0].files).each(function(){var file=$(this);if(regex.test(file[0].name.toLowerCase())){var reader=new FileReader();reader.onload=function(e){var img=$("<img />");img.attr("src",e.target.result);dvPreview.append(img)}
reader.readAsDataURL(file[0])}else{alert(file[0].name+" is not a valid image file.");dvPreview.html("");return!1}})}else{alert("This browser does not support HTML5 FileReader.")}})})