<?php
// $Id: template.php,v 1.17.2.1 2009/02/13 06:47:44 johnalbin Exp $

/**
 * @file
 * Contains theme override functions and preprocess functions for the theme.
 *
 * ABOUT THE TEMPLATE.PHP FILE
 *
 *   The template.php file is one of the most useful files when creating or
 *   modifying Drupal themes. You can add new regions for block content, modify
 *   or override Drupal's theme functions, intercept or make additional
 *   variables available to your theme, and create custom PHP logic. For more
 *   information, please visit the Theme Developer's Guide on Drupal.org:
 *   http://drupal.org/theme-guide
 *
 * OVERRIDING THEME FUNCTIONS
 *
 *   The Drupal theme system uses special theme functions to generate HTML
 *   output automatically. Often we wish to customize this HTML output. To do
 *   this, we have to override the theme function. You have to first find the
 *   theme function that generates the output, and then "catch" it and modify it
 *   here. The easiest way to do it is to copy the original function in its
 *   entirety and paste it here, changing the prefix from theme_ to thehifiweb_.
 *   For example:
 *
 *     original: theme_breadcrumb()
 *     theme override: thehifiweb_breadcrumb()
 *
 *   where thehifiweb is the name of your sub-theme. For example, the
 *   zen_classic theme would define a zen_classic_breadcrumb() function.
 *
 *   If you would like to override any of the theme functions used in Zen core,
 *   you should first look at how Zen core implements those functions:
 *     theme_breadcrumbs()      in zen/template.php
 *     theme_menu_item_link()   in zen/template.php
 *     theme_menu_local_tasks() in zen/template.php
 *
 *   For more information, please visit the Theme Developer's Guide on
 *   Drupal.org: http://drupal.org/node/173880
 *
 * CREATE OR MODIFY VARIABLES FOR YOUR THEME
 *
 *   Each tpl.php template file has several variables which hold various pieces
 *   of content. You can modify those variables (or add new ones) before they
 *   are used in the template files by using preprocess functions.
 *
 *   This makes THEME_preprocess_HOOK() functions the most powerful functions
 *   available to themers.
 *
 *   It works by having one preprocess function for each template file or its
 *   derivatives (called template suggestions). For example:
 *     THEME_preprocess_page    alters the variables for page.tpl.php
 *     THEME_preprocess_node    alters the variables for node.tpl.php or
 *                              for node-forum.tpl.php
 *     THEME_preprocess_comment alters the variables for comment.tpl.php
 *     THEME_preprocess_block   alters the variables for block.tpl.php
 *
 *   For more information on preprocess functions and template suggestions,
 *   please visit the Theme Developer's Guide on Drupal.org:
 *   http://drupal.org/node/223440
 *   and http://drupal.org/node/190815#template-suggestions
 */


/*
 * Add any conditional stylesheets you will need for this sub-theme.
 *
 * To add stylesheets that ALWAYS need to be included, you should add them to
 * your .info file instead. Only use this section if you are including
 * stylesheets based on certain conditions.
 */
/* -- Delete this line if you want to use and modify this code
// Example: optionally add a fixed width CSS file.
if (theme_get_setting('thehifiweb_fixed')) {
  drupal_add_css(path_to_theme() . '/layout-fixed.css', 'theme', 'all');
}
// */


/**
 * Implementation of HOOK_theme().
 */
function thehifiweb_theme(&$existing, $type, $theme, $path) {
  $hooks = zen_theme($existing, $type, $theme, $path);
  // Add your theme hooks like this:
  /*
  $hooks['hook_name_here'] = array( // Details go here );
  */
  // @TODO: Needs detailed comments. Patches welcome!
  return $hooks;
}

/**
 * Override or insert variables into all templates.
 *
 * @param $vars
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered (name of the .tpl.php file.)
 */
/**
 * Modify theme variables
 */
function thehifiweb_preprocess(&$vars, $hook) {
	switch($hook) {
	case 'webform_form':
		// Add custom class to "Sign up for news and promotions" webform submit button.
		// Used by thehifiweb_button() below.
		if($vars['form']['form_id']['#value'] == 'webform_client_form_1') {
			if(isset($vars['form']['submit']['#attributes']['class'])) {
				$curr_class = $vars['form']['submit']['#attributes']['class'];
				$vars['form']['submit']['#attributes']['class'] = 'orange-purple ' . $curr_class;
			} else {
				$vars['form']['submit']['#attributes']['class'] = 'orange-purple';
			}
			$vars['form']['submit']['#attributes']['override'] = true;
		}
		break;
  }
}

/**
 * Perform alterations before a finder form is rendered. 
 *
 * This is a good place to add custom FAPI values for #validate, #submit, and
 * #theme.
 *
 * @see hook_form_FORM_ID_alter()
 */
function finder_form_alter(&$form, &$form_state) {
    $form['finder_form']['submit']['#attributes']['class'] = 'orange-purple';
	$form['finder_form']['submit']['#attributes']['override'] = true;
}

/**
 * Override or insert variables into the page templates.
 *
 * @param $vars
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("page" in this case.)
 */
function thehifiweb_preprocess_page(&$vars, $hook) {
  /**
   * Solve 30 CSS files limit in Internet Explorer
   */
  $preprocess_css = variable_get('preprocess_css', 0);
  if (!$preprocess_css) {
    $styles = '';
    foreach ($vars['css'] as $media => $types) {
      $import = '';
      $counter = 0;
      foreach ($types as $files) {
        foreach ($files as $css => $preprocess) {
          $import .= '@import "'. base_path() . $css .'";'."\n";
          $counter++;
          if ($counter == 15) {
            $styles .= "\n".'<style type="text/css" media="'. $media .'">'."\n". $import .'</style>';
            $import = '';
            $counter = 0;
          }
        }
      }
      if ($import) {
        $styles .= "\n".'<style type="text/css" media="'. $media .'">'."\n". $import .'</style>';
      }
    }
    if ($styles) {
      $vars['styles'] = $styles;
    }
  }

  // If in node edit mode, I must be an admin and I need to see tabs and a specially formatted title.
  $vars['admin_mode'] = ((arg(0) == 'node') && (arg(2))) ? TRUE : FALSE;

  // Enable page templates based on vocabulary.
  if (arg(0) == 'taxonomy' && arg(1) == 'term' && is_numeric(arg(2))) {
    $tid = arg(2);
    $vars['template_files'][] = 'page-term-' . $tid;
    // Get vocabulary id of this term.
    $sql = "Select vid from {term_data} WHERE tid = '%d'";
    $vid = db_result(db_query($sql, $tid));
    $vars['template_files'][] = 'page-vocabulary-' . $vid;
  }

  // use custom page tpls by nodetype
  if ($node = menu_get_object()) {
    $vars['node'] = $node;
	$suggestions = array();
	$template_filename = 'page';
	$template_filename = $template_filename . '-' . $vars['node']->type;
    $suggestions[] = $template_filename;
    $vars['template_files'] = $suggestions;
  }

  // Add page template suggestions based on the aliased path.
  // For instance, if the current page has an alias of about/history/early,
  // we'll have templates of:
  // page-about-history-early.tpl.php
  // page-about-history.tpl.php
  // page-about.tpl.php
  // Whichever is found first is the one that will be used.
  if (module_exists('path')) {
    //$alias = drupal_get_path_alias(str_replace('/edit','',$_GET['q']));
    $alias = drupal_get_path_alias($_GET['q']);
    if ($alias != $_GET['q']) {
      $suggestions = array();
      $template_filename = 'page';
      foreach (explode('/', $alias) as $path_part) {
        $template_filename = $template_filename . '-' . $path_part;
        $suggestions[] = $template_filename;
      }
      $vars['template_files'] = array_merge((array) $suggestions, $vars['template_files']);
    }
  }
}

/**
 * Override or insert variables into the node templates.
 *
 * @param $vars
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("node" in this case.)
 */
/**
* Override or insert variables into the node templates.
*
* @param $vars
*   An array of variables to pass to the theme template.
*/
function thehifiweb_preprocess_node(&$vars, $hook) {
  $node = $vars['node'];

  // Enable node templates based on node id.
  $vars['template_files'][] = 'node-' . $node->nid;
  // Enable node templates based on node type and node id.
  $vars['template_files'][] = 'node-' . $node->type . '-' . $node->nid;

  // Enable node templates based on taxonomy term.
  if (arg(0) == 'taxonomy') {
    $taxonomy = $node->taxonomy;
    $terms = array();
    foreach ( $taxonomy as $term ) { $terms[] = $term->tid; }
    $term_str = implode('-', $terms);
	$vars['template_files'][] = 'node-taxonomy-' . $term_str;
  }
}
// */

/**
 * Override or insert variables into the comment templates.
 *
 * @param $vars
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("comment" in this case.)
 */
/* -- Delete this line if you want to use this function
function thehifiweb_preprocess_comment(&$vars, $hook) {
  $vars['sample_variable'] = t('Lorem ipsum.');
}
// */

/**
 * Override or insert variables into the block templates.
 *
 * @param $vars
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("block" in this case.)
 */
/* -- Delete this line if you want to use this function
function thehifiweb_preprocess_block(&$vars, $hook) {
  $vars['sample_variable'] = t('Lorem ipsum.');
}
// */

/**
 * Insert a node into pages.
 * @param int $nid
 *   node id.
 */
function get_node($nid) {
	$node = node_load($nid);
	$node->title = NULL;
	return node_view($node);
}

/**
 * Format a product's images with imagecache and Thickbox.
 *
 * @ingroup themeable
 */
function thehifiweb_uc_product_image($images, $teaser = 0, $page = 0) {
  // get nid
  $node = db_fetch_object( db_query("SELECT cfic.vid, cfic.nid FROM {content_field_image_cache} cfic LEFT JOIN {node} n ON n.nid = cfic.nid WHERE cfic.field_image_cache_fid = %d AND n.vid = cfic.vid", $images[0]['fid']) );

  // get discount amount, if any
  $discountAmt = db_fetch_object( db_query("SELECT td.name FROM {term_data} td LEFT JOIN {term_node} tn ON tn.tid = td.tid WHERE tn.nid = %d AND td.vid = 5 AND tn.vid = %d", $node->nid, $node->vid) );

  $discount = '';
  if($discountAmt) {
  	if ($discountNum = intval($discountAmt->name)) {
  		$discount .= '_' . $discountNum;
  	}
  }
  
  static $rel_count = 0;

  // Get the current product image widget.
  $image_widget = uc_product_get_image_widget();
  $image_widget_func = $image_widget['callback'];

  $first = array_shift($images);

  $output = '<div class="product-image clear-block"><div class="main-product-image">';
  $output .= '<a href="'. imagecache_create_url('product_full', $first['filepath']) .'" title="'. $first['data']['title'] .'"';
  if ($image_widget) {
    $output .= $image_widget_func($rel_count);
  }
  $output .= '>';
  $output .= theme('imagecache', 'product' . $discount, $first['filepath'], $first['alt'], $first['title']);
  $output .= '</a></div><div class="more-product-images clear-block">';
  foreach ($images as $thumbnail) {
    // Node preview adds extra values to $images that aren't files.
    if (!is_array($thumbnail) || empty($thumbnail['filepath'])) {
      continue;
    }
    $output .= '<div class="another-image"><a href="'. imagecache_create_url('product_full', $thumbnail['filepath']) .'" title="'. $thumbnail['data']['title'] .'"';
    if ($image_widget) {
      $output .= $image_widget_func($rel_count);
    }
    $output .= '>';
    $output .= theme('imagecache', 'uc_thumbnail', $thumbnail['filepath'], $thumbnail['alt'], $thumbnail['title']);
    $output .= '</a></div>';
  }
  $output .= '</div></div>';
  $rel_count++;

  return $output;
}

/**
 * Theme a form button.
 *
 * @ingroup themeable
 */
function thehifiweb_button($element) {
  $form_class = 'form-' . $element['#button_type'];
  $default_button_class = 'smgray';
  
  // Make sure not to overwrite classes.
  if (isset($element['#attributes']['class'])) {
	if ((isset($element['#attributes']['override'])) && ($element['#attributes']['override'] > 0)) {
		$element['#attributes']['class'] = $form_class . ' ' . $element['#attributes']['class'];
		unset($element['#attributes']['override']);
	} else if (strpos($element['#attributes']['class'], 'node-add-to-cart') !== false) {
		$element['#attributes']['class'] = 'orange ' . $form_class . ' ' . $element['#attributes']['class'];
	} else if (strpos($element['#attributes']['class'], 'list-add-to-cart') !== false) {
		$element['#attributes']['class'] = 'orange ' . $form_class . ' ' . $element['#attributes']['class'];
	} else {
		$element['#attributes']['class'] = $default_button_class . ' ' . $form_class . ' ' . $element['#attributes']['class'];
	}
  } else {
    $element['#attributes']['class'] = $default_button_class . ' ' . $form_class;
  }

  return '<button' . 
  	drupal_attributes($element['#attributes']) . 
  	(empty($element['#button_type']) ? '' : ' type="'. $element['#button_type'] .'" ') .
  	(empty($element['#id']) ? '' : 'id="'. $element['#id'] . '" ') .
  	(empty($element['#name']) ? '' : 'name="'. $element['#name'] . '" ') .
  	(empty($element['#value']) ? '' : 'value="'. check_plain($element['#value']) . '"') .
  	'><span><em>'. 
  	(empty($element['#value']) ? 'Submit' : check_plain($element['#value'])) .
  	'</em></span></button>' .
  	"\n";
}

/**
* Quick fix for the validation error: 'ID "edit-submit" already defined' or edit-name
* There is a solution in d6 core: <a href="http://drupal.org/node/111719
" title="http://drupal.org/node/111719
" rel="nofollow">http://drupal.org/node/111719
</a> */
function thehifiweb_submit($element) {
	// webform with ajax submit needs the "edit-submit", so we'll not change it.
	if(strpos($element['#attributes']['class'], "ajax-trigger") === false){
		static $count_double_id = 0;
		$tmp = str_replace('edit-submit', 'edit-submit-'. $count_double_id++, theme('button', $element));
		return str_replace('edit-name', 'edit-name-'. $count_double_id++, $tmp);
	} else {
		return theme('button', $element);
	}
}

/**
* Override or insert PHPTemplate variables into the search_theme_form template.
*
* @param $vars
*   A sequential array of variables to pass to the theme template.
* @param $hook
*   The name of the theme function being called (not used in this case.)
*/
function thehifiweb_preprocess_search_theme_form(&$vars, $hook) {
 
  // Modify elements of the search form
  $vars['form']['search_theme_form']['#title'] = t('Site Search');
 
  // Set a default value for the search box
  //$vars['form']['search_theme_form']['#value'] = t('Search');
 
  // Add a custom class to the search box
  //$vars['form']['search_theme_form']['#attributes'] = array('class' => t('cleardefault'));
 
  // Change the text on the submit button
  //$vars['form']['submit']['#type'] = 'button';
  $vars['form']['submit']['#attributes']['class'] = 'orange-purple';
  $vars['form']['submit']['#attributes']['override'] = true;
  $vars['form']['submit']['#value'] = t('GO');
 
  // Rebuild the rendered version (search form only, rest remains unchanged)
  unset($vars['form']['search_theme_form']['#printed']);
  $vars['search']['search_theme_form'] = drupal_render($vars['form']['search_theme_form']);
 
  // Rebuild the rendered version (submit button, rest remains unchanged)
  unset($vars['form']['submit']['#printed']);
  $vars['search']['submit'] = drupal_render($vars['form']['submit']);
 
  // Collect all form elements to make it easier to print the whole form.
  $vars['search_form'] = implode($vars['search']);
}

/**
 * Process variables for search-result.tpl.php.
 *
 * The $variables array contains the following arguments:
 * - $result
 * - $type
 *
 * @see search-result.tpl.php
 */
function thehifiweb_preprocess_search_result(&$vars, $hook) {
  $result = $vars['result'];
  $nid = $result['node']->nid;
  $vars['nid'] = $nid;
  $vars['url'] = check_url($result['link']);
  $vars['title'] = check_plain($result['title']);

  $product = node_load($nid);
  $vars['addtocart'] = theme('uc_product_add_to_cart', $product);
  if (module_exists('imagecache')) {
	$vars['imagelink'] = l(theme('imagecache', 'product_list', $product->field_image_cache[0]['filepath'], $vars['title'], $vars['title']), $vars['url'], array('html' => TRUE));
  }

  $vars['teaser'] = substr(check_plain(strip_tags($product->teaser)), 0, 200) . " &hellip;";
  $vars['list_price'] = check_plain(strip_tags($product->list_price));
  $vars['sell_price'] = check_plain(strip_tags($product->sell_price));
  
  $info = array();
  if (!empty($result['type'])) {
    $info['type'] = check_plain($result['type']);
  }
  if (!empty($result['user'])) {
    $info['user'] = $result['user'];
  }
  if (!empty($result['date'])) {
    $info['date'] = format_date($result['date'], 'small');
  }
  if (isset($result['extra']) && is_array($result['extra'])) {
    $info = array_merge($info, $result['extra']);
  }
  // Check for existence. User search does not include snippets.
  $vars['snippet'] = isset($result['snippet']) ? $result['snippet'] : '';
  // Provide separated and grouped meta information..
  $vars['info_split'] = $info;
  $vars['info'] = implode(' - ', $info);
  // Provide alternate search result template.
  $vars['template_files'][] = 'search-result-'. $vars['type'];
}

/**
 * Theme the catalog product grid.
 *
 * @ingroup themeable
 * @see theme_uc_catalog_item()
 */
function thehifiweb_uc_catalog_product_grid($products) {
  $product_table = '<div class="category-grid-products clear-block">';
  $count = 1;
  $context = array(
    'revision' => 'themed',
    'type' => 'product',
  );
  foreach ($products as $nid) {
    $product = node_load($nid);
    $context['subject'] = array('node' => $product);
    
    if (($count > 1) && (($count % variable_get('uc_catalog_grid_display_width', 4)) == 0)) {
      $last_class = " last";
    } else {
      $last_class = "";
    }

	$discount = '';
	if ($taxonomy = $product->taxonomy) {
	  foreach ( $taxonomy as $term ) {
	      if($term->vid == 5) {
			  if ($discountNum = intval($term->name)) {
				  $discount .= '_' . $discountNum;
			  }
	      }
	  }
	}

    if (module_exists('imagecache') && ($field = variable_get('uc_image_'. $product->type, '')) && isset($product->$field) && file_exists($product->{$field}[0]['filepath'])) {
      $imagelink = l(theme('imagecache', 'product_grid' . $discount, $product->{$field}[0]['filepath'], $product->title, $product->title), "node/$nid", array('html' => TRUE));
    }
    else {
      $imagelink = '';
    }

    $titlelink = l($product->title, "node/$nid", array('html' => TRUE));

    $product_table .= '<div class="product-grid-item' . $last_class . '">';

    // Display Image
    $product_table .= '<div class="product-grid-image">'. $imagelink .'</div>';

    // Display Title Link
    if (variable_get('uc_catalog_grid_display_title', TRUE)) {
      $product_table .= '<h3 class="product-grid-title">'. $titlelink .'</h3>';
    }
    
    // Display Price and Buy Button or Phone Number
    if (variable_get('uc_catalog_grid_display_sell_price', TRUE)) {
		if (((isset($product->list_price)) && ($product->list_price > 0)) || ($product->sell_price > 0)) {
			$product_table .= '<p>';
			
			if ((isset($product->list_price)) && ($product->list_price > 0)) {
				$listPrice = uc_currency_format($product->list_price);
				$sellPrice = strip_tags(uc_price($product->sell_price, $context));
				if ($listPrice !== $sellPrice) {
					$product_table .= '<span class="price-msrp">'. uc_currency_format($product->list_price) .'</span> ';
				
					$product_table .= '<span class="price-ours">'. uc_price($product->sell_price, $context) .'</span>';
				} else {
					$product_table .= '<span class="price-reg">'. uc_price($product->sell_price, $context) .'</span>';
				}
			} else {
				$product_table .= '<span class="price-reg">'. uc_price($product->sell_price, $context) .'</span>';
			}
			
			$product_table .= '</p>';

			if (module_exists('uc_cart') && variable_get('uc_catalog_grid_display_add_to_cart', TRUE)) {
			  if (variable_get('uc_catalog_grid_display_attributes', TRUE)) {
				$product_table .= theme('uc_product_add_to_cart', $product);
			  } else {
				$product_table .= drupal_get_form('uc_catalog_buy_it_now_form_'. $product->nid, $product);
			  }
			}
		} else {
			if (($product->type === 'download_product') && ($product->sell_price == 0)) {
				$product_table .= '<p><span class="price-ours">FREE</span></p>';
	
				if (module_exists('uc_cart') && variable_get('uc_catalog_grid_display_add_to_cart', TRUE)) {
				  if (variable_get('uc_catalog_grid_display_attributes', TRUE)) {
					$product_table .= theme('uc_product_add_to_cart', $product);
				  } else {
					$product_table .= drupal_get_form('uc_catalog_buy_it_now_form_'. $product->nid, $product);
				  }
				}
			} else {
				$product_table .= '<div class="call-us">Call us to place an order: 1-877-236-4434</div>';
			}
		}
    }

    $product_table .= '</div>';
    $count++;
  }

  $product_table .= "</div>";
  return $product_table;
}

/**
 * Display a formatted catalog page.
 *
 * If the category has products in it, display them in a TAPIr table. Subcategories
 *   are linked along the top of the page. If it does not have products, display
 *   subcategories in a grid with their images and subcategories.
 *
 * @param $tid
 *   Catalog term id from URL.
 * @return
 *   Formatted HTML of the catalog page.
 * @ingroup themeable
 */
function thehifiweb_uc_catalog_browse($tid = 0) {
  drupal_add_css(drupal_get_path('module', 'uc_catalog') .'/uc_catalog.css');

  $output = '';
  $tidWhere = '';
  $childTIDs = array();
  $catalog = uc_catalog_get_page((int)$tid);
  drupal_set_title(check_plain($catalog->name));
  drupal_set_breadcrumb(uc_catalog_set_breadcrumb($catalog->tid));
  $types = uc_product_types();
  $child_list = array();
  
  foreach ($catalog->children as $child) {
    // We want to show all the subcategory children on the parent page.
    $childTIDs[] = 'tn.tid = ' . $child->tid;
    
    if (!empty($child->image)) {
      $image = '<div>';
      if (module_exists('imagecache')) {
        $image .= l(theme('imagecache', 'uc_category', $child->image['filepath']), uc_catalog_path($child), array('html' => TRUE));
      }
      else {
        $image .= l(theme('image', $child->image['filepath']), uc_catalog_path($child), array('html' => TRUE));
      }
      $image .= '</div>';
    }
    else {
      $image = '<div></div>';
    }
    $grandchildren = array();
    $j = 0;
    $max_gc_display = 4;
    foreach ($child->children as $i => $grandchild) {
      if ($j > $max_gc_display) {
        break;
      }
      $g_child_nodes = 0;
      foreach ($types as $type) {
        $g_child_nodes += taxonomy_term_count_nodes($grandchild->tid, $type);
      }
      if ($g_child_nodes) {
        $grandchildren[$i] = l($grandchild->name, uc_catalog_path($grandchild), array('class' => 'subcategory'));
        $j++;
      }
    }
    //$grandchildren = array_slice($grandchildren, 0, intval(count($grandchildren) / 2) + 1, TRUE);
    if ($j > $max_gc_display) {
      array_push($grandchildren, l(t('More...'), uc_catalog_path($child), array('class' => 'subcategory')));
    }
    
    if ($child->nodes) {
      $cell_link = $image .'<strong>'. l($child->name, uc_catalog_path($child)) .'</strong>';
      if (variable_get('uc_catalog_show_subcategories', TRUE)) {
        $cell_link .= "<br/><span>". implode(', ', $grandchildren) ."</span>\n";
      }
      $child_list[] = $cell_link;
    }
  }

  if(!empty($childTIDs)) {
  	$tidWhere = 'OR ' . implode(' OR ', $childTIDs);
  	$tidWhere .= ' ';
  }

  if (!empty($catalog->image)) {
    if (module_exists('imagecache')) {
      $output .= theme('imagecache', 'uc_thumbnail', $catalog->image['filepath'], $catalog->name, $catalog->name, array('class' => 'category'));
    }
    else {
      $output .= theme('image', $catalog->image['filepath'], $catalog->name, $catalog->name, array('class' => 'category'));
    }
  }
  
  // Build an ORDER BY clause for the SELECT query based on table sort info.
  if (empty($_REQUEST['order'])) {
    $order = 'ORDER BY p.ordering, n.title, n.nid';
  } else {
    $order = tapirsort_sql(uc_product_table_header());
  }

  $sql = "SELECT DISTINCT(n.nid), n.sticky, n.title, n.created, p.model, p.sell_price, p.ordering
    FROM {node} n
      INNER JOIN {term_node} tn ON n.vid = tn.vid
      INNER JOIN {uc_products} AS p ON n.vid = p.vid
    WHERE tn.tid = %d " . $tidWhere . "AND n.status = 1
      AND n.type IN (". db_placeholders($types, 'varchar') .") ". $order;

  $sql_count = "SELECT COUNT(DISTINCT(n.nid))
    FROM {node} n
      INNER JOIN {term_node} tn ON n.vid = tn.vid
      INNER JOIN {uc_products} AS p ON n.vid = p.vid
    WHERE tn.tid = %d " . $tidWhere . "
      AND n.status = 1
      AND n.type IN (". db_placeholders($types, 'varchar') .")";

  $sql = db_rewrite_sql($sql);
  $sql_count = db_rewrite_sql($sql_count);
  $sql_args = array($catalog->tid);
  foreach ($types as $type) {
    $sql_args[] = $type;
  }
  $catalog->products = array();
  $result = pager_query($sql, variable_get('uc_product_nodes_per_page', 12), 0, $sql_count, $sql_args);
  while ($node = db_fetch_object($result)) {
    $catalog->products[] = $node->nid;
  }
  if (count($catalog->products)) {
    $output .= $catalog->description;
    $output .= theme('uc_catalog_product_grid', $catalog->products);
    $output .= theme('pager');
  }
  
  return $output;
}

/**
 * Theme a formatted price for display.
 * Modified to turn off prefix labels for product types.
 *
 * @ingroup themeable
 */
function thehifiweb_uc_price($value, $context, $options) {
  // Fixup class names.
  if (!is_array($context['class'])) {
    $context['class'] = array();
  }
  foreach ($context['class'] as $key => $class) {
    $context['class'][$key] = 'uc-price-'. $class;
  }
  $context['class'][] = 'uc-price';

  // Class the element.
  $output = '<span class="'. implode(' ', $context['class']) .'">';

  // Prefix(es).
  if ($options['label'] && isset($options['prefixes']) && $context['type'] !== 'product') {
    $output .= '<span class="price-prefixes">'. implode('', $options['prefixes']) .'</span>';
  }
  // Value.
  $output .= $value;
  // Suffix(es).
  if ($options['label'] && isset($options['suffixes'])) {
    $output .= '<span class="price-suffixes">'. implode('', $options['suffixes']) .'</span>';
  }
  $output .= '</span>';

  return $output;
}

/**
 * Format a product's price.
 * Modified to add price-ours class to sell_price.
 *
 * This is an extra wrapper theme around the output of uc_price() when it is
 * used in the product body. For expedience, it takes the same parameters as
 * uc_price().
 *
 * @param $price
 *   The monetary amount.
 * @param $context
 *   Determines the CSS class of the <div>, and helps determine if the price
 *   needs to be altered.
 * @param $options
 *   Toggles the label and other formatting.
 * @ingroup themeable
 * @see uc_price()
 */
function thehifiweb_uc_product_price($price, $context, $options = array()) {
  if ($context['field'] === 'sell_price') $context['class'][] = 'price-ours';
  $output = '<div class="product-info '. implode(' ', (array)$context['class']) .'">';
  $output .= uc_price($price, $context, $options);
  $output .= '</div>';

  return $output;
}

/**
 * This inline js sets up the timer for this slideshow.
 * We're going to add some additional javascript here to avoid altering views_slideshow.js 
 * (specifically, we're adding the views_slideshow_prev_div() function).
 */
function thehifiweb_views_slideshow_div_js($rows, $options, $id) {
  $hover = 'hover';
  if ($options['hover'] == 'hoverIntent') {
    if (module_exists('jq')) {
      $hover = jq_add('hoverIntent') ? 'hoverIntent' : 'hover';
    }
    else if (module_exists('hoverintent')) {
      $hover = hoverintent_add() ? 'hoverIntent' : 'hover';
    }
  }

  $num_divs = sizeof($rows);
  $fade = $options['fade'] ? 'true' : 'false';

  $js = <<<JS
// Set the timer data for a view slideshow.
$(document).ready(function() {
  // These are the divs containing the elements to be displayed in the main div in rotation or mouseover.
  slideshow_data["$id"] = new views_slideshow_data($num_divs, {$options['timer_delay']}, {$options['sort']}, $fade, "{$options['fade_speed']}", {$options['fade_value']});

  // This turns on the timer.
  views_slideshow_timer("$id", true);

  // This sets up the mouseover & mouseout to pause on the main element.
  $("#views_slideshow_main_$id").$hover(
    function() {
      views_slideshow_pause("$id");
    },
    function() {
      views_slideshow_resume("$id");
    });
});

// get the previous node div in our sequence
function views_slideshow_prev_div(slideshow_main) {
  if (slideshow_data[slideshow_main]._sort_order) {
	// select the prev div, in forward or reverse order
	new_div_number = slideshow_data[slideshow_main]._current_div - slideshow_data[slideshow_main]._sort_order;
  }
  else {
	// select a random div, but make sure we don't repeat ourselves, unless there's only one div
	do {
	  new_div_number = Math.floor(Math.random() * slideshow_data[slideshow_main]._num_divs);
	} while (slideshow_data[slideshow_main]._num_divs > 1 && (new_div_number == slideshow_data[slideshow_main]._num_divs - 1));
  }
  return new_div_number;
}
JS;
  return $js;
}

function thehifiweb_status_messages($display = NULL) {
  $output = '';
  foreach (drupal_get_messages($display) as $type => $messages) {
    if (count($messages) > 1) {
      $list  = '';
      $items = '';
      foreach ($messages as $message) {
        
        modify_message($message);
        if (!empty($message)) {
          $items .= '  <li>'. $message ."</li>\n";
        }
      }
      if (!empty($items)) {
        $list = " <ul>\n";
        $list .= $items;
        $list .= " </ul>\n";
      }
    }
    else {
      $message = $messages[0];
      modify_message($message);
      $list = $message;
    }
    
    if (!empty($list)) {
      $output .= "<div class=\"messages $type\">\n";
      $output .= $list;
      $output .= "</div>\n";
    }
  }
  return $output;
}

/**
* Modify/hide a message from user:
*
* @param string $message
*/
function modify_message(&$message) {
//Add here whatever you need to recognize and modify this message:

  global $user;
    
  // Hide these messages:
  $message = preg_replace('/No posts in this group\./', '', $message);
  $message = preg_replace('/Fetching data from GeoNames failed.*/', '', $message);
  $message = preg_replace('/The directory .*? has been created\./', '', $message);
  
  //If the administrator has removed _himself_, the message should instead of
  //"Username_ABC is no longer a group administrator." say:
  //"You are no longer an administrator for this group. To regain administrator privileges, please contact an administrator for this group."
  $current_name = $user->name;
  preg_match('/(.*) is no longer a\.*/', $message, $matches_A);
   //check if the current user is the same as the user in the message:
  if ($matches_A[1]) preg_match("/$current_name/", $matches_A[1], $matches_B);
  if ($matches_B[0]) $message = "You are no longer an administrator for this group. To regain administrator privileges, please contact an administrator for this group.";
}