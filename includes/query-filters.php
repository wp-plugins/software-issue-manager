<?php
/**
 * Query Filter Functions
 *
 * @package SIM_COM
 * @version 1.3.0
 * @since WPAS 4.0
 */
if (!defined('ABSPATH')) exit;
/**
 * Change query parameters before wp_query is processed
 *
 * @since WPAS 4.0
 * @param object $query
 *
 * @return object $query
 */
function sim_com_query_filters($query) {
	$has_limitby = get_option("sim_com_has_limitby_cap");
	if (!is_admin() && $query->is_main_query()) {
		if ($query->is_author || $query->is_search) {
			$query = emd_limit_author_search('sim_com', $query, $has_limitby);
		}
	}
	return $query;
}
add_action('pre_get_posts', 'sim_com_query_filters');
