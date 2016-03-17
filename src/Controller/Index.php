<?php

namespace Masterclass\Controller;

use Aura\View\View;
use Masterclass\Model\StoryMysqlDataStore;

class Index
{
    protected $model;
    protected $view;
    
    public function __construct(StoryMysqlDataStore $model, View $view)
    {
        $this->model = $model;
        $this->view = $view;
    }
    
    public function index()
    {
        $stories = $this->model->getAllStories();
        $this->view->setLayout('layout');
        $this->view->setView('story-list');
        $this->view->setData(['stories' => $stories]);
        echo $this->view->__invoke();
    }
}
