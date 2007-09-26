<?php
/**
 * sfPropelActAsCommentableBehaviorPlugin base components.
 * 
 * @package    plugins
 * @subpackage comment 
 * @author     Xavier Lacot <xavier@lacot.org>
 * @link       http://trac.symfony-project.com/trac/wiki/sfPropelActAsCommentableBehaviorPlugin
 */
class BasesfCommentComponents extends sfComponents
{
  public function executeCommentForm()
  {
    sfContext::getInstance()->getResponse()->addStylesheet('/sfPropelActAsCommentableBehaviorPlugin/css/sf_comment');
    $this->getConfig();
    $object = $this->object;
    $this->object_id = $object->getPrimaryKey();
    $this->object_model = get_class($object);
    
    if ($this->getUser()->isAuthenticated() && $this->config_user['enabled'])
    {
      $this->action = 'authentified_comment';
      $this->config_used = $this->config_user;
    }
    else
    {
      $this->action = 'anonymous_comment';
      $this->config_used = $this->config_anonymous;
    }
  }

  public function executeCommentList()
  {
    $object = $this->object;
    $order = $this->order;

    if (!$order)
    {
      $order = 'asc';
    }

    $this->comments = $object->getComments(array('order' => $order));
  }

  private function getConfig()
  {
    $config_anonymous = array('enabled' => true, 
                              'layout'  => array('name' => 'required', 
                                                 'email' => 'required', 
                                                 'title' => 'optionnal', 
                                                 'comment' => 'required'), 
                              'name'    => 'Anonymous User');
    $config_user = array('enabled'   => true, 
                         'layout'    => array('title' => 'optionnal', 
                                              'comment' => 'required'), 
                         'table'     => 'sf_guard_user', 
                         'id'        => 'id', 
                         'class'     => 'sfGuardUser', 
                         'id_method' => 'getUserId', 
                         'toString'  => 'toString', 
                         'save_name' => false);
    $config = array('user'             => $config_user,
                    'anonymous'        => $config_anonymous,
                    'use_ajax'         => sfConfig::get('app_sfPropelActAsCommentableBehaviorPlugin_use_ajax', false));

    $this->config = $config;
    $this->config_anonymous = sfConfig::get('app_sfPropelActAsCommentableBehaviorPlugin_anonymous', $config_anonymous);
    $this->config_user = sfConfig::get('app_sfPropelActAsCommentableBehaviorPlugin_user', $config_user);
  }
}