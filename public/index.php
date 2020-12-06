<?php

require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use DI\Container;

$container = new Container();
$container->set('renderer', function () {
    return new \Slim\Views\PhpRenderer(__DIR__ . '/../templates');
});
$app = AppFactory::createFromContainer($container);
$app->addErrorMiddleware(true, true, true);

//$app->get('/', function ($request, $response) {
//    $response->getBody()->write('Welcome to Slim!');
//    return $response;
//});

//$app->get('/users', function ($request, $response) {
//    return $response->write('GET /users');
//});
//
//$app->post('/users', function ($request, $response) {
//    return $response->withStatus(302);
//});
//
//$app->get('/courses/{id}', function ($request, $response, array $args) {
//    $id = $args['id'];
//    return $response->write("Course id: {$id}");
//});
//
//$app->get('/users/{id}', function ($request, $response, $args) {
//    $params = ['id' => $args['id'], 'nickname' => 'user-' . $args['id']];
//   return $this->get('renderer')->render($response, 'users/show.phtml', $params);
//});

$users = [
    ['firstName' => 'mike'],
    ['firstName' => 'mishel'],
    ['firstName' => 'adel'],
    ['firstName' => 'keks'],
    ['firstName' => 'kamila'],
];

$app->get('/users', function(\Slim\Http\ServerRequest $request, $response) use ($users) {
    $term = $request->getQueryParam('term');
    $filteredUsers = [];
    foreach ($users as $user) {
        $pos = strpos($user['firstName'], $term);
        if ($pos !== false) {
            $filteredUsers[] = $user;
        }
    }
//    var_dump($result);
    $params = [
        'filteredUsers' => $filteredUsers,
        'term' => $term,
        ];
    return $this->get('renderer')->render($response, 'users/index.phtml', $params);
});

$app->run();