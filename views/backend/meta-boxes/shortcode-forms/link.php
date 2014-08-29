<div id="RdyLnk-shortcode-link" class="RdyLnk-shortcode-form">
	<h4><?php _e('Insert a link to Amazon'); ?></h4>
	
	<table class="form-table">
		<tbody>
			<tr>
				<th class="label" valign="top" scope="row"><label for="RdyLnk-link-insert-title"><?php _e('Link Text'); ?></label></th>
				<td class="field">
					<input type="text" class="large-text" id="RdyLnk-link-insert-title" name="RdyLnk-link-insert-title" value="<?php esc_attr_e($item['title']); ?>" />
				</td>
			</tr>
			<tr class="submit">
				<td></td>
				<td>
					<input class="button button-primary RdyLnk-insert-shortcode" data-type="link" type="button" name="RdyLnk-link-insert-shortcode" id="RdyLnk-link-insert-shortcode" value="<?php esc_attr_e(__('Insert Shortcode')); ?>" />
					<input class="button button-secondary RdyLnk-cancel" type="button" name="RdyLnk-cancel" value="<?php esc_attr_e(__('Cancel')); ?>" />
				</td>
			</tr>
		</tbody>
	</table>
</div>
