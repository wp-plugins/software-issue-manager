<?php
/**
 * Setup and Process submit and search forms
 * @package SIM_COM
 * @version 1.1.0
 * @since WPAS 4.0
 */
if (!defined('ABSPATH')) exit;
if (is_admin()) {
	add_action('wp_ajax_emd_check_unique', 'emd_check_unique');
	add_action('wp_ajax_nopriv_emd_check_unique', 'emd_check_unique');
}
add_action('wp_loaded', 'sim_com_form_shortcodes');
/**
 * Start session and setup upload idr and current user id
 * @since WPAS 4.0
 *
 */
function sim_com_form_shortcodes() {
	global $current_user, $current_user_id, $file_upload_dir;
	get_currentuserinfo();
	$current_user_id = $current_user->ID;
	if (!isset($current_user_id) || $current_user_id == '') {
		$current_user_id = 'guest';
	}
	$upload_dir = wp_upload_dir();
	$file_upload_dir = $upload_dir['basedir'] . '/wpas-files/' . $current_user_id;
	if (!session_id()) {
		session_start();
	}
}
add_shortcode('issue_entry', 'sim_com_process_issue_entry');
add_shortcode('issue_search', 'sim_com_process_issue_search');
/**
 * Set each form field(attr,tax and rels) and render form
 *
 * @since WPAS 4.0
 *
 * @return object $form
 */
function sim_com_set_issue_search() {
	global $file_upload_dir;
	$show_captcha = 1;
	if (is_user_logged_in()) {
		$show_captcha = 0;
	}
	require_once SIM_COM_PLUGIN_DIR . '/assets/ext/zebraform/Zebra_Form.php';
	$form = new Zebra_Form('issue_search', 0, 'POST', '', array(
		'class' => 'form-container wpas-form wpas-form-stacked'
	));
	$form->form_properties['csrf_storage_method'] = false;
	//text
	$form->add('label', 'label_emd_iss_id', 'emd_iss_id', 'ID', array(
		'class' => 'control-label'
	));
	$obj = $form->add('text', 'emd_iss_id', '', array(
		'class' => 'input-md form-control',
		'placeholder' => __('ID', 'sim-com')
	));
	$obj->set_rule(array());
	//date
	$form->add('label', 'label_emd_iss_due_date', 'emd_iss_due_date', 'Due Date', array(
		'class' => 'control-label'
	));
	$obj = $form->add('date', 'emd_iss_due_date', '', array(
		'class' => 'input-md form-control',
		'placeholder' => __('Due Date', 'sim-com')
	));
	$obj->format('m-d-Y');
	$obj->set_rule(array(
		'date' => array(
			'error',
			__('Due Date: Please enter a valid date format', 'sim-com')
		) ,
	));
	$form->add('label', 'label_issue_cat', 'issue_cat', 'Category', array(
		'class' => 'control-label'
	));
	$obj = $form->add('selectadv', 'issue_cat', 'bug', array(
		'class' => 'input-md'
	) , '', '{"allowClear":true,"placeholder":"' . __("Please Select", "sim-com") . '","placeholderOption":"first"}');
	//get taxonomy values
	$txn_arr = Array();
	$txn_arr[''] = 'Please select';
	$txn_obj = get_terms('issue_cat', array(
		'hide_empty' => 0
	));
	foreach ($txn_obj as $txn) {
		$txn_arr[$txn->slug] = $txn->name;
	}
	$obj->add_options($txn_arr);
	$obj->set_rule(array());
	$form->add('label', 'label_issue_priority', 'issue_priority', 'Priority', array(
		'class' => 'control-label'
	));
	$obj = $form->add('selectadv', 'issue_priority', 'normal', array(
		'class' => 'input-md'
	) , '', '{"allowClear":true,"placeholder":"' . __("Please Select", "sim-com") . '","placeholderOption":"first"}');
	//get taxonomy values
	$txn_arr = Array();
	$txn_arr[''] = 'Please select';
	$txn_obj = get_terms('issue_priority', array(
		'hide_empty' => 0
	));
	foreach ($txn_obj as $txn) {
		$txn_arr[$txn->slug] = $txn->name;
	}
	$obj->add_options($txn_arr);
	$obj->set_rule(array());
	$form->add('label', 'label_issue_status', 'issue_status', 'Status', array(
		'class' => 'control-label'
	));
	$obj = $form->add('selectadv', 'issue_status', 'open', array(
		'class' => 'input-md'
	) , '', '{"allowClear":true,"placeholder":"' . __("Please Select", "sim-com") . '","placeholderOption":"first"}');
	//get taxonomy values
	$txn_arr = Array();
	$txn_arr[''] = 'Please select';
	$txn_obj = get_terms('issue_status', array(
		'hide_empty' => 0
	));
	foreach ($txn_obj as $txn) {
		$txn_arr[$txn->slug] = $txn->name;
	}
	$obj->add_options($txn_arr);
	$obj->set_rule(array());
	$form->add('label', 'label_rel_project_issues', 'rel_project_issues', __('Affected Projects', 'sim-com') , array(
		'class' => 'control-label'
	));
	$obj = $form->add('selectadv', 'rel_project_issues[]', __('Please select', 'sim-com') , array(
		'multiple' => 'multiple',
		'class' => 'input-md'
	) , '', '{"allowClear":true,"placeholder":"' . __("Please Select", "sim-com") . '","placeholderOption":"first"}');
	//get entity values
	$rel_ent_arr = Array();
	$rel_ent_args = Array(
		'post_type' => 'emd_project',
		'numberposts' => - 1
	);
	$rel_ent_pids = get_posts($rel_ent_args);
	$meta_keys = Array(
		'emd_prj_name',
		'emd_prj_version'
	);
	if (!empty($meta_keys) && !empty($rel_ent_pids)) {
		foreach ($rel_ent_pids as $my_ent_pid) {
			$rel_value = "";
			foreach ($meta_keys as $mymeta_key) {
				$rel_value.= get_post_meta($my_ent_pid->ID, $mymeta_key, true) . " - ";
			}
			$rel_value = rtrim($rel_value, " - ");
			$rel_ent_arr[$my_ent_pid->ID] = $rel_value;
		}
	}
	$obj->add_options($rel_ent_arr);
	$obj->set_rule(array());
	$form->assign('show_captcha', $show_captcha);
	if ($show_captcha == 1) {
		//Captcha
		$form->add('captcha', 'captcha_image', 'captcha_code', '', '<span style="font-weight:bold;" class="refresh-txt">Refresh</span>', 'refcapt');
		$form->add('label', 'label_captcha_code', 'captcha_code', __('Please enter the characters with black color.', 'sim-com'));
		$obj = $form->add('text', 'captcha_code', '', array(
			'placeholder' => __('Code', 'sim-com')
		));
		$obj->set_rule(array(
			'required' => array(
				'error',
				__('Captcha is required', 'sim-com')
			) ,
			'captcha' => array(
				'error',
				__('Characters from captcha image entered incorrectly!', 'sim-com')
			)
		));
	}
	$form->add('submit', 'singlebutton_issue_search', '' . __('Search Issues', 'sim-com') . ' ', array(
		'class' => 'wpas-button wpas-juibutton-secondary wpas-button-large '
	));
	return $form;
}
/**
 * Process each form and show error or success
 *
 * @since WPAS 4.0
 *
 * @return html
 */
function sim_com_process_issue_search() {
	$show_form = 1;
	$access_views = get_option('sim_com_access_views', Array());
	if (!current_user_can('view_issue_search') && !empty($access_views['forms']) && in_array('issue_search', $access_views['forms'])) {
		$show_form = 0;
	}
	if ($show_form == 1) {
		$noresult_msg = __('Your search returned no results.', 'sim-com');
		return emd_search_php_form('issue_search', 'sim_com', 'emd_issue', $noresult_msg, 'sc_issues');
	} else {
		return "<div class='alert alert-info not-authorized'>" . __('<p>You are not allowed to access to this area. Please contact the site administrator.</p>', 'sim-com') . "</div>";
	}
}
/**
 * Set each form field(attr,tax and rels) and render form
 *
 * @since WPAS 4.0
 *
 * @return object $form
 */
function sim_com_set_issue_entry() {
	global $file_upload_dir;
	$show_captcha = 1;
	if (is_user_logged_in()) {
		$show_captcha = 0;
	}
	require_once SIM_COM_PLUGIN_DIR . '/assets/ext/zebraform/Zebra_Form.php';
	$form = new Zebra_Form('issue_entry', 0, 'POST', '', array(
		'class' => 'form-container wpas-form wpas-form-stacked'
	));
	//text
	$form->add('label', 'label_blt_title', 'blt_title', 'Title', array(
		'class' => 'control-label'
	));
	$obj = $form->add('text', 'blt_title', '', array(
		'class' => 'input-md form-control',
		'placeholder' => __('Title', 'sim-com')
	));
	$obj->set_rule(array(
		'required' => array(
			'error',
			__('Title is required', 'sim-com')
		) ,
	));
	//wysiwyg
	$form->add('label', 'label_blt_content', 'blt_content', 'Content', array(
		'class' => 'control-label'
	));
	$obj = $form->add('wysiwyg', 'blt_content', '', array(
		'placeholder' => __('Enter text ...', 'sim-com') ,
		'style' => 'width: 100%; height: 200px',
		'class' => 'wyrj'
	));
	$obj->set_rule(array());
	//date
	$form->add('label', 'label_emd_iss_due_date', 'emd_iss_due_date', 'Due Date', array(
		'class' => 'control-label'
	));
	$obj = $form->add('date', 'emd_iss_due_date', '', array(
		'class' => 'input-md form-control',
		'placeholder' => __('Due Date', 'sim-com')
	));
	$obj->format('m-d-Y');
	$obj->set_rule(array(
		'date' => array(
			'error',
			__('Due Date: Please enter a valid date format', 'sim-com')
		) ,
	));
	$form->add('label', 'label_issue_priority', 'issue_priority', 'Priority', array(
		'class' => 'control-label'
	));
	$obj = $form->add('selectadv', 'issue_priority', 'normal', array(
		'class' => 'input-md'
	) , '', '{"allowClear":true,"placeholder":"' . __("Please Select", "sim-com") . '","placeholderOption":"first"}');
	//get taxonomy values
	$txn_arr = Array();
	$txn_arr[''] = 'Please select';
	$txn_obj = get_terms('issue_priority', array(
		'hide_empty' => 0
	));
	foreach ($txn_obj as $txn) {
		$txn_arr[$txn->slug] = $txn->name;
	}
	$obj->add_options($txn_arr);
	$obj->set_rule(array(
		'required' => array(
			'error',
			__('Priority is required!', 'sim-com')
		) ,
	));
	$form->add('label', 'label_issue_cat', 'issue_cat', 'Category', array(
		'class' => 'control-label'
	));
	$obj = $form->add('selectadv', 'issue_cat', 'bug', array(
		'class' => 'input-md'
	) , '', '{"allowClear":true,"placeholder":"' . __("Please Select", "sim-com") . '","placeholderOption":"first"}');
	//get taxonomy values
	$txn_arr = Array();
	$txn_arr[''] = 'Please select';
	$txn_obj = get_terms('issue_cat', array(
		'hide_empty' => 0
	));
	foreach ($txn_obj as $txn) {
		$txn_arr[$txn->slug] = $txn->name;
	}
	$obj->add_options($txn_arr);
	$obj->set_rule(array(
		'required' => array(
			'error',
			__('Category is required!', 'sim-com')
		) ,
	));
	$form->add('label', 'label_issue_status', 'issue_status', 'Status', array(
		'class' => 'control-label'
	));
	$obj = $form->add('selectadv', 'issue_status', 'open', array(
		'class' => 'input-md'
	) , '', '{"allowClear":true,"placeholder":"' . __("Please Select", "sim-com") . '","placeholderOption":"first"}');
	//get taxonomy values
	$txn_arr = Array();
	$txn_arr[''] = 'Please select';
	$txn_obj = get_terms('issue_status', array(
		'hide_empty' => 0
	));
	foreach ($txn_obj as $txn) {
		$txn_arr[$txn->slug] = $txn->name;
	}
	$obj->add_options($txn_arr);
	$obj->set_rule(array(
		'required' => array(
			'error',
			__('Status is required!', 'sim-com')
		) ,
	));
	$form->add('label', 'label_issue_tag', 'issue_tag', 'Tag', array(
		'class' => 'control-label'
	));
	$obj = $form->add('selectadv', 'issue_tag[]', 'Please Select', array(
		'multiple' => 'multiple',
		'class' => 'input-md'
	) , '', '{"allowClear":true,"placeholder":"' . __("Please Select", "sim-com") . '","placeholderOption":"first"}');
	//get taxonomy values
	$txn_arr = Array();
	$txn_obj = get_terms('issue_tag', array(
		'hide_empty' => 0
	));
	foreach ($txn_obj as $txn) {
		$txn_arr[$txn->slug] = $txn->name;
	}
	$obj->add_options($txn_arr);
	$obj->set_rule(array());
	$form->add('label', 'label_browser', 'browser', 'Browser', array(
		'class' => 'control-label'
	));
	$obj = $form->add('selectadv', 'browser[]', 'Please Select', array(
		'multiple' => 'multiple',
		'class' => 'input-md'
	) , '', '{"allowClear":true,"placeholder":"' . __("Please Select", "sim-com") . '","placeholderOption":"first"}');
	//get taxonomy values
	$txn_arr = Array();
	$txn_obj = get_terms('browser', array(
		'hide_empty' => 0
	));
	foreach ($txn_obj as $txn) {
		$txn_arr[$txn->slug] = $txn->name;
	}
	$obj->add_options($txn_arr);
	$obj->set_rule(array());
	$form->add('label', 'label_operating_system', 'operating_system', 'Operating System', array(
		'class' => 'control-label'
	));
	$obj = $form->add('selectadv', 'operating_system[]', 'Please Select', array(
		'multiple' => 'multiple',
		'class' => 'input-md'
	) , '', '{"allowClear":true,"placeholder":"' . __("Please Select", "sim-com") . '","placeholderOption":"first"}');
	//get taxonomy values
	$txn_arr = Array();
	$txn_obj = get_terms('operating_system', array(
		'hide_empty' => 0
	));
	foreach ($txn_obj as $txn) {
		$txn_arr[$txn->slug] = $txn->name;
	}
	$obj->add_options($txn_arr);
	$obj->set_rule(array());
	//file
	$obj = $form->add('file', 'emd_iss_document', '');
	$obj->set_rule(array(
		'upload' => array(
			$file_upload_dir,
			true,
			'error',
			'File could not be uploaded.'
		) ,
	));
	$form->add('label', 'label_rel_project_issues', 'rel_project_issues', __('Affected Projects', 'sim-com') , array(
		'class' => 'control-label'
	));
	$obj = $form->add('selectadv', 'rel_project_issues[]', __('Please select', 'sim-com') , array(
		'multiple' => 'multiple',
		'class' => 'input-md'
	) , '', '{"allowClear":true,"placeholder":"' . __("Please Select", "sim-com") . '","placeholderOption":"first"}');
	//get entity values
	$rel_ent_arr = Array();
	$rel_ent_args = Array(
		'post_type' => 'emd_project',
		'numberposts' => - 1
	);
	$rel_ent_pids = get_posts($rel_ent_args);
	$meta_keys = Array(
		'emd_prj_name',
		'emd_prj_version'
	);
	if (!empty($meta_keys) && !empty($rel_ent_pids)) {
		foreach ($rel_ent_pids as $my_ent_pid) {
			$rel_value = "";
			foreach ($meta_keys as $mymeta_key) {
				$rel_value.= get_post_meta($my_ent_pid->ID, $mymeta_key, true) . " - ";
			}
			$rel_value = rtrim($rel_value, " - ");
			$rel_ent_arr[$my_ent_pid->ID] = $rel_value;
		}
	}
	$obj->add_options($rel_ent_arr);
	$obj->set_rule(array(
		'required' => array(
			'error',
			__('Affected Projects is required!', 'sim-com')
		)
	));
	//hidden_func
	$emd_iss_id = emd_get_hidden_func('unique_id');
	$form->add('hidden', 'emd_iss_id', $emd_iss_id);
	//hidden
	$obj = $form->add('hidden', 'wpas_form_name', 'issue_entry');
	//hidden_func
	$wpas_form_submitted_by = emd_get_hidden_func('user_login');
	$form->add('hidden', 'wpas_form_submitted_by', $wpas_form_submitted_by);
	//hidden_func
	$wpas_form_submitted_ip = emd_get_hidden_func('user_ip');
	$form->add('hidden', 'wpas_form_submitted_ip', $wpas_form_submitted_ip);
	$form->assign('show_captcha', $show_captcha);
	if ($show_captcha == 1) {
		//Captcha
		$form->add('captcha', 'captcha_image', 'captcha_code', '', '<span style="font-weight:bold;" class="refresh-txt">Refresh</span>', 'refcapt');
		$form->add('label', 'label_captcha_code', 'captcha_code', __('Please enter the characters with black color.', 'sim-com'));
		$obj = $form->add('text', 'captcha_code', '', array(
			'placeholder' => __('Code', 'sim-com')
		));
		$obj->set_rule(array(
			'required' => array(
				'error',
				__('Captcha is required', 'sim-com')
			) ,
			'captcha' => array(
				'error',
				__('Characters from captcha image entered incorrectly!', 'sim-com')
			)
		));
	}
	$form->add('submit', 'singlebutton_issue_entry', '' . __('Create Issue', 'sim-com') . ' ', array(
		'class' => 'wpas-button wpas-juibutton-primary wpas-button-large '
	));
	return $form;
}
/**
 * Process each form and show error or success
 *
 * @since WPAS 4.0
 *
 * @return html
 */
function sim_com_process_issue_entry() {
	$show_form = 1;
	$access_views = get_option('sim_com_access_views', Array());
	if (!current_user_can('view_issue_entry') && !empty($access_views['forms']) && in_array('issue_entry', $access_views['forms'])) {
		$show_form = 0;
	}
	if ($show_form == 1) {
		return emd_submit_php_form('issue_entry', 'sim_com', 'emd_issue', 'publish', 'draft', 'Thanks for your submission.', 'There has been an error when submitting your entry. Please contact the site administrator.', 0, 1);
	} else {
		return "<div class='alert alert-info not-authorized'>" . __('You are not allowed to access to this area. Please contact the site administrator.', 'sim-com') . "</div>";
	}
}
