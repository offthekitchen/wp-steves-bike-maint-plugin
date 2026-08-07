<?php
/**
 * BikePress admin footer — About BikePress + Contact.
 *
 * @package BikePress
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<footer class="admin-footer">
	<div class="footer-section">
		<h3 class="footer-header"><?php esc_html_e( 'About BikePress', 'bikepress' ); ?></h3>
		<ul class="footer-list">
			<li class="footer-item">
				<a href="https://www.offthekitchen.com/bikepress-documentation/" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Documentation', 'bikepress' ); ?></a>
			</li>
			<li class="footer-item">
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=bikepress-privacy' ) ); ?>"><?php esc_html_e( 'Data & Privacy', 'bikepress' ); ?></a>
			</li>
			<li class="footer-item">
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=bikepress-terms' ) ); ?>"><?php esc_html_e( 'Terms & Conditions', 'bikepress' ); ?></a>
			</li>
		</ul>
	</div>
	<div class="footer-section">
		<h3 class="footer-header"><?php esc_html_e( 'Contact', 'bikepress' ); ?></h3>
		<ul class="footer-list">
			<li class="footer-item">
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=bikepress-about' ) ); ?>"><?php esc_html_e( 'About Me', 'bikepress' ); ?></a>
			</li>
			<li class="footer-item">
				<a href="mailto:steve@offthekitchen.com"><?php esc_html_e( 'Email', 'bikepress' ); ?></a>
			</li>
			<li class="footer-item">
				<a href="https://www.offthekitchen.com" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Website', 'bikepress' ); ?></a>
			</li>
			<li class="footer-item">
				<a href="https://buymeacoffee.com/offthekitchen" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Buy Me a Coffee', 'bikepress' ); ?></a>
			</li>
		</ul>
	</div>
</footer>
