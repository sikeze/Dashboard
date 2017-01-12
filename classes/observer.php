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
* @package local
* @subpackage dashboard
* @copyright 2017 Mihail Pozarski <mipozarski@alumnos.uai.cl>
* @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/
defined('MOODLE_INTERNAL') || die();
global $CFG;
require_once($CFG->dirroot . "/local/dashboard/tasklocallib.php");
/**
 * Event observer.
 * Verifies when a user logged in
 */
class local_dashboard_observer {
	public static function user_loggedin(core\event\user_loggedin $event) {
		global $DB;
		//dashboard_test();
		$user_object = $DB->get_record('user',array('id'=>$event->relateduserid));
		if(!dashboard_test($user_object)){
			error_log("Error inserting ip from $unenrolleduserid");
		}
		error_log(print_r($event->get_data(), true));
	}
}