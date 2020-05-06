<?php
   /*
   Plugin Name: Covid19 Nepalis Abroad Data
   Plugin URI: https://github.com/rushilshakya
   description: Plug-in to display covid19 Cases of Nepalis abroad
   Version: 1.0
   Author: Rushil Shakya
   Author URI: https://github.com/rushilshakya
   License: GPL2
   */

defined( 'ABSPATH' ) or die( 'No direct access please!' );

include_once dirname( __FILE__ ) . '/db.php';
register_activation_hook( __FILE__, 'na_covid19Nepal_install' );
register_activation_hook( __FILE__, 'na_covid19Nepal_install_data' );

// add_action("wp_footer", "updateDataNepalisAbroad");

function updateDataNepalisAbroad(){
	global $wpdb;
	$na_table_name = $wpdb->prefix . 'na_covid19Nepal';
	$sql = "SELECT * FROM $na_table_name
					 WHERE groupDate = (SELECT MAX(groupDate) FROM $na_table_name)
				ORDER BY region, country ASC";

  $results= $wpdb->get_results($sql);

	echo '<script type="text/javascript">';
	echo 'var covidNepalisAbroad=[];';

	foreach ( $results as $result ) {
   	echo 'covidNepalisAbroad.push(' . json_encode($result) . ');';
	}

	echo "</script>";
}

register_deactivation_hook( __FILE__, 'na_nepalData_remove_database' );
function na_nepalData_remove_database() {
   global $wpdb;

   $na_table_name = $wpdb->prefix . 'na_covid19Nepal';
   $naSql = "DROP TABLE IF EXISTS $na_table_name";
   $wpdb->query($naSql);

	delete_option("$na_covid19Nepal_db_version");
   wp_clear_scheduled_hook('na_covid19Nepal_update_data');
}

?>