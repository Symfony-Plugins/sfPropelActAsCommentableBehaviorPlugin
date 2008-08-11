<?php use_helper('I18N'); ?>
<?php if (!sfContext::getInstance()->getRequest()->isXmlHttpRequest()): ?>
  <h2 class="sf_comments_title"><?php echo __('Comments') ?></h2>
  <div id="sf_comment_list">
<?php endif; ?>
  <?php if (count($comments) > 0): ?>
    <?php foreach ($comments as $comment): ?>
      <?php include_partial('sfComment/commentView', array('comment' => $comment)) ?>
    <?php endforeach; ?>
  <?php else: ?>
    <p>
      <?php echo __('There is no comment for the moment.') ?>
      <?php if (sfConfig::get('app_sfPropelActAsCommentableBehaviorPlugin_hide_form', true)): ?>
        <?php echo link_to_function(__('Add a new comment'), visual_effect('appear', 'sf_comment_form')) ?>
      <?php endif; ?>
    </p>
  <?php endif; ?>
<?php if (!sfContext::getInstance()->getRequest()->isXmlHttpRequest()): ?>
  </div>
<?php endif; ?>