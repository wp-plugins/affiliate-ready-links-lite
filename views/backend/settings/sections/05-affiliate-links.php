<table class="form-table">
	<tbody>
		<tr>
			<th scope="row"><label for="RdyLnk-affiliate-links-new-window"><?php _e('New Window'); ?></label></th>
			<td>
				<label>
					<input type="checkbox" name="RdyLnk[affiliate-links-new-window]" id="RdyLnk-affiliate-links-new-window" value="yes" <?php checked($settings['affiliate-links-new-window'] == 'yes', true); ?> />
					<?php _e('I want all affiliate links to open in a new window'); ?>
				</label>
			</td>
		</tr>
	</tbody>
</table>

<?php if (!class_exists('RdyLnkPro')) {?><p><?php _e('Get access to free updates and priority support when you <a target="_blank" href="http://www.readythemes.com/ready-links-plugin/">upgrade</a>.'); ?></p><?php } ?>