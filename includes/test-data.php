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

		$status_active = 1;
		$status_retired = 2;
		$status_building = 3;

		$yot_bike1_id = 1;
		$ih_bike_id = 2;
		$trance_bike_id = 3;
		$ynot_bike_id = 4;
		$sf_bike_id = 5;

		// LOCAL
		$bike1_image_id = 97;
		$bike2_image_id = 180;
		$bike3_image_id = 179;
		$bike4_image_id = 309;
		$bike5_image_id = 309;

		// PROD
/* 		$bike1_image_id = 17200;
		$bike2_image_id = 17224;
		$bike3_image_id = 17221;
		$bike4_image_id = 17332;
		$bike5_image_id = 17223; */

		$charset_collate = $wpdb->get_charset_collate();

		$bike_name = 'Ye Olde Townie';
		$bike_desc = 'My old faithful bike. This guy took me on many adventures.';
		$bike_make = 'Specialized';
		$bike_model = 'Rockhopper';

		$wpdb->insert(
			BIKES_TABLE,
			array(
				'last_update' => current_time('mysql'),
				'bike_image_id' => $bike1_image_id,
				'bike_name' => $bike_name,
				'bike_desc' => $bike_desc,
				'bike_make' => $bike_make,
				'bike_model' => $bike_model,
				'purchase_date' => date('Y-m-d', strtotime('1995-06-14')),
				'serial_number' => 'M5GI59652',
				'bike_status_id' => $status_retired,
			)
		);

		$bike_name = 'Ironhorse';
		$bike_desc = 'Found it on the side of the road. It\'s gonna be back on the trails someday.';
		$bike_make = 'Ironhorse';
		$bike_model = 'Outlaw';

		$wpdb->insert(
			BIKES_TABLE,
			array(
				'last_update' => current_time('mysql'),
				'bike_image_id' => $bike3_image_id,
				'bike_name' => $bike_name,
				'bike_desc' => $bike_desc,
				'bike_make' => $bike_make,
				'bike_model' => $bike_model,
				'purchase_date' => date('Y-m-d', strtotime('2025-02-14')),
				'serial_number' => '0300270',
				'bike_status_id' => $status_building,
			)
		);

		$bike_name = 'In a Trance';
		$bike_desc = 'My new mountain bike. Bought it during COVID.  It was the only one available and took some getting used to.';
		$bike_make = 'Giant';
		$bike_model = 'Trance';

		$wpdb->insert(
			BIKES_TABLE,
			array(
				'last_update' => current_time('mysql'),
				'bike_image_id' => $bike2_image_id,
				'bike_name' => $bike_name,
				'bike_desc' => $bike_desc,
				'bike_make' => $bike_make,
				'bike_model' => $bike_model,
				'purchase_date' => date('Y-m-d', strtotime('2020-10-04')),
				'serial_number' => 'G7EF06693',
				'bike_status_id' => $status_active,
			)
		);
		
		$bike_name = 'Ye New Olde Townie';
		$bike_desc = 'A 15-year-old Rockhopper to replace my 30-year-old Rockhopper';
		$bike_make = 'Specialized';
		$bike_model = 'Rockhopper';

		$wpdb->insert(
			BIKES_TABLE,
			array(
				'last_update' => current_time('mysql'),
				'bike_image_id' => $bike4_image_id,
				'bike_name' => $bike_name,
				'bike_desc' => $bike_desc,
				'bike_make' => $bike_make,
				'bike_model' => $bike_model,
				'purchase_date' => date('Y-m-d', strtotime('2024-05-10')),
				'serial_number' => 'P7HAS1007',
				'bike_status_id' => $status_active,
			)
		);

		$bike_name = 'Super Fly';
		$bike_desc = 'My first full-suspension mountain bike.  I bent the frame in a crash and had to retire it.';
		$bike_make = 'Trek';
		$bike_model = 'Super Fly';

		$wpdb->insert(
			BIKES_TABLE,
			array(
				'last_update' => current_time('mysql'),
				//'bike_image_id' => $bike5_image_id,
				'bike_name' => $bike_name,
				'bike_desc' => $bike_desc,
				'bike_make' => $bike_make,
				'bike_model' => $bike_model,
				'purchase_date' => date('Y-m-d', strtotime('2013-08-30')),
				'serial_number' => 'WTU024G5132H',
				'bike_status_id' => $status_retired,
			)
		);

        // INSERT MAINTENANCE RECORDS - Ye Olde Townie	

        $maintenance_desc = 'Completely Rebuilt Drivetrain from 3x7 to 1x10';
		$bike_miles = 1000;

		$wpdb->insert(
			MAINTENANCE_TABLE,
			array(
				'last_update' => current_time('mysql'),
				'maintenance_date' => date('Y-m-d', strtotime('2018-09-30')),
				'bike_id' => $yot_bike1_id,
				'maintenance_desc' => $maintenance_desc,
				'bike_miles' => $bike_miles,
			)
		);

		$maintenance_desc = 'Replaced chain, brake cables and brake pads';
		$bike_miles = 2000;

		$wpdb->insert(
			MAINTENANCE_TABLE,
			array(
				'last_update' => current_time('mysql'),
				'maintenance_date' => date('Y-m-d', strtotime('2021-01-25')),
				'bike_id' => $yot_bike1_id,
				'maintenance_desc' => $maintenance_desc,
				'bike_miles' => $bike_miles,
			)
		);

		$maintenance_desc = 'Trued back wheel';
		$bike_miles = 2000;

		$wpdb->insert(
			MAINTENANCE_TABLE,
			array(
				'last_update' => current_time('mysql'),
				'maintenance_date' => date('Y-m-d', strtotime('2021-01-25')),
				'bike_id' => $yot_bike1_id,
				'maintenance_desc' => $maintenance_desc,
				'bike_miles' => $bike_miles,
			)
		);

		$maintenance_desc = 'Replaced shifter cables and serviced bottom bracket';
		$bike_miles = 3000;

		$wpdb->insert(
			MAINTENANCE_TABLE,
			array(
				'last_update' => current_time('mysql'),
				'maintenance_date' => date('Y-m-d', strtotime('2021-03-17')),
				'bike_id' => $yot_bike1_id,
				'maintenance_desc' => $maintenance_desc,
				'bike_miles' => $bike_miles,
			)
		);

		$maintenance_desc = 'Replaced bottom bracket and chain ring';
		$bike_miles = 4000;

		$wpdb->insert(
			MAINTENANCE_TABLE,
			array(
				'last_update' => current_time('mysql'),
				'maintenance_date' => date('Y-m-d', strtotime('2021-08-01')),
				'bike_id' => $yot_bike1_id,
				'maintenance_desc' => $maintenance_desc,
				'bike_miles' => $bike_miles,
			)
		);

		$maintenance_desc = 'Replaced derailleur hanger and cassette';
		$bike_miles = 4500;

		$wpdb->insert(
			MAINTENANCE_TABLE,
			array(
				'last_update' => current_time('mysql'),
				'maintenance_date' => date('Y-m-d', strtotime('2021-08-14')),
				'bike_id' => $yot_bike1_id,
				'maintenance_desc' => $maintenance_desc,
				'bike_miles' => $bike_miles,
			)
		);

		$maintenance_desc = 'Replaced chain';
		$bike_miles = 7602;

		$wpdb->insert(
			MAINTENANCE_TABLE,
			array(
				'last_update' => current_time('mysql'),
				'maintenance_date' => date('Y-m-d', strtotime('2022-10-26')),
				'bike_id' => $yot_bike1_id,
				'maintenance_desc' => $maintenance_desc,
				'bike_miles' => $bike_miles,
			)
		);

		$maintenance_desc = 'Replaced shifter cable';
		$bike_miles = 8011;

		$wpdb->insert(
			MAINTENANCE_TABLE,
			array(
				'last_update' => current_time('mysql'),
				'maintenance_date' => date('Y-m-d', strtotime('2023-12-13')),
				'bike_id' => $yot_bike1_id,
				'maintenance_desc' => $maintenance_desc,
				'bike_miles' => $bike_miles,
			)
		);
		
		$maintenance_desc = 'Replaced chain - SRAM 1071 10-speed';
		$bike_miles = 8134;

		$wpdb->insert(
			MAINTENANCE_TABLE,
			array(
				'last_update' => current_time('mysql'),
				'maintenance_date' => date('Y-m-d', strtotime('2023-12-16')),
				'bike_id' => $yot_bike1_id,
				'maintenance_desc' => $maintenance_desc,
				'bike_miles' => $bike_miles,
			)
		);

		$maintenance_desc = 'Final ride - irrepairable crack in headset';
		$bike_miles = 9192;

		$wpdb->insert(
			MAINTENANCE_TABLE,
			array(
				'last_update' => current_time('mysql'),
				'maintenance_date' => date('Y-m-d', strtotime('2025-02-08')),
				'bike_id' => $yot_bike1_id,
				'maintenance_desc' => $maintenance_desc,
				'bike_miles' => $bike_miles,
			)
		);

		// INSERT MAINTENANCE RECORDS - Ironhorse
		$maintenance_desc = 'Removed all old parts and cleaned frame';
		$bike_miles = 0;

		$wpdb->insert(
			MAINTENANCE_TABLE,
			array(
				'last_update' => current_time('mysql'),
				'maintenance_date' => date('Y-m-d', strtotime('2024-04-09')),
				'bike_id' => $ih_bike_id,
				'maintenance_desc' => $maintenance_desc,
				'bike_miles' => $bike_miles,
			)
		);

		// INSERT MAINTENANCE RECORDS - In a Trance
		$maintenance_desc = 'Replaced front wheel after being run over';
		$bike_miles = 500;

		$wpdb->insert(
			MAINTENANCE_TABLE,
			array(
				'last_update' => current_time('mysql'),
				'maintenance_date' => date('Y-m-d', strtotime('2020-12-25')),
				'bike_id' => $trance_bike_id,
				'maintenance_desc' => $maintenance_desc,
				'bike_miles' => $bike_miles,
			)
		);

		$maintenance_desc = 'Replaced rear wheel';
		$bike_miles = 700;

		$wpdb->insert(
			MAINTENANCE_TABLE,
			array(
				'last_update' => current_time('mysql'),
				'maintenance_date' => date('Y-m-d', strtotime('2021-04-24')),
				'bike_id' => $trance_bike_id,
				'maintenance_desc' => $maintenance_desc,
				'bike_miles' => $bike_miles,
			)
		);

		$maintenance_desc = 'Replaced brake pads and chain';
		$bike_miles = 1000;

		$wpdb->insert(
			MAINTENANCE_TABLE,
			array(
				'last_update' => current_time('mysql'),
				'maintenance_date' => date('Y-m-d', strtotime('2021-07-20')),
				'bike_id' => $trance_bike_id,
				'maintenance_desc' => $maintenance_desc,
				'bike_miles' => $bike_miles,
			)
		);

		$maintenance_desc = 'Replaced rear wheel (stripped freehub), brake pads and rotor';
		$bike_miles = 1500;

		$wpdb->insert(
			MAINTENANCE_TABLE,
			array(
				'last_update' => current_time('mysql'),
				'maintenance_date' => date('Y-m-d', strtotime('2022-07-14')),
				'bike_id' => $trance_bike_id,
				'maintenance_desc' => $maintenance_desc,
				'bike_miles' => $bike_miles,
			)
		);

		$maintenance_desc = 'Replaced chain';
		$bike_miles = 1500;

		$wpdb->insert(
			MAINTENANCE_TABLE,
			array(
				'last_update' => current_time('mysql'),
				'maintenance_date' => date('Y-m-d', strtotime('2022-12-19')),
				'bike_id' => $trance_bike_id,
				'maintenance_desc' => $maintenance_desc,
				'bike_miles' => $bike_miles,
			)
		);
		
		$maintenance_desc = 'Replaced shifter cable housing';
		$bike_miles = 3000;

		$wpdb->insert(
			MAINTENANCE_TABLE,
			array(
				'last_update' => current_time('mysql'),
				'maintenance_date' => date('Y-m-d', strtotime('2023-06-09')),
				'bike_id' => $trance_bike_id,
				'maintenance_desc' => $maintenance_desc,
				'bike_miles' => $bike_miles,
			)
		);

		$maintenance_desc = 'Replaced brake pads';
		$bike_miles = 3992;

		$wpdb->insert(
			MAINTENANCE_TABLE,
			array(
				'last_update' => current_time('mysql'),
				'maintenance_date' => date('Y-m-d', strtotime('2023-10-21')),
				'bike_id' => $trance_bike_id,
				'maintenance_desc' => $maintenance_desc,
				'bike_miles' => $bike_miles,
			)
		);

		$maintenance_desc = 'Replaced chain - SRAM GX Eagle 12-speed';
		$bike_miles = 5734;

		$wpdb->insert(
			MAINTENANCE_TABLE,
			array(
				'last_update' => current_time('mysql'),
				'maintenance_date' => date('Y-m-d', strtotime('2023-10-21')),
				'bike_id' => $trance_bike_id,
				'maintenance_desc' => $maintenance_desc,
				'bike_miles' => $bike_miles,
			)
		);

		// INSERT MAINTENANCE RECORDS - Ye New Olde Townie
		$maintenance_desc = 'Replaced front and rear tires and chain';
		$bike_miles = 1235;

		$wpdb->insert(
			MAINTENANCE_TABLE,
			array(
				'last_update' => current_time('mysql'),
				'maintenance_date' => date('Y-m-d', strtotime('2024-10-26')),
				'bike_id' => $ynot_bike_id,
				'maintenance_desc' => $maintenance_desc,
				'bike_miles' => $bike_miles,
			)
		);

		$maintenance_desc = 'Replaced broken spoke on rear wheel and trued wheel';
		$bike_miles = 2538;

		$wpdb->insert(
			MAINTENANCE_TABLE,
			array(
				'last_update' => current_time('mysql'),
				'maintenance_date' => date('Y-m-d', strtotime('2024-10-26')),
				'bike_id' => $ynot_bike_id,
				'maintenance_desc' => $maintenance_desc,
				'bike_miles' => $bike_miles,
			)
		);

		 // INSERT SPECS RECORDS - Ye Olde Townie
		 $bike_id = 1;

		 $specs_name = 'Wheel size';
		 $specs_desc = '26 inch';
 
		 $wpdb->insert(
			 SPECS_TABLE,
			 array(
				 'last_update' => current_time('mysql'),
				 'bike_id' => $bike_id,
				 'spec_name' => $specs_name,
				 'spec_desc' => $specs_desc,
			 )
		 );

		 $specs_name = 'Frame size';
		 $specs_desc = '21 inch';
 
		 $wpdb->insert(
			 SPECS_TABLE,
			 array(
				 'last_update' => current_time('mysql'),
				 'bike_id' => $bike_id,
				 'spec_name' => $specs_name,
				 'spec_desc' => $specs_desc,
			 )
		 );

		 $specs_name = 'Brake type';
		 $specs_desc = 'rim brakes';
 
		 $wpdb->insert(
			 SPECS_TABLE,
			 array(
				 'last_update' => current_time('mysql'),
				 'bike_id' => $bike_id,
				 'spec_name' => $specs_name,
				 'spec_desc' => $specs_desc,
			 )
		 );

		 $specs_name = 'Bottom Bracket Type';
		 $specs_desc = 'Thread Between';
 
		 $wpdb->insert(
			 SPECS_TABLE,
			 array(
				 'last_update' => current_time('mysql'),
				 'bike_id' => $bike_id,
				 'spec_name' => $specs_name,
				 'spec_desc' => $specs_desc,
			 )
		 );

		 $specs_name = 'Bottom Bracket Shell Width';
		 $specs_desc = '73mm';
 
		 $wpdb->insert(
			 SPECS_TABLE,
			 array(
				 'last_update' => current_time('mysql'),
				 'bike_id' => $bike_id,
				 'spec_name' => $specs_name,
				 'spec_desc' => $specs_desc,
			 )
		 );

		 // INSERT SPECS RECORDS - Ironhorse
		 $bike_id = 2;
		 $specs_name = 'Frame size';
		 $specs_desc = '21.5 inch';
 
		 $wpdb->insert(
			 SPECS_TABLE,
			 array(
				 'last_update' => current_time('mysql'),
				 'bike_id' => $bike_id,
				 'spec_name' => $specs_name,
				 'spec_desc' => $specs_desc,
			 )
		 );

		 $specs_name = 'Bottom Bracket Type';
		 $specs_desc = 'Thread Between';
 
		 $wpdb->insert(
			 SPECS_TABLE,
			 array(
				 'last_update' => current_time('mysql'),
				 'bike_id' => $bike_id,
				 'spec_name' => $specs_name,
				 'spec_desc' => $specs_desc,
			 )
		 );

		 $specs_name = 'Bottom Bracket Shell Width';
		 $specs_desc = '68mm';
 
		 $wpdb->insert(
			 SPECS_TABLE,
			 array(
				 'last_update' => current_time('mysql'),
				 'bike_id' => $bike_id,
				 'spec_name' => $specs_name,
				 'spec_desc' => $specs_desc,
			 )
		 );

		 // INSERT SPECS RECORDS - In a Trance
		 $bike_id = 3;

		 $specs_name = 'Frame size';
		 $specs_desc = 'Large';
 
		 $wpdb->insert(
			 SPECS_TABLE,
			 array(
				 'last_update' => current_time('mysql'),
				 'bike_id' => $bike_id,
				 'spec_name' => $specs_name,
				 'spec_desc' => $specs_desc,
			 )
		 );

		 $specs_name = 'Wheel size';
		 $specs_desc = '29 inch';
 
		 $wpdb->insert(
			 SPECS_TABLE,
			 array(
				 'last_update' => current_time('mysql'),
				 'bike_id' => $bike_id,
				 'spec_name' => $specs_name,
				 'spec_desc' => $specs_desc,
			 )
		 );

		 $specs_name = 'Brake type';
		 $specs_desc = 'disc brakes';
 
		 $wpdb->insert(
			 SPECS_TABLE,
			 array(
				 'last_update' => current_time('mysql'),
				 'bike_id' => $bike_id,
				 'spec_name' => $specs_name,
				 'spec_desc' => $specs_desc,
			 )
		 );

		 $specs_name = 'Brake pads';
		 $specs_desc = 'Shimano BP-M05-RX';
 
		 $wpdb->insert(
			 SPECS_TABLE,
			 array(
				 'last_update' => current_time('mysql'),
				 'bike_id' => $bike_id,
				 'spec_name' => $specs_name,
				 'spec_desc' => $specs_desc,
			 )
		 );

 		 // INSERT SPECS RECORDS - Ye New Olde Townie
		 $bike_id = 4;

		 $specs_name = 'Wheel size';
		 $specs_desc = '26 inch';
 
		 $wpdb->insert(
			 SPECS_TABLE,
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
			 SPECS_TABLE,
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
			 SPECS_TABLE,
			 array(
				 'last_update' => current_time('mysql'),
				 'bike_id' => $bike_id,
				 'spec_name' => $specs_name,
				 'spec_desc' => $specs_desc,
			 )
		 );

		// INSERT SPECS RECORDS - Superfly
		$specs_name = 'Wheel size';
		$specs_desc = '29 inch';

		$wpdb->insert(
			SPECS_TABLE,
			array(
				'last_update' => current_time('mysql'),
				'bike_id' => $sf_bike_id,
				'spec_name' => $specs_name,
				'spec_desc' => $specs_desc,
			)
		);

		$specs_name = 'Frame Size';
		$specs_desc = '19 inch - XL';

		$wpdb->insert(
			SPECS_TABLE,
			array(
				'last_update' => current_time('mysql'),
				'bike_id' => $sf_bike_id,
				'spec_name' => $specs_name,
				'spec_desc' => $specs_desc,
			)
		);

		$specs_name = 'Brake Type';
		$specs_desc = 'Shimano - Disc';

		$wpdb->insert(
			SPECS_TABLE,
			array(
				'last_update' => current_time('mysql'),
				'bike_id' => $sf_bike_id,
				'spec_name' => $specs_name,
				'spec_desc' => $specs_desc,
			)
		);

		// INSERT STATUS RECORDS
		$wpdb->insert(
			STATUS_TABLE,
			array(
				'last_update' => current_time('mysql'),
				'bike_status' => 'ACTIVE',
			)
		);

		$wpdb->insert(
			STATUS_TABLE,
			array(
				'last_update' => current_time('mysql'),
				'bike_status' => 'RETIRED',
			)
		);

		
		$wpdb->insert(
			STATUS_TABLE,
			array(
				'last_update' => current_time('mysql'),
				'bike_status' => 'BUILDING',
			)
		);
	}
	

}
