<?php

namespace GeekBrains\LevelTwo\Http\Actions\Users;

use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Http\Actions\ActionInterface;
use GeekBrains\LevelTwo\Http\ErrorResponse;
use GeekBrains\LevelTwo\Http\SuccessfulResponse;
use GeekBrains\LevelTwo\http\Request;
use GeekBrains\LevelTwo\http\Response;

class DeleteUser implements ActionInterface
{
    public function __construct(
        private UsersRepositoryInterface $usersRepository
    )
    {
    }


    public function handle(Request $request): Response
    {
        try {
            $userUuid = $request->query('uuid');
            $this->usersRepository->get(new UUID($userUuid));

        } catch (UserNotFoundException $error) {
            return new ErrorResponse($error->getMessage());
        }

        $this->usersRepository->delete(new UUID($userUuid));

        return new SuccessfulResponse([
            'uuid' => $userUuid,
        ]);
    }
}