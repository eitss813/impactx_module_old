<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Classified
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: index.tpl 9747 2012-07-26 02:08:08Z john $
 * @author     John
 */
?>

<div class="generic_list_wrapper">
    <ul class="generic_list_widget">
      <?php foreach( $this->paginator as $item ): ?>
        <li>
          <div class="photo">
            <?php echo $this->htmlLink($item->getHref(), $this->itemPhoto($item, 'thumb.icon'), array('class' => 'thumb')) ?>
          </div>
          <div class="info">
            <div class="title">
              <?php echo $this->htmlLink($item->getHref(), $item->getTitle()) ?>
            </div>
            <div class="stats">
              <?php echo $this->timestamp(strtotime($item->creation_date)) ?>
              - <?php echo $this->translate('hosted by %1$s',
                  $this->htmlLink($item->getOwner()->getHref(), $item->getOwner()->getTitle())) ?>
              <?php if( $this->popularType == 'view' ): ?>
                - <?php echo $this->translate(array('%s view', '%s views', $item->view_count), $this->locale()->toNumber($item->view_count)) ?>
              <?php else /*if( $this->popularType == 'member' )*/: ?>
                - <?php echo $this->translate(array('%s member', '%s members', $item->member_count), $this->locale()->toNumber($item->member_count)) ?>
              <?php endif; ?>
            </div>
          </div>
          <?php
            $desc = trim($this->string()->truncate($this->string()->stripTags($item->description), 300));
            if( !empty($desc) ): ?>
            <div class="description">
              <?php echo $desc ?>
            </div>
          <?php endif; ?>
        </li>
      <?php endforeach; ?>
    </ul>
    
    <?php if( $this->paginator->getPages()->pageCount > 1 ): ?>
      <?php echo $this->partial('_widgetLinks.tpl', 'core', array(
        'url' => $this->url(array(), 'event_general', true),
        'param' => array('order' => 'member_count+DESC')
        )); ?>
    <?php endif; ?>
</div>
