<?php
// $Id: page.tpl.php,v 1.14.2.10 2009/11/05 14:26:26 johnalbin Exp $

/**
 * @file page.tpl.php
 *
 * Theme implementation to display a single Drupal page.
 *
 * Available variables:
 *
 * General utility variables:
 * - $base_path: The base URL path of the Drupal installation. At the very
 *   least, this will always default to /.
 * - $css: An array of CSS files for the current page.
 * - $directory: The directory the theme is located in, e.g. themes/garland or
 *   themes/garland/minelli.
 * - $is_front: TRUE if the current page is the front page. Used to toggle the mission statement.
 * - $logged_in: TRUE if the user is registered and signed in.
 * - $is_admin: TRUE if the user has permission to access administration pages.
 *
 * Page metadata:
 * - $language: (object) The language the site is being displayed in.
 *   $language->language contains its textual representation.
 *   $language->dir contains the language direction. It will either be 'ltr' or 'rtl'.
 * - $head_title: A modified version of the page title, for use in the TITLE tag.
 * - $head: Markup for the HEAD section (including meta tags, keyword tags, and
 *   so on).
 * - $styles: Style tags necessary to import all CSS files for the page.
 * - $scripts: Script tags necessary to load the JavaScript files and settings
 *   for the page.
 * - $body_classes: A set of CSS classes for the BODY tag. This contains flags
 *   indicating the current layout (multiple columns, single column), the current
 *   path, whether the user is logged in, and so on.
 * - $body_classes_array: An array of the body classes. This is easier to
 *   manipulate then the string in $body_classes.
 * - $node: Full node object. Contains data that may not be safe. This is only
 *   available if the current page is on the node's primary url.
 *
 * Site identity:
 * - $front_page: The URL of the front page. Use this instead of $base_path,
 *   when linking to the front page. This includes the language domain or prefix.
 * - $logo: The path to the logo image, as defined in theme configuration.
 * - $site_name: The name of the site, empty when display has been disabled
 *   in theme settings.
 * - $site_slogan: The slogan of the site, empty when display has been disabled
 *   in theme settings.
 * - $mission: The text of the site mission, empty when display has been disabled
 *   in theme settings.
 *
 * Navigation:
 * - $search_box: HTML to display the search box, empty if search has been disabled.
 * - $primary_links (array): An array containing primary navigation links for the
 *   site, if they have been configured.
 * - $secondary_links (array): An array containing secondary navigation links for
 *   the site, if they have been configured.
 *
 * Page content (in order of occurrance in the default page.tpl.php):
 * - $left: The HTML for the left sidebar.
 *
 * - $breadcrumb: The breadcrumb trail for the current page.
 * - $title: The page title, for use in the actual HTML content.
 * - $help: Dynamic help text, mostly for admin pages.
 * - $messages: HTML for status and error messages. Should be displayed prominently.
 * - $tabs: Tabs linking to any sub-pages beneath the current page (e.g., the view
 *   and edit tabs when displaying a node).
 *
 * - $content: The main content of the current Drupal page.
 *
 * - $right: The HTML for the right sidebar.
 *
 * Footer/closing data:
 * - $feed_icons: A string of all feed icons for the current page.
 * - $footer_message: The footer message as defined in the admin settings.
 * - $footer : The footer region.
 * - $closure: Final closing markup from any modules that have altered the page.
 *   This variable should always be output last, after all other dynamic content.
 *
 * @see template_preprocess()
 * @see template_preprocess_page()
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language->language; ?>" lang="<?php print $language->language; ?>" dir="<?php print $language->dir; ?>">

<head>
  <title><?php print $head_title; ?></title>
  <?php print $head; ?>
  <?php print $styles; ?>
  <?php print $scripts; ?>
</head>
<body class="<?php print $body_classes; ?>">

  <div id="page-shadow"><div id="page"><div id="page-inner">

    <div id="header"><div id="header-inner" class="clear-block">

   <div id="header-group" class="clear-block">
		<div id="logo"><a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home"><img src="<?=$base_path . $directory;?>/images/logo-large.png" alt="<?php print t('Home'); ?>" id="logo-image" width="315" height="58" /></a></div>

		<?php if ($primary_links): ?>
		  <div id="primary" class="clear-block">
			<?php print theme('links', $primary_links); ?>
			<div id="phone-number">
				<span>call toll free</span> 1.877.236.4434
			</div>
		  </div> <!-- /#primary -->
		<?php endif; ?>

		<?php if ($secondary_links): ?>
		  <div id="secondary" class="clear-block">
			<?php print theme('links', $secondary_links); ?>
		  </div> <!-- /#secondary -->
		<?php endif; ?>
		
		<div id="payment-options"><img src="<?=$base_path . $directory;?>/images/icon-payments.png" alt="Payment Options" width="120" height="23" /></div>
      </div> <!-- /#header-group -->

		<?php if ($header): ?>
			<div id="header-blocks" class="region region-header">
				<?php print $header; ?>
			</div><!-- /#header-blocks -->
		<?php endif; ?>
	  
		<div id="sign-up-search" class="clear-block">
			<div id="sign-up-box" class="clear-block">
				<?php
					$sign_up = module_invoke('webformblock', 'block', 'view', 1);
					echo $sign_up['content'];
				?>
			</div> <!-- /#sign-up-box -->
			<?php if ($search_box): ?>
				<div id="search-box" class="clear-block">
					<?=$search_box;?>
				</div> <!-- /#search-box -->
			<?php endif; ?>
		</div>
    </div></div> <!-- /#header-inner, /#header -->

    <div id="main"><div id="main-inner" class="clear-block<?php if ($search_box || $primary_links || $secondary_links || $navbar) { print ' with-navbar'; } ?>">

      <div id="content"><div id="content-inner">

        <?php if ($mission): ?>
          <div id="mission"><?php print $mission; ?></div>
        <?php endif; ?>

        <?php if ($content_top): ?>
          <div id="content-top" class="region region-content_top">
            <?php print $content_top; ?>
          </div> <!-- /#content-top -->
        <?php endif; ?>

        <?php if ($breadcrumb || $title || $tabs || $help || $messages): ?>
          <div id="content-header">
            <?php print $breadcrumb; ?>
            <?php if ($title): ?>
				<?php if (($node->type === 'product') || ($node->type === 'product_kit') || ($node->type === 'download_product')) { ?>
				  <h2 class="sku">SKU: <?=$node->model;?></h2>
				  <h1 class="product-title"><?php print $title; ?></h1>
				<?php } else if (arg(0) === 'catalog') { ?>
				  <h1 class="catalog-title"><?php print $title; ?></h1>
				<?php } else if (arg(0) === 'products') { ?>
				  <h1 class="special-title"><?php print $title; ?></h1>
				<?php } else { ?>
				  <h1 class="title"><?php print $title; ?></h1>
				<?php } ?>
			<?php endif; ?>
            <?php print $messages; ?>
            <?php if ($tabs): ?>
              <div class="tabs"><?php print $tabs; ?></div>
            <?php endif; ?>
            <?php print $help; ?>
          </div> <!-- /#content-header -->
        <?php endif; ?>

		<?php if (($node->type === 'product') || ($node->type === 'product_kit') || ($node->type === 'download_product') || (arg(0) === 'catalog') || (arg(0) === 'products')) { ?>
			<?php print $content; ?>
		<?php } else { ?>
			<div id="content-area">
				<?php print $content; ?>
			</div> <!-- /#content-area -->
		<?php } ?>

        <?php if ($feed_icons): ?>
          <div class="feed-icons"><?php print $feed_icons; ?></div>
        <?php endif; ?>

        <?php if ($content_bottom): ?>
          <div id="content-bottom" class="region region-content_bottom">
            <?php print $content_bottom; ?>
          </div> <!-- /#content-bottom -->
        <?php endif; ?>

      </div></div> <!-- /#content-inner, /#content -->

      <?php if ($left): ?>
        <div id="sidebar-left"><div id="sidebar-left-inner" class="region region-left">
          <?php print $left; ?>
        </div></div> <!-- /#sidebar-left-inner, /#sidebar-left -->
      <?php endif; ?>

      <?php if ($right): ?>
        <div id="sidebar-right"><div id="sidebar-right-inner" class="region region-right">
          <?php print $right; ?>
        </div></div> <!-- /#sidebar-right-inner, /#sidebar-right -->
      <?php endif; ?>

    </div></div> <!-- /#main-inner, /#main -->

    <?php //if ($footer || $footer_message): ?>
      <div id="footer"><div id="footer-inner" class="region region-footer">

        <div id="footer-group" class="clear-block">
        	<div class="left">
				<?php
					$footer_menu = module_invoke('menu', 'block', 'view', 'menu-footer-links');
					echo $footer_menu['content'];
				?>
				
				<p>Copyright <?=date('Y');?> USA Hi-Fi. All rights reserved. <?=l('Privacy Policy', 'privacy-policy');?> | <?=l('Terms and Conditions', 'terms-conditions');?></p>
				
				<p>Site design and development by <a href="http://lubashadesign.com/">Lubasha Design</a></p>
			</div>
        	<div class="right">
        		<a href="#"><img src="<?=$base_path . $directory;?>/images/logo-small.png" alt="USA HiFi" width="243" height="43" /></a>
        	</div>
        </div>

        <?php if ($footer_message): ?>
          <div id="footer-message"><?php print $footer_message; ?></div>
        <?php endif; ?>

        <?php print $footer; ?>

      </div></div> <!-- /#footer-inner, /#footer -->
    <?php //endif; ?>

  </div></div></div> <!-- /#page-inner, /#page, /#page-shadow -->

  <?php if ($closure_region): ?>
    <div id="closure-blocks" class="region region-closure"><?php print $closure_region; ?></div>
  <?php endif; ?>

  <?php print $closure; ?>
</body>
</html>
