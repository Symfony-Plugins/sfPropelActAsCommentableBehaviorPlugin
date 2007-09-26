<?php
/**
 * sfPropelActAsCommentableBehaviorPlugin base actions.
 * 
 * @package    plugins
 * @subpackage comment 
 * @author     Xavier Lacot <xavier@lacot.org>
 * @link       http://trac.symfony-project.com/trac/wiki/sfPropelActAsCommentableBehaviorPlugin
 */
class BasesfCommentActions extends sfActions
{
  private
    $config,
    $config_user,
    $config_anonymous;

  /**
   * Saves a comment, for an authentified user
   */
  public function executeAuthenticatedComment()
  {
    $this->getConfig();

    if ((sfContext::getInstance()->getUser()->isAuthenticated() && $this->config_user['enabled'])
         && $this->getRequest()->getMethod() == sfRequest::POST)
    {
      $object_id = $this->getRequestParameter('sf_comment_object_id');
      $object_model = $this->getRequestParameter('sf_comment_object_model');
      $comment = array('text' => $this->getRequestParameter('sf_comment'));
      $object = sfPropelActAsCommentableToolkit::retrieveCommentableObject($object_model, $object_id);
      $id_method = $this->config_user['id_method'];

      $comment['author_id'] = sfContext::getInstance()->getUser()->$id_method();

      $object->addComment($comment);
      $this->object = $object;

      if (!$this->getContext()->getRequest()->isXmlHttpRequest())
      {
        $this->redirect($this->getRequestParameter('sf_comment_referer'));
      }
    }

    $this->setTemplate('comment');
  }

  /**
   * Saves a comment, for a non authentified user
   */
  public function executeAnonymousComment()
  {
    $this->getConfig();

    if ($this->config_anonymous['enabled'] && $this->getRequest()->getMethod() == sfRequest::POST)
    {
      $object_id = $this->getRequestParameter('sf_comment_object_id');
      $object_model = $this->getRequestParameter('sf_comment_object_model');
      $comment = array('text'         => $this->getRequestParameter('sf_comment'),
                       'author_name'  => $this->getRequestParameter('sf_comment_name'),
                       'author_email' => $this->getRequestParameter('sf_comment_email'));
      $object = sfPropelActAsCommentableToolkit::retrieveCommentableObject($object_model, $object_id);
      $object->addComment($comment);
      $this->object = $object;

      if (!$this->getContext()->getRequest()->isXmlHttpRequest())
      {
        $this->redirect($this->getRequestParameter('sf_comment_referer'));
      }
    }

    $this->setTemplate('comment');
  }

  /**
   * Displays the comment form
   */
  public function executeCommentForm()
  {
    $object_id = $this->getRequestParameter('sf_comment_object_id');
    $object_model = $this->getRequestParameter('sf_comment_object_model');

    $this->object = sfPropelActAsCommentableToolkit::retrieveCommentableObject($object_model, $object_id);
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
                    'use_cryptographp' => false,
                    'use_ajax'         => false);

    $this->config = sfConfig::get('app_sfPropelActAsCommentableBehaviorPlugin', $config);
    $this->config_anonymous = sfConfig::get('app_sfPropelActAsCommentableBehaviorPlugin_anonymous', $config_anonymous);
    $this->config_user = sfConfig::get('app_sfPropelActAsCommentableBehaviorPlugin_user', $config_user);
  }

  public function handleErrorAnonymousComment()
  {
    $this->handleErrorComment();
  }

  public function handleErrorAuthentifiedComment()
  {
    $this->handleErrorComment();
  }

  private function handleErrorComment()
  {
    $params = $this->getContext()->getController()->convertUrlStringToParameters($this->getRequestParameter('sf_comment_referer'));

    foreach ($params[1] as $param => $value)
    {
      $this->getRequest()->setParameter($param, $value);
    }

    if ($this->getContext()->getRequest()->isXmlHttpRequest())
    {
      $this->getResponse()->setStatusCode(500);
      $this->forward('sfComment', 'commentForm');
    }
    else
    {
      $this->forward($params[1]['module'], $params[1]['action']);
    }
  }
}