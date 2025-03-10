<?php
/**
 * Bike Admin Page
 */


global $wpdb;

$aStatuses = $wpdb->get_results("SELECT * FROM " . STATUS_TABLE);

?>
<div class="main-container">
    <header>
        <h1>MANAGE DATA</h1>
    </header>
    <main class="main-content">
<?php 
    foreach ($aStatuses as $oStatus) {
        echo "<div>$oStatus->bike_status</div>";
    }
?>
    </main>
</div>