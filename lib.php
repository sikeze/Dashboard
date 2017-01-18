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

defined('MOODLE_INTERNAL') || die();
//resources type, from the table mdl_modules
define('RESOURCES_TYPE_ASSIGN', 1);
define('RESOURCES_TYPE_ASSIGNMENT', 2);
define('RESOURCES_TYPE_BOOK', 3);
define('RESOURCES_TYPE_CHAT', 4);
define('RESOURCES_TYPE_CHOICE', 5);
define('RESOURCES_TYPE_DATA', 6);
define('RESOURCES_TYPE_FEEDBACK', 7);
define('RESOURCES_TYPE_FOLDER', 8);
define('RESOURCES_TYPE_FORUM', 9);
define('RESOURCES_TYPE_GLOSARY', 10);
define('RESOURCES_TYPE_IMSCP', 11);
define('RESOURCES_TYPE_LABEL', 12);
define('RESOURCES_TYPE_LESSON', 13);
define('RESOURCES_TYPE_LTI', 14);
define('RESOURCES_TYPE_PAGE', 15);
define('RESOURCES_TYPE_QUIZ', 16);
define('RESOURCES_TYPE_RESOURCE', 17);
define('RESOURCES_TYPE_SCORM', 18);
define('RESOURCES_TYPE_SURVEY', 19);
define('RESOURCES_TYPE_URL', 20);
define('RESOURCES_TYPE_WIKI', 21);
define('RESOURCES_TYPE_WORKSHOP', 22);
define('RESOURCES_TYPE_TURNITIN', 23);
define('RESOURCES_TYPE_EMARKING', 24);
//THE ID FOR PAPERATTENDANCE IS 1000 BECAUSE PAPERATTENANCE DON'T HAVE AN ID IN THE TABLE MDL_MODULES
define('RESOURCES_TYPE_PAPERATTENDANCE', 1000);
