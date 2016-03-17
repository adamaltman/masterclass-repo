<?php

namespace Masterclass\Domain\Story\UseCase;

use Masterclass\Domain\Story\CommentCollection;
use Masterclass\Domain\Story\Story;
use Masterclass\Domain\Story\StoryDataStore;
use Masterclass\Domain\Story\StoryId;

class CreateStory
{
    protected $storyDataStore;

    public function __construct(StoryDataStore $storyDataStore)
    {
        $this->storyDataStore = $storyDataStore;
    }

    public function handle($headline, $url, $createdBy)
    {
        try {
            $story = new Story(
                new StoryId($this->storyDataStore->getNextId()),
                $headline,
                $url,
                $createdBy,
                new \DateTime(),
                new CommentCollection([])
            );
            return $this->storyDataStore->persistStory($story);
        } catch (\Exception $e) {
            // @todo could be handled better (thrown to response to give 404)
            echo $e->getMessage();
            exit;
        }
    }
}
