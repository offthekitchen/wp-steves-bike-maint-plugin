<?php
/**
 * Terms & Conditions — footer content page.
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

	<h1><?php esc_html_e( 'Terms & Conditions', 'bikepress' ); ?></h1>
	<hr class="wp-header-end" />

	<div class="bikepress-prose">
		<p><strong><?php esc_html_e( '1. Acceptance of Terms', 'bikepress' ); ?></strong><br />
		<?php esc_html_e( 'By downloading, installing, or using BikePress ("the Plugin"), you agree to these Terms and Conditions. The Plugin is developed by Steve Weeks/Off the Kitchen LLC ("the Developer"). If you do not agree to these terms, do not install or use the Plugin.', 'bikepress' ); ?></p>

		<p><strong><?php esc_html_e( '2. License and Distribution', 'bikepress' ); ?></strong><br />
		<?php esc_html_e( 'The Plugin is open-source software and is licensed under the GNU General Public License (GPL) v2 or later. You are free to modify, redistribute, and reuse the source code in accordance with the terms of the GPL.', 'bikepress' ); ?></p>

		<p><strong><?php esc_html_e( '3. Local Operation and Privacy', 'bikepress' ); ?></strong><br />
		<?php esc_html_e( 'The Plugin operates entirely within your self-hosted WordPress installation.', 'bikepress' ); ?></p>
		<ul>
			<li><?php esc_html_e( 'No Tracking: It does not collect, log, or transmit any user data, site analytics, or system telemetry.', 'bikepress' ); ?></li>
			<li><?php esc_html_e( 'No External Calls: It does not connect to any external servers, third-party APIs, or cloud infrastructure to function.', 'bikepress' ); ?></li>
		</ul>

		<p><strong><?php esc_html_e( '4. No Warranty ("As Is")', 'bikepress' ); ?></strong><br />
		<?php esc_html_e( 'The Plugin is provided "as is" and "as available," without any warranty of any kind, express or implied. The Developer does not guarantee that the Plugin will be error-free, uninterrupted, or fully compatible with every WordPress theme, hosting environment, or other active plugins.', 'bikepress' ); ?></p>

		<p><strong><?php esc_html_e( '5. Limitation of Liability', 'bikepress' ); ?></strong><br />
		<?php esc_html_e( 'In no event shall the Developer be liable for any direct, indirect, incidental, or consequential damages. This includes, but is not limited to:', 'bikepress' ); ?></p>
		<ul>
			<li><?php esc_html_e( 'Website downtime, crashes, or server errors.', 'bikepress' ); ?></li>
			<li><?php esc_html_e( 'Data loss, database corruption, or security vulnerabilities.', 'bikepress' ); ?></li>
			<li><?php esc_html_e( 'Financial loss or damage resulting from the use or inability to use the Plugin.', 'bikepress' ); ?></li>
		</ul>

		<p><strong><?php esc_html_e( '6. Support', 'bikepress' ); ?></strong><br />
		<?php esc_html_e( 'Technical support is not guaranteed. Any assistance provided through the official WordPress.org support forums is offered voluntarily, on a best-effort basis, without any service level agreements.', 'bikepress' ); ?></p>

		<p><strong><?php esc_html_e( '7. Governing Law', 'bikepress' ); ?></strong><br />
		<?php esc_html_e( 'These terms are governed by the laws of the United States of America without regard to its conflict of law principles.', 'bikepress' ); ?></p>
	</div>

	<?php include plugin_dir_path( __FILE__ ) . 'bike-admin-footer.php'; ?>
</div>
