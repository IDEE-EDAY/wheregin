jQuery(document).ready(function($){

	function handle_images( frameArgs, callback ){
		var SM_Frame = wp.media( frameArgs );

		SM_Frame.on( 'select', function() {

			callback( SM_Frame.state().get('selection') );
			SM_Frame.close();
		});

		SM_Frame.open();	
	}

	$(document).on( 'click', '.featured-image', function(e) {
		e.preventDefault();

		var frameArgs = {
			multiple: false,
			title: 'Select Featured Image'
		};

		handle_images( frameArgs, function( selection ){
			model = selection.first();
			$('#offer_featured_image').val( model.id );
			var img = model.attributes.url;
			var ext = img.substring(img.lastIndexOf('.'));
			img = img.replace( ext, '-150x150'+ext );
			$('.featured-image-wrap').html( '<img src="'+img+'" class="img-responsive"/>' );
		});
	});

	$(document).on( 'click', '.coupon-image', function(e) {
		e.preventDefault();

		var frameArgs = {
			multiple: false,
			title: 'Select Coupon Image'
		};

		handle_images( frameArgs, function( selection ){
			model = selection.first();
			$('#coupon_image').val( model.id );
			var img = model.attributes.url;
			var ext = img.substring(img.lastIndexOf('.'));
			img = img.replace( ext, '-150x150'+ext );
			$('.coupon-image-wrap').html( '<img src="'+img+'" class="img-responsive"/>' );
		});
	});	


	/* DEAL IMAGES */
	$(document).on( 'click', '.deal-images', function(e) {
		e.preventDefault();

		$('.deal-images-wrap').sortable({
			revert: false,
			update: function(event, ui) {
				update_deal_images();
			}
		});			

		var frameArgs = {
			multiple: true,
			title: 'Select Deal Images'
		};

		handle_images( frameArgs, function( selection ){
			var images = selection.toJSON();
			if( images.length > 0 ){
				for( var i = 0; i < images.length; i++ ){
					var img = images[i].url;
					var ext = img.substring(img.lastIndexOf('.'));
					img = img.replace( ext, '-150x150'+ext );					
					$('.deal-images-wrap').append( '<div class="deal-image-wrap" data-image_id='+images[i].id+'><img src="'+img+'" class="img-responsive"/><a href="javascript:;" class="remove-deal-image"><i class="fa fa-close"></i></a></div>' );
				}
			}

			update_deal_images();
		});
	});

	$(document).on('click', '.remove-deal-image', function(){
		$(this).parents('.deal-image-wrap').remove();
		update_deal_images();
	});
	

	function update_deal_images(){
		var image_ids = [];
		$('.deal-image-wrap').each(function(){
			image_ids.push( $(this).attr('data-image_id') );
		});

		$('#deal_images').val( image_ids.join(',') );
	}

	/* CHANGE AVATAR */
	$('.user-avatar').click(function(e){
		e.preventDefault();

		var frameArgs = {
			multiple: false,
			title: $(this).text()
		};

		handle_images( frameArgs, function( selection ){
			model = selection.first();			
			$.ajax({
				url: ajaxurl,
				method: "POST",
				data:{
					action: 'change_avatar',
					'wp-user-avatar': model.id
				},
				success: function(avatar){
					$('.img-user-avatar').attr( 'src', avatar );
				}
			});
		});
	});
});