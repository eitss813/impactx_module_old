<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Core
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: index.tpl 9747 2012-07-26 02:08:08Z john $
 * @author     John
 */
?>

<ul>
  <li>
    <span><?php echo $this->locale()->toNumber($this->member_count); ?></span>
    <div><?php echo $this->translate(array('member', 'members', $this->member_count)) ?></div>
  </li>
  <?php if ($this->friend_count > 0): ?>
    <li>
      <span><?php echo $this->locale()->toNumber($this->friend_count) ?></span>
      <div><?php echo $this->translate(array('friendship', 'friendships', $this->friend_count)) ?></div>
    </li>
  <?php endif; ?>
  <?php if ($this->post_count > 0): ?>
    <li>
      <span><?php echo $this->locale()->toNumber($this->post_count) ?></span>
      <div><?php echo $this->translate(array('post', 'posts', $this->post_count)) ?></div>
    </li>
  <?php endif; ?>
  <?php if ($this->comment_count > 0): ?>
    <li>
      <span><?php echo $this->locale()->toNumber($this->comment_count) ?></span>
      <div><?php echo $this->translate(array('comment', 'comments', $this->comment_count)) ?></div>
    </li>
  <?php endif; ?>

  <?php if (is_array($this->hooked_stats) && !empty($this->hooked_stats)): ?>
  <?php foreach ($this->hooked_stats as $key => $value): ?>
    <?php if ($value > 0): ?>
      <li>
        <span><?php echo $this->locale()->toNumber($value) ?></span>
        <div><?php echo $this->translate(array($key, $key, $value)) ?></div>
      </li>
    <?php endif; ?>
  <?php endforeach; endif; ?>

</ul>
<style>
  .generic_layout_container.layout_core_statistics {
    display: none;
  }
  .generic_layout_container.layout_sitemember_profile_photo_sitemembers {
    display: none;
  }
</style>