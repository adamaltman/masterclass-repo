<?php

namespace Masterclass\Domain\Story;


interface StoryDataStore
{

    public function getAllStories();

    /**
     * @param $storyId
     *
     * @return Story
     */
    public function loadStoryAggregateById($storyId);
    public function loadStoryById($storyId);

    /**
     * @param $headline
     * @param $url
     * @param $username
     * @return Story
     */
    public function createStory($headline, $url, $username);

    /**
     * @param Story $story
     * @return Story
     */
    public function persistStory(Story $story);

    public function getNextId();
}
