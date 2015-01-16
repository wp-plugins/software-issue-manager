<?php $ent_attrs = get_option('sim_com_attr_list'); ?>
<div class="emd-container">
<?php $blt_content = $post->post_content;
if (!empty($blt_content)) { ?>
   <div id="emd-project-blt-content-div" class="emd-single-div">
   <div id="emd-project-blt-content-key" class="emd-single-title">
   <?php _e('Content', 'sim-com'); ?>
   </div>
   <div id="emd-project-blt-content-val" class="emd-single-val">
   <?php echo $blt_content; ?>
   </div>
   </div>
<?php
} ?>
<?php
$emd_prj_name = rwmb_meta('emd_prj_name');
if (!empty($emd_prj_name)) { ?>
   <div id="emd-project-emd-prj-name-div" class="emd-single-div">
   <div id="emd-project-emd-prj-name-key" class="emd-single-title">
<?php _e('Name', 'sim-com'); ?>
   </div>
   <div id="emd-project-emd-prj-name-val" class="emd-single-val">
<?php echo $emd_prj_name; ?>
   </div>
   </div>
<?php
} ?>
<?php
$emd_prj_version = rwmb_meta('emd_prj_version');
if (!empty($emd_prj_version)) { ?>
   <div id="emd-project-emd-prj-version-div" class="emd-single-div">
   <div id="emd-project-emd-prj-version-key" class="emd-single-title">
<?php _e('Version', 'sim-com'); ?>
   </div>
   <div id="emd-project-emd-prj-version-val" class="emd-single-val">
<?php echo $emd_prj_version; ?>
   </div>
   </div>
<?php
} ?>
<?php $emd_prj_start_date = rwmb_meta('emd_prj_start_date');
if (!empty($emd_prj_start_date)) {
	$emd_prj_start_date = emd_translate_date_format($ent_attrs['emd_project']['emd_prj_start_date'], $emd_prj_start_date, 1);
?>
   <div id="emd-project-emd-prj-start-date-div" class="emd-single-div">
   <div id="emd-project-emd-prj-start-date-key" class="emd-single-title">
   <?php _e('Start Date', 'sim-com'); ?>
   </div>
   <div id="emd-project-emd-prj-start-date-val" class="emd-single-val">
   <?php echo esc_html($emd_prj_start_date); ?>
   </div></div>
<?php
} ?>
<?php $emd_prj_target_end_date = rwmb_meta('emd_prj_target_end_date');
if (!empty($emd_prj_target_end_date)) {
	$emd_prj_target_end_date = emd_translate_date_format($ent_attrs['emd_project']['emd_prj_target_end_date'], $emd_prj_target_end_date, 1);
?>
   <div id="emd-project-emd-prj-target-end-date-div" class="emd-single-div">
   <div id="emd-project-emd-prj-target-end-date-key" class="emd-single-title">
   <?php _e('Target End Date', 'sim-com'); ?>
   </div>
   <div id="emd-project-emd-prj-target-end-date-val" class="emd-single-val">
   <?php echo esc_html($emd_prj_target_end_date); ?>
   </div></div>
<?php
} ?>
<?php $emd_prj_actual_end_date = rwmb_meta('emd_prj_actual_end_date');
if (!empty($emd_prj_actual_end_date)) {
	$emd_prj_actual_end_date = emd_translate_date_format($ent_attrs['emd_project']['emd_prj_actual_end_date'], $emd_prj_actual_end_date, 1);
?>
   <div id="emd-project-emd-prj-actual-end-date-div" class="emd-single-div">
   <div id="emd-project-emd-prj-actual-end-date-key" class="emd-single-title">
   <?php _e('Actual End Date', 'sim-com'); ?>
   </div>
   <div id="emd-project-emd-prj-actual-end-date-val" class="emd-single-val">
   <?php echo esc_html($emd_prj_actual_end_date); ?>
   </div></div>
<?php
} ?>
<?php $rwmb_file = rwmb_meta('emd_prj_file', 'type=file');
if (!empty($rwmb_file)) { ?>
  <div id="emd-project-emd-prj-file-div" class="emd-single-div">
  <div id="emd-project-emd-prj-file-key" class="emd-single-title">
  <?php _e('Documents', 'sim-com'); ?>
  </div>
  <div id="emd-project-emd-prj-file-val" class="emd-single-val">
  <?php foreach ($rwmb_file as $info) { ?>
  <a href='<?php echo esc_url($info['url']); ?>' target='_blank' title='<?php echo esc_attr($info['title']); ?>'><?php echo esc_html($info['name']); ?>
   </a><br />
  <?php
	} ?>
  </div>
  </div>
<?php
} ?>
<?php
$taxlist = get_object_taxonomies(get_post_type() , 'objects');
foreach ($taxlist as $taxkey => $mytax) {
	$termlist = get_the_term_list(get_the_ID() , $taxkey, '', ' , ', '');
	if (!empty($termlist)) { ?>
      <div id="emd-project-<?php echo esc_attr($taxkey); ?>-div" class="emd-single-div">
      <div id="emd-project-<?php echo esc_attr($taxkey); ?>-key" class="emd-single-title">
      <?php echo esc_html($mytax->labels->singular_name); ?>
      </div>
      <div id="emd-project-<?php echo esc_attr($taxkey); ?>-val" class="emd-single-val">
      <?php echo $termlist; ?>
      </div>
      </div>
   <?php
	}
} ?>
<div id="emd_project-emd_issue-relation-sec" class="relation-sec"><div class='connected-div' id='rel-project-issues-connected'>
				<div class='connected-title' id='rel-project-issues-connected-title'><?php echo __('Project Issues', 'sim-com'); ?></div>
<?php $post = get_post();
$res = emd_get_p2p_connections('connected', 'project_issues', 'ul', $post, 1, 0);
if (!empty($res['rels'])) {
	echo $res['before_list'];
	$real_post = $post;
	foreach ($res['rels'] as $myrel) {
		$post = $myrel;
		echo $res['before_item']; ?>
<a href="<?php echo get_permalink($post->ID); ?>"><?php echo get_the_title(); ?></a><?php
		echo $res['after_item'];
	}
	$post = $real_post;
	echo $res['after_list'];
} ?>
</div></div>
</div><!--container-end-->