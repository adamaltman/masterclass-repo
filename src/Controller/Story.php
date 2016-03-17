<?php

namespace Masterclass\Controller;

use Aura\View\View;
use Masterclass\Domain\Story\UseCase\CreateStory;
use Masterclass\Domain\Story\UseCase\ViewStory;
use Masterclass\Request;


class Story
{
    protected $comment;
    protected $story;
    protected $request;
    protected $view;
    protected $viewStory;
    protected $createStory;

    public function __construct(
        Request $request,
        View $view,
        ViewStory $viewStory,
        CreateStory $createStory
    ) {
        $this->request = $request;
        $this->view = $view;
        $this->viewStory = $viewStory;
        $this->createStory = $createStory;
    }

    public function index()
    {
        $id = $this->request->getQueryParam('id');
        if (!$id) {
            header("Location: /");
            exit;
        }

        $story = $this->viewStory->handle($id);

        $this->view->setLayout('layout');
        $this->view->setView('story');
        $this->view->setData(
            [
                'story' => $story,
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
                $story = $this->createStory->handle($headline, $url, $this->request->getSession()->get('username'));
                $id = $story->getId()->asInt();
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
