<?php
/**
 * sfPropelActAsCommentableBehaviorPlugin actions. Feel free to override this
 * class in your dedicated app module.
 *
 * @package    plugins
 * @subpackage comment
 * @author     Xavier Lacot <xavier@lacot.org>
 * @link       http://trac.symfony-project.com/trac/wiki/sfPropelActAsCommentableBehaviorPlugin
 */
class sfCommentAdminActions extends autoSfCommentAdminActions
{
  protected function updatesfCommentFromRequest()
  {
    parent::updatesfCommentFromRequest();
    $sf_comment = $this->getRequestParameter('sf_comment');

    if (isset($sf_comment['author_id']) && $sf_comment['author_id'] == '')
    {
      $this->sf_comment->setAuthorId(null);
    }
  }
}
