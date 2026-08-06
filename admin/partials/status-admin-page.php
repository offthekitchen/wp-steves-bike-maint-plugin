<?php
/**
 * Manage Statuses — list, add, edit, delete (Phase 3 CRUD).
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

$action    = isset( $_GET['action'] ) ? sanitize_key( wp_unslash( $_GET['action'] ) ) : 'list';
$status_id = isset( $_GET['status_id'] ) ? absint( $_GET['status_id'] ) : 0;

if ( ! in_array( $action, array( 'list', 'new', 'edit', 'delete' ), true ) ) {
	$action = 'list';
}

$list_url = admin_url( 'admin.php?page=status-admin' );
$new_url  = admin_url( 'admin.php?page=status-admin&action=new' );
$hub_url  = admin_url( 'admin.php?page=supporting-data-admin' );

$status = null;
if ( 'edit' === $action || 'delete' === $action ) {
	if ( $status_id <= 0 ) {
		$action = 'list';
	} else {
		$status = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . STATUS_TABLE . ' WHERE id = %d', $status_id ) ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		if ( ! $status ) {
			$action    = 'list';
			$status_id = 0;
		}
	}
}

$notice = isset( $_GET['bikepress_notice'] ) ? sanitize_key( wp_unslash( $_GET['bikepress_notice'] ) ) : '';
$notice_messages = array(
	'status_created'           => array( 'success', __( 'Status created.', 'bikepress' ) ),
	'status_updated'           => array( 'success', __( 'Status updated.', 'bikepress' ) ),
	'status_deleted'           => array( 'success', __( 'Status deleted. Bikes using it were reassigned to Unknown.', 'bikepress' ) ),
	'status_save_error'        => array( 'error', __( 'Could not save the status.', 'bikepress' ) ),
	'status_delete_error'      => array( 'error', __( 'Could not delete the status.', 'bikepress' ) ),
	'status_name_required'     => array( 'error', __( 'Status name is required.', 'bikepress' ) ),
	'status_unknown_protected' => array( 'error', __( 'The Unknown status cannot be deleted.', 'bikepress' ) ),
);

$form_status_id = 0;
$form_name      = '';

if ( $status ) {
	$form_status_id = absint( $status->id );
	$form_name      = $status->bike_status;
}

$is_unknown = $status ? ( 'Unknown' === $status->bike_status ) : false;
?>
<div class="wrap bikepress-status-admin">
	<?php include plugin_dir_path( __FILE__ ) . 'bike-admin-header.php'; ?>
	<p><a href="<?php echo esc_url( $hub_url ); ?>">&larr; <?php esc_html_e( 'Return to Supporting Data', 'bikepress' ); ?></a></p>

	<h1 class="wp-heading-inline"><?php esc_html_e( 'Manage Statuses', 'bikepress' ); ?></h1>
	<?php if ( 'list' === $action ) : ?>
		<a href="<?php echo esc_url( $new_url ); ?>" class="page-title-action"><?php esc_html_e( 'Add New Status', 'bikepress' ); ?></a>
	<?php endif; ?>
	<hr class="wp-header-end" />

	<?php if ( $notice && isset( $notice_messages[ $notice ] ) ) : ?>
		<div class="notice notice-<?php echo esc_attr( $notice_messages[ $notice ][0] ); ?> is-dismissible">
			<p><?php echo esc_html( $notice_messages[ $notice ][1] ); ?></p>
		</div>
	<?php endif; ?>

	<?php if ( 'delete' === $action && $status ) : ?>
		<?php if ( $is_unknown ) : ?>
			<div class="notice notice-error">
				<p><?php esc_html_e( 'The Unknown status cannot be deleted.', 'bikepress' ); ?></p>
				<a class="button" href="<?php echo esc_url( $list_url ); ?>"><?php esc_html_e( 'Back to list', 'bikepress' ); ?></a>
			</div>
		<?php else : ?>
			<div class="notice notice-warning">
				<p>
					<?php
					echo esc_html(
						sprintf(
							/* translators: %s: status name */
							__( 'Delete status “%s”? Bikes using this status will be reassigned to Unknown.', 'bikepress' ),
							$status->bike_status
						)
					);
					?>
				</p>
				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
					<input type="hidden" name="action" value="bikepress_delete_status" />
					<input type="hidden" name="status_id" value="<?php echo esc_attr( (string) $status->id ); ?>" />
					<?php wp_nonce_field( 'bikepress_delete_status', 'bikepress_delete_status_nonce' ); ?>
					<?php submit_button( __( 'Yes, delete status', 'bikepress' ), 'delete', 'submit', false ); ?>
					<a class="button" href="<?php echo esc_url( $list_url ); ?>"><?php esc_html_e( 'Cancel', 'bikepress' ); ?></a>
				</form>
			</div>
		<?php endif; ?>

	<?php elseif ( 'new' === $action || 'edit' === $action ) : ?>
		<h2><?php echo 'edit' === $action ? esc_html__( 'Edit Status', 'bikepress' ) : esc_html__( 'Add New Status', 'bikepress' ); ?></h2>

		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="bikepress_save_status" />
			<input type="hidden" name="status_id" value="<?php echo esc_attr( (string) $form_status_id ); ?>" />
			<?php wp_nonce_field( 'bikepress_save_status', 'bikepress_status_nonce' ); ?>

			<table class="form-table" role="presentation">
				<tbody>
					<tr>
						<th scope="row"><label for="bike_status"><?php esc_html_e( 'Status name', 'bikepress' ); ?></label></th>
						<td><input name="bike_status" id="bike_status" type="text" class="regular-text" required value="<?php echo esc_attr( $form_name ); ?>" /></td>
					</tr>
				</tbody>
			</table>

			<?php submit_button( 'edit' === $action ? __( 'Update status', 'bikepress' ) : __( 'Add status', 'bikepress' ) ); ?>
			<a class="button" href="<?php echo esc_url( $list_url ); ?>"><?php esc_html_e( 'Cancel', 'bikepress' ); ?></a>
		</form>

	<?php else : ?>
		<?php
		$rows = $wpdb->get_results(
			'SELECT s.*, (
				SELECT COUNT(*) FROM ' . BIKES_TABLE . ' b WHERE b.bike_status_id = s.id
			) AS bike_count
			FROM ' . STATUS_TABLE . ' s
			ORDER BY s.bike_status ASC'
		); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		?>

		<table class="wp-list-table widefat fixed striped">
			<thead>
				<tr>
					<th scope="col"><?php esc_html_e( 'Status', 'bikepress' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Bikes using', 'bikepress' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Actions', 'bikepress' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php if ( empty( $rows ) ) : ?>
					<tr><td colspan="3"><?php esc_html_e( 'No statuses found.', 'bikepress' ); ?></td></tr>
				<?php else : ?>
					<?php foreach ( $rows as $row ) : ?>
						<?php
						$row_is_unknown = ( 'Unknown' === $row->bike_status );
						$edit_url       = admin_url( 'admin.php?page=status-admin&action=edit&status_id=' . absint( $row->id ) );
						$delete_url     = admin_url( 'admin.php?page=status-admin&action=delete&status_id=' . absint( $row->id ) );
						?>
						<tr>
							<td><strong><a href="<?php echo esc_url( $edit_url ); ?>"><?php echo esc_html( $row->bike_status ); ?></a></strong></td>
							<td><?php echo esc_html( (string) $row->bike_count ); ?></td>
							<td>
								<a href="<?php echo esc_url( $edit_url ); ?>"><?php esc_html_e( 'Edit', 'bikepress' ); ?></a>
								<?php if ( ! $row_is_unknown ) : ?>
									|
									<a href="<?php echo esc_url( $delete_url ); ?>"><?php esc_html_e( 'Delete', 'bikepress' ); ?></a>
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>
	<?php endif; ?>
</div>
