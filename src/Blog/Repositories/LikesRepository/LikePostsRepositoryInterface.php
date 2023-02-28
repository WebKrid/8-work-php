<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\LikeRepository;

use GeekBrains\LevelTwo\Blog\LikePosts;
use GeekBrains\LevelTwo\Blog\UUID;

interface LikePostsRepositoryInterface
{
    public function save(LikePosts $like) : void;
    public function getByPostUuid(UUID $uuid) : array;
}