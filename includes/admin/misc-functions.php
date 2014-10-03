<?php
/**
 * Misc Admin Functions
 *
 * @package SIM_COM
 * @version 1.0.0
 * @since WPAS 4.0
 */
if (!defined('ABSPATH')) exit;
add_action('edit_form_advanced', 'sim_com_force_post_builtin');
/**
 * Add required js check for builtin fields and taxonomies
 *
 * @since WPAS 4.0
 *
 * @return js
 */
function sim_com_force_post_builtin() {
	$post = get_post();
	if (in_array($post->post_type, Array(
		'emd_issue',
		'emd_project'
	))) { ?>
   <script type='text/javascript'>
       jQuery('#publish').click(function(){
           var msg = [];
           <?php if (in_array($post->post_type, Array(
			'emd_issue'
		))) { ?>
   var title = jQuery('[id^="titlediv"]').find('#title');
   if(title.val().length < 1) {
       jQuery('#title').addClass('error');
       msg.push('<?php _e('Title', 'sim-com'); ?>');
   }
<?php
		} ?>
           
           
           <?php if (in_array($post->post_type, Array(
			'emd_issue'
		))) { ?>
      var tcount = jQuery("input[name='radio_tax_input[issue_cat][]']:checked").length;
      if(tcount < 1){
         jQuery('#radio-tagsdiv-issue_cat').css({'border-left':'4px solid #DD3D36'});
         msg.push('<?php _e('Categories Taxonomy', 'sim-com'); ?>');
       }else{
         jQuery('#radio-tagsdiv-issue_cat').attr('style','');
       }
<?php
		} ?>
<?php if (in_array($post->post_type, Array(
			'emd_issue'
		))) { ?>
      var tcount = jQuery("input[name='radio_tax_input[issue_priority][]']:checked").length;
      if(tcount < 1){
         jQuery('#radio-tagsdiv-issue_priority').css({'border-left':'4px solid #DD3D36'});
         msg.push('<?php _e('Priorities Taxonomy', 'sim-com'); ?>');
       }else{
         jQuery('#radio-tagsdiv-issue_priority').attr('style','');
       }
<?php
		} ?>
<?php if (in_array($post->post_type, Array(
			'emd_project'
		))) { ?>
      var tcount = jQuery("input[name='radio_tax_input[project_status][]']:checked").length;
      if(tcount < 1){
         jQuery('#radio-tagsdiv-project_status').css({'border-left':'4px solid #DD3D36'});
         msg.push('<?php _e('Statuses Taxonomy', 'sim-com'); ?>');
       }else{
         jQuery('#radio-tagsdiv-project_status').attr('style','');
       }
<?php
		} ?>
<?php if (in_array($post->post_type, Array(
			'emd_project'
		))) { ?>
      var tcount = jQuery("input[name='radio_tax_input[project_priority][]']:checked").length;
      if(tcount < 1){
         jQuery('#radio-tagsdiv-project_priority').css({'border-left':'4px solid #DD3D36'});
         msg.push('<?php _e('Priorities Taxonomy', 'sim-com'); ?>');
       }else{
         jQuery('#radio-tagsdiv-project_priority').attr('style','');
       }
<?php
		} ?>
<?php if (in_array($post->post_type, Array(
			'emd_issue'
		))) { ?>
      var tcount = jQuery("input[name='radio_tax_input[issue_status][]']:checked").length;
      if(tcount < 1){
         jQuery('#radio-tagsdiv-issue_status').css({'border-left':'4px solid #DD3D36'});
         msg.push('<?php _e('Statuses Taxonomy', 'sim-com'); ?>');
       }else{
         jQuery('#radio-tagsdiv-issue_status').attr('style','');
       }
<?php
		} ?>
           if(msg.length > 0){
              jQuery('#publish').removeClass('button-primary-disabled');
              jQuery('#ajax-loading').attr( 'style','');
              jQuery('#post').siblings('#message').remove();
              jQuery('#post').before('<div id="message" class="error"><p>'+msg.join(', ')+' <?php _e('required', 'sim-com'); ?>.</p></div>');
              return false; 
           }
       }); 
    </script>
<?php
	}
}
