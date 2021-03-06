<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Video
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: embed.tpl 9747 2012-07-26 02:08:08Z john $
 * @author     Jung
 */
?>

<div style="padding: 20px;padding-bottom: 5px;">
  <?php if( $this->error == 1 ): ?>
    <?php echo $this->translate('Embedding of videos has been disabled.') ?>
    <?php return ?>
  <?php elseif( $this->error == 2 ): ?>
    <?php echo $this->translate('Embedding of videos has been disabled for this video.') ?>
    <?php return ?>
  <?php elseif( !$this->video || $this->video->status != 1 ): ?>
    <?php echo $this->translate('The video you are looking for does not exist or has not been processed yet.') ?>
    <?php return ?>
  <?php endif; ?>

  <textarea cols="50" rows="4"><?php
    echo $this->embedCode;
  ?></textarea>

  <br />
  <br />

  <div style="text-align: center">
    <a href="javascript:void(0);" onclick="parent.Smoothbox.close();">
      <?php echo $this->translate('close') ?>
    </a>
  </div>
</div>
