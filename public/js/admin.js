//Preload the main panel
var panel = $('#admin-panel');
var panelUrl = url + '/admin/panel/';

//set the current panel name.
$(panel).hide();
var currentPanel = 'page';
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

	//New Page/Post
	$(panel).on( 'click' , '#new' , function( ele ) {
		
		event.preventDefault();
		$(panel).slideUp(100);
		$.ajax({
			type: "GET",
		    url: panelUrl + $( this ).attr('name') + '/' + $( this ).attr('id'),
		    success: function(result){
		        $(panel)
		        .html(result)
		        .slideDown(100);
		    }
		});

	});

	

	//Page Naviation Stuff
	$(panel).on( 'click' , '.panel-nav' , function( ele ) {
		
		event.preventDefault();
		var confirmed = true;

		if($(this).attr('id')=='publish'||$(this).attr('id')=='delete') {
			if(!confirm('Are you sure?')) {
				confirmed = false;
			}
		}

		if(confirmed) {
	    	$.ajax({
				type: "POST",
			    url: panelUrl  + $(this).parent().parent().attr('name') + '/' + $(this).attr('id'),
			    data: { 
			    	id: 	$(this).parent().parent().attr('id'),
			    },
			    success: function(result){
			        $(panel)
			        .html(result)
			        .slideDown(100);
			    }
			});			
		}
	});
});


