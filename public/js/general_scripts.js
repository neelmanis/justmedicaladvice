$(function(){$('[data-toggle="tooltip"]').tooltip({container:'body'})})
$(function(){$('[data-toggle="popover"]').popover({container:'body'})})
$('body').on('click',function(e){$('[data-toggle="popover"]').each(function(){if(!$(this).is(e.target)&&$(this).has(e.target).length===0&&$('.popover').has(e.target).length===0){$(this).popover('hide')}})});$("[data-toggle=fpopover]").each(function(i,obj){$(this).popover({html:!0,viewport:{selector:'body',padding:15},container:'body',content:function(){var id=$(this).attr('id')
return $('#fpopover-content-'+id).html()}})});$('body').on('click',function(e){$('[data-toggle="fpopover"]').each(function(){if(!$(this).is(e.target)&&$(this).has(e.target).length===0&&$('.popover').has(e.target).length===0){$(this).popover('hide')}})});if($(window).width()>991){$("[data-sticky_column]").stick_in_parent({parent:"[data-sticky_parent]",offset_top:80})}
function sticky_relocate(){var window_top=$(window).scrollTop();var div_top=$('#sticky-anchor').offset().top;if(window_top>div_top){$('.searchbar_box').addClass('stick');$('.onscroll_profile').addClass('stick');$('.headlogo').addClass('hclose')}else{$('.searchbar_box').removeClass('stick header-down');$('.onscroll_profile').removeClass('stick header-up');$('.headlogo').removeClass('hclose')}}
$(function(){$(window).scroll(sticky_relocate);sticky_relocate()});var didScroll;var lastScrollTop=0;var delta=5;var navbarHeight=$('.searchbar_box').outerHeight();$(window).scroll(function(event){didScroll=!0});setInterval(function(){if(didScroll){hasScrolled();didScroll=!1}},350);function hasScrolled(){var st=$(this).scrollTop();if(Math.abs(lastScrollTop-st)<=delta)
return;if(st>lastScrollTop&&st>navbarHeight){$('.searchbar_box').removeClass('header-down').addClass('header-up');$('.onscroll_profile').removeClass('header-up').addClass('header-down')}else{if(st+$(window).height()<$(document).height()){$('.searchbar_box').removeClass('header-up').addClass('header-down');$('.onscroll_profile').removeClass('header-down').addClass('header-up')}}
lastScrollTop=st}
var _debug=!1,_placeholderSupport=function(){var e=document.createElement("input");return e.type="text",void 0!==e.placeholder}();function PlaceholderFormSubmit(e){for(var t=0;t<e.elements.length;t++){HandlePlaceholderItemSubmit(e.elements[t])}}function HandlePlaceholderItemSubmit(e){if(e.name){var t=e.getAttribute("placeholder");t&&t.length>0&&e.value===t&&(e.value="",window.setTimeout(function(){e.value=t},100))}}function ReplaceWithText(e){if(!_placeholderSupport){var t=document.createElement("input");t.type="text",t.id=e.id,t.name=e.name,t.className=e.className;for(var o=0;o<e.attributes.length;o++){var l=e.attributes.item(o).nodeName,n=e.attributes.item(o).nodeValue;"type"!==l&&"name"!==l&&t.setAttribute(l,n)}t.originalTextbox=e,e.parentNode.replaceChild(t,e),HandlePlaceholder(t),_placeholderSupport||(e.onblur=function(){this.dummyTextbox&&0===this.value.length&&this.parentNode.replaceChild(this.dummyTextbox,this)})}}function HandlePlaceholder(e){if(_placeholderSupport)Debug("browser has native support for placeholder");else{var t=e.getAttribute("placeholder");t&&t.length>0?(Debug("Placeholder found for input box '"+e.name+"': "+t),e.value=t,e.setAttribute("old_color",e.style.color),e.style.color="#c0c0c0",e.onfocus=function(){var e=this;this.originalTextbox&&((e=this.originalTextbox).dummyTextbox=this,this.parentNode.replaceChild(this.originalTextbox,this),e.focus()),Debug("input box '"+e.name+"' focus"),e.style.color=e.getAttribute("old_color"),e.value===t&&(e.value="")},e.onblur=function(){var e=this;Debug("input box '"+e.name+"' blur"),""===e.value&&(e.style.color="#c0c0c0",e.value=t)}):Debug("input box '"+e.name+"' does not have placeholder attribute")}}function Debug(e){if(void 0!==_debug&&_debug){var t=document.getElementById("Console");t||((t=document.createElement("div")).id="Console",document.body.appendChild(t)),t.innerHTML+=e+"<br />"}}window.onload=function(){for(var e=document.getElementsByTagName("input"),t=document.getElementsByTagName("textarea"),o=[],l=0;l<e.length;l++)o.push(e[l]);for(l=0;l<t.length;l++)o.push(t[l]);for(l=0;l<o.length;l++){var n=o[l];n.type&&""!=n.type&&"text"!=n.type&&"textarea"!=n.type?"password"==n.type&&ReplaceWithText(n):HandlePlaceholder(n)}if(!_placeholderSupport)for(l=0;l<document.forms.length;l++){var a=document.forms[l];a.attachEvent?a.attachEvent("onsubmit",function(){PlaceholderFormSubmit(a)}):a.addEventListener&&a.addEventListener("submit",function(){PlaceholderFormSubmit(a)},!1)}}