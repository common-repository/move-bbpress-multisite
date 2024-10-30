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
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<!-- Create a header in the default WordPress 'wrap' container -->
<div class="wrap">

	<div id="icon-themes" class="icon32"></div>
	<h2><?php _e( 'bbPress Multisite Move', 'bbpress-ms-move' ); ?></h2>
	<?php settings_errors(); ?>

	<?php
	$active_tab = 'bbpress_copy';

	if( isset( $_GET[ 'tab' ] ) ) {
		$active_tab = $_GET[ 'tab' ];
	} else if( $active_tab == 'bbpress_delete' ) {
		$active_tab = 'bbpress_delete';
	} else if( $active_tab == 'input_examples' ) {
		$active_tab = 'input_examples';
	}
	?>

	<h2 class="nav-tab-wrapper">
		<a href="?page=bbpress-ms-move" class="nav-tab <?php echo $active_tab == 'bbpress_copy' ? 'nav-tab-active' : ''; ?>"><?php _e( 'bbPress Copy', 'bbpress-ms-move' ); ?></a>
		<a href="?page=bbpress-ms-move&tab=bbpress_delete" class="nav-tab <?php echo $active_tab == 'bbpress_delete' ? 'nav-tab-active' : ''; ?>"><?php _e( 'bbPress Delete', 'bbpress-ms-move' ); ?></a>
	</h2>

	<form method="post" action="options.php">

		<?php
		//Set Nonce
		$ajax_nonce = wp_create_nonce( "bbpc-ajax-security" );
		echo "<input id='bbpc-nonce' type='hidden' value='$ajax_nonce' />";

		if( $active_tab == 'bbpress_copy' ) {

			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'partials/bbpress-ms-move-admin-display-copy.php';

		} elseif( $active_tab == 'bbpress_delete' ) {

			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'partials/bbpress-ms-move-admin-display-delete.php';


		}


		?>
	</form>

</div><!-- /.wrap -->