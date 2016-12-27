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
 * Defines the version of emarking
* This code fragment is called by moodle_needs_upgrading() and
* /admin/index.php
*
* @package local
* @subpackage dashboard
* @copyright 2016 Mihail Pozarski <mipozarski@alumnos.uai.cl>
* @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/
defined('MOODLE_INTERNAL') || die();
$plugin->version = 2016122708; // The current module version (Date: YYYYMMDDXX).
$plugin->requires = 2013110500; // Requires this Moodle version.
$plugin->component = 'local_dashboard'; // To check on upgrade, that module sits in correct place.
$plugin->pluginname = "local_dashboard";
