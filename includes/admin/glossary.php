<?php
/**
 * Settings Glossary Functions
 *
 * @package SIM_COM
 * @version 1.0.2
 * @since WPAS 4.0
 */
if (!defined('ABSPATH')) exit;
add_action('sim_com_settings_glossary', 'sim_com_settings_glossary');
/**
 * Display glossary information
 * @since WPAS 4.0
 *
 * @return html
 */
function sim_com_settings_glossary() { ?>
<p><?php _e('Software Issue Manager allows to track the resolution of every project issue in a productive and efficient way.', 'sim-com'); ?></p>
<p><?php _e('The below are the definitions of entities, attributes, and terms included in Software Issue Manager.', 'sim-com'); ?></p>
<div id="glossary" class="accordion-container">
<ul class="outer-border">
<li id="emd_issue" class="control-section accordion-section">
<h3 class="accordion-section-title hndle" tabindex="2"><?php _e('Issues', 'sim-com'); ?></h3>
<div class="accordion-section-content">
<div class="inside">
<table class="form-table"><p class"lead"><?php _e('Issues are a collection of information about bugs, tasks, and feature requests that occur during a project\'s life cycle.', 'sim-com'); ?></p><tr>
<th><?php _e('Title', 'sim-com'); ?></th>
<td><?php _e(' Title is a required field. Title does not have a default value. ', 'sim-com'); ?></td>
</tr><tr>
<th><?php _e('Content', 'sim-com'); ?></th>
<td><?php _e(' Content does not have a default value. ', 'sim-com'); ?></td>
</tr><tr>
<th><?php _e('ID', 'sim-com'); ?></th>
<td><?php _e('Sets a unique identifier for an issue. ID does not have a default value. ', 'sim-com'); ?></td>
</tr><tr>
<th><?php _e('Due Date', 'sim-com'); ?></th>
<td><?php _e('Sets the targeted resolution date for an issue. Due Date does not have a default value. ', 'sim-com'); ?></td>
</tr><tr>
<th><?php _e('Resolution Summary', 'sim-com'); ?></th>
<td><?php _e('Sets a brief summary of the resolution of an issue. Resolution Summary does not have a default value. ', 'sim-com'); ?></td>
</tr><tr>
<th><?php _e('Documents', 'sim-com'); ?></th>
<td><?php _e('Allows to upload files related to an issue. Documents does not have a default value. ', 'sim-com'); ?></td>
</tr><tr>
<th><?php _e('Form Name', 'sim-com'); ?></th>
<td><?php _e(' Form Name is filterable in the admin area. Form Name has a default value of <b>admin</b>.', 'sim-com'); ?></td>
</tr><tr>
<th><?php _e('Form Submitted By', 'sim-com'); ?></th>
<td><?php _e(' Form Submitted By is filterable in the admin area. Form Submitted By does not have a default value. ', 'sim-com'); ?></td>
</tr><tr>
<th><?php _e('Form Submitted IP', 'sim-com'); ?></th>
<td><?php _e(' Form Submitted IP is filterable in the admin area. Form Submitted IP does not have a default value. ', 'sim-com'); ?></td>
</tr><tr>
<th><?php _e('Browser', 'sim-com'); ?></th>

<td><?php _e('Sets the browser version that an issue may be reproduced in. Browser accepts multiple values like tags', 'sim-com'); ?>. <?php _e('Browser does not have a default value', 'sim-com'); ?>.<div class="taxdef-block"><p><?php _e('The following are the preset values for <b>Browser:</b>', 'sim-com'); ?></p><p class="taxdef-values"><?php _e('Chrome 33', 'sim-com'); ?>, <?php _e('Internet Explorer 11', 'sim-com'); ?>, <?php _e('Safari 7.0', 'sim-com'); ?>, <?php _e('Opera 20', 'sim-com'); ?>, <?php _e('Firefox 29', 'sim-com'); ?></p></div></td>
</tr>
<tr>
<th><?php _e('Category', 'sim-com'); ?></th>

<td><?php _e('Sets the category that an issue belongs to. Category accepts multiple values like tags', 'sim-com'); ?>. <?php _e('Category has a default value of:', 'sim-com'); ?> <?php _e(' bug', 'sim-com'); ?>. <?php _e('Category is a required field therefore must be assigned to a value', 'sim-com'); ?>.<div class="taxdef-block"><p><?php _e('The following are the preset values and value descriptions for <b>Category:</b>', 'sim-com'); ?></p>
<table class="table tax-table form-table"><tr><td><?php _e('Bug', 'sim-com'); ?></td>
<td><?php _e('Bugs are software problems or defects in the system that need to be resolved.', 'sim-com'); ?></td>
</tr>
<tr>
<td><?php _e('Feature Request', 'sim-com'); ?></td>
<td><?php _e('Feature requests are functional enhancements submitted by clients.', 'sim-com'); ?></td>
</tr>
<tr>
<td><?php _e('Task', 'sim-com'); ?></td>
<td><?php _e('Tasks are activities that need to be accomplished within a defined period of time or by a deadline to resolve issues.', 'sim-com'); ?></td>
</tr>
</table>
</div></td>
</tr>
<tr>
<th><?php _e('Priority', 'sim-com'); ?></th>

<td><?php _e('Sets the priority level assigned to an issue. Priority accepts multiple values like tags', 'sim-com'); ?>. <?php _e('Priority has a default value of:', 'sim-com'); ?> <?php _e(' normal', 'sim-com'); ?>. <?php _e('Priority is a required field therefore must be assigned to a value', 'sim-com'); ?>.<div class="taxdef-block"><p><?php _e('The following are the preset values and value descriptions for <b>Priority:</b>', 'sim-com'); ?></p>
<table class="table tax-table form-table"><tr><td><?php _e('Critical', 'sim-com'); ?></td>
<td><?php _e('Critical bugs either render a system unusable (not being able to create content or upgrade between versions, blocks not displaying, and the like), cause loss of data, or expose security vulnerabilities. These bugs are to be fixed immediately.', 'sim-com'); ?></td>
</tr>
<tr>
<td><?php _e('Major', 'sim-com'); ?></td>
<td><?php _e('Issues which have significant repercussions but do not render the whole system unusable are marked major. An example would be a PHP error which is only triggered under rare circumstances or which affects only a small percentage of all users. These issues are prioritized in the current development release and backported to stable releases where applicable. Major issues do not block point releases.', 'sim-com'); ?></td>
</tr>
<tr>
<td><?php _e('Normal', 'sim-com'); ?></td>
<td><?php _e('Bugs that affect one piece of functionality are normal priority. An example would be the category filter not working on the database log screen. This is a self-contained bug and does not impact the overall functionality of the software.', 'sim-com'); ?></td>
</tr>
<tr>
<td><?php _e('Minor', 'sim-com'); ?></td>
<td><?php _e('Minor priority is most often used for cosmetic issues that don\'t inhibit the functionality or main purpose of the project, such as correction of typos in code comments or whitespace issues.', 'sim-com'); ?></td>
</tr>
</table>
</div></td>
</tr>
<tr>
<th><?php _e('Status', 'sim-com'); ?></th>

<td><?php _e('Sets the current status of an issue. Status accepts multiple values like tags', 'sim-com'); ?>. <?php _e('Status has a default value of:', 'sim-com'); ?> <?php _e(' open', 'sim-com'); ?>. <?php _e('Status is a required field therefore must be assigned to a value', 'sim-com'); ?>.<div class="taxdef-block"><p><?php _e('The following are the preset values and value descriptions for <b>Status:</b>', 'sim-com'); ?></p>
<table class="table tax-table form-table"><tr><td><?php _e('Open', 'sim-com'); ?></td>
<td><?php _e('This issue is in the initial state, ready for the assignee to start work on it.', 'sim-com'); ?></td>
</tr>
<tr>
<td><?php _e('In Progress', 'sim-com'); ?></td>
<td><?php _e('This issue is being actively worked on at the moment.', 'sim-com'); ?></td>
</tr>
<tr>
<td><?php _e('Reopened', 'sim-com'); ?></td>
<td><?php _e('This issue was once \'Resolved\' or \'Closed\', but is now being re-visited, e.g. an issue with a Resolution of \'Cannot Reproduce\' is Reopened when more information becomes available and the issue becomes reproducible. The next issue states are either marked In Progress, Resolved or Closed.', 'sim-com'); ?></td>
</tr>
<tr>
<td><?php _e('Closed', 'sim-com'); ?></td>
<td><?php _e('This issue is complete.', 'sim-com'); ?></td>
</tr>
<tr>
<td><?php _e('Resolved - Fixed', 'sim-com'); ?></td>
<td><?php _e('A fix for this issue has been implemented.', 'sim-com'); ?></td>
</tr>
<tr>
<td><?php _e('Resolved - Won\'t Fix', 'sim-com'); ?></td>
<td><?php _e('This issue will not be fixed, e.g. it may no longer be relevant.', 'sim-com'); ?></td>
</tr>
<tr>
<td><?php _e('Resolved - Duplicate', 'sim-com'); ?></td>
<td><?php _e('This issue is a duplicate of an existing issue. It is recommended you create a link to the duplicated issue by creating a related issue connection.', 'sim-com'); ?></td>
</tr>
<tr>
<td><?php _e('Resolved - Incomplete', 'sim-com'); ?></td>
<td><?php _e('There is not enough information to work on this issue.', 'sim-com'); ?></td>
</tr>
<tr>
<td><?php _e('Resolved - CNR', 'sim-com'); ?></td>
<td><?php _e('This issue could not be reproduced at this time, or not enough information was available to reproduce the issue. If more information becomes available, reopen the issue.', 'sim-com'); ?></td>
</tr>
</table>
</div></td>
</tr>
<tr>
<th><?php _e('Tag', 'sim-com'); ?></th>

<td><?php _e('Allows to tag issues to further classify or group related issues. Tag accepts multiple values like tags', 'sim-com'); ?>. <?php _e('Tag does not have a default value', 'sim-com'); ?>.<div class="taxdef-block"><p><?php _e('There are no preset values for <b>Tag:</b>', 'sim-com'); ?></p></div></td>
</tr>
<tr>
<th><?php _e('Operating System', 'sim-com'); ?></th>

<td><?php _e('Sets the operating system(s) that an issue may be reproduced in. Operating System accepts multiple values like tags', 'sim-com'); ?>. <?php _e('Operating System does not have a default value', 'sim-com'); ?>.<div class="taxdef-block"><p><?php _e('The following are the preset values for <b>Operating System:</b>', 'sim-com'); ?></p><p class="taxdef-values"><?php _e('Windows 8 (32-bit and 64-bit)', 'sim-com'); ?>, <?php _e('Windows 7 (32-bit and 64-bit)', 'sim-com'); ?>, <?php _e('Windows Vista (32-bit and 64-bit)', 'sim-com'); ?>, <?php _e('Windows XP (32-bit and 64-bit)', 'sim-com'); ?>, <?php _e('Windows Server 2008 R2 (64-bit)', 'sim-com'); ?>, <?php _e('Windows Server 2008 (32-bit and 64-bit)', 'sim-com'); ?>, <?php _e('Windows Server 2003 (32-bit and 64-bit)', 'sim-com'); ?>, <?php _e('Windows 2000 SP4', 'sim-com'); ?>, <?php _e('Mac OS X 10.8 Mountain Lion (32-bit and 64-bit)', 'sim-com'); ?>, <?php _e('Mac OS X 10.7 Lion (32-bit and 64-bit)', 'sim-com'); ?>, <?php _e('Mac OS X 10.6 Snow Leopard (32-bit)', 'sim-com'); ?>, <?php _e(' Mac OS X 10.5 Leopard', 'sim-com'); ?>, <?php _e('Mac OS X 10.4 Tiger', 'sim-com'); ?>, <?php _e('Linux (32-bit and 64-bit versions, kernel 2.6 or compatible)', 'sim-com'); ?></p></div></td>
</tr>
<tr>
<th><?php _e('Affected Projects', 'sim-com'); ?></th>
<td><?php _e('Allows to display and create connections with Projects', 'sim-com'); ?>. <?php _e('One instance of Issues can associated with many instances of Projects, and vice versa', 'sim-com'); ?>.  <?php _e('The relationship can be set up in the edit area of Issues using Affected Projects relationship box. ', 'sim-com'); ?> <?php _e('This relationship is required when publishing new Issues', 'sim-com'); ?>. </td>
</tr></table>
</div>
</div>
</li><li id="emd_project" class="control-section accordion-section">
<h3 class="accordion-section-title hndle" tabindex="1"><?php _e('Projects', 'sim-com'); ?></h3>
<div class="accordion-section-content">
<div class="inside">
<table class="form-table"><p class"lead"><?php _e('A project is a collection of related issues. Projects have a unique version number, specific start and end dates.', 'sim-com'); ?></p><tr>
<th><?php _e('Content', 'sim-com'); ?></th>
<td><?php _e(' Content does not have a default value. ', 'sim-com'); ?></td>
</tr><tr>
<th><?php _e('Name', 'sim-com'); ?></th>
<td><?php _e('Sets the name of a project. Name is a required field. Being a unique identifier, it uniquely distinguishes each instance of Project entity. Name does not have a default value. ', 'sim-com'); ?></td>
</tr><tr>
<th><?php _e('Version', 'sim-com'); ?></th>
<td><?php _e('Sets the version number of a project. Version is a required field. Being a unique identifier, it uniquely distinguishes each instance of Project entity. Version has a default value of <b>V1.0.0</b>.', 'sim-com'); ?></td>
</tr><tr>
<th><?php _e('Start Date', 'sim-com'); ?></th>
<td><?php _e('Sets the start date of a project. Start Date is a required field. Start Date does not have a default value. ', 'sim-com'); ?></td>
</tr><tr>
<th><?php _e('Target End Date', 'sim-com'); ?></th>
<td><?php _e('Sets the targeted end date of a project. Target End Date does not have a default value. ', 'sim-com'); ?></td>
</tr><tr>
<th><?php _e('Actual End Date', 'sim-com'); ?></th>
<td><?php _e('Sets the actual end date of a project. Actual End Date does not have a default value. ', 'sim-com'); ?></td>
</tr><tr>
<th><?php _e('Documents', 'sim-com'); ?></th>
<td><?php _e('Allows to upload project related files. Documents does not have a default value. ', 'sim-com'); ?></td>
</tr><tr>
<th><?php _e('Priority', 'sim-com'); ?></th>

<td><?php _e('Sets the current priority of a project. Priority accepts multiple values like tags', 'sim-com'); ?>. <?php _e('Priority has a default value of:', 'sim-com'); ?> <?php _e(' medium', 'sim-com'); ?>. <?php _e('Priority is a required field therefore must be assigned to a value', 'sim-com'); ?>.<div class="taxdef-block"><p><?php _e('The following are the preset values for <b>Priority:</b>', 'sim-com'); ?></p><p class="taxdef-values"><?php _e('Low', 'sim-com'); ?>, <?php _e('Medium', 'sim-com'); ?>, <?php _e('High', 'sim-com'); ?></p></div></td>
</tr>
<tr>
<th><?php _e('Status', 'sim-com'); ?></th>

<td><?php _e('Sets the current status of a project. Status accepts multiple values like tags', 'sim-com'); ?>. <?php _e('Status has a default value of:', 'sim-com'); ?> <?php _e(' draft', 'sim-com'); ?>. <?php _e('Status is a required field therefore must be assigned to a value', 'sim-com'); ?>.<div class="taxdef-block"><p><?php _e('The following are the preset values for <b>Status:</b>', 'sim-com'); ?></p><p class="taxdef-values"><?php _e('Draft', 'sim-com'); ?>, <?php _e('In Review', 'sim-com'); ?>, <?php _e('Published', 'sim-com'); ?>, <?php _e('In Process', 'sim-com'); ?></p></div></td>
</tr>
<tr>
<th><?php _e('Project Issues', 'sim-com'); ?></th>
<td><?php _e('Allows to display and create connections with Issues', 'sim-com'); ?>. <?php _e('One instance of Projects can associated with many instances of Issues, and vice versa', 'sim-com'); ?>.  <?php _e('The relationship can be set up in the edit area of Issues using Affected Projects relationship box', 'sim-com'); ?>. <?php _e('This relationship is required when publishing new Projects', 'sim-com'); ?>. </td>
</tr></table>
</div>
</div>
</li>
</ul>
</div>
<?php
}
