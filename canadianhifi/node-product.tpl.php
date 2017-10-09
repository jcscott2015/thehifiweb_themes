<?php
// $Id: node.tpl.php,v 1.4.2.1 2009/05/12 18:41:54 johnalbin Exp $

/**
 * @file node.tpl.php
 *
 * Theme implementation to display product node.
 */
?>
<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?>"><div class="node-inner">

  <?php if ($unpublished): ?>
    <div class="unpublished"><?php print t('Unpublished'); ?></div>
  <?php endif; ?>

  <div class="content clear-block">
    <div class="product-purchase-info">
    	<?php //image ?>
    	<?php print $node->content['image']['#value']; ?>

		<?php if (((isset($node->list_price)) && ($node->list_price > 0)) && ($node->sell_price > 0)) : ?>
		<?php
			$context = array(
				'revision' => 'themed',
				'type' => 'product'
			);
			$product = node_load($node->nid);
			$context['subject'] = array('node' => $product);
		?>
		<div class="<?=($node->attributes) ? 'product-buy' : 'product-buy-no-attr';?> clear-block">
			<div class="product-prices">
				<ul>
				<?php if ((isset($node->list_price)) && ($node->list_price > 0)) : ?>
					<?php 
						$listPrice = uc_currency_format($node->list_price);
						$sellPrice = strip_tags(uc_price($node->sell_price, $context));
					?>
					<?php if ($listPrice !== $sellPrice) : ?>
						<li class="clear-block"><span class="price-label">MSRP:</span><span class="price-msrp list"><?=uc_currency_format($node->list_price);?></span></li>
						<li class="clear-block"><span class="price-label">Our price:</span><span class="price-ours sell"><?=uc_price($node->sell_price, $context);?></span></li>
					<?php else: ?>
						<li class="clear-block no-msrp"><span class="price-label">Our price:</span><span class="price-reg sell"><?=uc_price($node->sell_price, $context);?></span></li>
					<?php endif; ?>
				<?php else: ?>
					<li class="clear-block no-msrp"><span class="price-label">Our price:</span><span class="price-reg sell"><?=uc_price($node->sell_price, $context);?></span></li>
				<?php endif; ?>
				</ul>
			</div><!-- /.product-prices -->
	
			<?php //add to cart ?>
			<?php print $node->content['add_to_cart']['#value']; ?>

		</div><!-- /.product-buy -->

		<?php else: ?>
			<div class="call-us">
				Call us to place an order: 1-877-236-4434
			</div>
		<?php endif; ?>

		<?php if (($node->field_condition[0]['safe']) || ($node->field_ship_time[0]['safe'])) : ?>
			<div class="grey-box clear-block">
			<?php if ($node->field_condition[0]['safe']) : ?>
				<div class="grey-box-left">
					<p><strong>Condition:</strong></p>
					<p><?=$node->field_condition[0]['safe'];?></p>
				</div>
			<?php endif; ?>
			
			<?php if ($node->field_ship_time[0]['safe']) : ?>
				<div class="grey-box-right">
					<p><strong>Apprx Ship Time:</strong></p>
					<p><?=$node->field_ship_time[0]['safe'];?></p>
				</div>
			<?php endif; ?>
			</div><!-- /.grey-box -->
		<?php endif; ?>

		<p class="contact-us">
			For Questions Please Call: 1-877-236-4434<br />
			Email Us: <a href="mailto:sales@canadianhifi.com">sales@canadianhifi.com</a>
		</p>
		<?php //if ($node->weight) : ?>
			<!-- <p>Weight: <?//=$node->weight . $node->weight_units;?></p> -->
		<?php //endif; ?>

		<?php //if ($node->length || $node->width || $node->height) : ?>
			<!-- <p>Dimensions: <?//=$node->length . ' x ' . $node->width . ' x ' . $node->height . $node->length_units;?></p> -->
		<?php //endif; ?>	
    </div><!-- /.product-info -->

    <?php //quicktabs ?>
	<?php $quicktabs = module_invoke('quicktabs', 'block', 'view', 1); ?>
	<?php print $quicktabs['content']; ?>
	<?php //print $content; ?>
  </div>

  <?php print $links; ?>

</div></div> <!-- /node-inner, /node -->
