<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Music
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: Controller.php 9747 2012-07-26 02:08:08Z john $
 * @author     Steve
 */

/**
 * @category   Application_Extensions
 * @package    Music
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */
class Music_Widget_ProfilePlayerController extends Engine_Content_Widget_Abstract
{
  public function indexAction()
  {
    // Don't render this if not authorized
    $viewer = Engine_Api::_()->user()->getViewer();
    if( !Engine_Api::_()->core()->hasSubject() ) {
      return $this->setNoRender();
    }

    // Get subject and check auth
    $subject = Engine_Api::_()->core()->getSubject();
    if( !$subject->authorization()->isAllowed($viewer, 'view') ) {
      return $this->setNoRender();
    }

    // Get playlist
    $select   = Engine_Api::_()->getApi('core', 'music')->getPlaylistSelect(array('user'=>$subject->getIdentity()))->where('profile = 1');
    $playlist = Engine_Api::_()->getDbtable('playlists', 'music')->fetchRow($select);

    // No playlist registered
    if( !$playlist ) {
      return $this->setNoRender();
    }

    $this->getElement()->setTitle($playlist->getTitle());

    // Assign
    $this->view->playlist = $playlist;
  }
}
