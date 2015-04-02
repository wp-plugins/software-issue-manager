<?php $ent_attrs = get_option('sim_com_attr_list'); ?>
<div class="emd-container">
<?php $blt_content = $post->post_content;
if (!empty($blt_content)) { ?>
   <div id="emd-issue-blt-content-div" class="emd-single-div">
   <div id="emd-issue-blt-content-key" class="emd-single-title">
   <?php _e('Content', 'sim-com'); ?>
   </div>
   <div id="emd-issue-blt-content-val" class="emd-single-val">
   <?php echo $blt_content; ?>
   </div>
   </div>
<?php
} ?>
<?php $emd_iss_due_date = emd_mb_meta('emd_iss_due_date');
if (!empty($emd_iss_due_date)) {
	$emd_iss_due_date = emd_translate_date_format($ent_attrs['emd_issue']['emd_iss_due_date'], $emd_iss_due_date, 1);
?>
   <div id="emd-issue-emd-iss-due-date-div" class="emd-single-div">
   <div id="emd-issue-emd-iss-due-date-key" class="emd-single-title">
   <?php _e('Due Date', 'sim-com'); ?>
   </div>
   <div id="emd-issue-emd-iss-due-date-val" class="emd-single-val">
   <?php echo esc_html($emd_iss_due_date); ?>
   </div></div>
<?php
} ?>
<?php
$emd_iss_id = emd_mb_meta('emd_iss_id');
if (!empty($emd_iss_id)) { ?>
   <div id="emd-issue-emd-iss-id-div" class="emd-single-div">
   <div id="emd-issue-emd-iss-id-key" class="emd-single-title">
<?php _e('ID', 'sim-com'); ?>
   </div>
   <div id="emd-issue-emd-iss-id-val" class="emd-single-val">
<?php echo $emd_iss_id; ?>
   </div>
   </div>
<?php
} ?>
<?php
$emd_iss_resolution_summary = emd_mb_meta('emd_iss_resolution_summary');
if (!empty($emd_iss_resolution_summary)) { ?>
   <div id="emd-issue-emd-iss-resolution-summary-div" class="emd-single-div">
   <div id="emd-issue-emd-iss-resolution-summary-key" class="emd-single-title">
<?php _e('Resolution Summary', 'sim-com'); ?>
   </div>
   <div id="emd-issue-emd-iss-resolution-summary-val" class="emd-single-val">
<?php echo $emd_iss_resolution_summary; ?>
   </div>
   </div>
<?php
} ?>
<?php $emd_mb_file = emd_mb_meta('emd_iss_document', 'type=file');
if (!empty($emd_mb_file)) { ?>
  <div id="emd-issue-emd-iss-document-div" class="emd-single-div">
  <div id="emd-issue-emd-iss-document-key" class="emd-single-title">
  <?php _e('Documents', 'sim-com'); ?>
  </div>
  <div id="emd-issue-emd-iss-document-val" class="emd-single-val">
  <?php foreach ($emd_mb_file as $info) {
		$fsrc = wp_mime_type_icon($info['ID']);
?>
  <a href='<?php echo esc_url($info['url']); ?>' target='_blank' title='<?php echo esc_attr($info['title']); ?>'><img src='<?php echo $fsrc; ?>' title='<?php echo esc_html($info['name']); ?>' width='20' />
   </a>
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
      <div id="emd-issue-<?php echo esc_attr($taxkey); ?>-div" class="emd-single-div">
      <div id="emd-issue-<?php echo esc_attr($taxkey); ?>-key" class="emd-single-title">
      <?php echo esc_html($mytax->labels->singular_name); ?>
      </div>
      <div id="emd-issue-<?php echo esc_attr($taxkey); ?>-val" class="emd-single-val">
      <?php echo $termlist; ?>
      </div>
      </div>
   <?php
	}
} ?>
<div id="emd_issue-emd_project-relation-sec" class="relation-sec"><div class='connected-div' id='rel-project-issues-connected'>
				<div class='connected-title' id='rel-project-issues-connected-title'><?php echo __('Affected Projects', 'sim-com'); ?></div>
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