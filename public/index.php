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

$users = [
    ['firstName' => 'mike'],
    ['firstName' => 'mishel'],
    ['firstName' => 'adel'],
    ['firstName' => 'keks'],
    ['firstName' => 'kamila'],
];

//$app->get('/users', function(\Slim\Http\ServerRequest $request, $response) use ($users) {
//    $term = $request->getQueryParam('term');
//    $filteredUsers = [];
//    foreach ($users as $user) {
//        $pos = strpos($user['firstName'], $term);
//        if ($pos !== false) {
//            $filteredUsers[] = $user;
//        }
//    }
////    var_dump($result);
//    $params = [
//        'filteredUsers' => $filteredUsers,
//        'term' => $term,
//        ];
//    return $this->get('renderer')->render($response, 'users/index.phtml', $params);
//});

$app->get('/users/new', function ($request, $response) {
    $params = [
        'user' => ['name' => '', 'email' => '']
    ];
    return $this->get('renderer')->render($response, "users/create_user.phtml", $params);
});

$app->post('/users', function ($request, $response) {
    $user = $request->getParsedBodyParam('user');
    $errors = validate($user);
    if (count($errors) === 0) {
        $repo->save($user);
        return $response->withRedirect('/users', 302);
    }
    $params = [
        'user' => $user,
        'errors' => $errors
    ];
    return $this->get('renderer')->render($response, "users/new.phtml", $params);
});

$app->run();

function validate($user) {
    $errors = [];
    if(empty($user['name'])) {
        $errors[] = 'Вы не ввели имя';
    }
    if(empty($user['email'])) {
        $errors[] = 'Вы не ввели email';
    }
    return $errors;
}

function chek($user) {

}