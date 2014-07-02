//Preload the main panel
var panel = $('#admin-panel');
var panelUrl = url + '/admin/panel/';

$(panel).hide();
$.ajax({
	type: "GET",
    url: panelUrl+'pages',
    success: function(result){
        $(panel)
        .html(result)
        .slideDown(100);
    }
});


$( document ).ready(function() {

	//Default page load - pages!
	$('.admin-nav').on( 'click' , function() {
		event.preventDefault();
		$(panel).slideUp(100);
		$.ajax({
			type: "GET",
		    url: panelUrl + $( this ).attr('id'),
		    success: function(result){
		        $(panel)
		        .html(result)
		        .slideDown(100);
		    }
		});
	});

	//Page Naviation Stuff
	$(panel).on( 'click' , '.page-nav' , function( ele ) {
		event.preventDefault();

		var pageUrl = panelUrl + 'pages';

    	$.ajax({
			type: "POST",
		    url: pageUrl  + '/' + $(this).attr('id'),
		    data: { 
		    	id: 	$(this).parent().parent().attr('id'),
		    },
		    success: function(result){
		        $(panel)
		        .html(result)
		        .slideDown(100);
		    }
		});
	});
});


