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
 * @copyright  2016 Juan Luis Cordova (jcordova@alumnos.uai.cl)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
// GET USERS AND IP EVERY TIME THEY CONNECT

function get_users_and_ip ($startdate,$finishdate){
	global $DB;
	$query = 		'SELECT l.id AS id,
							l.userid AS userid,
							l.ip AS ip
					FROM 	{logstore_standard_log} AS l
					WHERE	l.action = ? AND
							l.timecreated BETWEEN ? AND ?
					GROUP BY l.id';
	$users_and_ip = $DB->get_records_sql($query,array('loggedin',$startdate,$finishdate));
	
	return $users_and_ip;
}

// GET USERS AND DATE WHENEVER THEY CONNECT

function get_users_and_time_conection ($startdate,$finishdate){
	global $DB;
	$query 	= 		"SELECT l.id,
							l.userid,
							l.timecreated
					FROM 	{logstore_standard_log} AS l
							WHERE 	l.action= ? AND
							l.timecreated BETWEEN ? AND ?
					GROUP BY l.id";
	$users_and_conection=$DB->get_records_sql($query, array ('loggedin',$startdate,$finishdate));
	
	return $users_and_conection;
}

// AVERAGE OF VIEWED COURSES PER SESION

function get_total_courses_visits_per_sesion ($startdate,$finishdate){
	global $DB;
			$query1=		"SELECT 	COUNT(*) as count1
							FROM 		{logstore_standard_log} as l
							WHERE		l.action = ? AND
										l.target = ? AND
										l.timecreated BETWEEN ? AND ?
					";
			$total_course_visits= $DB->get_record_sql($query1,array('viewed','course',$startdate,$finishdate))->count1;
			
			$query2=		"SELECT		COUNT(*) as count2
							FROM 		{logstore_standard_log} as l
							WHERE 		l.action = ? AND
							l.timecreated BETWEEN ? AND ?
					";
			$total_loggin= $DB->get_record_sql($query2,array('loggedin',$startdate,$finishdate))->count2;
	
	$prom=$total_course_visits/$total_loggin;
	
	return $prom;
	
	};
	
// TOTAL SESIONS  

function dashboard_totalsesionstoday(){
	global $DB;
		$time = time();
		$timebefore = time() - 86400;
		$query2="SELECT	id,
				month(FROM_UNIXTIME(timecreated)) as month,
				day(FROM_UNIXTIME(timecreated)) as day,
				hour(FROM_UNIXTIME(timecreated)) as hour,
				timecreated,
				COUNT(*) as sessions
				FROM {logstore_standard_log} as l
				WHERE l.action = ? AND
				l.timecreated BETWEEN ? AND ? 
				GROUP BY month(FROM_UNIXTIME(timecreated)),
				day(FROM_UNIXTIME(timecreated)),
				hour(FROM_UNIXTIME(timecreated))";
		$totalloggin= $DB->get_records_sql($query2,array('loggedin', $timebefore,$time ));
		return $totalloggin;
}

// TOTAL USERS

function total_users (){
	global $DB;
	$query = 		"SELECT 	COUNT(*) AS count
					FROM 		{user} AS u";
	$result=$DB->get_record_sql($query);
	$usersamount=$result->count;
			
	return $useramount;
}

//AMOUNT OF NEW USERS WITH ACTIVITY IN THE LAST YEAR/MONTH/DAY

function total_new_users ($defined_new){
	global $DB;
	$query = 		"SELECT 	COUNT(*) AS count
					FROM 		{user} AS u
					WHERE		u.lastlogin > ?";
	$time_condition=time()-strtotime($defined_new);
	$result=$DB->get_record_sql($query,array($time_condition));
	
	$active_users=$result->count;
	
	return $active_users;
	
}

// TOTAL PAGEVIEWS

function total_pageviews ($startdate,$finishdate){
	global $DB;
	$query2=				"SELECT		COUNT(*) as count
							FROM 		{logstore_standard_log} as l
							WHERE 		l.action = ? AND
							l.timecreated BETWEEN ? AND ?";
	$total_loggin= $DB->get_record_sql($query2,array('view',$startdate,$finishdate))->count;
	return $total_loggin;
}
?>