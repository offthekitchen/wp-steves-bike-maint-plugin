<?php
/**
 * Reports — select a bike and download a printable PDF report.
 *
 * @package BikePress
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! current_user_can( 'manage_options' ) ) {
	wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'bikepress' ) );
}

global $wpdb;

$hub_url = admin_url( 'admin.php?page=my-bikes' );
$bikes   = $wpdb->get_results(
	'SELECT id, bike_name FROM ' . BIKES_TABLE . ' ORDER BY bike_name ASC'
); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

$notice = isset( $_GET['bikepress_notice'] ) ? sanitize_key( wp_unslash( $_GET['bikepress_notice'] ) ) : '';
$notice_messages = array(
	'report_bike_required' => array( 'error', __( 'Please choose a bike.', 'bikepress' ) ),
	'report_bike_missing'  => array( 'error', __( 'That bike could not be found.', 'bikepress' ) ),
	'report_error'         => array( 'error', __( 'Could not create the PDF report.', 'bikepress' ) ),
);

$selected = isset( $_GET['bike_id'] ) ? absint( $_GET['bike_id'] ) : 0;
?>
<div class="wrap bikepress-reports-admin">
	<?php include plugin_dir_path( __FILE__ ) . 'bike-admin-header.php'; ?>
	<p><a href="<?php echo esc_url( $hub_url ); ?>">&larr; <?php esc_html_e( 'Return to My Bikes', 'bikepress' ); ?></a></p>

	<h1><?php esc_html_e( 'Reports', 'bikepress' ); ?></h1>
	<hr class="wp-header-end" />

	<?php if ( $notice && isset( $notice_messages[ $notice ] ) ) : ?>
		<div class="notice notice-<?php echo esc_attr( $notice_messages[ $notice ][0] ); ?> is-dismissible">
			<p><?php echo esc_html( $notice_messages[ $notice ][1] ); ?></p>
		</div>
	<?php endif; ?>

	<?php if ( empty( $bikes ) ) : ?>
		<div class="notice notice-warning">
			<p><?php esc_html_e( 'No bikes found. Add a bike under Manage Bikes before creating a report.', 'bikepress' ); ?></p>
		</div>
	<?php else : ?>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="bikepress_create_bike_report" />
			<?php wp_nonce_field( 'bikepress_create_bike_report', 'bikepress_report_nonce' ); ?>

			<table class="form-table" role="presentation">
				<tbody>
					<tr>
						<th scope="row"><label for="bike_id"><?php esc_html_e( 'Bike', 'bikepress' ); ?></label></th>
						<td>
							<select name="bike_id" id="bike_id" required>
								<option value=""><?php esc_html_e( '— Select a bike —', 'bikepress' ); ?></option>
								<?php foreach ( $bikes as $bike_row ) : ?>
									<option value="<?php echo esc_attr( (string) $bike_row->id ); ?>" <?php selected( $selected, (int) $bike_row->id ); ?>>
										<?php echo esc_html( $bike_row->bike_name ); ?>
									</option>
								<?php endforeach; ?>
							</select>
						</td>
					</tr>
				</tbody>
			</table>

			<?php submit_button( __( 'Create Bike Report (PDF)', 'bikepress' ), 'primary', 'submit', false ); ?>
		</form>
	<?php endif; ?>

	<?php include plugin_dir_path( __FILE__ ) . 'bike-admin-footer.php'; ?>
</div>
