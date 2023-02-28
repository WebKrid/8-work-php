<?php
namespace GeekBrains\LevelTwo\Blog;

class LikeComments
{
    public function __construct(
        private UUID $uuid,
        private UUID $comment_id,
        private UUID $user_id,
    )
    {
    }

        /**
         * @return UUID
         */ 
        public function uuid():UUID
        {
            return $this->uuid;
        }

        /**
         * @param UUID $uuid
         */ 
        public function setUuid($uuid):void
        {
            $this->uuid = $uuid;
        }

        /**
         * @return UUID
         */ 
        public function getCommentId():UUID
        {
            return $this->comment_id;
        }

        /**
         * @param UUID $post_id
         */ 
        public function setCommentId($comment_id):void
        {
            $this->comment_id = $comment_id;
        }

        /**
         * @return UUID
         */ 
        public function getUser_id():UUID
        {
            return $this->user_id;
        }

        /**
         *
         * @param UUID $user_id
         */ 
        public function setUser_id($user_id):void
        {
            $this->user_id = $user_id;
        }
}