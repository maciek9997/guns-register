<?php

namespace Controller;

use Repository\UserRepository;
use Repository\CollectionRepository;
use Silex\Api\ControllerProviderInterface;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

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
        $controller->match('/', array($this, 'indexAction'))
            ->bind('users');
        $controller->match('users_collection/{id}', [$this, 'collectionAction'])
            ->bind('users_collection');
        return $controller;
    }

    /**
     * Index action.
     *
     * @param \Silex\Application                        $app     Silex application
     * @param \Symfony\Component\HttpFoundation\Request $request Request object
     *
     * @return string Response
     */

    //Funkcja wyświetlająca listę użytkowników

    public function indexAction(Application $app, Request $request)
    {
        $usersRepository = new UserRepository($app['db']);
        $users = $usersRepository->findAll();

        return $app['twig']->render('users/list.html.twig', array('users' => $users));
    }

    //Funkcja wyświetlająca kolekcję danego użytkownika

    public function collectionAction(Application $app, Request $request)
    {
        $gunsRepository = new CollectionRepository($app['db']);
        $usersRepository = new UserRepository($app['db']);
        $user = $usersRepository->findNameById($request->get('id'));
        $guns = $gunsRepository->findMyGuns($request->get('id'));

        return $app['twig']->render('users/collection.html.twig', array('guns' => $guns, 'user' => $user));
    }
}