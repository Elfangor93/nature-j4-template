<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   (C) 2010 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\Component\Content\Site\Helper\RouteHelper;

$app    = Factory::getApplication();
$lang   = Factory::getLanguage();
$user   = Factory::getUser();
$groups = $user->getAuthorisedViewLevels();

$fieldModel = $app->bootComponent('com_fields')->getMVCFactory()->createModel('Field', 'Administrator', ['ignore_request' => true]);

if ($this->maxLevel != 0 && count($this->children[$this->category->id]) > 0) : ?>

	<?php foreach ($this->children[$this->category->id] as $id => $child) : ?>
    <?php
      // Get intro image
      $field_id = 1;
      $child->intro_image = json_decode($fieldModel->getFieldValue($field_id, $child->id));
    ?>
		<?php // Check whether category access level allows access to subcategories. ?>
		<?php if (in_array($child->access, $groups)) : 
      $nchild = '';
      if ($id == 0) {
        $nchild = 'first';
      }
      if ($id == (count($this->children[$this->category->id])-1)) {
        $nchild = 'last';
      }
      ?>
			<?php if ($this->params->get('show_empty_categories') || $child->numitems || count($child->getChildren())) : ?>
			<div class="com-content-category-blog__child child-item <?php echo $nchild; ?>">
        <div class="item-content">

          <div class="text">
            <div class="page-header">
              <h2><?php echo $this->escape($child->title); ?></h2>
              <?php if ( $this->params->get('show_cat_num_articles', 1)) : ?>
                <span class="badge bg-info">
                  <?php echo Text::_('COM_CONTENT_NUM_ITEMS'); ?>&nbsp;
                  <?php echo $child->getNumItems(true); ?>
                </span>
              <?php endif; ?>

              <?php if ($this->maxLevel > 1 && count($child->getChildren()) > 0) : ?>
                <a href="#category-<?php echo $child->id; ?>" data-bs-toggle="collapse" class="btn btn-sm float-end" aria-label="<?php echo Text::_('JGLOBAL_EXPAND_CATEGORIES'); ?>"><span class="icon-plus" aria-hidden="true"></span></a>
              <?php endif; ?>
              </div>

            <?php if ($this->params->get('show_subcat_desc') == 1) : ?>
              <?php if ($child->description) : ?>
                <div class="com-content-category-blog__description category-desc">
                  <?php echo HTMLHelper::_('content.prepare', $child->description, '', 'com_content.category'); ?>
                </div>
              <?php endif; ?>
            <?php endif; ?>

            <p class="readmore">
              <a class="btn btn-secondary" href="<?php echo Route::_(RouteHelper::getCategoryRoute($child->id, $child->language)); ?>" aria-label="<?php echo Text::sprintf('JGLOBAL_READ_MORE_TITLE', $child->title); ?>">
                <?php echo Text::_('JGLOBAL_READ_MORE'); ?>
              </a>
            </p>
          </div>

          <div class="image">
            <figure class="item-image">
                <?php
                  $layoutAttr = [
                    'src' => $child->intro_image->imagefile,
                    'alt' => empty($child->intro_image->alt_text) ? false : $child->intro_image->alt_text,
                    'itemprop' => 'thumbnail'
                  ];
                ?>
                <?php echo LayoutHelper::render('joomla.html.image', $layoutAttr); ?>
            </figure>
          </div>

          <?php if ($this->maxLevel > 1 && count($child->getChildren()) > 0) : ?>
          <div class="com-content-category-blog__children collapse fade" id="category-<?php echo $child->id; ?>">
            <?php
            $this->children[$child->id] = $child->getChildren();
            $this->category = $child;
            $this->maxLevel--;
            echo $this->loadTemplate('children');
            $this->category = $child->getParent();
            $this->maxLevel++;
            ?>
          </div>
          <?php endif; ?>

        </div>
			</div>
			<?php endif; ?>
		<?php endif; ?>
	<?php endforeach; ?>

<?php endif;
