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
  public function executeAuthor()
  {
    if (isset($this->author_id))
    {
      $this->getConfig();
      $class = $this->config_user['class'];
      $toString = $this->config_user['toString'];
      $peer = sprintf('%sPeer', $class);
      $author = call_user_func(array($peer, 'retrieveByPk'), $this->author_id);
      $this->author = (!is_null($author)) ? $author->$toString() : '';
    }
    else
    {
      $this->author = $this->author_name;
    }
  }


  public function executeCommentForm()
  {
    $this->getConfig();

    if ($this->config['css'])
    {
      sfContext::getInstance()->getResponse()->addStylesheet('/sfPropelActAsCommentableBehaviorPlugin/css/sf_comment');
    }

    if ($this->object instanceof sfOutputEscaperObjectDecorator)
    {
      $object = $this->object->getRawValue();
    }
    else
    {
      $object = $this->object;
    }

    $this->object_model = get_class($object);
    $this->object_id = $object->getPrimaryKey();
    $this->token = sfPropelActAsCommentableToolkit::addTokenToSession($this->object_model, $this->object_id);

    if ($this->getUser()->isAuthenticated() && $this->config_user['enabled'])
    {
      $this->action = 'authenticatedComment';
      $this->config_used = $this->config_user;
    }
    else
    {
      $this->action = 'anonymousComment';
      $this->config_used = $this->config_anonymous;
    }
  }

  public function executeCommentList()
  {
    $object = $this->object;
    $order = $this->order;
    $namespace = $this->namespace;
    $limit = $this->limit;

    if (!$order)
    {
      $order = 'asc';
    }

    if (!$namespace)
    {
      $namespace = null;
    }

    if (!$limit)
    {
      $criteria = null;
    }
    else
    {
      $criteria = new Criteria();
      $criteria->setLimit($limit);
    }

    $this->comments = $object->getComments(array('order' => $order, 'namespace' => $namespace), $criteria);
  }

  public function executeGravatar()
  {
    if (isset($this->author_id))
    {
      $this->getConfig();
      $class = $this->config_user['class'];
      $toString = $this->config_user['toString'];
      $toEmail = $this->config_user['toEmail'];
      $peer = sprintf('%sPeer', $class);
      $author = call_user_func(array($peer, 'retrieveByPk'), $this->author_id);
      $this->author_name = (!is_null($author)) ? $author->$toString() : '';
      $this->author_email = (!is_null($author)) ? $author->$toEmail() : '';
    }
  }

  protected function getConfig()
  {
    $config_anonymous = array('enabled' => true,
                              'layout'  => array('name' => 'required',
                                                 'email' => 'required',
                                                 'title' => 'optional',
                                                 'website' => 'optional',
                                                 'comment' => 'required'),
                              'name'    => 'Anonymous User');
    $config_user = array('enabled'   => true,
                         'layout'    => array('title' => 'optional',
                                              'comment' => 'required'),
                         'table'     => 'sf_guard_user',
                         'id'        => 'id',
                         'class'     => 'sfGuardUser',
                         'id_method' => 'getUserId',
                         'toString'  => 'toString',
                         'save_name' => false);
    $config = array('user'             => $config_user,
                    'anonymous'        => $config_anonymous,
                    'use_ajax'         => sfConfig::get('app_sfPropelActAsCommentableBehaviorPlugin_use_ajax', false),
                    'css'              => sfConfig::get('app_sfPropelActAsCommentableBehaviorPlugin_css', true),
                    'namespaces'       => array());

    $this->config = $config;
    $this->config_anonymous = sfConfig::get('app_sfPropelActAsCommentableBehaviorPlugin_anonymous', $config_anonymous);
    $this->config_user = sfConfig::get('app_sfPropelActAsCommentableBehaviorPlugin_user', $config_user);
  }
}