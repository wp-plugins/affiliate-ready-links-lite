<table class="form-table">
	<tbody>
		<tr>
			<th scope="row"><label for="RdyLnk-default-search-locale"><?php _e('Default Search Locale'); ?></label></th>
			<td>
				<select name="RdyLnk[default-search-locale]" id="RdyLnk-default-search-locale">
					<?php foreach($locale_keys as $locale_key) { ?>
					<option <?php selected($locale_key, $settings['default-search-locale']); ?> value="<?php esc_attr_e($locale_key); ?>"><?php esc_html_e(Amazon_API::get_locale_name($locale_key)); ?></option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="RdyLnk-default-search-index"><?php _e('Default Search Index'); ?></label></th>
			<td>
				<select name="RdyLnk[default-search-index]" id="RdyLnk-default-search-index">
					<?php foreach($locale_keys as $locale_key) { foreach(Amazon_API::get_locale_search_indices($locale_key) as $search_index) { ?>
					<option <?php selected($locale_key . $search_index, $settings['default-search-locale'] . $settings['default-search-index']); ?> class="<?php esc_attr_e($locale_key); ?>" value="<?php esc_attr_e($search_index); ?>"><?php esc_html_e(Amazon_API::get_search_index_nice_name($search_index)); ?></option>
					<?php } } ?>
				</select>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php _e('Enabled Content Types'); ?></th>
			<td>
				<ul>
					<?php foreach(get_post_types(array('show_ui' => true), 'objects') as $type) { ?>
					<li>
						<label>
							<input <?php if ($type->labels->name!="Posts"&&!class_exists("RdyLnkPro")) echo 'disabled';?> type="checkbox" <?php checked($settings['post-types'][$type->name], 'yes'); ?> name="RdyLnk[post-types][<?php esc_attr_e($type->name); ?>]" value="yes" />
							<?php esc_html_e($type->labels->name); ?>
						</label>
					</li>
					<?php } ?>
				</ul>
				<?php if (!class_exists('RdyLnkPro')) {?><small><?php _e('All custom post types are available just in the PRO version here : '); ?><a href="http://www.readythemes.com/ready-links-plugin-addon/" target="_blank">http://www.readythemes.com/ready-links-plugin-addon/</a></small><?php }?>
			</td>
		</tr>
	</tbody>
</table>