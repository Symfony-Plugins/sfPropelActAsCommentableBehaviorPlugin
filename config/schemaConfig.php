<?php

// Default values
$config = array(
  'connection'     => 'propel',
  'user_table'     => 'sf_guard_user',
  'user_id'        => 'id',
  'user_class'     => 'sfGuardUser',
  'comment_table'  => 'sf_comment',
);

// Check custom project values in my_project/config/sfCommentPlugin.yml
if(is_readable($config_file = sfConfig::get('sf_config_dir').'/sfPropelActAsCommentableBehaviorPlugin.yml'))
{
  $user_config = sfYaml::load($config_file);
  if(isset($user_config['schema']))
  {
    $config = array_merge($config, $user_config['schema']);
  }
}