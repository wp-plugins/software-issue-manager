<?php
/**
 *  Uninstall Software Issue Manager
 *
 * Uninstalling deletes notifications and terms initializations
 *
 * @package SIM_COM
 * @version 1.3.0
 * @since WPAS 4.0
 */
if (!defined('WP_UNINSTALL_PLUGIN')) exit;
if (!current_user_can('activate_plugins')) return;
function sim_com_uninstall() {
	//delete options
	$options_to_delete = Array(
		'sim_com_notify_list',
		'sim_com_ent_list',
		'sim_com_attr_list',
		'sim_com_shc_list',
		'sim_com_tax_list',
		'sim_com_rel_list',
		'sim_com_license_key',
		'sim_com_license_status',
		'sim_com_comment_list',
		'sim_com_access_views',
		'sim_com_limitby_auth_caps',
		'sim_com_limitby_caps',
		'sim_com_has_limitby_cap',
		'sim_com_setup_pages',
		'sim_com_emd_issue_terms_init',
		'sim_com_emd_project_terms_init'
	);
	if (!empty($options_to_delete)) {
		foreach ($options_to_delete as $option) {
			delete_option($option);
		}
	}
	$emd_activated_plugins = get_option('emd_activated_plugins');
	if (!empty($emd_activated_plugins)) {
		$emd_activated_plugins = array_diff($emd_activated_plugins, Array(
			'sim-com'
		));
		update_option('emd_activated_plugins', $emd_activated_plugins);
	}
}
if (is_multisite()) {
	global $wpdb;
	$blogs = $wpdb->get_results("SELECT blog_id FROM {$wpdb->blogs}", ARRAY_A);
	if ($blogs) {
		foreach ($blogs as $blog) {
			switch_to_blog($blog['blog_id']);
			sim_com_uninstall();
		}
		restore_current_blog();
	}
} else {
	sim_com_uninstall();
}
