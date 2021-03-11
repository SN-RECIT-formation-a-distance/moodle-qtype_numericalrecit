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
 * @package    qtype_numericalrecit
 * @copyright  2013 Jean-Michel Vedrine
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {
    // Use tooltip or not to display correct answer.
    $settings->add(new admin_setting_configcheckbox('qtype_numericalrecit/usepopup',
            new lang_string('settingusepopup', 'qtype_numericalrecit'),
            new lang_string('settingusepopup_desc', 'qtype_numericalrecit'), 0));
    // Default answer type.
    $settings->add(new admin_setting_configselect('qtype_numericalrecit/defaultanswertype',
            new lang_string('defaultanswertype', 'qtype_numericalrecit'),
            new lang_string('defaultanswertype_desc', 'qtype_numericalrecit'), 0,
            array(0 => new lang_string('number', 'qtype_numericalrecit'),
                    10 => new lang_string('numeric', 'qtype_numericalrecit'),
                        100 => new lang_string('numerical_formula', 'qtype_numericalrecit'),
                        1000 => new lang_string('algebraic_formula', 'qtype_numericalrecit'))));
    // Default correctness.
    $settings->add(new admin_setting_configtext('qtype_numericalrecit/defaultcorrectness',
        get_string('defaultcorrectness', 'qtype_numericalrecit'),
        get_string('defaultcorrectness_desc', 'qtype_numericalrecit'), '_relerr < 0.01'));
    // Default answermark.
    $settings->add(new admin_setting_configtext('qtype_numericalrecit/defaultanswermark',
        get_string('defaultanswermark', 'qtype_numericalrecit'),
        get_string('defaultanswermark_desc', 'qtype_numericalrecit'), 1));
    // Default unit penalty.
    $settings->add(new admin_setting_configtext('qtype_numericalrecit/defaultunitpenalty',
        get_string('defaultunitpenalty', 'qtype_numericalrecit'),
        get_string('defaultunitpenalty_desc', 'qtype_numericalrecit'), 1));
}
