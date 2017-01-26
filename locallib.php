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
* @copyright  2017 Mihail Pozarski <mipozarski@alumnos.uai.cl>
* @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/

/**
 * Return all the users data to fill the sparklines charts
 * @param int $dispersion the dispersion value (1=month,2=day,3=hour)
 * @return array[array[]]
 */
function dashboard_usersinfodispersion($dispersion) {
	global $DB;
	
	//set the duspersion
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
	//query that gets the total of sessions group by the time
	$sessions = $DB->get_records_sql("SELECT id, DATE_FORMAT(FROM_UNIXTIME(time),'".$datetypesql."') as times,
									  SUM(sessions) as totalsessions
									  FROM {dashboard_data}
									  GROUP BY times
									  ORDER BY time DESC
									  LIMIT 80");
	
	//query that gets average time in seconds that of a session
	$avgsessions = $DB->get_records_sql("SELECT id, DATE_FORMAT(FROM_UNIXTIME(time),'".$datetypesql."') as times,
										 ROUND(AVG(avgsessiontime),0) as avgtime
										 FROM {dashboard_data}
										 GROUP BY times 
										 ORDER BY time DESC
										 LIMIT 80");
	
	//query that gets the total users group by the time
	$users = $DB->get_records_sql("SELECT id, DATE_FORMAT(FROM_UNIXTIME(time),'".$datetypesql."') as times,
								   SUM(users) as totalusers
								   FROM {dashboard_data}
								   GROUP BY times
								   ORDER BY time DESC
								   LIMIT 80");
	
	//query that gets the total views of courses group by the time
	$courseview = $DB->get_records_sql("SELECT id, DATE_FORMAT(FROM_UNIXTIME(time),'".$datetypesql."') as times,
										SUM(courseviews) as totalcourses
										FROM {dashboard_data}
										GROUP BY times
										ORDER BY time DESC
										LIMIT 80");
	
	//query that gets the total views of courses per session 
	$coursepersession = $DB->get_records_sql("SELECT id, DATE_FORMAT(FROM_UNIXTIME(time),'".$datetypesql."') as times,
											  ROUND((SUM(courseviews))/(SUM(sessions)),3) as coursepersession
											  FROM {dashboard_data}
											  GROUP BY times
											  ORDER BY time DESC
											  LIMIT 80");
	
	//query that gets the total of new users group by the time
	$newusers = $DB->get_records_sql("SELECT id, DATE_FORMAT(FROM_UNIXTIME(time),'".$datetypesql."') as times,
									  SUM(newusers) as totalnewusers
									  FROM {dashboard_data}
									  GROUP BY times
									  ORDER BY time DESC
									  LIMIT 80");

	//query that gets time in db
	$timevalues = $DB->get_record_sql("SELECT
										id, DATE_FORMAT(FROM_UNIXTIME(MAX(time)),'".$datetypesql."') as maxtime,
										DATE_FORMAT(FROM_UNIXTIME(MIN(time)),'".$datetypesql."') as mintime
									    FROM {dashboard_data}
										LIMIT 80");
	
	//check if time exists
	$time = $timevalues->maxtime;
	$usersdata = array();
	$exist = 0;
	
	//the sparklines will only show the last 80 values
	for($i=81;$i>0;$i--) {
		foreach($sessions as $session){
			//if the date exists, then fill de array with the db value
			if($session->times == $time) {
				$usersdata[0][]=(int)$session->totalsessions;
				$exist = 1;
			}
		}
		//if the date does not exists, then fill the array with 0
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
		//new date with minus 1 month, 1 day or 1 hour depending on dispersion
		$time = date($datetypephp,strtotime($time.$dateadd));
	}

	foreach ($usersdata as $key => $data){
		$usersdata[$key] = array_reverse($data);
	}

	return $usersdata;
}

/**
 * Return all the users data labels to show on the sparklines charts
 * @return array[array[]]
 */
function dashboard_usersinfolabels() {
	global $DB;
	
	//query that get the value of each sparkline to show in the div
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
	
	//fill the labels array with the querys values
	$labels = array($totalsessions->totalsessions,$avgsessions->avgsessions,$newusers->newusers,$users->users,$courseviews->courseviews,$coursepersession->coursesessions);
	return $labels;
}

/**
 * Return cities, countries and total users to fill the location table by dates
 * @param int $initialdate the initial date you want to get the data from (unix)
 * @param int $initialdate the end date you want to get the data from (unix)
 * @return obj array[]
 */
function dashboard_locationtable($initialdate = null, $enddate = null) {
	global $DB;
	
	$parameters = array();
	
	//query that gets cities, countries and total users
	$query = "SELECT
			id, city, country, COUNT(userid) as usersid
			FROM {dashboard_users_location}";
	
	//check if there is any date
	if($initialdate !== null AND $enddate !== null){
		$parameters[] = $initialdate;
		$parameters[] = $enddate;
		$query .= " WHERE timecreated BETWEEN ? AND ? ";
	}
	
	//concat the group by to the query
	$query = $query." GROUP BY city";
	
	$locationdata = $DB->get_records_sql($query, $parameters);
	
	return $locationdata;
}

/**
 * Return latitud and longitude to make the clustering of markers in map
 * @param int $initialdate the initial date you want to get the data from (unix)
 * @param int $initialdate the end date you want to get the data from (unix)
 * @return obj array[]
 */
function dashboard_locationmap($initialdate = null, $enddate = null) {
	global $DB;

	$parameters = array();
	
	//query that gets the latitude and longitude
	$query = "SELECT id, latitude, longitude FROM {dashboard_users_location}";
	
	//check if there is any date
	if($initialdate !== null AND $enddate !== null){
		$parameters[] = $initialdate;
		$parameters[] = $enddate;
		$query .= " WHERE timecreated BETWEEN ? AND ? ";
	}
	
	$coordinates = $DB->get_records_sql($query, $parameters);

	return $coordinates;
}

/**
 * Return all users data for the main chart
 * @param int $select the id of the users select (sessions, average session time, users, new users, course views)
 * @param int $dispersion the dispersion value (1=month,2=day,3=hour)
 * @param int $initialdate the initial date you want to get the data from (unix)
 * @param int $initialdate the end date you want to get the data from (unix)
 * @return array[array[]]
*/
function dashboard_userschart($select,$dispersion, $initialdate = null, $enddate = null) {
	global $DB;
	
	//set the dispersion
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

	//set the select
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
	
	//query that gets time on db
	$timequery = "SELECT
				DATE_FORMAT(FROM_UNIXTIME(MAX(time)),'".$datetypesql."') as maxtime,
				DATE_FORMAT(FROM_UNIXTIME(MIN(time)),'".$datetypesql."') as mintime
			    FROM {dashboard_data}";

	//check if there is any date
	if($initialdate !== null AND $enddate !== null){
		$timeparameters[] = $initialdate;
		$timeparameters[] = $enddate;
		$timequery .= " WHERE time BETWEEN ? AND ? ";
	}

	$timevalues = $DB->get_record_sql($timequery, $timeparameters);
	
	$time = $timevalues->mintime;

	$parameters = array();

	//query that gets all the users data 
	$query = "SELECT
			DATE_FORMAT(FROM_UNIXTIME(time),'".$datetypesql."') as times,".$dataquery."
			FROM {dashboard_data}";
	
	//check if there is any date
	if($initialdate !== null AND $enddate !== null){
		$parameters[] = $initialdate;
		$parameters[] = $enddate;
		$query .= " WHERE time BETWEEN ? AND ? ";
	}

	//concat the group by
	$query = $query." GROUP BY times";

	$usersdata = $DB->get_records_sql($query, $parameters);
	
	//check for calendar dates to show on the main chart
	if($initialdate !== null){
		$time = date($datetypephp,$initialdate);
	}else{
		$time = $timevalues->mintime;
	}
	if($enddate !== null){
		$maxtime = $enddate;
	}else{
		$maxtime = strtotime($timevalues->maxtime);
	}
	
	$usersviewsdata = array();
	$positioncount = 0;

	//fill array with the data
	while(strtotime($time)<=$maxtime) {
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
	if($initialdate !== null){
		$time = date($datetypephp,$initialdate);
	}else{
		$time = $timevalues->mintime;
	}
	if($enddate !== null){
		$maxtime = $enddate;
	}else{
		$maxtime = strtotime($timevalues->maxtime);
	}	
	$resourcearray = array();
	
	//go throw each day,hour,month depending on dispersion, until it reach the maxtime 
	while(strtotime($time)<=$maxtime) {
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
	global $DB, $CFG;
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
	$timevalues = $DB->get_record_sql($timequery);
	
	$time = $timevalues->mintime;
	
	$modules = explode(',',$CFG->dashboard_resourcetypes);
	list ( $sqlin, $parametros ) = $DB->get_in_or_equal ( $modules );
	
	$modulesdata = $DB->get_records_sql("SELECT * FROM {modules} WHERE name $sqlin",$parametros);
	
	//use to get the resouece in a specific position in the array
	$resourceposition = 1;
	$resourcearray = array();
	foreach($modulesdata as $module){
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
		if($initialdate !== null){
			$time = date($datetypephp,$initialdate);
		}else{
			$time = $timevalues->mintime;
		}
		if($enddate !== null){
			$maxtime = $enddate;
		}else{
			$maxtime = strtotime($timevalues->maxtime);
		}
		$positioncount = 0;
		//go throw each day,hour,month depending on dispersion, until it reach the maxtime
		while(strtotime($time)<=$maxtime) {
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
	
	return $facebookusers->facebookusers/$moodleusers->totalusers;
	
}