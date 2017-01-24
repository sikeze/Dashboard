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
	$settings->add(new admin_setting_configmulticheckbox('dashboard_resourcetypes',
			'Módulos activos',
			'Lista de módulos activos.',
			array(
					'assign' => 1,
					'assignment' => 1,
					'book' => 0,
					'chat' => 0,
					'choice' => 0,
					'data' => 0,
					'feedback' => 0,
					'folder' => 0,
					'forum' => 1,
					'glossary' => 0,
					'imscp' => 0,
					'label' => 0,
					'lesson' => 0,
					'lti' => 0,
					'page' => 0,
					'quiz' => 0,
					'resource' => 1,
					'scorm' => 0,
					'survey' => 1,
					'url' => 1,
					'wiki' => 0,
					'workshop' => 0,
					'emarking' => 1,
					'paperattendance' => 1,
					'tunitin' => 1
					
			),
			array(
					'assign'=>'assign',
					'assignment'=>'assignment',
					'book' => 'book',
					'chat' => 'chat',
					'choice' => 'choice',
					'data' => 'data',
					'feedback' => 'feedback',
					'folder' => 'folder',
					'forum' => 'forum',
					'glossary' => 'glossary',
					'imscp' => 'imscp',
					'label' => 'label',
					'lesson' => 'lesson',
					'lti' => 'lti',
					'page' => 'page',
					'quiz' => 'quiz',
					'resource' => 'resource',
					'scorm' => 'scorm',
					'survey' => 'survey',
					'url' => 'url',
					'wiki' => 'wiki',
					'workshop' => 'workshop',
					'emarking' => 'emarking',
					'paperattendance' => 'paperattendance',
					'turnitin' => 'turnitin'
			)
	));
}