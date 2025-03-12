<?php
/**
 * Data Admin Page
 */


global $wpdb;

$aStatuses = $wpdb->get_results("SELECT * FROM " . STATUS_TABLE);

?>
<div class="main-container">
<?php include(plugin_dir_path(__FILE__) . 'bike-admin-header.php'); ?>
    <h1>MANAGE DATA</h1>
    <main class="main-content">
        <?php
        foreach ($aStatuses as $oStatus) {
            echo "<div>$oStatus->bike_status</div>";
        }
        ?>
    </main>
    <?php include(plugin_dir_path(__FILE__) . 'bike-admin-footer.php'); ?>
</div>
