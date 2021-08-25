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
 * Version information for the numericalrecit question type.
 *
 * @package    qtype_numericalrecit
 * @copyright  2010 Hon Wai, Lau <lau65536@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$plugin->component = 'qtype_numericalrecit';
$plugin->version   = 2021070100;
$plugin->cron      = 0;
$plugin->requires  = 2018050800;
$plugin->dependencies = array(
    'qbehaviour_adaptive' => 2015111600,
    'qbehaviour_adaptivemultipart'     => 2014092500,
    'qtype_multichoice' => 2015111600,
	'atto_recittakepicture' => 2021050400
);
$plugin->release   = 'R13-1.13.2';
$plugin->maturity  = MATURITY_ALPHA;
