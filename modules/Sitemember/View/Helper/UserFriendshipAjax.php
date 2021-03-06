<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitemember
 * @copyright  Copyright 2014-2015 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: UserFriendshipAjax.php 2014-07-20 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitemember_View_Helper_UserFriendshipAjax extends Zend_View_Helper_Abstract
{
  public function userFriendshipAjax($user, $viewer = null, $iconOnly = false)
  {
    if( null === $viewer ) {
      $viewer = Engine_Api::_()->user()->getViewer();
    }

    if( !$viewer || !$viewer->getIdentity() || $user->isSelf($viewer) ) {
      return '';
    }

    $direction = (int) Engine_Api::_()->getApi('settings', 'core')->getSetting('user.friends.direction', 1);

    // Get data
    if( !$direction ) {
       $row = $user->membership()->getRow($viewer);
    }
    else 
        $row = $viewer->membership()->getRow($user);

    // Render

    // Check if friendship is allowed in the network
    $eligible =  (int) Engine_Api::_()->getApi('settings', 'core')->getSetting('user.friends.eligible', 2);
    if($eligible == 0){
      return '';
    }
   
    // check admin level setting if you can befriend people in your network
    else if( $eligible == 1 ) {

      $networkMembershipTable = Engine_Api::_()->getDbtable('membership', 'network');
      $networkMembershipName = $networkMembershipTable->info('name');

      $select = new Zend_Db_Select($networkMembershipTable->getAdapter());
      $select
        ->from($networkMembershipName, 'user_id')
        ->join($networkMembershipName, "`{$networkMembershipName}`.`resource_id`=`{$networkMembershipName}_2`.resource_id", null)
        ->where("`{$networkMembershipName}`.user_id = ?", $viewer->getIdentity())
        ->where("`{$networkMembershipName}_2`.user_id = ?", $user->getIdentity())
        ;

      $data = $select->query()->fetch();

      if(empty($data)){
        return '';
      }
    }
    
    if( !$direction ) {
      // one-way mode
      if( null === $row ) {
        return $this->view->htmlLink(array('route' => 'user_extended', 'controller' => 'friends', 'action' => 'add', 'user_id' => $user->user_id), (!$iconOnly ? $this->view->translate('Follow') :'' ), array(
          'class' => 'buttonlink smoothbox icon_friend_add', 'title' => $this->view->translate('Follow')
        ));
      } else if( $row->resource_approved == 0 ) {
        return $this->view->htmlLink(array('route' => 'user_extended', 'controller' => 'friends', 'action' => 'cancel', 'user_id' => $user->user_id), (!$iconOnly ? $this->view->translate('Cancel Follow Request') : ''), array(
          'class' => 'buttonlink smoothbox icon_friend_cancel', 'title' => $this->view->translate('Cancel Follow Request')
        ));
      } else {
        return $this->view->htmlLink(array('route' => 'user_extended', 'controller' => 'friends', 'action' => 'remove', 'user_id' => $user->user_id), (!$iconOnly ? $this->view->translate('Unfollow') : ''), array(
          'class' => 'buttonlink smoothbox icon_friend_remove', 'title' => $this->view->translate('Unfollow')
        ));
      }

    } else {
      // two-way mode
      if( null === $row ) {
        return $this->view->htmlLink(array('route' => 'user_extended', 'controller' => 'friends', 'action' => 'add', 'user_id' => $user->user_id), (!$iconOnly ? $this->view->translate('Add Friend') : ''), array(
          'class' => 'buttonlink smoothbox icon_friend_add', 'title' => $this->view->translate('Add Friend')
        ));
      } else if( $row->user_approved == 0 ) {
        return $this->view->htmlLink(array('route' => 'user_extended', 'controller' => 'friends', 'action' => 'cancel', 'user_id' => $user->user_id), (!$iconOnly ? $this->view->translate('Cancel Request') : ''), array(
          'class' => 'buttonlink smoothbox icon_friend_cancel', 'title' => $this->view->translate('Cancel Request')
        ));
      } else if( $row->resource_approved == 0 ) {
        return $this->view->htmlLink(array('route' => 'user_extended', 'controller' => 'friends', 'action' => 'confirm', 'user_id' => $user->user_id), (!$iconOnly ? $this->view->translate('Accept Request') : ''), array(
          'class' => 'buttonlink smoothbox icon_friend_add', 'title' => $this->view->translate('Accept Request')
        ));
      } else if( $row->active ) {
        return $this->view->htmlLink(array('route' => 'user_extended', 'controller' => 'friends', 'action' => 'remove', 'user_id' => $user->user_id), (!$iconOnly ? $this->view->translate('Remove Friend') : ''), array(
          'class' => 'buttonlink smoothbox icon_friend_remove', 'title' => $this->view->translate('Remove Friend')
        ));
      }
    }

    return '';
  }
}