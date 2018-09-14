<?php

namespace Controller;

use Repository\UserRepository;
use Repository\CollectionRepository;
use Silex\Api\ControllerProviderInterface;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class UsersController
 * @package Controller
 */
class UsersController implements ControllerProviderInterface
{
    /**
     * Routing settings.
     *
     * @param \Silex\Application $app Silex application
     *
     * @return \Silex\ControllerCollection Result
     */
    public function connect(Application $app)
    {
        $controller = $app['controllers_factory'];
        $controller->match('/{page}', array($this, 'indexAction'))
            ->value('page', 1)
            ->bind('users');
        $controller->match('users_collection/{id}', [$this, 'collectionAction'])
            ->bind('users_collection');
        return $controller;
    }

    /**
     * Index action.
     * Funkcja wyświetlająca listę użytkowników
     * @param Application $app
     * @param int $page
     * @return mixed
     */
    public function indexAction(Application $app, $page = 1)
    {
        $usersRepository = new UserRepository($app['db']);
        $users = $usersRepository->findAll($page);

        return $app['twig']->render('users/list.html.twig', array('paginator' => $users));
    }

    /**
     * Funkcja wyświetlająca kolekcję danego użytkownika
     * @param Application $app
     * @param Request $request
     * @return mixed
     */
    public function collectionAction(Application $app, Request $request)
    {
        $gunsRepository = new CollectionRepository($app['db']);
        $usersRepository = new UserRepository($app['db']);
        $user = $usersRepository->findNameById($request->get('id'));
        $guns = $gunsRepository->findMyGuns($request->get('id'));

        return $app['twig']->render('users/collection.html.twig', array('guns' => $guns, 'user' => $user));
    }
}