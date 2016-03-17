<?php

namespace Masterclass\Model;

use Masterclass\Db\DataStore;
use Masterclass\Db\NotFound;
use Masterclass\Domain\Story\Comment;
use Masterclass\Domain\Story\CommentCollection;
use Masterclass\Domain\Story\Story;
use Masterclass\Domain\Story\StoryDataStore;
use Masterclass\Domain\Story\StoryId;

final class StoryMysqlDataStore implements StoryDataStore
{
    protected $dataStore;

    public function __construct(DataStore $dataStore)
    {
        $this->dataStore = $dataStore;
    }

    public function getAllStories()
    {
        $sql = 'SELECT * FROM story ORDER BY created_on DESC';
        $stories = $this->dataStore->fetchAll($sql);

        foreach ($stories as $k => $story) {
            $comment_sql = 'SELECT count(*) as `count` FROM comment WHERE story_id = ?';
            $count = $this->dataStore->fetchOne($comment_sql, [$story['id']]);
            $stories[$k]['count'] = $count['count'];
        }

        return $stories;
    }

    public function loadStoryById($storyId)
    {
        $story_sql = 'SELECT * FROM story WHERE id = ?';

        return $this->dataStore->fetchOne($story_sql, [$storyId]);
    }

    /**
     * {@inheritdoc}
     */
    public function loadStoryAggregateById($storyId)
    {
        $story_sql = 'SELECT * FROM story WHERE id = ?';
        $storyData = $this->dataStore->fetchOne($story_sql, [$storyId]);
        $commentSql = 'SELECT * FROM comment WHERE story_id = ?';
        $commentsData = $this->dataStore->fetchAll($commentSql, [$storyId]);

        if ($storyData === false) {
            throw new NotFound('Story not found');
        }

        $comments = [];

        foreach ($commentsData as $commentData) {
            $comments[] = new Comment(
                $commentData['created_by'],
                \DateTime::createFromFormat("U", strtotime($commentData['created_on'])),
                new StoryId($commentData['story_id']),
                $commentData['comment']
            );
        }

        return new Story(
            new StoryId($storyData['id']),
            $storyData['headline'],
            $storyData['url'],
            $storyData['created_by'],
            \DateTime::createFromFormat("U", strtotime($storyData['created_on'])),
            new CommentCollection($comments)
            );
    }

    public function createStory($headline, $url, $username)
    {
        $sql = 'INSERT INTO story (headline, url, created_by, created_on) VALUES (?, ?, ?, NOW())';
        $this->dataStore->insert(
            $sql,
            [
                $headline,
                $url,
                $username,
            ]
        );

        return $this->loadStoryAggregateById($this->dataStore->lastInsertId());
    }

    public function persistStory(Story $story)
    {
        $sql = 'INSERT INTO story (headline, url, created_by, created_on) VALUES (?, ?, ?, NOW())';
        $this->dataStore->insert(
            $sql,
            [
                $story->getHeadline(),
                $story->getUrl(),
                $story->getCreatedBy(),
            ]
        );

        return $this->loadStoryAggregateById($this->dataStore->lastInsertId());
    }

    /**
     * This could be a problem with concurrency.
     * We could change to use a uuid4 snowflake as the id.
     *
     * @return mixed
     */
    public function getNextId()
    {
        return $this->dataStore->lastInsertId() + 1;
    }
}
