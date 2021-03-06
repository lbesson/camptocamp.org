<?php 
use_helper('Pagination', 'Field', 'SmartDate', 'SmartFormat', 'sfBBCode', 'Viewer', 'ModalBox', 'Lightbox', 'Javascript', 'MyImage');
$mobile_version =  c2cTools::mobileVersion();

$format = $sf_data->getRaw('format');
$main_title = (in_array('full', $format)) ? 'conditions and comments' : 'recent conditions';

echo display_title(__($main_title), 'outings', false);

if (!$mobile_version)
{
    echo '<div id="nav_space">&nbsp;</div>';
    include_partial('nav4list');
}

$format = $sf_data->getRaw('format');
$format_full = in_array('full', $format);

$conditions_statuses = sfConfig::get('mod_outings_conditions_statuses_list');
$access_statuses = sfConfig::get('mod_outings_access_statuses_list');
$glacier_statuses = sfConfig::get('mod_outings_glacier_statuses_list');
$frequentation_statuses = sfConfig::get('mod_outings_frequentation_statuses_list');
$hut_statuses = sfConfig::get('mod_outings_hut_statuses_list');

echo display_content_top('list_content');
echo start_content_tag('outings_content');

if (!isset($items) || count($items) == 0):

    echo __('there is no %1% to show', array('%1%' => __('outings')));

else:
    
    echo '<p class="list_header">'
       . link_to_outings(__('Show as a list'))
       . ' &nbsp; ' . link_to_associated_images(__('List all linked images'), 'outings', array('orderby' => 'odate', 'order' => 'desc'));
    if ($show_images)
    {
        echo '';
        $nb_images = 0;
    }
    echo '</p>';
    
    $pager_navigation = pager_navigation($pager, array('list_header'));
    echo $pager_navigation;
    echo pager_nb_results($pager);
    
    $class = 'recent_conditions';
    if ($show_images)
    {
        $class .= ' condimg';
        echo javascript_tag('lightbox_msgs = Array("' . __('View image details') . '","' . __('View original image') . '");');
    }
?>
<ul class="<?php echo $class ?>">
    <?php
    foreach ($items as $item): ?>
    <li><?php
        $i18n = $item['OutingI18n'][0];
        $item_id = $i18n['id'];
        $activities = $item['activities'];
        echo '<span class="item_title">' .
             '<time datetime="' . $item['date'] . '">' . format_date($item['date'], 'dd/MM/yyyy') . '</time> - ' .
             get_paginated_activities($activities, false, '&nbsp;') . ' - ' .
             link_to($i18n['name'],
                     '@document_by_id_lang_slug?module=outings&id=' . $item_id . '&lang=' . $i18n['culture'] . '&slug=' . make_slug($i18n['name'])) . ' - ' .
             displayWithSuffix($item['max_elevation'], 'meters') . ' - ' .
             field_route_ratings_data($item, false, true, false, 'html', $activities);
        if (isset($item['nb_images']))
        {
            echo ' - ' . picto_tag('picto_images', __('nb_linked_images')) . '&nbsp;' . $item['nb_images'];
        }
        if (isset($item['nb_comments']))
        {
            echo ' - ' . picto_tag('action_comment', __('nb_comments')) . '&nbsp;' . link_to($item['nb_comments'], '@document_comment?module=outings&id='
        . $item_id . '&lang=' . $i18n['culture']);
        }
        echo '</span>';
        ?>
        <ul>
            <?php
            $geoassociations = $item['geoassociations'];
            if (check_not_empty($geoassociations)): ?>
            <li>
            <?php 
            $areas = array();
            foreach ($geoassociations as $geo)
            {
                $areas[] = $geo['AreaI18n'][0]['name'];
            }
            echo implode(', ', $areas);
            ?></li>
            <?php
            endif;
            ?>
            <li><?php echo _format_data('author', link_to($item['creator'], '@document_by_id?module=users&id=' . $item['creator_id'])); ?></li>
            <?php
            // FIXME sfOutputEscaperObjectDecorator shouldn't be used..
            $access_elevation = check_not_empty($item['access_elevation']) && !($item['access_elevation'] instanceof sfOutputEscaperObjectDecorator) ? $item['access_elevation'] : 0;
            $access_status = $item['access_status'];
            $has_access_status = is_integer($access_status) && array_key_exists($access_status, $conditions_statuses);
            $up_snow_elevation = check_not_empty($item['up_snow_elevation']) && !($item['up_snow_elevation'] instanceof sfOutputEscaperObjectDecorator) ? $item['up_snow_elevation'] : 0;
            $down_snow_elevation = check_not_empty($item['down_snow_elevation']) && !($item['down_snow_elevation'] instanceof sfOutputEscaperObjectDecorator) ? $item['down_snow_elevation'] : 0;
            if (check_not_empty($access_elevation) || $has_access_status || check_not_empty($up_snow_elevation) || check_not_empty($down_snow_elevation)):
            ?>
            <li><?php
                if (check_not_empty($access_elevation))
                {
                    echo field_data_arg_if_set('access_elevation', $access_elevation, array('suffix' => 'meters'));
                    if ($has_access_status)
                    {
                        echo ' - ' . __($access_statuses[$access_status]);
                    }
                    echo ' &nbsp; ';
                }
                else if ($has_access_status)
                {
                    echo _format_data_from_list('access_status', $access_status, 'mod_outings_access_statuses_list') . ' &nbsp; ';
                }
                
                echo field_data_arg_range_if_set('up_snow_elevation', 'down_snow_elevation', $up_snow_elevation, $down_snow_elevation,
                                                 array('separator' => 'elevation separator',
                                                       'suffix'    => 'meters'));
            ?>
            </li>
            <?php
            endif;
            
            $outing_route_desc = $i18n['outing_route_desc'];
            $has_outing_route_desc = check_not_empty($outing_route_desc) && !($outing_route_desc instanceof sfOutputEscaperObjectDecorator);
            if ($has_outing_route_desc): ?>
                <li><div class="section_subtitle" id="_outing_route_desc" data-tooltip=""><?php echo __('outing_route_desc') ?></div><?php echo parse_links(parse_bbcode($outing_route_desc, null, false, false)) ?></li>
            <?php endif;
            
            $conditions = $i18n['conditions'];
            $has_conditions = check_not_empty($conditions) && !($conditions instanceof sfOutputEscaperObjectDecorator);
            $conditions_status = $item['conditions_status'];
            $has_conditions_status = is_integer($conditions_status) && !empty($conditions_status) && array_key_exists($conditions_status, $conditions_statuses);
            $glacier_status = $item['glacier_status'];
            $has_glacier_status = is_integer($glacier_status) && !empty($glacier_status) && array_key_exists($glacier_status, $glacier_statuses);
            $frequentation_status = $item['frequentation_status'];
            $has_frequentation_status = is_integer($frequentation_status) && !empty($frequentation_status) && array_key_exists($frequentation_status, $frequentation_statuses);
            $conditions_levels = unserialize($i18n->get('conditions_levels', ESC_RAW));
            $has_conditions_levels = !empty($conditions_levels) && count($conditions_levels);
            if ($has_conditions || $has_conditions_status || $has_glacier_status || $has_frequentation_status || $has_conditions_levels || $has_avalanche_date): ?>
                <li><div class="section_subtitle" id="_conditions" data-tooltip=""><?php echo __('conditions_status') ?></div>
                <?php
                if ($has_conditions_status)
                {
                    echo __($conditions_statuses[$conditions_status]) . ' &nbsp; ';
                }
                if ($has_glacier_status)
                {
                    echo _format_data_from_list('glacier_status', $glacier_status, 'mod_outings_glacier_statuses_list') . ' &nbsp; ';
                }
                if ($has_frequentation_status)
                {
                    echo _format_data_from_list('frequentation_status', $frequentation_status, 'mod_outings_frequentation_statuses_list');
                }
                
                if ($has_conditions_levels)
                {
                    echo conditions_levels_data($conditions_levels);
                }
                
                if ($has_conditions)
                {
                    echo parse_links(parse_bbcode($conditions, null, false, false));
                }
                ?>
                </li>
            <?php endif;
            
            $avalanche_date = $item['avalanche_date'];
            $avalanche_date_list = BaseDocument::convertStringToArray($avalanche_date);
            $has_avalanche_date = check_not_empty($avalanche_date) && !($avalanche_date instanceof sfOutputEscaperObjectDecorator) && count($avalanche_date_list) && !array_intersect(array(0, 1), $avalanche_date_list);
            $avalanche_desc = $i18n['avalanche_desc'];
            $has_avalanche_desc = $has_avalanche_date && check_not_empty($avalanche_desc) && !($avalanche_desc instanceof sfOutputEscaperObjectDecorator);
            if ($has_avalanche_date): ?>
                <li><div class="section_subtitle" id="_avalanche_infos" data-tooltip=""><?php echo __('avalanche_infos') ?></div>
                <?php
                $avalanche_date_string = get_paginated_value_from_list($avalanche_date, 'mod_outings_avalanche_date_list');
                echo '<p class="avalanche_date">'
                   . c2cTools::multibyte_ucfirst(trim($avalanche_date_string))
                   . '.'
                   . '</p>';

                if ($has_avalanche_desc)
                {
                    echo parse_links(parse_bbcode($avalanche_desc, null, false, false));
                }
                ?></li>
            <?php endif;

            $weather = $i18n['weather'];
            if (check_not_empty($weather) && !($weather instanceof sfOutputEscaperObjectDecorator)): //FIXME sfOutputEscaperObjectDecorator ?>
                <li><div class="section_subtitle" id="_weather" data-tooltip=""><?php echo __('weather') ?></div><?php echo parse_links(parse_bbcode($weather, null, false, false)) ?></li>
            <?php endif;
            
            $timing = $i18n['timing'];
            if (check_not_empty($timing) && !($timing instanceof sfOutputEscaperObjectDecorator)): //FIXME sfOutputEscaperObjectDecorator ?>
                <li><div class="section_subtitle" id="_weather" data-tooltip=""><?php echo __('timing') ?></div><?php echo parse_links(parse_bbcode($timing, null, false, false)) ?></li>
            <?php endif;
            
            if ($format_full)
            {
                $access_comments = $i18n['access_comments'];
                if (check_not_empty($access_comments) && !($access_comments instanceof sfOutputEscaperObjectDecorator)): //FIXME sfOutputEscaperObjectDecorator ?>
                    <li><div class="section_subtitle" id="_access_comments" data-tooltip=""><?php echo __('access_comments') ?></div><?php echo parse_links(parse_bbcode($access_comments, null, false, false)) ?></li>
                <?php endif;
                
                $hut_status = $item['hut_status'];
                $has_hut_status = is_integer($hut_status) && !empty($hut_status) && array_key_exists($hut_status, $hut_statuses);
                $hut_comments = $i18n['hut_comments'];
                $has_hut_comments = check_not_empty($hut_comments) && !($hut_comments instanceof sfOutputEscaperObjectDecorator);
                if ($has_hut_status || $has_hut_comments): //FIXME sfOutputEscaperObjectDecorator ?>
                    <li><div class="section_subtitle" id="_hut_comments" data-tooltip=""><?php echo __('hut_comments') ?></div> <?php
                        if ($has_hut_status)
                        {
                            echo __($hut_statuses[$hut_status]);
                        }
                        if ($has_hut_comments)
                        {
                            echo parse_links(parse_bbcode($hut_comments, null, false, false));
                        }
                 ?></li><?php endif;
                
                $outing_comments = $i18n['description'];
                if (check_not_empty($outing_comments) && !($outing_comments instanceof sfOutputEscaperObjectDecorator)): //FIXME sfOutputEscaperObjectDecorator ?>
                    <li><div class="section_subtitle" id="_description" data-tooltip=""><?php echo __('comments') ?></div><?php echo parse_links(parse_bbcode($outing_comments, null, false, false)) ?></li>
                <?php endif;
            }
            ?>
        </ul>
    <?php
    if ($show_images && isset($item['linked_docs']))
    {
        include_partial('images/linked_images', array('images' => $item['linked_docs'],
                                                      'module_name' => 'outings',
                                                      'document_id' => $item_id,
                                                      'user_can_dissociate' => false));
    }
    ?>
    </li>
    <?php
    endforeach ?>
</ul>
<?php
    echo $pager_navigation;
endif;

echo end_content_tag();

include_partial('common/content_bottom') ?>
