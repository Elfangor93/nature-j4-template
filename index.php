<?php
/**
 * @package     Joomla.Site
 * @subpackage  Templates.nature
 *
 * @copyright   (C) 2021 Dr. Menzel IT. <https://www.dr-menzel-it.de>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

/** @var Joomla\CMS\Document\HtmlDocument $this */

$app       = Factory::getApplication();
$wa        = $this->getWebAssetManager();
$document  = $app->getDocument();

// Detecting Active Variables
$option    = $app->input->getCmd('option', '');
$view      = $app->input->getCmd('view', '');
$layout    = $app->input->getCmd('layout', '');
$task      = $app->input->getCmd('task', '');
$itemid    = $app->input->getCmd('Itemid', '');
$id        = $app->input->getCmd('id', '');
$sitename  = htmlspecialchars($app->get('sitename'), ENT_QUOTES, 'UTF-8');
$menu      = $app->getMenu();
$pageclass  = $menu->getActive() !== null ? $menu->getActive()->getParams()->get('pageclass_sfx', '') : '';
$params     = $this->params;

// Set generator
$this->setGenerator($params->get('generator', 'Joomla! - Open Source Content Management'));

// Get title image
if (!empty($menu->getActive()->getParams()->get('menu_image')))
{
  // get menu item image
  $image = HTMLHelper::image($menu->getActive()->getParams()->get('menu_image'), $menu->getActive()->title);
}
elseif ($option == 'com_content' && $view == 'article')
{
  // get article image
  $item = $app->bootComponent('com_content')->getMVCFactory()->createTable($view);
  $item->load($id);
  $images = json_decode($item->images);

  if (empty($images->image_fulltext))
  {
    $image = false;
  }
  else
  {
    $alt = empty($images->image_fulltext_alt) && empty($images->image_fulltext_alt_empty) ? false : $images->image_fulltext_alt;
    $image = HTMLHelper::image($images->image_fulltext, $alt);
  }
}
elseif ($option == 'com_content' && $view == 'category')
{
  // get category image
  $item = $app->bootComponent('com_categories')->getMVCFactory()->createTable($view);
  $item->load($id);
  $cat_params = json_decode($item->params);

  if (empty($cat_params->image))
  {
    $image = false;
  }
  else
  {
    $alt = empty($cat_params->image_alt) ? false : $cat_params->image_alt;
    $image = HTMLHelper::image($cat_params->image, $alt);
  }
}
else
{
  // get image from module
  $image = false;
}

// Template path
$templatePath = 'media/templates/site/nature';

// Icons
$search = '<svg focusable="false" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
<path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
</svg>';
$open = '<svg focusable="false" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="34" height="34" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
<path fill-rule="evenodd" d="M2.5 11.5A.5.5 0 0 1 3 11h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4A.5.5 0 0 1 3 7h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4A.5.5 0 0 1 3 3h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
</svg>';

// Divider
$wave = '<svg focusable="false" aria-hidden="true" viewBox="0 0 500 150" preserveAspectRatio="none">
<path d="M0.00,49.98 C253.38,114.77 349.20,-50.00 500.00,49.98 L500.00,150.00 L0.00,150.00 Z" style="stroke: none;"></path>
</svg>';
$tiltr = '<svg focusable="false" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 150" preserveAspectRatio="none">
<path d="M0,30L500,150L500,150L0,150Z"></path>
</svg>';
$tiltl = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 150" preserveAspectRatio="none">
<path d="M0,150L500,30L500,150L0,150Z"></path>
</svg>';

// Load burgerMenu
$hasBurger = '';

if ($params->get('burgerMenu') == 1)
{
    $hasBurger .= 'navbar__with-burger';
}

// Load Icons
if ($params->get('icons') == 1)
{
    $wa->useStyle('fontawesome');
    $this->getPreloadManager()->preload($wa->getAsset('style', 'fontawesome')->getUri() . '?' . $this->getMediaVersion(), ['as' => 'style']);
}
elseif ($params->get('icons') == 2)
{
    $wa->registerAndUseStyle('bi-icons', $templatePath . '/css/bootstrap-icons.css');
    $wa->registerAndUseStyle('icons', $templatePath . '/css/icons.css');
    $this->getPreloadManager()->preload($wa->getAsset('style', 'bi-icons')->getUri() . '?' . $this->getMediaVersion(), ['as' => 'style']);
    $this->getPreloadManager()->preload($wa->getAsset('style', 'icons')->getUri() . '?' . $this->getMediaVersion(), ['as' => 'style']);
}

// Use a font scheme if set in the template style options
$paramsFontScheme = $params->get('useFontScheme', false);

if ($paramsFontScheme)
{
    $assetFontScheme = 'fontscheme.' . $paramsFontScheme;
    $wa->registerAndUseStyle($assetFontScheme, $templatePath . '/css/global/' . $paramsFontScheme . '.css');
    $this->getPreloadManager()->preload($wa->getAsset('style', $assetFontScheme)->getUri() . '?' . $this->getMediaVersion(), ['as' => 'style']);
}

// Enable assets
$wa->useStyle('template.nature')
    ->useScript('template.nature')
    ->useStyle('template.user')
    ->useScript('template.user');
$this->getPreloadManager()->preload($wa->getAsset('style', 'template.nature')->getUri() . '?' . $this->getMediaVersion(), ['as' => 'style']);

// Logo file or site title param
if ($params->get('logoFile'))
{
  $logo_path_arr = explode('#', htmlspecialchars($params->get('logoFile'), ENT_QUOTES));
  list($logo_width, $logo_height, $logo_type, $logo_attr) = getimagesize($logo_path_arr[0]);

  $logo = '<img id="logo" style="max-width:250px" src="' . Uri::root() . htmlspecialchars($params->get('logoFile'), ENT_QUOTES) . '" alt="' . $sitename . '" '.$logo_attr.'>';
  
  if ($params->get('logoFile_small'))
  {
    $logosmall_path_arr = explode('#', htmlspecialchars($params->get('logoFile_small'), ENT_QUOTES));
    list($logosmall_width, $logosmall_height, $logosmall_type, $logosmall_attr) = getimagesize($logosmall_path_arr[0]);

    $logo .= '<img id="logo-small" style="max-width:250px" src="' . Uri::root() . htmlspecialchars($params->get('logoFile_small'), ENT_QUOTES) . '" alt="' . $sitename . '" '.$logosmall_attr.'>';
  }
}
elseif ($params->get('siteTitle'))
{
  list($logo_width, $logo_height) = array(250, 92);

  $logo = '<span id="logo" style="max-width:250px" title="' . $sitename . '">' . htmlspecialchars($params->get('siteTitle'), ENT_COMPAT, 'UTF-8') . '</span>';
}
else
{
  list($logo_width, $logo_height, $logo_type, $logo_attr) = getimagesize($templatePath . '/images/nature-logo.png');

  $logo = '<img id="logo" style="max-width:250px" src="' . $templatePath . '/images/nature-logo.png" class="logo" alt="' . $sitename . '">';
}

$hasClass = '';

if ($this->countModules('sidebar-left', true))
{
    $hasClass .= 'with-sidebar-left';
}

if ($this->countModules('sidebar-right', true))
{
    $hasClass .= 'with-sidebar-right';
}

$stickyHeader = $params->get('stickyHeader') ? 'position-sticky sticky-top' : '';

// Favicons
include 'templates/nature/includes/favicons.php';

$this->setMetaData('viewport', 'width=device-width, initial-scale=1');
?>

<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
    <jdoc:include type="metas" />

    <meta property="og:title" content="<?php echo $params->get('sitename');?>">
    <meta property="og:type" content="website"/>
    <meta property="og:url" content="<?php echo str_replace('" ','&quot;', Uri::current());?>">
    <meta property="og:image" content="<?php echo Uri::root() . 'images/logo_osg.png';?>"/>
    <meta property="og:site_name" content="Iten Bewässerungen"/>
    <meta property="og:description" content="<?php echo $params->get( 'MetaDesc' );?>"/>

    <?php include "templates/nature/includes/style.php";?>
    <jdoc:include type="styles" />
    <style>
        header #brand-logo {
          height: <?php echo $logo_height; ?>;
        }
    </style>
    <jdoc:include type="scripts" />
</head>
<body class="site <?php echo $option
    . ' view-' . $view
    . ($layout ? ' layout-' . $layout : ' no-layout')
    . ($task ? ' task-' . $task : ' no-task')
    . ($itemid ? ' itemid-' . $itemid : '')
  . (($menu->getActive() == $menu->getDefault()) ? ' front' : ' page')
    . ' ' . $pageclass;
    echo ($this->direction == 'rtl' ? ' rtl' : '');
?>">
    <?php if ($this->countModules('top-header', true)) : ?>
        <div class="container-top-header">
            <div class="wrapper">
                <jdoc:include type="modules" name="top-header" style="none" />
            </div>
        </div>
    <?php endif; ?>
    <header id="header-elem" class="header <?php echo $stickyHeader; ?>">
        <a href="#main" class="skip-link">Skip to main content</a>
        <div class="wrapper header__wrapper">
            <?php if ($params->get('logoPosition')) : ?>
                <?php if ($this->countModules('logo', true)) : ?>
                    <div class="header__start navbar-brand">
                        <jdoc:include type="modules" name="logo" />
                    </div>
                <?php endif; ?>
            <?php else : ?>
                <div class="header__start navbar-brand">
                    <a id="brand-logo" class="brand-logo" href="<?php echo $this->baseurl; ?>/">
                        <?php echo $logo; ?>
                    </a>
                    <?php if ($params->get('siteDescription')) : ?>
                        <div class="site-description"><?php echo htmlspecialchars($params->get('siteDescription')); ?></div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if (($this->countModules('menu', true)) || ($this->countModules('search', true))) : ?>
            <div class="header__end">
                <?php if ($this->countModules('menu', true)) : ?>
                    <nav class="navbar-top <?php echo $hasBurger; ?>" aria-label="Top Navigation" id="menu">

                    <?php if ($params->get('burgerMenu') == 1): ?>
                        <button class="nav__toggle" aria-expanded="false" type="button" aria-label="Open navigation"><?php echo $open; ?></button>
                    <?php endif; ?>
                        <jdoc:include type="modules" name="menu" />
                    </nav>
                <?php endif; ?>

                <?php if ($this->countModules('search', true)) : ?>
                <div class="search">
                    <button class="search__toggle" aria-label="Open search"><?php echo $search; ?></button>
                    <div class="container-search">
                        <jdoc:include type="modules" name="search" />
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </header>

    <?php if ($image || $this->countModules('banner', true)) : ?>
      <div class="container-banner">
        <?php if($image) : ?>
          <?php echo $image; ?>
        <?php else: ?>
          <jdoc:include type="modules" name="banner" />
        <?php endif; ?>
        <div class="round-shape-divider banner"></div>
      </div>
    <?php endif; ?>

    <?php if ($this->countModules('top-a', true)) : ?>
    <div class="container-top-a <?php echo ($params->get('topa') == 1 ? 'with-wrapper' : ''); ?>">
        <div class="grid <?php echo $params->get('topa') ? 'wrapper' : 'full-width'; ?> columns-<?php echo $params->get('topacols'); ?>">
            <jdoc:include type="modules" name="top-a" />
        </div>
    </div>
    <?php endif; ?>

    <?php if ($this->countModules('top-b', true)) : ?>
        <?php if ($params->get('topbdiv') != 0) : ?>
        <div class="custom-shape-divider top-b">
            <?php if ($params->get('topbdiv') == 1) : ?>
                <?php echo $wave; ?>
            <?php endif; ?>

            <?php if ($params->get('topbdiv') == 2) : ?>
                <?php echo $tiltr; ?>
            <?php endif; ?>

            <?php if ($params->get('topbdiv') == 3) : ?>
                <?php echo $tiltl; ?>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        <div class="container-top-b <?php echo ($params->get('topb') == 1 ? 'with-wrapper' : ''); ?>">
            <div class="grid <?php echo $params->get('topb') ? 'wrapper' : 'full-width'; ?> columns-<?php echo $params->get('topbcols'); ?>">
                <jdoc:include type="modules" name="top-b" />
            </div>
        </div>
    <?php endif; ?>

    <?php if ($this->countModules('breadcrumbs', true)) : ?>
        <jdoc:include type="modules" name="breadcrumbs" />
    <?php endif; ?>

    <main id="main" tabindex="-1">
        <div class="wrapper">
            <jdoc:include type="message" />

            <div class="main-content <?php echo $hasClass; ?>">

                <?php if (($params->get('sidebar') == 0) && ($this->countModules('sidebar-left', true))) : ?>
                    <div class="container-sidebar sidebar--left">
                        <jdoc:include type="modules" name="sidebar-left" style="html5" />
                    </div>
                <?php endif; ?>

                <div class="container-content">

                    <?php if ($this->countModules('main-top', true)) : ?>
                    <jdoc:include type="modules" name="main-top" />
                    <?php endif; ?>

                    <jdoc:include type="component" />

                    <?php if ($this->countModules('main-bottom', true)) : ?>
                    <jdoc:include type="modules" name="main-bottom" />
                    <?php endif; ?>

                </div>

                <?php if (($params->get('sidebar') == 1) && ($this->countModules('sidebar-right', true))) : ?>
                    <div class="container-sidebar sidebar--right">
                        <jdoc:include type="modules" name="sidebar-right" style="html5" />
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </main>

    <?php if ($this->countModules('bottom-a', true)) : ?>
        <?php if ($params->get('bottomadiv') != 0) : ?>
        <div class="custom-shape-divider bottom-a">
            <?php if ($params->get('bottomadiv') == 1) : ?>
                <?php echo $wave; ?>
            <?php endif; ?>

            <?php if ($params->get('bottomadiv') == 2) : ?>
                <?php echo $tiltr; ?>
            <?php endif; ?>

            <?php if ($params->get('bottomadiv') == 3) : ?>
                <?php echo $tiltl; ?>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        <div class="container-bottom-a <?php echo ($params->get('bottoma') == 1 ? 'with-wrapper' : ''); ?>">
            <div class="grid <?php echo $params->get('bottoma') ? 'wrapper' : 'full-width'; ?> columns-<?php echo $params->get('bottomacols'); ?>">
                <jdoc:include type="modules" name="bottom-a" style="html5" />
            </div>
        </div>
    <?php endif; ?>

    <?php if ($this->countModules('bottom-b', true)) : ?>
        <?php if ($params->get('bottombdiv') != 0) : ?>
        <div class="custom-shape-divider bottom-b">
            <?php if ($params->get('bottombdiv') == 1) : ?>
                <?php echo $wave; ?>
            <?php endif; ?>

            <?php if ($params->get('bottombdiv') == 2) : ?>
                <?php echo $tiltr; ?>
            <?php endif; ?>

            <?php if ($params->get('bottombdiv') == 3) : ?>
                <?php echo $tiltl; ?>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        <div class="container-bottom-b <?php echo ($params->get('bottomb') == 1 ? 'with-wrapper' : ''); ?>">
            <div class="grid <?php echo $params->get('bottomb') ? 'wrapper' : 'full-width'; ?> columns-<?php echo $params->get('bottombcols'); ?>">
                <jdoc:include type="modules" name="bottom-b" />
            </div>
        </div>
    <?php endif; ?>

    <?php if ($this->countModules('footer', true)) : ?>
      <div class="round-shape-divider footer"></div>
      <footer class="container-footer">
        <div class="wrapper container-footer_wrapper">
          <jdoc:include type="modules" name="footer" />
          <div id="copyright" class="copyright">
            <p>© <?php echo date("Y") . ' ' . $params->get('copyright'); ?> </p>
          </div>
        </div>
      </footer>
    <?php endif; ?>

    <?php if ($params->get('backTop') == 1) : ?>
        <div class="back-to-top-wrapper">
            <a href="#top" id="back-top" class="back-to-top-link" aria-label="<?php echo Text::_('TPL_NATURE_BACKTOTOP'); ?>">
                <span class="arrow-up" aria-hidden="true">&#9650;</span>
            </a>
        </div>
    <?php endif; ?>

    <jdoc:include type="modules" name="debug" style="none" />

    <script>
      // When the user scrolls down 50px from the top of the document, resize the logo
      window.onscroll = function() {scrollFunction()};

      function scrollFunction() {
        if (document.documentElement.scrollTop > 130) {
          <?php if ($params->get('logoFile_small')) :?>
            document.getElementById("logo").style.opacity = "0";
            document.getElementById("logo").style.height = "0";
            document.getElementById("brand-logo").style.height = "60px";
            document.getElementById("logo-small").style.height = "55px";
          <?php endif; ?>
        } else if (document.documentElement.scrollTop < 20) {
          <?php if ($params->get('logoFile_small')) :?>
            document.getElementById("brand-logo").style.height = "160px";
            document.getElementById("logo-small").style.height = "98px";
            document.getElementById("logo").style.opacity = "1";
            document.getElementById("logo").style.height = "auto";
          <?php endif; ?>
        }
      }
    </script>

</body>
</html>
