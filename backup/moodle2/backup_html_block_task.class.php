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
 * @package    block_analytics
 * @subpackage backup-moodle2
 * @copyright  2025 Enovation Solution
 * @license    http://www.gnu.org/copyleft/gpl.analytics GNU GPL v3 or later
 */

/**
 * Specialised backup task for the analytics block
 * (requires encode_content_links in some configdata attrs)
 *
 * TODO: Finish phpdocs
 */
class backup_analytics_block_task extends backup_block_task {

    protected function define_my_settings() {
    }

    protected function define_my_steps() {
    }

    public function get_fileareas() {
        return array('content');
    }

    public function get_configdata_encoded_attributes() {
        return array('text'); // We need to encode some attrs in configdata
    }

    static public function encode_content_links($content) {
        return $content; // No special encoding of links
    }
}

