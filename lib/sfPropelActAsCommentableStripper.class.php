<?php
/*
 * This file is part of the sfPropelActAsCommentableBehavior package.
 *
 * (c) 2008 Xavier Lacot <xavier@lacot.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfPropelActAsCommentableBehavior stripper class
 *
 * @author Xavier Lacot
 */
class sfPropelActAsCommentableStripper
{
  static public function clean($text)
  {
    $allowed_html_tags = sfConfig::get('app_sfPropelActAsCommentableBehaviorPlugin_allowed_tags', array());
    require_once realpath(dirname(__FILE__).'/htmlpurifier-3.1.1-lite/library/HTMLPurifier.auto.php');
    $config = HTMLPurifier_Config::createDefault();
    $config->set('HTML', 'Doctype', 'XHTML 1.0 Strict');
    $config->set('HTML', 'Allowed', implode(',', array_keys($allowed_html_tags)));

    if (isset($allowed_html_tags['a']))
    {
      $config->set('HTML', 'AllowedAttributes', 'a.href');
      $config->set('AutoFormat', 'Linkify', true);
    }

    if (isset($allowed_html_tags['p']))
    {
      $config->set('AutoFormat', 'AutoParagraph', true);
    }

    $purifier = new HTMLPurifier($config);
    return $purifier->purify($text);
  }
}