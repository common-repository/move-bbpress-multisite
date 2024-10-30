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
	<?php _e( 'This will try to delete all bbPress related info from a multisite install.', 'bbpress-ms-move' );?>
</p>

<div>
	<h3><?php _e( 'Select a blog to delete from:', 'bbpress-ms-move' );?></h3>
	<p><?php echo $bbp_copy->blogs_dropdown( 'bbpress_copy_from' );?></p>
</div>

<div>
	<ul id="bbpc-functions">
		<?php echo $bbp_copy->output_settings($bbp_copy->delete_actions());?>
	</ul>
</div>


<p class="submit">
	<span name="bbpress_copy" id="bbpress_delete" class="button-primary button-red">Delete bbPress</span>
</p>
