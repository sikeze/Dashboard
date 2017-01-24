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

//FILL USERS INFO CHART LABELS USERS PAGE
function dashboard_usersinfolabels() {
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
//FILL LOCATION TABLE USERS PAGE
function dashboard_locationtable() {
	global $DB;
	$regions = $DB->get_records_sql("SELECT city, COUNT(userid) as usersid
									 FROM {dashboard_users_location}
									 GROUP BY city");
	
	return $regions;
}
//GET LOCATION COORDINATES FOR MARKERS CLUSTERING (MAP)
function dashboard_locationmap() {
	global $DB;

	$coordinates = $DB->get_records_sql("SELECT id, latitude, longitude FROM {dashboard_users_location}");
	

	return $coordinates;
}
//FILL USERS INFO SPARKLINE CHARTS WITH ANY DISPERSION
function dashboard_usersinfodispersion($dispersion) {
	global $DB;
	if($dispersion == 1){
		$datetypesql = '%b-%Y';
		$dateadd = "-1 month";
		$datetypephp = "M-Y";
	} elseif($dispersion == 2){
		$datetypesql = '%d-%b-%Y';
		$dateadd = "-1 day";
		$datetypephp = "d-M-Y";
	} else{
		$datetypesql = '%d-%b-%Y %H:00:00';
		$dateadd = "-1 hour";
		$datetypephp = "d-M-Y H:00:00";
	}
	$sessions = $DB->get_records_sql("SELECT id, DATE_FORMAT(FROM_UNIXTIME(time),'".$datetypesql."') as times,
									  SUM(sessions) as totalsessions
									  FROM {dashboard_data}
									  GROUP BY times
									  LIMIT 80");
	$avgsessions = $DB->get_records_sql("SELECT id, DATE_FORMAT(FROM_UNIXTIME(time),'".$datetypesql."') as times,
										 ROUND(AVG(avgsessiontime),0) as avgtime
										 FROM {dashboard_data}
										 GROUP BY time
										 LIMIT 80");
	$users = $DB->get_records_sql("SELECT id, DATE_FORMAT(FROM_UNIXTIME(time),'".$datetypesql."') as times,
								   SUM(users) as totalusers
								   FROM {dashboard_data}
								   GROUP BY times
								   LIMIT 80");
	$courseview = $DB->get_records_sql("SELECT id, DATE_FORMAT(FROM_UNIXTIME(time),'".$datetypesql."') as times,
										SUM(courseviews) as totalcourses
										FROM {dashboard_data}
										GROUP BY times
										LIMIT 80");
	$coursepersession = $DB->get_records_sql("SELECT id, DATE_FORMAT(FROM_UNIXTIME(time),'".$datetypesql."') as times,
											  ROUND((SUM(courseviews))/(SUM(sessions)),3) as coursepersession
											  FROM {dashboard_data}
											  GROUP BY times
											  LIMIT 80");
	$newusers = $DB->get_records_sql("SELECT id, DATE_FORMAT(FROM_UNIXTIME(time),'".$datetypesql."') as times,
									  SUM(newusers) as totalnewusers
									  FROM {dashboard_data}
									  GROUP BY times
									  LIMIT 80");

	$timevalues = $DB->get_record_sql("SELECT
										id, DATE_FORMAT(FROM_UNIXTIME(MAX(time)),'".$datetypesql."') as maxtime,
										DATE_FORMAT(FROM_UNIXTIME(MIN(time)),'".$datetypesql."') as mintime
									    FROM {dashboard_data}
										LIMIT 80");
	$time = $timevalues->maxtime;
	$usersdata = array();
	$exist = 0;
	for($i=81;$i>0;$i--) {
		foreach($sessions as $session){
			if($session->times == $time) {
				$usersdata[0][]=(int)$session->totalsessions;
				$exist = 1;
			}
		}
		if($exist == 0){
			$usersdata[0][]= (int)0;
		}
		$exist = 0;

		foreach($avgsessions as $avg){
			if($avg->times == $time) {
				$usersdata[1][]=(int)$avg->avgtime;
				$exist = 1;
			}
		}
		if($exist == 0){
			$usersdata[1][]= (int)0;
		}
		$exist = 0;

		foreach($users as $user){
			if($user->times == $time) {
				$usersdata[2][]=(int)$user->totalusers;
				$exist = 1;
			}
		}
		if($exist == 0){
			$usersdata[2][]= (int)0;
		}
		$exist = 0;

		foreach($courseview as $course){
			if($course->times == $time) {
				$usersdata[3][]=(int)$course->totalcourses;
				$exist = 1;
			}
		}
		if($exist == 0){
			$usersdata[3][]= (int)0;
		}
		$exist = 0;

		foreach($coursepersession as $coursesession){
			if($coursesession->times == $time) {
				$usersdata[4][]=(int)$coursesession->coursepersession;
				$exist = 1;
			}
		}
		if($exist == 0){
			$usersdata[4][]= (int)0;
		}
		$exist = 0;

		foreach($newusers as $newuser){
			if($newuser->times == $time) {
				$usersdata[5][]=(int)$newuser->totalnewusers;
				$exist = 1;
			}
		}
		if($exist == 0){
			$usersdata[5][]= (int)0;
		}
		$exist = 0;
		$time = date($datetypephp,strtotime($time.$dateadd));
	}

	foreach ($usersdata as $key => $data){
		$usersdata[$key] = array_reverse($data);
	}

	return $usersdata;
}

//FILL USERS/DATES CHART OF USERS PAGE WITH ANY DISPERSION AND DATE OF CALENDAR
function dashboard_userschart($select,$dispersion, $initialdate = null, $enddate = null) {
	global $DB;
	if($dispersion == 1){
		$datetypesql = '%b-%Y';
		$dateadd = "+1 month";
		$datetypephp = "M-Y";
	} elseif($dispersion == 2){
		$datetypesql = '%d-%b-%Y';
		$dateadd = "+1 day";
		$datetypephp = "d-M-Y";
	} else{
		$datetypesql = '%d-%b-%Y %H:00:00';
		$dateadd = "+1 hour";
		$datetypephp = "d-M-Y H:00:00";
	}

	if ($select == 1) {
		$dataquery = "SUM(sessions) as totalsessions";
		$name = "totalsessions";
	} elseif ($select == 2) {
		$dataquery = "ROUND(AVG(avgsessiontime),0) as avgtime";
		$name = "avgtime";
	} elseif ($select == 3) {
		$dataquery = "SUM(users) as totalusers";
		$name = "totalusers";
	} elseif ($select == 4) {
		$dataquery = "SUM(newusers) as totalnewusers";
		$name = "totalnewusers";
	} else {
		$dataquery = "SUM(courseviews) as totalcourseviews";
		$name = "totalcourseviews";
	}

	$timeparameters = array();

	$timequery = "SELECT
				DATE_FORMAT(FROM_UNIXTIME(MAX(time)),'".$datetypesql."') as maxtime,
				DATE_FORMAT(FROM_UNIXTIME(MIN(time)),'".$datetypesql."') as mintime
			    FROM {dashboard_data}";

	if($initialdate !== null AND $enddate !== null){
		$timeparameters[] = $initialdate;
		$timeparameters[] = $enddate;
		$timequery .= " WHERE time BETWEEN ? AND ? ";
	}

	$timevalues = $DB->get_record_sql($timequery, $timeparameters);

	$parameters = array();

	$query = "SELECT
			DATE_FORMAT(FROM_UNIXTIME(time),'".$datetypesql."') as times,".$dataquery."
			FROM {dashboard_data}";

	if($initialdate !== null AND $enddate !== null){
		$parameters[] = $initialdate;
		$parameters[] = $enddate;
		$query .= " WHERE time BETWEEN ? AND ? ";
	}

	$query = $query." GROUP BY times";

	$usersdata = $DB->get_records_sql($query, $parameters);
	$positioncount = 0;
	$time = $timevalues->mintime;
	$usersviewsdata = array();

	while(strtotime($time)<=strtotime($timevalues->maxtime)) {
		if(array_key_exists($time,$usersdata)) {
			$usersviewsdata[$positioncount][0] = $time;
			$usersviewsdata[$positioncount][1] = (int)$usersdata[$time]->$name;
		} else {
			$usersviewsdata[$positioncount][0] = $time;
			$usersviewsdata[$positioncount][1] = (int)0;
		}
		$time = date($datetypephp,strtotime($time.$dateadd));
		$positioncount++;
	}
	return $usersviewsdata;
}

/**
 * Returns  the resources id that exist in moodle, must be change for CFG modules admin wants to see
 * @return objarray[]
 */
function dashboard_getresourcemoduleid(){
	global $DB;
	$modules = $DB->get_records('modules');
	return $modules;
}
/**
 * Return the specific resource data for the main chart
 * @param int $resourceid the id of the moodle module
 * @param int $dispersion the dispersion value (1=month,2=day,3=hour)
 * @param int $initialdate the initial date you want to get the data from (unix)
 * @param int $initialdate the end date you want to get the data from (unix)
 * @return array[array[]]
 */
function dashboard_resourcedata($resourceid, $dispersion, $initialdate = null, $enddate = null){
	global $DB;
	// set the dispersion
	if($dispersion == 1){
		$datetypesql = '%b-%Y';
		$dateadd = "+1 month";
		$datetypephp = "M-Y";
	} elseif($dispersion == 2){
		$datetypesql = '%d-%b-%Y';
		$dateadd = "+1 day";
		$datetypephp = "d-M-Y";
	} else{
		$datetypesql = '%d-%b-%Y %H:00:00';
		$dateadd = "+1 hour";
		$datetypephp = "d-M-Y H:00:00";
	}
	// what resource to see	
	$timeparameters = array(
			$resourceid
	);
	// Query to get the max and min times in the table
	$timequery = "SELECT
				DATE_FORMAT(FROM_UNIXTIME(MAX(time)),'".$datetypesql."') as maxtime,
				DATE_FORMAT(FROM_UNIXTIME(MIN(time)),'".$datetypesql."') as mintime
			    FROM {dashboard_resources}
				WHERE resourceid = ? ";
	
	// Check if there is any date
	if($initialdate !== null AND $enddate !== null){
		$timeparameters[] = $initialdate;
		$timeparameters[] = $enddate;
		$timequery .= " AND time BETWEEN ? AND ? ";
	}
	//Get the time values from the database
	$timevalues = $DB->get_record_sql($timequery, $timeparameters);
	
	//Parameters
	$parameters = array(
			$resourceid
	);
	// Query to get the activities from the specific resource
	$query = "SELECT 
			DATE_FORMAT(FROM_UNIXTIME(time),'".$datetypesql."') as times,
			SUM(activity) as activity,
			amountcreated
			FROM {dashboard_resources}
			WHERE resourceid = ?";
	
	// Check if there is any date
	if($initialdate !== null AND $enddate !== null){
		$parameters[] = $initialdate;
		$parameters[] = $enddate;
		$query .= "AND time BETWEEN ? AND ? ";
	}
	// Concat the groupby
	$query = $query." GROUP BY times";
	
	//Get the resource data
	$resourcedata = $DB->get_records_sql($query, $parameters);
	
	// Position caount used to get the array in google chart format
	$positioncount = 0;
	$time = $timevalues->mintime;
	$resourcearray = array();
	$time = $timevalues->mintime;
	
	//go throw each day,hour,month depending on dispersion, until it reach the maxtime 
	while(strtotime($time)<=strtotime($timevalues->maxtime)) {
		if(array_key_exists($time,$resourcedata)) {
			$resourcearray[$positioncount][0] = $time;
			$resourcearray[$positioncount][1] = (int)$resourcedata[$time]->activity;
		} else {
			$resourcearray[$positioncount][0] = $time;
			$resourcearray[$positioncount][1] = (int)0;
		}
		//add a hour, day, month depending on dispersion
		$time = date($datetypephp,strtotime($time.$dateadd));
		$positioncount++;
	}
	return $resourcearray;
}
/**
 * Return the all resource data for the main chart
 * @param int $dispersion the dispersion value (1=month,2=day,3=hour)
 * @param int $initialdate the initial date you want to get the data from (unix)
 * @param int $initialdate the end date you want to get the data from (unix)
 * @return array[array[]]
 */
function dashboard_allresourcesdata($dispersion, $initialdate = null, $enddate = null){
	global $DB;
	// set the dispersion
	if($dispersion == 1){
		$datetypesql = '%b-%Y';
		$dateadd = "+1 month";
		$datetypephp = "M-Y";
	} elseif($dispersion == 2){
		$datetypesql = '%d-%b-%Y';
		$dateadd = "+1 day";
		$datetypephp = "d-M-Y";
	} else{
		$datetypesql = '%d-%b-%Y %H:00:00';
		$dateadd = "+1 hour";
		$datetypephp = "d-M-Y H:00:00";
	}
	// Query that get the time range
	$timequery = "SELECT
				DATE_FORMAT(FROM_UNIXTIME(MAX(time)),'".$datetypesql."') as maxtime,
				DATE_FORMAT(FROM_UNIXTIME(MIN(time)),'".$datetypesql."') as mintime
			    FROM {dashboard_resources} ";
	//check if there is a date
	if($initialdate !== null AND $enddate !== null){
		$timeparameters[] = $initialdate;
		$timeparameters[] = $enddate;
		$timequery .= " WHERE time BETWEEN ? AND ? ";
		$timevalues = $DB->get_record_sql($timequery, $timeparameters);
	}else{
		$timevalues = $DB->get_record_sql($timequery);
	}
	
	//get the modules id from moodle
	$modules = dashboard_getresourcemoduleid();
	
	//use to get the resouece in a specific position in the array
	$resourceposition = 1;
	$resourcearray = array();
	foreach($modules as $module){
		$parameters = array(
				$module->id
		);
		//query that gets the specific resource data
		$query = "SELECT DATE_FORMAT(FROM_UNIXTIME(time),'".$datetypesql."') as times, SUM(activity) as activity, amountcreated
			FROM {dashboard_resources}
			WHERE resourceid = ?";
		
		//check if there is any date
		if($initialdate !== null AND $enddate !== null){
			$parameters[] = $initialdate;
			$parameters[] = $enddate;
			$query .= "AND time BETWEEN ? AND ? ";
		}
		
		//concat the group by statemaen
		$query = $query." GROUP BY times";
		
		//get the resource data
		$resourcedata = $DB->get_records_sql($query, $parameters);
		
		$time = $timevalues->mintime;
		$positioncount = 0;
		//go throw each day,hour,month depending on dispersion, until it reach the maxtime
		while(strtotime($time)<=strtotime($timevalues->maxtime)) {
			if(array_key_exists($time,$resourcedata)) {
				$resourcearray[$positioncount][0] = $time;
				$resourcearray[$positioncount][$resourceposition] = (int)$resourcedata[$time]->activity;
			} else {
				$resourcearray[$positioncount][0] = $time;
				$resourcearray[$positioncount][$resourceposition] = (int)0;
			}
			$time = date($datetypephp,strtotime($time.$dateadd));
			$positioncount++;
		}
		$resourceposition++;
	}
	return $resourcearray;
}
/**
 * Return the specific resource data for the main chart
 * @return int
 */
function dashboard_getfacebookusers() {
	global $DB;
	//Get the amount of moodle users who has access the platform (firstaccess diferent from 0)
	$moodleusers = $DB->get_record_sql('SELECT COUNT(id) as totalusers FROM {user} WHERE firstaccess != ?', array(0));
	//Get the amount of facebook active users who has access status = 1 (active)
	$facebookusers = $DB->get_record_sql('SELECT COUNT(id) as facebookusers FROM {facebook_user} WHERE status = ?', array(1));
	
	return $faebookusers->facebookusers/$moodleusers->totalusers;
	
}