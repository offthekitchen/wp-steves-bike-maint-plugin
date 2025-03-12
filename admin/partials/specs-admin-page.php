<?php
/**
 * Specs Maintenance Page
 */
global $wpdb;

$aSpecs = $wpdb->get_results("SELECT * FROM " . SPECS_TABLE);

?>
<div class="main-container">
<?php include(plugin_dir_path(__FILE__) . 'bike-admin-header.php'); ?>
    <h1>MANAGE SPECS</h1>
    <main class="main-content">
        <?php
        foreach ($aSpecs as $spec) {
            echo "<div>$spec->spec_name</div>";
        }
        ?>
    </main>
    <?php include(plugin_dir_path(__FILE__) . 'bike-admin-footer.php'); ?>
</div>
