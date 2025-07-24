"use strict";

jQuery(function($){

	$('#palettes-about .selectize select').selectize();

	$('#gothic_user_homebuilder').on( 'change', function( e ) {
		var select = $('#gothic_user_community');
		var builder = $(this).val();
		$.getJSON(gothic_selections_admin_script.builder_community_json + '?builder_id=' + builder, function( data ){
			var append = '<option value="-1" selected="selected" aria-disabled="true" disabled >'
				+ gothic_selections_admin_script.select_a_community + '</option>';

			$.each(data, function (i, item) {
				append += '<option value="' + item.ID + '">' + item.post_title + '</option>';
			});
			$(select).html(append);
			$(select).siblings('.error').remove();
		}).error( function( jqxhr ) {
			if ( 404 === jqxhr.status ) {
				$(select).html( '<option value="-1" selected="selected" aria-disabled="true" disabled>' + gothic_selections_admin_script.no_communities + '</option>' );
				$(select).after('<p class="error"><strong>' + gothic_selections_admin_script.no_communities_desc + '</strong></p>' );
			}
		});
	});
});