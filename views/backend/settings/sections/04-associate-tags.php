<?php if (!class_exists('RdyLnkPro')) {?><p><strong style="color:red"><?php _e('This setting is only available in the PRO version.'); ?></strong></p> <?php } ?>
<p><?php _e('It is highly recommended that you add an Associate Tag for each locale that you expect to search with.'); ?></p>

<table class="form-table">
	<tbody>
		<?php foreach($locale_keys as $index => $locale_key) { ?>
		<tr>
			<th scope="row">
				<label for="RdyLnk-affiliate-locale-<?php esc_attr_e($locale_key); ?>"><?php esc_html_e(Amazon_API::get_locale_name($locale_key)); ?></label><br />
				<a href="<?php esc_attr_e(Amazon_API::get_locale_associate_signup_url($locale_key)); ?>" target="_blank"><small><?php _e('Sign Up'); ?></small></a>
			</th>
			<td>
				<input disabled type="text" class="regular-text code" name="RdyLnk[affiliate-locale][<?php esc_attr_e($locale_key); ?>]" id="RdyLnk-affiliate-locale-<?php esc_attr_e($locale_key); ?>" value="<?php esc_attr_e($settings['affiliate-locale'][$locale_key]); ?>" /><br />
				<small><?php if(0 == $index) { ?> (i.e. yourtrackingidhere-20) <?php } ?></small>
			</td>
		</tr>
		<?php } ?>
	</tbody>
</table>