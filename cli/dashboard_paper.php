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
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
/**
 *
*
* @package    local
* @subpackage sync
* @copyright  2017 Cristobal Silva (cristobal.isilvap@gmail.com)
* @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/
//This cli script is just for test querys velocities getting paperattendance data
define('CLI_SCRIPT', true);
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/config.php");
require_once ($CFG->libdir . '/clilib.php');
global $DB, $CFG;
// Now get cli options
list($options, $unrecognized) = cli_get_params(array(
		'help' => false,
		'debug' => false,
), array(
		'h' => 'help',
		'd' => 'debug'
));
if($unrecognized) {
	$unrecognized = implode("\n  ", $unrecognized);
	cli_error(get_string('cliunknowoption', 'admin', $unrecognized));
}
// Text for the console
if($options['help']) {
	$help =
	"Test to optimize paperattendance querys
	Options:
	-h, --help            Print out this help
	Example:
	\$sudo /usr/bin/php /local/dashboard/cli/tester.php";
	mtrace($help);
	die();
}
//heading
cli_heading('Dashboard paper data test'); // TODO: localize
mtrace("\nStarting at ".date("F j, Y, G:i:s")."\n");
//Query to paperattendance table group by time
$query1 = "SELECT DATE_FORMAT(FROM_UNIXTIME(sm.date),'%d-%b-%Y %H:00:00') as roundtime,
		COUNT(s.id) AS sesscount
		FROM {paperattendance_session} s
		INNER JOIN {paperattendance_sessmodule} sm ON (s.id = sm.sessionid)
		WHERE sm.date BETWEEN ? AND ?
		GROUP BY roundtime";
//Query to paperattendance table group by time and category
$query2 = "SELECT DATE_FORMAT(FROM_UNIXTIME(sm.date),'%d-%b-%Y %H:00:00') as roundtime,
		c.category,
		COUNT(s.id) AS sesscount
		FROM {paperattendance_session} s
		INNER JOIN {paperattendance_sessmodule} sm ON (s.id = sm.sessionid)
		INNER JOIN {course} c ON (c.id = s.courseid)
		WHERE sm.date BETWEEN ? AND ?
		GROUP BY category, roundtime";
//Query to dashboard_paperattendance table group by time
$query3 = "SELECT DATE_FORMAT(FROM_UNIXTIME(time),'%d-%b-%Y %H:00:00') as roundtime,
		sessionsnumber AS sesscount
		FROM {dashboard_paperattendance}
		WHERE time BETWEEN ? AND ?
		GROUP BY roundtime";

//query for max and min time
$timequery = "SELECT
				MAX(date) as maxtime,
				MIN(date) as mintime
			    FROM {paperattendance_sessmodule}";
$time = $DB->get_record_sql($timequery);
$timeparameters = array((int)$time->mintime, (int)$time->maxtime);

//Show init time for query1
$inittime = date("D d-m-Y H:i:s");
mtrace("Init time for query to paperattendance tables grouped by time: ".$inittime."\n");
//data for query1
$data1 = $DB->get_records_sql($query1, $timeparameters);
//Show end time
$endtime = date("D d-m-Y H:i:s");
mtrace("End time for query to paperattendance tables grouped by time: ".$endtime."\n");
$exetime = strtotime($endtime)-strtotime($inittime);
mtrace("Execution time: ".$exetime." seconds\n");

//Show init time for query2
$inittime = date("D d-m-Y H:i:s");
mtrace("Init time for query to paperattendance tables grouped by time and category: ".$inittime."\n");
//data for query1
$data2 = $DB->get_records_sql($query2, $timeparameters);
//Show end time
$endtime = date("D d-m-Y H:i:s");
mtrace("End time for query to paperattendance tables grouped by time and category: ".$endtime."\n");
$exetime = strtotime($endtime)-strtotime($inittime);
mtrace("Execution time: ".$exetime." seconds\n");

//Show init time for query3
$inittime = date("D d-m-Y H:i:s");
mtrace("Init time for query to dashboard_paperattendance table grouped by time: ".$inittime."\n");
//data for query1
$data3 = $DB->get_records_sql($query3, $timeparameters);
//Show end time
$endtime = date("D d-m-Y H:i:s");
mtrace("End time for query to dashboard_paperattendance table grouped by time: ".$endtime."\n");
$exetime = strtotime($endtime)-strtotime($inittime);
mtrace("Execution time: ".$exetime." seconds\n");

exit(0);