<?php
/**
 * Users controller.
 * Kontroler wyświetlania użytkowników i zarządzania nimi przez administratora
 */

/**
 * This file is part of the Symfony package.
 */
namespace Controller;

use Repository\UserRepository;
use Repository\CollectionRepository;
use Silex\Api\ControllerProviderInterface;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Form\ChangePasswordForm;
use Form\ChangeRoleForm;

/**
 * Class UsersController
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
        $controller->match('users_password/{id}', [$this, 'passwordAction'])
            ->bind('users_password');
        $controller->match('users_role/{id}', [$this, 'roleAction'])
            ->bind('users_role');

        return $controller;
    }

    /**
     * Index action.
     * Funkcja wyświetlająca listę użytkowników
     * @param Application $app
     * @param int         $page
     *
     * @return mixed
     */
    public function indexAction(Application $app, $page = 1)
    {
        $usersRepository = new UserRepository($app['db']);
        $users = $usersRepository->findAll($page);
        $pages = $usersRepository->countAllPages();

        if ($page > $pages) {
            return $app->redirect(
                $app['url_generator']->generate('users'),
                301
            );
        }

        return $app['twig']->render('users/list.html.twig', array('paginator' => $users));
    }

    /**
     * Funkcja wyświetlająca kolekcję danego użytkownika
     * @param Application $app
     * @param Request     $request
     *
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

    /**
     * Funkcja pozwalająca na zmianę hasła użytkownikowi przez administratora
     * @param Application $app
     * @param Request     $request
     *
     * @return mixed
     */
    public function passwordAction(Application $app, Request $request)
    {
        $form = $app['form.factory']->createBuilder(ChangePasswordForm::class)->getForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $pass = $app['security.encoder.bcrypt']->encodePassword($data['password'], '');
            $conn = $app['db'];
            $conn->executeUpdate('UPDATE users SET password = ? WHERE id = ?', array($pass, $request->get('id')));
            $app['session']->getFlashBag()->add(
                'messages',
                [
                    'type'    => 'success',
                    'message' => 'message.change_password_success',
                ]
            );
        }

        return $app['twig']->render(
            'users/changePassword.html.twig',
            [
                'form' => $form->createView(),
                'userId' => $request->get('id'),
            ]
        );
    }

    /**
     * Funkcja pozwalająca na zmianę roli użytkownikowi przez administratora
     * @param Application $app
     * @param Request     $request
     *
     * @return mixed
     */
    public function roleAction(Application $app, Request $request)
    {
        $usersRepository = new UserRepository($app['db']);
        $role = $usersRepository->findRoleById($request->get('id'));

        $form = $app['form.factory']->createBuilder(ChangeRoleForm::class, ['role' => $role['role_id']])->getForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $conn = $app['db'];
            $conn->executeUpdate('UPDATE users SET role_id = ? WHERE id = ?', array($data['role'], $request->get('id')));
            $app['session']->getFlashBag()->add(
                'messages',
                [
                    'type'    => 'success',
                    'message' => 'message.change_role_success',
                ]
            );
        }

        return $app['twig']->render(
            'users/changeRole.html.twig',
            [
                'form' => $form->createView(),
                'userId' => $request->get('id'),
            ]
        );
    }
}
