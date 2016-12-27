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
* @subpackage dashboard
* @copyright  2016 Mihail Pozarski <mipozarski@alumnos.uai.cl>
* @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/
namespace dashboard\task;
class dashboard_sessions extends \core\task\scheduled_task {

	public function get_name() {
		return get_string('sessiontask', 'local_dashboard');
	}
	public function execute() {
		global $DB;
		require_once(dirname(__FILE__) . '/locallib.php');
		$values = dashboard_totalsesionstoday();
		$insert = array();
		foreach($values as $value){
			$sessions = new stdClass();
			$sessions->time = $value->timecreated;
			$sessions->sessions = $value->sessions;
			$sessions->avgsessions = 0;
			$sessions->pageviews = 0;
			$sessions->pageviewspersession = 0;
			$insert[]=$sessions;
		}
		
		$DB->insert_records($table, $dataobjects);
	}
}