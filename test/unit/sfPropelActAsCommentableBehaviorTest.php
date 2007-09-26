<?php
// test variables definition
define('TEST_CLASS', 'Post');

// initializes testing framework
$app = 'frontend';
include(dirname(__FILE__).'/../../../../test/bootstrap/functional.php');

// initialize database manager
$databaseManager = new sfDatabaseManager();
$databaseManager->initialize();
$con = Propel::getConnection();

// clean the database
CommentPeer::doDeleteAll();
call_user_func(array(_create_object()->getPeer(), 'doDeleteAll'));

// create a new test browser
$browser = new sfTestBrowser();
$browser->initialize();

// start tests
$t = new lime_test(8, new lime_output_color());


// these tests check for the comments attachement consistency
$t->diag('comments attachment consistency');

$object1 = _create_object();
$t->ok($object1->getComments() == array(), 'a new object has no comment.');
$object1->save();

$object1->addComment('One first comment.');
$object_comments = $object1->getComments();
$t->ok((count($object_comments) == 1) && ($object_comments[0]->getText() == 'One first comment.'), 'a saved object can get commented.');

$object1->addComment('One second comment.');
$t->ok($object1->getNbComments() == 2, 'one object can have several comments.');

$object2 = _create_object();
$object2->save();

$object2->addComment('One first comment on object2.');
$object1_comments = $object1->getComments();
$object2_comments = $object2->getComments();
$t->ok((count($object1_comments) == 2) && (count($object2_comments) == 1), 'one comment is only attached to one Propel object.');


// these tests check for other methods
$t->diag('comments manipulation methods');
CommentPeer::doDeleteAll();

$object1 = _create_object();
$object1->save();
$object1->addComment('One first comment.');
$object1->addComment('One second comment.');
$object_comments = $object1->getComments();
$nb_object_comments = $object1->getNbComments();
$t->ok(($nb_object_comments == count($object_comments)) && ($nb_object_comments == 2), 'getNbComments() returns the number of comments attached to the object when it has still not been saved.');

$object1->addComment('One third comment.');
$object_comments = $object1->getComments();
$nb_object_comments = $object1->getNbComments();
$t->ok(($nb_object_comments == count($object_comments)) && ($nb_object_comments == 3), 'getNbComments() returns the number of comments attached to the object, when it has been saved also.');

$object1->clearComments();
$t->ok($object1->getNbComments() === 0, 'comments on an object can be cleared using clearComments().');


// these tests check for comments retrieval methods
$t->diag('comments retrieval methods');
CommentPeer::doDeleteAll();

$object1 = _create_object();
$object1->save();
$object1->addComment('One first comment.');
$object1->addComment('One second comment.');
$asc_comments = $object1->getComments(array('order' => 'asc'));
$desc_comments = $object1->getComments(array('order' => 'desc'));
$t->ok(($asc_comments[0]->getText() == 'One first comment.') 
       && ($asc_comments[1]->getText() == 'One second comment.') 
       && ($desc_comments[1]->getText() == 'One first comment.') 
       && ($desc_comments[0]->getText() == 'One second comment.'), 'comments can be retrieved in a specific order.');


// test object creation
function _create_object()
{
  $classname = TEST_CLASS;

  if (!class_exists($classname))
  {
    throw new Exception(sprintf('Unknow class "%s"', $classname));
  }

  return new $classname();
}