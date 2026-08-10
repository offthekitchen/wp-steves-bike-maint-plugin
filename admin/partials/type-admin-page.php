<?php
/**
 * Manage Types — list, add, edit, delete.
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
$type_id = isset( $_GET['type_id'] ) ? absint( $_GET['type_id'] ) : 0;

if ( ! in_array( $action, array( 'list', 'new', 'edit', 'delete' ), true ) ) {
	$action = 'list';
}

$list_url = admin_url( 'admin.php?page=type-admin' );
$new_url  = admin_url( 'admin.php?page=type-admin&action=new' );
$hub_url  = admin_url( 'admin.php?page=supporting-data-admin' );

$type = null;
if ( 'edit' === $action || 'delete' === $action ) {
	if ( $type_id <= 0 ) {
		$action = 'list';
	} else {
		$type = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . TYPE_TABLE . ' WHERE id = %d', $type_id ) ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		if ( ! $type ) {
			$action  = 'list';
			$type_id = 0;
		}
	}
}

$notice = isset( $_GET['bikepress_notice'] ) ? sanitize_key( wp_unslash( $_GET['bikepress_notice'] ) ) : '';
$notice_messages = array(
	'type_created'            => array( 'success', __( 'Type created.', 'bikepress' ) ),
	'type_updated'            => array( 'success', __( 'Type updated.', 'bikepress' ) ),
	'type_deleted'            => array( 'success', __( 'Type deleted.', 'bikepress' ) ),
	'type_deleted_reassigned' => array( 'success', __( 'Type deleted. Bikes using it were reassigned to Unknown.', 'bikepress' ) ),
	'type_save_error'         => array( 'error', __( 'Could not save the type.', 'bikepress' ) ),
	'type_delete_error'       => array( 'error', __( 'Could not delete the type.', 'bikepress' ) ),
	'type_name_required'      => array( 'error', __( 'Type name is required.', 'bikepress' ) ),
	'type_unknown_protected'  => array( 'error', __( 'The Unknown type cannot be deleted.', 'bikepress' ) ),
);

$form_type_id = 0;
$form_name    = '';

if ( $type ) {
	$form_type_id = absint( $type->id );
	$form_name    = $type->bike_type;
}

$is_unknown = $type ? ( 'Unknown' === $type->bike_type ) : false;
?>
<div class="wrap bikepress-type-admin">
	<?php include plugin_dir_path( __FILE__ ) . 'bike-admin-header.php'; ?>
	<p><a href="<?php echo esc_url( $hub_url ); ?>">&larr; <?php esc_html_e( 'Return to Supporting Data', 'bikepress' ); ?></a></p>

	<h1 class="wp-heading-inline"><?php esc_html_e( 'Manage Types', 'bikepress' ); ?></h1>
	<?php if ( 'list' === $action ) : ?>
		<a href="<?php echo esc_url( $new_url ); ?>" class="page-title-action"><?php esc_html_e( 'Add New Type', 'bikepress' ); ?></a>
	<?php endif; ?>
	<hr class="wp-header-end" />

	<?php if ( $notice && isset( $notice_messages[ $notice ] ) ) : ?>
		<div class="notice notice-<?php echo esc_attr( $notice_messages[ $notice ][0] ); ?> is-dismissible">
			<p><?php echo esc_html( $notice_messages[ $notice ][1] ); ?></p>
		</div>
	<?php endif; ?>

	<?php if ( 'delete' === $action && $type ) : ?>
		<?php if ( $is_unknown ) : ?>
			<div class="notice notice-error">
				<p><?php esc_html_e( 'The Unknown type cannot be deleted.', 'bikepress' ); ?></p>
				<a class="button" href="<?php echo esc_url( $list_url ); ?>"><?php esc_html_e( 'Back to list', 'bikepress' ); ?></a>
			</div>
		<?php else : ?>
			<?php
			$bike_count_for_type = (int) $wpdb->get_var(
				$wpdb->prepare(
					'SELECT COUNT(*) FROM ' . BIKES_TABLE . ' WHERE bike_type_id = %d',
					absint( $type->id )
				)
			); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			?>
			<div class="notice notice-warning">
				<p>
					<?php
					if ( $bike_count_for_type > 0 ) {
						echo esc_html(
							sprintf(
								/* translators: 1: type name, 2: bike count */
								_n(
									'Delete type “%1$s”? %2$d bike using this type will be reassigned to Unknown.',
									'Delete type “%1$s”? %2$d bikes using this type will be reassigned to Unknown.',
									$bike_count_for_type,
									'bikepress'
								),
								$type->bike_type,
								$bike_count_for_type
							)
						);
					} else {
						echo esc_html(
							sprintf(
								/* translators: %s: type name */
								__( 'Delete type “%s”? No bikes currently use this type.', 'bikepress' ),
								$type->bike_type
							)
						);
					}
					?>
				</p>
				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
					<input type="hidden" name="action" value="bikepress_delete_type" />
					<input type="hidden" name="type_id" value="<?php echo esc_attr( (string) $type->id ); ?>" />
					<?php wp_nonce_field( 'bikepress_delete_type', 'bikepress_delete_type_nonce' ); ?>
					<?php submit_button( __( 'Yes, delete type', 'bikepress' ), 'delete', 'submit', false ); ?>
					<a class="button" href="<?php echo esc_url( $list_url ); ?>"><?php esc_html_e( 'Cancel', 'bikepress' ); ?></a>
				</form>
			</div>
		<?php endif; ?>

	<?php elseif ( 'new' === $action || 'edit' === $action ) : ?>
		<h2><?php echo 'edit' === $action ? esc_html__( 'Edit Type', 'bikepress' ) : esc_html__( 'Add New Type', 'bikepress' ); ?></h2>

		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="bikepress_save_type" />
			<input type="hidden" name="type_id" value="<?php echo esc_attr( (string) $form_type_id ); ?>" />
			<?php wp_nonce_field( 'bikepress_save_type', 'bikepress_type_nonce' ); ?>

			<table class="form-table" role="presentation">
				<tbody>
					<tr>
						<th scope="row"><label for="bike_type"><?php esc_html_e( 'Type name', 'bikepress' ); ?></label></th>
						<td><input name="bike_type" id="bike_type" type="text" class="regular-text" required value="<?php echo esc_attr( $form_name ); ?>" /></td>
					</tr>
				</tbody>
			</table>

			<?php submit_button( 'edit' === $action ? __( 'Update type', 'bikepress' ) : __( 'Add type', 'bikepress' ) ); ?>
			<a class="button" href="<?php echo esc_url( $list_url ); ?>"><?php esc_html_e( 'Cancel', 'bikepress' ); ?></a>
		</form>

	<?php else : ?>
		<?php
		$rows = $wpdb->get_results(
			'SELECT t.*, (
				SELECT COUNT(*) FROM ' . BIKES_TABLE . ' b WHERE b.bike_type_id = t.id
			) AS bike_count
			FROM ' . TYPE_TABLE . ' t
			ORDER BY t.bike_type ASC'
		); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		?>

		<table class="wp-list-table widefat fixed striped">
			<thead>
				<tr>
					<th scope="col"><?php esc_html_e( 'Type', 'bikepress' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Bikes using', 'bikepress' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Actions', 'bikepress' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php if ( empty( $rows ) ) : ?>
					<tr><td colspan="3"><?php esc_html_e( 'No types found.', 'bikepress' ); ?></td></tr>
				<?php else : ?>
					<?php foreach ( $rows as $row ) : ?>
						<?php
						$row_is_unknown = ( 'Unknown' === $row->bike_type );
						$edit_url       = admin_url( 'admin.php?page=type-admin&action=edit&type_id=' . absint( $row->id ) );
						$delete_url     = admin_url( 'admin.php?page=type-admin&action=delete&type_id=' . absint( $row->id ) );
						?>
						<tr>
							<td><strong><a href="<?php echo esc_url( $edit_url ); ?>"><?php echo esc_html( $row->bike_type ); ?></a></strong></td>
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
	<?php include plugin_dir_path( __FILE__ ) . 'bike-admin-footer.php'; ?>
</div>
