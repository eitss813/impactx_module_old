<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Forum
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: Forums.php 9747 2012-07-26 02:08:08Z john $
 * @author     John
 */

/**
 * @category   Application_Extensions
 * @package    Forum
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */
class Forum_Model_DbTable_Forums extends Engine_Db_Table
{
  protected $_rowClass = 'Forum_Model_Forum';

  public function getChildrenSelectOfForumCategory($category)
  {
    return $this->select()->where('category_id = ?', $category->category_id);
  }
}
