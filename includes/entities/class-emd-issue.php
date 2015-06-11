<?php
/**
 * Entity Class
 *
 * @package SIM_COM
 * @version 2.0.1
 * @since WPAS 4.0
 */
if (!defined('ABSPATH')) exit;
/**
 * Emd_Issue Class
 * @since WPAS 4.0
 */
class Emd_Issue extends Emd_Entity {
	protected $post_type = 'emd_issue';
	protected $textdomain = 'sim-com';
	protected $sing_label;
	protected $plural_label;
	protected $menu_entity;
	/**
	 * Initialize entity class
	 *
	 * @since WPAS 4.0
	 *
	 */
	public function __construct() {
		add_action('init', array(
			$this,
			'set_filters'
		));
		add_action('admin_init', array(
			$this,
			'set_metabox'
		));
		add_filter('post_updated_messages', array(
			$this,
			'updated_messages'
		));
		add_action('manage_emd_issue_posts_custom_column', array(
			$this,
			'custom_columns'
		) , 10, 2);
		add_filter('manage_emd_issue_posts_columns', array(
			$this,
			'column_headers'
		));
	}
	/**
	 * Get column header list in admin list pages
	 * @since WPAS 4.0
	 *
	 * @param array $columns
	 *
	 * @return array $columns
	 */
	public function column_headers($columns) {
		foreach ($this->boxes as $mybox) {
			foreach ($mybox['fields'] as $fkey => $mybox_field) {
				if (!in_array($fkey, Array(
					'wpas_form_name',
					'wpas_form_submitted_by',
					'wpas_form_submitted_ip'
				)) && !in_array($mybox_field['type'], Array(
					'textarea',
					'wysiwyg'
				)) && $mybox_field['list_visible'] == 1) {
					$columns[$fkey] = $mybox_field['name'];
				}
			}
		}
		$args = array(
			'_builtin' => false,
			'object_type' => Array(
				$this->post_type
			)
		);
		$taxonomies = get_taxonomies($args, 'objects');
		if (!empty($taxonomies)) {
			foreach ($taxonomies as $taxonomy) {
				$columns[$taxonomy->name] = $taxonomy->label;
			}
		}
		return $columns;
	}
	/**
	 * Get custom column values in admin list pages
	 * @since WPAS 4.0
	 *
	 * @param int $column_id
	 * @param int $post_id
	 *
	 * @return string $value
	 */
	public function custom_columns($column_id, $post_id) {
		if (taxonomy_exists($column_id) == true) {
			$terms = get_the_terms($post_id, $column_id);
			$ret = array();
			if (!empty($terms)) {
				foreach ($terms as $term) {
					$url = add_query_arg(array(
						'post_type' => $this->post_type,
						'term' => $term->slug,
						'taxonomy' => $column_id
					) , admin_url('edit.php'));
					$ret[] = sprintf('<a href="%s">%s</a>', $url, $term->name);
				}
			}
			echo implode(', ', $ret);
			return;
		}
		$value = get_post_meta($post_id, $column_id, true);
		$type = "";
		foreach ($this->boxes as $mybox) {
			foreach ($mybox['fields'] as $fkey => $mybox_field) {
				if ($fkey == $column_id) {
					$type = $mybox_field['type'];
					break;
				}
			}
		}
		switch ($type) {
			case 'plupload_image':
			case 'image':
			case 'thickbox_image':
				$image_list = emd_mb_meta($column_id, 'type=image');
				if (!empty($image_list)) {
					$value = "";
					foreach ($image_list as $myimage) {
						$value.= "<img src='" . $myimage['url'] . "' >";
					}
				}
			break;
			case 'user':
			case 'user-adv':
				$user_id = emd_mb_meta($column_id);
				if (!empty($user_id)) {
					$user_info = get_userdata($user_id);
					$value = $user_info->display_name;
				}
			break;
			case 'file':
				$file_list = emd_mb_meta($column_id, 'type=file');
				if (!empty($file_list)) {
					$value = "";
					foreach ($file_list as $myfile) {
						$fsrc = wp_mime_type_icon($myfile['ID']);
						$value.= "<a href='" . $myfile['url'] . "' target='_blank'><img src='" . $fsrc . "' title='" . $myfile['name'] . "' width='20' /></a>";
					}
				}
			break;
			case 'checkbox_list':
				$checkbox_list = emd_mb_meta($column_id, 'type=checkbox_list');
				if (!empty($checkbox_list)) {
					$value = implode(', ', $checkbox_list);
				}
			break;
			case 'select':
			case 'select_advanced':
				$select_list = get_post_meta($post_id, $column_id, false);
				if (!empty($select_list)) {
					$value = implode(', ', $select_list);
				}
			break;
			case 'checkbox':
				if ($value == 1) {
					$value = '<span class="dashicons dashicons-yes"></span>';
				}
			break;
		}
		echo $value;
	}
	/**
	 * Register post type and taxonomies and set initial values for taxs
	 *
	 * @since WPAS 4.0
	 *
	 */
	public static function register() {
		$labels = array(
			'name' => __('Issues', 'sim-com') ,
			'singular_name' => __('Issue', 'sim-com') ,
			'add_new' => __('Add New', 'sim-com') ,
			'add_new_item' => __('Add New Issue', 'sim-com') ,
			'edit_item' => __('Edit Issue', 'sim-com') ,
			'new_item' => __('New Issue', 'sim-com') ,
			'all_items' => __('All Issues', 'sim-com') ,
			'view_item' => __('View Issue', 'sim-com') ,
			'search_items' => __('Search Issues', 'sim-com') ,
			'not_found' => __('No Issues Found', 'sim-com') ,
			'not_found_in_trash' => __('No Issues Found In Trash', 'sim-com') ,
			'menu_name' => __('Issues', 'sim-com') ,
		);
		register_post_type('emd_issue', array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'description' => __('An issue is anything that might affect the project meeting its goals such as bugs, tasks, and feature requests that occur during a project\'s life cycle.', 'sim-com') ,
			'show_in_menu' => true,
			'menu_position' => 6,
			'has_archive' => true,
			'exclude_from_search' => false,
			'rewrite' => array(
				'slug' => 'issues'
			) ,
			'can_export' => true,
			'hierarchical' => false,
			'menu_icon' => 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz48IURPQ1RZUEUgc3ZnIFBVQkxJQyAiLS8vVzNDLy9EVEQgU1ZHIDEuMS8vRU4iICJodHRwOi8vd3d3LnczLm9yZy9HcmFwaGljcy9TVkcvMS4xL0RURC9zdmcxMS5kdGQiPjxzdmcgdmVyc2lvbj0iMS4xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHZpZXdCb3g9IjAgMCAyMCAyMCI+PGc+PC9nPjxwYXRoIGQ9Ik0zLjE3MiAxMy4zOTdoMy41OTJsMC40OTItMi44OTZoLTMuNDY5bC0wLjYxNSAyLjg5NnpNNC4zNTYgMTEuMTk2aDIuMDcxbC0wLjI1NiAxLjUwNmgtMi4xMzVsMC4zMi0xLjUwNnoiIGZpbGw9IiNmNzBjMGMiPjwvcGF0aD48cGF0aCBkPSJNNC44NzYgOC41MDhsMC4zMDItMS40MjFoMS42ODRsMS4wNDItMC42IDAuMDEwLTAuMDU2aC0zLjI3MmwtMC41OCAyLjczMmgzLjM4OGwwLjI1NS0xLjUwMy0wLjc0NCAwLjQzMy0wLjA3MCAwLjQxNHoiIGZpbGw9IiNmNzBjMGMiPjwvcGF0aD48cGF0aCBkPSJNNi4yODggNy42MjZsLTAuNDY1LTAuNDQzLTAuNDYzIDAuMjc5IDAuODEzIDAuODUyIDIuMzMtMS4zNTctMC40MzMtMC4zNTd6IiBmaWxsPSIjZjcwYzBjIj48L3BhdGg+PHBhdGggZD0iTTIwLjE1NiAwLjcwOWwtMC44NzUtMS4wMjdjLTAuMjMtMC4yNy0wLjYzNi0wLjMwNC0wLjkwOS0wLjA3NmwtMC43NTQgMC42MzIgMS43MDcgMi4wMDQgMC43NTQtMC42MzJjMC4yNzItMC4yMjggMC4zMDYtMC42MzEgMC4wNzYtMC45MDF6IiBmaWxsPSIjZjcwYzBjIj48L3BhdGg+PHBhdGggZD0iTTExLjU5NiA0Ljc2N2wxLjA4OC0wLjg3NGgtOS43MTZsLTMuMDE5IDEyLjM0NWgxOS43ODdsLTIuODE0LTExLjUwNi0wLjc1NCAwLjYyNyAyLjQ0NiAxMC4wMDRoLTE3LjU0NWwyLjU5Mi0xMC41OTd6IiBmaWxsPSIjZjcwYzBjIj48L3BhdGg+PHBhdGggZD0iTTE1Ljk3NCA1LjA0OGwzLjAyMC0yLjUyOS0xLjcwOC0yLjAwNC02Ljg5NCA1Ljc3MyAxLjcwOCAyLjAwNCAzLjg3MS0zLjI0MnoiIGZpbGw9IiNmNzBjMGMiPjwvcGF0aD48cGF0aCBkPSJNMTEuNjU3IDguNTYxbC0xLjYwNi0xLjg4NS0xLjA3MCAyLjUyOHoiIGZpbGw9IiNmNzBjMGMiPjwvcGF0aD48L3N2Zz4=',
			'map_meta_cap' => 'true',
			'taxonomies' => array() ,
			'capability_type' => 'emd_issue',
			'supports' => array(
				'title',
				'editor',
				'author',
				'revisions',
				'comments'
			)
		));
		$operating_system_nohr_labels = array(
			'name' => __('Operating Systems', 'sim-com') ,
			'singular_name' => __('Operating System', 'sim-com') ,
			'search_items' => __('Search Operating Systems', 'sim-com') ,
			'popular_items' => __('Popular Operating Systems', 'sim-com') ,
			'all_items' => __('All', 'sim-com') ,
			'parent_item' => null,
			'parent_item_colon' => null,
			'edit_item' => __('Edit Operating System', 'sim-com') ,
			'update_item' => __('Update Operating System', 'sim-com') ,
			'add_new_item' => __('Add New Operating System', 'sim-com') ,
			'new_item_name' => __('Add New Operating System Name', 'sim-com') ,
			'separate_items_with_commas' => __('Seperate Operating Systems with commas', 'sim-com') ,
			'add_or_remove_items' => __('Add or Remove Operating Systems', 'sim-com') ,
			'choose_from_most_used' => __('Choose from the most used Operating Systems', 'sim-com') ,
			'menu_name' => __('Operating Systems', 'sim-com') ,
		);
		register_taxonomy('operating_system', array(
			'emd_issue'
		) , array(
			'hierarchical' => false,
			'labels' => $operating_system_nohr_labels,
			'public' => true,
			'show_ui' => true,
			'show_in_nav_menus' => true,
			'show_in_menu' => true,
			'show_tagcloud' => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var' => true,
			'rewrite' => array(
				'slug' => 'operating_system'
			) ,
			'capabilities' => array(
				'manage_terms' => 'manage_operating_system',
				'edit_terms' => 'edit_operating_system',
				'delete_terms' => 'delete_operating_system',
				'assign_terms' => 'assign_operating_system'
			) ,
		));
		$issue_priority_nohr_labels = array(
			'name' => __('Priorities', 'sim-com') ,
			'singular_name' => __('Priority', 'sim-com') ,
			'search_items' => __('Search Priorities', 'sim-com') ,
			'popular_items' => __('Popular Priorities', 'sim-com') ,
			'all_items' => __('All', 'sim-com') ,
			'parent_item' => null,
			'parent_item_colon' => null,
			'edit_item' => __('Edit Priority', 'sim-com') ,
			'update_item' => __('Update Priority', 'sim-com') ,
			'add_new_item' => __('Add New Priority', 'sim-com') ,
			'new_item_name' => __('Add New Priority Name', 'sim-com') ,
			'separate_items_with_commas' => __('Seperate Priorities with commas', 'sim-com') ,
			'add_or_remove_items' => __('Add or Remove Priorities', 'sim-com') ,
			'choose_from_most_used' => __('Choose from the most used Priorities', 'sim-com') ,
			'menu_name' => __('Priorities', 'sim-com') ,
		);
		register_taxonomy('issue_priority', array(
			'emd_issue'
		) , array(
			'hierarchical' => false,
			'labels' => $issue_priority_nohr_labels,
			'public' => true,
			'show_ui' => true,
			'show_in_nav_menus' => true,
			'show_in_menu' => true,
			'show_tagcloud' => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var' => true,
			'rewrite' => array(
				'slug' => 'issue_priority'
			) ,
			'capabilities' => array(
				'manage_terms' => 'manage_issue_priority',
				'edit_terms' => 'edit_issue_priority',
				'delete_terms' => 'delete_issue_priority',
				'assign_terms' => 'assign_issue_priority'
			) ,
		));
		$issue_cat_nohr_labels = array(
			'name' => __('Categories', 'sim-com') ,
			'singular_name' => __('Category', 'sim-com') ,
			'search_items' => __('Search Categories', 'sim-com') ,
			'popular_items' => __('Popular Categories', 'sim-com') ,
			'all_items' => __('All', 'sim-com') ,
			'parent_item' => null,
			'parent_item_colon' => null,
			'edit_item' => __('Edit Category', 'sim-com') ,
			'update_item' => __('Update Category', 'sim-com') ,
			'add_new_item' => __('Add New Category', 'sim-com') ,
			'new_item_name' => __('Add New Category Name', 'sim-com') ,
			'separate_items_with_commas' => __('Seperate Categories with commas', 'sim-com') ,
			'add_or_remove_items' => __('Add or Remove Categories', 'sim-com') ,
			'choose_from_most_used' => __('Choose from the most used Categories', 'sim-com') ,
			'menu_name' => __('Categories', 'sim-com') ,
		);
		register_taxonomy('issue_cat', array(
			'emd_issue'
		) , array(
			'hierarchical' => false,
			'labels' => $issue_cat_nohr_labels,
			'public' => true,
			'show_ui' => true,
			'show_in_nav_menus' => true,
			'show_in_menu' => true,
			'show_tagcloud' => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var' => true,
			'rewrite' => array(
				'slug' => 'issue_cat'
			) ,
			'capabilities' => array(
				'manage_terms' => 'manage_issue_cat',
				'edit_terms' => 'edit_issue_cat',
				'delete_terms' => 'delete_issue_cat',
				'assign_terms' => 'assign_issue_cat'
			) ,
		));
		$issue_tag_nohr_labels = array(
			'name' => __('Tags', 'sim-com') ,
			'singular_name' => __('Tag', 'sim-com') ,
			'search_items' => __('Search Tags', 'sim-com') ,
			'popular_items' => __('Popular Tags', 'sim-com') ,
			'all_items' => __('All', 'sim-com') ,
			'parent_item' => null,
			'parent_item_colon' => null,
			'edit_item' => __('Edit Tag', 'sim-com') ,
			'update_item' => __('Update Tag', 'sim-com') ,
			'add_new_item' => __('Add New Tag', 'sim-com') ,
			'new_item_name' => __('Add New Tag Name', 'sim-com') ,
			'separate_items_with_commas' => __('Seperate Tags with commas', 'sim-com') ,
			'add_or_remove_items' => __('Add or Remove Tags', 'sim-com') ,
			'choose_from_most_used' => __('Choose from the most used Tags', 'sim-com') ,
			'menu_name' => __('Tags', 'sim-com') ,
		);
		register_taxonomy('issue_tag', array(
			'emd_issue'
		) , array(
			'hierarchical' => false,
			'labels' => $issue_tag_nohr_labels,
			'public' => true,
			'show_ui' => true,
			'show_in_nav_menus' => true,
			'show_in_menu' => true,
			'show_tagcloud' => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var' => true,
			'rewrite' => array(
				'slug' => 'issue_tag'
			) ,
			'capabilities' => array(
				'manage_terms' => 'manage_issue_tag',
				'edit_terms' => 'edit_issue_tag',
				'delete_terms' => 'delete_issue_tag',
				'assign_terms' => 'assign_issue_tag'
			) ,
		));
		$browser_nohr_labels = array(
			'name' => __('Browsers', 'sim-com') ,
			'singular_name' => __('Browser', 'sim-com') ,
			'search_items' => __('Search Browsers', 'sim-com') ,
			'popular_items' => __('Popular Browsers', 'sim-com') ,
			'all_items' => __('All', 'sim-com') ,
			'parent_item' => null,
			'parent_item_colon' => null,
			'edit_item' => __('Edit Browser', 'sim-com') ,
			'update_item' => __('Update Browser', 'sim-com') ,
			'add_new_item' => __('Add New Browser', 'sim-com') ,
			'new_item_name' => __('Add New Browser Name', 'sim-com') ,
			'separate_items_with_commas' => __('Seperate Browsers with commas', 'sim-com') ,
			'add_or_remove_items' => __('Add or Remove Browsers', 'sim-com') ,
			'choose_from_most_used' => __('Choose from the most used Browsers', 'sim-com') ,
			'menu_name' => __('Browsers', 'sim-com') ,
		);
		register_taxonomy('browser', array(
			'emd_issue'
		) , array(
			'hierarchical' => false,
			'labels' => $browser_nohr_labels,
			'public' => true,
			'show_ui' => true,
			'show_in_nav_menus' => true,
			'show_in_menu' => true,
			'show_tagcloud' => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var' => true,
			'rewrite' => array(
				'slug' => 'browser'
			) ,
			'capabilities' => array(
				'manage_terms' => 'manage_browser',
				'edit_terms' => 'edit_browser',
				'delete_terms' => 'delete_browser',
				'assign_terms' => 'assign_browser'
			) ,
		));
		$issue_status_nohr_labels = array(
			'name' => __('Statuses', 'sim-com') ,
			'singular_name' => __('Status', 'sim-com') ,
			'search_items' => __('Search Statuses', 'sim-com') ,
			'popular_items' => __('Popular Statuses', 'sim-com') ,
			'all_items' => __('All', 'sim-com') ,
			'parent_item' => null,
			'parent_item_colon' => null,
			'edit_item' => __('Edit Status', 'sim-com') ,
			'update_item' => __('Update Status', 'sim-com') ,
			'add_new_item' => __('Add New Status', 'sim-com') ,
			'new_item_name' => __('Add New Status Name', 'sim-com') ,
			'separate_items_with_commas' => __('Seperate Statuses with commas', 'sim-com') ,
			'add_or_remove_items' => __('Add or Remove Statuses', 'sim-com') ,
			'choose_from_most_used' => __('Choose from the most used Statuses', 'sim-com') ,
			'menu_name' => __('Statuses', 'sim-com') ,
		);
		register_taxonomy('issue_status', array(
			'emd_issue'
		) , array(
			'hierarchical' => false,
			'labels' => $issue_status_nohr_labels,
			'public' => true,
			'show_ui' => true,
			'show_in_nav_menus' => true,
			'show_in_menu' => true,
			'show_tagcloud' => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var' => true,
			'rewrite' => array(
				'slug' => 'issue_status'
			) ,
			'capabilities' => array(
				'manage_terms' => 'manage_issue_status',
				'edit_terms' => 'edit_issue_status',
				'delete_terms' => 'delete_issue_status',
				'assign_terms' => 'assign_issue_status'
			) ,
		));
		if (!get_option('sim_com_emd_issue_terms_init')) {
			$set_tax_terms = Array(
				Array(
					'name' => __('Windows 8 (32-bit and 64-bit)', 'sim-com') ,
					'slug' => sanitize_title('Windows 8 (32-bit and 64-bit)')
				) ,
				Array(
					'name' => __('Windows 7 (32-bit and 64-bit)', 'sim-com') ,
					'slug' => sanitize_title('Windows 7 (32-bit and 64-bit)')
				) ,
				Array(
					'name' => __('Windows Vista (32-bit and 64-bit)', 'sim-com') ,
					'slug' => sanitize_title('Windows Vista (32-bit and 64-bit)')
				) ,
				Array(
					'name' => __('Windows XP (32-bit and 64-bit)', 'sim-com') ,
					'slug' => sanitize_title('Windows XP (32-bit and 64-bit)')
				) ,
				Array(
					'name' => __('Windows Server 2008 R2 (64-bit)', 'sim-com') ,
					'slug' => sanitize_title('Windows Server 2008 R2 (64-bit)')
				) ,
				Array(
					'name' => __('Windows Server 2008 (32-bit and 64-bit)', 'sim-com') ,
					'slug' => sanitize_title('Windows Server 2008 (32-bit and 64-bit)')
				) ,
				Array(
					'name' => __('Windows Server 2003 (32-bit and 64-bit)', 'sim-com') ,
					'slug' => sanitize_title('Windows Server 2003 (32-bit and 64-bit)')
				) ,
				Array(
					'name' => __('Windows 2000 SP4', 'sim-com') ,
					'slug' => sanitize_title('Windows 2000 SP4')
				) ,
				Array(
					'name' => __('Mac OS X 10.8 Mountain Lion (32-bit and 64-bit)', 'sim-com') ,
					'slug' => sanitize_title('Mac OS X 10.8 Mountain Lion (32-bit and 64-bit)')
				) ,
				Array(
					'name' => __('Mac OS X 10.7 Lion (32-bit and 64-bit)', 'sim-com') ,
					'slug' => sanitize_title('Mac OS X 10.7 Lion (32-bit and 64-bit)')
				) ,
				Array(
					'name' => __('Mac OS X 10.6 Snow Leopard (32-bit)', 'sim-com') ,
					'slug' => sanitize_title('Mac OS X 10.6 Snow Leopard (32-bit)')
				) ,
				Array(
					'name' => __('Mac OS X 10.5 Leopard', 'sim-com') ,
					'slug' => sanitize_title('Mac OS X 10.5 Leopard')
				) ,
				Array(
					'name' => __('Mac OS X 10.4 Tiger', 'sim-com') ,
					'slug' => sanitize_title('Mac OS X 10.4 Tiger')
				) ,
				Array(
					'name' => __('Linux (32-bit and 64-bit versions, kernel 2.6 or compatible)', 'sim-com') ,
					'slug' => sanitize_title('Linux (32-bit and 64-bit versions, kernel 2.6 or compatible)')
				)
			);
			self::set_taxonomy_init($set_tax_terms, 'operating_system');
			$set_tax_terms = Array(
				Array(
					'name' => __('Critical', 'sim-com') ,
					'slug' => sanitize_title('Critical') ,
					'desc' => __('Critical bugs either render a system unusable (not being able to create content or upgrade between versions, blocks not displaying, and the like), cause loss of data, or expose security vulnerabilities. These bugs are to be fixed immediately.', 'sim-com')
				) ,
				Array(
					'name' => __('Major', 'sim-com') ,
					'slug' => sanitize_title('Major') ,
					'desc' => __('Issues which have significant repercussions but do not render the whole system unusable are marked major. An example would be a PHP error which is only triggered under rare circumstances or which affects only a small percentage of all users. These issues are prioritized in the current development release and backported to stable releases where applicable. Major issues do not block point releases.', 'sim-com')
				) ,
				Array(
					'name' => __('Normal', 'sim-com') ,
					'slug' => sanitize_title('Normal') ,
					'desc' => __('Bugs that affect one piece of functionality are normal priority. An example would be the category filter not working on the database log screen. This is a self-contained bug and does not impact the overall functionality of the software.', 'sim-com')
				) ,
				Array(
					'name' => __('Minor', 'sim-com') ,
					'slug' => sanitize_title('Minor') ,
					'desc' => __('Minor priority is most often used for cosmetic issues that don\'t inhibit the functionality or main purpose of the project, such as correction of typos in code comments or whitespace issues.', 'sim-com')
				)
			);
			self::set_taxonomy_init($set_tax_terms, 'issue_priority');
			$set_tax_terms = Array(
				Array(
					'name' => __('Bug', 'sim-com') ,
					'slug' => sanitize_title('Bug') ,
					'desc' => __('Bugs are software problems or defects in the system that need to be resolved.', 'sim-com')
				) ,
				Array(
					'name' => __('Feature Request', 'sim-com') ,
					'slug' => sanitize_title('Feature Request') ,
					'desc' => __('Feature requests are functional enhancements submitted by clients.', 'sim-com')
				) ,
				Array(
					'name' => __('Task', 'sim-com') ,
					'slug' => sanitize_title('Task') ,
					'desc' => __('Tasks are activities that need to be accomplished within a defined period of time or by a deadline to resolve issues.', 'sim-com')
				)
			);
			self::set_taxonomy_init($set_tax_terms, 'issue_cat');
			$set_tax_terms = Array(
				Array(
					'name' => __('Chrome 33', 'sim-com') ,
					'slug' => sanitize_title('Chrome 33')
				) ,
				Array(
					'name' => __('Internet Explorer 11', 'sim-com') ,
					'slug' => sanitize_title('Internet Explorer 11')
				) ,
				Array(
					'name' => __('Safari 7.0', 'sim-com') ,
					'slug' => sanitize_title('Safari 7.0')
				) ,
				Array(
					'name' => __('Opera 20', 'sim-com') ,
					'slug' => sanitize_title('Opera 20')
				) ,
				Array(
					'name' => __('Firefox 29', 'sim-com') ,
					'slug' => sanitize_title('Firefox 29')
				)
			);
			self::set_taxonomy_init($set_tax_terms, 'browser');
			$set_tax_terms = Array(
				Array(
					'name' => __('Open', 'sim-com') ,
					'slug' => sanitize_title('Open') ,
					'desc' => __('This issue is in the initial state, ready for the assignee to start work on it.', 'sim-com')
				) ,
				Array(
					'name' => __('In Progress', 'sim-com') ,
					'slug' => sanitize_title('In Progress') ,
					'desc' => __('This issue is being actively worked on at the moment.', 'sim-com')
				) ,
				Array(
					'name' => __('Reopened', 'sim-com') ,
					'slug' => sanitize_title('Reopened') ,
					'desc' => __('This issue was once \'Resolved\' or \'Closed\', but is now being re-visited, e.g. an issue with a Resolution of \'Cannot Reproduce\' is Reopened when more information becomes available and the issue becomes reproducible. The next issue states are either marked In Progress, Resolved or Closed.', 'sim-com')
				) ,
				Array(
					'name' => __('Closed', 'sim-com') ,
					'slug' => sanitize_title('Closed') ,
					'desc' => __('This issue is complete.', 'sim-com')
				) ,
				Array(
					'name' => __('Resolved - Fixed', 'sim-com') ,
					'slug' => sanitize_title('Resolved - Fixed') ,
					'desc' => __('A fix for this issue has been implemented.', 'sim-com')
				) ,
				Array(
					'name' => __('Resolved - Won\'t Fix', 'sim-com') ,
					'slug' => sanitize_title('Resolved - Won\'t Fix') ,
					'desc' => __('This issue will not be fixed, e.g. it may no longer be relevant.', 'sim-com')
				) ,
				Array(
					'name' => __('Resolved - Duplicate', 'sim-com') ,
					'slug' => sanitize_title('Resolved - Duplicate') ,
					'desc' => __('This issue is a duplicate of an existing issue. It is recommended you create a link to the duplicated issue by creating a related issue connection.', 'sim-com')
				) ,
				Array(
					'name' => __('Resolved - Incomplete', 'sim-com') ,
					'slug' => sanitize_title('Resolved - Incomplete') ,
					'desc' => __('There is not enough information to work on this issue.', 'sim-com')
				) ,
				Array(
					'name' => __('Resolved - CNR', 'sim-com') ,
					'slug' => sanitize_title('Resolved - CNR') ,
					'desc' => __('This issue could not be reproduced at this time, or not enough information was available to reproduce the issue. If more information becomes available, reopen the issue.', 'sim-com')
				)
			);
			self::set_taxonomy_init($set_tax_terms, 'issue_status');
			update_option('sim_com_emd_issue_terms_init', true);
		}
	}
	/**
	 * Set metabox fields,labels,filters, comments, relationships if exists
	 *
	 * @since WPAS 4.0
	 *
	 */
	public function set_filters() {
		$this->sing_label = __('Issue', 'sim-com');
		$this->plural_label = __('Issues', 'sim-com');
		$this->menu_entity = 'emd_issue';
		$this->boxes[] = array(
			'id' => 'issue_info_emd_issue_0',
			'title' => __('Issue Info', 'sim-com') ,
			'pages' => array(
				'emd_issue'
			) ,
			'context' => 'normal',
		);
		list($search_args, $filter_args) = $this->set_args_boxes();
		if (!post_type_exists($this->post_type) || in_array($this->post_type, Array(
			'post',
			'page'
		))) {
			self::register();
		}
	}
	/**
	 * Initialize metaboxes
	 * @since WPAS 4.5
	 *
	 */
	public function set_metabox() {
		if (class_exists('EMD_Meta_Box') && is_array($this->boxes)) {
			foreach ($this->boxes as $meta_box) {
				new EMD_Meta_Box($meta_box);
			}
		}
	}
	/**
	 * Change content for created frontend views
	 * @since WPAS 4.0
	 * @param string $content
	 *
	 * @return string $content
	 */
	public function change_content($content) {
		global $post;
		$layout = "";
		if (get_post_type() == $this->post_type && is_single()) {
			ob_start();
			emd_get_template_part($this->textdomain, 'single', 'emd-issue');
			$layout = ob_get_clean();
		}
		if ($layout != "") {
			$content = $layout;
		}
		return $content;
	}
}
new Emd_Issue;
