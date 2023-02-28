<?php

namespace GeekBrains\LevelTwo\Http\Actions\Posts;

use GeekBrains\LevelTwo\Blog\Exceptions\PostNotFoundException;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepository\PostsRepositoryInterface;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Http\Actions\ActionInterface;
use GeekBrains\LevelTwo\Http\ErrorResponse;
use GeekBrains\LevelTwo\Http\SuccessfulResponse;
use GeekBrains\LevelTwo\http\Request;
use GeekBrains\LevelTwo\http\Response;

class DeletePost implements ActionInterface
{
    public function __construct(
        private PostsRepositoryInterface $postsRepository
    )
    {
    }


    public function handle(Request $request): Response
    {
        try {
            $postUuid = $request->query('uuid');
            $this->postsRepository->get(new UUID($postUuid));

        } catch (PostNotFoundException $error) {
            return new ErrorResponse($error->getMessage());
        }

        $this->postsRepository->delete(new UUID($postUuid));

        return new SuccessfulResponse([
            'uuid' => $postUuid,
        ]);
    }
}

class DeletePost extends Post
{
    public function __construct(


private PostsRepositoryInterface $postsRepository,
) {
parent::__construct();
}
// Конфигурируем команду
protected function configure(): void
{
    $this
        ->setName('posts:delete')
        ->setDescription('Deletes a post')
        ->addArgument(
            'uuid',
            InputArgument::REQUIRED,
            'UUID of a post to delete'
        );
}
protected function execute(
    InputInterface $input,
    OutputInterface $output,
): int {
        'Delete post [Y/n]? ',
        false
    );
    if (!$this->getHelper('question')
        ->ask($input, $output, $question)
    ) {
        return Command::SUCCESS;
    }
    $uuid = new UUID($input->getArgument('uuid'));
    $this->postsRepository->delete($uuid);
    $output->writeln("Post $uuid deleted");
    return Command::SUCCESS;
}
}
