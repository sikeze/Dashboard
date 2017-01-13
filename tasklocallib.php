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

/*function get_users_and_ip ($startdate,$finishdate){
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
*/
function dashboard_getosandbrowser(){

	$user_agent     =   $_SERVER['HTTP_USER_AGENT'];
	
	function getOS($user_agent) {
	

		$os_platform    =   "Unknown OS Platform";
	
		$os_array       =   array(
				'/Windows NT 10.0/i'     =>  'Windows 10',
				'/windows nt 6.3/i'     =>  'Windows 8.1',
				'/windows nt 6.2/i'     =>  'Windows 8',
				'/windows nt 6.1/i'     =>  'Windows 7',
				'/windows nt 6.0/i'     =>  'Windows Vista',
				'/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
				'/windows nt 5.1/i'     =>  'Windows XP',
				'/windows xp/i'         =>  'Windows XP',
				'/windows nt 5.0/i'     =>  'Windows 2000',
				'/windows me/i'         =>  'Windows ME',
				'/win98/i'              =>  'Windows 98',
				'/win95/i'              =>  'Windows 95',
				'/win16/i'              =>  'Windows 3.11',
				'/macintosh|mac os x/i' =>  'Mac OS X',
				'/mac_powerpc/i'        =>  'Mac OS 9',
				'/linux/i'              =>  'Linux',
				'/ubuntu/i'             =>  'Ubuntu',
				'/iphone/i'             =>  'iPhone',
				'/ipod/i'               =>  'iPod',
				'/ipad/i'               =>  'iPad',
				'/android/i'            =>  'Android',
				'/blackberry/i'         =>  'BlackBerry',
				'/webos/i'              =>  'Mobile'
		);
	
		foreach ($os_array as $regex => $value) {
	
			if (preg_match($regex, $user_agent)) {
				$os_platform    =   $value;
			}
	
		}
	
		return $os_platform;
	
	}
	
	function getBrowser($user_agent) {
	
		$user_agent     =   $_SERVER['HTTP_USER_AGENT'];
	
		$browser        =   "Unknown Browser";
	
		$browser_array  =   array(
				'/msie/i'       =>  'Internet Explorer',
				'/firefox/i'    =>  'Firefox',
				'/safari/i'     =>  'Safari',
				'/chrome/i'     =>  'Chrome',
				'/edge/i'       =>  'Edge',
				'/opera/i'      =>  'Opera',
				'/netscape/i'   =>  'Netscape',
				'/maxthon/i'    =>  'Maxthon',
				'/konqueror/i'  =>  'Konqueror',
				'/mobile/i'     =>  'Handheld Browser'
		);
	
		foreach ($browser_array as $regex => $value) {
	
			if (preg_match($regex, $user_agent)) {
				$browser    =   $value;
			}
	
		}
	
		return $browser;
	
	}
	
	
	$user_os        =   getOS($user_agent);
	$user_browser   =   getBrowser($user_agent);
	
	$device_details =   "<strong>Browser: </strong>".$user_browser."<br /><strong>Operating System: </strong>".$user_os."";
	
	print_r($device_details);
	
}
// AVERAGE OF VIEWED COURSES PER SESION
function dashboard_getip(){
	global $DB, $USER;
	$lasttime = $DB->get_record_sql("SELECT MAX(timecreated) as time FROM {dashboard_users_location}");
	if($lasttime->time == null){
		$lasttime = 0;
	}else{
		$lasttime = (int)$lasttime->time;
	}
	$query = "SELECT id,lastaccess, lastip
			FROM {user}
			WHERE lastaccess > ?";
	if($users= $DB->get_records_sql($query,array($lasttime))){
		$insertarray = array();
		foreach($users as $user){
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_URL, 'freegeoip.net/json/'.$user->lastip);
			$result = curl_exec($curl);
			curl_close($curl);
			$result = json_decode($result);
			
			if($result->latitude !== 0 && $result->longitude !== 0){
				$userlocation = $result;
				
				$userinsert = new stdClass();
				$userinsert->userid = $user->id;
				$userinsert->userid = $user->lastaccess;
				$userinsert->country = $userlocation->country_name;
				$userinsert->region = $userlocation->region_name;
				$userinsert->city = $userlocation->city;
				$userinsert->latitude = $userlocation->latitude;
				$userinsert->longitude =  $userlocation->longitude;
				var_dump($userinsert);
				$insertarray[] = $userinsert;
			}
		}
		if(count($insertarray) > 0){
			if($DB->insert_records('dashboard_data', $data)){
				echo "user insert completed ";
			}
		}
	}
}

function dashboard_getusersdata(){
	global $DB;
	//date_default_timezone_set('Chile/Continental');
	$time = time();
	$lasttime = $DB->get_record_sql("SELECT MAX(time) as time FROM {dashboard_data}");
	if($lasttime->time == null){
		$lasttime = 0;
	}else{
		$lasttime = (int)$lasttime->time;
	}
	echo $lasttime;
	echo time();
	
	$sessionsparams = array(
			'loggedin',
			$lasttime,
			$time

	);
	$sessionsquery="SELECT DATE_FORMAT(FROM_UNIXTIME(timecreated),'%d-%c-%Y %H:00:00') as time,
					COUNT(*) as sessions
					FROM {logstore_standard_log} as l
					WHERE l.action = ? AND
					l.timecreated BETWEEN ? AND ? 
					GROUP BY time
					ORDER BY time ASC";
	if($data= $DB->get_records_sql($sessionsquery,$sessionsparams)){
		$courseviewsparams = array(
				'viewed',
				'course',
				$lasttime,
				$time
		);
		$courseviewsquery=	"SELECT  DATE_FORMAT(FROM_UNIXTIME(timecreated),'%d-%c-%Y %H:00:00') as time,
							COUNT(*) as courseviews
							FROM {logstore_standard_log} as l
							WHERE l.action = ? AND l.target = ? AND
							l.timecreated BETWEEN ? AND ?
	    					GROUP BY time
							ORDER BY time ASC";
		if($courseviews= $DB->get_records_sql($courseviewsquery,$courseviewsparams)){
			foreach($courseviews as $views){
				if(array_key_exists($views->time, $data)){
					$data[$views->time]->courseviews = $views->courseviews;
				}else{
					$dataobj = new stdClass();
					$dataobj->time = $views->time;
					$dataobj->sessions = 0;
					$dataobj->courseviews = $views->courseviews;
					$data[$views->time] = $dataobj;
				}
			}
		}
		$usersparams = array(
				'loggedin',
				$lasttime,
				$time
		);
		$usersquery ="SELECT y.time as time, COUNT(y.users) as users FROM(SELECT	id,
					DATE_FORMAT(FROM_UNIXTIME(l.timecreated),'%d-%c-%Y %H:00:00') as time,
					userid as users
					FROM {logstore_standard_log} as l
					WHERE l.action = ? AND l.timecreated BETWEEN ? AND ?
					GROUP BY time,userid) as y
					GROUP BY time
					ORDER BY time ASC";
		if($users=$DB->get_records_sql($usersquery, $usersparams)){
			foreach($users as $user){
				if(array_key_exists($user->time, $data)){
					$data[$user->time]->users = $user->users;
				}else{
					$dataobj = new stdClass();
					$dataobj->time = $user->time;
					$dataobj->sessions = 0;
					$dataobj->courseviews = 0;
					$dataobj->users = $user->users;
					$data[$user->time] = $dataobj;
				}
			}
		}
		$newusersparams = array(
				$lasttime,
				$time
		);
		$newusersquery ="SELECT	id,
			DATE_FORMAT(FROM_UNIXTIME(u.lastaccess),'%d-%c-%Y %H:00:00') as time,
			COUNT(*) as newusers
			FROM {user} as u
			WHERE u.firstaccess BETWEEN ? AND ?
			GROUP BY time
			ORDER BY time ASC";
		if($newusers=$DB->get_records_sql($newusersquery, $newusersparams)){
			foreach($newusers as $newuser){
				if(array_key_exists($newuser->time, $data)){
					$data[$newuser->time]->newusers = $newuser->newusers;
				}else{
					$dataobj = new stdClass();
					$dataobj->time = $newuser->time;
					$dataobj->sessions = 0;
					$dataobj->courseviews = 0;
					$dataobj->users = 0;
					$dataobj->newusers = $newuser->newusers;
					$data[$newuser->time] = $dataobj;
				}
			}
		}
		$avgsessionstimeparams = array(
				'loggedin',
				'loggedout',
				$lasttime,
				$time
		);
		$avgsessionstimequery="SELECT id,
				userid,
				action,
				DATE_FORMAT(FROM_UNIXTIME(timecreated),'%d-%c-%Y %H:00:00') as date ,
				timecreated as time
				FROM {logstore_standard_log}
				WHERE action IN (?,?) AND timecreated BETWEEN ? AND ?
				ORDER BY userid, time ASC";
		if($avgsessionstime = $DB->get_records_sql($avgsessionstimequery,$avgsessionstimeparams)){
			$previousaction = null;
			$previousdate = null;
			$previoususer = null;
			$previoustime = null;
			$timesaver = array();
			$avgtime = array();
			foreach($avgsessionstime as $avgsessiontime){
				if($previoususer == $avgsessiontime->userid && $previousdate == $avgsessiontime->date && $previousaction == 'loggedout' && $avgsessiontime->action == 'loggedin'){
					$time = $avgsessiontime->time - $previoustime;
					$timesaver[$avgsessiontime->date][] = $time;
					//echo "$previoususer = $logged->userid -- $previousaction = $logged->action -- $previousdate = $logged->date <br>";
				}
		
				$previousaction = $avgsessiontime->action;
				$previousdate = $avgsessiontime->date;
				$previoususer = $avgsessiontime->userid;
				$previoustime = $avgsessiontime->time;
			}
			$sum = 0;
			$count = 0;
			$arrayplace = 0;
			$previousdate = null;
			$arrayavg = array();
			foreach($timesaver as $key => $times){
				$arraylenght = count($times) - 1;
				$avgtime = array_sum($times)/count($times);
				$arrayavg[$key] = $avgtime;
			}
			foreach($arrayavg as $key => $avg){
				if(array_key_exists($key, $data)){
					$data[$key]->avgsessiontime = $avg;
				}else{
					$dataobj = new stdClass();
					$dataobj->time = $key;
					$dataobj->sessions = 0;
					$dataobj->courseviews = 0;
					$dataobj->users = 0;
					$dataobj->newusers = 0;
					$dataobj->avgsessiontime = $avg;
					$data[$key] = $dataobj;
				}
			}
			$arrayupdate = array();
			foreach($data as $key => $thisdata){
				if(!property_exists($thisdata, "avgsessiontime")){
					$thisdata->avgsessiontime = 0;
				}
				if(!property_exists($thisdata, "courseviews")){
					$thisdata->courseviews = 0;
				}
				if(!property_exists($thisdata, "newusers")){
					$thisdata->newusers = 0;
				}
				if(!property_exists($thisdata, "users")){
					$thisdata->users = 0;
				}
				$dataobj = new stdClass();
				$dataobj->time = (int)strtotime($thisdata->time) ;
				$dataobj->sessions = (int)$thisdata->sessions;
				$dataobj->courseviews = (int)$thisdata->courseviews;
				$dataobj->users = (int)$thisdata->users;
				$dataobj->newusers = (int)$thisdata->newusers;
				$dataobj->avgsessiontime = (int)$thisdata->avgsessiontime;
				$dataobj->windows = 0;
				$dataobj->linux = 0;
				$dataobj->macintosh = 0;
				$dataobj->ios = 0;
				$dataobj->android = 0;

				if($dataobj->time == 0){
					$dataobj->time = $key;

				}
				if($dataobj->time == $lasttime){
					$arrayupdate[$key] = $dataobj;
					unset($data[$key]);

				}else{
					$data[$key] = $dataobj;
				}
			}
		}
		var_dump($data);
		if(count($data)>0){
			if($DB->insert_records('dashboard_data', $data)){
				echo "insert completed ";
			}
		}
		foreach($arrayupdate as $update){
			
			$params= array(
					$update->sessions,
					$update->courseviews,
					$update->users,
					$update->newusers,
					$update->avgsessiontime,
					$update->windows,
					$update->linux,
					$update->macintosh,
					$update->ios,
					$update->android,
					$update->time
			);
			
			$query="UPDATE {dashboard_data}
					SET sessions = ?,
					courseviews = ?,
					users = ?,newusers = ?,
					avgsessiontime = ?,
					windows = ?,linux = ?,
					macintosh = ?, ios = ?,
					android = ?
					WHERE time = ?";
			
			if($DB->execute($query,$params)){
				echo 'update completed';
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