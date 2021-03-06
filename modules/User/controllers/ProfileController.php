<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    User
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: ProfileController.php 9747 2012-07-26 02:08:08Z john $
 * @author     John
 */

/**
 * @category   Application_Core
 * @package    User
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */
class User_ProfileController extends Core_Controller_Action_Standard
{
  public function init()
  {
    // @todo this may not work with some of the content stuff in here, double-check

    // open profile page only if authenticated
    $uri = $_SERVER['REQUEST_URI'];

    // set auth to profile-activity feed
    if (
      (strpos($uri, '/network/profile/') !== false)  ||
      (strpos($uri, '/net/profile/') !== false)  ||
      (strpos($uri, '/profile/') !== false) ||
      (strpos($uri, '/network/pages/activities') !== false) ||
      (strpos($uri, '/net/pages/activities') !== false) ||
      (strpos($uri, '/pages/activities') !== false)
    ) {
        if( !$this->_helper->requireUser()->isValid() ){
            return;
        }
    }

    $subject = null;
    if( !Engine_Api::_()->core()->hasSubject() )
    {
      $id = $this->_getParam('id');

      // use viewer ID if not specified
      //if( is_null($id) )
      //  $id = Engine_Api::_()->user()->getViewer()->getIdentity();

      if( null !== $id )
      {
        $subject = Engine_Api::_()->user()->getUser($id);
        if( $subject->getIdentity() )
        {
          Engine_Api::_()->core()->setSubject($subject);
        }
      }
    }

    $viewer = Engine_Api::_()->user()->getViewer();
    $this->_helper->requireSubject('user');
    $this->_helper->requireAuth()
    //  ->setNoForward()                         // for showing image and name irrespective of privacy
      ->setAuthParams($subject, $viewer, 'view')
      ->isValid();
  }
  
  public function indexAction()
  {
    $subject = Engine_Api::_()->core()->getSubject();
    $viewer = Engine_Api::_()->user()->getViewer();

    // check public settings
    $require_check = Engine_Api::_()->getApi('settings', 'core')->core_general_profile;
    if( !$require_check && !$this->_helper->requireUser()->isValid() ) {
      return;
    }

    // Check enabled
    if( !$subject->enabled && !$viewer->isAdmin() ) {
      return $this->_forward('requireauth', 'error', 'core');
    }

    // Check block
    if( $viewer->isBlockedBy($subject) && !$viewer->isAdmin() ) {
      return $this->_forward('requireauth', 'error', 'core');
    }

    // Increment view count
    if( !$subject->isSelf($viewer) ) {
      $subject->view_count++;
      $subject->save();
    }

    
    // Check to see if profile styles is allowed
    $style_perm = Engine_Api::_()->getDbtable('permissions', 'authorization')->getAllowed('user', $subject->level_id, 'style');
    if($style_perm){
      // Get styles
      $table = Engine_Api::_()->getDbtable('styles', 'core');
      $select = $table->select()
        ->where('type = ?', $subject->getType())
        ->where('id = ?', $subject->getIdentity())
        ->limit();

      $row = $table->fetchRow($select);
      if( null !== $row && !empty($row->style) )
      {
        $this->view->headStyle()->appendStyle($row->style);
      }
    }

    // Render
    $this->_helper->content
        ->setNoRender()
        ->setEnabled()
        ;
  }
  
}
