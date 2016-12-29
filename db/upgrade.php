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
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
/**
 * This file keeps track of upgrades to the evaluaciones block
*
* Sometimes, changes between versions involve alterations to database structures
* and other major things that may break installations.
*
* The upgrade function in this file will attempt to perform all the necessary
* actions to upgrade your older installation to the current version.
*
* If there's something it cannot do itself, it will tell you what you need to do.
*
* The commands in here will all be database-neutral, using the methods of
* database_manager class
*
* Please do not forget to use upgrade_set_timeout()
* before any action that may take longer time to finish.
*
* @since 2.0
* @package blocks
* @copyright 2016 Mihail Pozarski <mipozarski@alumnos.uai.cl>
* @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/
/**
 *
* @param int $oldversion
* @param object $block
*/
function xmldb_local_dashboard_upgrade($oldversion) {
	global $CFG, $DB;
	$dbman = $DB->get_manager();
	
	if ($oldversion < 2016122901) {
	
		// Define index time (unique) to be added to dashboard_data.
		$table = new xmldb_table('dashboard_data');
		$index = new xmldb_index('time', XMLDB_INDEX_UNIQUE, array('time'));
	
		// Conditionally launch add index time.
		if (!$dbman->index_exists($table, $index)) {
			$dbman->add_index($table, $index);
		}
	
		// Dashboard savepoint reached.
		upgrade_plugin_savepoint(true, 2016122901, 'local', 'dashboard');
	}
	
	return true;
	}