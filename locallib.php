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

function dashboard_gettotalcourseviews(){
	global $DB;
	$time = time();
	$lasttime = $DB->get_record_sql("SELECT MAX(time) as time FROM {dashboard_data}");
	$params = array(
			'viewed',
			$lasttime->time,
			$time
	);
	$query=	"SELECT id, COUNT(*) as courseviews,
			DATE_FORMAT(FROM_UNIXTIME(timecreated),'%d-%c-%Y %H:00:00') as date
			FROM {logstore_standard_log} as l
			WHERE l.action = ? AND
			l.timecreated BETWEEN ? AND ?
	    	GROUP BY date";
	if($totalcoursevisits= $DB->get_records_sql($query,$params)){
		$arrayupdate = array();
		$params = array(
				$lasttime->time,
				$time
		);
		$query=	"SELECT id, time
			FROM {dashboard_data} as d
			WHERE time BETWEEN ? AND ?";
		if($exists=$DB->get_records_sql($query,$params)){
			foreach($exists as $existkey => $exist){
				foreach($totalcoursevisits as $totalvisitskey => $totalvisits){
					if($exist->time == strtotime($totalvisits->date)){
						$arrayupdate[$totalvisits->date] = $totalvisits->courseviews;
						unset($totalcoursevisits[$totalvisitskey]);
					}
				}
			}
		}
		foreach($arrayupdate as $key => $update){
			$params= array(
					$update,
					strtotime($key)
			);
		
			$query="UPDATE {dashboard_data}
				SET courseviews = ?
				WHERE time = ?";
				
			if($DB->execute($query,$params)){
				echo 'Courseviews updated ';
			}
		}
		foreach($totalcoursevisits as $totalvisits){
				$sessions = new stdClass();
				// + 60*60 because the strtotimestrap sustracts 1 hour.
				$sessions->time = strtotime($totalvisits->date);
				$sessions->sessions = 0;
				$sessions->avgsessiontime = 0;
				$sessions->courseviews = $totalvisits->courseviews;
				$sessions->users = 0;
				$sessions->newusers = 0;
				$sessions->windows = 0;
				$sessions->linux = 0;
				$sessions->macintosh = 0;
				$sessions->ios = 0;
				$sessions->android = 0;
				$insert[]=$sessions;
		}
		if(count($totalcoursevisits)>0){
			if($DB->insert_records('dashboard_data', $insert)){
				echo "insert completed";
			}
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
	$query ="SELECT y.date, COUNT(y.users) as users FROM(SELECT	id,
			DATE_FORMAT(FROM_UNIXTIME(l.timecreated),'%d-%c-%Y %H:00:00') as date,
			userid as users
			FROM {logstore_standard_log} as l
			WHERE l.action = ? AND l.timecreated BETWEEN ? AND ? 
			GROUP BY date,userid) as y
			GROUP BY date";
	if($users=$DB->get_records_sql($query, array('loggedin',$timebefore, $time))){
		
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

function dashboard_avgsessiontime(){
	global $DB;
	$time = time();
	$timebefore = time() - 86400;
		$query="SELECT id,userid, action, DATE_FORMAT(FROM_UNIXTIME(timecreated),'%d-%c-%Y %H:00:00') as date , timecreated as time
				FROM {logstore_standard_log} 
				WHERE action IN (?,?) AND timecreated BETWEEN ? AND ?
				ORDER BY userid, date DESC";
		if($totallogged= $DB->get_records_sql($query,array('loggedin','loggedout',$timebefore,$time))){
			$previousaction = null;
			$previousdate = null;
			$previoususer = null;
			$previoustime = null;
			$timesaver = array();
			$avgtime = array();
			foreach($totallogged as $logged){
				if($previoususer == $logged->userid && $previousdate == $logged->date && $previousaction == 'loggedout' && $logged->action == 'loggedin'){
					$time = $previoustime - $logged->time;
					$timesaver[$logged->date][] = $time;
					//echo "$previoususer = $logged->userid -- $previousaction = $logged->action -- $previousdate = $logged->date <br>";
				}
				
				$previousaction = $logged->action;
				$previousdate = $logged->date;
				$previoususer = $logged->userid;
				$previoustime = $logged->time;
			}
			$sum = 0;
			$count = 0;
			$arrayplace = 0;
			$previousdate = null;
			foreach($timesaver as $key => $times){
				$arraylenght = count($times) - 1;
				$avgtime = array_sum($times)/count($times);
				$arrayavg[$key] = $avgtime;
			}
			foreach($arrayavg as $key => $avg){
				$params= array(
						$avg,
						strtotime($key)
				);
				
				$query="UPDATE {dashboard_data}
					SET avgsessiontime = ?
					WHERE time = ?";
					
				if($DB->execute($query,$params)){
					echo 'avgsessionstime updated ';
				}
			}
			}
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