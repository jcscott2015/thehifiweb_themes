<?php
// $Id: views-view-fields--hot-deals.tpl.php,v 1.6 2008/09/24 22:48:21 merlinofchaos Exp $
/**
 * @file views-view-fields.tpl.php
 * Default simple view template to all the fields as a row.
 *
 * - $view: The view in use.
 * - $fields: an array of $field objects. Each one contains:
 *   - $field->content: The output of the field.
 *   - $field->raw: The raw data for the field, if it exists. This is NOT output safe.
 *   - $field->class: The safe class id to use.
 *   - $field->handler: The Views field handler object controlling this field. Do not use
 *     var_export to dump this object, as it can't handle the recursion.
 *   - $field->inline: Whether or not the field should be inline.
 *   - $field->inline_html: either div or span based on the above flag.
 *   - $field->separator: an optional separator that may appear before a field.
 * - $row: The raw result object from the query, with all data it fetched.
 *
 * @ingroup views_templates
 */

?>
<?php
	$stripped_rows = array();
	$my_rows = array();
	foreach ($fields as $id => $field) {
		$stripped_rows[$id] = strip_tags($field->content);
		$my_rows[$id] = $field;
	}
	
	if(($my_rows['tid']->content) && ($discountNum = intval($my_rows['tid']->content))) {
		$discount = '_' . $discountNum;
	} else {
		$discount = null;
	}
	
	if (module_exists('imagecache')) {
		$imagelink = l(theme('imagecache', 'product_grid' . $discount, $my_rows['field_image_cache_fid']->content, $my_rows['title']->raw, $my_rows['title']->raw), "node/" . $my_rows['nid']->content, array('html' => TRUE));

		$my_rows['field_image_cache_fid']->content = $imagelink;
	}
	
	// We don't need these anymore...
	unset($my_rows['nid']);
	unset($my_rows['tid']);
	
	// if the list price equals the sell price, just show the sell price.
	if ($stripped_rows['list_price'] == $stripped_rows['sell_price']) {
		// Delete list price.
		unset($my_rows['list_price']);
		// Change price-ours class to price-reg.
		$my_rows['sell_price']->content = str_replace('price-ours', 'price-reg', $my_rows['sell_price']->content);
	}

?>

<?php foreach ($my_rows as $id => $field): ?>
  <?php if (!empty($field->separator)): ?>
    <?php print $field->separator; ?>
  <?php endif; ?>

  <<?php print $field->inline_html;?> class="views-field-<?php print $field->class; ?>">
    <?php if ($field->label): ?>
      <label class="views-label-<?php print $field->class; ?>">
        <?php print $field->label; ?>:
      </label>
    <?php endif; ?>
      <?php
      // $field->element_type is either SPAN or DIV depending upon whether or not
      // the field is a 'block' element type or 'inline' element type.
      ?>
      <<?php print $field->element_type; ?> class="field-content"><?php print $field->content; ?></<?php print $field->element_type; ?>>
  </<?php print $field->inline_html;?>>
<?php endforeach; ?>
