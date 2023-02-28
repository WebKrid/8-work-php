<?php
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepository\PostsRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Blog\Exceptions\HttpException;
use GeekBrains\LevelTwo\http\Actions\ActionInterface;;
use GeekBrains\LevelTwo\http\Request;
use GeekBrains\LevelTwo\http\Response;
use Psr\Log\LoggerInterface;
use GeekBrains\LevelTwo\Blog\Http\Actions\Posts\CreatePost;
use GeekBrains\LevelTwo\Repositories\CommentsRepository\authTokensRepository;
class LikePosts
{
    public function __construct(
        private UUID $uuid,
        private UUID $post_id,
        private UUID $user_id,
    )
    {
    }

    /**
     * @return UUID
     */
    public function uuid(): UUID
    {
        return $this->uuid;
    }

    /**
     * @param UUID $uuid
     */
    public function setUuid(UUID $uuid): void
    {
        $this->uuid = $uuid;
    }

    /**
     * @return UUID
     */
    public function getPostId(): UUID
    {
        return $this->post_id;
    }

    /**
     * @param UUID $post_id
     */
    public function setPostId(UUID $post_id): void
    {
        $this->post_id = $post_id;
    }

    /**
     * @return UUID
     */
    public function getUserId(): UUID
    {
        return $this->user_id;
    }

    /**
     * @param UUID $user_id
     */
    public function setUserId(UUID $user_id): void
    {
        $this->user_id = $user_id;
    }

}