
<div class="form-alerts">
<?php
echo (isset($zf_error) ? $zf_error : (isset($error) ? $error : ''));
?>
</div>
<!-- issue_entry Form Description -->
<div class="issue_entry_desc">
<?php _e('<p>Use this <em>form</em> to file <em>issues</em> about projects.</p>', 'sim-com'); ?>
</div>
<fieldset>
<div class="issue_entry-btn-fields">
<!-- issue_entry Form Attributes -->
<div class="issue_entry_attributes">
<div id="row1" class="row">
<!-- text input-->
<div class="col-md-12 woptdiv">
<div class="form-group">
<label id="label_blt_title" class="control-label" for="blt_title">
<?php _e('Title', 'sim-com'); ?>
<span style="display: inline-flex;right: 0px; position: relative; top:-6px;">
<a href="#" data-html="true" data-toggle="tooltip" title="<?php _e('Title field is required', 'sim-com'); ?>" id="info_blt_title" class="helptip">
<span class="field-icons icons-required"></span>
</a>
</span>
</label>
<?php echo $blt_title; ?>
</div>
</div>
</div>
<div id="row2" class="row">
<!-- wysiwyg input-->
<div class="col-md-12">
<div class="form-group">
<label id="label_blt_content" class="control-label" for="blt_content">
<?php _e('Content', 'sim-com'); ?>
<span style="display: inline-flex;right: 0px; position: relative; top:-6px;">
</span>
</label>
<?php echo $blt_content; ?>
</div>
</div>
</div>
<div id="row3" class="row">
<!-- date-->
<div class="col-md-12 woptdiv">
<div class="form-group">
<label id="label_emd_iss_due_date" class="control-label" for="emd_iss_due_date">
<?php _e('Due Date', 'sim-com'); ?>
<span style="display: inline-flex;right: 0px; position: relative; top:-6px;"> <a data-html="true" href="#" data-toggle="tooltip" title="<?php _e('Sets the targeted resolution date for an issue.', 'sim-com'); ?>" id="info_emd_iss_due_date" class="helptip"><span class="field-icons icons-help"></span></a>
 </span>
</label>
<?php echo $emd_iss_due_date; ?>
</div>
</div>
</div>
<div id="row4" class="row">
<!-- Taxonomy input-->
<div class="col-md-12">
<div class="form-group">
<label id="label_issue_priority" class="control-label" for="issue_priority">
<?php _e('Priority', 'sim-com'); ?>
<span style="display: inline-flex;right: 0px; position: relative; top:-6px;">
<a data-html="true" href="#" data-toggle="tooltip" title="<?php _e('Sets the priority level assigned to an issue.', 'sim-com'); ?>" id="info_issue_priority" class="helptip"><span class="field-icons icons-help"></span></a>
<a href="#" data-html="true" data-toggle="tooltip" title="<?php _e('Priority field is required', 'sim-com'); ?>" id="info_issue_priority" class="helptip">
<span class="field-icons icons-required"></span>
</a>
</span>
</label>
<?php echo $issue_priority; ?>
</div>
</div>
</div>
<div id="row5" class="row">
<!-- Taxonomy input-->
<div class="col-md-12">
<div class="form-group">
<label id="label_issue_cat" class="control-label" for="issue_cat">
<?php _e('Category', 'sim-com'); ?>
<span style="display: inline-flex;right: 0px; position: relative; top:-6px;">
<a data-html="true" href="#" data-toggle="tooltip" title="<?php _e('Sets the category that an issue belongs to.', 'sim-com'); ?>" id="info_issue_cat" class="helptip"><span class="field-icons icons-help"></span></a>
<a href="#" data-html="true" data-toggle="tooltip" title="<?php _e('Category field is required', 'sim-com'); ?>" id="info_issue_cat" class="helptip">
<span class="field-icons icons-required"></span>
</a>
</span>
</label>
<?php echo $issue_cat; ?>
</div>
</div>
</div>
<div id="row6" class="row">
<!-- Taxonomy input-->
<div class="col-md-12">
<div class="form-group">
<label id="label_issue_status" class="control-label" for="issue_status">
<?php _e('Status', 'sim-com'); ?>
<span style="display: inline-flex;right: 0px; position: relative; top:-6px;">
<a data-html="true" href="#" data-toggle="tooltip" title="<?php _e('Sets the current status of an issue.', 'sim-com'); ?>" id="info_issue_status" class="helptip"><span class="field-icons icons-help"></span></a>
<a href="#" data-html="true" data-toggle="tooltip" title="<?php _e('Status field is required', 'sim-com'); ?>" id="info_issue_status" class="helptip">
<span class="field-icons icons-required"></span>
</a>
</span>
</label>
<?php echo $issue_status; ?>
</div>
</div>
</div>
<div id="row7" class="row">
<!-- Taxonomy input-->
<div class="col-md-12">
<div class="form-group">
<label id="label_issue_tag" class="control-label" for="issue_tag">
<?php _e('Tag', 'sim-com'); ?>
<span style="display: inline-flex;right: 0px; position: relative; top:-6px;">
<a data-html="true" href="#" data-toggle="tooltip" title="<?php _e('Allows to tag issues to further classify or group related issues.', 'sim-com'); ?>" id="info_issue_tag" class="helptip"><span class="field-icons icons-help"></span></a>
</span>
</label>
<?php echo $issue_tag; ?>
</div>
</div>
</div>
<div id="row8" class="row">
<!-- Taxonomy input-->
<div class="col-md-12">
<div class="form-group">
<label id="label_browser" class="control-label" for="browser">
<?php _e('Browser', 'sim-com'); ?>
<span style="display: inline-flex;right: 0px; position: relative; top:-6px;">
<a data-html="true" href="#" data-toggle="tooltip" title="<?php _e('Sets the browser version that an issue may be reproduced in.', 'sim-com'); ?>" id="info_browser" class="helptip"><span class="field-icons icons-help"></span></a>
</span>
</label>
<?php echo $browser; ?>
</div>
</div>
</div>
<div id="row9" class="row">
<!-- Taxonomy input-->
<div class="col-md-12">
<div class="form-group">
<label id="label_operating_system" class="control-label" for="operating_system">
<?php _e('Operating System', 'sim-com'); ?>
<span style="display: inline-flex;right: 0px; position: relative; top:-6px;">
<a data-html="true" href="#" data-toggle="tooltip" title="<?php _e('Sets the operating system(s) that an issue may be reproduced in.', 'sim-com'); ?>" id="info_operating_system" class="helptip"><span class="field-icons icons-help"></span></a>
</span>
</label>
<?php echo $operating_system; ?>
</div>
</div>
</div>
<div id="row10" class="row">
<!-- file input-->
<div class="col-md-12">
<?php _e('Documents', 'sim-com'); ?>
<a data-html="true" href="#" data-toggle="tooltip" title="<?php _e('Allows to upload files related to an issue.', 'sim-com'); ?>" id="info_emd_iss_document" class="helptip"><span class="field-icons icons-help"></span></a>
<div class="form-group">
<?php echo $emd_iss_document; ?>
</div>
</div>
</div>
<div id="row11" class="row">
<!-- HR-->
<hr>
</div>
<div id="row12" class="row">
<!-- rel-ent input-->
<div class="col-md-12">
<div class="form-group">
<label id="label_rel_project_issues" class="control-label" for="rel_project_issues">
<?php _e('Affected Projects', 'sim-com'); ?>
<span style="display: inline-flex;right: 0px; position: relative; top:-6px;">
<a data-html="true" href="#" data-toggle="tooltip" title="<?php _e('Allows to assign issue(s) to project(s) ,and vice versa.', 'sim-com'); ?>" id="info_project_issues" class="helptip"><span class="field-icons icons-help"></span></a>
<a href="#" data-html="true" data-toggle="tooltip" title="<?php _e('Affected Projects field is required', 'sim-com'); ?>" id="info_project_issues" class="helptip">
<span class="field-icons icons-required"></span>
</a>
</span>
</label>
<?php echo $rel_project_issues; ?>
</div>
</div>
</div>
 
 
 
 
</div><!--form-attributes-->
<?php if ($show_captcha == 1) { ?>
<div class="row">
<div class="col-xs-12">
<div id="captcha-group" class="form-group">
<?php echo $captcha_image; ?>
<label style="padding:0px;" id="label_captcha_code" class="control-label" for="captcha_code">
<a id="info_captcha_code_help" class="helptip" data-html="true" data-toggle="tooltip" href="#" title="<?php _e('Please enter the characters with black color in the image above.', 'sim-com'); ?>">
<span class="field-icons icons-help"></span>
</a>
<a id="info_captcha_code_req" class="helptip" title="<?php _e('Security Code field is required', 'sim-com'); ?>" data-toggle="tooltip" href="#">
<span class="field-icons icons-required"></span>
</a>
</label>
<?php echo $captcha_code; ?>
</div>
</div>
</div>
<?php
} ?>
<?php wp_nonce_field('issue_entry', 'issue_entry_nonce'); ?>
<input type="hidden" name="form_name" id="form_name" value="issue_entry">
<!-- Button -->
<div class="row">
<div class="col-md-12">
<div class="wpas-form-actions">
<?php echo $singlebutton_issue_entry; ?>
</div>
</div>
</div>
</div><!--form-btn-fields-->
</fieldset>