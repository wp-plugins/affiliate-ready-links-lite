<p><?php printf(__('The following fields are required in order to send requests to Amazon and retrieve data about products and listings.  If you do not already have access keys set up, please visit the <a href="%s" target="_blank">AWS Account Management</a> page to create and retrieve them.'), 'https://aws-portal.amazon.com/gp/aws/developer/account/index.html?ie=UTF8&action=access-key#access_credentials'); ?></p>
<table class="form-table">
	<tbody>
		<tr>
			<th scope="row"><label for="RdyLnk-access-key-id"><?php _e('Access Key ID'); ?></label></th>
			<td>
				<input type="text" class="regular-text code" name="RdyLnk[access-key-id]" id="RdyLnk-access-key-id" value="<?php esc_attr_e($settings['access-key-id']); ?>" />
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="RdyLnk-access-key-id"><?php _e('Secret Access Key'); ?></label></th>
			<td>
				<input type="text" class="regular-text code" name="RdyLnk[secret-access-key]" id="RdyLnk-secret-access-key" value="<?php esc_attr_e($settings['secret-access-key']); ?>" />
			</td>
		</tr>
	</tbody>
</table>