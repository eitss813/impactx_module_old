<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Fields
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: Field.php 9747 2012-07-26 02:08:08Z john $
 * @author     John
 */

/**
 * @category   Application_Core
 * @package    Fields
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @author     John
 */
class Yndynamicform_Form_Admin_Field_StarRating extends Engine_Form
{
  protected $_option;

  public function getOption()
  {
    return $this -> _option;
  }

  public function setOption($option)
  {
    $this -> _option = $option;
  }

  public function init()
  {
    $this->setMethod('POST')
        ->setAttrib('class', 'global_form_popup')
        ->setTitle('Edit Form Field');

    // Add type
    $types = Engine_Api::_()->yndynamicform()->getFieldInfo('fields');
    $availableTypes = array();
    foreach( $types as $fieldType => $info ) {
      $availableTypes[$fieldType] = $info['label'];
    }

    $this->addElement('Select', 'type', array(
        'required' => true,
        'allowEmpty' => false,
        'multiOptions' => $availableTypes,
        'style' => 'display:none',
        'onchange' => 'var form = this.getParent("form"); form.method = "get"; form.submit();',
    ));

    $this->type->removeDecorator('label');

    // Add label
    $this->addElement('Text', 'label', array(
        'label' => 'Field Label',
        'required' => true,
        'allowEmpty' => false,
    ));

    // Add description
    $this->addElement('Textarea', 'description', array(
        'label' => 'Description',
        'rows' => 6,
    ));

    $selectedColor = '#888800';
    $unselectedColor = '#888888';

    $this->addElement('Text', 'selected_star_color', array(
        'label' => 'Color of selected stars',
        'class' => 'yndform_color',
        'value' => $selectedColor,
        'disabled' => 'true',
    ));
    $this->selected_star_color->addDecorator('HtmlTag2', array('tag' => 'div', 'id' => 'selected_star_color', 'class' => 'form-wrapper yndform_color_wrapper'));
    
    $this->addElement('Heading', 'selected_star_color_box', array(
        'class' => 'yndform_color_picker',
        'value' => '<input class="" value="'.$this->selected_star_color->getValue().'" type="color" id="selected_star_color_picker" name="selected_star_color"/>'
    ));
    $this->selected_star_color_box->removeDecorator('label');

    $this->addElement('Text', 'unselected_star_color', array(
        'label' => 'Color of unselected stars',
        'class' => 'yndform_color',
        'value' => $unselectedColor,
        'disabled' => 'true',
    ));
    $this->unselected_star_color->addDecorator('HtmlTag2', array('tag' => 'div', 'id' => 'unselected_star_color', 'class' => 'form-wrapper yndform_color_wrapper'));


    $this->addElement('Heading', 'unselected_star_color_box', array(
        'class' => 'yndform_color_picker',
        'value' => '<input class="" value="'.$this->unselected_star_color->getValue().'" type="color" id="unselected_star_color_picker" name="unselected_star_color"/>'
    ));
    $this->unselected_star_color_box->removeDecorator('label');



    // Add Css
    $this->addElement('Text', 'style', array(
        'label' => 'Inline CSS',
    ));

    $this->addElement('Checkbox', 'conditional_enabled', array(
        'label' => 'Enable conditional logic',
        'description' => 'YNDYNAMICFORM_FORM_ADMIN_FIELD_CONDITIONAL_DESCRIPTION',
        'onchange' => 'yndformToggleConditionalLogic(this)',
        'value' => 0,
    ));

    $this->conditional_enabled->getDecorator('Description')->setOption('placement', 'append');

    $this->addElement('hidden', 'conditional_logic', array(
        'order' => 101,
    ));

    //conditional logic
    $this->addElement('dummy', 'conditional_logic_tpl', array(
        'decorators' => array( array(
            'ViewScript',
            array(
                'viewScript' => '_conditional-logic.tpl',
                'class' => 'form_element',
            )
        )),
    ));

    // Display
    $this->addElement('hidden', 'display', array(
        'value' => 1,
        'order' => 100,
    ));

    $this->addElement('Checkbox', 'show_registered', array(
        'label' => 'Show this field to Registered User',
        'description' => 'Privacy',
        'value' => 1,
    ));

    $this->addElement('Checkbox', 'show_guest', array(
        'label' => 'Show this field to Guest',
        'value' => 1,
    ));
    $this->show_guest->removeDecorator('description');

    // Add submit
    $this->addElement('Button', 'execute', array(
        'label' => 'Save',
        'type' => 'submit',
        'decorators' => array(
            'ViewHelper',
        ),
        'order' => 10000,
        'ignore' => true,
    ));

    // Add cancel
    $this->addElement('Cancel', 'cancel', array(
        'label' => 'cancel',
        'link' => true,
        'onclick' => 'parent.Smoothbox.close();',
        'prependText' => ' or ',
        'decorators' => array(
            'ViewHelper',
        ),
        'order' => 10001,
        'ignore' => true,
    ));

    $this->addDisplayGroup(array('execute', 'cancel'), 'buttons', array(
        'order' => 10002,
    ));
  }
}