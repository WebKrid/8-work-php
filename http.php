<?php


use GeekBrains\LevelTwo\Blog\Exceptions\AppException;
use GeekBrains\LevelTwo\Blog\Exceptions\HttpException;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepository\SqlitePostsRepository;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use GeekBrains\LevelTwo\Http\Actions\Likes\CreatePostLike;
use GeekBrains\LevelTwo\Http\Actions\Posts\CreatePost;
use GeekBrains\LevelTwo\Http\Actions\Posts\DeletePost;
use GeekBrains\LevelTwo\Http\Actions\Users\CreateUser;
use GeekBrains\LevelTwo\Http\Actions\Users\DeleteUser;
use GeekBrains\LevelTwo\Http\Actions\Users\FindByUsername;
use GeekBrains\LevelTwo\Http\ErrorResponse;
use GeekBrains\LevelTwo\Http\Request;
use GeekBrains\LevelTwo\Http\SuccessfulResponse;
use Psr\Log\LoggerInterface;

// Подключаем файл bootstrap.php
// и получаем настроенный контейнер
$container = require __DIR__ . '/bootstrap.php';

// Получаем объект логгера из контейнера
$logger = $container->get(LoggerInterface::class);


$request = new Request(
    $_GET,
    $_SERVER,
    file_get_contents('php://input'),
);
    
try {
    $path = $request->path();
    } catch (HttpException $e) {
    $logger->warning($e->getMessage());
    (new ErrorResponse)->send();
    return;
}

try {
    $method = $request->method();
    } catch (HttpException $e) {
    // Логируем сообщение с уровнем WARNING
    $logger->warning($e->getMessage());

    (new ErrorResponse)->send();
    return;
}


$routes = [
    'GET' => [
        '/users/show' => FindByUsername::class,
        '/posts/show' => FindByUuid::class,
    ],
    'POST' => [
        '/users/create' => CreateUser::class,
        '/posts/create' => CreatePost::class,
        'post-like/create' => CreatePostLike::class,
        'comment-like/create' => CreateCommentLike::class
    ],
    ' DELETE' => [
        '/posts' => DeletePost::class,
        '/users' => DeleteUser::class,
        '/posts' => BearerTokenAdificatorn::class,
    ],
];

if (!array_key_exists(BearerTokenAdificatorn) || !BearerTokenAdificatorn()) {
// Логируем сообщение с уровнем NOTICE
$message = "Route not found: $method $path";
$logger->notice($message);
(new ErrorResponse($message))->send();
return;
}


// Получаем имя класса действия для маршрута
$actionClassName = $routes[$method][$path];

// С помощью контейнера
// создаём объект нужного действия
$action = $container->get($actionClassName);
    
try {
    $response = $action->handle($request);
} catch (AppException $e) {
    // Логируем сообщение с уровнем WARNING
    // Логируем сообщение с уровнем ERROR
    $logger->error($e->getMessage(), ['exception' => $e]);

    (new ErrorResponse($e->getMessage()))->send();
    return;
}

$response->send();