$("[data-toggle=fpopover]").each(function(i, obj) {
	$(this).popover({
	  html:true,viewport:{selector:'body',padding:15},container:'body',
	  content: function() {
		var id = $(this).attr('id')
		return $('#fpopover-content-'+id).html();
	  }
	});
});

$('body').on('click', function (e) {
    $('[data-toggle="fpopover"]').each(function () {
        if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
            $(this).popover('hide');
        }
    });
});