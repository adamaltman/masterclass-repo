<?php

namespace Masterclass\Controller;

use Masterclass\Model\CommentMysqlDataStore as CommentModel;
use Masterclass\Request;

class Comment
{
    protected $commentModel;
    protected $request;

    public function __construct(CommentModel $comment, Request $request)
    {
        if (!$request->getSession()->get('AUTHENTICATED')) {
            header("Location: /");
            exit;
        }
        $this->commentModel = $comment;
        $this->request = $request;
    }

    public function create()
    {
        $this->commentModel->addComment(
            $this->request->getSession()->get('username'),
            $this->request->getPostParam('story_id'),
            $this->request->getSanitizedValue($this->request->getPostParam('comment'))
        );

        header("Location: /story/?id=" . $_POST['story_id']);
    }
}
