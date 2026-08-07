<?php
/**
 * Import / Export admin page.
 *
 * @package BikePress
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! current_user_can( 'manage_options' ) ) {
	wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'bikepress' ) );
}

$hub_url = admin_url( 'admin.php?page=supporting-data-admin' );
$notice  = isset( $_GET['bikepress_notice'] ) ? sanitize_key( wp_unslash( $_GET['bikepress_notice'] ) ) : '';

$notice_messages = array(
	'import_ok'             => array( 'success', __( 'Import completed.', 'bikepress' ) ),
	'import_invalid_json'   => array( 'error', __( 'Import file is not valid JSON.', 'bikepress' ) ),
	'import_invalid_format' => array( 'error', __( 'Import file is not a BikePress export.', 'bikepress' ) ),
	'import_db_mismatch'    => array( 'error', __( 'Import blocked: database version in the file does not match this site.', 'bikepress' ) ),
	'import_missing_data'   => array( 'error', __( 'Import file has no data section.', 'bikepress' ) ),
	'import_upload_error'   => array( 'error', __( 'Could not read the uploaded file.', 'bikepress' ) ),
	'import_error'          => array( 'error', __( 'Import failed.', 'bikepress' ) ),
);

$flash_message = '';
$import_detail = '';
$flash         = get_transient( 'bikepress_import_flash_' . get_current_user_id() );
if ( is_array( $flash ) ) {
	delete_transient( 'bikepress_import_flash_' . get_current_user_id() );
	if ( ! empty( $flash['notice'] ) ) {
		$notice = sanitize_key( $flash['notice'] );
	}
	if ( ! empty( $flash['message'] ) ) {
		$flash_message = (string) $flash['message'];
	}
	if ( ! empty( $flash['stats'] ) && is_array( $flash['stats'] ) ) {
		$parts = array();
		foreach ( $flash['stats'] as $entity => $counts ) {
			if ( ! is_array( $counts ) ) {
				continue;
			}
			$parts[] = sprintf(
				'%s: %d inserted, %d updated, %d errors',
				$entity,
				isset( $counts['inserted'] ) ? (int) $counts['inserted'] : 0,
				isset( $counts['updated'] ) ? (int) $counts['updated'] : 0,
				isset( $counts['errors'] ) ? (int) $counts['errors'] : 0
			);
		}
		$import_detail = implode( ' | ', $parts );
	}
}

$db_version = BikePress_Import_Export::current_db_version();
?>
<div class="wrap bikepress-import-export-admin">
	<?php include plugin_dir_path( __FILE__ ) . 'bike-admin-header.php'; ?>
	<p><a href="<?php echo esc_url( $hub_url ); ?>">&larr; <?php esc_html_e( 'Return to Supporting Data', 'bikepress' ); ?></a></p>

	<h1><?php esc_html_e( 'Import / Export Data', 'bikepress' ); ?></h1>
	<hr class="wp-header-end" />

	<?php if ( $notice && isset( $notice_messages[ $notice ] ) ) : ?>
		<div class="notice notice-<?php echo esc_attr( $notice_messages[ $notice ][0] ); ?> is-dismissible">
			<p><?php echo esc_html( $flash_message ? $flash_message : $notice_messages[ $notice ][1] ); ?></p>
			<?php if ( $import_detail ) : ?>
				<p><?php echo esc_html( $import_detail ); ?></p>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<p>
		<?php
		printf(
			/* translators: %s: current db version */
			esc_html__( 'Exports include all statuses, bikes, specs, and maintenance records, tagged with database version %s.', 'bikepress' ),
			esc_html( $db_version )
		);
		?>
	</p>

	<h2><?php esc_html_e( 'Export', 'bikepress' ); ?></h2>
	<p><?php esc_html_e( 'Download a JSON backup of the current BikePress data.', 'bikepress' ); ?></p>
	<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
		<input type="hidden" name="action" value="bikepress_export_data" />
		<?php wp_nonce_field( 'bikepress_export_data', 'bikepress_export_nonce' ); ?>
		<?php submit_button( __( 'Download export file', 'bikepress' ), 'primary', 'submit', false ); ?>
	</form>

	<hr />

	<h2><?php esc_html_e( 'Import', 'bikepress' ); ?></h2>
	<p><?php esc_html_e( 'Upload a BikePress JSON export. Matching row IDs are updated; new IDs are inserted. Imports are blocked if the file’s database version does not match this site.', 'bikepress' ); ?></p>
	<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" enctype="multipart/form-data">
		<input type="hidden" name="action" value="bikepress_import_data" />
		<?php wp_nonce_field( 'bikepress_import_data', 'bikepress_import_nonce' ); ?>
		<p>
			<label for="bikepress_import_file"><?php esc_html_e( 'Export file (.json)', 'bikepress' ); ?></label><br />
			<input type="file" name="bikepress_import_file" id="bikepress_import_file" accept=".json,application/json" required />
		</p>
		<?php submit_button( __( 'Import file', 'bikepress' ), 'secondary', 'submit', false ); ?>
	</form>

	<?php include plugin_dir_path( __FILE__ ) . 'bike-admin-footer.php'; ?>
</div>
