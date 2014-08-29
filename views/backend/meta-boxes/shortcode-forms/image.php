<div id="RdyLnk-shortcode-image" class="RdyLnk-shortcode-form">
	<h4><?php _e('Insert a link to Amazon with an image'); ?></h4>
	
	<table class="form-table">
		<tbody>
			<tr valign="top">
				<th scope="row"><label for="RdyLnk-image-align"><?php _e('Alignment'); ?></label></th>
				<td class="RdyLnk-align">
					<label class="image-align-none-label">
						<input checked="checked"  type="radio" value="none" id="RdyLnk-image-alignment-none" name="RdyLnk-image-alignment" /><span><?php _e('None'); ?></span>
					</label>
					
					<label class="image-align-left-label">
						<input type="radio" value="left" id="RdyLnk-image-alignment-left" name="RdyLnk-image-alignment" /><span><?php _e('Left'); ?></span>
					</label>
					
					<label class="image-align-center-label">
						<input type="radio" value="center" id="RdyLnk-image-alignment-center" name="RdyLnk-image-alignment" /><span><?php _e('Center'); ?></span>
					</label>
				
					<label class="image-align-right-label">
						<input type="radio" value="right" id="RdyLnk-image-alignment-right" name="RdyLnk-image-alignment" /><span><?php _e('Right'); ?></span>
					</label>
					
					<div class="clear"></div>
				</td>
				<input type="hidden" name="RdyLnk-image-type" id="RdyLnk-image-type" value="amazon" />
			</tr>
			<tr class="RdyLnk-image-type-dependent amazon" valign="top">
				<th scope="row">
					<label for="RdyLnk-image-size"><?php _e('Size'); ?></label><br /><br />
					<img id="RdyLnk-image-example" src="" alt="" style="display: none;" />
				</th>
				<td>
					<ul id="RdyLnk-image-sizes">
						<li id="RdyLnk-image-size-template">
							<label>
								<input type="radio" name="RdyLnk-image-size" />
								<strong class="RdyLnk-image-size-name"></strong> 
								<em>(<span class="RdyLnk-image-size-width"></span> &times; <span class="RdyLnk-image-size-height"></span>)</em>
							</label>
						</li>
					</ul>
				</td>
			</tr>
			<tr class="submit">
				<td></td>
				<td class="savesend">
					<input class="button button-primary RdyLnk-insert-shortcode" data-type="image" type="button" name="RdyLnk-image-insert-shortcode" id="RdyLnk-image-insert-shortcode" value="<?php esc_attr_e(__('Insert Shortcode')); ?>" />
					<input class="button button-secondary RdyLnk-cancel" type="button" name="RdyLnk-cancel" value="<?php esc_attr_e(__('Cancel')); ?>" />
				</td>
			</tr>
		</tbody>
	</table>
</div>