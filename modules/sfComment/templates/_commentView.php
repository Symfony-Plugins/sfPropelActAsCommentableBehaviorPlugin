<?php use_helper('I18N'); ?>
<?php use_helper('Date'); ?>
<div class="sf_comment" id="sf_comment_<?php echo $comment['Id'] ?>">
  <p class="sf_comment_info">
    <a href="#sf_comment_<?php echo $comment['Id'] ?>"><span class="sf_comment_author">
      <?php if (!is_null($comment['AuthorId'])): ?>
        <?php
        include_component('sfComment',
                          'author',
                          array('author_id'    => $comment['AuthorId'],
                                'sf_cache_key' => $comment['AuthorId']));
        ?><?php else: ?><?php echo $comment['AuthorName'] ?><?php endif; ?></span>,
    <?php echo __('%1% ago', array('%1%' => distance_of_time_in_words(strtotime($comment['CreatedAt'])))) ?></a>
  </p>
  <p class="sf_comment_text">
    <?php echo $comment['Text']; ?>
  </p>
</div>