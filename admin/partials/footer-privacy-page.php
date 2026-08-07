<?php
/**
 * Data & Privacy — footer content page.
 *
 * @package BikePress
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! current_user_can( 'manage_options' ) ) {
	wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'bikepress' ) );
}
?>
<div class="wrap bikepress-footer-content">
	<?php include plugin_dir_path( __FILE__ ) . 'bike-admin-header.php'; ?>

	<h1><?php esc_html_e( 'Data & Privacy', 'bikepress' ); ?></h1>
	<hr class="wp-header-end" />

	<div class="bikepress-prose">
		<p><?php esc_html_e( 'My data and privacy policy is simple. I do not collect any data from you. I would love to hear from you about your experience with BikePress, but I won’t add you to a distro list, solicit you or give your information to anyone for any reason ever. Simple enough.', 'bikepress' ); ?></p>
	</div>

	<?php include plugin_dir_path( __FILE__ ) . 'bike-admin-footer.php'; ?>
</div>
