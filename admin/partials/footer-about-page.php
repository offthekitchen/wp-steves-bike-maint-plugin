<?php
/**
 * About Me — footer content page.
 *
 * @package BikePress
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! current_user_can( 'manage_options' ) ) {
	wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'bikepress' ) );
}

$about_image = plugin_dir_url( __DIR__ ) . 'img/about-image.jpg';
?>
<div class="wrap bikepress-footer-content">
	<?php include plugin_dir_path( __FILE__ ) . 'bike-admin-header.php'; ?>

	<h1><?php esc_html_e( 'About Me', 'bikepress' ); ?></h1>
	<hr class="wp-header-end" />

	<div class="bikepress-prose bikepress-about">
		<p class="bikepress-about-image">
			<img src="<?php echo esc_url( $about_image ); ?>" alt="<?php esc_attr_e( 'Steve Weeks', 'bikepress' ); ?>" />
		</p>
		<p><?php esc_html_e( 'I’m not a bike mechanic, but I am a tinker. I learned to wrench by volunteering at a local shop fixing bikes to be donated to kids in need. So, although I’ve got no certifications, I do know how to true wheels, rebuild drive trains, adjust derailleurs and replace cables. And I love it. Nothing makes me happier than rebuilding some old, abandoned bike I find, and I’m proud to say that all my bikes make absolutely no noise when I’m riding. I just think a bike is simultaneously one of the simplest and most complicated machines. They’re fun to work on, and building a collection of bike mechanic tools is addictive.', 'bikepress' ); ?></p>
		<p><?php esc_html_e( 'Over the years, I’ve kept a journal for each of my bikes. It’s nice having all the specs at my fingertips when I’m making repairs and really helpful knowing how many miles it’s been since my last new chain. As a software developer, I wanted to learn how to develop a WordPress plugin, so the idea of making my bikes journals a plugin just seemed natural. I took an online class on WordPress development and got as far as creating a plugin that could be installed, activated and removed and created a widget that allowed an interactive display of a collection of bikes and their specs and maintenance records. I started creating the admin pages, but it was slow going as I was learning and constantly refactoring.', 'bikepress' ); ?></p>
		<p><?php esc_html_e( 'Enter AI. When it came on the scene, I knew I needed to learn how development could be improved using AI and the pitfalls of using it. I reattacked my plugin with a plan to use AI to finish the admin screens, add some new features and improve my existing code.', 'bikepress' ); ?></p>
		<p><?php esc_html_e( 'The result is BikePress. If you are like me (obsessed with riding, building, maintaining and collecting bikes), maybe you’ll enjoy using this plugin for tinkers. I’d love to know what you think or hear how you’re using it.', 'bikepress' ); ?></p>
		<p>
			<a class="button button-primary" href="https://buymeacoffee.com/offthekitchen" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Buy Me a Coffee', 'bikepress' ); ?></a>
		</p>
	</div>

	<?php include plugin_dir_path( __FILE__ ) . 'bike-admin-footer.php'; ?>
</div>
