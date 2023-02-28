<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\LikeRepository;

use GeekBrains\LevelTwo\Blog\Exceptions\InvalidArgumentException;
use GeekBrains\LevelTwo\Blog\Exceptions\LikesNotFoundException;
use GeekBrains\LevelTwo\Blog\LikeComments;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Blog\Exceptions\LikeAlreadyExists;


class SqliteLikeCommentsRepository implements LikeCommentsRepositoryInterface
{
    private \PDO $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function save(LikeComments $like): void
    {
        $statement = $this->connection->prepare('
            INSERT INTO likeComments (uuid, user_uuid, comment_uuid)
            VALUES (:uuid, :user_uuid, :comment_uuid)
        ');
        $statement->execute([
            // ':uuid' => (string)$like->uuid(),
            ':uuid'=> (string)$like->uuid(),
            ':user_uuid' => (string)$like->getUser_id(),
            ':comment_uuid' => (string)$like->getCommentId(),
        ]);
    }

    /**
     * @throws LikesNotFoundException
     * @throws InvalidArgumentException
     */
    public function getByCommentsUuid(UUID $uuid): array
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM likes WHERE comment_uuid = :uuid'
        );

        $statement->execute([
            'uuid' => (string)$uuid
        ]);

        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);

        if (!$result) {
            throw new LikesNotFoundException(
                'No likes to post with uuid = : ' . $uuid
            );
        }

        $likes = [];
        foreach ($result as $like) {
            $likes[] = new LikeComments(
                uuid: new UUID($like['uuid']),
                comment_id: new UUID($like['comment_uuid']),
                user_id: new UUID($like['user_uuid']),
            );
        }

        return $likes;
    }

    /**
     * @throws LikeAlreadyExists
     */
    public function checkUserLikeForCommentExists($commentUuid, $userUuid): void
    {
        $statement = $this->connection->prepare(
            'SELECT *
            FROM likeComments
            WHERE 
                comment_uuid = :commentUuid AND user_uuid = :userUuid'
        );

        $statement->execute(
            [
                ':commentUuid' => $commentUuid,
                ':userUuid' => $userUuid
            ]
        );

        $isExisted = $statement->fetch();

        if ($isExisted) {
            throw new LikeAlreadyExists(
                'The users like for this comment already exists'
            );
        }
    }
}