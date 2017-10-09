<?php
// $Id: block_uc_catalog_0.tpl.php,v 1.3 2007/08/07 08:39:36 goba Exp $
?>
<div id="block-<?php print $block->module .'-'. $block->delta; ?>" class="clear-block block block-<?php print $block->module ?>">

<?php if (!empty($block->subject)): ?>
  <h2 class="uc_catalog-header"><?php print $block->subject ?></h2>
<?php endif;?>

  <div class="content"><?php print $block->content ?></div>
</div>
