<?php
/**
 * My Bikes hub.
 *
 * @package BikePress
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

$img_base = plugin_dir_url( __DIR__ ) . 'img/';
?>
<div class="bikepress-hub main-container">
	<?php include plugin_dir_path( __FILE__ ) . 'bike-admin-header.php'; ?>
	<h1><?php esc_html_e( 'MY BIKES', 'bikepress' ); ?></h1>
	<main class="main-content">
		<section id="bike-admin-tools" class="bike-admin-tools">
			<div class="bikepress-hub-cards bike-admin-cards">

				<article class="bikepress-hub-card manage-bikes-card">
					<div class="card__info-hover">
						<img src="<?php echo esc_url( $img_base . 'admin-icon-bikes.png' ); ?>" alt="<?php esc_attr_e( 'bike icon', 'bikepress' ); ?>" class="admin-icon">
					</div>
					<div class="card__img" style="background-image:url('<?php echo esc_url( $img_base . 'manage-bikes-thumbnail.jpg' ); ?>');"></div>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=bikes-admin' ) ); ?>" class="card_link">
						<div class="card__img--hover" style="background-image:url('<?php echo esc_url( $img_base . 'manage-bikes-thumbnail.jpg' ); ?>');"></div>
					</a>
					<div class="card__info">
						<span class="card__subcategory"><?php esc_html_e( '5 bikes', 'bikepress' ); ?></span>
						<h3 class="card__title"><?php esc_html_e( 'Manage Bikes', 'bikepress' ); ?></h3>
						<span class="card__desc"><?php esc_html_e( 'Add new bikes and maintain existing ones', 'bikepress' ); ?></span>
					</div>
				</article>

				<article class="bikepress-hub-card manage-specs-card">
					<div class="card__info-hover">
						<img src="<?php echo esc_url( $img_base . 'admin-icon-specs.png' ); ?>" alt="<?php esc_attr_e( 'specs icon', 'bikepress' ); ?>" class="admin-icon">
					</div>
					<div class="card__img" style="background-image:url('<?php echo esc_url( $img_base . 'manage-specs-thumbnail.jpg' ); ?>');"></div>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=specs-admin' ) ); ?>" class="card_link">
						<div class="card__img--hover" style="background-image:url('<?php echo esc_url( $img_base . 'manage-specs-thumbnail.jpg' ); ?>');"></div>
					</a>
					<div class="card__info">
						<span class="card__subcategory"><?php esc_html_e( 'Subtitle', 'bikepress' ); ?></span>
						<h3 class="card__title"><?php esc_html_e( 'Manage Specs', 'bikepress' ); ?></h3>
						<span class="card__desc"><?php esc_html_e( 'Maintain bike specifications', 'bikepress' ); ?></span>
					</div>
				</article>

				<article class="bikepress-hub-card manage-maint-card">
					<div class="card__info-hover">
						<img src="<?php echo esc_url( $img_base . 'admin-icon-maint.png' ); ?>" alt="<?php esc_attr_e( 'maint icon', 'bikepress' ); ?>" class="admin-icon">
					</div>
					<div class="card__img" style="background-image:url('<?php echo esc_url( $img_base . 'manage-data-thumbnail.jpg' ); ?>');"></div>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=maint-admin' ) ); ?>" class="card_link">
						<div class="card__img--hover" style="background-image:url('<?php echo esc_url( $img_base . 'manage-data-thumbnail.jpg' ); ?>');"></div>
					</a>
					<div class="card__info">
						<span class="card__subcategory"><?php esc_html_e( 'Last Maintenance: March 1, 2025', 'bikepress' ); ?></span>
						<h3 class="card__title"><?php esc_html_e( 'Manage Maintenance Records', 'bikepress' ); ?></h3>
						<span class="card__desc"><?php esc_html_e( 'Maintain bike maintenance records', 'bikepress' ); ?></span>
					</div>
				</article>

				<article class="bikepress-hub-card manage-data-card">
					<div class="card__info-hover">
						<img src="<?php echo esc_url( $img_base . 'admin-icon-data.png' ); ?>" alt="<?php esc_attr_e( 'data icon', 'bikepress' ); ?>" class="admin-icon">
					</div>
					<div class="card__img" style="background-image:url('<?php echo esc_url( $img_base . 'manage-maint-thumbnail.jpg' ); ?>');"></div>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=supporting-data-admin' ) ); ?>" class="card_link">
						<div class="card__img--hover" style="background-image:url('<?php echo esc_url( $img_base . 'manage-maint-thumbnail.jpg' ); ?>');"></div>
					</a>
					<div class="card__info">
						<span class="card__subcategory"><?php esc_html_e( 'Subtitle', 'bikepress' ); ?></span>
						<h3 class="card__title"><?php esc_html_e( 'Manage Supporting Data', 'bikepress' ); ?></h3>
						<span class="card__desc"><?php esc_html_e( 'Maintain supporting bike data (e.g. statuses)', 'bikepress' ); ?></span>
					</div>
				</article>

			</div>
		</section>
	</main>
	<?php include plugin_dir_path( __FILE__ ) . 'bike-admin-footer.php'; ?>
</div>
