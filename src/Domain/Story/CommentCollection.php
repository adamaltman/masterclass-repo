<?php

namespace Masterclass\Domain\Story;

class CommentCollection
{
    protected $comments;

    public function __construct($comments)
    {
        foreach ($comments as $comment) {
            $this->addComment($comment);
        }
    }

    /**
     * @return Comment[]
     */
    public function getComments()
    {
        return $this->comments;
    }

    public function addComment(Comment $comment)
    {
        $this->comments[] = $comment;
    }
}
