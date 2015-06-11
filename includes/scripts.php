<?php
/**
 * Enqueue Scripts Functions
 *
 * @package SIM_COM
 * @version 2.0.1
 * @since WPAS 4.0
 */
if (!defined('ABSPATH')) exit;
add_action('admin_enqueue_scripts', 'sim_com_load_admin_enq');
/**
 * Enqueue style and js for each admin entity pages and settings
 *
 * @since WPAS 4.0
 * @param string $hook
 *
 */
function sim_com_load_admin_enq($hook) {
	global $typenow;
	if ($hook == 'edit-tags.php') {
		return;
	}
	if ($hook == 'toplevel_page_sim_com' || $hook == 'sim-com_page_sim_com_notify' || $hook == 'sim-com_page_sim_com_settings') {
		wp_enqueue_script('accordion');
		return;
	} else if (in_array($hook, Array(
		'sim-com_page_sim_com_store',
		'sim-com_page_sim_com_designs',
		'sim-com_page_sim_com_support'
	))) {
		wp_enqueue_style('admin-tabs', SIM_COM_PLUGIN_URL . 'assets/css/admin-store.css');
		return;
	}
	if (in_array($typenow, Array(
		'emd_project',
		'emd_issue'
	))) {
		$theme_changer_enq = 1;
		$datetime_enq = 0;
		$date_enq = 0;
		$sing_enq = 0;
		$tab_enq = 0;
		if ($hook == 'post.php' || $hook == 'post-new.php') {
			$unique_vars['msg'] = __('Please enter a unique value.', 'sim-com');
			$unique_vars['reqtxt'] = __('required', 'sim-com');
			$unique_vars['app_name'] = 'sim_com';
			$ent_list = get_option('sim_com_ent_list');
			if (!empty($ent_list[$typenow])) {
				$unique_vars['keys'] = $ent_list[$typenow]['unique_keys'];
				if (!empty($ent_list[$typenow]['req_blt'])) {
					$unique_vars['req_blt_tax'] = $ent_list[$typenow]['req_blt'];
				}
			}
			$tax_list = get_option('sim_com_tax_list');
			if (!empty($tax_list[$typenow])) {
				foreach ($tax_list[$typenow] as $txn_name => $txn_val) {
					if ($txn_val['required'] == 1) {
						$unique_vars['req_blt_tax'][$txn_name] = Array(
							'hier' => $txn_val['hier'],
							'type' => $txn_val['type'],
							'label' => $txn_val['label'] . ' ' . __('Taxonomy', 'sim-com')
						);
					}
				}
			}
			wp_enqueue_script('unique_validate-js', SIM_COM_PLUGIN_URL . 'assets/js/unique_validate.js', array(
				'jquery',
				'jquery-validate'
			) , SIM_COM_VERSION, true);
			wp_localize_script("unique_validate-js", 'unique_vars', $unique_vars);
		}
		switch ($typenow) {
			case 'emd_issue':
				$date_enq = 1;
				$sing_enq = 1;
			break;
			case 'emd_project':
				$date_enq = 1;
				$sing_enq = 1;
			break;
		}
		if ($datetime_enq == 1) {
			wp_enqueue_script("jquery-ui-timepicker", SIM_COM_PLUGIN_URL . 'assets/ext/emd-meta-box/js/jqueryui/jquery-ui-timepicker-addon.js', array(
				'jquery-ui-datepicker',
				'jquery-ui-slider'
			) , SIM_COM_VERSION, true);
			$tab_enq = 1;
		} elseif ($date_enq == 1) {
			wp_enqueue_script("jquery-ui-datepicker");
			$tab_enq = 1;
		}
		if ($sing_enq == 1) {
			wp_enqueue_script('radiotax', SIM_COM_PLUGIN_URL . 'includes/admin/singletax/singletax.js', array(
				'jquery'
			) , SIM_COM_VERSION, true);
		}
		if ($tab_enq == 1) {
			wp_enqueue_style('jq-css', SIM_COM_PLUGIN_URL . 'assets/css/smoothness-jquery-ui.css');
		}
	}
}
add_action('wp_enqueue_scripts', 'sim_com_frontend_scripts');
/**
 * Enqueue style and js for each frontend entity pages and components
 *
 * @since WPAS 4.0
 *
 */
function sim_com_frontend_scripts() {
	$dir_url = SIM_COM_PLUGIN_URL;
	if (is_page()) {
		$grid_vars = Array();
		$local_vars['ajax_url'] = admin_url('admin-ajax.php');
		$local_vars['validate_msg']['required'] = __('This field is required.', 'emd-plugins');
		$local_vars['validate_msg']['remote'] = __('Please fix this field.', 'emd-plugins');
		$local_vars['validate_msg']['email'] = __('Please enter a valid email address.', 'emd-plugins');
		$local_vars['validate_msg']['url'] = __('Please enter a valid URL.', 'emd-plugins');
		$local_vars['validate_msg']['date'] = __('Please enter a valid date.', 'emd-plugins');
		$local_vars['validate_msg']['dateISO'] = __('Please enter a valid date ( ISO )', 'emd-plugins');
		$local_vars['validate_msg']['number'] = __('Please enter a valid number.', 'emd-plugins');
		$local_vars['validate_msg']['digits'] = __('Please enter only digits.', 'emd-plugins');
		$local_vars['validate_msg']['creditcard'] = __('Please enter a valid credit card number.', 'emd-plugins');
		$local_vars['validate_msg']['equalTo'] = __('Please enter the same value again.', 'emd-plugins');
		$local_vars['validate_msg']['maxlength'] = __('Please enter no more than {0} characters.', 'emd-plugins');
		$local_vars['validate_msg']['minlength'] = __('Please enter at least {0} characters.', 'emd-plugins');
		$local_vars['validate_msg']['rangelength'] = __('Please enter a value between {0} and {1} characters long.', 'emd-plugins');
		$local_vars['validate_msg']['range'] = __('Please enter a value between {0} and {1}.', 'emd-plugins');
		$local_vars['validate_msg']['max'] = __('Please enter a value less than or equal to {0}.', 'emd-plugins');
		$local_vars['validate_msg']['min'] = __('Please enter a value greater than or equal to {0}.', 'emd-plugins');
		$local_vars['unique_msg'] = __('Please enter a unique value.', 'emd-plugins');
		$wpas_shc_list = get_option('sim_com_shc_list');
		wp_register_style('issue-entry-forms', $dir_url . 'assets/css/issue-entry-forms.css');
		wp_register_script('issue-entry-forms-js', $dir_url . 'assets/js/issue-entry-forms.js');
		wp_localize_script('issue-entry-forms-js', 'issue_entry_vars', $local_vars);
		wp_register_style('allview-css', $dir_url . '/assets/css/allview.css');
		wp_register_style('issue-search-forms', $dir_url . 'assets/css/issue-search-forms.css');
		wp_register_script('issue-search-forms-js', $dir_url . 'assets/js/issue-search-forms.js');
		wp_localize_script('issue-search-forms-js', 'issue_search_vars', $local_vars);
		wp_register_script('jvalidate-js', $dir_url . 'assets/ext/jvalidate1111/wpas.validate.min.js', array(
			'jquery'
		));
		wp_register_style('wpasui', SIM_COM_PLUGIN_URL . 'assets/ext/wpas-jui/wpas-jui.min.css');
		wp_register_style('jq-css', SIM_COM_PLUGIN_URL . 'assets/css/smoothness-jquery-ui.css');
		wp_register_style('allview-css', $dir_url . '/assets/css/allview.css');
		return;
	}
	if (is_single() && get_post_type() == 'emd_issue') {
		wp_enqueue_style("sim-com-default-single-css", SIM_COM_PLUGIN_URL . 'assets/css/sim-com-default-single.css');
	}
	if (is_single() && get_post_type() == 'emd_project') {
		wp_enqueue_style("sim-com-default-single-css", SIM_COM_PLUGIN_URL . 'assets/css/sim-com-default-single.css');
	}
}
