<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_contact
 *
 * @copyright   (C) 2006 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\String\PunycodeHelper;

$icon = $this->params->get('contact_icons') == 0;

/**
 * Marker_class: Class based on the selection of text, none, or icons
 * jicon-text, jicon-none, jicon-icon
 */
?>
<div class="com-contact__address contact-address dl-horizontal" itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
  <?php if ($this->item->name && $this->show_name) : ?>
    <div class="flex-right block">  
      <div class="contact-icon">
        <span class="icon-home" aria-hidden="true"></span><span class="visually-hidden"><?php echo Text::_('COM_CONTACT_NAME'); ?></span>
      </div>
      <div class="contact-company" itemprop="company"><?php echo $this->item->name; ?></div>
    </div>
  <?php endif; ?>

	<?php if (($this->params->get('address_check') > 0) &&
		($this->item->address || $this->item->suburb  || $this->item->state || $this->item->country || $this->item->postcode)) : ?>
    <div class="flex-right block">
			<?php if ($icon && !$this->params->get('marker_address')) : ?>
        <div class="contact-icon">
          <span class="icon-address" aria-hidden="true"></span><span class="visually-hidden"><?php echo Text::_('COM_CONTACT_ADDRESS'); ?></span>
        </div>				
			<?php else : ?>
        <div class="contact-icon">
          <span class="<?php echo $this->params->get('marker_class'); ?>">
            <?php echo $this->params->get('marker_address'); ?>
          </span>
        </div>
			<?php endif; ?>

      <div class="contact-address">
        <ul>
          <?php if ($this->item->con_position) : ?>
              <li><span class="contact-name" itemprop="name"><?php echo $this->item->con_position; ?></span></li>
          <?php endif; ?>

          <?php if ($this->item->address && $this->params->get('show_street_address')) : ?>
            <li>
              <span class="contact-street" itemprop="streetAddress"><?php echo nl2br($this->item->address, false); ?></span>
            </li>
          <?php endif; ?>

          <li>
            <?php if ($this->item->suburb && $this->params->get('show_suburb')) : ?>
              <span class="contact-suburb" itemprop="addressLocality">
                <?php echo $this->item->suburb; ?>
              </span>
            <?php endif; ?>

            <?php if ($this->item->postcode && $this->params->get('show_postcode')) : ?>
              <span class="contact-postcode" itemprop="postalCode">
                <?php echo $this->item->postcode; ?>
              </span>
            <?php endif; ?>
          </li>

          <?php if ($this->item->state && $this->params->get('show_state')) : ?>
            <li>
              <span class="contact-state" itemprop="addressRegion">
                <?php echo $this->item->state; ?>
              </span>
            </li>
          <?php endif; ?>

          <?php if ($this->item->country && $this->params->get('show_country')) : ?>
            <li>
              <span class="contact-country" itemprop="addressCountry">
                <?php echo $this->item->country; ?>
              </span>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
	<?php endif; ?>

  <?php if ($this->item->email_to && $this->params->get('show_email')) : ?>
    <div class="flex-right block">
      <div class="contact-icon">
        <?php if ($icon && !$this->params->get('marker_email')) : ?>
          <span class="icon-envelope" aria-hidden="true"></span><span class="visually-hidden"><?php echo Text::_('COM_CONTACT_EMAIL'); ?>"></span>
        <?php else : ?>
          <span class="<?php echo $this->params->get('marker_class'); ?>">
            <?php echo $this->params->get('marker_email'); ?>
          </span>
        <?php endif; ?>
      </div>
      <div class="contact-emailto">
        <?php echo $this->item->email_to; ?>
      </div>
    </div>
  <?php endif; ?>

  <?php if ($this->item->telephone && $this->params->get('show_telephone')) : ?>
    <div class="flex-right block">
      <div class="contact-icon">
        <?php if ($icon && !$this->params->get('marker_telephone')) : ?>
            <span class="icon-phone" aria-hidden="true"></span><span class="visually-hidden"><?php echo Text::_('COM_CONTACT_TELEPHONE'); ?></span>
        <?php else : ?>
          <span class="<?php echo $this->params->get('marker_class'); ?>">
            <?php echo $this->params->get('marker_telephone'); ?>
          </span>
        <?php endif; ?>
      </div>
      <div class="contact-telephone" itemprop="telephone">
        <?php echo $this->item->telephone; ?>
      </div>
    </div>
  <?php endif; ?>

  <?php if ($this->item->fax && $this->params->get('show_fax')) : ?>
    <div class="flex-right block">
      <div class="contact-icon">
        <?php if ($icon && !$this->params->get('marker_fax')) : ?>
          <span class="icon-fax" aria-hidden="true"></span><span class="visually-hidden"><?php echo Text::_('COM_CONTACT_FAX'); ?></span>
        <?php else : ?>
          <span class="<?php echo $this->params->get('marker_class'); ?>">
            <?php echo $this->params->get('marker_fax'); ?>
          </span>
        <?php endif; ?>
      </div>
      <div class="contact-fax" itemprop="faxNumber">
        <?php echo $this->item->fax; ?>
      </div>
    </div>
  <?php endif; ?>

  <?php if ($this->item->mobile && $this->params->get('show_mobile')) : ?>
    <div class="flex-right block">
      <div class="contact-icon">
        <?php if ($icon && !$this->params->get('marker_mobile')) : ?>
          <span class="icon-mobile" aria-hidden="true"></span><span class="visually-hidden"><?php echo Text::_('COM_CONTACT_MOBILE'); ?></span>
        <?php else : ?>
          <span class="<?php echo $this->params->get('marker_class'); ?>">
            <?php echo $this->params->get('marker_mobile'); ?>
          </span>
        <?php endif; ?>
      </div>
      <div class="contact-mobile" itemprop="telephone">
        <?php echo $this->item->mobile; ?>
      </div>
    </div>
  <?php endif; ?>

  <?php if ($this->item->webpage && $this->params->get('show_webpage')) : ?>
    <div class="flex-right block">
      <div class="contact-icon">
        <?php if ($icon && !$this->params->get('marker_webpage')) : ?>
          <span class="icon-home" aria-hidden="true"></span><span class="visually-hidden"><?php echo Text::_('COM_CONTACT_WEBPAGE'); ?></span>
        <?php else : ?>
          <span class="<?php echo $this->params->get('marker_class'); ?>">
            <?php echo $this->params->get('marker_webpage'); ?>
          </span>
        <?php endif; ?>
      </div>
      <div class="contact-webpage">
        <a href="<?php echo $this->item->webpage; ?>" target="_blank" rel="noopener noreferrer" itemprop="url">
        <?php echo PunycodeHelper::urlToUTF8($this->item->webpage); ?></a>
      </div>
    </div>
  <?php endif; ?>
</div>
