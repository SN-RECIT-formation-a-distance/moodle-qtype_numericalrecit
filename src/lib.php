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

defined('MOODLE_INTERNAL') || die();

/**
 * Used to save and restore image correctly
 *
 * @package    qtype_numericalrecit
 * @copyright  2019 RECIT
 * @copyright  Based on work by 2010 Hon Wai, Lau <lau65536@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

function qtype_numericalrecit_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options=array()) {
    global $CFG;
    require_once($CFG->libdir . '/questionlib.php');
    if (in_array($filearea, array('stepfeedback', 'intro'))) {
        $relativepath = implode('/', $args);

        $fullpath = "/$context->id/qtype_numericalrecit/{$filearea}$itemId/$relativepath";

        $fs = get_file_storage();
		$file = $fs->get_file_by_hash(sha1($fullpath));		
        
        if($file == false){
            return false;
        }
        
        if ($file->is_directory()){		
            return false;
        }

        send_stored_file($file, null, 0, $options);
    }else{
        question_pluginfile($course, $context, 'qtype_numericalrecit', $filearea, $args, $forcedownload, $options);
    }
}
