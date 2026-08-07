<?php
/**
 * Manage Maintenance — list, add, edit, delete (Phase 2 CRUD).
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

$action         = isset( $_GET['action'] ) ? sanitize_key( wp_unslash( $_GET['action'] ) ) : 'list';
$maint_id       = isset( $_GET['maint_id'] ) ? absint( $_GET['maint_id'] ) : 0;
$filter_bike_id = isset( $_GET['bike_id'] ) ? absint( $_GET['bike_id'] ) : 0;

if ( ! in_array( $action, array( 'list', 'new', 'edit', 'delete' ), true ) ) {
	$action = 'list';
}

$bikes = $wpdb->get_results( 'SELECT id, bike_name FROM ' . BIKES_TABLE . ' ORDER BY bike_name ASC' ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

$list_args = array( 'page' => 'maint-admin' );
$new_args  = array( 'page' => 'maint-admin', 'action' => 'new' );
if ( $filter_bike_id > 0 ) {
	$list_args['bike_id'] = $filter_bike_id;
	$new_args['bike_id']  = $filter_bike_id;
}
$list_url = add_query_arg( $list_args, admin_url( 'admin.php' ) );
$new_url  = add_query_arg( $new_args, admin_url( 'admin.php' ) );

$maint = null;
if ( 'edit' === $action || 'delete' === $action ) {
	if ( $maint_id <= 0 ) {
		$action = 'list';
	} else {
		$maint = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . MAINTENANCE_TABLE . ' WHERE id = %d', $maint_id ) ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		if ( ! $maint ) {
			$action   = 'list';
			$maint_id = 0;
		}
	}
}

$notice = isset( $_GET['bikepress_notice'] ) ? sanitize_key( wp_unslash( $_GET['bikepress_notice'] ) ) : '';
$notice_messages = array(
	'maint_created'       => array( 'success', __( 'Maintenance record created.', 'bikepress' ) ),
	'maint_updated'       => array( 'success', __( 'Maintenance record updated.', 'bikepress' ) ),
	'maint_deleted'       => array( 'success', __( 'Maintenance record deleted.', 'bikepress' ) ),
	'maint_save_error'    => array( 'error', __( 'Could not save the maintenance record.', 'bikepress' ) ),
	'maint_delete_error'  => array( 'error', __( 'Could not delete the maintenance record.', 'bikepress' ) ),
	'maint_desc_required' => array( 'error', __( 'Maintenance description is required.', 'bikepress' ) ),
	'maint_date_required' => array( 'error', __( 'Maintenance date is required.', 'bikepress' ) ),
	'maint_bike_required' => array( 'error', __( 'Please choose a bike.', 'bikepress' ) ),
);

$form_maint_id = 0;
$form_bike_id  = $filter_bike_id;
$form_desc     = '';
$form_miles    = 0;
$form_date     = '';

if ( $maint ) {
	$form_maint_id = absint( $maint->id );
	$form_bike_id  = absint( $maint->bike_id );
	$form_desc     = $maint->maintenance_desc;
	$form_miles    = absint( $maint->bike_miles );
	if ( ! empty( $maint->maintenance_date ) && '0000-00-00 00:00:00' !== $maint->maintenance_date ) {
		$form_date = gmdate( 'Y-m-d', strtotime( $maint->maintenance_date ) );
	}
}
?>
<div class="wrap bikepress-maint-admin">
	<?php include plugin_dir_path( __FILE__ ) . 'bike-admin-header.php'; ?>

	<h1 class="wp-heading-inline"><?php esc_html_e( 'Manage Maintenance Records', 'bikepress' ); ?></h1>
	<?php if ( 'list' === $action ) : ?>
		<a href="<?php echo esc_url( $new_url ); ?>" class="page-title-action"><?php esc_html_e( 'Add New Record', 'bikepress' ); ?></a>
	<?php endif; ?>
	<hr class="wp-header-end" />

	<?php if ( $notice && isset( $notice_messages[ $notice ] ) ) : ?>
		<div class="notice notice-<?php echo esc_attr( $notice_messages[ $notice ][0] ); ?> is-dismissible">
			<p><?php echo esc_html( $notice_messages[ $notice ][1] ); ?></p>
		</div>
	<?php endif; ?>

	<?php if ( 'delete' === $action && $maint ) : ?>
		<div class="notice notice-warning">
			<p>
				<?php
				echo esc_html(
					sprintf(
						/* translators: %s: maintenance description */
						__( 'Delete maintenance record “%s”?', 'bikepress' ),
						$maint->maintenance_desc
					)
				);
				?>
			</p>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<input type="hidden" name="action" value="bikepress_delete_maintenance" />
				<input type="hidden" name="maint_id" value="<?php echo esc_attr( (string) $maint->id ); ?>" />
				<input type="hidden" name="filter_bike_id" value="<?php echo esc_attr( (string) $filter_bike_id ); ?>" />
				<?php wp_nonce_field( 'bikepress_delete_maintenance', 'bikepress_delete_maint_nonce' ); ?>
				<?php submit_button( __( 'Yes, delete record', 'bikepress' ), 'delete', 'submit', false ); ?>
				<a class="button" href="<?php echo esc_url( $list_url ); ?>"><?php esc_html_e( 'Cancel', 'bikepress' ); ?></a>
			</form>
		</div>

	<?php elseif ( 'new' === $action || 'edit' === $action ) : ?>
		<h2><?php echo 'edit' === $action ? esc_html__( 'Edit Maintenance Record', 'bikepress' ) : esc_html__( 'Add New Maintenance Record', 'bikepress' ); ?></h2>

		<?php if ( empty( $bikes ) ) : ?>
			<div class="notice notice-error"><p><?php esc_html_e( 'No bikes found. Add a bike first.', 'bikepress' ); ?></p></div>
		<?php endif; ?>

		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="bikepress_save_maintenance" />
			<input type="hidden" name="maint_id" value="<?php echo esc_attr( (string) $form_maint_id ); ?>" />
			<input type="hidden" name="filter_bike_id" value="<?php echo esc_attr( (string) $filter_bike_id ); ?>" />
			<?php wp_nonce_field( 'bikepress_save_maintenance', 'bikepress_maint_nonce' ); ?>

			<table class="form-table" role="presentation">
				<tbody>
					<tr>
						<th scope="row"><label for="bike_id"><?php esc_html_e( 'Bike', 'bikepress' ); ?></label></th>
						<td>
							<select name="bike_id" id="bike_id" required>
								<option value=""><?php esc_html_e( '— Select —', 'bikepress' ); ?></option>
								<?php foreach ( (array) $bikes as $bike_row ) : ?>
									<option value="<?php echo esc_attr( (string) $bike_row->id ); ?>" <?php selected( $form_bike_id, (int) $bike_row->id ); ?>>
										<?php echo esc_html( $bike_row->bike_name ); ?>
									</option>
								<?php endforeach; ?>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="maintenance_date"><?php esc_html_e( 'Date', 'bikepress' ); ?></label></th>
						<td><input name="maintenance_date" id="maintenance_date" type="date" required value="<?php echo esc_attr( $form_date ); ?>" /></td>
					</tr>
					<tr>
						<th scope="row"><label for="maintenance_desc"><?php esc_html_e( 'Description', 'bikepress' ); ?></label></th>
						<td><input name="maintenance_desc" id="maintenance_desc" type="text" class="regular-text" required value="<?php echo esc_attr( $form_desc ); ?>" /></td>
					</tr>
					<tr>
						<th scope="row"><label for="bike_miles"><?php esc_html_e( 'Miles', 'bikepress' ); ?></label></th>
						<td><input name="bike_miles" id="bike_miles" type="number" min="0" step="1" class="small-text" value="<?php echo esc_attr( (string) $form_miles ); ?>" /></td>
					</tr>
				</tbody>
			</table>

			<?php submit_button( 'edit' === $action ? __( 'Update record', 'bikepress' ) : __( 'Add record', 'bikepress' ) ); ?>
			<a class="button" href="<?php echo esc_url( $list_url ); ?>"><?php esc_html_e( 'Cancel', 'bikepress' ); ?></a>
		</form>

	<?php else : ?>
		<form method="get" action="<?php echo esc_url( admin_url( 'admin.php' ) ); ?>" style="margin:1em 0;">
			<input type="hidden" name="page" value="maint-admin" />
			<label for="filter_bike_id" class="screen-reader-text"><?php esc_html_e( 'Filter by bike', 'bikepress' ); ?></label>
			<select name="bike_id" id="filter_bike_id">
				<option value="0"><?php esc_html_e( 'All bikes', 'bikepress' ); ?></option>
				<?php foreach ( (array) $bikes as $bike_row ) : ?>
					<option value="<?php echo esc_attr( (string) $bike_row->id ); ?>" <?php selected( $filter_bike_id, (int) $bike_row->id ); ?>>
						<?php echo esc_html( $bike_row->bike_name ); ?>
					</option>
				<?php endforeach; ?>
			</select>
			<?php submit_button( __( 'Filter', 'bikepress' ), 'secondary', '', false ); ?>
		</form>

		<?php
		if ( $filter_bike_id > 0 ) {
			$rows = $wpdb->get_results(
				$wpdb->prepare(
					'SELECT m.*, b.bike_name FROM ' . MAINTENANCE_TABLE . ' m
					INNER JOIN ' . BIKES_TABLE . ' b ON m.bike_id = b.id
					WHERE m.bike_id = %d
					ORDER BY m.maintenance_date DESC',
					$filter_bike_id
				)
			); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		} else {
			$rows = $wpdb->get_results(
				'SELECT m.*, b.bike_name FROM ' . MAINTENANCE_TABLE . ' m
				INNER JOIN ' . BIKES_TABLE . ' b ON m.bike_id = b.id
				ORDER BY m.maintenance_date DESC'
			); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		}
		?>

		<table class="wp-list-table widefat fixed striped">
			<thead>
				<tr>
					<th scope="col"><?php esc_html_e( 'Bike', 'bikepress' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Date', 'bikepress' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Description', 'bikepress' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Miles', 'bikepress' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Actions', 'bikepress' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php if ( empty( $rows ) ) : ?>
					<tr><td colspan="5"><?php esc_html_e( 'No maintenance records found.', 'bikepress' ); ?></td></tr>
				<?php else : ?>
					<?php foreach ( $rows as $row ) : ?>
						<?php
						$edit_args   = array( 'page' => 'maint-admin', 'action' => 'edit', 'maint_id' => absint( $row->id ) );
						$delete_args = array( 'page' => 'maint-admin', 'action' => 'delete', 'maint_id' => absint( $row->id ) );
						if ( $filter_bike_id > 0 ) {
							$edit_args['bike_id']   = $filter_bike_id;
							$delete_args['bike_id'] = $filter_bike_id;
						}
						$edit_url   = add_query_arg( $edit_args, admin_url( 'admin.php' ) );
						$delete_url = add_query_arg( $delete_args, admin_url( 'admin.php' ) );
						$bike_url   = add_query_arg(
							array(
								'page'    => 'bikes-admin',
								'action'  => 'edit',
								'bike_id' => absint( $row->bike_id ),
							),
							admin_url( 'admin.php' )
						);
						$date_disp  = ( ! empty( $row->maintenance_date ) && '0000-00-00 00:00:00' !== $row->maintenance_date )
							? gmdate( 'Y-m-d', strtotime( $row->maintenance_date ) )
							: '—';
						?>
						<tr>
							<td><a href="<?php echo esc_url( $bike_url ); ?>"><?php echo esc_html( $row->bike_name ); ?></a></td>
							<td><?php echo esc_html( $date_disp ); ?></td>
							<td><strong><a href="<?php echo esc_url( $edit_url ); ?>"><?php echo esc_html( $row->maintenance_desc ); ?></a></strong></td>
							<td><?php echo esc_html( (string) $row->bike_miles ); ?></td>
							<td>
								<a href="<?php echo esc_url( $edit_url ); ?>"><?php esc_html_e( 'Edit', 'bikepress' ); ?></a>
								|
								<a href="<?php echo esc_url( $delete_url ); ?>"><?php esc_html_e( 'Delete', 'bikepress' ); ?></a>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>
	<?php endif; ?>
	<?php include plugin_dir_path( __FILE__ ) . 'bike-admin-footer.php'; ?>
</div>
