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
		// FUNCTIONS FOR USERS DATA

// GET USERS AND IP EVERY TIME THEY CONNECT
// GET USERS AND DATE WHENEVER THEY CONNECT
// AVERAGE OF VIEWED COURSES PER SESION
// TOTAL SESIONS
// TOTAL USERS
// AMOUNT OF NEW USERS WITH ACTIVITY IN THE LAST DEFINED TIME
// TOTAL PAGEVIEWS

		// FUNCTIONS FOR RESOURCES DATA

// USE IN TIME FOR TURNITIN
// USE IN TIME FOR EMARKING
// USE IN TIME FOR PAPERATENDANCE
// USE PERCENTAGE FOR EMARKING
// USE PERCENTAGE FOR TURNITIN
// USE PERCENTAGE FOR PAPERATENDANCE
// COSTS OF EMARKING
// PERCENTAGE OF USE FOR FACEBOOK

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
	
	}
	
// TOTAL SESIONS    

function dashboard_totalsesionstoday(){
	global $DB;
		$time = time();
		$timebefore = time() - 86400;
		$query2="SELECT	id,
				DATE_FORMAT(FROM_UNIXTIME(timecreated),'%d-%c-%Y %H:00:00') as date,
				COUNT(*) as sessions
				FROM {logstore_standard_log} as l
				WHERE l.action = ? AND
				l.timecreated BETWEEN ? AND ? 
				GROUP BY date";
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
// AMOUNT OF USE (IN A TIME) FOR PAPERATENDANCE
function paperattendance_use ($startdate,$finishdate){
	global $DB;
		$query =			"SELECT 	COUNT(*) AS count
							FROM		{paperattendance_session} AS p
							WHERE		lastmodified > ? AND
										lastmodified < ?";
		$courseswithpaperattendance = $DB->get_record_sql($query,array($startdate,$finishdate))->count;

	return $courseswithpaperattendance;

}
// USE PERCENTAGE FOR PAPERATENDANCE (PER COURSE)
function paperattendance_use_percentage ($startdate,$finishdate){
	global $DB;
		$query1 =			"SELECT 	COUNT(*) AS count1
							FROM		{course}
							WHERE 		startdate > ?";
		$coursesamount=$DB->get_record_sql($query,array($startdate))->count1;
	
		$query2 = 			"SELECT 	COUNT(*) AS count2
							FROM		{course} AS c
							INNER JOIN 	{paperattendance_session} AS pas ON (c.id=pas.courseid)
							WHERE		lastmodified > ? AND
										lastmodified < ?";
		$courseswithpaperattendance = $DB->get_record_sql($query,array($startdate,$finishdate))->count2;
	
		$paperattendanceuse=$coursesamount/$courseswithpaperattendance*'100';

	return $paperattendanceuse;
}

// AMOUNT OF USE IN A TIME FOR TURNITIN (HOW MANY TESTS)

function turnitin_use ($startdate,$finishdate){
	global $DB;
		$query =			"SELECT 	COUNT(*) AS count
							FROM		{plagiarism_turnitin_files} AS c
							WHERE		lastmodified > ? AND
										lastmodified < ?";
		$courseswithpaperattendance = $DB->get_record_sql($query,array($startdate,$finishdate))->count;
	return $courseswithpaperattendance;
}

// USE PERCENTAGE FOR TURNITIN (TOTAL COURSES/COURSES WITH TURNITIN)
function turnitine_use_percentage ($startdate,$finishdate){
	global $DB;
		$query1 =			"SELECT 	COUNT(*) AS count1
							FROM		{course}
							WHERE 		startdate > ?";
		$coursesamount=$DB->get_record_sql($query,array($startdate))->count1;
		$query2 = 			"SELECT 	COUNT(*) AS count2
							FROM		{course} AS c
							INNER JOIN 	{course_module} AS cm ON (c.id=cm.courseid)
							INNER JOIN 	{plagiarism_turnitin_files} AS ptf ON (cm.id=ptf.cm)
							WHERE		ptf.lastmodified > ? AND
										ptf.lastmodified < ?";
		$courseswithpaperattendance = $DB->get_record_sql($query,array($startdate,$finishdate))->count2;
		
		

}
// AMOUNT OF USE (IN A TIME) FOR EMARKING
function emarking_use ($startdate,$finishdate){
}
// USE PERCENTAGE FOR EMARKING
function emarking_use_percentage ($startdate,$finishdate){
};
// COSTS OF EMARKING
function emarking_cost ($startdate,$finishdate){
};
// PERCENTAGE OF USE FOR FACEBOOK
function facebook_use ($startdate,$finishdate){ 
};

?>