<?php
/**
 * Entity Class
 *
 * @package SIM_COM
 * @version 1.0.4
 * @since WPAS 4.0
 */
if (!defined('ABSPATH')) exit;
/**
 * Emd_Project Class
 * @since WPAS 4.0
 */
class Emd_Project extends Emd_Entity {
	protected $post_type = 'emd_project';
	protected $textdomain = 'sim-com';
	protected $sing_label;
	protected $plural_label;
	private $boxes = Array();
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
		add_action('save_post', array(
			$this,
			'change_title'
		) , 99, 2);
		add_filter('post_updated_messages', array(
			$this,
			'updated_messages'
		));
		add_action('manage_emd_project_posts_custom_column', array(
			$this,
			'custom_columns'
		) , 10, 2);
		add_filter('manage_emd_project_posts_columns', array(
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
				))) {
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
				$image_list = rwmb_meta($column_id, 'type=image');
				if (!empty($image_list)) {
					$value = "";
					foreach ($image_list as $myimage) {
						$value.= "<img src='" . $myimage['url'] . "' >";
					}
				}
			break;
			case 'user':
			case 'user-adv':
				$user_id = rwmb_meta($column_id);
				if (!empty($user_id)) {
					$user_info = get_userdata($user_id);
					$value = $user_info->display_name;
				}
			break;
			case 'file':
				$file_list = rwmb_meta($column_id, 'type=file');
				if (!empty($file_list)) {
					$value = "";
					foreach ($file_list as $myfile) {
						$value.= "<a href='" . $myfile['url'] . "' target='_blank'>" . $myfile['name'] . "</a>, ";
					}
					$value = rtrim($value, ", ");
				}
			break;
			case 'checkbox_list':
				$checkbox_list = rwmb_meta($column_id, 'type=checkbox_list');
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
			'name' => __('Projects', 'sim-com') ,
			'singular_name' => __('Project', 'sim-com') ,
			'add_new' => __('Add New', 'sim-com') ,
			'add_new_item' => __('Add New Project', 'sim-com') ,
			'edit_item' => __('Edit Project', 'sim-com') ,
			'new_item' => __('New Project', 'sim-com') ,
			'all_items' => __('All Projects', 'sim-com') ,
			'view_item' => __('View Project', 'sim-com') ,
			'search_items' => __('Search Projects', 'sim-com') ,
			'not_found' => __('No Projects Found', 'sim-com') ,
			'not_found_in_trash' => __('No Projects Found In Trash', 'sim-com') ,
			'menu_name' => __('Projects', 'sim-com') ,
		);
		register_post_type('emd_project', array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'description' => __('A project is a collection of related issues. Projects have a unique version number, specific start and end dates.', 'sim-com') ,
			'show_in_menu' => true,
			'menu_position' => 7,
			'has_archive' => true,
			'exclude_from_search' => false,
			'rewrite' => array(
				'slug' => 'projects'
			) ,
			'can_export' => true,
			'hierarchical' => false,
			'menu_icon' => 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPCEtLSBHZW5lcmF0ZWQgYnkgSWNvTW9vbi5pbyAtLT4KPCFET0NUWVBFIHN2ZyBQVUJMSUMgIi0vL1czQy8vRFREIFNWRyAxLjEvL0VOIiAiaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkIj4KPHN2ZyB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHdpZHRoPSIyMCIgaGVpZ2h0PSIyMCIgdmlld0JveD0iMCAwIDIwIDIwIj4KPGc+CjwvZz4KCTxwYXRoIGQ9Ik0xMS4yNTUgNS42NWw0Ljc0LTEuNTkgMy45NzYgMTEuODUxLTQuNzQgMS41OXpNMCAxNy41aDV2LTEzLjc1aC01djEzLjc1ek0xLjI1IDYuMjVoMi41djEuMjVoLTIuNXYtMS4yNXpNNi4yNSAxNy41aDV2LTEzLjc1aC01djEzLjc1ek03LjUgNi4yNWgyLjV2MS4yNWgtMi41di0xLjI1eiIgZmlsbD0iIzM2MzYzNiI+PC9wYXRoPgo8L3N2Zz4K',
			'map_meta_cap' => 'true',
			'taxonomies' => array() ,
			'capability_type' => 'emd_project',
			'supports' => array(
				'editor',
			)
		));
		$project_priority_nohr_labels = array(
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
		register_taxonomy('project_priority', array(
			'emd_project'
		) , array(
			'hierarchical' => false,
			'labels' => $project_priority_nohr_labels,
			'public' => true,
			'show_ui' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud' => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var' => true,
			'rewrite' => array(
				'slug' => 'project_priority'
			) ,
			'capabilities' => array(
				'manage_terms' => 'manage_project_priority',
				'edit_terms' => 'edit_project_priority',
				'delete_terms' => 'delete_project_priority',
				'assign_terms' => 'assign_project_priority'
			) ,
		));
		$project_status_nohr_labels = array(
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
		register_taxonomy('project_status', array(
			'emd_project'
		) , array(
			'hierarchical' => false,
			'labels' => $project_status_nohr_labels,
			'public' => true,
			'show_ui' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud' => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var' => true,
			'rewrite' => array(
				'slug' => 'project_status'
			) ,
			'capabilities' => array(
				'manage_terms' => 'manage_project_status',
				'edit_terms' => 'edit_project_status',
				'delete_terms' => 'delete_project_status',
				'assign_terms' => 'assign_project_status'
			) ,
		));
		if (!get_option('sim_com_emd_project_terms_init')) {
			$set_tax_terms = Array(
				Array(
					'name' => __('Low', 'sim-com') ,
					'slug' => sanitize_title('Low')
				) ,
				Array(
					'name' => __('Medium', 'sim-com') ,
					'slug' => sanitize_title('Medium')
				) ,
				Array(
					'name' => __('High', 'sim-com') ,
					'slug' => sanitize_title('High')
				)
			);
			self::set_taxonomy_init($set_tax_terms, 'project_priority');
			$set_tax_terms = Array(
				Array(
					'name' => __('Draft', 'sim-com') ,
					'slug' => sanitize_title('Draft')
				) ,
				Array(
					'name' => __('In Review', 'sim-com') ,
					'slug' => sanitize_title('In Review')
				) ,
				Array(
					'name' => __('Published', 'sim-com') ,
					'slug' => sanitize_title('Published')
				) ,
				Array(
					'name' => __('In Process', 'sim-com') ,
					'slug' => sanitize_title('In Process')
				)
			);
			self::set_taxonomy_init($set_tax_terms, 'project_status');
			update_option('sim_com_emd_project_terms_init', true);
		}
	}
	/**
	 * Set metabox fields,labels,filters, comments, relationships if exists
	 *
	 * @since WPAS 4.0
	 *
	 */
	public function set_filters() {
		$filter_args = array();
		$this->sing_label = __('Project', 'sim-com');
		$this->plural_label = __('Projects', 'sim-com');
		$this->boxes[] = array(
			'id' => 'project_info_emd_project_0',
			'title' => __('Project Info', 'sim-com') ,
			'pages' => array(
				'emd_project'
			) ,
			'context' => 'normal',
			'fields' => array(
				'emd_prj_name' => array(
					'name' => __('Name', 'sim-com') ,
					'id' => 'emd_prj_name',
					'type' => 'text',
					'multiple' => false,
					'desc' => __('Sets the name of a project.', 'sim-com') ,
					'class' => 'emd_prj_name',
				) ,
				'emd_prj_version' => array(
					'name' => __('Version', 'sim-com') ,
					'id' => 'emd_prj_version',
					'type' => 'text',
					'multiple' => false,
					'desc' => __('Sets the version number of a project.', 'sim-com') ,
					'std' => 'V1.0.0',
					'class' => 'emd_prj_version',
				) ,
				'emd_prj_start_date' => array(
					'name' => __('Start Date', 'sim-com') ,
					'id' => 'emd_prj_start_date',
					'type' => 'date',
					'multiple' => false,
					'js_options' => array(
						'dateFormat' => 'mm-dd-yy'
					) ,
					'desc' => __('Sets the start date of a project.', 'sim-com') ,
					'class' => 'emd_prj_start_date',
				) ,
				'emd_prj_target_end_date' => array(
					'name' => __('Target End Date', 'sim-com') ,
					'id' => 'emd_prj_target_end_date',
					'type' => 'date',
					'multiple' => false,
					'js_options' => array(
						'dateFormat' => 'mm-dd-yy'
					) ,
					'desc' => __('Sets the targeted end date of a project.', 'sim-com') ,
					'class' => 'emd_prj_target_end_date',
				) ,
				'emd_prj_actual_end_date' => array(
					'name' => __('Actual End Date', 'sim-com') ,
					'id' => 'emd_prj_actual_end_date',
					'type' => 'date',
					'multiple' => false,
					'js_options' => array(
						'dateFormat' => 'mm-dd-yy'
					) ,
					'desc' => __('Sets the actual end date of a project.', 'sim-com') ,
					'class' => 'emd_prj_actual_end_date',
				) ,
				'emd_prj_file' => array(
					'name' => __('Documents', 'sim-com') ,
					'id' => 'emd_prj_file',
					'type' => 'file',
					'multiple' => false,
					'desc' => __('Allows to upload project related files.', 'sim-com') ,
					'class' => 'emd_prj_file',
				) ,
			) ,
			'validation' => array(
				'onfocusout' => false,
				'onkeyup' => false,
				'onclick' => false,
				'rules' => array(
					'emd_prj_name' => array(
						'required' => true,
						'minlength' => 3,
						'uniqueAttr' => true,
					) ,
					'emd_prj_version' => array(
						'required' => true,
						'uniqueAttr' => true,
					) ,
					'emd_prj_start_date' => array(
						'required' => true,
					) ,
					'emd_prj_target_end_date' => array(
						'required' => false,
					) ,
					'emd_prj_actual_end_date' => array(
						'required' => false,
					) ,
					'emd_prj_file' => array(
						'required' => false,
					) ,
				) ,
			)
		);
		if (!post_type_exists($this->post_type) || in_array($this->post_type, Array(
			'post',
			'page'
		))) {
			self::register();
		}
		global $pagenow;
		if ('post-new.php' === $pagenow || 'post.php' === $pagenow) {
			if (class_exists('RW_Meta_Box') && is_array($this->boxes)) {
				foreach ($this->boxes as $meta_box) {
					new RW_Meta_Box($meta_box);
				}
			}
		}
		if (!function_exists('p2p_register_connection_type')) {
			return;
		}
		p2p_register_connection_type(array(
			'name' => 'project_issues',
			'from' => 'emd_project',
			'to' => 'emd_issue',
			'sortable' => 'any',
			'reciprocal' => false,
			'cardinality' => 'many-to-many',
			'title' => array(
				'from' => __('Project Issues', 'sim-com') ,
				'to' => __('Affected Projects', 'sim-com')
			) ,
			'from_labels' => array(
				'singular_name' => __('Project', 'sim-com') ,
				'search_items' => __('Search Projects', 'sim-com') ,
				'not_found' => __('No Projects found.', 'sim-com') ,
			) ,
			'to_labels' => array(
				'singular_name' => __('Issue', 'sim-com') ,
				'search_items' => __('Search Issues', 'sim-com') ,
				'not_found' => __('No Issues found.', 'sim-com') ,
			) ,
			'admin_box' => array(
				'show' => 'to',
				'context' => 'advanced'
			) ,
		));
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
			emd_get_template_part($this->textdomain, 'single', 'emd-project');
			$layout = ob_get_clean();
		}
		if ($layout != "") {
			$content = $layout;
		}
		return $content;
	}
}
new Emd_Project;
