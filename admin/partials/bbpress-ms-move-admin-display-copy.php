<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://ayecode.io/
 * @since      1.0.0
 *
 * @package    Bbpress_Ms_Move
 * @subpackage Bbpress_Ms_Move/admin/partials
 */


$bbp_copy = new Bbpress_Ms_Move_Copy();
?>
<p>
	<?php _e( 'This will try to copy all bbPress related info to a multisite install. Once done you should verify everything copied ok before deleting the original. ', 'bbpress-ms-move' );?>
</p>
<ol>
	<li>
		<?php _e( 'A new site should be used to copy to as post IDs will remain the same.', 'bbpress-ms-move' );?>
	</li>
	<li>
		<?php _e( 'You should activate bbPress on the new site before copying.', 'bbpress-ms-move' );?>
	</li>
</ol>

<div>
	<h3><?php _e( 'Select a blog to copy from:', 'bbpress-ms-move' );?></h3>
	<p><?php echo $bbp_copy->blogs_dropdown( 'bbpress_copy_from' );?></p>
</div>

<div>
	<h3><?php _e( 'Select a blog to copy to:', 'bbpress-ms-move' );?></h3>
	<p><?php echo $bbp_copy->blogs_dropdown( 'bbpress_copy_to' );?></p>
</div>

<div>
	<ul id="bbpc-functions">
		<?php echo $bbp_copy->output_settings($bbp_copy->copy_actions());?>
	</ul>
</div>


<p class="submit">
	<span name="bbpress_copy" id="bbpress_copy" class="button-primary">Copy bbPress</span>
</p>
