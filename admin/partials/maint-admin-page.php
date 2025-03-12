<?php
/**
 * Specs Maintenance Page
 */
global $wpdb;

$aMaintLogs = $wpdb->get_results("SELECT * FROM " . MAINTENANCE_TABLE);

?>
<div class="main-container">
<?php include(plugin_dir_path(__FILE__) . 'bike-admin-header.php'); ?>
    <h1>MANAGE MAINTENANCE LOGS</h1>
    <main class="main-content">
        <?php
        foreach ($aMaintLogs as $maintLog) {
            echo "<div>$maintLog->maintenance_desc</div>";
        }
        ?>
    </main>
    <?php include(plugin_dir_path(__FILE__) . 'bike-admin-footer.php'); ?>
</div>
