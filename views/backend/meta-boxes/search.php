<p class="hide-if-js"><?php _e('RdyLnk requires JavaScript to work correctly.  Please enable JavaScript if you wish to use the plugin.'); ?></p>

<div class="hide-if-no-js">
	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row"><label for="RdyLnk-search-terms"><?php _e('Search Keywords or ASIN'); ?></label></th>
				<td>
					<input type="text" class="regular-text" name="RdyLnk-search-terms" id="RdyLnk-search-terms" value="" />
					<input type="button" class="button-primary" name="RdyLnk-search-button" id="RdyLnk-search-button" value="<?php _e('Search Amazon'); ?>" />
					<img alt="" title="" id="RdyLnk-status" class="ajax-feedback " src="images/wpspin_light.gif" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="RdyLnk-search-locale"><?php _e('Search Locale'); ?></label></th>
				<td>
					<select name="RdyLnk-search-locale" id="RdyLnk-search-locale">
						<?php foreach($locale_keys as $locale_key) { ?>
						<option <?php selected($locale_key, $settings['default-search-locale']); ?> value="<?php esc_attr_e($locale_key); ?>"><?php esc_html_e(Amazon_API::get_locale_name($locale_key)); ?></option>
						<?php } ?>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="RdyLnk-search-index"><?php _e('Search Index'); ?></label></th>
				<td>
					<select name="RdyLnk-search-index" id="RdyLnk-search-index">
						<?php foreach($locale_keys as $locale_key) { foreach(Amazon_API::get_locale_search_indices($locale_key) as $search_index) { ?>
						<option <?php selected($locale_key . $search_index, $settings['default-search-locale'] . $settings['default-search-index']); ?> class="<?php esc_attr_e($locale_key); ?>" value="<?php esc_attr_e($search_index); ?>"><?php esc_html_e(Amazon_API::get_search_index_nice_name($search_index)); ?></option>
						<?php } } ?>
					</select>
				</td>
			</tr>
		</tbody>
	</table>

	<div id="RdyLnk-error" class="hide-if-js RdyLnk-error"><p><?php _e( 'Unfortunately, an error occurred.  Please try again.' ); ?></p></div>

	<div id="RdyLnk-results" class="hide-if-js">
		<div class="tablenav RdyLnk-tablenav top">
			<div class="tablenav-pages"></div>
		</div>

		<table class="widefat" id="RdyLnk-results-table">
			<thead>
				<tr>
					<th scope="col"><?php _e('Image'); ?></th>
					<th scope="col"><?php _e('Link'); ?></th>
					<th scope="col" class="RdyLnk-actions"><?php _e('Actions'); ?></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th scope="col"><?php _e('Image'); ?></th>
					<th scope="col"><?php _e('Link'); ?></th>
					<th scope="col" class="RdyLnk-actions"><?php _e('Actions'); ?></th>
				</tr>
			</tfoot>
			<tbody>
				<tr id="RdyLnk-result-template" class="noremove">
					<td>
						<img class="RdyLnk-result-image" src="<?php echo esc_url($placeholder_image); ?>" alt="" />
					</td>
					<td>
						<a class="RdyLnk-result-link" href="#" target="_blank"><?php _e('Product Title'); ?></a><br />
						<?php _e('List Price: '); ?><span class="RdyLnk-list-price"></span><br />
						<?php _e('Actual Price: '); ?><span class="RdyLnk-actual-price"></span>
					</td>
					<td class="RdyLnk-actions" align="right">
						<a title="<?php _e('Insert Text Link'); ?>" data-type="link" class="RdyLnk-use-text-link" href="#text-link" rel=""><?php _e('Text Link'); ?></a> |
						<a title="<?php _e('Insert Image Link'); ?>" data-type="image" class="RdyLnk-use-image-link" href="#image-link" rel=""><?php _e('Image Link'); ?></a>
						<?php /*?><a class="external" target="_blank" href="http://RdyLnk.com/go.php?offer=thgcrew&pid=8&u=RdyLnk.com/upgrade"><?php _e('More Features (Upgrade to Pro)'); ?></a><?php */?>
					</td>
				</tr>
				<tr id="RdyLnk-no-results" class="noremove">
					<td colspan="3"><?php _e('No results were found. Please try searching with different keywords or an alternative search index.'); ?></td>
				</tr>
			</tbody>
		</table>

		<div class="tablenav RdyLnk-tablenav bottom">
			<div class="tablenav-pages"></div>
		</div>
	</div>

	<?php include('shortcode-forms/image.php'); ?>
	<?php include('shortcode-forms/link.php'); ?>

</div>