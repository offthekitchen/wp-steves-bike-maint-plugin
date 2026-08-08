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
?>
<div class="bikepress-hub main-container">
	<?php include plugin_dir_path( __FILE__ ) . 'bike-admin-header.php'; ?>
	<h1><?php esc_html_e( 'MANAGE SUPPORTING DATA', 'bikepress' ); ?></h1>
	<main class="main-content">
		<section id="supporting-data-tools" class="bike-admin-tools">
			<div class="bikepress-hub-cards bike-admin-cards">

				<article class="bikepress-hub-card manage-status-card">
					<div class="card__info-hover">
						<img src="<?php echo esc_url( $img_base . 'admin-icon-status.png' ); ?>" alt="<?php esc_attr_e( 'status icon', 'bikepress' ); ?>" class="admin-icon">
					</div>
					<div class="card__img" style="background-image:url('<?php echo esc_url( $img_base . 'manage-data-thumbnail.jpg' ); ?>');"></div>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=status-admin' ) ); ?>" class="card_link">
						<div class="card__img--hover" style="background-image:url('<?php echo esc_url( $img_base . 'manage-data-thumbnail.jpg' ); ?>');"></div>
					</a>
					<div class="card__info">
						<span class="card__subcategory"><?php esc_html_e( 'Statuses', 'bikepress' ); ?></span>
						<h3 class="card__title"><?php esc_html_e( 'Manage Statuses', 'bikepress' ); ?></h3>
						<span class="card__desc"><?php esc_html_e( 'Add and Maintain Bike Statuses', 'bikepress' ); ?></span>
					</div>
				</article>

				<article class="bikepress-hub-card manage-import-export-card">
					<div class="card__info-hover">
						<img src="<?php echo esc_url( $img_base . 'admin-icon-import-export.png' ); ?>" alt="<?php esc_attr_e( 'import export icon', 'bikepress' ); ?>" class="admin-icon">
					</div>
					<div class="card__img" style="background-image:url('<?php echo esc_url( $img_base . 'manage-data-thumbnail.jpg' ); ?>');"></div>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=import-export-admin' ) ); ?>" class="card_link">
						<div class="card__img--hover" style="background-image:url('<?php echo esc_url( $img_base . 'manage-data-thumbnail.jpg' ); ?>');"></div>
					</a>
					<div class="card__info">
						<span class="card__subcategory"><?php esc_html_e( 'Backup', 'bikepress' ); ?></span>
						<h3 class="card__title"><?php esc_html_e( 'Import / Export Data', 'bikepress' ); ?></h3>
						<span class="card__desc"><?php esc_html_e( 'Download or restore a JSON backup of all BikePress data', 'bikepress' ); ?></span>
					</div>
				</article>

			</div>
		</section>
	</main>
	<?php include plugin_dir_path( __FILE__ ) . 'bike-admin-footer.php'; ?>
</div>
