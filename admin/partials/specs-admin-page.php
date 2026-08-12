<?php
/**
 * Manage Specs — list, add, edit, delete (Phase 2 CRUD).
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
$spec_id        = isset( $_GET['spec_id'] ) ? absint( $_GET['spec_id'] ) : 0;
$filter_bike_id = isset( $_GET['bike_id'] ) ? absint( $_GET['bike_id'] ) : 0;

if ( ! in_array( $action, array( 'list', 'new', 'edit', 'delete' ), true ) ) {
	$action = 'list';
}

$bikes = $wpdb->get_results( 'SELECT id, bike_name FROM ' . BIKES_TABLE . ' ORDER BY bike_name ASC' ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

$list_args = array( 'page' => 'specs-admin' );
$new_args  = array( 'page' => 'specs-admin', 'action' => 'new' );
if ( $filter_bike_id > 0 ) {
	$list_args['bike_id'] = $filter_bike_id;
	$new_args['bike_id']  = $filter_bike_id;
}
$list_url = add_query_arg( $list_args, admin_url( 'admin.php' ) );
$new_url  = add_query_arg( $new_args, admin_url( 'admin.php' ) );

$spec = null;
if ( 'edit' === $action || 'delete' === $action ) {
	if ( $spec_id <= 0 ) {
		$action = 'list';
	} else {
		$spec = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . SPECS_TABLE . ' WHERE id = %d', $spec_id ) ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		if ( ! $spec ) {
			$action  = 'list';
			$spec_id = 0;
		}
	}
}

$notice = isset( $_GET['bikepress_notice'] ) ? sanitize_key( wp_unslash( $_GET['bikepress_notice'] ) ) : '';
$notice_messages = array(
	'spec_created'       => array( 'success', __( 'Spec created.', 'bikepress' ) ),
	'spec_updated'       => array( 'success', __( 'Spec updated.', 'bikepress' ) ),
	'spec_deleted'       => array( 'success', __( 'Spec deleted.', 'bikepress' ) ),
	'spec_save_error'    => array( 'error', __( 'Could not save the spec.', 'bikepress' ) ),
	'spec_delete_error'  => array( 'error', __( 'Could not delete the spec.', 'bikepress' ) ),
	'spec_name_required' => array( 'error', __( 'Spec name is required.', 'bikepress' ) ),
	'spec_bike_required' => array( 'error', __( 'Please choose a bike.', 'bikepress' ) ),
);

$form_spec_id   = 0;
$form_bike_id   = $filter_bike_id;
$form_spec_name = '';
$form_spec_desc = '';

if ( $spec ) {
	$form_spec_id   = absint( $spec->id );
	$form_bike_id   = absint( $spec->bike_id );
	$form_spec_name = $spec->spec_name;
	$form_spec_desc = $spec->spec_desc;
}
?>
<div class="wrap bikepress-specs-admin">
	<?php include plugin_dir_path( __FILE__ ) . 'bike-admin-header.php'; ?>

	<h1 class="wp-heading-inline"><?php esc_html_e( 'Manage Specs', 'bikepress' ); ?></h1>
	<?php if ( 'list' === $action ) : ?>
		<a href="<?php echo esc_url( $new_url ); ?>" class="page-title-action"><?php esc_html_e( 'Add New Spec', 'bikepress' ); ?></a>
	<?php endif; ?>
	<hr class="wp-header-end" />

	<?php if ( $notice && isset( $notice_messages[ $notice ] ) ) : ?>
		<div class="notice notice-<?php echo esc_attr( $notice_messages[ $notice ][0] ); ?> is-dismissible">
			<p><?php echo esc_html( $notice_messages[ $notice ][1] ); ?></p>
		</div>
	<?php endif; ?>

	<?php if ( 'delete' === $action && $spec ) : ?>
		<div class="notice notice-warning">
			<p>
				<?php
				echo esc_html(
					sprintf(
						/* translators: %s: spec name */
						__( 'Delete spec “%s”?', 'bikepress' ),
						$spec->spec_name
					)
				);
				?>
			</p>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<input type="hidden" name="action" value="bikepress_delete_spec" />
				<input type="hidden" name="spec_id" value="<?php echo esc_attr( (string) $spec->id ); ?>" />
				<input type="hidden" name="filter_bike_id" value="<?php echo esc_attr( (string) $filter_bike_id ); ?>" />
				<?php wp_nonce_field( 'bikepress_delete_spec', 'bikepress_delete_spec_nonce' ); ?>
				<?php submit_button( __( 'Yes, delete spec', 'bikepress' ), 'delete', 'submit', false ); ?>
				<a class="button" href="<?php echo esc_url( $list_url ); ?>"><?php esc_html_e( 'Cancel', 'bikepress' ); ?></a>
			</form>
		</div>

	<?php elseif ( 'new' === $action || 'edit' === $action ) : ?>
		<h2><?php echo 'edit' === $action ? esc_html__( 'Edit Spec', 'bikepress' ) : esc_html__( 'Add New Spec', 'bikepress' ); ?></h2>

		<?php if ( empty( $bikes ) ) : ?>
			<div class="notice notice-error"><p><?php esc_html_e( 'No bikes found. Add a bike first.', 'bikepress' ); ?></p></div>
		<?php endif; ?>

		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="bikepress_save_spec" />
			<input type="hidden" name="spec_id" value="<?php echo esc_attr( (string) $form_spec_id ); ?>" />
			<input type="hidden" name="filter_bike_id" value="<?php echo esc_attr( (string) $filter_bike_id ); ?>" />
			<?php wp_nonce_field( 'bikepress_save_spec', 'bikepress_spec_nonce' ); ?>

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
							<a
								class="bikepress-dropdown-edit bikepress-bike-edit-link"
								href="<?php echo esc_url( $form_bike_id > 0 ? admin_url( 'admin.php?page=bikes-admin&action=edit&bike_id=' . $form_bike_id ) : admin_url( 'admin.php?page=bikes-admin' ) ); ?>"
								data-bikepress-select="#bike_id"
								data-bikepress-edit-url="<?php echo esc_url( admin_url( 'admin.php?page=bikes-admin&action=edit&bike_id=' ) ); ?>"
								data-bikepress-list-url="<?php echo esc_url( admin_url( 'admin.php?page=bikes-admin' ) ); ?>"
							><?php esc_html_e( 'edit', 'bikepress' ); ?></a>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="spec_name"><?php esc_html_e( 'Spec name', 'bikepress' ); ?></label></th>
						<td><input name="spec_name" id="spec_name" type="text" class="regular-text" required value="<?php echo esc_attr( $form_spec_name ); ?>" /></td>
					</tr>
					<tr>
						<th scope="row"><label for="spec_desc"><?php esc_html_e( 'Description', 'bikepress' ); ?></label></th>
						<td><input name="spec_desc" id="spec_desc" type="text" class="regular-text" value="<?php echo esc_attr( $form_spec_desc ); ?>" /></td>
					</tr>
				</tbody>
			</table>

			<?php submit_button( 'edit' === $action ? __( 'Update spec', 'bikepress' ) : __( 'Add spec', 'bikepress' ) ); ?>
			<a class="button" href="<?php echo esc_url( $list_url ); ?>"><?php esc_html_e( 'Cancel', 'bikepress' ); ?></a>
		</form>

	<?php else : ?>
		<form method="get" action="<?php echo esc_url( admin_url( 'admin.php' ) ); ?>" style="margin:1em 0;">
			<input type="hidden" name="page" value="specs-admin" />
			<label for="filter_bike_id" class="screen-reader-text"><?php esc_html_e( 'Filter by bike', 'bikepress' ); ?></label>
			<select name="bike_id" id="filter_bike_id">
				<option value="0"><?php esc_html_e( 'All bikes', 'bikepress' ); ?></option>
				<?php foreach ( (array) $bikes as $bike_row ) : ?>
					<option value="<?php echo esc_attr( (string) $bike_row->id ); ?>" <?php selected( $filter_bike_id, (int) $bike_row->id ); ?>>
						<?php echo esc_html( $bike_row->bike_name ); ?>
					</option>
				<?php endforeach; ?>
			</select>
			<a
				class="bikepress-dropdown-edit bikepress-bike-edit-link"
				href="<?php echo esc_url( $filter_bike_id > 0 ? admin_url( 'admin.php?page=bikes-admin&action=edit&bike_id=' . $filter_bike_id ) : admin_url( 'admin.php?page=bikes-admin' ) ); ?>"
				data-bikepress-select="#filter_bike_id"
				data-bikepress-edit-url="<?php echo esc_url( admin_url( 'admin.php?page=bikes-admin&action=edit&bike_id=' ) ); ?>"
				data-bikepress-list-url="<?php echo esc_url( admin_url( 'admin.php?page=bikes-admin' ) ); ?>"
			><?php esc_html_e( 'edit', 'bikepress' ); ?></a>
			<p class="bikepress-filter-actions">
				<?php submit_button( __( 'Filter', 'bikepress' ), 'secondary', '', false ); ?>
			</p>
		</form>

		<?php
		if ( $filter_bike_id > 0 ) {
			$rows = $wpdb->get_results(
				$wpdb->prepare(
					'SELECT s.*, b.bike_name FROM ' . SPECS_TABLE . ' s
					INNER JOIN ' . BIKES_TABLE . ' b ON s.bike_id = b.id
					WHERE s.bike_id = %d
					ORDER BY s.spec_name ASC',
					$filter_bike_id
				)
			); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		} else {
			$rows = $wpdb->get_results(
				'SELECT s.*, b.bike_name FROM ' . SPECS_TABLE . ' s
				INNER JOIN ' . BIKES_TABLE . ' b ON s.bike_id = b.id
				ORDER BY b.bike_name ASC, s.spec_name ASC'
			); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		}
		?>

		<table class="wp-list-table widefat fixed striped">
			<thead>
				<tr>
					<th scope="col"><?php esc_html_e( 'Bike', 'bikepress' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Spec name', 'bikepress' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Description', 'bikepress' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Actions', 'bikepress' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php if ( empty( $rows ) ) : ?>
					<tr><td colspan="4"><?php esc_html_e( 'No specs found.', 'bikepress' ); ?></td></tr>
				<?php else : ?>
					<?php foreach ( $rows as $row ) : ?>
						<?php
						$edit_args   = array( 'page' => 'specs-admin', 'action' => 'edit', 'spec_id' => absint( $row->id ) );
						$delete_args = array( 'page' => 'specs-admin', 'action' => 'delete', 'spec_id' => absint( $row->id ) );
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
						?>
						<tr>
							<td><a href="<?php echo esc_url( $bike_url ); ?>"><?php echo esc_html( $row->bike_name ); ?></a></td>
							<td><strong><a href="<?php echo esc_url( $edit_url ); ?>"><?php echo esc_html( $row->spec_name ); ?></a></strong></td>
							<td><?php echo esc_html( $row->spec_desc ); ?></td>
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
