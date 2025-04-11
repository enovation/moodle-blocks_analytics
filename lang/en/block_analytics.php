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
 * Languages configuration for the block_analytics plugin
 *
 * @package   block_analytics
 * @copyright 2025 Enovation Solution
 * @license   http://www.gnu.org/copyleft/gpl.analytics GNU GPL v3 or later
 */

$string['allowadditionalcssclasses'] = 'Allow additional CSS classes';
$string['configallowadditionalcssclasses'] = 'Adds a configuration option to Analytics block instances allowing additional CSS classes to be set.';
$string['configclasses'] = 'Additional CSS classes';
$string['configclasses_help'] = 'The purpose of this configuration is to aid with theming by helping distinguish Analytics blocks from each other. Any CSS classes entered here (space delimited) will be appended to the block\'s default classes.';
$string['configcontent'] = 'Content';
$string['configtitle'] = 'Analytics block title';
$string['analytics:addinstance'] = 'Add a new Analytics block';
$string['analytics:myaddinstance'] = 'Add a new Analytics block to Dashboard';
$string['newanalyticsblock'] = '(new Analytics block)';
$string['pluginname'] = 'Analytics';
$string['search:content'] = 'Analytics block content';
$string['privacy:metadata'] = 'The Analytics block only shows data stored in other locations';

$string['metabasekey'] = 'Metabase Key';
$string['metabasekey_desc'] = 'Metabase Key';
$string['metabaseurl'] = 'Metabase URL';
$string['metabaseurl_desc'] = 'Metabase URL';
$string['payload'] = 'Payload';
$string['payload_desc'] = 'Add the payload in JSON format';
$string['extraurlparams'] = 'Extra URL parameters';
$string['extraurlparams_desc'] = 'Add extra URL parameters needed for metabase embed';
$string['dashboard'] = 'Dashboard ID';
$string['iframewidth'] = 'iframe width';
$string['iframewidth_desc'] = 'Width of the the iframe';
$string['iframeheight'] = 'iframe height';
$string['iframeheight_desc'] = 'Height of the iframe';
$string['needadminpermission'] = 'You need admin permission to view this page';
$string['analytics:view'] = 'View the Analytics block content';

// Error.
$string['nopermissiontoview'] = 'No permission to view Analytics block';
$string['errorsetaudience'] = 'At least one audience restriction needs to be applied before the Metabase embed can be displayed.';
$string['errorjson'] = 'Incorrect JSON';
$string['nopermissiontoviewcategory'] = 'No permission to view the Partner categories';