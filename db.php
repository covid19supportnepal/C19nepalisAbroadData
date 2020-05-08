<?php
defined( 'ABSPATH' ) or die( 'No direct access please!' );

global $na_covid19Nepal_db_version;
$na_covid19Nepal_db_version = '1.0';

function na_covid19Nepal_install() {
	global $wpdb;
	global $na_covid19Nepal_db_version;
	//BEGIN: TABLE CREATE FOR NEPALIS ABROAD
	$na_table_name = $wpdb->prefix . 'na_covid19Nepal';

	$charset_collate = $wpdb->get_charset_collate();

	$nepalisAbroadSql = "CREATE TABLE $na_table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		insertTime datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		groupDate int(11) NOT NULL,
		asOfTime datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		country varchar(20) NOT NULL,
		region varchar(20) NOT NULL,
		infected mediumint(9) NOT NULL,
		fatality mediumint(9) NOT NULL,
		recovered mediumint(9) NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	//dbDelta is loaded from upgrade.php
	//dbDelta will only apply the delta from the create table statement
	//so no need to check if table exists
	dbDelta( $nepalisAbroadSql );
	//END: TABLE CREATE FOR NEPALIS ABROAD

	//add option also checks if already in WP
	add_option( 'na_covid19Nepal_db_version', $na_covid19Nepal_db_version );

	//check if schedule is already in place
	if( !wp_next_scheduled( 'na_covid19Nepal_update_data' ) )
	{
		wp_schedule_event( time(), 'daily', 'na_covid19Nepal_update_data' );
	}
}

//registering the function name to be called from wp_schedule
add_action('na_covid19Nepal_update_data', 'na_covid19Nepal_install_data');

function na_covid19Nepal_install_data() {

	global $wpdb;

	//BEGIN: FETCH NEPALIS ABROAD
	$jsonNA = file_get_contents('https://www.covid19.nrna.org.np/wp-admin/admin-ajax.php?action=wp_ajax_ninja_tables_public_action&table_id=2655&target_action=get-all-data&default_sorting=old_first');
	$objNA = json_decode($jsonNA);

  $na_table_name = $wpdb->prefix . 'na_covid19Nepal';

  //open a file stream to write to
  $file = plugin_dir_path( __FILE__ ) . '/js/nepalisAbroad.js';
	$open = fopen( $file, "w" );
	$countriesArray='[';
	$total;
	$groupDate=time();
	$asOfTime = date('Y-m-d H:i:s',$groupDate);
	$asOfDate = date('Y-m-d',$groupDate);

	foreach($objNA as $countryData) {
			$country= $countryData->value->country;
			$region= $countryData->value->region;
			$infected= $countryData->value->infected;
			$fatality= $countryData->value->fatality;
			$recovered= $countryData->value->recovered;

			if($country == "") {
				$total = '{
					infected: "'.$infected.'",
					fatality: "'.$fatality.'",
					recovered: "'.$recovered.'"
				}';
			} else {
				$countriesArray = $countriesArray .
				'{
					country: "'.$country.'",
					region: "'.$region.'",
					infected: "'.$infected.'",
					fatality: "'.$fatality.'",
					recovered: "'.$recovered.'"
				},';
			}

			$wpdb->insert(
				$na_table_name,
				array(
					'insertTime' => current_time( 'mysql' ),
					'groupDate'=>$groupDate,
					'asOfTime' =>$asOfTime,
					'country'=>$country,
					'region'=>$region,
					'infected'=>$infected,
					'fatality'=>$fatality,
					'recovered'=>$recovered,
				)
      );

	}
	$countriesArray = rtrim($countriesArray, ",");
	$countriesArray = $countriesArray. ']';

	$varToWrite = 'var covidNepalisAbroad=
										{
											asOfDate: "'.$asOfDate.'",
											total: '.$total.',
											countries: '.$countriesArray.'
										};';

	$write = fwrite( $open, $varToWrite );
	//close the filestream
	fclose( $open );
	//END: FETCH NEPALIS ABROAD
}
