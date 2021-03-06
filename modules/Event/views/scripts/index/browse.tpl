<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Event
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: browse.tpl 9987 2013-03-20 00:58:10Z john $
 * @author     John Boehr <john@socialengine.com>
 */
?>

<?php if( count($this->paginator) > 0 ): ?>
  <?php $tabClass = "upcoming_events"; ?>
  <?php if( $this->filter == "past" ): ?>
    <?php $tabClass = "past_events"; ?>
  <?php endif; ?>
  <ul class='events_browse <?php echo $tabClass ?> grid_wrapper'>
    <?php foreach( $this->paginator as $event ): ?>
      <li>
        <div class="events_photo">
          <?php $startTime = $this->locale()->toDateTime($event->starttime); ?>
          <?php $endTime = $this->locale()->toDateTime($event->endtime); ?>
          <?php if( $this->filter == "past" ): ?>
            <?php $imgTitle = array('title' => "Ends on : $endTime");?>
          <?php else: ?>
            <?php $imgTitle = array('title' => "Starts on : $startTime");?>
          <?php endif; ?>
          <?php echo $this->htmlLink($event->getHref(), $this->itemBackgroundPhoto($event, 'thumb.profile'), $imgTitle) ?>
          <div class="info_stat_grid">
            <?php if( $event->like_count > 0 ) :?>
              <span>
                <i class="fa fa-thumbs-up"></i>
                <?php echo $this->locale()->toNumber($event->like_count) ?>
              </span>
            <?php endif; ?>
            <?php if( $event->comment_count > 0 ) :?>
              <span>
                <i class="fa fa-comment"></i>
                <?php echo $this->locale()->toNumber($event->comment_count) ?>
              </span>
            <?php endif; ?>
            <?php if( $event->view_count > 0 ) :?>
              <span class="views_event">
                <i class="fa fa-eye"></i>
                <?php echo $this->locale()->toNumber($event->view_count) ?>
              </span>
            <?php endif; ?>
          </div>
          <div class="events_held_info">
            <i class="fa fa-calendar"></i>
            <span class="event_start_date"><?php echo $startTime; ?></span>
            <span class="event_end_date"><?php echo $endTime; ?></span>
          </div>
        </div>
        <div class="events_options">
        </div>
        <div class="events_info">
          <div class="events_title">
            <h3><?php echo $this->htmlLink($event->getHref(), $event->getTitle()) ?></h3>
          </div>
          <div class="events_members">
            <?php echo $this->translate(array('%s guest', '%s guests', $event->membership()->getMemberCount()),$this->locale()->toNumber($event->membership()->getMemberCount())) ?>
            <?php echo $this->translate('led by') ?>
            <?php echo $this->htmlLink($event->getOwner()->getHref(), $event->getOwner()->getTitle()) ?>
          </div>
          <?php if( $event->location ):?>
            <div class="events_location">
              <i class="fa fa-map-marker"></i>
              <span><?php echo $event->location ?></span>
            </div>
          <?php endif; ?>
        </div>
        <p class="half_border_bottom"></p>
      </li>
    <?php endforeach; ?>
  </ul>

  <?php if( $this->paginator->count() > 1 ): ?>
    <?php echo $this->paginationControl($this->paginator, null, null, array(
      'query' => $this->formValues,
    )); ?>
  <?php endif; ?>


  <?php elseif( preg_match("/category_id=/", $_SERVER['REQUEST_URI'] )): ?>
  <div class="tip">
    <span>
    <?php echo $this->translate('Nobody has created an event with that criteria.');?>
    <?php if( $this->canCreate ): ?>
      <?php echo $this->translate('Be the first to %1$screate%2$s one!', '<a href="'.$this->url(array('action'=>'create'), 'event_general').'">', '</a>'); ?>
    <?php endif; ?>
    </span>
  </div>   
  
  
<?php else: ?>
  <div class="tip">
    <span>
    <?php if( $this->filter != "past" ): ?>
      <?php echo $this->translate('Nobody has created an event yet.') ?>
      <?php if( $this->canCreate ): ?>
        <?php echo $this->translate('Be the first to %1$screate%2$s one!', '<a href="'.$this->url(array('action'=>'create'), 'event_general').'">', '</a>'); ?>
      <?php endif; ?>
    <?php else: ?>
      <?php echo $this->translate('There are no past events yet.') ?>
    <?php endif; ?>
    </span>
  </div>

<?php endif; ?>


<script type="text/javascript">
  $$('.core_main_event').getParent().addClass('active');
</script>
