<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Album
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: Edit.php 10117 2013-11-15 19:37:20Z andres $
 * @author     John Boehr <j@webligo.com>
 */

/**
 * @category   Application_Extensions
 * @package    Album
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */
class Album_Form_Photo_Edit extends Engine_Form
{
  public function init()
  {
    $this
      ->setTitle('Edit Photo')
      ->setMethod('POST')
      ->setAction($_SERVER['REQUEST_URI'])
      ->setAttrib('class', 'global_form_popup')
      ;
      
    $this->addElement('Text', 'title', array(
      'label' => 'Title',
      'filters' => array(
        new Engine_Filter_Censor(),
        new Engine_Filter_HtmlSpecialChars(),
      ),
    ));

    $this->addElement('Text', 'tags', array(
      'label'=>'Tags (Keywords)',
      'autocomplete' => 'off',
      'filters' => array(
        'StripTags',
        new Engine_Filter_Censor(),
      ),
    ));

    $this->addElement('Textarea', 'description', array(
      'label' => 'Caption',
      'rows' => 2,
      'cols' => 120,
      'filters' => array(
        'StripTags',
        new Engine_Filter_Censor(),
      ),
    ));

    $this->addElement('Button', 'execute', array(
      'label' => 'Save Changes',
      'ignore' => true,
      'decorators' => array('ViewHelper'),
      'type' => 'submit'
    ));

    $this->addElement('Cancel', 'cancel', array(
      'prependText' => ' or ',
      'label' => 'cancel',
      'link' => true,
      'href' => '',
      'onclick' => 'parent.Smoothbox.close();',
      'decorators' => array(
        'ViewHelper'
      ),
    ));

    $this->addDisplayGroup(array(
      'execute',
      'cancel'
    ), 'buttons');
  }
}
