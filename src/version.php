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
 * @copyright  2019 RECIT
 * @copyright  Based on work by 2010 Hon Wai, Lau <lau65536@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$plugin->component = 'qtype_numericalrecit';
$plugin->version   = 2025013002;
$plugin->requires  = 2024071200.00; // Moodle 4.5.0
$plugin->dependencies = array(
    'qbehaviour_adaptive' => 2015111600,
    'qbehaviour_adaptivemultipart' => 2014092500,
    'qtype_multichoice' => 2015111600
);
$plugin->release   = 'v2.0.4-stable';
$plugin->supported = [405, 405];      //  Moodle 3.9.x, 3.10.x and 3.11.x are supported.
$plugin->maturity  = MATURITY_STABLE;
