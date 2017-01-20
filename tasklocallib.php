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

require_once(dirname(__FILE__) . '/lib.php');


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
				$userinsert->timecreated = $user->lastaccess;
				$userinsert->country = $userlocation->country_name;
				$userinsert->region = $userlocation->region_name;
				$userinsert->city = $userlocation->city;
				$userinsert->latitude = $userlocation->latitude;
				$userinsert->longitude =  $userlocation->longitude;
				$insertarray[] = $userinsert;

			}

		}
		if(count($insertarray) > 0){
			if($DB->insert_records('dashboard_users_location', $insertarray)){
				mtrace("user insert completed ");
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
			WHERE u.firstaccess BETWEEN ? AND ? AND u.lastaccess != 0
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

				if($dataobj->time == 0 OR is_string($dataobj->time)){
					unset($data[$key]);

				}
				if($dataobj->time == $lasttime){
					$arrayupdate[$key] = $dataobj;
					unset($data[$key]);

				}else{
					$data[$key] = $dataobj;
				}
			}
		}
		if(count($data)>0){
			if($DB->insert_records('dashboard_data', $data)){
				mtrace("user insert completed ");
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
				mtrace("user update completed ");
			}
		}
	}
}
function dashboard_resourcesdata (){
	global $DB;
	$time = time();
	$arrayupdate = array();
	$insertresourcedata = array ();

	$lasttimeresourcesdata = $DB->get_record_sql( "SELECT 	MAX(time) AS time
												 	FROM 	{dashboard_resources}")->time;
	if ($lasttimeresourcesdata==null){
		$lasttimeresourcesdata=1;
	}

	//PAPERATTENDANCE


	$querypaper="SELECT CONCAT(d.lastmodified,d.courseid),d.lastmodified AS time,d.courseid AS courseid,activity,IFNULL(amountcreated,0) AS amountcreated
			 FROM (SELECT count(*) AS activity, courseid, lastmodified
			 FROM (SELECT s.courseid AS courseid,DATE_FORMAT(FROM_UNIXTIME(s.lastmodified),'%d-%c-%Y %H:00:00') AS lastmodified
			 FROM mdl_paperattendance_session as s
			 UNION ALL
			 SELECT s1.courseid AS courseid,DATE_FORMAT(FROM_UNIXTIME(p1.lastmodified),'%d-%c-%Y %H:00:00') AS lastmodified
			 FROM mdl_paperattendance_session AS s1
			 INNER JOIN mdl_paperattendance_presence AS p1 ON (s1.id=p1.sessionid)
			 UNION ALL
			 SELECT s2.courseid AS courseid,DATE_FORMAT(FROM_UNIXTIME(d2.timecreated),'%d-%c-%Y %H:00:00') AS lastmodified
			 FROM mdl_paperattendance_session AS s2
			 INNER JOIN mdl_paperattendance_presence AS p2 ON (s2.id=p2.sessionid)
			 INNER JOIN mdl_paperattendance_discussion AS d2 ON (p2.id=d2.presenceid)
			 UNION ALL
			 SELECT s3.courseid AS courseid,DATE_FORMAT(FROM_UNIXTIME(d3.timemodified),'%d-%c-%Y %H:00:00') AS lastmodified
			 FROM mdl_paperattendance_session AS s3
			 INNER JOIN mdl_paperattendance_presence AS p3 ON (s3.id=p3.sessionid)
			 INNER JOIN mdl_paperattendance_discussion AS d3 ON (p3.id=d3.presenceid)) AS a
			 GROUP BY courseid,lastmodified) as d
			 LEFT JOIN
			 (SELECT count(*) AS amountcreated,courseid,lastmodified
			 FROM (SELECT s5.courseid AS courseid,DATE_FORMAT(FROM_UNIXTIME(s5.lastmodified),'%d-%c-%Y %H:00:00') AS lastmodified
			 FROM mdl_paperattendance_session as s5) as b
			 GROUP BY courseid,lastmodified)
			 AS c ON (c.courseid=d.courseid) AND (c.lastmodified=d.lastmodified)";
	 $paperdata =$DB->get_records_sql($querypaper);
	 foreach ($paperdata AS $data){
		$currentdata = new stdClass();
		$currentdata->courseid=(int)$data->courseid;
		$currentdata->time=(int)strtotime($data->time)+60*60;
		$currentdata->resourceid=RESOURCES_TYPE_PAPERATTENDANCE;
		$currentdata->activity=(int)$data->activity;
		$currentdata->amountcreated=(int)$data->amountcreated;
		if($currentdata->time>$lasttimeresourcesdata){
		$insertresourcedata[]=$currentdata;
		}else if ($currentdata->time==$lasttimeresourcesdata){
		$arrayupdate[] = $currentdata;
		}
	 }



		//TURNITIN

	 $queryturnitin="SELECT CONCAT(a.time,a.course),a.time AS time,a.course AS courseid,activity,IFNULL(amountcreated,0) AS amountcreated
		FROM (SELECT count(id) AS activity, time, course
		FROM (SELECT t.id AS id,
		DATE_FORMAT(FROM_UNIXTIME(t.timecreated),'%d-%c-%Y %H:00:00') AS time,t.course AS course
		FROM mdl_turnitintooltwo AS t
		UNION
		SELECT t.id AS id, DATE_FORMAT(FROM_UNIXTIME(t.timemodified),'%d-%c-%Y %H:00:00') AS time, t.course AS course
		FROM mdl_turnitintooltwo AS t
		UNION ALL
		SELECT ts.id AS id, DATE_FORMAT(FROM_UNIXTIME(ts.submission_modified),'%d-%c-%Y %H:00:00') AS time, t.course AS course
		FROM mdl_turnitintooltwo AS t
		INNER JOIN mdl_turnitintooltwo_submissions AS ts ON (t.id=ts.turnitintooltwoid)) AS alpha
		GROUP BY course,time) AS a
		LEFT JOIN
		(SELECT COUNT(t.id) as amountcreated, DATE_FORMAT(FROM_UNIXTIME(t.timecreated),'%d-%c-%Y %H:00:00') as time, t.course as course
		FROM mdl_turnitintooltwo AS t
		GROUP BY time,course) AS b
		ON (a.course=b.course) AND (a.time=b.time)";

		$turnitindata =$DB->get_records_sql($queryturnitin);
		foreach ($turnitindata AS $data){
		$currentdata = new stdClass();
		$currentdata->courseid=(int)$data->courseid;
		$currentdata->time=(int)strtotime($data->time)+60*60;
		$currentdata->resourceid=RESOURCES_TYPE_TURNITIN;
		$currentdata->activity=(int)$data->activity;
		$currentdata->amountcreated=(int)$data->amountcreated;
		if($currentdata->time>$lasttimeresourcesdata){
		$insertresourcedata[]=$currentdata;
		}else if ($currentdata->time==$lasttimeresourcesdata){
		$arrayupdate[] = $currentdata;
		}

		}

	
	//EMARKING

	/*
	 $query="";



	 $emarkingdata =$DB->get_records_sql($query);
	 foreach ($emarkingdata AS $data){
		$currentdata = new stdClass();
		$currentdata->courseid=(int)$data->courseid;
		$currentdata->time=(int)strtotime($data->time)+60*60;
		$currentdata->resourceid=RESOURCES_TYPE_EMARKING;
		$currentdata->activity=(int)$data->activity;
		$currentdata->amountcreated=(int)$data->amountcreated;
		if($currentdata->time==null){
		$currentdata->amountcreated = 0;
		}
		if($currentdata->time==$lasttimeemarking){
		$arrayupdate[] = $currentdata;

		}else{
		if ($currentdata->time > $lasttimeemarking){
		$insertresourcedata[]=$currentdata;
		}
		}

		}
		*/

	//get all data related to the resources contained in the table mdl_modules
	$querybasicresources = "SELECT CONCAT(a.resource,a.date,a.course) AS id,a.date AS time,a.resource AS resourceid,a.course AS courseid,IFNULL(amountcreated,0) AS amountcreated ,activity
						FROM
							(SELECT 	l2.id as id2,
							COUNT(l2.id) as activity,
							DATE_FORMAT(FROM_UNIXTIME(l2.timecreated),'%d-%c-%Y %H:00:00') as date,
							cm2.course as course, m2.id AS resource
							FROM 		mdl_logstore_standard_log AS l2
							INNER JOIN	mdl_course_modules AS cm2 ON (l2.contextinstanceid=cm2.id)
							INNER JOIN	mdl_modules AS m2 ON (cm2.module=m2.id) AND target= ? AND l2.timecreated BETWEEN ? AND ?
							GROUP BY 	date,resource,course) AS a
						LEFT JOIN
							(SELECT 	l1.id as id2,
							COUNT(l1.id) as amountcreated,
							DATE_FORMAT(FROM_UNIXTIME(l1.timecreated),'%d-%c-%Y %H:00:00') as date,
							cm1.course as course, m1.id AS resource
							FROM 		mdl_logstore_standard_log AS l1
							INNER JOIN	mdl_course_modules AS cm1 ON (l1.contextinstanceid=cm1.id)
							INNER JOIN	mdl_modules AS m1 ON (cm1.module=m1.id) AND target= ? AND action= ? AND l1.timecreated BETWEEN ? AND ?
							GROUP BY 	date,resource,course) AS b
						ON (a.date=b.date) AND (a.course=b.course) AND (a.resource=b.resource)
						ORDER BY time,activity ASC";
	$basicresourcesdata =$DB->get_records_sql($querybasicresources,array('course_module',$lasttimeresourcesdata,$time,'course_module','created',$lasttimeresourcesdata,$time));

	foreach ($basicresourcesdata AS $data){

		$currentdata = new stdClass();
		$currentdata->courseid=(int)$data->courseid;
		$currentdata->time=(int)strtotime($data->time)+60*60;
		$currentdata->resourceid=(int)$data->resourceid;
		$currentdata->activity=(int)$data->activity;
		$currentdata->amountcreated=(int)$data->amountcreated;
		if($currentdata->time>$lasttimeresourcesdata){
			$insertresourcedata[]=$currentdata;
		}else if ($currentdata->time==$lasttimeresourcesdata){
			$arrayupdate[] = $currentdata;
		}
	}

	var_dump($lasttimeresourcesdata);
	echo "<br> update data:";
	var_dump($arrayupdate);
	echo "<br> insert data:";
	var_dump($insertresourcedata);


	if(count($insertresourcedata)>0){
		if(	 $DB->insert_records('dashboard_resources',$insertresourcedata)){
			echo "insert completed";
		}
	}

	//we update the data contained in arrayupdate to fill the vacuum time
	if(count($arrayupdate) >0){
		foreach($arrayupdate as $update){
			$params= array(
					$update->activity,
					$update->amountcreated,
					$update->courseid,
					$update->time,
					$update->resourceid,
			);
			$query="UPDATE {dashboard_resources}
					 SET activity = ?,
					 amountcreated = ?
					 WHERE courseid = ? AND
					 time = ? AND
					 resourceid = ?";
			if($DB->execute($query,$params)){
				echo 'update completed';
			}
		}
	}


}
?>