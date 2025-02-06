<?php

/**
 * Fired during plugin activation
 *
 * @link       http://www.offthekitchen.com
 * @since      1.0.0
 *
 * @package    Steves_Bike_Maintenance
 * @subpackage Steves_Bike_Maintenance/includes
 */

/**
 * This class conmtrols the injection of test data
 *
 * @since      1.0.0
 * @package    Steves_Bike_Maintenance
 * @subpackage Steves_Bike_Maintenance/includes
 * @author     John S Weeks <steve@offthekitchen.com>
 */
class Test_Data
{

	/**
	 * Test Data
	 *
	 * This function establishes all the necessary setup during the activation of the plugin.
	 *
	 * @since    1.0.0
	 */
	public static function insert_test_data()
	{

		global $wpdb;

		$bikes_table_name = $wpdb->prefix . "bikes";
		$maintenance_table_name = $wpdb->prefix . "bike_maintenance"; 
		$specs_table_name = $wpdb->prefix . "bike_specs"; 
		$status_table_name = $wpdb->prefix . "bike_status"; 

		$status_active = 1;
		$status_retired = 2;
		$status_building = 3;

		// PROD
/* 		$bike1_image_id = 17200;
		$bike2_image_id = 17224;
		$bike3_image_id = 17221;
		$bike4_image_id = 17223; */

		// LOCAL
		$bike1_image_id = 97;
		$bike2_image_id = 180;
		$bike3_image_id = 179;
		$bike4_image_id = 84;

		$charset_collate = $wpdb->get_charset_collate();

		$bike_name = 'Ye Olde Townie';
		$bike_desc = 'My old faithful bike. This guy took me on many adventures.';
		$bike_make = 'Specialized';
		$bike_model = 'Rockhopper';

		$wpdb->insert(
			$bikes_table_name,
			array(
				'last_update' => current_time('mysql'),
				'bike_image_id' => $bike1_image_id,
				'bike_name' => $bike_name,
				'bike_desc' => $bike_desc,
				'bike_make' => $bike_make,
				'bike_model' => $bike_model,
				'bike_status_id' => $status_retired,
			)
		);

		$bike_name = 'Ironhorse';
		$bike_desc = 'Found it on the side of the road. It\'s gonna be back on the trails someday.';
		$bike_make = 'Ironhorse';
		$bike_model = 'Outlaw';

		$wpdb->insert(
			$bikes_table_name,
			array(
				'last_update' => current_time('mysql'),
				'bike_image_id' => $bike3_image_id,
				'bike_name' => $bike_name,
				'bike_desc' => $bike_desc,
				'bike_make' => $bike_make,
				'bike_model' => $bike_model,
				'bike_status_id' => $status_building,
			)
		);

		$bike_name = 'In a Trance';
		$bike_desc = 'My new mountain bike. Bought it during COVID.  It was the only one available and took some getting used to.';
		$bike_make = 'Giant';
		$bike_model = 'Trance';

		$wpdb->insert(
			$bikes_table_name,
			array(
				'last_update' => current_time('mysql'),
				'bike_image_id' => $bike2_image_id,
				'bike_name' => $bike_name,
				'bike_desc' => $bike_desc,
				'bike_make' => $bike_make,
				'bike_model' => $bike_model,
				'bike_status_id' => $status_active,
			)
		);
		
		$bike_name = 'Ye New Olde Townie';
		$bike_desc = 'A 15-year-old Rockhopper to replace my 30-year-old Rockhopper';
		$bike_make = 'Specialized';
		$bike_model = 'Rockhopper';

		$wpdb->insert(
			$bikes_table_name,
			array(
				'last_update' => current_time('mysql'),
				// 'bike_image_id' => $bike4_image_id,
				'bike_name' => $bike_name,
				'bike_desc' => $bike_desc,
				'bike_make' => $bike_make,
				'bike_model' => $bike_model,
				'bike_status_id' => $status_active,
			)
		);

        // INSERT MAINTENANCE RECORDS
		$bike1_id = 1;
		$bike2_id = 3;
		$bike3_id = 4;
		$maintenance_desc = 'Purchased';
		$bike_miles = 0;

		$wpdb->insert(
			$maintenance_table_name,
			array(
				'last_update' => current_time('mysql'),
				'maintenance_date' => date('Y-m-d', strtotime('2010-06-14')),
				'bike_id' => $bike1_id,
				'maintenance_desc' => $maintenance_desc,
				'bike_miles' => $bike_miles,
			)
		);

        $maintenance_desc = 'Rebuilt Drivetrain';
		$bike_miles = 500;

		$wpdb->insert(
			$maintenance_table_name,
			array(
				'last_update' => current_time('mysql'),
				'maintenance_date' => date('Y-m-d', strtotime('2015-03-20')),
				'bike_id' => $bike1_id,
				'maintenance_desc' => $maintenance_desc,
				'bike_miles' => $bike_miles,
			)
		);

		$maintenance_desc = 'Replaced brakes pads';
		$bike_miles = 500;

		$wpdb->insert(
			$maintenance_table_name,
			array(
				'last_update' => current_time('mysql'),
				'maintenance_date' => date('Y-m-d', strtotime('2017-11-06')),
				'bike_id' => $bike1_id,
				'maintenance_desc' => $maintenance_desc,
				'bike_miles' => $bike_miles,
			)
		);

        $maintenance_desc = 'Replaced wheels';
		$bike_miles = 1510;

		$wpdb->insert(
			$maintenance_table_name,
			array(
				'last_update' => current_time('mysql'),
				'maintenance_date' => date('Y-m-d', strtotime('2022-09-16')),
				'bike_id' => $bike1_id,
				'maintenance_desc' => $maintenance_desc,
				'bike_miles' => $bike_miles,
			)
		);

		$maintenance_desc = 'Purchased';
		$bike_miles = 0;

		$wpdb->insert(
			$maintenance_table_name,
			array(
				'last_update' => current_time('mysql'),
				'maintenance_date' => date('Y-m-d', strtotime('2023-06-24')),
				'bike_id' => $bike2_id,
				'maintenance_desc' => $maintenance_desc,
				'bike_miles' => $bike_miles,
			)
		);

		$maintenance_desc = 'Replaced discs and seat';
		$bike_miles = 206;

		$wpdb->insert(
			$maintenance_table_name,
			array(
				'last_update' => current_time('mysql'),
				'maintenance_date' => date('Y-m-d', strtotime('2024-04-09')),
				'bike_id' => $bike2_id,
				'maintenance_desc' => $maintenance_desc,
				'bike_miles' => $bike_miles,
			)
		);

		$maintenance_desc = 'Purchased';
		$bike_miles = 0;

		$wpdb->insert(
			$maintenance_table_name,
			array(
				'last_update' => current_time('mysql'),
				'maintenance_date' => date('Y-m-d', strtotime('2024-01-01')),
				'bike_id' => $bike3_id,
				'maintenance_desc' => $maintenance_desc,
				'bike_miles' => $bike_miles,
			)
		);

		 // INSERT SPECS RECORDS
		 $bike_id = 1;
		 $specs_name = 'Wheel size';
		 $specs_desc = '26 inch';
 
		 $wpdb->insert(
			 $specs_table_name,
			 array(
				 'last_update' => current_time('mysql'),
				 'bike_id' => $bike_id,
				 'spec_name' => $specs_name,
				 'spec_desc' => $specs_desc,
			 )
		 );

		 $bike_id = 1;
		 $specs_name = 'Frame size';
		 $specs_desc = '21 inch';
 
		 $wpdb->insert(
			 $specs_table_name,
			 array(
				 'last_update' => current_time('mysql'),
				 'bike_id' => $bike_id,
				 'spec_name' => $specs_name,
				 'spec_desc' => $specs_desc,
			 )
		 );

		 $bike_id = 2;
		 $specs_name = 'Frame size';
		 $specs_desc = '21.5 inch';
 
		 $wpdb->insert(
			 $specs_table_name,
			 array(
				 'last_update' => current_time('mysql'),
				 'bike_id' => $bike_id,
				 'spec_name' => $specs_name,
				 'spec_desc' => $specs_desc,
			 )
		 );

		 $bike_id = 3;
		 $specs_name = 'Frame size';
		 $specs_desc = 'Large';
 
		 $wpdb->insert(
			 $specs_table_name,
			 array(
				 'last_update' => current_time('mysql'),
				 'bike_id' => $bike_id,
				 'spec_name' => $specs_name,
				 'spec_desc' => $specs_desc,
			 )
		 );

		 $bike_id = 3;
		 $specs_name = 'Wheel size';
		 $specs_desc = '29 inch';
 
		 $wpdb->insert(
			 $specs_table_name,
			 array(
				 'last_update' => current_time('mysql'),
				 'bike_id' => $bike_id,
				 'spec_name' => $specs_name,
				 'spec_desc' => $specs_desc,
			 )
		 );

		 $bike_id = 3;
		 $specs_name = 'Brake type';
		 $specs_desc = 'disc brakes';
 
		 $wpdb->insert(
			 $specs_table_name,
			 array(
				 'last_update' => current_time('mysql'),
				 'bike_id' => $bike_id,
				 'spec_name' => $specs_name,
				 'spec_desc' => $specs_desc,
			 )
		 );

		 $bike_id = 4;
		 $specs_name = 'Wheel size';
		 $specs_desc = '26 inch';
 
		 $wpdb->insert(
			 $specs_table_name,
			 array(
				 'last_update' => current_time('mysql'),
				 'bike_id' => $bike_id,
				 'spec_name' => $specs_name,
				 'spec_desc' => $specs_desc,
			 )
		 );

		 $bike_id = 4;
		 $specs_name = 'Frame size';
		 $specs_desc = '19.5 inch';
 
		 $wpdb->insert(
			 $specs_table_name,
			 array(
				 'last_update' => current_time('mysql'),
				 'bike_id' => $bike_id,
				 'spec_name' => $specs_name,
				 'spec_desc' => $specs_desc,
			 )
		 );
		 $bike_id = 4;
		 $specs_name = 'Brake type';
		 $specs_desc = 'rim brakes';
 
		 $wpdb->insert(
			 $specs_table_name,
			 array(
				 'last_update' => current_time('mysql'),
				 'bike_id' => $bike_id,
				 'spec_name' => $specs_name,
				 'spec_desc' => $specs_desc,
			 )
		 );

		// INSERT STATUS RECORDS
		$wpdb->insert(
			$status_table_name,
			array(
				'last_update' => current_time('mysql'),
				'bike_status' => 'ACTIVE',
			)
		);

		$wpdb->insert(
			$status_table_name,
			array(
				'last_update' => current_time('mysql'),
				'bike_status' => 'RETIRED',
			)
		);

		
		$wpdb->insert(
			$status_table_name,
			array(
				'last_update' => current_time('mysql'),
				'bike_status' => 'BUILDING',
			)
		);
	}
	

}
