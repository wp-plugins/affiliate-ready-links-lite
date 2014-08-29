<div class="wrap RdyLnk-metaboxes">
	<form id="RdyLnk-settings-form" method="post" action="<?php esc_attr_e(add_query_arg(array('page' => 'RdyLnk'), admin_url('options-general.php'))); ?>">
		<?php wp_nonce_field('save-RdyLnk-settings', 'save-RdyLnk-settings-nonce'); ?>

		<?php screen_icon(); ?>
		<h2>
			<?php _e('Ready Links Settings'); ?>
			<input type="submit" class="button button-primary" name="save-RdyLnk-settings" value="<?php _e('Save Changes'); ?>" />

		</h2>


		<div class="metabox-holder">
			<?php settings_errors(); ?>

			<div id="main-sortables" class="meta-box-sortables">
				<?php do_meta_boxes('RdyLnk-settings', 'normal', $settings); ?>
			</div>
		</div>

		<div class="bottom-buttons">
			<input type="submit" class="button button-primary" name="save-RdyLnk-settings" value="<?php _e('Save Changes'); ?>" />
		</div>

	</form>
	<div class="clear"></div>
</div>