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
 * @package    local
 * @subpackage dashboard
 * @copyright  2017 Danielle Alves (dalves@alumnos.uai.cl)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

//FILL USERS SESSIONS/DATES CHART USERS PAGE
function users_sessions_dates() {
	global $DB;
	
	$sessions = $DB->get_records_sql('SELECT time, sessions 
									  FROM {dashboard_data} 
									  ORDER BY time ASC');
	$timevalues = $DB->get_record_sql('SELECT MAX(time) as maxtime, MIN(time) as mintime 
									   FROM {dashboard_data}');
	
	$positioncount = 0;
	$time = $timevalues->mintime;
	$sessionsdata = array();
	
	while($time<=$timevalues->maxtime) {
		$date = date("j M, Y H:i",$time);
		if(array_key_exists($time,$sessions)) {
			$sessionsdata[$positioncount][0] = $date;
			$sessionsdata[$positioncount][1] = (int)$sessions[$time]->sessions;
		} else {
			$sessionsdata[$positioncount][0] = $date;
			$sessionsdata[$positioncount][1] = (int)0;
		}
		$time = $time + 60*60;
		$positioncount++;
	}
	return $sessionsdata;
}

//FILL USERS INFO SPARKLINES CHARTS USERS PAGE
function users_info() {
	global $DB;
	
	$sessions = $DB->get_records_sql('SELECT time, sessions
									  FROM {dashboard_data}
									  ORDER BY time ASC');
	
	$avgsessions = $DB->get_records_sql('SELECT time, avgsessiontime
										 FROM {dashboard_data}
										 ORDER BY time ASC');
	
	$users = $DB->get_records_sql('SELECT time, users
								   FROM {dashboard_data}
								   ORDER BY time ASC');
	
	$courseview = $DB->get_records_sql('SELECT time, courseviews
										FROM {dashboard_data}
										ORDER BY time ASC');
	
	$coursepersession = $DB->get_records_sql('SELECT time, sessions, courseviews
											  FROM {dashboard_data}
											  ORDER BY time ASC');
	
	$newusers = $DB->get_records_sql('SELECT time, newusers
									  FROM mdl_dashboard_data
									  ORDER BY time ASC');
	
	$timevalues = $DB->get_record_sql('SELECT MAX(time) as maxtime, MIN(time) as mintime
									   FROM {dashboard_data}');
	
	$positioncount = 0;
	$time = $timevalues->mintime;
	$usersdata = array();
	
	while($time<=$timevalues->maxtime) {
		if(array_key_exists($time,$sessions)) {
			$usersdata[0][$positioncount]= (int)$sessions[$time]->sessions;
		} else {
			$usersdata[0][$positioncount]= (int)0;
		}
		if(array_key_exists($time,$avgsessions)) {
			$usersdata[1][$positioncount]= (int)$avgsessions[$time]->avgsessiontime;
		} else {
			$usersdata[1][$positioncount]= (int)0;
		}
		if(array_key_exists($time,$users)) {
			$usersdata[2][$positioncount]= (int)$users[$time]->users;
		} else {
			$usersdata[2][$positioncount]= (int)0;
		}
		if(array_key_exists($time,$courseview)) {
			$usersdata[3][$positioncount]= (int)$courseview[$time]->courseviews;
		} else {
			$usersdata[3][$positioncount]= (int)0;
		}
		if(array_key_exists($time,$coursepersession)) {
			$usersdata[4][$positioncount]= round(((int)$coursepersession[$time]->courseviews)/((int)$coursepersession[$time]->sessions),3);
		} else {
			$usersdata[4][$positioncount]= (int)0;
		}
		if(array_key_exists($time,$newusers)) {
			$usersdata[5][$positioncount]= (int)$newusers[$time]->newusers;
		} else {
			$usersdata[5][$positioncount]= (int)0;
		}
		$time = $time + 60*60;
		$positioncount++;
	}
	return $usersdata;
}

//FILL USERS INFO CHART LABELS USERS PAGE
function users_info_labels() {
	global $DB;
	
	$totalsessions = $DB->get_record_sql('SELECT SUM(sessions) as totalsessions
										  FROM {dashboard_data}');
	
	$avgsessions = $DB->get_record_sql('SELECT ROUND(AVG(avgsessiontime),0) as avgsessions
										  FROM {dashboard_data}');
	
	$newusers = $DB->get_record_sql('SELECT SUM(newusers) as newusers
									 FROM {dashboard_data}');
	
	$users = $DB->get_record_sql('SELECT SUM(users) as users
								  FROM {dashboard_data}');
	
	$courseviews = $DB->get_record_sql('SELECT SUM(courseviews) as courseviews
									 	FROM {dashboard_data}');
	
	$coursepersession = $DB->get_record_sql('SELECT ROUND(AVG(courseviews/sessions),3) as coursesessions
											 FROM {dashboard_data}');

	$labels = array($totalsessions->totalsessions,$avgsessions->avgsessions,$newusers->newusers,$users->users,$courseviews->courseviews,$coursepersession->coursesessions);
	return $labels;
}

//FILL DEVICES TABLE USERS PAGE
function users_devices_table() {
	
}