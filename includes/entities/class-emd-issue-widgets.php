<?php
/**
 * Entity Widget Classes
 *
 * @package SIM_COM
 * @version 1.0.0
 * @since WPAS 4.0
 */
if (!defined('ABSPATH')) exit;
/**
 * Entity widget class extends Emd_Widget class
 *
 * @since WPAS 4.0
 */
class sim_com_recent_issues_sidebar_widget extends Emd_Widget {
	public $title;
	public $text_domain = 'sim-com';
	public $class_label;
	public $class = 'emd_issue';
	public $type = 'entity';
	public $has_pages = false;
	public $css_label = 'recent-issues';
	public $id = 'sim_com_recent_issues_sidebar_widget';
	public $query_args = array(
		'post_type' => 'emd_issue',
		'post_status' => 'publish',
		'orderby' => 'date',
		'order' => 'DESC'
	);
	/**
	 * Instantiate entity widget class with params
	 *
	 * @since WPAS 4.0
	 */
	function sim_com_recent_issues_sidebar_widget() {
		$this->Emd_Widget(__('Recent Issues', 'sim-com') , __('Issues', 'sim-com') , __('The most recent issues', 'sim-com'));
	}
	/**
	 * Returns widget layout
	 *
	 * @since WPAS 4.0
	 */
	function layout() {
		$layout = "<p class=\"issue-title\">* <a href=\"" . get_permalink() . "\">" . get_the_title() . "</a></p>
";
		return $layout;
	}
}
if (current_user_can('edit_emd_issues')) {
	register_widget('sim_com_recent_issues_sidebar_widget');
}
