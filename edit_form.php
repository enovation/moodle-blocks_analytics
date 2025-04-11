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
 * Block edit form class for the block_analytics plugin.
 *
 * Defines the form used for configuring an instance of the block_analytics block.
 *
 * @package   block_analytics
 * @copyright 2025 Enovation Solution
 * @license   http://www.gnu.org/copyleft/gpl.analytics GNU GPL v3 or later
 */

class block_analytics_edit_form extends block_edit_form {

    /**
     * Defines specific form elements for this block.
     *
     * @param MoodleQuickForm $mform The form being built.
     * @return void
     */
    protected function specific_definition($mform) {

        // Fields for editing HTML block title and contents.
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        $mform->addElement('text', 'config_title', get_string('configtitle', 'block_analytics'));
        $mform->addRule('config_title', null, 'required', null, 'client');
        $mform->setType('config_title', PARAM_TEXT);

        $mform->addElement('text', 'config_dashboard', get_string('dashboard', 'block_analytics'));
        $mform->setType('config_dashboard', PARAM_INT);
        $mform->addRule('config_dashboard', null, 'numeric', null, 'client');

        $mform->addElement('text', 'config_extraurlparams', get_string('extraurlparams', 'block_analytics'));

        $mform->addElement('text', 'config_iframewidth', get_string('iframewidth', 'block_analytics'));
        $mform->setDefault('config_iframewidth', '100%');

        $mform->addElement('text', 'config_iframeheight', get_string('iframeheight', 'block_analytics'));
        $mform->setDefault('config_iframewidth', '1000');
    }

    /**
     * Populates the form with default values from the block configuration.
     *
     * @param stdClass $defaults The default values to populate the form with.
     * @return void
     */
    function set_data($defaults) {
        if (!empty($this->block->config) && !empty($this->block->config->text)) {
            $text = $this->block->config->text;
            $draftid_editor = file_get_submitted_draft_itemid('config_text');
            if (empty($text)) {
                $currenttext = '';
            } else {
                $currenttext = $text;
            }
            $defaults->config_text['text'] = file_prepare_draft_area($draftid_editor, $this->block->context->id, 'block_analytics', 'content', 0, array('subdirs' => true), $currenttext);
            $defaults->config_text['itemid'] = $draftid_editor;
            $defaults->config_text['format'] = $this->block->config->format ?? FORMAT_MOODLE;
        } else {
            $text = '';
        }

        if (!$this->block->user_can_edit() && !empty($this->block->config->title)) {
            // If a title has been set but the user cannot edit it format it nicely
            $title = $this->block->config->title;
            $defaults->config_title = format_string($title, true, $this->page->context);
            // Remove the title from the config so that parent::set_data doesn't set it.
            unset($this->block->config->title);
        }

        // have to delete text here, otherwise parent::set_data will empty content
        // of editor
        unset($this->block->config->text);
        parent::set_data($defaults);
        // restore $text
        if (!isset($this->block->config)) {
            $this->block->config = new stdClass();
        }
        $this->block->config->text = $text;
        if (isset($title)) {
            // Reset the preserved title
            $this->block->config->title = $title;
        }
    }
}
