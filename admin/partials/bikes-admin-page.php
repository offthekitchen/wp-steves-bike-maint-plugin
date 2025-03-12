<?php
/**
 * Specs Maintenance Page
 */
global $wpdb;

$aBikes = $wpdb->get_results("SELECT * FROM " . BIKES_TABLE);

?>
<div class="main-container">
<?php include(plugin_dir_path(__FILE__) . 'bike-admin-header.php'); ?>
    <h1>MANAGE BIKES</h1>
    <main class="main-content">
        <?php
        foreach ($aBikes as $bike) {
            echo "<div>$bike->bike_name</div>";
        }
        ?>
    </main>
    <?php include(plugin_dir_path(__FILE__) . 'bike-admin-footer.php'); ?>
</div>
