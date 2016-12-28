<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle. If not, see <http://www.gnu.org/licenses/>.
/**
 * This script processes a PDF file.
*
* @package local
* @subpackage dashboard
* @copyright 2016 Mihail Pozarski <mipozarski@alumnos.uai.cl>
* @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/
error_reporting(E_ALL);
//define('CLI_SCRIPT', true);
require (dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php');
// Force a debugging mode regardless the settings in the site administration
@error_reporting(E_ALL | E_STRICT); // NOT FOR PRODUCTION SERVERS!
@ini_set('display_errors', '1');    // NOT FOR PRODUCTION SERVERS!
@ini_set('mysql.connect_timeout', 900);
@ini_set('default_socket_timeout', 900);
$CFG->debug = (E_ALL | E_STRICT);   // === DEBUG_DEVELOPER - NOT FOR PRODUCTION SERVERS!
$CFG->debugdisplay = 1;             // NOT FOR PRODUCTION SERVERS!
require_once ($CFG->libdir . '/clilib.php'); // cli only functions
require_once(dirname(dirname(__FILE__)) . '/locallib.php');
cli_heading('Sessions fill');
	global $DB;

	$values = dashboard_totalsesionstoday();
	$insert = array();
	foreach($values as $value){
		
		$sessions = new stdClass();
		// + 60*60 because the strtotimestrap sustracts 1 hour.
		$sessions->time = strtotime($value->date) + 60*60;
		$sessions->sessions = $value->sessions;
		$sessions->avgsession	 = 0;
		$sessions->pageviews = 0;
		$sessions->pageviewspersession = 0;
		$insert[]=$sessions;
	}

	$DB->insert_records('dashboard_data', $insert);
	
exit(0); // 0 means success