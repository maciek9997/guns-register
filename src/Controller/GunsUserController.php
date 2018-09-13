<?php

namespace Controller;

use Repository\CollectionRepository;
use Repository\CommentsRepository;
use Repository\UserRepository;
use Silex\Api\ControllerProviderInterface;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Repository\GunsRepository;
use Repository\DictionaryRepository;
use Form\CommentForm;

/**
 * Class GunsUserController
 * @package Controller
 */
class GunsUserController implements ControllerProviderInterface
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
        $controller->match('list', [$this, 'listAction'])
            ->bind('user_guns_list');
        $controller->match('guns_show/{id}', [$this, 'showAction'])
            ->bind('user_guns_show');
        $controller->match('collection', [$this, 'collectionAction'])
            ->bind('user_guns_collection');
        $controller->match('add/{id}', [$this, 'addAction'])
            ->bind('user_guns_add');
        $controller->match('delete/{id}', [$this, 'deleteAction'])
            ->bind('user_guns_delete');

        return $controller;
    }

    /**
     * @param Application $app
     * @param Request $request
     * @return mixed
     * Funkcja wyświetlania dostępnej w rejestrze broni
     */
    public function listAction(Application $app, Request $request)
    {
        $gunsRepository = new GunsRepository($app['db']);
        $guns = $gunsRepository->findAllGuns();

        return $app['twig']->render('user/guns/list.html.twig', array('guns' => $guns));
    }

    /**
     * @param Application $app
     * @param Request $request
     * @return mixed
     * @throws \Doctrine\DBAL\DBALException
     * Funkcja podglądu wybranej broni
     */
    public function showAction(Application $app, Request $request)
    {
        $gunsRepository = new GunsRepository($app['db']);
        $gun = $gunsRepository->findGunById($request->get('id'));
        $dictionary = new DictionaryRepository($app['db']);
        $commentsRepository = new CommentsRepository($app['db']);

        $dictionaryList = $dictionary->getAllTypes();

        $form = $app['form.factory']->createBuilder(CommentForm::class)->getForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
            $userToken = $app['security.token_storage']->getToken();
            $userRep = new UserRepository($app['db']);
            $user = $userRep->getUserByLogin($userToken->getUser()->getUserName());
            $commentsRepository->addComment($user['id'],$request->get('id'),$form->getData());

            $app['session']->getFlashBag()->add(
                'messages',
                [
                    'type' => 'success',
                    'message' => 'message.comment_add_success',
                ]
            );
        }

        $comments = $commentsRepository->findComments($request->get('id'));
        $this->view['form'] = $form->createView();

        return $app['twig']->render('user/guns/show.html.twig', array(
            'gun' => $gun,
            'dictionary' => $dictionaryList,
            'form' => $form->createView(),
            'comments' => $comments
        ));
    }

    /**
     * @param Application $app
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Doctrine\DBAL\DBALException
     * Funkcja dodania broni do kolekcji
     */
    public function addAction(Application $app, Request $request)
    {
        $userToken = $app['security.token_storage']->getToken();
        $userRep = new UserRepository($app['db']);
        $user = $userRep->getUserByLogin($userToken->getUser()->getUserName());
        $collectionRep = new CollectionRepository($app['db']);
        $exist = $collectionRep->findisExist($user['id'], $request->get('id'));

        if (!$exist) {
            $collectionRep->addGun($user['id'], $request->get('id'));
            $app['session']->getFlashBag()->add(
                'messages',
                [
                    'type' => 'success',
                    'message' => 'message.gun_add_success',
                ]
            );
        } else {
            $app['session']->getFlashBag()->add(
                'messages',
                [
                    'type' => 'info',
                    'message' => 'message.is_in_collection',
                ]
            );
        }

        return $app->redirect(
            $app['url_generator']->generate('user_guns_collection'),
            301
        );
    }


    /**
     * @param Application $app
     * @param Request $request
     * @return mixed
     * @throws \Doctrine\DBAL\DBALException
     * Funkcja wyświetlająca kolekcję danego użytkownika
     */
    public function collectionAction(Application $app, Request $request)
    {
        $userToken = $app['security.token_storage']->getToken();
        $userRep = new UserRepository($app['db']);
        $user = $userRep->getUserByLogin($userToken->getUser()->getUserName());
        $gunsRepository = new CollectionRepository($app['db']);
        $guns = $gunsRepository->findMyGuns($user['id']);

        return $app['twig']->render('user/guns/collection.html.twig', array('guns' => $guns));
    }

    /**
     * @param Application $app
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Doctrine\DBAL\DBALException
     * Funkcja usuwająca daną broń z kolekcji danego użytkownika
     */
    public function deleteAction(Application $app, Request $request)
    {
        $userToken = $app['security.token_storage']->getToken();
        $userRep = new UserRepository($app['db']);
        $user = $userRep->getUserByLogin($userToken->getUser()->getUserName());
        $gunsRepository = new CollectionRepository($app['db']);
        $gunsRepository->deleteGun($request->get('id'), $user['id']);

        $app['session']->getFlashBag()->add(
            'messages',
            [
                'type'    => 'success',
                'message' => 'message.gun_delete_success',
            ]
        );

        return $app->redirect(
            $app['url_generator']->generate('user_guns_collection'),
            301
        );
    }


}