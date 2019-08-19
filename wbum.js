jQuery( document ).ready(function($) {
    var i = 0;
    var wbum_output = jQuery('#wbum_bulk_meta_update_ouput');

	jQuery('#wbum_process_start_20190819').on('click', function(){
		go = confirm("Are you sure?");
		if (go == true) {
			processFile();
		}
	});

	function processFile() {
        var wbum_post_type = jQuery('#wbum_post_type').val();
        var wbum_meta_key = jQuery('#wbum_meta_key').val();
        var wbum_meta_value = jQuery('#wbum_meta_value').val();

		$.post(
			ajaxurl,
			{
				'action': 'wbum_process',
                'page' : i,
                'wbum_post_type' : wbum_post_type,
                'wbum_meta_key' : wbum_meta_key,
                'wbum_meta_value' : wbum_meta_value
			}, 
			function( response ) {
				if( response === '-1' ) {
                    console.log('All done.');
                    wbum_output.append('<p>All done.</p>');
				} else {
                    console.log('Page: ' + response + ' processed.');
                    wbum_output.prepend('<p>Page: ' + response + ' processed.</p>');
					i++;
					processFile();
				}
			}
		);
	}
});
