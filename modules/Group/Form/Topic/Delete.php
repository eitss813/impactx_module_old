<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Group
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: Delete.php 9747 2012-07-26 02:08:08Z john $
 * @author     John
 */

/**
 * @category   Application_Extensions
 * @package    Group
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */
class Group_Form_Topic_Delete extends Engine_Form
{
  public function init()
  {
    $this
      ->setTitle('Delete Topic')
      ->setDescription('Are you sure you want to delete this topic?')
      ;
    
    $this->addElement('Button', 'submit', array(
      'label' => 'Delete Topic',
      'ignore' => true,
      'type' => 'submit',
      'decorators' => array(
        'ViewHelper',
      ),
    ));

    $this->addElement('Cancel', 'cancel', array(
      'label' => 'cancel',
      'prependText' => ' or ',
      'type' => 'link',
      'link' => true,
      'onclick' => 'parent.Smoothbox.close();',
      'decorators' => array(
        'ViewHelper',
      ),
    ));

    $this->addDisplayGroup(array('submit', 'cancel'), 'buttons');
  }
}
