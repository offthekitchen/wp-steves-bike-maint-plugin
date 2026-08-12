<?php
/**
 * Manage Bikes — list, add, edit, delete (Phase 1 CRUD).
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

$action  = isset( $_GET['action'] ) ? sanitize_key( wp_unslash( $_GET['action'] ) ) : 'list';
$bike_id = isset( $_GET['bike_id'] ) ? absint( $_GET['bike_id'] ) : 0;

if ( ! in_array( $action, array( 'list', 'new', 'edit', 'delete' ), true ) ) {
	$action = 'list';
}

$list_url = admin_url( 'admin.php?page=bikes-admin' );
$new_url  = admin_url( 'admin.php?page=bikes-admin&action=new' );

$statuses = $wpdb->get_results( 'SELECT id, bike_status FROM ' . STATUS_TABLE . ' ORDER BY bike_status ASC' ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
$types    = $wpdb->get_results( 'SELECT id, bike_type FROM ' . TYPE_TABLE . ' ORDER BY bike_type ASC' ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

$bike = null;
if ( 'edit' === $action || 'delete' === $action ) {
	if ( $bike_id <= 0 ) {
		$action = 'list';
	} else {
		$bike = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . BIKES_TABLE . ' WHERE id = %d', $bike_id ) ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		if ( ! $bike ) {
			$action = 'list';
			$bike_id = 0;
		}
	}
}

$notice = isset( $_GET['bikepress_notice'] ) ? sanitize_key( wp_unslash( $_GET['bikepress_notice'] ) ) : '';
$notice_messages = array(
	'bike_created'           => array( 'success', __( 'Bike created.', 'bikepress' ) ),
	'bike_updated'           => array( 'success', __( 'Bike updated.', 'bikepress' ) ),
	'bike_deleted'           => array( 'success', __( 'Bike and related specs/maintenance deleted.', 'bikepress' ) ),
	'bike_save_error'        => array( 'error', __( 'Could not save the bike.', 'bikepress' ) ),
	'bike_delete_error'      => array( 'error', __( 'Could not delete the bike.', 'bikepress' ) ),
	'bike_name_required'     => array( 'error', __( 'Bike name is required.', 'bikepress' ) ),
	'bike_status_required'   => array( 'error', __( 'Please choose a status.', 'bikepress' ) ),
	'bike_type_required'     => array( 'error', __( 'Please choose a type.', 'bikepress' ) ),
	'bike_make_too_long'     => array( 'error', __( 'Make cannot be longer than 15 characters.', 'bikepress' ) ),
	'bike_model_too_long'    => array( 'error', __( 'Model cannot be longer than 50 characters.', 'bikepress' ) ),
	'bike_serial_too_long'   => array( 'error', __( 'Serial number cannot be longer than 50 characters.', 'bikepress' ) ),
	'bike_field_too_long'    => array( 'error', __( 'One or more fields are too long for the database. Shorten make (15), model (50), or serial number (50) and try again.', 'bikepress' ) ),
);

$default_image = plugins_url( 'img/default-bike.jpg', dirname( dirname( __FILE__ ) ) . '/wp-bikepress.php' );

$form_bike_id        = 0;
$form_name           = '';
$form_make           = '';
$form_model          = '';
$form_serial         = '';
$form_status_id      = 0;
$form_type_id        = 0;
$form_desc           = '';
$form_image_id       = 0;
$form_purchase_date  = '';

if ( $bike ) {
	$form_bike_id       = absint( $bike->id );
	$form_name          = $bike->bike_name;
	$form_make          = $bike->bike_make;
	$form_model         = $bike->bike_model;
	$form_serial        = $bike->serial_number;
	$form_status_id     = absint( $bike->bike_status_id );
	$form_type_id       = absint( $bike->bike_type_id );
	$form_desc          = $bike->bike_desc;
	$form_image_id      = absint( $bike->bike_image_id );
	if ( ! empty( $bike->purchase_date ) && '0000-00-00 00:00:00' !== $bike->purchase_date ) {
		$form_purchase_date = gmdate( 'Y-m-d', strtotime( $bike->purchase_date ) );
	}
}

$preview_url = $default_image;
if ( $form_image_id > 0 ) {
	$src = wp_get_attachment_image_src( $form_image_id, 'medium' );
	if ( $src ) {
		$preview_url = $src[0];
	}
}
?>
<div class="wrap bikepress-bikes-admin">
	<?php include plugin_dir_path( __FILE__ ) . 'bike-admin-header.php'; ?>

	<h1 class="wp-heading-inline"><?php esc_html_e( 'Manage Bikes', 'bikepress' ); ?></h1>
	<?php if ( 'list' === $action ) : ?>
		<a href="<?php echo esc_url( $new_url ); ?>" class="page-title-action"><?php esc_html_e( 'Add New Bike', 'bikepress' ); ?></a>
	<?php endif; ?>
	<hr class="wp-header-end" />

	<?php if ( $notice && isset( $notice_messages[ $notice ] ) ) : ?>
		<div class="notice notice-<?php echo esc_attr( $notice_messages[ $notice ][0] ); ?> is-dismissible">
			<p><?php echo esc_html( $notice_messages[ $notice ][1] ); ?></p>
		</div>
	<?php endif; ?>

	<?php if ( 'delete' === $action && $bike ) : ?>
		<div class="notice notice-warning">
			<p>
				<?php
				echo esc_html(
					sprintf(
						/* translators: %s: bike name */
						__( 'Delete “%s”? This will also permanently delete related specs and maintenance records.', 'bikepress' ),
						$bike->bike_name
					)
				);
				?>
			</p>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<input type="hidden" name="action" value="bikepress_delete_bike" />
				<input type="hidden" name="bike_id" value="<?php echo esc_attr( (string) $bike->id ); ?>" />
				<?php wp_nonce_field( 'bikepress_delete_bike', 'bikepress_delete_bike_nonce' ); ?>
				<?php submit_button( __( 'Yes, delete bike', 'bikepress' ), 'delete', 'submit', false ); ?>
				<a class="button" href="<?php echo esc_url( $list_url ); ?>"><?php esc_html_e( 'Cancel', 'bikepress' ); ?></a>
			</form>
		</div>

	<?php elseif ( 'new' === $action || 'edit' === $action ) : ?>
		<?php if ( 'edit' === $action ) : ?>
			<h2>
				<?php
				echo esc_html(
					sprintf(
						/* translators: %s: bike name */
						__( 'Edit %s', 'bikepress' ),
						$form_name
					)
				);
				?>
			</h2>
			<p class="bikepress-edit-bike-actions">
				<a class="button" href="<?php echo esc_url( admin_url( 'admin.php?page=specs-admin&bike_id=' . $form_bike_id ) ); ?>"><?php esc_html_e( 'Specs', 'bikepress' ); ?></a>
				<a class="button" href="<?php echo esc_url( admin_url( 'admin.php?page=maint-admin&bike_id=' . $form_bike_id ) ); ?>"><?php esc_html_e( 'Maintenance', 'bikepress' ); ?></a>
				<a class="button" href="<?php echo esc_url( admin_url( 'admin.php?page=reports-admin&bike_id=' . $form_bike_id ) ); ?>"><?php esc_html_e( 'Bike Report', 'bikepress' ); ?></a>
			</p>
		<?php else : ?>
			<h2><?php esc_html_e( 'Add New Bike', 'bikepress' ); ?></h2>
		<?php endif; ?>

		<?php if ( empty( $statuses ) ) : ?>
			<div class="notice notice-error">
				<p><?php esc_html_e( 'No statuses found. Add statuses under Manage Supporting Data, or import demo data from that hub.', 'bikepress' ); ?></p>
			</div>
		<?php endif; ?>

		<?php if ( empty( $types ) ) : ?>
			<div class="notice notice-error">
				<p><?php esc_html_e( 'No types found. Add types under Manage Supporting Data.', 'bikepress' ); ?></p>
			</div>
		<?php endif; ?>

		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" id="bikepress-bike-form">
			<input type="hidden" name="action" value="bikepress_save_bike" />
			<input type="hidden" name="bike_id" value="<?php echo esc_attr( (string) $form_bike_id ); ?>" />
			<?php wp_nonce_field( 'bikepress_save_bike', 'bikepress_bike_nonce' ); ?>

			<table class="form-table" role="presentation">
				<tbody>
					<tr>
						<th scope="row"><label for="bike_name"><?php esc_html_e( 'Bike name', 'bikepress' ); ?></label></th>
						<td><input name="bike_name" id="bike_name" type="text" class="regular-text" required value="<?php echo esc_attr( $form_name ); ?>" /></td>
					</tr>
					<tr>
						<th scope="row"><label for="bike_make"><?php esc_html_e( 'Make', 'bikepress' ); ?></label></th>
						<td>
							<input name="bike_make" id="bike_make" type="text" class="regular-text" maxlength="15" value="<?php echo esc_attr( $form_make ); ?>" />
							<p class="description"><?php esc_html_e( 'Maximum 15 characters.', 'bikepress' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="bike_model"><?php esc_html_e( 'Model', 'bikepress' ); ?></label></th>
						<td>
							<input name="bike_model" id="bike_model" type="text" class="regular-text" maxlength="50" value="<?php echo esc_attr( $form_model ); ?>" />
							<p class="description"><?php esc_html_e( 'Maximum 50 characters.', 'bikepress' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="serial_number"><?php esc_html_e( 'Serial number', 'bikepress' ); ?></label></th>
						<td>
							<input name="serial_number" id="serial_number" type="text" class="regular-text" maxlength="50" value="<?php echo esc_attr( $form_serial ); ?>" />
							<p class="description"><?php esc_html_e( 'Maximum 50 characters.', 'bikepress' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="bike_type_id"><?php esc_html_e( 'Type', 'bikepress' ); ?></label></th>
						<td>
							<select name="bike_type_id" id="bike_type_id" required>
								<option value=""><?php esc_html_e( '— Select —', 'bikepress' ); ?></option>
								<?php foreach ( (array) $types as $type_row ) : ?>
									<option value="<?php echo esc_attr( (string) $type_row->id ); ?>" <?php selected( $form_type_id, (int) $type_row->id ); ?>>
										<?php echo esc_html( $type_row->bike_type ); ?>
									</option>
								<?php endforeach; ?>
							</select>
							<a class="bikepress-dropdown-edit" href="<?php echo esc_url( admin_url( 'admin.php?page=type-admin' ) ); ?>"><?php esc_html_e( 'edit', 'bikepress' ); ?></a>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="bike_status_id"><?php esc_html_e( 'Status', 'bikepress' ); ?></label></th>
						<td>
							<select name="bike_status_id" id="bike_status_id" required>
								<option value=""><?php esc_html_e( '— Select —', 'bikepress' ); ?></option>
								<?php foreach ( (array) $statuses as $status_row ) : ?>
									<option value="<?php echo esc_attr( (string) $status_row->id ); ?>" <?php selected( $form_status_id, (int) $status_row->id ); ?>>
										<?php echo esc_html( $status_row->bike_status ); ?>
									</option>
								<?php endforeach; ?>
							</select>
							<a class="bikepress-dropdown-edit" href="<?php echo esc_url( admin_url( 'admin.php?page=status-admin' ) ); ?>"><?php esc_html_e( 'edit', 'bikepress' ); ?></a>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="purchase_date"><?php esc_html_e( 'Purchase date', 'bikepress' ); ?></label></th>
						<td><input name="purchase_date" id="purchase_date" type="date" value="<?php echo esc_attr( $form_purchase_date ); ?>" /></td>
					</tr>
					<tr>
						<th scope="row"><label for="bike_desc"><?php esc_html_e( 'Description', 'bikepress' ); ?></label></th>
						<td>
							<textarea name="bike_desc" id="bike_desc" class="large-text" rows="5"><?php echo esc_textarea( $form_desc ); ?></textarea>
							<p class="description"><?php esc_html_e( 'Longer descriptions are supported.', 'bikepress' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Image', 'bikepress' ); ?></th>
						<td>
							<div id="bike_image_container" style="margin-bottom:8px;">
								<img id="bike-image" src="<?php echo esc_url( $preview_url ); ?>" alt="" style="max-width:200px;height:auto;" />
							</div>
							<input type="hidden" name="bike_image_id" id="bike-image-id" value="<?php echo esc_attr( (string) $form_image_id ); ?>" />
							<button type="button" class="button" id="select_image_button"><?php esc_html_e( 'Select image', 'bikepress' ); ?></button>
							<button type="button" class="button" id="clear_image_button"><?php esc_html_e( 'Clear image', 'bikepress' ); ?></button>
							<p class="description" id="bikepress-default-image" data-default-src="<?php echo esc_url( $default_image ); ?>">
								<?php esc_html_e( 'Uses the default bike image when cleared.', 'bikepress' ); ?>
							</p>
						</td>
					</tr>
				</tbody>
			</table>

			<?php
			submit_button(
				'edit' === $action ? __( 'Update bike', 'bikepress' ) : __( 'Add bike', 'bikepress' )
			);
			?>
			<a class="button" href="<?php echo esc_url( $list_url ); ?>"><?php esc_html_e( 'Cancel', 'bikepress' ); ?></a>
		</form>

	<?php else : ?>
		<?php
		$bikes = $wpdb->get_results(
			'SELECT b.*, s.bike_status, t.bike_type FROM ' . BIKES_TABLE . ' b
			LEFT JOIN ' . STATUS_TABLE . ' s ON b.bike_status_id = s.id
			LEFT JOIN ' . TYPE_TABLE . ' t ON b.bike_type_id = t.id
			ORDER BY b.bike_name ASC'
		); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		?>
		<table class="wp-list-table widefat fixed striped">
			<thead>
				<tr>
					<th scope="col"><?php esc_html_e( 'Name', 'bikepress' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Make', 'bikepress' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Model', 'bikepress' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Type', 'bikepress' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Status', 'bikepress' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Actions', 'bikepress' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php if ( empty( $bikes ) ) : ?>
					<tr>
						<td colspan="6"><?php esc_html_e( 'No bikes found.', 'bikepress' ); ?></td>
					</tr>
				<?php else : ?>
					<?php foreach ( $bikes as $row ) : ?>
						<?php
						$edit_url   = admin_url( 'admin.php?page=bikes-admin&action=edit&bike_id=' . absint( $row->id ) );
						$delete_url = admin_url( 'admin.php?page=bikes-admin&action=delete&bike_id=' . absint( $row->id ) );
						?>
						<tr>
							<td><strong><a href="<?php echo esc_url( $edit_url ); ?>"><?php echo esc_html( $row->bike_name ); ?></a></strong></td>
							<td><?php echo esc_html( $row->bike_make ); ?></td>
							<td><?php echo esc_html( $row->bike_model ); ?></td>
							<td><?php echo esc_html( $row->bike_type ? $row->bike_type : '—' ); ?></td>
							<td><?php echo esc_html( $row->bike_status ? $row->bike_status : '—' ); ?></td>
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
