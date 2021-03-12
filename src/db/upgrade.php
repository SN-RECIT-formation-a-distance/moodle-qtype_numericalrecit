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
 * numericalrecit question type upgrade code.
 *
 * @package    qtype_numericalrecit
 * @copyright  2010 Hon Wai, Lau <lau65536@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// This file keeps track of upgrades to
// the numericalrecit qtype plugin.
function xmldb_qtype_numericalrecit_upgrade($oldversion=0) {
    global $DB, $CFG;

    $dbman = $DB->get_manager();


    if ($oldversion < 2018080300) {
        // Import from xml code was wrong for answernumbering,
        // There was also a typo in the upgrade code.
        // Fix all broken questions in database.
        $DB->set_field('qtype_numericalrecit_options', 'answernumbering', 'none', array('answernumbering' => ''));

        // numericalrecit savepoint reached.
        upgrade_plugin_savepoint(true, 2018080300, 'qtype', 'numericalrecit');
    }
    return true;
}
