<?php global $sc_issues_count;
$ent_attrs = get_option('sim_com_attr_list'); ?>
<tr>
    <td class="results-cell"><a href="<?php echo get_permalink(); ?>"><?php echo esc_html(emd_mb_meta('emd_iss_id')); ?>
</a></td>
    <td class="results-cell"><?php echo get_the_title(); ?></td>
    <td class="results-cell"><?php echo get_the_term_list(get_the_ID() , 'issue_cat', '', ' ', ''); ?></td>
    <td class="results-cell"><?php echo get_the_term_list(get_the_ID() , 'issue_status', '', ' ', ''); ?></td>
    <td class="results-cell"><?php echo get_the_term_list(get_the_ID() , 'issue_priority', '', ' ', ''); ?></td>
</tr>