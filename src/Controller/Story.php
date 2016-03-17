<?php

namespace Masterclass\Controller;

use Aura\View\View;
use Masterclass\Model\CommentMysqlDataStore;
use Masterclass\Model\StoryMysqlDataStore as StoryModel;
use Masterclass\Request;

class Story
{
    protected $comment;
    protected $story;
    protected $request;
    protected $view;

    public function __construct(
        CommentMysqlDataStore $comment,
        StoryModel $story,
        Request $request,
        View $view
    ) {
        $this->comment = $comment;
        $this->story = $story;
        $this->request = $request;
        $this->view = $view;
    }

    public function index()
    {
        $id = $this->request->getQueryParam('id');
        if (!$id) {
            header("Location: /");
            exit;
        }

        $story = $this->story->loadStoryById($id);

        if (empty($story)) {
            header("Location: /");
            exit;
        }

        $comments = $this->comment->getCommentsForStoryId($id);
        $comment_count = count($comments);

        $this->view->setLayout('layout');
        $this->view->setView('story');
        $this->view->setData(
            [
                'story' => $story,
                'comment_count' => $comment_count,
                'comments' => $comments,
                'authenticated' => $this->request->getSession()->get('AUTHENTICATED'),
            ]
        );
        echo $this->view->__invoke();
    }

    public function create()
    {
        if (!$this->request->getSession()->get('AUTHENTICATED')) {
            header("Location: /user/login");
            exit;
        }

        $error = '';
        if ($this->request->getPostParam('create') !== null) {
            $headline = $this->request->getPostParam('headline');
            $url = $this->request->getPostParam('url');

            if (empty($headline)
                || empty($url)
                || !$this->request->validateUrl($url)
            ) {
                $error = 'You did not fill in all the fields or the URL did not validate.';
            } else {
                $id = $this->story->createStory(
                    $headline,
                    $url,
                    $this->request->getSession()->get('username')
                );
                header("Location: /story/?id=$id");
                exit;
            }
        }

        $this->view->setLayout('layout');
        $this->view->setView('story-create');
        $this->view->setData(['error' => $error]);
        echo $this->view->__invoke();
    }

}
