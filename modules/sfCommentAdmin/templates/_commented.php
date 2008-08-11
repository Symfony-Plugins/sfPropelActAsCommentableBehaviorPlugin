<?php
$model = $sf_comment->getCommentableModel();
$id = $sf_comment->getCommentableId();
$commented_object = sfPropelActAsCommentableToolkit::retrieveCommentableObject($model, $id);

if (is_callable(array($commented_object, 'getTitle')))
{
  $commented = $commented_object->getTitle();
}
elseif (is_callable(array($commented_object, 'getName')))
{
  $commented = $commented_object->getName();
}
elseif (is_callable(array($commented_object, 'toString')))
{
  $commented = $commented_object->toString();
}
else
{
  $commented = $model.' #'.$id;
}

if ('' == $commented)
{
  $commented = $model.' #'.$id;
}

echo $commented;