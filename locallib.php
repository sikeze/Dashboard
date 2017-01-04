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

function dashboard_filldatatable (){
	global $DB;
	//date_default_timezone_set('Chile/Continental');
	//$timebefore = date("d-m-Y H:00:00", time()-86400);
	$time = time();
	$timebefore = $time - 86400;
	$fillingtime = $timebefore - ($timebefore % 3600);

	$insert = array();
	while($fillingtime <= $time){
		$sessions = new stdClass();
		// + 60*60 because the strtotimestrap sustracts 1 hour.
		$sessions->time = $fillingtime;
		$sessions->sessions = 0;
		$sessions->avgsessiontime = 0;
		$sessions->courseviews = 0;
		$sessions->users = 0;
		$sessions->newusers = 0;
		$sessions->windows = 0;
		$sessions->linux = 0;
		$sessions->macintosh = 0;
		$sessions->ios = 0;
		$sessions->android = 0;
		$insert[]=$sessions;
		
		$fillingtime = $fillingtime + (60*60);
	}
	
	if($DB->insert_records('dashboard_data', $insert)){
		echo "insert completed";
	}else{
		echo "insert failed";
	}
	
	
	
}

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

function dashboard_gettotalcourseviews(){
	global $DB;
	
	$time = time();
	$timebefore = time() - 86400;
			
			$params = array(
					'viewed',
					$timebefore,
					$time
			);
			$query=		"SELECT COUNT(*) as courseviews,
						DATE_FORMAT(FROM_UNIXTIME(timecreated),'%d-%c-%Y %H:00:00') as date
						FROM {logstore_standard_log} as l
						WHERE l.action = ? AND
						l.timecreated BETWEEN ? AND ?
					    GROUP BY date";
			$totalcoursevisits= $DB->get_records_sql($query,$params);
			foreach($totalcoursevisits as $totalvisits){
					
				$params= array(
						$totalvisits->courseviews,
						strtotime($totalvisits->date)
				);
			
				$query="UPDATE {dashboard_data}
					SET courseviews = ?
					WHERE time = ?";
					
				if($DB->execute($query,$params)){
					echo 'Courseviews updated ';
				}
			}
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
		$totalloggins= $DB->get_records_sql($query2,array('loggedin', $timebefore,$time ));
		foreach($totalloggins as $totalloggin){
			
			$params= array(
					$totalloggin->sessions,
					strtotime($totalloggin->date)
			);
		
			$query="UPDATE {dashboard_data}
					SET sessions = ?
					WHERE time = ?";
			
			if($DB->execute($query,$params)){
				echo 'Sessions updated ';
			}
		}
}

// TOTAL USERS

function dashboard_totalusers(){
	global $DB;
	$time = time();
	$timebefore = time() - 86400;
	$query ="SELECT	id,
			DATE_FORMAT(FROM_UNIXTIME(u.lastaccess),'%d-%c-%Y %H:00:00') as date,
			COUNT(*) as users
			FROM {user} as u
			WHERE u.lastaccess BETWEEN ? AND ? 
			GROUP BY date";
	if($users=$DB->get_records_sql($query, array($timebefore, $time))){
		foreach($users as $user){
				
			$params= array(
					$user->users,
					strtotime($user->date)
			);
		
			$query="UPDATE {dashboard_data}
					SET users = ?
					WHERE time = ?";
				
			if($DB->execute($query,$params)){
				echo 'users updated ';
			}
		}
	}else{
		echo"user update failed";
	}
}

//AMOUNT OF NEW USERS WITH ACTIVITY IN THE LAST YEAR/MONTH/DAY

function dashboard_newusers(){
	global $DB;
	$time = time();
	$timebefore = time() - 86400;
	$query ="SELECT	id,
			DATE_FORMAT(FROM_UNIXTIME(u.lastaccess),'%d-%c-%Y %H:00:00') as date,
			COUNT(*) as users
			FROM {user} as u
			WHERE u.firstaccess BETWEEN ? AND ?
			GROUP BY date";
	if($users=$DB->get_records_sql($query, array($timebefore, $time))){
		foreach($users as $user){
	
			$params= array(
					$user->users,
					strtotime($user->date)
			);
	
			$query="UPDATE {dashboard_data}
					SET newusers = ?
					WHERE time = ?";
	
			if($DB->execute($query,$params)){
				echo 'new users updated ';
			}
		}
	}else{
		echo"user update failed";
	}
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