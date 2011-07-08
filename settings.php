<?php 

/**
 * Global config file for the ILP 
 *
 * @copyright &copy; 2011 University of London Computer Centre
 * @author http://www.ulcc.ac.uk, http://moodle.ulcc.ac.uk
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @package ILP
 * @version 2.0
 */



global $CFG;

// include the assmgr db
require_once($CFG->dirroot.'/blocks/ilp/db/ilp_db.php');

// instantiate the assmgr db
$dbc = new ilp_db();

$globalsettings 	= new admin_setting_heading('block_ilp/userstatus', get_string('userstatus', 'block_ilp'), '');

$settings->add($globalsettings);

$items				=	$dbc->get_status_items(ILP_DEFAULT_USERSTATUS_RECORD);

$options			=	array();
if (!empty($items)) {
	foreach ($items as $i) {
		$options[$i->id]	=	$i->name;
	}
}

$userstatus			= 	new admin_setting_configselect('block_ilp/defaultstatusitem',get_string('defaultstatusitem','block_ilp'),get_string('defaultstatusitemconfig','block_ilp'), 'simulationassignment',$options);

$link ='<a href="'.$CFG->wwwroot.'/blocks/ilp/actions/edit_report_configuration.php">'.get_string('reportconfigurationsection', 'block_ilp').'</a>';
$settings->add(new admin_setting_heading('block_ilp_report_configuration', '', $link));


$settings->add($userstatus);


//fail colour
$failcolour			=	new admin_setting_configtext('block_ilp/failcolour',get_string('failcsscolour','block_ilp'),get_string('failcsscolourconfig','block_ilp'),ILP_CSSCOLOUR_FAIL,PARAM_RAW);
$settings->add($failcolour);
//pass colour
$passcolour			=	new admin_setting_configtext('block_ilp/passcolour',get_string('passcsscolour','block_ilp'),get_string('passcsscolourconfig','block_ilp'),ILP_CSSCOLOUR_PASS,PARAM_RAW);
$settings->add($passcolour);

//mid colour
$midcolour			=	new admin_setting_configtext('block_ilp/midcolour',get_string('midcsscolour','block_ilp'),get_string('midcsscolourconfig','block_ilp'),ILP_CSSCOLOUR_MID,PARAM_RAW);
$settings->add($midcolour);

//the fail percentage
$failpercentage			=	new admin_setting_configtext('block_ilp/failpercent',get_string('failpercent','block_ilp'),get_string('failpercentconfig','block_ilp'),ILP_DEFAULT_FAIL_PERCENTAGE,PARAM_INT);
$settings->add($failpercentage);

//the fail percentage
$passpercentage			=	new admin_setting_configtext('block_ilp/passpercent',get_string('passpercent','block_ilp'),get_string('passpercentconfig','block_ilp'),ILP_DEFAULT_PASS_PERCENTAGE,PARAM_INT);
$settings->add($passpercentage);

$link ='<a href="'.$CFG->wwwroot.'/blocks/ilp/actions/edit_status_items.php">'.get_string('editstatusitems', 'block_ilp').'</a>';
$settings->add(new admin_setting_heading('block_ilp_statusitems', '', $link));


$mis_settings 	= new admin_setting_heading('block_ilp/mis_connection', get_string('mis_connection', 'block_ilp'), '');
$settings->add($mis_settings);
$options = array(
    'mssql' => 'Mssql',
    'mysql' => 'Mysql',
    'odbc' => 'Odbc',
    'oracle' => 'Oracle',
    'postgres' => 'Postgres',
    'sybase' => 'Sybase'
);
$mis_connection			= 	new admin_setting_configselect('block_ilp/dbconnectiontype',get_string('db_connection','block_ilp'),get_string('reportconfigurationsection','block_ilp'), 'mysql', $options);
$settings->add( $mis_connection );
/*
*/

$dbname			=	new admin_setting_configtext('block_ilp/dbname',get_string( 'db_name', 'block_ilp' ),get_string( 'set_db_name', 'block_ilp' ),'moodle',PARAM_RAW);
$settings->add($dbname);

$dbprefix			=	new admin_setting_configtext('block_ilp/dbprefix',get_string( 'db_prefix', 'block_ilp' ),get_string( 'prefix_for_tablenames', 'block_ilp' ),'mdl_',PARAM_RAW);
$settings->add($dbprefix);

$dbhost			=	new admin_setting_configtext('block_ilp/dbhost',get_string( 'db_host', 'block_ilp' ), get_string( 'host_name_or_ip', 'block_ilp' ),'localhost',PARAM_RAW);
$settings->add($dbhost);

$dbuser			=	new admin_setting_configtext('block_ilp/dbuser',get_string( 'db_user', 'block_ilp' ), get_string( 'db_user', 'block_ilp' ),'',PARAM_RAW);
$settings->add( $dbuser );

$dbpass			=	new admin_setting_configtext('block_ilp/dbpass',get_string( 'db_pass', 'block_ilp' ), get_string( 'db_pass', 'block_ilp' ),'',PARAM_RAW);
$settings->add($dbpass);

$miscsettings 	= new admin_setting_heading('block_ilp/miscoptions', get_string('miscoptions', 'block_ilp'), '');

$settings->add($miscsettings);

$maxreports			=	new admin_setting_configtext('block_ilp/maxreports',get_string('maxreports','block_ilp'),get_string('maxreportsconfig','block_ilp'),ILP_DEFAULT_LIST_REPORTS,PARAM_INT);
$settings->add($maxreports);

$misplugin_settings 	= new admin_setting_heading('block_ilp/mis_plugins', get_string('mis_pluginsettings', 'block_ilp'), '');
// -----------------------------------------------------------------------------
// Get MIS plugin settings
// -----------------------------------------------------------------------------
$settings->add($misplugin_settings);
global $CFG;

$plugins = $CFG->dirroot.'/blocks/ilp/classes/dashboard/mis';

$mis_plugins = assmgr_records_to_menu($dbc->get_mis_plugins(), 'id', 'name');

foreach ($mis_plugins as $plugin_file) {

    require_once($plugins.'/'.$plugin_file.".php");
    // instantiate the object
    $class = basename($plugin_file, ".php");
    $pluginobj = new $class();
    $method = array($pluginobj, 'config_settings');

    //check whether the config_settings method has been defined
    if (is_callable($method,true)) {
        $settings = $pluginobj->config_settings($settings);
    }
}


?>