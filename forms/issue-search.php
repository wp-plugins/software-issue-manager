
<div class="form-alerts">
<?php
echo (isset($zf_error) ? $zf_error : (isset($error) ? $error : ''));
$form_list = get_option('sim_com_glob_forms_list');
$form_variables = $form_list['issue_search'];
?>
</div>
<!-- issue_search Form Description -->
<div class="issue_search_desc">
<?php _e('<p>Use this <em>form</em> to search project issues.</p>', 'sim-com'); ?>
</div>
<fieldset>
<div class="issue_search-btn-fields">
<!-- issue_search Form Attributes -->
<div class="issue_search_attributes">
<div id="row13" class="row">
<!-- text input-->
<?php if ($form_variables['emd_iss_id']['show'] == 1) { ?>
<div class="col-md-<?php echo $form_variables['emd_iss_id']['size']; ?> woptdiv">
<div class="form-group">
<label id="label_emd_iss_id" class="control-label" for="emd_iss_id">
<?php _e('ID', 'sim-com'); ?>
<span style="display: inline-flex;right: 0px; position: relative; top:-6px;">
<a data-html="true" href="#" data-toggle="tooltip" title="<?php _e('Sets a unique identifier for an issue.', 'sim-com'); ?>" id="info_emd_iss_id" class="helptip"><span class="field-icons icons-help"></span></a>
</span>
</label>
<?php echo $emd_iss_id; ?>
</div>
</div>
<?php
} ?>
</div>
<div id="row14" class="row">
<!-- date-->
<?php if ($form_variables['emd_iss_due_date']['show'] == 1) { ?>
<div class="col-md-<?php echo $form_variables['emd_iss_due_date']['size']; ?> woptdiv">
<div class="form-group">
<label id="label_emd_iss_due_date" class="control-label" for="emd_iss_due_date">
<?php _e('Due Date', 'sim-com'); ?>
<span style="display: inline-flex;right: 0px; position: relative; top:-6px;"> <a data-html="true" href="#" data-toggle="tooltip" title="<?php _e('Sets the targeted resolution date for an issue.', 'sim-com'); ?>" id="info_emd_iss_due_date" class="helptip"><span class="field-icons icons-help"></span></a>
 </span>
</label>
<?php echo $emd_iss_due_date; ?>
</div>
</div>
<?php
} ?>
</div>
<div id="row15" class="row">
<!-- Taxonomy input-->
<?php if ($form_variables['issue_cat']['show'] == 1) { ?>
<div class="col-md-<?php echo $form_variables['issue_cat']['size']; ?>">
<div class="form-group">
<label id="label_issue_cat" class="control-label" for="issue_cat">
<?php _e('Category', 'sim-com'); ?>
<span style="display: inline-flex;right: 0px; position: relative; top:-6px;">
<a data-html="true" href="#" data-toggle="tooltip" title="<?php _e('Sets the category that an issue belongs to.', 'sim-com'); ?>" id="info_issue_cat" class="helptip"><span class="field-icons icons-help"></span></a>
</span>
</label>
<?php echo $issue_cat; ?>
</div>
</div>
<?php
} ?>
</div>
<div id="row16" class="row">
<!-- Taxonomy input-->
<?php if ($form_variables['issue_priority']['show'] == 1) { ?>
<div class="col-md-<?php echo $form_variables['issue_priority']['size']; ?>">
<div class="form-group">
<label id="label_issue_priority" class="control-label" for="issue_priority">
<?php _e('Priority', 'sim-com'); ?>
<span style="display: inline-flex;right: 0px; position: relative; top:-6px;">
<a data-html="true" href="#" data-toggle="tooltip" title="<?php _e('Sets the priority level assigned to an issue.', 'sim-com'); ?>" id="info_issue_priority" class="helptip"><span class="field-icons icons-help"></span></a>
</span>
</label>
<?php echo $issue_priority; ?>
</div>
</div>
<?php
} ?>
</div>
<div id="row17" class="row">
<!-- Taxonomy input-->
<?php if ($form_variables['issue_status']['show'] == 1) { ?>
<div class="col-md-<?php echo $form_variables['issue_status']['size']; ?>">
<div class="form-group">
<label id="label_issue_status" class="control-label" for="issue_status">
<?php _e('Status', 'sim-com'); ?>
<span style="display: inline-flex;right: 0px; position: relative; top:-6px;">
<a data-html="true" href="#" data-toggle="tooltip" title="<?php _e('Sets the current status of an issue.', 'sim-com'); ?>" id="info_issue_status" class="helptip"><span class="field-icons icons-help"></span></a>
</span>
</label>
<?php echo $issue_status; ?>
</div>
</div>
<?php
} ?>
</div>
<div id="row18" class="row">
<!-- rel-ent input-->
<?php if ($form_variables['rel_project_issues']['show'] == 1) { ?>
<div class="col-md-<?php echo $form_variables['rel_project_issues']['size']; ?>">
<div class="form-group">
<label id="label_rel_project_issues" class="control-label" for="rel_project_issues">
<?php _e('Affected Projects', 'sim-com'); ?>
<span style="display: inline-flex;right: 0px; position: relative; top:-6px;">
<a data-html="true" href="#" data-toggle="tooltip" title="<?php _e('Allows to assign issue(s) to project(s) ,and vice versa.', 'sim-com'); ?>" id="info_project_issues" class="helptip"><span class="field-icons icons-help"></span></a>
</span>
</label>
<?php echo $rel_project_issues; ?>
</div>
</div>
<?php
} ?>
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
<?php wp_nonce_field('issue_search', 'issue_search_nonce'); ?>
<input type="hidden" name="form_name" id="form_name" value="issue_search">
<!-- Button -->
<div class="row">
<div class="col-md-12">
<div class="wpas-form-actions">
<?php echo $singlebutton_issue_search; ?>
</div>
</div>
</div>
</div><!--form-btn-fields-->
</fieldset>