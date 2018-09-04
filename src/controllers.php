<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Controller\HomeController;
use Controller\GunsController;
use Controller\AuthController;
use Controller\GunsUserController;
use Controller\UsersController;

$app->mount('/', new HomeController());

$app->error(function (\Exception $e, Request $request, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    // 404.html, or 40x.html, or 4xx.html, or error.html
    $templates = array(
        'errors/'.$code.'.html.twig',
        'errors/'.substr($code, 0, 2).'x.html.twig',
        'errors/'.substr($code, 0, 1).'xx.html.twig',
        'errors/default.html.twig',
    );

    return new Response($app['twig']->resolveTemplate($templates)->render(array('code' => $code)), $code);
});

//ALL
$app->mount('/auth', new AuthController());

//ADMIN
$app->mount('/admin/guns', new GunsController());
$app->mount('/admin/users', new UsersController());

//USER
$app->mount('/user/guns', new GunsUserController());

