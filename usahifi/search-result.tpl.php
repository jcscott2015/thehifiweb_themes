<?php
// $Id: search-result.tpl.php,v 1.1.2.1 2008/08/28 08:21:44 dries Exp $

/**
 * @file search-result.tpl.php
 * Default theme implementation for displaying a single search result.
 *
 * This template renders a single search result and is collected into
 * search-results.tpl.php. This and the parent template are
 * dependent to one another sharing the markup for definition lists.
 *
 * Available variables:
 * - $url: URL of the result.
 * - $title: Title of the result.
 * - $snippet: A small preview of the result. Does not apply to user searches.
 * - $info: String of all the meta information ready for print. Does not apply
 *   to user searches.
 * - $info_split: Contains same data as $info, split into a keyed array.
 * - $type: The type of search, e.g., "node" or "user".
 *
 * Default keys within $info_split:
 * - $info_split['type']: Node type.
 * - $info_split['user']: Author of the node linked to users profile. Depends
 *   on permission.
 * - $info_split['date']: Last update of the node. Short formatted.
 * - $info_split['comment']: Number of comments output as "% comments", %
 *   being the count. Depends on comment.module.
 * - $info_split['upload']: Number of attachments output as "% attachments", %
 *   being the count. Depends on upload.module.
 *
 * Since $info_split is keyed, a direct print of the item is possible.
 * This array does not apply to user searches so it is recommended to check
 * for their existance before printing. The default keys of 'type', 'user' and
 * 'date' always exist for node searches. Modules may provide other data.
 *
 *   <?php if (isset($info_split['comment'])) : ?>
 *     <span class="info-comment">
 *       <?php print $info_split['comment']; ?>
 *     </span>
 *   <?php endif; ?>
 *
 * To check for all available data within $info_split, use the code below.
 *
 *   <?php print '<pre>'. check_plain(print_r($info_split, 1)) .'</pre>'; ?>
 *
 * @see template_preprocess_search_result()
 */
?>
<?php
	$context = array(
		'revision' => 'themed',
		'type' => 'product'
	);
	$product = node_load($nid);
	$context['subject'] = array('node' => $product);
?>
<div class="search-row clear-block">
<?php if ($imagelink) : ?>
	<div class="search-image"><?php print $imagelink; ?></div>
<?php endif; ?>

	<div class="search-text">
		<h2 class="search-title">
		  <a href="<?=$url;?>"><?=$title;?></a>
		</h2>
		<?php if ($teaser) : ?>
			<p class="search-teaser"><?php print $teaser; ?></p>
			<?php
			if (((isset($list_price)) && ($list_price > 0)) && $sell_price > 0) {
				echo '<p>';
				
				if ((isset($list_price)) && ($list_price > 0)) {
					if ($list_price != $sell_price) {
						echo '<span class="price-msrp">'. uc_price($list_price, $context) .'</span> ';
						echo '<span class="price-ours">'. uc_price($sell_price, $context) .'</span>';
					} else {
						echo '<span class="price-reg">'. uc_price($sell_price, $context) .'</span>';
					}
				} else {
					echo '<span class="price-reg">'. uc_price($sell_price, $context) .'</span>';
				}
				
				echo '</p>';
			} else {
				if ($sell_price == 0) {
					echo '<p class="price-free">FREE</p>';
					echo $addtocart;
				}
				//echo '<p><strong>';
				//echo 'Call us to place an order: 1-877-236-4434';
				//echo '</strong></p>';
			}
			?>
		<?php else: ?>
			<?php if ($snippet) : ?>
			<p class="search-snippet"><?php print $snippet; ?></p>
			<?php endif; ?>
		<?php endif; ?>
	</div>
</div>
