<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Core
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: Controller.php 9747 2012-07-26 02:08:08Z john $
 * @author     John
 */

/**
 * @category   Application_Core
 * @package    Core
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */
class Core_Widget_ContainerTabsController extends Engine_Content_Widget_Abstract
{
    public function indexAction()
    {
        // Set up element
        $element = $this->getElement();
        $element->clearDecorators()
            //->addDecorator('Children', array('placement' => 'APPEND'))
            ->addDecorator('Container');

        // If there is action_id make the activity_feed tab active
        $action_id = (int) Zend_Controller_Front::getInstance()->getRequest()->getParam('action_id');
        $tab = $this->_getParam('tab');

        if($tab == 'user.forms'){
            $activeTab = 'user.forms';
        }else{
            $activeTab = $action_id ? 'activity.feed' : $this->_getParam('tab');
        }

        // if it is from friends tab
        $this->view->url_link =  $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $finalParam = null;
        $finalelement = $element->getElements() ;
        $url_linkArr = explode("?",$actual_link);
        if(count($url_linkArr)== 2) {
            $final_param = explode("=",$url_linkArr[1]);
            $finalParam= $final_param[1];
            $temp = $finalelement[0];
            $finalelement[0] = $finalelement[1];
            $finalelement[1] = $temp;
        }


        if( empty($activeTab) ) {
            $activeTab = Zend_Controller_Front::getInstance()->getRequest()->getParam('tab');
        }

        // Iterate over children
        $tabs = array();
        $childrenContent = '';
        foreach( $finalelement as $key => $child ) {
            // First tab is active if none supplied
            if( null === $activeTab ) {
                $activeTab = $child->getIdentity();
            }
            // If not active, set to display none
            if( $child->getIdentity() != $activeTab && $child->getName() != $activeTab) {
                $child->getDecorator('Container')->setParam('style', 'display:none;');
            }
            // Set specific class name
            $child_class = $child->getDecorator('Container')->getParam('class');
            $child->getDecorator('Container')->setParam('class', $child_class . ' tab_'.$child->getIdentity());
            $child->getDecorator('Container')->setParam('id', 'menu_content_container_'.$key);

            // Remove title decorator
            //$child->removeDecorator('Title');
            // Render to check if it actually renders or not
            $childrenContent .= $child->render() . PHP_EOL;
            // Get title and childcount
            $title = $child->getTitle();
            $childCount = null;
            if( method_exists($child, 'getWidget') && method_exists($child->getWidget(), 'getChildCount') ) {
                $childCount = $child->getWidget()->getChildCount();
            }
            if( !$title ) $title = $child->getName();
            // If it does render, add it to the tab list
            if( !$child->getNoRender() ) {
                $tabs[] = array(
                    'id' => $child->getIdentity(),
                    'name' => $child->getName(),
                    'containerClass' => $child->getDecorator('Container')->getClass(),
                    'title' => $title,
                    'childCount' => $childCount
                );
            }
        }

        // Don't bother rendering if there are no tabs to show
        if( empty($tabs) ) {
            return $this->setNoRender();
        }

        $this->view->activeTab = $activeTab;
        $this->view->tabs = $tabs;
        $this->view->childrenContent = $childrenContent;
        $this->view->max =  $this->_getParam('max');
    }
}