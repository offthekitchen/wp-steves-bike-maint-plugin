<?php
/**
 * Supporting Data hub — card entry points for statuses (and future entities).
 *
 * @package BikePress
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! current_user_can( 'manage_options' ) ) {
	wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'bikepress' ) );
}

$img_base = plugin_dir_url( __DIR__ ) . 'img/';
$notice   = isset( $_GET['bikepress_notice'] ) ? sanitize_key( wp_unslash( $_GET['bikepress_notice'] ) ) : '';

$notice_messages = array(
	'demo_ok'     => array( 'success', __( 'Demo data imported.', 'bikepress' ) ),
	'demo_exists' => array( 'warning', __( 'Demo data was not imported because bikes already exist.', 'bikepress' ) ),
);

$demo_import_url = wp_nonce_url(
	add_query_arg(
		array(
			'action'      => 'bikepress_import_demo_data',
			'redirect_to' => 'supporting-data-admin',
		),
		admin_url( 'admin-post.php' )
	),
	'bikepress_import_demo_data'
);
?>
<div class="bikepress-hub main-container">
	<?php include plugin_dir_path( __FILE__ ) . 'bike-admin-header.php'; ?>
	<h1><?php esc_html_e( 'MANAGE SUPPORTING DATA', 'bikepress' ); ?></h1>

	<?php if ( $notice && isset( $notice_messages[ $notice ] ) ) : ?>
		<div class="notice notice-<?php echo esc_attr( $notice_messages[ $notice ][0] ); ?> is-dismissible" style="margin: 12px 0;">
			<p><?php echo esc_html( $notice_messages[ $notice ][1] ); ?></p>
		</div>
	<?php endif; ?>

	<main class="main-content">
		<section id="supporting-data-tools" class="bike-admin-tools">
			<div class="bikepress-hub-cards bike-admin-cards">

				<article class="bikepress-hub-card manage-status-card">
					<div class="card__info-hover">
						<img src="<?php echo esc_url( $img_base . 'admin-icon-status.png' ); ?>" alt="<?php esc_attr_e( 'status icon', 'bikepress' ); ?>" class="admin-icon">
					</div>
					<div class="card__img" style="background-image:url('<?php echo esc_url( $img_base . 'manage-statuses-thumbnail.jpg' ); ?>');"></div>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=status-admin' ) ); ?>" class="card_link">
						<div class="card__img--hover" style="background-image:url('<?php echo esc_url( $img_base . 'manage-statuses-thumbnail.jpg' ); ?>');"></div>
					</a>
					<div class="card__info">
						<span class="card__subcategory"><?php esc_html_e( 'Statuses', 'bikepress' ); ?></span>
						<h3 class="card__title"><?php esc_html_e( 'Manage Statuses', 'bikepress' ); ?></h3>
						<span class="card__desc"><?php esc_html_e( 'Add and Maintain Bike Statuses', 'bikepress' ); ?></span>
					</div>
				</article>

				<article class="bikepress-hub-card manage-type-card">
					<div class="card__info-hover">
						<img src="<?php echo esc_url( $img_base . 'admin-icon-data.png' ); ?>" alt="<?php esc_attr_e( 'type icon', 'bikepress' ); ?>" class="admin-icon">
					</div>
					<div class="card__img" style="background-image:url('<?php echo esc_url( $img_base . 'manage-types-thumbnail.jpg' ); ?>');"></div>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=type-admin' ) ); ?>" class="card_link">
						<div class="card__img--hover" style="background-image:url('<?php echo esc_url( $img_base . 'manage-types-thumbnail.jpg' ); ?>');"></div>
					</a>
					<div class="card__info">
						<span class="card__subcategory"><?php esc_html_e( 'Types', 'bikepress' ); ?></span>
						<h3 class="card__title"><?php esc_html_e( 'Manage Types', 'bikepress' ); ?></h3>
						<span class="card__desc"><?php esc_html_e( 'Add and Maintain Bike Types', 'bikepress' ); ?></span>
					</div>
				</article>

				<article class="bikepress-hub-card manage-import-export-card">
					<div class="card__info-hover">
						<img src="<?php echo esc_url( $img_base . 'admin-icon-import-export.png' ); ?>" alt="<?php esc_attr_e( 'import export icon', 'bikepress' ); ?>" class="admin-icon">
					</div>
					<div class="card__img" style="background-image:url('<?php echo esc_url( $img_base . 'manage-import-export-thumbnail.jpg' ); ?>');"></div>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=import-export-admin' ) ); ?>" class="card_link">
						<div class="card__img--hover" style="background-image:url('<?php echo esc_url( $img_base . 'manage-import-export-thumbnail.jpg' ); ?>');"></div>
					</a>
					<div class="card__info">
						<span class="card__subcategory"><?php esc_html_e( 'Backup', 'bikepress' ); ?></span>
						<h3 class="card__title"><?php esc_html_e( 'Import / Export Data', 'bikepress' ); ?></h3>
						<span class="card__desc"><?php esc_html_e( 'Download or restore a JSON backup of all BikePress data', 'bikepress' ); ?></span>
					</div>
				</article>

				<article class="bikepress-hub-card manage-demo-data-card">
					<div class="card__info-hover">
						<img src="<?php echo esc_url( $img_base . 'admin-icon-data.png' ); ?>" alt="<?php esc_attr_e( 'demo data icon', 'bikepress' ); ?>" class="admin-icon">
					</div>
					<div class="card__img" style="background-image:url('<?php echo esc_url( $img_base . 'manage-demo-data-thumbnail.jpg' ); ?>');"></div>
					<a href="<?php echo esc_url( $demo_import_url ); ?>" class="card_link">
						<div class="card__img--hover" style="background-image:url('<?php echo esc_url( $img_base . 'manage-demo-data-thumbnail.jpg' ); ?>');"></div>
					</a>
					<div class="card__info">
						<span class="card__subcategory"><?php esc_html_e( 'Sample', 'bikepress' ); ?></span>
						<h3 class="card__title"><?php esc_html_e( 'Import Demo Data', 'bikepress' ); ?></h3>
						<span class="card__desc"><?php esc_html_e( 'Load sample bikes, specs, and maintenance records', 'bikepress' ); ?></span>
					</div>
				</article>

			</div>
		</section>
	</main>
	<?php include plugin_dir_path( __FILE__ ) . 'bike-admin-footer.php'; ?>
</div>
