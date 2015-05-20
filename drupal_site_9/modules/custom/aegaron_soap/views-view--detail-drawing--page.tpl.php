<?php

/**
 * @file
 * Main view template.
 *
 * Variables available:
 * - $classes_array: An array of classes determined in
 *   template_preprocess_views_view(). Default classes are:
 *     .view
 *     .view-[css_name]
 *     .view-id-[view_name]
 *     .view-display-id-[display_name]
 *     .view-dom-id-[dom_id]
 * - $classes: A string version of $classes_array for use in the class attribute
 * - $css_name: A css-safe version of the view name.
 * - $css_class: The user-specified classes names, if any
 * - $header: The view header
 * - $footer: The view footer
 * - $rows: The results of the view query, if any
 * - $empty: The empty text to display if the view is empty
 * - $pager: The pager next/prev links to display, if any
 * - $exposed: Exposed widget form/info to display
 * - $feed_icon: Feed icon to display, if any
 * - $more: A link to view more, if any
 *
 * @ingroup views_templates
 */

$service = wsclient_service_load('dev_aegaron_soap_service');
$params = array();

if (isset($view->args[0])) {
  $arkid = str_replace('_','/',$view->args[0]);
} else {
  $arkid = '21198/zz002c3kjg';
}

$params['ark'] = $arkid;
$result = $service->getItem($params);

if (isset($result->return)) {
  $drawing = $result->return;
  $metadata = new SimpleXMLElement($drawing->xmlMetadata);
  $itemdata = $metadata->metadata->dc;

  foreach($itemdata->altIdentifier as $tempid) {
    if (isset($tempid->drawing)) {
      $drawingid = (string)$tempid->drawing;
    }
  }

  foreach($itemdata->subject as $tempdata) {
    if (isset($tempdata->place)) {
      $place = (string)$tempdata->place;
    } else {
      $place = '';
    }
  }

  foreach($itemdata->altTitle as $tempdata) {
    if (isset($tempdata->planTitle)) {
      $title = '<h1>'.$place.', '.(string)$tempdata->planTitle.'</h1>';
    }
  }

} else {
    $drawing = array();
}

?>

<?php if (isset($drawingid)): ?>
  <script type="text/javascript">
  aegaron.mapid1 = '<?php print $drawingid; ?>';
  console.log(aegaron.mapid1);
  </script>
<?php else: ?>
  <?php drupal_set_message(t('No drawing with the arkid of '.$arkid),'warning'); ?>
<?php endif; ?>

<div class="<?php print $classes; ?>">
  <?php print render($title_prefix); ?>
  <?php if ($title): ?>
    <?php print $title; ?>
  <?php endif; ?>
  <?php print render($title_suffix); ?>
  <?php if ($header): ?>
    <div class="view-header">
      <?php print $header; ?>
    </div>
  <?php endif; ?>

  <?php if ($exposed): ?>
    <div class="view-filters">
      <?php print $exposed; ?>
    </div>
  <?php endif; ?>

  <?php if ($attachment_before): ?>
    <div class="attachment attachment-before">
      <?php print $attachment_before; ?>
    </div>
  <?php endif; ?>

<!-- content -->
<!--
  <?php if ($rows): ?>
    <div class="view-content">
      <?php print $rows; ?>
    </div>
  <?php elseif ($empty): ?>
    <div class="view-empty">
      <?php print $empty; ?>
    </div>
  <?php endif; ?>
-->
<!-- end -->

  <?php if ($pager): ?>
    <?php print $pager; ?>
  <?php endif; ?>

  <?php if ($attachment_after): ?>
    <div class="attachment attachment-after">
      <?php print $attachment_after; ?>
    </div>
  <?php endif; ?>

  <?php if ($more): ?>
    <?php print $more; ?>
  <?php endif; ?>

  <?php if ($footer): ?>
    <div class="view-footer">
      <?php print $footer; ?>
    </div>
  <?php endif; ?>

  <?php if ($feed_icon): ?>
    <div class="feed-icon">
      <?php print $feed_icon; ?>
    </div>
  <?php endif; ?>

</div><?php /* class view */ ?>
