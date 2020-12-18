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

$filePath = file('public/dataBase.csv');

$app->post('/users', function ($request, $response) use ($filePath){
    $user = $request->getParsedBodyParam('user');
    $errors = validate($user);
    print_r($errors);
    $csv = parser($filePath);
    $check = check($csv, $user);
    if(check($csv, $user) === 1) {
        return $response->withRedirect('/users');
    }
    $params = [
        'user' => $user,
        'errors' => $errors,
    ];
        return $this->get('renderer')->render($response, "users/index.phtml", $params);
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
//    print_r($user);
    $result = '';
    foreach ($csv as $client) {
        if ($user['name'] === $client['name'] && $user['email'] === $client['email']) {
            $result = '1';
        }
        if ($user['name'] !== $client['name'] || $user['email'] !== $client['email']) {
            $result = '0';
        }
    }
    return $result;
}







