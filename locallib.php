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
		$date = date("d-M-Y H:00:00",$time);
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
									  ORDER BY time DESC
									  LIMIT 80');

	$avgsessions = $DB->get_records_sql('SELECT time, avgsessiontime
										 FROM {dashboard_data}
										 ORDER BY time DESC
										 LIMIT 80');

	$users = $DB->get_records_sql('SELECT time, users
								   FROM {dashboard_data}
								   ORDER BY time DESC
								   LIMIT 80');

	$courseview = $DB->get_records_sql('SELECT time, courseviews
										FROM {dashboard_data}
										ORDER BY time DESC
										LIMIT 80');

	$coursepersession = $DB->get_records_sql('SELECT time, sessions, courseviews
											  FROM {dashboard_data}
											  ORDER BY time DESC
											  LIMIT 80');

	$newusers = $DB->get_records_sql('SELECT time, newusers
									  FROM mdl_dashboard_data
									  ORDER BY time DESC
									  LIMIT 80');

	$timevalues = $DB->get_record_sql('SELECT MAX(time) as maxtime, MIN(time) as mintime
									   FROM {dashboard_data}
									   LIMIT 80');

	$positioncount = 80;
	$time = $timevalues->maxtime;
	$usersdata = array();

	for($i=81;$i>0;$i--) {
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
			
			if ((int)$coursepersession[$time]->sessions == 0) {
				$usersdata[4][$positioncount]= (int)0;
			}
		} else {
			$usersdata[4][$positioncount]= (int)0;
		}
		if(array_key_exists($time,$newusers)) {
			$usersdata[5][$positioncount]= (int)$newusers[$time]->newusers;
		} else {
			$usersdata[5][$positioncount]= (int)0;
		}
		$time = $time - 60*60;
		$positioncount--;
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

//FILL LOCATION TABLE USERS PAGE
function location_table() {
	global $DB;

	$regions = $DB->get_records_sql("SELECT region, COUNT(userid) as usersid
									 FROM {dashboard_users_location}
									 GROUP BY region");

	return $regions;
}

//FILL USERS INFO SPARKLINE CHARTS WITH ANY DISPERSSION
function users_info_disperssion($disperssion) {
	global $DB;
	if($disperssion == 1){
		$datetypesql = '%b-%Y';
		$dateadd = "-1 month";
		$datetypephp = "M-Y";
	} elseif($disperssion == 2){
		$datetypesql = '%d-%b-%Y';
		$dateadd = "-1 day";
		$datetypephp = "d-M-Y";
	} else{
		$datetypesql = '%d-%b-%Y %H:00:00';
		$dateadd = "-1 hour";
		$datetypephp = "d-M-Y H:00:00";
	}

	$sessions = $DB->get_records_sql("SELECT DATE_FORMAT(FROM_UNIXTIME(time),'".$datetypesql."') as times,
									  SUM(sessions) as totalsessions
									  FROM {dashboard_data}
									  GROUP BY times
									  LIMIT 80");

	$avgsessions = $DB->get_records_sql("SELECT DATE_FORMAT(FROM_UNIXTIME(time),'".$datetypesql."') as times,
										 ROUND(AVG(avgsessiontime),0) as avgtime
										 FROM {dashboard_data}
										 GROUP BY time
										 LIMIT 80");

	$users = $DB->get_records_sql("SELECT DATE_FORMAT(FROM_UNIXTIME(time),'".$datetypesql."') as times,
								   SUM(users) as totalusers
								   FROM {dashboard_data}
								   GROUP BY times
								   LIMIT 80");

	$courseview = $DB->get_records_sql("SELECT DATE_FORMAT(FROM_UNIXTIME(time),'".$datetypesql."') as times,
										SUM(courseviews) as totalcourses
										FROM {dashboard_data}
										GROUP BY times
										LIMIT 80");

	$coursepersession = $DB->get_records_sql("SELECT DATE_FORMAT(FROM_UNIXTIME(time),'".$datetypesql."') as times,
											  ROUND((SUM(courseviews))/(SUM(sessions)),3) as coursepersession
											  FROM {dashboard_data}
											  GROUP BY times
											  LIMIT 80");

	$newusers = $DB->get_records_sql("SELECT DATE_FORMAT(FROM_UNIXTIME(time),'".$datetypesql."') as times,
									  SUM(newusers) as totalnewusers
									  FROM {dashboard_data}
									  GROUP BY times
									  LIMIT 80");

	$timevalues = $DB->get_record_sql("SELECT
										DATE_FORMAT(FROM_UNIXTIME(MAX(time)),'".$datetypesql."') as maxtime,
										DATE_FORMAT(FROM_UNIXTIME(MIN(time)),'".$datetypesql."') as mintime
									    FROM {dashboard_data}
										LIMIT 80");

	$time = $timevalues->maxtime;
	$usersdata = array();

	for($i=81;$i>0;$i--) {
		if(array_key_exists($time,$sessions)) {
			$usersdata[0][]=(int)$sessions[$time]->totalsessions;
		} else {
			$usersdata[0][]= (int)0;
		}
		if(array_key_exists($time,$avgsessions)) {
			$usersdata[1][]=(int)$avgsessions[$time]->avgtime;
		} else {
			$usersdata[1][]= (int)0;
		}
		if(array_key_exists($time,$users)) {
			$usersdata[2][]=(int)$users[$time]->totalusers;
		} else {
			$usersdata[2][]= (int)0;
		}
		if(array_key_exists($time,$courseview)) {
			$usersdata[3][]=(int)$courseview[$time]->totalcourses;
		} else {
			$usersdata[3][]= (int)0;
		}
		if(array_key_exists($time,$coursepersession)) {
			$usersdata[4][]=(int)$coursepersession[$time]->coursepersession;
		} else {
			$usersdata[4][]= (int)0;
		}
		if(array_key_exists($time,$newusers)) {
			$usersdata[5][]=(int)$newusers[$time]->totalnewusers;
		} else {
			$usersdata[5][]= (int)0;
		}
		$time = date($datetypephp,strtotime($time.$dateadd));
	}
	foreach ($usersdata as $key => $data){
		$usersdata[$key] = array_reverse($data);
	}
	return $usersdata;
}

//FILL USERS SESSIONS/DATES CHART WITH ANY DISPERSSION
function users_sessions_disperssion($disperssion) {
	global $DB;
	if($disperssion == 1){
		$datetypesql = '%b-%Y';
		$dateadd = "+1 month";
		$datetypephp = "M-Y";
	} elseif($disperssion == 2){
		$datetypesql = '%d-%b-%Y';
		$dateadd = "+1 day";
		$datetypephp = "d-M-Y";
	} else{
		$datetypesql = '%d-%b-%Y %H:00:00';
		$dateadd = "+1 hour";
		$datetypephp = "d-M-Y H:00:00";
	}

	$query = "SELECT DATE_FORMAT(FROM_UNIXTIME(time),'".$datetypesql."') as times, SUM(sessions) as totalsessions
									  FROM {dashboard_data}
									  GROUP BY times";
	$sessions = $DB->get_records_sql($query);


	$timevalues = $DB->get_record_sql("SELECT
										DATE_FORMAT(FROM_UNIXTIME(MAX(time)),'".$datetypesql."') as maxtime,
										DATE_FORMAT(FROM_UNIXTIME(MIN(time)),'".$datetypesql."') as mintime
									    FROM {dashboard_data}");
	$positioncount = 0;
	$time = $timevalues->mintime;

	$sessionsdata = array();
	while(strtotime($time)<=strtotime($timevalues->maxtime)) {
		if(array_key_exists($time,$sessions)) {
			$sessionsdata[$positioncount][0] = $time;
			$sessionsdata[$positioncount][1] = (int)$sessions[$time]->totalsessions;
		} else {
			$sessionsdata[$positioncount][0] = $time;
			$sessionsdata[$positioncount][1] = (int)0;

		}
		$time = date($datetypephp,strtotime($time.$dateadd));
		$positioncount++;
	}
	return $sessionsdata;
}

//FILL USERS AVG TIME SESSIONS/DATES CHART USERS PAGE WITH ANY DISPERSSION
function users_avgsessions_disperssion($disperssion) {
	global $DB;
	if($disperssion == 1){
		$datetypesql = '%b-%Y';
		$dateadd = "+1 month";
		$datetypephp = "M-Y";
	} elseif($disperssion == 2){
		$datetypesql = '%d-%b-%Y';
		$dateadd = "+1 day";
		$datetypephp = "d-M-Y";
	} else{
		$datetypesql = '%d-%b-%Y %H:00:00';
		$dateadd = "+1 hour";
		$datetypephp = "d-M-Y H:00:00";
	}

	$query = "SELECT DATE_FORMAT(FROM_UNIXTIME(time),'".$datetypesql."') as times, ROUND(AVG(avgsessiontime),0) as avgtime
									  FROM {dashboard_data}
									  GROUP BY times";

	$avgtime = $DB->get_records_sql($query);

	$timevalues = $DB->get_record_sql("SELECT
										DATE_FORMAT(FROM_UNIXTIME(MAX(time)),'".$datetypesql."') as maxtime,
										DATE_FORMAT(FROM_UNIXTIME(MIN(time)),'".$datetypesql."') as mintime
									    FROM {dashboard_data}");

	$positioncount = 0;
	$time = $timevalues->mintime;
	$avgtimedata = array();

	while(strtotime($time)<=strtotime($timevalues->maxtime)) {
		if(array_key_exists($time,$avgtime)) {
			$avgtimedata[$positioncount][0] = $time;
			$avgtimedata[$positioncount][1] = (int)$avgtime[$time]->avgtime;
		} else {
			$avgtimedata[$positioncount][0] = $time;
			$avgtimedata[$positioncount][1] = (int)0;
		}
		$time = date($datetypephp,strtotime($time.$dateadd));
		$positioncount++;
	}
	return $avgtimedata;
}

//FILL USERS/DATES CHART USERS PAGE WITH ANY DISPERSSION
function users_dates_disperssion($disperssion) {
	global $DB;
	if($disperssion == 1){
		$datetypesql = '%b-%Y';
		$dateadd = "+1 month";
		$datetypephp = "M-Y";
	} elseif($disperssion == 2){
		$datetypesql = '%d-%b-%Y';
		$dateadd = "+1 day";
		$datetypephp = "d-M-Y";
	} else{
		$datetypesql = '%d-%b-%Y %H:00:00';
		$dateadd = "+1 hour";
		$datetypephp = "d-M-Y H:00:00";
	}

	$query = "SELECT DATE_FORMAT(FROM_UNIXTIME(time),'".$datetypesql."') as times, SUM(users) as totalusers
									  FROM {dashboard_data}
									  GROUP BY times";

	$users = $DB->get_records_sql($query);

	$timevalues = $DB->get_record_sql("SELECT
										DATE_FORMAT(FROM_UNIXTIME(MAX(time)),'".$datetypesql."') as maxtime,
										DATE_FORMAT(FROM_UNIXTIME(MIN(time)),'".$datetypesql."') as mintime
									    FROM {dashboard_data}");

	$positioncount = 0;
	$time = $timevalues->mintime;
	$usersdata = array();

	while(strtotime($time)<=strtotime($timevalues->maxtime)) {
		if(array_key_exists($time,$users)) {
			$usersdata[$positioncount][0] = $time;
			$usersdata[$positioncount][1] = (int)$users[$time]->totalusers;
		} else {
			$usersdata[$positioncount][0] = $time;
			$usersdata[$positioncount][1] = (int)0;
		}
		$time = date($datetypephp,strtotime($time.$dateadd));
		$positioncount++;
	}
	return $usersdata;
}

//FILL NEW USERS/DATES CHART USERS PAGE WITH ANY DISPERSSION
function newusers_dates_disperssion($disperssion) {
	global $DB;
	if($disperssion == 1){
		$datetypesql = '%b-%Y';
		$dateadd = "+1 month";
		$datetypephp = "M-Y";
	} elseif($disperssion == 2){
		$datetypesql = '%d-%b-%Y';
		$dateadd = "+1 day";
		$datetypephp = "d-M-Y";
	} else{
		$datetypesql = '%d-%b-%Y %H:00:00';
		$dateadd = "+1 hour";
		$datetypephp = "d-M-Y H:00:00";
	}

	$query = "SELECT DATE_FORMAT(FROM_UNIXTIME(time),'".$datetypesql."') as times, SUM(newusers) as totalnewusers
									  FROM {dashboard_data}
									  GROUP BY times";

	$newusers = $DB->get_records_sql($query);

	$timevalues = $DB->get_record_sql("SELECT
										DATE_FORMAT(FROM_UNIXTIME(MAX(time)),'".$datetypesql."') as maxtime,
										DATE_FORMAT(FROM_UNIXTIME(MIN(time)),'".$datetypesql."') as mintime
									    FROM {dashboard_data}");

	$positioncount = 0;
	$time = $timevalues->mintime;
	$newusersdata = array();

	while(strtotime($time)<=strtotime($timevalues->maxtime)) {
		if(array_key_exists($time,$newusers)) {
			$newusersdata[$positioncount][0] = $time;
			$newusersdata[$positioncount][1] = (int)$newusers[$time]->totalnewusers;
		} else {
			$newusersdata[$positioncount][0] = $time;
			$newusersdata[$positioncount][1] = (int)0;
		}
		$time = date($datetypephp,strtotime($time.$dateadd));
		$positioncount++;
	}
	return $newusersdata;
}

//FILL COURSE VIEWS/DATES CHART USERS PAGE WITH ANY DISPERSSION
function courseview_dates_disperssion($disperssion) {
	global $DB;
	if($disperssion == 1){
		$datetypesql = '%b-%Y';
		$dateadd = "+1 month";
		$datetypephp = "M-Y";
	} elseif($disperssion == 2){
		$datetypesql = '%d-%b-%Y';
		$dateadd = "+1 day";
		$datetypephp = "d-M-Y";
	} else{
		$datetypesql = '%d-%b-%Y %H:00:00';
		$dateadd = "+1 hour";
		$datetypephp = "d-M-Y H:00:00";
	}

	$query = "SELECT DATE_FORMAT(FROM_UNIXTIME(time),'".$datetypesql."') as times, SUM(courseviews) as totalcourseviews
									  FROM {dashboard_data}
									  GROUP BY times";

	$courseviews = $DB->get_records_sql($query);

	$timevalues = $DB->get_record_sql("SELECT
										DATE_FORMAT(FROM_UNIXTIME(MAX(time)),'".$datetypesql."') as maxtime,
										DATE_FORMAT(FROM_UNIXTIME(MIN(time)),'".$datetypesql."') as mintime
									    FROM {dashboard_data}");

	$positioncount = 0;
	$time = $timevalues->mintime;
	$courseviewsdata = array();

	while(strtotime($time)<=strtotime($timevalues->maxtime)) {
		if(array_key_exists($time,$courseviews)) {
			$courseviewsdata[$positioncount][0] = $time;
			$courseviewsdata[$positioncount][1] = (int)$courseviews[$time]->totalcourseviews;
		} else {
			$courseviewsdata[$positioncount][0] = $time;
			$courseviewsdata[$positioncount][1] = (int)0;
		}
		$time = date($datetypephp,strtotime($time.$dateadd));
		$positioncount++;
	}
	return $courseviewsdata;
}
function dashboard_getresourcemoduleid(){
	global $DB;
	$modules = $DB->get_records('modules');
	return $modules;
}
function dashboard_resourcedata($resourceid, $disperssion, $initialdate = null, $enddate = null){
	global $DB;
	if($disperssion == 1){
		$datetypesql = '%b-%Y';
		$dateadd = "+1 month";
		$datetypephp = "M-Y";
	} elseif($disperssion == 2){
		$datetypesql = '%d-%b-%Y';
		$dateadd = "+1 day";
		$datetypephp = "d-M-Y";
	} else{
		$datetypesql = '%d-%b-%Y %H:00:00';
		$dateadd = "+1 hour";
		$datetypephp = "d-M-Y H:00:00";
	}
	$parameters = array(
			$resourceid
	);
	
	$timequery = "SELECT
				DATE_FORMAT(FROM_UNIXTIME(MAX(time)),'".$datetypesql."') as maxtime,
				DATE_FORMAT(FROM_UNIXTIME(MIN(time)),'".$datetypesql."') as mintime
			    FROM {dashboard_resources}
				WHERE resourceid = ?";
	
	$timevalues = $DB->get_record_sql($timequery, $parameters);
	$query = "SELECT DATE_FORMAT(FROM_UNIXTIME(time),'".$datetypesql."') as times, activity, amountcreated
			FROM {dashboard_resources}
			WHERE resourceid = ?";

	if($initialdate !== null AND $enddate !== null){
		$parameters[] = $initialdate;
		$parameters[] = $enddate;
		$query .= "AND time BETWEEN ? AND ? ";
	}
	
	$query = $query." GROUP BY times";
	
	$resourcedata = $DB->get_records_sql($query, $parameters);
	$positioncount = 0;
	$time = $timevalues->mintime;
	$courseviewsdata = array();
	while(strtotime($time)<=strtotime($timevalues->maxtime)) {
		if(array_key_exists($time,$resourcedata)) {
			$courseviewsdata[$positioncount][0] = $time;
			$courseviewsdata[$positioncount][1] = (int)$resourcedata[$time]->activity;
		} else {
			$courseviewsdata[$positioncount][0] = $time;
			$courseviewsdata[$positioncount][1] = (int)0;
		}
		$time = date($datetypephp,strtotime($time.$dateadd));
		$positioncount++;
	}
	return $courseviewsdata;
}