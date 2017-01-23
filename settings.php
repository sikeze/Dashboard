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
 *
*
* @package    local
* @subpackage dashboard
* @copyright  2017 Mihail Pozarski <mipozarski@alumnos.uai.cl
* @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/
//Belongs to plugin Dashboard
defined('MOODLE_INTERNAL') || die;
if ($hassiteconfig) {
	$settings = new admin_settingpage( 'local_dashboard', 'Dashboard' );
	$ADMIN->add( 'localplugins', $settings );
	$settings->add( new admin_setting_configtext(
	
			// This is the reference you will use to your configuration
			'yourplugin_apikey',
	
			// This is the friendly title for the config, which will be displayed
			'External API: Key',
	
			// This is helper text for this config field
			'This is the key used to access the External API',
	
			// This is the default value
			'No Key Defined',
	
			// This is the type of Parameter this config is
			PARAM_TEXT
	
			) );
}