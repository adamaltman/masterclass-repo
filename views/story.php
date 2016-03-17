<?php
/**
 * @var Masterclass\Domain\Story\Story $story
 */
$story = $this->story;
?>

<a class="headline" href="<?= $story->getUrl() ?>"><?= $story->getHeadline() ?></a><br />
<span class="details">
    <?= $story->getCreatedBy() ?> |
    <?= count($story->getComments()) ?> ' Comments |
    <?= $story->getCreatedOn()->format('n/j/Y g:i a') ?>
</span>

<?php if ($comments = $story->getComments()): ?>
<?php foreach ($comments as $comment): ?>

    <div class="comment">
    <span class="comment_details">
        <?= $comment->getCreatedBy() ?> |
        <?= $comment->getCreatedOn()->format('n/j/Y g:i a') ?>
    </span>
    <?= $comment->getText() ?>
</div>

<?php endforeach; ?>
<?php endif; ?>


<?php if ($this->authenticated): ?>
    <form method="post" action="/comment/create">
    <input type="hidden" name="story_id" value="<?= $story->getId()->getId() ?>" />
    <textarea cols="60" rows="6" name="comment"></textarea><br />
    <input type="submit" name="submit" value="Submit Comment" />
    </form>
<?php endif; ?>
