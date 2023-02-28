<?php
use GeekBrains\LevelTwo\Blog\Container\DIContainer;
use GeekBrains\LevelTwo\Blog\Repositories\LikeRepository\LikeCommentsRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Repositories\LikeRepository\LikePostsRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Repositories\LikeRepository\SqliteLikeCommentsRepository;
use GeekBrains\LevelTwo\Blog\Repositories\LikeRepository\SqliteLikePostsRepository;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepository\PostsRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepository\SqlitePostsRepository;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Dotenv\Dotenv;
use GeekBrains\LevelTwo\Http\Auth\JsonBodyUsernameIdentification;
use GeekBrains\LevelTwo\Http\Auth\IdentificationInterface;

require_once __DIR__ . '/vendor/autoload.php';

// Загружаем переменные окружения из файла .env
Dotenv::createImmutable(__DIR__)->safeLoad();

$container = new DIContainer();


// 1. подключение к БД
$container->bind(
PDO::class,
// Берём путь до файла базы данных SQLite
// из переменной окружения SQLITE_DB_PATH
new PDO('sqlite:' . __DIR__ . '/' . $_ENV['SQLITE_DB_PATH'])

// new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
);

// Выносим объект логгера в переменную
$logger = (new Logger('blog'));

// Включаем логирование в файлы,
// если переменная окружения LOG_TO_FILES
// содержит значение 'yes'
if ('yes' === $_ENV['LOG_TO_FILES']) {
    $logger
    ->pushHandler(new StreamHandler(
        __DIR__ . '/logs/blog.log'
        ))
        ->pushHandler(new StreamHandler(
        __DIR__ . '/logs/blog.error.log',
        level: Logger::ERROR,
        bubble: false,
        ));
        
}

$container->bind(
    IdentificationInterface::class,
    JsonBodyUsernameIdentification::class
);
    

// Добавляем логгер в контейнер
$container->bind(
    $logger
);

$container->bind(
    LikePostsRepositoryInterface::class,
    SqliteLikePostsRepository::class
);

$container->bind(
    LikeCommentsRepositoryInterface::class,
    TokenAuthenticationInterface::class,
    SqliteLikeCommentsRepository::class,
);

// 2. репозиторий статей
$container->bind(
    CreatePost:class,
PostsRepositoryInterface::class,
SqlitePostsRepository::class
);

// 3. репозиторий пользователей
$container->bind(
UsersRepositoryInterface::class,
SqliteUsersRepository::class,

);

// Возвращаем объект контейнера
return $container;


$faker = new \Faker\Generator();
$faker->addProvider(new Person($faker));
$faker->addProvider(new Text($faker));
$faker->addProvider(new Internet($faker));
$faker->addProvider(new Lorem($faker));

$container->bind(
    \Faker\Generator::class,
    $faker
);

return $conteiner;