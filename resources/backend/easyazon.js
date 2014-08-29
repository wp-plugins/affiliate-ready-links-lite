var RdyLnk = function() {
	var $ = jQuery;
	
	var $error_container, $no_results_row, $results_container, $results_table, $search_button, $single_result_template, $status_indicator;
	
	$(document).ready(function($) {
		$error_container = $('#RdyLnk-error');
		$no_results_row = $('#RdyLnk-no-results');
		$results_container = $('#RdyLnk-results');
		$results_table = $('#RdyLnk-results-table tbody');
		$search_button = $('#RdyLnk-search-button');
		$single_result_template = $('#RdyLnk-result-template');
		$status_indicator = $('#RdyLnk-status');
	});
	
	var object = {};
	
	object.current_item_data = null;
	object.current_request_data = null;
	
	/// DATA RETRIEVAL

	object.get_index = function() {
		return $('#RdyLnk-search-index').val();
	};
	
	object.get_keywords = function() {
		return $('#RdyLnk-search-terms').val();
	};
	
	object.get_locale = function() {
		return $('#RdyLnk-search-locale').val();
	};
	
	/// RESULT MANIPULATION
	
	object.add_product_result = function(item) {
		if(item.DetailPageURL) { // Account for the mysterious case where there is no page for the item
			var $result_row = $single_result_template.clone().removeAttr('id').removeClass('noremove');
			
			$result_row.find('.RdyLnk-result-link').attr('href', item.DetailPageURL).text(item.ItemAttributes.Title);
			
			if(item.SmallImage) {
				$result_row.find('.RdyLnk-result-image').attr('src', item.SmallImage.URL).attr('height', item.SmallImage.Height).attr('width', item.SmallImage.Width);
			} else {
				$result_row.find('.RdyLnk-result-image').hide();
			}
	
			var list_price = 'N/A';
			if(item.ItemAttributes.ListPrice && '' != item.ItemAttributes.ListPrice.FormattedPrice) {
				list_price = item.ItemAttributes.ListPrice.FormattedPrice;
			}
			$result_row.find('.RdyLnk-list-price').text(list_price);
			
			var actual_price = 'N/A';
			if(item.OfferSummary && item.OfferSummary.LowestNewPrice && '' != item.OfferSummary.LowestNewPrice.FormattedPrice) {
				actual_price = item.OfferSummary.LowestNewPrice.FormattedPrice;
			}
			$result_row.find('.RdyLnk-actual-price').text(actual_price);
	
			object.set_item_data($result_row, item);
			$results_table.append($result_row.show());
		}
	};
	
	object.get_item_data = function($element) {
		$element = $($element);
		
		if(!$element.is('tr')) {
			$element = $element.parents('tr');
		}
		
		if(0 < $element.size()) {
			return $element.data('RdyLnk-item');
		} else {
			return false;
		}
	};
	
	object.set_item_data = function($element, item) {
		$element = $($element);
		
		if(!$element.is('tr')) {
			$element = $element.parents('tr');
		}
		
		if(0 < $element.size()) {
			return $element.data('RdyLnk-item', item);
		} else {
			return false;
		}
	};
	
	object.get_request_data = function() {
		return $results_container.data('RdyLnk-request');
	};
	
	object.set_request_data = function(data) {
		return $results_container.data('RdyLnk-request', data);
	};
	
	/// SHORTCODES
	
	object.show_shortcode_form = function(item_data, request_data, title, type) {
		var function_name = 'shortcode_form_' + type;
		if('function' == typeof object[function_name]) {
			object[function_name](item_data, request_data);
			
			object.current_item_data = item_data;
			object.current_request_data = request_data;
			
			tb_show(title, '#TB_inline?width=640&height=800&inlineId=RdyLnk-shortcode-'+type);
			
			$('#TB_ajaxContent').height($('#TB_window').height() - 50);
		}
	};
	
	object.shortcode_form_block = function(item_data, request_data) {
		
	};
	
	object.shortcode_form_cta = function(item_data, request_data) {
		
	};
	
	object.shortcode_form_image = function(item_data, request_data) {
		var $ajax_feedback = $('#RdyLnk-image-uploaded-status').hide();
		
		$('#RdyLnk-image-type').val('amazon').change();
		
		var $image_sizes = $('#RdyLnk-image-sizes');
		$image_sizes.children(':not(#RdyLnk-image-size-template)').remove();
		
		var $template = $('#RdyLnk-image-size-template');
		
		var medium_info = {};
		
		$.each(item_data.ImageSets.ImageSet, function(image_set_index, image_set) {
			if('@attributes' != image_set_index) {
				var image_set_name = image_set_index.replace('Image', '');
				
				if('Medium' == image_set_name) {
					medium_info.url = image_set.URL;
					medium_info.height = image_set.Height;
					medium_info.width = image_set.Width;
				}
				
				var $image_size = $template.clone().removeAttr('id');
				$image_size.data('RdyLnk-image-set', image_set);
				$image_size.find('.RdyLnk-image-size-name').text(image_set_name);
				$image_size.find('.RdyLnk-image-size-height').text(image_set.Height);
				$image_size.find('.RdyLnk-image-size-width').text(image_set.Width);
				
				$image_sizes.append($image_size);
			}
		});
		
		var $image_example = $('#RdyLnk-image-example').hide();
		if(medium_info.url) {
			$image_example.attr('src', medium_info.url).attr('height', medium_info.height).attr('width', medium_info.width).show();
		}
		
		$image_sizes.find('li:not(#RdyLnk-image-size-template) input').filter(':first').attr('checked', 'checked');
	};
	
	object.shortcode_form_link = function(item_data, request_data) {
		$('#RdyLnk-link-insert-title').val(item_data.ItemAttributes.Title);
	};
	
	object.shortcode_form_search_cta = function(item_data, request_data) {
		
	};
	
	object.shortcode_form_search_link = function(item_data, request_data) {
		$('#RdyLnk-search-link-insert-title').val(request_data.SearchRequest.keywords);
	};
	
	//// INSERTIONS
	
	object.build_shortcode = function(shortcode, attributes, content) {
		var string = '[' + shortcode, property;
		for(property in attributes) {
			string += ' ' + property + '="' + attributes[property] + '"'; 
		}
		string += ']';
		
		if('' != content) {
			string += content + '[/' + shortcode + ']';
		}
		
		return string;
	};
	
	object.build_block_shortcode = function(callback) {
		var attributes = {
			align: $('[name="RdyLnk-block-alignment"]:checked').val(), 
			asin: object.current_item_data.ASIN,
			locale: object.current_request_data.SearchRequest.locale
		};
		var content = '';
		
		callback(object.build_shortcode(RdyLnk_Configuration.block_shortcode, attributes, content));
	};
	
	object.build_cta_shortcode = function(callback) {
		var $selected_image = $('[name="RdyLnk-cta-image"]:checked');
		
		var attributes = {
			align: $('[name="RdyLnk-cta-alignment"]:checked').val(), 
			asin: object.current_item_data.ASIN,
			height: $selected_image.attr('data-height'), 
			key: $selected_image.val(), 
			locale: object.current_request_data.SearchRequest.locale,
			width: $selected_image.attr('data-width')
		};
		var content = '';
		
		callback(object.build_shortcode(RdyLnk_Configuration.cta_shortcode, attributes, content));
	};
	
	object.build_image_shortcode = function(callback) {
		var attributes = {
			align: $('[name="RdyLnk-image-alignment"]:checked').val(), 
			asin: object.current_item_data.ASIN,
			locale: object.current_request_data.SearchRequest.locale
		};
		var content = '';
		
		var $type = $('#RdyLnk-image-type');
		if('amazon' == $type.val()) {
			var selected_image = $('[name="RdyLnk-image-size"]:checked').parents('li').data('RdyLnk-image-set');
		
			attributes.height = selected_image.Height;
			attributes.src = selected_image.URL;
			attributes.width = selected_image.Width;
			
			callback(object.build_shortcode(RdyLnk_Configuration.image_shortcode, attributes, content));
		} else {
			var $ajax_feedback = $('#RdyLnk-image-uploaded-status').css('visibility', 'visible').show();
			
			var $new_form = $('<form method="post" enctype="multipart/form-data"><input type="hidden" name="action" value="RdyLnk_upload" /></form>');
			var $file_input = $('#RdyLnk-image-uploaded');
			var $file_input_parent = $file_input.parent();
			$new_form.appendTo($('body'));
			
			$new_form.append($file_input).css('background', 'red').ajaxSubmit({
				dataType: 'json',
				clearForm: false,
				resetForm: false,
				success: function(response, status, xhr, $form) {
					$ajax_feedback.hide();
					$new_form.remove();
					$file_input_parent.append($file_input.val(''));
					
					if(response.error_message) {
						alert(response.error_message + "\nPlease try again.");
					} else {
						attributes.height = response.height;
						attributes.src = response.url;
						attributes.width = response.width;
						
						callback(object.build_shortcode(RdyLnk_Configuration.image_shortcode, attributes, content));
					}
				},
				url: ajaxurl
			});
		}
	};
	
	object.build_link_shortcode = function(callback) {
		var attributes = { 
			asin: object.current_item_data.ASIN, 
			locale: object.current_request_data.SearchRequest.locale 
		};
		var content = $.trim($('#RdyLnk-link-insert-title').val());
		
		callback(object.build_shortcode(RdyLnk_Configuration.link_shortcode, attributes, content));
	};
	
	object.build_search_cta_shortcode = function(callback) {
		var $selected_image = $('[name="RdyLnk-search-cta-image"]:checked');
		
		var attributes = {
			align: $('[name="RdyLnk-search-cta-alignment"]:checked').val(),
			height: $selected_image.attr('data-height'),  
			keywords: object.current_request_data.SearchRequest.keywords,
			key: $selected_image.val(), 
			locale: object.current_request_data.SearchRequest.locale,
			width: $selected_image.attr('data-width')
		};
		var content = '';
		
		callback(object.build_shortcode(RdyLnk_Configuration.cta_shortcode, attributes, content));
	};
	
	object.build_search_link_shortcode = function(callback) {
		var attributes = { 
			keywords: object.current_request_data.SearchRequest.keywords,
			locale: object.current_request_data.SearchRequest.locale 
		};
		var content = $.trim($('#RdyLnk-search-link-insert-title').val());
		
		callback(object.build_shortcode(RdyLnk_Configuration.link_shortcode, attributes, content));
	};
	
	object.insert_shortcode = function(type) {
		var function_name = 'build_' + type + '_shortcode';
		
		if('function' == typeof object[function_name]) {
			var callback = function(text) {
				send_to_editor(text);
			};
			object[function_name](callback);
		}
	};
	
	/// AJAX REQUESTS
	
	object.initiate_search_request = function() {
		object.send_search_request(object.get_index(), object.get_keywords(), object.get_locale(), 1);
	};
	
	object.receive_search_request_data = function(data, status) {
		if(data.error_message) {
			object.show_error(data.error_message);
		} else {
			object.set_request_data(data);
			
			if(0 == data.SearchResponse.Items.Item.length) {
				$no_results_row.show();
			} else {
				if(data.PaginationLinks) {
					$('.RdyLnk-tablenav .tablenav-pages').html(data.PaginationLinks);
				}
				
				if(data.SearchResponse.Items.MoreSearchResultsUrl) {
					$('#RdyLnk-search-link').attr('href', data.SearchResponse.Items.MoreSearchResultsUrl).find('#RdyLnk-search-link-keywords').text(data.SearchRequest.keywords);
					$('#RdyLnk-search-link-container').show();
				} else {
					$('#RdyLnk-search-link-container').hide();
				}
				
				$.each(data.SearchResponse.Items.Item, function(item_index, item) {
					object.add_product_result(item);
				});
			}
		}
	};
	
	object.send_search_request = function(index, keywords, locale, page) {
		if(!object.requesting) {
			object.start_request();
		
			$.post(
				ajaxurl,
				{ action: 'RdyLnk', keywords: keywords, index: index, locale: locale, page: page },
				function(data, status) {
					object.receive_search_request_data(data, status);
					object.end_request();
				},
				'json'
			)
		}
	};
	
	/// NOTIFICATIONS
	
	object.show_error = function(error_message) {
		$error_container.find('p').html(error_message).parent().show();
	};
	
	/// REQUEST STATUS
	
	object.requesting = false;
	
	object.start_request = function() {
		object.requesting = true;
		
		$error_container.hide();
		$no_results_row.hide();
		$results_container.hide();
		$results_table.find('tr:not(.noremove)').remove();
		$search_button.attr('disabled', 'disabled');
		$status_indicator.css('visibility', 'visible');
	};
	
	object.end_request = function() {
		object.requesting = false;
		
		if($results_table.find('tr:not(.noremove)').size() > 0) {
			$results_container.show();
		}
		
		$search_button.removeAttr('disabled');
		$status_indicator.css('visibility', 'hidden');
	};
	
	return object;
}();

jQuery(document).ready(function($) {
	// Change the search index options for each different locale since they're not the same
	var $index = $('#RdyLnk-search-index, #RdyLnk-default-search-index');
	var $index_clone = $index.clone();
	
	$('#RdyLnk-search-locale, #RdyLnk-default-search-locale').change(function(event) {
		var $this = $(this);
		var current_value = $index.val();
		
		$('.RdyLnk-cta-images-filter').val($this.val()).change();
		
		$index.empty().append($index_clone.find('option.' + $this.val()).clone()).val(current_value);
	}).change();
	
	$('.RdyLnk-cta-images-filter').change(function(event) {
		var $this = $(this);
		var $images_container = $this.parents('table').find('.RdyLnk-cta-images');
		
		var locale = $this.val();
		var $labels = $images_container.find('label');
		
		if('' == locale) {
			$labels.show();
		} else {
			var $inputs = $labels.hide().find('input[value^="amazon-' + locale + '"]');
			$inputs.parent().show();
			if(0 == $inputs.filter(':checked').size()) {
				$inputs.filter(':first').attr('checked', 'checked');
			}
		}
		
	}).change();

	$('#RdyLnk-info-block-placeholders').click(function(event) {
		event.preventDefault();
		
		$('.RdyLnk-info-block-placeholders').slideToggle();
	});

	// Show and hide the dependent link cloaking fields depending on if it is checked or not
	$('#RdyLnk-affiliate-links-cloaking').bind('click change', function(event) {
		var $this = $(this);
		var $dependents = $('.RdyLnk-affiliate-links-cloaking-dependent');
		
		if($this.is(':checked')) {
			$dependents.show();
		} else {
			$dependents.hide();
		}
	}).change();
	
	// Change the length of the cloaking prefix field so it fits nicely
	$('#RdyLnk-affiliate-links-cloaking-prefix').bind('keyup', function(event) {
		var $this = $(this);
		var cloaking_prefix = $.trim($this.val());
		
		if('' == cloaking_prefix) {
			cloaking_prefix = 'product';
		}
		
		$this.css('width', ((cloaking_prefix.length + 1) * 7 + 4) + 'px');
	}).keyup();
	
	// Bind the search events appropriately
	$('#RdyLnk-search-button').click(function(event) {
		event.preventDefault();
		
		RdyLnk.initiate_search_request();
	});
	
	$('#RdyLnk-search-terms').keypress(function(event) {
		if(13 == event.which) { // Press Return
			event.preventDefault();
			
			RdyLnk.initiate_search_request();
		}
	});
	
	// Bind the insertion events appropriately
	$('.RdyLnk-actions a').live('click', function(event) {
		var $this = $(this);

		if($this.hasClass('external')) {
			return true;
		}

		event.preventDefault();

		
		
		var type = $this.attr('data-type');
		var title = $this.attr('title');
		var type = $this.attr('data-type');
		
		var item_data = RdyLnk.get_item_data($this);
		var request_data = RdyLnk.get_request_data();
		
		RdyLnk.show_shortcode_form(item_data, request_data, title, type);
	});
	
	// Remove the Thickbox
	$('.RdyLnk-cancel').live('click', function(event) {
		event.preventDefault();
		
		tb_remove();
	});
	
	// Insert shortcodes
	$('.RdyLnk-insert-shortcode').live('click', function(event) {
		event.preventDefault();
		
		var $this = $(this);
		
		var type = $this.attr('data-type');
		
		RdyLnk.insert_shortcode(type);
	});
	
	$('.RdyLnk-tablenav .tablenav-pages a').live('click', function(event) {
		event.preventDefault();
		
		var $this = $(this);
		var href = $this.attr('href');
		var parts = href.split('#');
		var page;
		if(parts[1]) {
			page = parts[1];
		} else {
			page = 1;
		}
		
		var last_request = RdyLnk.get_request_data();
		var locale = last_request.SearchRequest.locale;
		var keywords = last_request.SearchRequest.keywords;
		var index = last_request.SearchRequest.index;
		
		RdyLnk.send_search_request(index, keywords, locale, page);
	});
	
	$('#RdyLnk-image-type').change(function(event) {
		var $this = $(this);
		
		var $dependents = $('.RdyLnk-image-type-dependent').hide().filter('.' + $this.val()).show();
	}).change();
});