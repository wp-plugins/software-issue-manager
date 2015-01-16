<?php
/**
 * Install and Deactivate Plugin Functions
 * @package SIM_COM
 * @version 1.1.0
 * @since WPAS 4.0
 */
if (!defined('ABSPATH')) exit;
if (!class_exists('Sim_Com_Install_Deactivate')):
	/**
	 * Sim_Com_Install_Deactivate Class
	 * @since WPAS 4.0
	 */
	class Sim_Com_Install_Deactivate {
		private $option_name;
		/**
		 * Hooks for install and deactivation and create options
		 * @since WPAS 4.0
		 */
		public function __construct() {
			$this->option_name = 'sim_com';
			register_activation_hook(SIM_COM_PLUGIN_FILE, array(
				$this,
				'install'
			));
			register_deactivation_hook(SIM_COM_PLUGIN_FILE, array(
				$this,
				'deactivate'
			));
			add_action('admin_init', array(
				$this,
				'setup_pages'
			));
			add_action('admin_notices', array(
				$this,
				'install_notice'
			));
			add_action('generate_rewrite_rules', 'emd_create_rewrite_rules');
			add_filter('query_vars', 'emd_query_vars');
			if (is_admin()) {
				$this->stax = new Emd_Single_Taxonomy('sim-com');
			}
			add_action('before_delete_post', array(
				$this,
				'delete_post_file_att'
			));
		}
		/**
		 * Runs on plugin install to setup custom post types and taxonomies
		 * flushing rewrite rules, populates settings and options
		 * creates roles and assign capabilities
		 * @since WPAS 4.0
		 *
		 */
		public function install() {
			P2P_Storage::install();
			Emd_Project::register();
			Emd_Issue::register();
			flush_rewrite_rules();
			$this->set_roles_caps();
			$this->set_options();
		}
		/**
		 * Runs on plugin deactivate to remove options, caps and roles
		 * flushing rewrite rules
		 * @since WPAS 4.0
		 *
		 */
		public function deactivate() {
			flush_rewrite_rules();
			$this->remove_caps_roles();
			$this->reset_options();
		}
		/**
		 * Sets caps and roles
		 *
		 * @since WPAS 4.0
		 *
		 */
		public function set_roles_caps() {
			global $wp_roles;
			if (class_exists('WP_Roles')) {
				if (!isset($wp_roles)) {
					$wp_roles = new WP_Roles();
				}
			}
			if (is_object($wp_roles)) {
				$this->set_reset_caps($wp_roles, 'add');
			}
		}
		/**
		 * Removes caps and roles
		 *
		 * @since WPAS 4.0
		 *
		 */
		public function remove_caps_roles() {
			global $wp_roles;
			if (class_exists('WP_Roles')) {
				if (!isset($wp_roles)) {
					$wp_roles = new WP_Roles();
				}
			}
			if (is_object($wp_roles)) {
				$this->set_reset_caps($wp_roles, 'remove');
			}
		}
		/**
		 * Set , reset capabilities
		 *
		 * @since WPAS 4.0
		 * @param object $wp_roles
		 * @param string $type
		 *
		 */
		public function set_reset_caps($wp_roles, $type) {
			$caps['enable'] = Array(
				'manage_issue_cat' => Array(
					'administrator'
				) ,
				'assign_issue_status' => Array(
					'administrator',
					'editor',
					'author'
				) ,
				'edit_published_emd_projects' => Array(
					'administrator',
					'editor',
					'author'
				) ,
				'edit_browser' => Array(
					'administrator'
				) ,
				'edit_others_emd_projects' => Array(
					'administrator',
					'editor'
				) ,
				'delete_emd_issues' => Array(
					'administrator',
					'editor',
					'author',
					'contributor'
				) ,
				'manage_issue_tag' => Array(
					'administrator'
				) ,
				'assign_browser' => Array(
					'administrator',
					'editor',
					'author'
				) ,
				'manage_issue_status' => Array(
					'administrator'
				) ,
				'delete_issue_tag' => Array(
					'administrator'
				) ,
				'delete_project_status' => Array(
					'administrator'
				) ,
				'manage_project_status' => Array(
					'administrator'
				) ,
				'read_private_emd_issues' => Array(
					'administrator',
					'editor'
				) ,
				'edit_project_status' => Array(
					'administrator'
				) ,
				'read_private_emd_projects' => Array(
					'administrator',
					'editor'
				) ,
				'edit_emd_projects' => Array(
					'administrator',
					'editor',
					'author',
					'contributor'
				) ,
				'edit_published_emd_issues' => Array(
					'administrator',
					'editor',
					'author'
				) ,
				'delete_issue_priority' => Array(
					'administrator'
				) ,
				'delete_published_emd_projects' => Array(
					'administrator',
					'editor',
					'author'
				) ,
				'manage_browser' => Array(
					'administrator'
				) ,
				'manage_project_priority' => Array(
					'administrator'
				) ,
				'delete_browser' => Array(
					'administrator'
				) ,
				'manage_issue_priority' => Array(
					'administrator'
				) ,
				'edit_private_emd_projects' => Array(
					'administrator',
					'editor'
				) ,
				'edit_issue_cat' => Array(
					'administrator'
				) ,
				'manage_operating_system' => Array(
					'administrator'
				) ,
				'delete_issue_cat' => Array(
					'administrator'
				) ,
				'delete_emd_projects' => Array(
					'administrator',
					'editor',
					'author',
					'contributor'
				) ,
				'publish_emd_projects' => Array(
					'administrator',
					'editor',
					'author'
				) ,
				'assign_issue_cat' => Array(
					'administrator',
					'editor',
					'author'
				) ,
				'delete_private_emd_projects' => Array(
					'administrator',
					'editor'
				) ,
				'delete_project_priority' => Array(
					'administrator'
				) ,
				'assign_issue_priority' => Array(
					'administrator',
					'editor',
					'author'
				) ,
				'publish_emd_issues' => Array(
					'administrator',
					'editor',
					'author'
				) ,
				'assign_project_priority' => Array(
					'administrator',
					'editor',
					'author'
				) ,
				'edit_emd_issues' => Array(
					'administrator',
					'editor',
					'author',
					'contributor'
				) ,
				'delete_operating_system' => Array(
					'administrator'
				) ,
				'delete_published_emd_issues' => Array(
					'administrator',
					'editor',
					'author'
				) ,
				'edit_issue_status' => Array(
					'administrator'
				) ,
				'edit_issue_tag' => Array(
					'administrator'
				) ,
				'delete_issue_status' => Array(
					'administrator'
				) ,
				'delete_private_emd_issues' => Array(
					'administrator',
					'editor'
				) ,
				'edit_project_priority' => Array(
					'administrator'
				) ,
				'assign_operating_system' => Array(
					'administrator',
					'editor',
					'author'
				) ,
				'edit_operating_system' => Array(
					'administrator'
				) ,
				'edit_private_emd_issues' => Array(
					'administrator',
					'editor'
				) ,
				'delete_others_emd_projects' => Array(
					'administrator',
					'editor'
				) ,
				'assign_issue_tag' => Array(
					'administrator',
					'editor',
					'author'
				) ,
				'assign_project_status' => Array(
					'administrator',
					'editor',
					'author'
				) ,
				'edit_others_emd_issues' => Array(
					'administrator',
					'editor'
				) ,
				'edit_issue_priority' => Array(
					'administrator'
				) ,
				'delete_others_emd_issues' => Array(
					'administrator',
					'editor'
				) ,
			);
			foreach ($caps as $stat => $role_caps) {
				foreach ($role_caps as $mycap => $roles) {
					foreach ($roles as $myrole) {
						if (($type == 'add' && $stat == 'enable') || ($stat == 'disable' && $type == 'remove')) {
							$wp_roles->add_cap($myrole, $mycap);
						} else if (($type == 'remove' && $stat == 'enable') || ($type == 'add' && $stat == 'disable')) {
							$wp_roles->remove_cap($myrole, $mycap);
						}
					}
				}
			}
		}
		/**
		 * Set app specific options
		 *
		 * @since WPAS 4.0
		 *
		 */
		private function set_options() {
			update_option($this->option_name . '_setup_pages', 1);
			$ent_list = Array(
				'emd_project' => Array(
					'label' => __('Projects', 'sim-com') ,
					'unique_keys' => Array(
						'emd_prj_name',
						'emd_prj_version'
					)
				) ,
				'emd_issue' => Array(
					'label' => __('Issues', 'sim-com') ,
					'unique_keys' => Array(
						'emd_iss_id'
					)
				) ,
			);
			update_option($this->option_name . '_ent_list', $ent_list);
			$shc_list['app'] = 'Software Issue Manager';
			$shc_list['forms']['issue_entry'] = Array(
				'name' => 'issue_entry',
				'page_title' => __('Issue Entry', 'sim-com')
			);
			$shc_list['forms']['issue_search'] = Array(
				'name' => 'issue_search',
				'page_title' => __('Search Issues', 'sim-com')
			);
			if (!empty($shc_list)) {
				update_option($this->option_name . '_shc_list', $shc_list);
			}
			$attr_list['emd_project']['emd_prj_name'] = Array(
				'label' => __('Name', 'sim-com') ,
				'display_type' => 'text',
				'required' => 1,
				"type" => "char"
			);
			$attr_list['emd_project']['emd_prj_version'] = Array(
				'label' => __('Version', 'sim-com') ,
				'display_type' => 'text',
				'required' => 1,
				"default" => "V1.0.0",
				"type" => "char"
			);
			$attr_list['emd_project']['emd_prj_start_date'] = Array(
				'label' => __('Start Date', 'sim-com') ,
				'display_type' => 'date',
				'required' => 1,
				"type" => "date",
				"dformat" => array(
					'dateFormat' => 'mm-dd-yy'
				)
			);
			$attr_list['emd_project']['emd_prj_target_end_date'] = Array(
				'label' => __('Target End Date', 'sim-com') ,
				'display_type' => 'date',
				'required' => 0,
				"type" => "date",
				"dformat" => array(
					'dateFormat' => 'mm-dd-yy'
				)
			);
			$attr_list['emd_project']['emd_prj_actual_end_date'] = Array(
				'label' => __('Actual End Date', 'sim-com') ,
				'display_type' => 'date',
				'required' => 0,
				"type" => "date",
				"dformat" => array(
					'dateFormat' => 'mm-dd-yy'
				)
			);
			$attr_list['emd_project']['emd_prj_file'] = Array(
				'label' => __('Documents', 'sim-com') ,
				'display_type' => 'file',
				'required' => 0,
				"type" => "char"
			);
			$attr_list['emd_issue']['emd_iss_id'] = Array(
				'label' => __('ID', 'sim-com') ,
				'display_type' => 'hidden',
				'required' => 0,
				"type" => "char"
			);
			$attr_list['emd_issue']['emd_iss_due_date'] = Array(
				'label' => __('Due Date', 'sim-com') ,
				'display_type' => 'date',
				'required' => 0,
				"type" => "date",
				"dformat" => array(
					'dateFormat' => 'mm-dd-yy'
				)
			);
			$attr_list['emd_issue']['emd_iss_resolution_summary'] = Array(
				'label' => __('Resolution Summary', 'sim-com') ,
				'display_type' => 'wysiwyg',
				'required' => 0,
				"type" => "char"
			);
			$attr_list['emd_issue']['emd_iss_document'] = Array(
				'label' => __('Documents', 'sim-com') ,
				'display_type' => 'file',
				'required' => 0,
				"type" => "char"
			);
			$attr_list['emd_issue']['wpas_form_name'] = Array(
				'label' => __('Form Name', 'sim-com') ,
				'display_type' => 'hidden',
				'required' => 0,
				"default" => "admin",
				"type" => "char"
			);
			$attr_list['emd_issue']['wpas_form_submitted_by'] = Array(
				'label' => __('Form Submitted By', 'sim-com') ,
				'display_type' => 'hidden',
				'required' => 0,
				"type" => "char"
			);
			$attr_list['emd_issue']['wpas_form_submitted_ip'] = Array(
				'label' => __('Form Submitted IP', 'sim-com') ,
				'display_type' => 'hidden',
				'required' => 0,
				"type" => "char"
			);
			if (!empty($attr_list)) {
				update_option($this->option_name . '_attr_list', $attr_list);
			}
			$tax_list['emd_issue']['issue_priority'] = Array(
				'label' => __('Priorities', 'sim-com') ,
				'default' => Array(
					__('Normal', 'sim-com')
				) ,
				'type' => 'single'
			);
			$tax_list['emd_issue']['issue_status'] = Array(
				'label' => __('Statuses', 'sim-com') ,
				'default' => Array(
					__('Open', 'sim-com')
				) ,
				'type' => 'single'
			);
			$tax_list['emd_issue']['issue_cat'] = Array(
				'label' => __('Categories', 'sim-com') ,
				'default' => Array(
					__('Bug', 'sim-com')
				) ,
				'type' => 'single'
			);
			$tax_list['emd_issue']['issue_tag'] = Array(
				'label' => __('Tags', 'sim-com') ,
				'default' => '',
				'type' => 'multi'
			);
			$tax_list['emd_project']['project_status'] = Array(
				'label' => __('Statuses', 'sim-com') ,
				'default' => Array(
					__('Draft', 'sim-com')
				) ,
				'type' => 'single'
			);
			$tax_list['emd_project']['project_priority'] = Array(
				'label' => __('Priorities', 'sim-com') ,
				'default' => Array(
					__('Medium', 'sim-com')
				) ,
				'type' => 'single'
			);
			$tax_list['emd_issue']['browser'] = Array(
				'label' => __('Browsers', 'sim-com') ,
				'default' => '',
				'type' => 'multi'
			);
			$tax_list['emd_issue']['operating_system'] = Array(
				'label' => __('Operating Systems', 'sim-com') ,
				'default' => '',
				'type' => 'multi'
			);
			if (!empty($tax_list)) {
				update_option($this->option_name . '_tax_list', $tax_list);
			}
			$rel_list['rel_project_issues'] = Array(
				'from' => 'emd_project',
				'to' => 'emd_issue',
				'from_title' => __('Project Issues', 'sim-com') ,
				'to_title' => __('Affected Projects', 'sim-com')
			);
			if (!empty($rel_list)) {
				update_option($this->option_name . '_rel_list', $rel_list);
			}
			$emd_activated_plugins = get_option('emd_activated_plugins');
			if (!$emd_activated_plugins) {
				update_option('emd_activated_plugins', Array(
					'sim-com'
				));
			} else {
				array_push($emd_activated_plugins, 'sim-com');
				update_option('emd_activated_plugins', $emd_activated_plugins);
			}
			//conf parameters for incoming email
			$has_incoming_email = Array(
				'emd_issue' => Array(
					'label' => 'Issues',
					'status' => 'publish',
					'vis_submit' => 0,
					'tax' => 'issue_tag',
					'subject' => 'blt_title',
					'date' => Array(
						'post_date'
					) ,
					'body' => 'emd_blt_content',
					'att' => 'emd_iss_document',
					'email' => '',
					'name' => ''
				)
			);
			update_option($this->option_name . '_has_incoming_email', $has_incoming_email);
			$emd_inc_email_apps = get_option('emd_inc_email_apps');
			$emd_inc_email_apps[$this->option_name] = $this->option_name . '_inc_email_conf';
			update_option('emd_inc_email_apps', $emd_inc_email_apps);
			//action to configure different extension conf parameters for this plugin
			do_action('emd_extension_set_conf');
		}
		/**
		 * Reset app specific options
		 *
		 * @since WPAS 4.0
		 *
		 */
		private function reset_options() {
			delete_option($this->option_name . '_ent_list');
			delete_option($this->option_name . '_shc_list');
			delete_option($this->option_name . '_attr_list');
			delete_option($this->option_name . '_tax_list');
			delete_option($this->option_name . '_rel_list');
			$users = get_users();
			foreach ($users as $user) {
				delete_user_meta($user->ID, $this->option_name . '_adm_share');
			}
			$users = get_users();
			foreach ($users as $user) {
				delete_user_meta($user->ID, $this->option_name . '_adm_notice1');
			}
			$users = get_users();
			foreach ($users as $user) {
				delete_user_meta($user->ID, $this->option_name . '_adm_notice2');
			}
			delete_option($this->option_name . '_setup_pages');
			$emd_activated_plugins = get_option('emd_activated_plugins');
			if (!empty($emd_activated_plugins)) {
				$emd_activated_plugins = array_diff($emd_activated_plugins, Array(
					'sim-com'
				));
				update_option('emd_activated_plugins', $emd_activated_plugins);
			}
			$incemail_settings = get_option('emd_inc_email_apps', Array());
			unset($incemail_settings[$this->option_name]);
			update_option('emd_inc_email_apps', $incemail_settings);
			delete_option($this->option_name . '_has_incoming_email');
		}
		/**
		 * Show install notices
		 *
		 * @since WPAS 4.0
		 *
		 * @return html
		 */
		public function install_notice() {
			if (isset($_GET[$this->option_name . '_adm_share'])) {
				update_user_meta(get_current_user_id() , $this->option_name . '_adm_share', true);
			}
			if (get_user_option($this->option_name . '_adm_share') != 1) {
				$product_uri = 'goo.gl/5ZOaZh';
				$product_desc = 'Software Issue Manager allows to track the resolution of every project issue in a productive and efficient way.';
				$product_name = 'Software Issue Manager';
				$product_image = 'https://emdplugins.com/campaign_images/sim_pro_banner.gif';
?>
<div class="updated">
<span style="color:red;font-weight:700;">Share this delight with others! <i class="icon-arrow-right"></i></span>
                <span class="dashicons dashicons-smiley"></span>
                <ul style="margin:0 !important;display: inline-block;padding-left:20px;">
                <li style="display: inline-block;"><a href="javascript:twitterShare('http://<?php echo $product_uri; ?>','<?php echo $product_desc; ?>', 602,496);" data-lang="en"><img src="<?php echo SIM_COM_PLUGIN_URL . 'assets/img/twitter_icon.jpg'; ?>" alt="Share on Twitter" /></a></li>
                <li style="display: inline-block;"><a href="" onclick="javascript:fbShare('<?php echo $product_uri; ?>','<?php echo $product_name; ?>','<?php echo $product_desc; ?>', 600, 400);return false;" target="_blank"><img src="<?php echo SIM_COM_PLUGIN_URL . 'assets/img/fb_icon.jpg'; ?>" alt="Share on Facebook" /></a></li>
                <li style="display: inline-block;"><a href="javascript:gplusShare('<?php echo $product_uri; ?>', 483, 540)"><img src="<?php echo SIM_COM_PLUGIN_URL . 'assets/img/gplus_icon.jpg'; ?>" alt="Share on Google+"/></a></li>
                <li style="display: inline-block;"><a href="http://www.tumblr.com/share/link?url=<?php echo $product_uri; ?>&amp;name=<?php echo $product_name; ?>&amp;description=<?php echo $product_desc; ?>" title="Share on Tumblr" target="_blank"><img src="<?php echo SIM_COM_PLUGIN_URL . 'assets/img/tumblr_icon.jpg'; ?>" alt="Share on Tumblr"/></a></li>
                <li style="display: inline-block;"><a href="javascript:pinterestShare('<?php echo $product_uri; ?>', '<?php echo $product_image; ?>', '<?php echo $product_desc; ?>', 774, 452)" data-pin-do="buttonPin" ><img src="<?php echo SIM_COM_PLUGIN_URL . 'assets/img/pinterest_icon.jpg'; ?>" alt="Share on Pinterest"/></a></li>
                <li style="display: inline-block;"><a href="javascript:stumbleuponShare('<?php echo $product_uri; ?>', 802, 592)"><img src="<?php echo SIM_COM_PLUGIN_URL . 'assets/img/stumbleupon_icon.jpg'; ?>" alt="Share on Stumbleupon"/></a></li>
                <li style="display: inline-block;"><a href="javascript:linkedinShare('<?php echo $product_uri; ?>', '<?php echo $product_name; ?>', '<?php echo $product_desc; ?>', 850, 450)"><img src="<?php echo SIM_COM_PLUGIN_URL . 'assets/img/linkedin_icon.jpg'; ?>" alt="Share on LinkedIn"/></a></li>
                <li style="display: inline-block;"><a href="javascript:redditShare('<?php echo $product_uri; ?>', 800, 400)"><img src="<?php echo SIM_COM_PLUGIN_URL . 'assets/img/reddit_icon.jpg'; ?>" alt="Share on Reddit"/></a></li>
                <li style="display: inline-block;"><a href="mailto:?subject=<?php echo $product_name; ?>&amp;body=<?php echo $product_desc . "
 http://" . $product_uri; ?>"><img src="<?php echo SIM_COM_PLUGIN_URL . 'assets/img/email_icon.jpg'; ?>" alt="Email to a friend"/></a></li>
                </ul>
                <a style="float:right;" href="<?php echo esc_url(add_query_arg($this->option_name . '_adm_share', true)); ?>"><span class="dashicons dashicons-dismiss" style="font-size:15px;"></span><?php _e('Dismiss', 'sim_com'); ?></a>
                
</div>
<?php
			}
			if (isset($_GET[$this->option_name . '_adm_notice1'])) {
				update_user_meta(get_current_user_id() , $this->option_name . '_adm_notice1', true);
			}
			if (get_user_option($this->option_name . '_adm_notice1') != 1) {
?>
<div class="updated">
<?php
				printf('<p><a href="%1s" target="_blank"> %2$s </a>%3$s<a style="float:right;" href="%4$s"><span class="dashicons dashicons-dismiss" style="font-size:15px;"></span>%5$s</a></p>', 'https://docs.emdplugins.com/docs/software-issue-manager-community-documentation/?pk_campaign=simcom&pk_source=plugin&pk_medium=link&pk_content=notice', __('New To Software Issue Manager? Review the documentation!', 'wpas') , __('&#187;', 'wpas') , esc_url(add_query_arg($this->option_name . '_adm_notice1', true)) , __('Dismiss', 'wpas'));
?>
</div>
<?php
			}
			if (isset($_GET[$this->option_name . '_adm_notice2'])) {
				update_user_meta(get_current_user_id() , $this->option_name . '_adm_notice2', true);
			}
			if (get_user_option($this->option_name . '_adm_notice2') != 1) {
?>
<div class="updated">
<?php
				printf('<p><a href="%1s" target="_blank"> %2$s </a>%3$s<a style="float:right;" href="%4$s"><span class="dashicons dashicons-dismiss" style="font-size:15px;"></span>%5$s</a></p>', 'https://emdplugins.com/plugins/incoming-email-extension/?pk_campaign=simcom&pk_source=plugin&pk_medium=link&pk_content=notice', __('Buy WPAS Incoming Email Extension to create issues through emails!', 'wpas') , __('&#187;', 'wpas') , esc_url(add_query_arg($this->option_name . '_adm_notice2', true)) , __('Dismiss', 'wpas'));
?>
</div>
<?php
			}
			if (get_option($this->option_name . '_setup_pages') == 1) {
				echo "<div id=\"message\" class=\"updated\"><p><strong>" . __('Welcome to Software Issue Manager', 'sim-com') . "</strong></p>
           <p class=\"submit\"><a href=\"" . add_query_arg('setup_sim_com_pages', 'true', admin_url('index.php')) . "\" class=\"button-primary\">" . __('Setup Software Issue Manager Pages', 'sim-com') . "</a> <a class=\"skip button-primary\" href=\"" . add_query_arg('skip_setup_sim_com_pages', 'true', admin_url('index.php')) . "\">" . __('Skip setup', 'sim-com') . "</a></p>
         </div>";
			}
		}
		/**
		 * Setup pages for components and redirect to dashboard
		 *
		 * @since WPAS 4.0
		 *
		 */
		public function setup_pages() {
			if (!is_admin()) {
				return;
			}
			global $wpdb;
			if (!empty($_GET['setup_' . $this->option_name . '_pages'])) {
				$shc_list = get_option($this->option_name . '_shc_list');
				$types = Array(
					'forms',
					'charts',
					'shcs',
					'datagrids',
					'integrations'
				);
				foreach ($types as $shc_type) {
					if (!empty($shc_list[$shc_type])) {
						foreach ($shc_list[$shc_type] as $keyshc => $myshc) {
							if (isset($myshc['page_title'])) {
								$pages[$keyshc] = $myshc;
							}
						}
					}
				}
				foreach ($pages as $key => $page) {
					$found = "";
					$page_content = "[" . $key . "]";
					$found = $wpdb->get_var($wpdb->prepare("SELECT ID FROM " . $wpdb->posts . " WHERE post_type='page' AND post_content LIKE %s LIMIT 1;", "%{$page_content}%"));
					if ($found != "") {
						continue;
					}
					$page_data = array(
						'post_status' => 'publish',
						'post_type' => 'page',
						'post_author' => get_current_user_id() ,
						'post_title' => $page['page_title'],
						'post_content' => $page_content,
						'comment_status' => 'closed'
					);
					$page_id = wp_insert_post($page_data);
				}
				delete_option($this->option_name . '_setup_pages');
				wp_redirect(admin_url('index.php?sim-com-installed=true'));
				exit;
			}
			if (!empty($_GET['skip_setup_' . $this->option_name . '_pages'])) {
				delete_option($this->option_name . '_setup_pages');
				wp_redirect(admin_url('index.php?'));
				exit;
			}
		}
		/**
		 * Delete file attachments when a post is deleted
		 *
		 * @since WPAS 4.0
		 * @param $pid
		 *
		 * @return bool
		 */
		public function delete_post_file_att($pid) {
			$entity_fields = get_option($this->option_name . '_attr_list');
			$post_type = get_post_type($pid);
			if (!empty($entity_fields[$post_type])) {
				//Delete fields
				foreach (array_keys($entity_fields[$post_type]) as $myfield) {
					if (in_array($entity_fields[$post_type][$myfield]['display_type'], Array(
						'file',
						'image',
						'plupload_image',
						'thickbox_image'
					))) {
						$pmeta = get_post_meta($pid, $myfield);
						if (!empty($pmeta)) {
							foreach ($pmeta as $file_id) {
								wp_delete_attachment($file_id);
							}
						}
					}
				}
			}
			return true;
		}
	}
endif;
return new Sim_Com_Install_Deactivate();
