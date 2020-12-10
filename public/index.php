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
        if(cheсker($user) !== false) {
            return $response->withRedirect('/users', 302);
        }
        $params = [
            'user' => $user,
            'errors' => $errors,
        ];
        return $this->get('renderer')->render($response, "users/autorization.phtml", $params);
    });

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

    function cheсker($user) {
        $rows = array_map('str_getcsv', file('public/dataBase.csv'));
        $header = array_shift($rows);
        $csv = [];
        foreach($rows as $row) {
            $csv[] = array_combine($header, $row);
        }

        foreach ($csv as $client) {
            if($user['name'] !== $client['name'] || $user['email'] !== $client['email']) {
                return false;
                continue;
            }
            if ($user['name'] === $client['name'] && $user['email'] === $client['email']) {
                return true;
            }
        }
    }

$app->run();

