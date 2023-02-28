<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\LikeRepository;

use GeekBrains\LevelTwo\Blog\LikeComments;
use GeekBrains\LevelTwo\Blog\UUID;

interface LikeCommentsRepositoryInterface
{
    public function save(LikeComments $like) : void;
    public function getByCommentsUuid(UUID $uuid) : array;
}