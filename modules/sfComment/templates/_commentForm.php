<?php use_helper('Form'); ?>
<?php use_helper('Validation'); ?>
<?php use_helper('Javascript'); ?>
<?php use_helper('I18N'); ?>
<?php use_helper('Date'); ?>

<?php if ( ($sf_user->isAuthenticated() && $config_user['enabled'])
          || $config_anonymous['enabled']): ?>
  <?php
  $options = array('class' => 'sf_comment_form',
                   'id'    => 'sf_comment_form');

  if (sfConfig::get('app_sfPropelActAsCommentableBehaviorPlugin_hide_form', true)
      &&($object->getNbComments() == 0))
  {
    $options['style'] = 'display: none';
  }

  echo form_tag('sfComment/'.$action, $options);
  ?>
    <fieldset>
      <?php if ($sf_request->hasError('unauthorized')): ?>
        <div class="sf_comment_form_error">
          <?php echo $sf_request->getError('unauthorized') ?>
        </div>
      <?php endif; ?>

      <?php if (isset($config_used['layout']['name'])): ?>
        <div class="<?php echo $config_used['layout']['name']; ?>">
          <label for="sf_comment_name"><?php echo __('Name') ?></label>
          <?php echo form_error('sf_comment_name') ?>
          <?php echo input_tag('sf_comment_name') ?>
        </div>
      <?php endif; ?>

      <?php if (isset($config_used['layout']['email'])): ?>
        <div class="<?php echo $config_used['layout']['email']; ?>">
          <label for="sf_comment_email"><?php echo __('Email') ?></label>
          <?php echo form_error('sf_comment_email') ?>
          <?php echo input_tag('sf_comment_email') ?>
        </div>
      <?php endif; ?>

      <?php if (isset($config_used['layout']['website'])): ?>
        <div class="<?php echo $config_used['layout']['website']; ?>">
          <label for="sf_comment_email"><?php echo __('Website') ?></label>
          <?php echo form_error('sf_comment_website') ?>
          <?php echo input_tag('sf_comment_website') ?>
        </div>
      <?php endif; ?>

      <?php if (isset($config_used['layout']['title'])): ?>
        <div class="<?php echo $config_used['layout']['title']; ?>">
          <label for="sf_comment_title"><?php echo __('Title') ?></label>
          <?php echo form_error('sf_comment_title') ?>
          <?php echo input_tag('sf_comment_title') ?>
        </div>
      <?php endif; ?>

      <div class="required">
        <label for="sf_comment"><?php echo __('Write a comment') ?></label>
        <?php echo form_error('sf_comment') ?>
        <?php echo textarea_tag('sf_comment', '', array('cols' => 40, 'rows' => 8)) ?>
      </div>

      <?php
      $allowed_html_tags = sfConfig::get('app_sfPropelActAsCommentableBehaviorPlugin_allowed_tags', array());
      sort($allowed_html_tags);

      if (count($allowed_html_tags) > 0):
      ?>
        <div>
          <?php echo __('Allowed HTML tags are: %1%', array('%1%' => htmlspecialchars(implode(', ', $allowed_html_tags)))) ?>
        </div>
      <?php endif; ?>

      <?php
      switch (sfConfig::get('sf_path_info_array'))
      {
        case 'SERVER':
          $pathInfoArray =& $_SERVER;
          break;
        case 'ENV':
        default:
          $pathInfoArray =& $_ENV;
      }

      $referer = sfRouting::getInstance()->getCurrentInternalUri();

      if ($pathInfoArray['QUERY_STRING'] != '')
      {
        $referer .= '?'.$pathInfoArray['QUERY_STRING'];
      }
      ?>
      <?php echo input_hidden_tag('sf_comment_referer', sfContext::getInstance()->getRequest()->getParameter('sf_comment_referer', $referer)) ?>
      <?php echo input_hidden_tag('sf_comment_object_token', $token) ?>

      <?php if (isset($namespace) && ($namespace != null)): ?>
        <?php echo input_hidden_tag('sf_comment_namespace', $namespace) ?>
      <?php endif; ?>

      <?php if ($config['use_ajax']): ?>
        <?php if_javascript(); ?>
          <div id="sf_comment_ajax_indicator" style="display: none">&nbsp;</div>
          <?php
          echo submit_to_remote('sf_comment_ajax_submit',
                               __('Post this comment'),
                               array('update'   => array('success' => 'sf_comment_list', 'failure' => 'sf_comment_form'),
                                     'url'      => 'sfComment/'.$action,
                                     'loading'  => "Element.show('sf_comment_ajax_indicator')",
                                     'success'  => "Element.hide('sf_comment_ajax_indicator');Element.scrollTo('sf_comment_list')",
                                     'script'   => true),
                               array('class'    => 'submit'));
          ?>
        <?php end_if_javascript(); ?>
        <noscript>
          <p><?php echo submit_tag(__('Post this comment'), array('class' => 'submit')) ?></p>
        </noscript>
      <?php else: ?>
        <?php echo submit_tag(__('Post this comment'), array('class' => 'submit')) ?>
      <?php endif; ?>
    </fieldset>
  </form>
<?php endif; ?>