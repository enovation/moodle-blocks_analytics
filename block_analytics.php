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
 * Block definition class for the block_analytics plugin.
 *
 * Displays an embedded analytics dashboard within local-custompage pages.
 *
 * @package   block_analytics
 * @copyright 2025 Enovation Solution
 * @license   http://www.gnu.org/copyleft/gpl.analytics GNU GPL v3 or later
 */

use Firebase\JWT\JWT;

class block_analytics extends block_base {

    /**
     * Initializes the block title.
     *
     * @return void
     */
    function init() {
        $this->title = get_string('pluginname', 'block_analytics');
    }

    /**
     * Indicates that the block has global config settings.
     *
     * @return bool
     */
    function has_config() {
        return true;
    }

    /**
     * Defines where this block can be used.
     *
     * @return array Allowed formats.
     */
    function applicable_formats() {
        return array('all' => false, 'local-custompage' => true);
    }

    /**
     * Updates the block title if a custom title is set in the config.
     *
     * @return void
     */
    function specialization() {
        if (isset($this->config->title)) {
            $this->title = $this->title = format_string($this->config->title, true, ['context' => $this->context]);
        } else {
            $this->title = get_string('newanalyticsblock', 'block_analytics');
        }
    }

    /**
     * Allows multiple instances of the block on the same page.
     *
     * @return bool
     */
    function instance_allow_multiple() {
        return true;
    }

    /**
     * Generates the content of the block.
     * Embeds a dashboard iframe with a JWT token if audience is set.
     *
     * @return stdClass|null The block content.
     */
    function get_content() {
        global $CFG, $DB;

        require_once($CFG->libdir . '/filelib.php');

        $this->content = null;

        $url = get_config('block_analytics', 'metabaseurl');
        $key = get_config('block_analytics', 'metabasekey');
        $dashboard = $this->config->dashboard;
        $extraurlparams = $this->config->extraurlparams;
        $iframewidth = $this->config->iframewidth;
        $iframeheight = $this->config->iframeheight;

        $iframehtml = '';

        if ($DB->count_records('local_custompage_audience', ['pageid' => $this->instance->subpagepattern]) == 0) {
            $iframehtml .= get_string('errorsetaudience', 'block_analytics');
        } else {
            $payloadarray = [];
            $payloadarray["resource"]["dashboard"] = $dashboard;
            $payloadarray["params"] = (object) [];
            $time = time();
            $payloadarray["exp"] = $time + 60 * 60;
            $jwt = JWT::encode($payloadarray, $key, 'HS256');

            $iframeurl = $url . "/embed/dashboard/" . $jwt . "#" . $extraurlparams;

            $iframehtml .= '<iframe
                src="' . $iframeurl . '"
                frameborder="0"
                width="' . $iframewidth . '"
                height="' . $iframeheight . '"
                allowtransparency
            ></iframe>';
        }

        if ($this->content !== NULL) {
            return $this->content;
        }

        $filteropt = new stdClass;
        $filteropt->overflowdiv = true;
        if ($this->content_is_trusted()) {
            // fancy analytics allowed only on course, category and system blocks.
            $filteropt->noclean = true;
        }

        $this->content = new stdClass;
        $this->content->footer = '';

        if (!empty($iframehtml) && isset($this->config->text)) {
            // rewrite url
            $this->config->text = file_rewrite_pluginfile_urls($this->config->text, 'pluginfile.php', $this->context->id, 'block_analytics', 'content', NULL);
            // Default to FORMAT_HTML which is what will have been used before the
            // editor was properly implemented for the block.
            $format = FORMAT_HTML;
            // Check to see if the format has been properly set on the config
            if (isset($this->config->format)) {
                $format = $this->config->format;
            }
            $this->content->text = $iframehtml;
        } else {
            $this->content->text = '';
        }

        unset($filteropt); // memory footprint

        return $this->content;
    }

    /**
     * Deletes files associated with the block instance.
     *
     * @return bool
     */
    function instance_delete() {
        global $DB;
        $fs = get_file_storage();
        $fs->delete_area_files($this->context->id, 'block_analytics');
        return true;
    }

    /**
     * Copy any block-specific data when copying to a new block instance.
     * @param int $fromid the id number of the block instance to copy from
     * @return boolean
     */
    public function instance_copy($fromid) {
        $fromcontext = context_block::instance($fromid);
        $fs = get_file_storage();
        // This extra check if file area is empty adds one query if it is not empty but saves several if it is.
        if (!$fs->is_area_empty($fromcontext->id, 'block_analytics', 'content', 0, false)) {
            $draftitemid = 0;
            file_prepare_draft_area($draftitemid, $fromcontext->id, 'block_analytics', 'content', 0, array('subdirs' => true));
            file_save_draft_area_files($draftitemid, $this->context->id, 'block_analytics', 'content', 0, array('subdirs' => true));
        }
        return true;
    }

    /**
     * Determines whether this block's content can include JavaScript.
     *
     * @return bool
     */
    function content_is_trusted() {
        global $SCRIPT;

        if (!$context = context::instance_by_id($this->instance->parentcontextid, IGNORE_MISSING)) {
            return false;
        }
        //find out if this block is on the profile page
        if ($context->contextlevel == CONTEXT_USER) {
            if ($SCRIPT === '/my/index.php') {
                // this is exception - page is completely private, nobody else may see content there
                // that is why we allow JS here
                return true;
            } else {
                // no JS on public personal pages, it would be a big security issue
                return false;
            }
        }

        return true;
    }

    /**
     * The block should only be dockable when the title of the block is not empty
     * and when parent allows docking.
     *
     * @return bool
     */
    public function instance_can_be_docked() {
        return (!empty($this->config->title) && parent::instance_can_be_docked());
    }

    /**
     * Add custom analytics attributes to aid with theming and styling
     *
     * @return array
     */
    function analytics_attributes() {
        global $CFG;

        $attributes = parent::analytics_attributes();

        if (!empty($CFG->block_analytics_allowcssclasses)) {
            if (!empty($this->config->classes)) {
                $attributes['class'] .= ' ' . $this->config->classes;
            }
        }

        return $attributes;
    }

    /**
     * Return the plugin config settings for external functions.
     *
     * @return stdClass the configs for both the block instance and plugin
     * @since Moodle 3.8
     */
    public function get_config_for_external() {
        global $CFG;

        // Return all settings for all users since it is safe (no private keys, etc..).
        $instanceconfigs = !empty($this->config) ? $this->config : new stdClass();
        $pluginconfigs = (object) ['allowcssclasses' => $CFG->block_analytics_allowcssclasses];

        return (object) [
            'instance' => $instanceconfigs,
            'plugin' => $pluginconfigs,
        ];
    }
}
