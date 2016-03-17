<?php

namespace Masterclass\Domain\Story\UseCase;


use Masterclass\Domain\Story\StoryDataStore;

class ViewStory
{
    protected $storyDataStore;

    public function __construct(StoryDataStore $storyDataStore)
    {
        $this->storyDataStore = $storyDataStore;
    }

    public function handle($id)
    {
        try {
            return $this->storyDataStore->loadStoryAggregateById($id);
        } catch (\Exception $e) {
            // @todo could be handled better (thrown to response to give 404)
            echo $e->getMessage();
            exit;
        }
    }
}
