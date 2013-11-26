<nav class="snap-drawers">
  <div class="snap-drawer snap-drawer-left">
    <ul>
      <li><?php echo link_to(picto_tag('picto_huts').__('Home'), '@homepage') ?></li>
      <li><?php echo f_link_to(picto_tag('action_comment').__('Forums'), '?lang='.__('meta_language')); ?></li>
      <li>
        <a href="#"><?php echo picto_tag('action_query').__('Search') ?></a>
        <ul>
          <li><a href="#" class="snap-back"><?php echo __('Back') ?></a></li>
          <li><?php echo link_to(picto_tag('picto_outings').__('outings'), '@filter?module=outings') ?></li>
          <li><?php echo link_to(picto_tag('picto_routes').__('routes'), '@filter?module=routes') ?></li>
          <li><?php echo link_to(picto_tag('picto_images').__('images'), '@filter?module=images') ?></li>
          <li><?php echo link_to(picto_tag('picto_summits').__('summits'), '@filter?module=summits') ?></li>
          <li><?php echo link_to(picto_tag('picto_sites').__('sites'), '@filter?module=sites') ?></li>
          <li><?php echo link_to(picto_tag('picto_parkings').__('parkings'), '@filter?module=parkings') ?></li>
          <li><?php echo link_to(picto_tag('picto_huts').__('huts'), '@filter?module=huts') ?></li>
          <li><?php echo link_to(picto_tag('picto_books').__('books'), '@filter?module=books') ?></li>
          <li><?php echo link_to(picto_tag('picto_articles').__('articles'), '@filter?module=articles') ?></li>
          <li><?php echo link_to(picto_tag('picto_products').__('products'), '@filter?module=products') ?></li>
          <li><?php echo link_to(picto_tag('picto_users').__('users'), '@filter?module=users') ?></li>
        </ul>
      </li>
      <li>
        <a href="#"><?php echo picto_tag('action_list').__('See') ?></a>
        <ul>
          <li><a href="#" class="snap-back"><?php echo __('Back') ?></a></li>
          <li><?php echo link_to(__('outings'), '@default_index?module=outings&orderby=date&order=desc') ?></li>
          <li><?php echo link_to(__('cond short'), 'outings/conditions') ?></li>
          <li><?php echo link_to(__('routes'), '@default_index?module=routes') ?></li>
          <li><?php echo link_to(__('images'), '@default_index?module=images') ?></li>
          <li><?php echo link_to(__('summits'), '@default_index?module=summits') ?></li>
          <li><?php echo link_to(__('sites'), '@default_index?module=sites') ?></li>
          <li><?php echo link_to(__('parkings'), '@default_index?module=parkings') ?></li>
          <li><?php echo link_to(__('huts'), '@default_index?module=huts') ?></li>
          <li><?php echo link_to(__('books'), '@default_index?module=books') ?></li>
          <li><?php echo link_to(__('articles'), '@default_index?module=articles') ?></li>
          <li><?php echo link_to(__('products'), '@default_index?module=products') ?></li>
          <li><?php echo link_to(__('users'), '@default_index?module=users') ?></li>
        </ul>
      </li>
    </ul>
  </div>
</nav>
