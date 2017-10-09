<?php
// $Id: views-view-grid-product.tpl.php,v 1.3 2008/06/14 17:42:43 merlinofchaos Exp $
/**
 * @file views-view-grid--product.tpl.php
 * Default simple view template to display a rows in a grid.
 *
 * - $rows contains a nested array of rows. Each row contains an array of
 *   columns.
 *
 * @ingroup views_templates
 */
?>
<?php if (!empty($title)) : ?>
  <h3><?php print $title; ?></h3>
<?php endif; ?>
<div class="category-grid-products clear-block">
    <?php 
    	foreach ($rows as $row_number => $columns) :
			$col_count = count($columns);
			foreach ($columns as $column_number => $item): 
				if((($column_number + 1) % $col_count) == 0) {
					$last_class = " last";
				} else {
					$last_class = "";
				}

	?>
				<div class="product-grid-item<?=$last_class;?>">
					<?php print $item; ?>
				</div>
	<?php
			endforeach;
    	endforeach;
    ?>
</div>
