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

$filePath = 'public/dataBase.csv';

#редирект с корня автоматом на вторизацию
$app->get('/', function ($request, $response) {
    return $response->withRedirect('/users/autorization');
});

$app->get('/users/autorization', function ($request, $response) {
    $params = [
        'user' => ['name' => '', 'email' => ''],
        'errors' => [],
    ];
    return $this->get('renderer')->render($response, "users/autorization.phtml", $params);
});

$app->post('/users', function ($request, $response) {
        $user = $request->getParsedBodyParam('user');
        $errors = validate($user);
        if(check() === true) {
            return $response->withRedirect('/users');
        }
        $params = [
            'user' => $user,
            'errors' => $errors,
        ];
        return $this->get('renderer')->render($response, "users/autorization.phtml", $params);
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

function parser($filePath)
{
    $rows = array_map('str_getcsv', $filePath);
    $header = array_shift($rows);
    $csv = [];
    foreach ($rows as $row) {
        $csv[] = array_combine($header, $row);
    }
    return $csv;
}

function check($csv, $user) {
    foreach ($csv as $client) {
        if ($user['name'] === $client['name'] && $user['email'] === $client['email']) {
            return true;
        } else {
            return false;
        }
    }
}







