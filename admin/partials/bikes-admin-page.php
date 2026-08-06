<?php
/**
 * Bike Maintenance Page
 */

global $wpdb;

$bike_plugin_url = WP_PLUGIN_DIR . '/wp-bikepress';

// Data
$mode = 'new';
$bikeId = 0;
$bikeName = '';
$bikeMake = '';
$bikeModel = '';
$serialNumber = '';
$bikeStatusId = '';
$bikeImageId = 0;
$bikeDescription = '';

if (isset($_REQUEST['bike_id']) && $_REQUEST['bike_id'] != "") {
    $mode = 'editting';
    $bikeId = $_REQUEST['bike_id'];
    $thisBike = $wpdb->get_results("SELECT * FROM " . BIKES_TABLE . " WHERE id = " . $bikeId);
}
    $bikeName = $_POST['bike-name'];
    $bikeMake = $_POST['bike-make'];
// --- ADDING OR UPDATING ---
if (isset($_POST["update"]) || isset($_POST["add"])) {
    echo "<h1>UPDATE OR add</h1>";
    $mode =- 'editting';
    $bikeId = $_POST['bike-id'];
    $bikeName = $_POST['bike-name'];
    $bikeMake = $_POST['bike-make'];
    $bikeModel = $_POST['bike-model'];
    $serialNumber = $_POST['serial-number'];
    $bikeStatusId = $_POST['bike-status'];
    $bikeImageId = $_POST['bike-image-id'];
    $bikeDescription = $_POST['bike-desc'];
}

// UPDATE BIKE
if (isset($_POST['update'])) {
    echo "<h1>UPDATE/h1>";
    $data_to_update = array(
    'bike_name' => $bikeName,
    'bike_make' => $bikeMake,
    'bike_model' => $bikeModel,
    'serial_number' => $serialNumber,
    'bike_status' => $bikeStatus,
    'bike_image_id' => $bikeImageId,
    'bike_desc' => $bikeDescription
    );

    $where_condition = array(
    'ID' => $bikeId
);

$data_formats = array(
    '%s', // Format for 'post_title'
    '%s'  // Format for 'post_content'
);

$where_formats = array(
    '%d'  // Format for 'ID'
);

$updated = $wpdb->update(
    BIKES_TABLE,
    $data_to_update,
    $where_condition,
    $data_formats,
    $where_formats
);

if ( false === $updated ) {
    // Handle error
    echo "An error occurred during the update.";
} else {
    // Success, potentially 0 rows updated if values were the same
    echo "Rows updated: " . $updated;
}

}

$aBikes = $wpdb->get_results("SELECT * FROM " . BIKES_TABLE);
$aStatuses = $wpdb->get_results("SELECT * FROM " . STATUS_TABLE);

// Should really check if ercord was found 
if ($bikeId && $bikeId > 0) {
    $bikeName = $thisBike[0]->bike_name;
    $bikeMake = $thisBike[0]->bike_make;
    $bikeModel = $thisBike[0]->bike_model;
    $serialNumber = $thisBike[0]->serial_number;
    $bikeStatus = $thisBike[0]->bike_status;
    $bikeImageId = $thisBike[0]->bike_image_id;
    $bikeDescription = $thisBike[0]->bike_desc;
    $image_attributes = wp_get_attachment_image_src($bikeImageId);
    $imageTag = '';
    if ($image_attributes) {
        $imageTag = "<img id=\"bike-image\" src=\"{$image_attributes[0]}\" width=\"{$image_attributes[1]}\" height=\"{$image_attributes[2]}\" class=\"bike-image\" />";
    } else {
        $imageTag = "<img id=\"bike-image\" src=\"{$bike_plugin_url}img/default-bike.jpg\" class=\"bike-image\" />";
    }
}


?>
<div class="main-container">
    <?php include(plugin_dir_path(__FILE__) . 'bike-admin-header.php'); ?>
    <h1>MANAGE BIKES</h1>
    <main class="main-content">
        <form id="bikepress-bike-form" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="post">
            <!-- Hidden field required to process post-->
            <input type="hidden" name="action" value="bike_admin_form_submit">
            <?php wp_nonce_field( 'bike_admin_form_nonce_action', 'bike_admin_form_nonce_field' ); ?>
            
            <input type=" hidden" id="bike-id" name="bike-id" value="<?php echo $bikeId ?>">
            <input type="hidden" id="bike-image-id" name="bike-image-id" value="<?php echo $bikeImageId ?>">
            <table class="form-table gp-table">
                <tbody>
                    <tr>
                        <th scope="row">
                            <label for="bike_name">BIKE NAME:</label>
                        </th>
                        <td>
                            <input id="bike-name" name="bike-name" type="text"
                                value="<?php echo $bikeName; ?>" size="48">
                        </td>
                        <th scope="row">
                            <label for="bike-desc">DESCRIPTION:</label>
                        </th>
                        <td rowspan="3">
                            <input id="bike-desc" name="bike-desc" type="textarea"
                                value="<?php echo $bikeDescription; ?>" rows="8" cols="50">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="bike_make">BIKE MAKE:</label>
                        </th>
                        <td>
                            <input id="bike-make" name="bike-make" type="text"
                                value="<?php echo $bikeMake; ?>" size="48">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="bike_model">BIKE MODEL:</label>
                        </th>
                        <td>
                            <input id="bike-model" name="bike-model" type="text"
                                value="<?php echo $bikeModel; ?>" size="48">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="serial-number">SERIAL NUMBER:</label>
                        </th>
                        <td>
                            <input id="serial-number" name="serial-number" type="text"
                                value="<?php echo $serialNumber; ?>" size="48">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="bike_status">BIKE STATUS:</label>
                        </th>
                        <td>
                            <select id="bike-status" name="bike-status" type="text" value="">
                                <?php
                                foreach ($aStatuses as $status) {
                                    echo "<option name=\"$status->bike_status\"";
                                    if ($bikeStatusId == $status->id) {
                                        echo " selected ";
                                    }
                                    echo ">$status->bike_status</option>";
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="bike_status">IMAGE:</label>
                        </th>
                        <td>
                            <div id="bike_image_container">
                                <?php echo $imageTag; ?>
                            </div>
                            <button id="select_image_button" class="button button-primary">Select Image</button>
                            <div>
                                <a href="javascript:deleteImage();">Delete</a>
                            </div>
                            <input type="hidden" id="bike-image-id"
                                name="bike-image-id" value="0">
                        </td>
                    </tr>
                    <tr>
                        <td scope="row">
                            &nbsp;
                        </td>
                        <td>
                            <?php
                            if ($mode != 'new') {
                                ?>
                                <button id="update-bike" name="update" type="submit" onclick="submitForm()">Update
                                    Bike</button>
                            <?php
                            }
                            ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
        <table id="bike-list" class="wp-list-table widefat">
            <thead>
                <tr id="bike-list-header">
                    <th scope="col" class="manage-column">Bike Name</th>
                    <th scope="col" class="manage-column">Make</th>
                    <th scope="col" class="manage-column">Model</th>
                    <th scope="col" class="manage-column">Actions</th>
                </tr>
            </thead>

            <?php
            $bAlternateRow = false;
            foreach ($aBikes as $bike) {
                echo "<tr id=\"bike_$bike->id\">";
                echo "<td>$bike->bike_name</td>";
                echo "<td>$bike->bike_make</td>";
                echo "<td>$bike->bike_model</td>";
                //TOFO:  THE URL NEEDS TO BE A CONSTANT
                echo "<td><a href=\"http://127.0.0.1/wp-sandbox/wp-admin/admin.php?page=bikes-admin&sbmaction=edit&bike_id=$bike->id\">EDIT</a> / <a href=\"\">DELETE</a></td>";
                echo "</tr>";
            }
            ?>
        </table>
    </main>
    <?php include(plugin_dir_path(__FILE__) . 'bike-admin-footer.php'); ?>
</div>
<style>
    #bike-list tr:nth-child(odd) {
        background-color: #f6f6f6;
    }

    #bike-list #bike-list-header {
        background-color: lightsteelblue;
    }

    #submit-bike {
        padding 10px;
        border-radius: 8px;
        background-color: lightsteelblue;
        font-size: 16px;
        color: white;
        border: none;
        padding: 12px;
    }

    #bike-desc {
        width: 300px;
        height: 150px;
        padding: 12px;
    }
</style>
<script>
    // When the delete image link is chosen replace the image wuith the default
    function deleteImage() {
        // Remove Bike Image Id
        const bikeImageIdElement = document.getElementById('bike-image-id');
        bikeImageIdElement.value = '';
        // Replace Bike Image with default-bike
        const bikeImage = document.getElementById('bike-image');
        // TODO Better path to image
        bikeImage.src = '../wp-content/plugins/wp-bikepress/img/default-bike.jpg';
        return true;
    }

    function validateForm(form) {
        const name = form.bike - name.value;
        const desc = form.bike - name.value;
        let errorMessage = ''
        var isValid = true
        //const email = form.email.value;
        //const emailRegex = /^[\w-]+(\.[\w-]+)*@([\w-]+\.)+[a-zA-Z]{2,7}$/;
        console.log('Bike Name: ' + name)
        if (!name) {
            console.log('NO NAME')
            errorMessage += 'Bike Name is required\n'
            isValid = false
        }
        if (!desc) {
            errorMessage += 'Bike Description is required\n'
            isValid = false
        }

        if (!isValid)
            alert(errorMessage)

        return isValid;
    }

    // Validate the form befor esubmitting
    function submitForm() {
        console.log('Validating form!')

        const form = document.getElementById('bikepress-bike-form');
        form.addEventListener('submit', (event) => {
            event.preventDefault();
            if (!validateForm(form)) {
                console.log('NOT VALID')
                return;
            }
        });
        console.log('VALID')

        form.submit();

    }

</script>