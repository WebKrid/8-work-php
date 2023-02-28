<?php

namespace GeekBrains\LevelTwo\Http\Actions\Posts;

use GeekBrains\LevelTwo\Blog\Exceptions\AuthException;
use GeekBrains\LevelTwo\Blog\Exceptions\InvalidArgumentException;
use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepository\PostsRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Blog\Exceptions\HttpException;
use GeekBrains\LevelTwo\http\Actions\ActionInterface;
use GeekBrains\LevelTwo\Http\Auth\IdentificationInterface;
use GeekBrains\LevelTwo\Http\Auth\JsonBodyUsernameIdentification;
use GeekBrains\LevelTwo\http\ErrorResponse;
use GeekBrains\LevelTwo\http\Request;
use GeekBrains\LevelTwo\http\Response;
use GeekBrains\LevelTwo\http\SuccessfulResponse;
use Psr\Log\LoggerInterface;


class CreatePost implements ActionInterface
{
    public function __construct(
        // private UsersRepositoryInterface $usersRepository,
        private PostsRepositoryInterface $postsRepository,
        // Внедряем контракт логгера
        private LoggerInterface $logger,
        // Вместо контракта репозитория пользователей
        // внедряем контракт идентификации
        // private JsonBodyUsernameIdentification $identification,
        private IdentificationInterface $identification,

    )
    {
    }

    public function handle(Request $request): Response
    {
        try {
            // Идентифицируем пользователя -
            // автора статьи
            $user = $this->identification->user($request);
        } catch (AuthException $e) {
            return new ErrorResponse($e->getMessage());
        }

        $newPostUuid = UUID::random();

        try {
            $post = new Post(
                $newPostUuid,
                $user,
                $request->jsonBodyField('title'),
                $request->jsonBodyField('text'),
            );
        } catch (HttpException $exception) {
            return new ErrorResponse($exception->getMessage());
        }

        $this->postsRepository->save($post);
        // Логируем UUID новой статьи
        $this->logger->info("Post created: $newPostUuid");

        return new SuccessfulResponse([
            'uuid' => (string)$newPostUuid,
        ]);
    }
}


class BearerTokenAdificatorn implements TokenAuthenticationInterface
{
private const HEADER_PREFIX = 'Bearer ';
public function __construct(
private AuthTokensRepositoryInterface $authTokensRepository,
) {
}
public function user(Request $request): User
{
try {
$header = $request->header('Authorization');
} catch (HttpException $e) {
throw new AuthException($e->getMessage());
}
        if (!str_starts_with($header, self::HEADER_PREFIX)) {
            throw new AuthException("Malformed token: [$header]");
        }
        // Отрезаем префикс Bearer
        $token = mb_substr($header, strlen(self::HEADER_PREFIX));
        // Ищем токен в репозитории
        try {
            $TokenAuthenticationInterface = $this->authTokensRepository->get($token);
        } catch (AuthTokenNotFoundException) {
            throw new AuthException("Bad token: [$token]");
        }
     
        if ($TokenAuthenticationInterface->expiresOn() <= new DateTimeImmutable()) {
            throw new AuthException("Token expired: [$token]");
        }
        $userUuid = $TokenAuthenticationInterface->userUuid();
        // Ищем и возвращаем пользователя
        return $this->usersRepository->get($userUuid);
    }
}




class PostNumber extends Post :void {
...
    protected function configure(): void
{
$this
->setName('posts:delete')
->setDescription('Deletes a post')
->addArgument(
'uuid',
InputArgument::REQUIRED,
'UUID of a post to delete'
)
// Добавили опцию
->addOption(
// Имя опции
'check-existence',
// Сокращённое имя
'c',
// Опция не имеет значения
InputOption::VALUE_NONE,
// Описание
'Check if post actually exists',
);
}

protected
function execute(
    InputInterface  $input,
    OutputInterface $output,
): int
{
    ...
    $uuid = new UUID($input->getArgument('uuid'));
// Если опция проверки существования статьи установлена
    if ($input->getOption('check-existence')) {
        try {
// Пытаемся получить статью
            $this->postsRepository->get($uuid);
        } catch (PostNotFoundException $e) {
// Выходим, если статья не найдена
            $output->writeln($e->getMessage());
            return Command::FAILURE;
        }
    }
    $this->postsRepository->delete($uuid);
    $output->writeln("Post $uuid deleted");
    return Command::SUCCESS;
}